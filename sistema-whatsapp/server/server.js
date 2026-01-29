const express = require('express');
const { default: makeWASocket, useMultiFileAuthState, DisconnectReason } = require('@whiskeysockets/baileys');
const mysql = require('mysql2/promise');
const qrcode = require('qrcode');
const pino = require('pino');
const fs = require('fs');
const cors = require('cors');

const app = express();
app.use(express.json());
app.use(cors());

// PROTEÇÃO DA API (API KEY)
const API_SECRET = 'minha_senha_secreta_api'; // Mude aqui e no config.php
app.use((req, res, next) => {
    const token = req.headers['x-api-token'];
    if (!token || token !== API_SECRET) {
        return res.status(403).json({ error: 'Acesso negado' });
    }
    next();
});

// Configuração do Banco de Dados
const dbConfig = {
    host: 'localhost',
    user: 'root', // ALTERE AQUI NA HOSPEDAGEM
    password: '', // ALTERE AQUI NA HOSPEDAGEM
    database: 'sistema_whatsapp' // ALTERE AQUI
};

// Armazena as sessões ativas na memória
const sessions = {}; // { instance_id: socket }

// Função para logar e salvar mensagens
async function saveMessage(instanceId, msg, fromMe) {
    if (!msg.message) return;

    // Simplificação do corpo da mensagem
    const type = Object.keys(msg.message)[0];
    const body = msg.message.conversation ||
                 msg.message.extendedTextMessage?.text ||
                 msg.message.imageMessage?.caption ||
                 '[' + type + ']';

    const remoteJid = msg.key.remoteJid;
    const pushName = msg.pushName || remoteJid.split('@')[0];

    const conn = await mysql.createConnection(dbConfig);

    try {
        // 1. Buscar ou criar contato
        let [rows] = await conn.execute(
            'SELECT id FROM contacts WHERE instance_id = ? AND phone = ?',
            [instanceId, remoteJid]
        );

        let contactId;
        let isNewContact = false;

        if (rows.length > 0) {
            contactId = rows[0].id;
            // Atualizar last_activity e nome
            await conn.execute(
                'UPDATE contacts SET name = ?, last_activity = NOW() WHERE id = ?',
                [pushName, contactId]
            );
        } else {
            const [res] = await conn.execute(
                'INSERT INTO contacts (instance_id, phone, name) VALUES (?, ?, ?)',
                [instanceId, remoteJid, pushName]
            );
            contactId = res.insertId;
            isNewContact = true;
        }

        // 2. Salvar Mensagem
        await conn.execute(
            'INSERT INTO messages (contact_id, remote_jid, from_me, type, body) VALUES (?, ?, ?, ?, ?)',
            [contactId, remoteJid, fromMe ? 1 : 0, type, body]
        );

        // 3. Auto-Resposta (Chatbot Simples)
        // Se for novo contato e a mensagem não for minha
        if (!fromMe && isNewContact) {
            const welcomeText = "Olá! Seja bem vindo.\n\nEscolha o que deseja:\n1. Falar com Atendente";
            // Enviar mensagem de boas vindas
            setTimeout(() => {
                const sock = sessions[instanceId];
                if(sock) {
                    sock.sendMessage(remoteJid, { text: welcomeText });
                    // Salvar msg enviada no banco
                    const mockMsg = {
                        key: { remoteJid }, message: { conversation: welcomeText }, pushName: 'Bot'
                    };
                    saveMessage(instanceId, mockMsg, true);
                }
            }, 1000);
        }

    } catch (err) {
        console.error('Erro ao salvar mensagem:', err);
    } finally {
        await conn.end();
    }
}

async function updateInstanceStatus(id, status, qr = null) {
    const conn = await mysql.createConnection(dbConfig);
    await conn.execute(
        'UPDATE instances SET status = ?, qrcode = ? WHERE id = ?',
        [status, qr, id]
    );
    await conn.end();
}

// Inicializa uma conexão
async function startSession(instanceId, sessionName) {
    if (sessions[instanceId]) return;

    const { state, saveCreds } = await useMultiFileAuthState(`./sessions/${sessionName}`);

    const sock = makeWASocket({
        auth: state,
        printQRInTerminal: false,
        logger: pino({ level: 'silent' }),
        browser: ["Sistema WhatsApp", "Chrome", "1.0"]
    });

    sessions[instanceId] = sock;

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            const url = await qrcode.toDataURL(qr);
            await updateInstanceStatus(instanceId, 'connecting', url);
        }

        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error?.output?.statusCode !== DisconnectReason.loggedOut);
            console.log(`Conexão fechada (${instanceId}). Reconectando: ${shouldReconnect}`);

            await updateInstanceStatus(instanceId, 'disconnected');
            delete sessions[instanceId];

            if (shouldReconnect) {
                startSession(instanceId, sessionName);
            }
        } else if (connection === 'open') {
            console.log(`Conexão aberta (${instanceId})`);
            await updateInstanceStatus(instanceId, 'connected', null);
        }
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('messages.upsert', async (m) => {
        const msg = m.messages[0];
        if (!msg.key.fromMe && m.type === 'notify') {
            console.log('Mensagem recebida:', msg);
            await saveMessage(instanceId, msg, false);
        }
    });
}

// Inicializa sessões salvas no banco ao ligar
async function init() {
    const conn = await mysql.createConnection(dbConfig);
    const [rows] = await conn.execute('SELECT * FROM instances'); // Tenta reconectar todos
    await conn.end();

    for (const row of rows) {
        console.log(`Iniciando sessão ${row.name}...`);
        startSession(row.id, row.session_name);
    }
}

// Rotas da API

// 1. Criar nova sessão
app.post('/instance', async (req, res) => {
    const { name } = req.body;
    const sessionName = name.toLowerCase().replace(/[^a-z0-9]/g, '') + '_' + Date.now();

    const conn = await mysql.createConnection(dbConfig);
    const [ret] = await conn.execute('INSERT INTO instances (name, session_name) VALUES (?, ?)', [name, sessionName]);
    await conn.end();

    const id = ret.insertId;
    startSession(id, sessionName);

    res.json({ id, name, sessionName });
});

// 2. Status / QR Code
app.get('/instance/:id', async (req, res) => {
    const id = req.params.id;
    const conn = await mysql.createConnection(dbConfig);
    const [rows] = await conn.execute('SELECT * FROM instances WHERE id = ?', [id]);
    await conn.end();

    if(rows.length > 0) res.json(rows[0]);
    else res.status(404).json({error: 'Not found'});
});

// 3. Enviar Mensagem
app.post('/send', async (req, res) => {
    const { instance_id, phone, message } = req.body;

    const sock = sessions[instance_id];
    if (!sock) return res.status(400).json({ error: 'Instance not connected' });

    // Enviar
    // O JID deve ter o formato 551199999999@s.whatsapp.net
    const jid = phone.includes('@') ? phone : phone + '@s.whatsapp.net';

    await sock.sendMessage(jid, { text: message });

    // Salvar no banco como enviada por mim
    // Simular objeto msg para reutilizar a função save
    const mockMsg = {
        key: { remoteJid: jid },
        message: { conversation: message },
        pushName: 'Me'
    };
    await saveMessage(instance_id, mockMsg, true);

    res.json({ success: true });
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor rodando na porta ${PORT}`);
    init();
});
