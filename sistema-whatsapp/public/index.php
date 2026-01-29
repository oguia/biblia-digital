<?php require_once 'auth.php'; check_login(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel WhatsApp Multi-Instância</title>
    <style>
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; height: 100vh; display: flex; flex-direction: column; }
        .header { background: #008069; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 1.2rem; }

        .container { display: flex; flex: 1; overflow: hidden; }

        /* Sidebar (Contatos) */
        .sidebar { width: 300px; border-right: 1px solid #ddd; display: flex; flex-direction: column; background: white; }
        .sidebar-header { padding: 10px; background: #f0f2f5; border-bottom: 1px solid #ddd; }
        .contact-list { overflow-y: auto; flex: 1; }
        .contact-item { padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; }
        .contact-item:hover { background: #f5f5f5; }
        .contact-item.active { background: #e8f5e9; }
        .contact-name { font-weight: bold; font-size: 0.95rem; }
        .contact-details { font-size: 0.8rem; color: #666; display: flex; justify-content: space-between; margin-top: 4px; }
        .badge { background: #25d366; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; }

        /* Chat */
        .main-chat { flex: 1; display: flex; flex-direction: column; background: #efeae2; }
        .chat-header { padding: 10px 20px; background: #f0f2f5; border-bottom: 1px solid #ddd; font-weight: bold; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px; }
        .message { max-width: 60%; padding: 8px 12px; border-radius: 8px; font-size: 0.95rem; line-height: 1.4; position: relative; }
        .msg-in { align-self: flex-start; background: white; border-top-left-radius: 0; }
        .msg-out { align-self: flex-end; background: #d9fdd3; border-top-right-radius: 0; }
        .chat-input { padding: 10px; background: #f0f2f5; display: flex; gap: 10px; }
        .chat-input input { flex: 1; padding: 10px; border-radius: 20px; border: 1px solid #ccc; }

        /* Admin / Config */
        .config-panel { display: none; padding: 20px; flex: 1; background: #fff; overflow-y: auto; }
        .card { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .qr-box { margin-top: 10px; text-align: center; }
        .qr-box img { max-width: 200px; border: 1px solid #ccc; }

        button { padding: 8px 15px; background: #008069; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button.secondary { background: #666; }
        input[type=text], input[type=password], input[type=email], select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema WhatsApp</h1>
        <div>
            <span style="font-size: 0.9rem; margin-right: 15px;">Olá, <?php echo $_SESSION['admin_name']; ?></span>
            <button onclick="toggleConfig()">⚙️ Gerenciar</button>
            <a href="auth.php?logout=1" style="color: white; text-decoration: none; margin-left: 10px;">Sair</a>
        </div>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <input type="text" placeholder="Buscar conversa..." style="width: 90%;">
            </div>
            <div class="contact-list" id="contactList">Loading...</div>
        </div>

        <!-- Chat -->
        <div class="main-chat" id="chatArea">
            <div class="chat-header" id="chatTitle">Selecione uma conversa</div>
            <div class="chat-messages" id="messagesList"></div>
            <div class="chat-input" id="inputArea" style="display:none;">
                <input type="text" id="msgInput" placeholder="Digite uma mensagem..." onkeypress="if(event.key==='Enter') sendMessage()">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>

        <!-- Config Panel (Super Admin) -->
        <div class="config-panel" id="configPanel">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h2>Gerenciamento</h2>
                <button class="secondary" onclick="toggleConfig()">Voltar ao Chat</button>
            </div>

            <!-- Instâncias -->
            <div class="card">
                <h3>Instâncias (WhatsApp Conectados)</h3>
                <?php if ($_SESSION['instance_id'] === null): ?>
                    <div style="margin-bottom: 10px;">
                        <input type="text" id="newInstanceName" placeholder="Nome (ex: Vendas)">
                        <button onclick="createInstance()">+ Nova Instância</button>
                    </div>
                <?php endif; ?>
                <div id="instancesList"></div>
            </div>

            <!-- Usuários (Apenas Super Admin) -->
            <?php if ($_SESSION['instance_id'] === null): ?>
            <div class="card">
                <h3>Criar Novo Usuário</h3>
                <input type="text" id="newUserName" placeholder="Nome">
                <input type="email" id="newUserEmail" placeholder="Email">
                <input type="password" id="newUserPass" placeholder="Senha">
                <select id="newUserInstance">
                    <option value="">Super Admin (Acesso Total)</option>
                    <!-- Preenchido via JS -->
                </select>
                <button onclick="createUser()">Cadastrar</button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        let currentContactId = null;
        let instancesCache = [];

        // --- CORE FUNCTIONS ---
        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }

        function toggleConfig() {
            const chat = document.getElementById('chatArea');
            const conf = document.getElementById('configPanel');
            if (conf.style.display === 'block') {
                conf.style.display = 'none';
                chat.style.display = 'flex';
            } else {
                conf.style.display = 'block';
                chat.style.display = 'none';
                loadInstances();
            }
        }

        // --- API CALLS ---
        async function loadContacts() {
            const res = await fetch('api.php?action=get_contacts');
            const contacts = await res.json();
            const list = document.getElementById('contactList');
            list.innerHTML = '';

            contacts.forEach(c => {
                const div = document.createElement('div');
                div.className = `contact-item ${currentContactId == c.id ? 'active' : ''}`;
                div.onclick = () => selectContact(c.id, c.name, c.phone);
                div.innerHTML = `
                    <div class="contact-name">${escapeHtml(c.name || c.phone)}</div>
                    <div class="contact-details">
                        <span>${escapeHtml(c.last_msg || '')}</span>
                        <span class="badge">${c.instance_name}</span>
                    </div>
                `;
                list.appendChild(div);
            });
        }

        function selectContact(id, name, phone) {
            currentContactId = id;
            document.getElementById('chatTitle').innerText = `${name} (${phone})`;
            document.getElementById('inputArea').style.display = 'flex';
            loadMessages(id, true);
            loadContacts();
        }

        async function loadMessages(contactId, scroll) {
            if (contactId !== currentContactId) return;
            const res = await fetch(`api.php?action=get_messages&contact_id=${contactId}`);
            const msgs = await res.json();
            const container = document.getElementById('messagesList');
            container.innerHTML = '';

            msgs.forEach(m => {
                const div = document.createElement('div');
                div.className = `message ${m.from_me == 1 ? 'msg-out' : 'msg-in'}`;
                div.innerHTML = `${escapeHtml(m.body)}`;
                container.appendChild(div);
            });

            if (scroll) container.scrollTop = container.scrollHeight;
        }

        async function sendMessage() {
            const input = document.getElementById('msgInput');
            const text = input.value.trim();
            if (!text || !currentContactId) return;

            input.value = '';
            // Optimistic Update
            const container = document.getElementById('messagesList');
            const div = document.createElement('div');
            div.className = 'message msg-out';
            div.innerText = text;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;

            await fetch('api.php?action=send_message', {
                method: 'POST',
                body: JSON.stringify({ contact_id: currentContactId, text: text })
            });
            loadMessages(currentContactId, false);
        }

        // --- ADMIN / CONFIG FUNCTIONS ---
        async function loadInstances() {
            const res = await fetch('api.php?action=get_instances');
            instancesCache = await res.json();

            // Render List
            const list = document.getElementById('instancesList');
            list.innerHTML = '';
            instancesCache.forEach(i => {
                const div = document.createElement('div');
                div.style.padding = '10px';
                div.style.borderBottom = '1px solid #eee';
                div.innerHTML = `
                    <strong>${escapeHtml(i.name)}</strong> - Status:
                    <span style="color:${i.status === 'connected' ? 'green' : 'red'}">${i.status}</span>
                    ${i.qrcode && i.status !== 'connected' ?
                        `<div class="qr-box">
                            <p>Escaneie para conectar:</p>
                            <img src="${i.qrcode}">
                        </div>`
                        : ''}
                `;
                list.appendChild(div);
            });

            // Update User Dropdown (if exists)
            const sel = document.getElementById('newUserInstance');
            if (sel) {
                sel.innerHTML = '<option value="">Super Admin (Acesso Total)</option>';
                instancesCache.forEach(i => {
                    sel.innerHTML += `<option value="${i.id}">Admin de: ${escapeHtml(i.name)}</option>`;
                });
            }
        }

        async function createInstance() {
            const name = document.getElementById('newInstanceName').value;
            if(!name) return alert('Nome obrigatório');

            await fetch('api.php?action=create_instance', {
                method: 'POST',
                body: JSON.stringify({ name })
            });
            document.getElementById('newInstanceName').value = '';
            loadInstances();
        }

        async function createUser() {
            const name = document.getElementById('newUserName').value;
            const email = document.getElementById('newUserEmail').value;
            const password = document.getElementById('newUserPass').value;
            const instance_id = document.getElementById('newUserInstance').value;

            if(!name || !email || !password) return alert('Preencha tudo');

            await fetch('api.php?action=create_user', {
                method: 'POST',
                body: JSON.stringify({ name, email, password, instance_id })
            });
            alert('Usuário Criado!');
            document.getElementById('newUserName').value = '';
            document.getElementById('newUserEmail').value = '';
            document.getElementById('newUserPass').value = '';
        }

        // Polling
        setInterval(loadContacts, 5000);
        setInterval(() => { if(currentContactId) loadMessages(currentContactId, false); }, 3000);
        setInterval(() => {
            if(document.getElementById('configPanel').style.display === 'block') loadInstances();
        }, 5000);

        loadContacts();
    </script>
</body>
</html>
