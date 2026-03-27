const { default: makeWASocket, useMultiFileAuthState, DisconnectReason, fetchLatestBaileysVersion } = require('@whiskeysockets/baileys');
const pino = require('pino');
const express = require('express');
const cors = require('cors');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');
const axios = require('axios');
const http = require('http');

// Support reading .env locally
require('dotenv').config();

const app = express();
app.use(cors());
app.use(express.json());

let sock;
let currentQR = null;

// The Hostinger Node.js app environment uses process.env.PORT
const PORT = process.env.PORT || 3000;
const SESSION_DIR = path.join(__dirname, 'auth_info_baileys');

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState(SESSION_DIR);
    const { version, isLatest } = await fetchLatestBaileysVersion();

    console.log(`Using WA v${version.join('.')}, isLatest: ${isLatest}`);

    sock = makeWASocket({
        version,
        logger: pino({ level: 'silent' }), // reduce console spam on hostinger
        printQRInTerminal: true,
        auth: state,
        browser: ['ZapCRM Bot', 'Chrome', '1.0.0']
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            console.log('Got new QR Code');
            try {
                // Generate base64 Data URI for HTML display
                currentQR = await qrcode.toDataURL(qr);
            } catch (err) {
                console.error("Failed to generate QR code image", err);
            }
        }

        if (connection === 'close') {
            const shouldReconnect = lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('Connection closed due to ', lastDisconnect.error, ', reconnecting ', shouldReconnect);

            if (shouldReconnect) {
                connectToWhatsApp();
            } else {
                console.log('Logged out. Session deleted.');
                fs.rmSync(SESSION_DIR, { recursive: true, force: true });
                currentQR = null;
                // Wait 3 seconds and restart QR generation
                setTimeout(connectToWhatsApp, 3000);
            }
        } else if (connection === 'open') {
            console.log('WhatsApp connection opened!');
            currentQR = null; // Clear QR code when connected
        }
    });

    sock.ev.on('messages.upsert', async m => {
        const msg = m.messages[0];
        if (!msg.message || msg.key.fromMe) return;

        // Parse message robustly
        let text = '';
        if (msg.message.conversation) {
            text = msg.message.conversation;
        } else if (msg.message.extendedTextMessage && msg.message.extendedTextMessage.text) {
            text = msg.message.extendedTextMessage.text;
        } else {
            return; // Ignore images/audio for now
        }

        const senderId = msg.key.remoteJid;
        const pushName = msg.pushName || 'Client';

        console.log(`Received message from ${senderId}: ${text}`);

        // Send to PHP Webhook (configured via .env on Hostinger)
        try {
            const webhookUrl = process.env.WEBHOOK_URL;
            const botToken = process.env.BOT_TOKEN;

            if (!webhookUrl || !botToken) {
                console.error('WEBHOOK_URL or BOT_TOKEN missing in .env');
                return;
            }

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
                await sock.sendMessage(senderId, { text: response.data.message });
                console.log("Sent reply via webhook command:", response.data.message);
            }
        } catch (error) {
            console.error('Error sending message to PHP webhook:', error.message);
        }
    });
}

// Visual Dashboard for the Node.js App
app.get('/', (req, res) => {
    const isConnected = !!(sock && sock.user);
    const webhook = process.env.WEBHOOK_URL || 'Não configurado (configure o arquivo .env)';

    let html = `
    <html>
        <head>
            <title>Painel do Robô - ZapCRM</title>
            <style>
                body { font-family: sans-serif; background: #f0f2f5; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%; }
                .status { margin: 20px 0; padding: 10px; border-radius: 6px; font-weight: bold; }
                .online { background: #dcf8c6; color: #128c7e; }
                .offline { background: #ffebee; color: #c62828; }
                img { max-width: 100%; border-radius: 8px; margin: 20px 0; border: 1px solid #ddd; padding: 10px; }
                .info { font-size: 12px; color: #666; margin-top: 20px; text-align: left; padding-top: 20px; border-top: 1px solid #eee; word-break: break-all; }
                .btn-logout { background: #c62828; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px;}
            </style>
        </head>
        <body>
            <div class="card">
                <h2>Servidor WhatsApp Node.js</h2>
                <div class="status ${isConnected ? 'online' : 'offline'}">
                    Status: ${isConnected ? '🟢 CONECTADO' : '🔴 DESCONECTADO'}
                </div>

                ${isConnected ?
                    `<p>O seu robô está rodando 24h/dia.</p>
                     <p>Telefone conectado: <b>${sock.user.id.split(':')[0]}</b></p>
                     <form action="/logout" method="POST">
                        <button type="submit" class="btn-logout">Desconectar WhatsApp</button>
                     </form>`
                    :
                    (currentQR ?
                        `<p>Escaneie o QR Code abaixo com seu WhatsApp:</p><img src="${currentQR}" alt="QR Code" />`
                        :
                        `<p>Gerando QR Code... Aguarde alguns segundos e atualize a página.</p>`
                    )
                }

                <div class="info">
                    <b>Webhook Destino (CRM PHP):</b><br>
                    ${webhook}
                </div>
            </div>
            <script>
                // Atualiza a página sozinho se não estiver conectado, para mostrar o QR Code mais novo
                ${!isConnected ? 'setTimeout(() => window.location.reload(), 5000);' : ''}
            </script>
        </body>
    </html>
    `;
    res.send(html);
});

// Endpoint to logout session manually via dashboard
app.post('/logout', (req, res) => {
    if (sock) {
        sock.logout('user_initiated');
    }
    res.redirect('/');
});


// Middleware to verify calls from PHP API
app.use((req, res, next) => {
    if (req.path === '/' || req.path === '/ping' || req.path === '/logout') return next();

    const authHeader = req.headers.authorization || '';
    const botToken = process.env.BOT_TOKEN || '';

    if (!botToken) {
        return res.status(500).json({ error: 'BOT_TOKEN is not set on Node.js server .env' });
    }

    if (authHeader !== `Bearer ${botToken}`) {
        return res.status(401).json({ error: 'Unauthorized call from CRM. Tokens mismatch.' });
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

// Start the server
const server = http.createServer(app);
server.listen(PORT, '0.0.0.0', () => {
    console.log(`Node.js Web App running on port ${PORT}`);
    connectToWhatsApp();
});
