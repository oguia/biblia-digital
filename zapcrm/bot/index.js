const { default: makeWASocket, useMultiFileAuthState, DisconnectReason, fetchLatestBaileysVersion } = require('@whiskeysockets/baileys');
const pino = require('pino');
const express = require('express');
const cors = require('cors');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');
const axios = require('axios');
const http = require('http');

const app = express();
app.use(cors());
app.use(express.json());

let sock;

// Depending on if run from esbuild or dev, the qr code path changes
// When compiled via esbuild, this script runs in api/bot/bot.cjs
// So __dirname is api/bot. We want api/qr.png
const IS_COMPILED = __dirname.endsWith('bot');
const API_DIR = IS_COMPILED ? path.join(__dirname, '..') : path.join(__dirname, '../api');

const QR_PATH = path.join(API_DIR, 'qr.png');
const PORT_FILE = path.join(API_DIR, 'bot_port.txt');
const SESSION_DIR = path.join(__dirname, 'auth_info_baileys');

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState(SESSION_DIR);
    const { version, isLatest } = await fetchLatestBaileysVersion();

    console.log(`Using WA v${version.join('.')}, isLatest: ${isLatest}`);

    sock = makeWASocket({
        version,
        logger: pino({ level: 'silent' }), // reduce console spam on hostinger
        printQRInTerminal: false,
        auth: state,
        browser: ['ZapCRM', 'Chrome', '1.0.0']
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            console.log('Got QR code, saving to ' + QR_PATH);
            try {
                // Save QR to file so PHP can serve it in the admin panel
                await qrcode.toFile(QR_PATH, qr);
            } catch (err) {
                console.error("Failed to generate QR code image", err);
            }
        }

        if (connection === 'close') {
            const shouldReconnect = lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('Connection closed due to ', lastDisconnect.error, ', reconnecting ', shouldReconnect);

            // Delete QR image if disconnected
            if (fs.existsSync(QR_PATH)) {
                fs.unlinkSync(QR_PATH);
            }

            if (shouldReconnect) {
                connectToWhatsApp();
            } else {
                console.log('Logged out. Please delete the auth_info_baileys folder and restart to get a new QR code.');
                // Delete session
                fs.rmSync(SESSION_DIR, { recursive: true, force: true });
            }
        } else if (connection === 'open') {
            console.log('WhatsApp connection opened!');
            // Delete QR code file now that we are connected
            if (fs.existsSync(QR_PATH)) {
                fs.unlinkSync(QR_PATH);
            }
        }
    });

    sock.ev.on('messages.upsert', async m => {
        const msg = m.messages[0];
        if (!msg.message || msg.key.fromMe) return;

        // Parse message
        let text = '';

        if (msg.message.conversation) {
            text = msg.message.conversation;
        } else if (msg.message.extendedTextMessage && msg.message.extendedTextMessage.text) {
            text = msg.message.extendedTextMessage.text;
        } else {
            // Not a text message we care about (images, audio, etc) for this simple bot
            return;
        }

        const senderId = msg.key.remoteJid;
        const pushName = msg.pushName || 'Client';

        console.log(`Received message from ${senderId}: ${text}`);

        // Send to PHP Webhook
        try {
            const webhookUrl = process.env.WEBHOOK_URL || 'http://127.0.0.1:8000/zapcrm/api/index.php/webhook';
            const botToken = process.env.BOT_TOKEN || '';

            const response = await axios.post(webhookUrl, {
                from: senderId.replace('@s.whatsapp.net', ''),
                pushName: pushName,
                text: text
            }, {
                headers: {
                    'Authorization': `Bearer ${botToken}`
                }
            });

            if (response.data && response.data.reply && response.data.message) {
                // PHP webhook asked us to send a reply (Gemini generated it)
                await sock.sendMessage(senderId, { text: response.data.message });
                console.log("Sent reply via webhook command:", response.data.message);
            }
        } catch (error) {
            console.error('Error sending message to PHP webhook:', error.message);
        }
    });
}

// HTTP API endpoints for PHP CRM to trigger sending messages

// Middleware to verify calls from PHP API
app.use((req, res, next) => {
    const authHeader = req.headers.authorization || '';
    const botToken = process.env.BOT_TOKEN || '';
    if (botToken && authHeader !== `Bearer ${botToken}`) {
        return res.status(401).json({ error: 'Unauthorized local call' });
    }
    next();
});

app.get('/ping', (req, res) => {
    res.json({ status: 'ok', connected: !!(sock && sock.user) });
});

app.post('/send', async (req, res) => {
    try {
        const { to, message } = req.body;
        if (!to || !message) {
            return res.status(400).json({ error: 'Missing to or message' });
        }

        if (!sock) {
            return res.status(500).json({ error: 'WhatsApp not connected' });
        }

        const jid = to.includes('@s.whatsapp.net') ? to : `${to}@s.whatsapp.net`;
        await sock.sendMessage(jid, { text: message });

        res.json({ success: true });
    } catch (error) {
        console.error("Failed to send manual message", error);
        res.status(500).json({ error: error.message });
    }
});

// Start the server with dynamic port to prevent EADDRINUSE
const server = http.createServer(app);
server.listen(0, '127.0.0.1', () => {
    const port = server.address().port;
    console.log(`Bot Server running on http://127.0.0.1:${port}`);

    // Write port to file for PHP to read
    fs.writeFileSync(PORT_FILE, port.toString());

    connectToWhatsApp();
});
