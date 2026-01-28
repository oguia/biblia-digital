<?php require_once 'auth.php'; check_login(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel WhatsApp</title>
    <style>
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; height: 100vh; display: flex; flex-direction: column; }
        .header { background: #008069; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 1.2rem; }
        .header a, .header button { color: white; text-decoration: none; cursor: pointer; background: none; border: none; font-size: 1rem;}

        .container { display: flex; flex: 1; overflow: hidden; }

        .sidebar { width: 300px; border-right: 1px solid #ddd; display: flex; flex-direction: column; background: white; }
        .sidebar-header { padding: 10px; background: #f0f2f5; border-bottom: 1px solid #ddd; font-weight: bold; display: flex; justify-content: space-between; }
        .contact-list { overflow-y: auto; flex: 1; }
        .contact-item { padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; display: flex; flex-direction: column; }
        .contact-item:hover { background: #f5f5f5; }
        .contact-item.active { background: #e8f5e9; }
        .contact-name { font-weight: bold; }
        .contact-msg { font-size: 0.85rem; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .contact-badge { background: #25d366; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; align-self: flex-start; margin-top: 5px; }

        .main-chat { flex: 1; display: flex; flex-direction: column; background: #efeae2; }
        .chat-header { padding: 10px 20px; background: #f0f2f5; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px; }

        .message { max-width: 60%; padding: 8px 12px; border-radius: 8px; font-size: 0.95rem; line-height: 1.4; position: relative; }
        .msg-in { align-self: flex-start; background: white; border-top-left-radius: 0; }
        .msg-out { align-self: flex-end; background: #d9fdd3; border-top-right-radius: 0; }
        .msg-time { font-size: 0.7rem; color: #999; text-align: right; margin-top: 4px; }

        .chat-input { padding: 10px; background: #f0f2f5; display: flex; gap: 10px; border-top: 1px solid #ddd; }
        .chat-input input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none; }
        .chat-input button { padding: 10px 20px; background: #008069; color: white; border: none; border-radius: 20px; cursor: pointer; }

        .settings-panel { display: none; padding: 20px; overflow-y: auto; background: white; flex: 1; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .card h3 { margin-top: 0; }

        /* Utility */
        .hidden { display: none !important; }
        .btn-small { padding: 5px 10px; background: #ddd; border: none; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #ff5252; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema WhatsApp</h1>
        <div>
            <button onclick="toggleSettings()">⚙️ Configurações</button>
            <a href="auth.php?logout=1" style="margin-left: 15px;">Sair</a>
        </div>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                Atendimentos
                <button class="btn-small" onclick="loadContacts()">↻</button>
            </div>
            <div class="contact-list" id="contactList">
                <!-- Preenchido via JS -->
                <div style="padding:20px; text-align:center; color:#999;">Carregando...</div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="main-chat" id="chatArea">
            <div class="chat-header" id="chatHeader">
                <span id="chatTitle">Selecione uma conversa</span>
                <button id="btnCloseChat" class="btn-small btn-danger hidden" onclick="closeChat()">Encerrar Atendimento</button>
            </div>
            <div class="chat-messages" id="messagesList">
                <!-- Mensagens aqui -->
            </div>
            <div class="chat-input hidden" id="chatInputArea">
                <input type="text" id="msgInput" placeholder="Digite uma mensagem..." onkeypress="handleEnter(event)">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>

        <!-- Settings Area -->
        <div class="settings-panel" id="settingsPanel">
            <h2>Configurações</h2>
            <button class="btn-small" onclick="toggleSettings()">Voltar para o Chat</button>
            <hr>

            <div class="card">
                <h3>Adicionar Atendente</h3>
                <input type="text" id="newAgentName" placeholder="Nome" style="width: 100%; margin-bottom: 5px; padding: 5px;">
                <input type="email" id="newAgentEmail" placeholder="Email" style="width: 100%; margin-bottom: 5px; padding: 5px;">
                <input type="password" id="newAgentPass" placeholder="Senha" style="width: 100%; margin-bottom: 5px; padding: 5px;">
                <button class="btn-small" onclick="addAgent()" style="background:#008069; color:white;">Cadastrar</button>
                <div id="agentList" style="margin-top: 15px;"></div>
            </div>

            <div class="card">
                <h3>Departamentos</h3>
                <input type="text" id="newDeptName" placeholder="Nome do Dept" style="width: 100%; margin-bottom: 5px; padding: 5px;">
                <input type="text" id="newDeptDesc" placeholder="Descrição" style="width: 100%; margin-bottom: 5px; padding: 5px;">
                <button class="btn-small" onclick="addDept()" style="background:#008069; color:white;">Criar Departamento</button>
                <div id="deptList" style="margin-top: 15px;"></div>
            </div>
        </div>
    </div>

    <script>
        let currentContactId = null;

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            loadContacts();
            setInterval(loadContacts, 10000); // Atualiza lista a cada 10s
            setInterval(() => {
                if (currentContactId) loadMessages(currentContactId, false);
            }, 5000); // Atualiza chat a cada 5s
        });

        function toggleSettings() {
            const chat = document.getElementById('chatArea');
            const settings = document.getElementById('settingsPanel');
            if (settings.style.display === 'block') {
                settings.style.display = 'none';
                chat.style.display = 'flex';
            } else {
                settings.style.display = 'block';
                chat.style.display = 'none';
                loadSettings();
            }
        }

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
                    <div class="contact-msg">${escapeHtml(c.last_msg || '')}</div>
                    ${c.dept_name ? `<div class="contact-badge">${escapeHtml(c.dept_name)}</div>` : ''}
                `;
                list.appendChild(div);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function selectContact(id, name, phone) {
            currentContactId = id;
            document.getElementById('chatTitle').innerText = `${name} (${phone})`;
            document.getElementById('chatInputArea').classList.remove('hidden');
            document.getElementById('btnCloseChat').classList.remove('hidden');
            loadMessages(id, true);
            loadContacts(); // Refresh active state
        }

        async function loadMessages(contactId, scroll) {
            if (contactId !== currentContactId) return;
            const res = await fetch(`api.php?action=get_messages&contact_id=${contactId}`);
            const msgs = await res.json();
            const container = document.getElementById('messagesList');

            // Simples diff check (pode ser melhorado)
            if (container.childElementCount === msgs.length && !scroll) return;

            container.innerHTML = '';
            msgs.forEach(m => {
                const div = document.createElement('div');
                div.className = `message ${m.type === 'out' ? 'msg-out' : 'msg-in'}`;
                div.innerHTML = `
                    ${escapeHtml(m.body)}
                    <div class="msg-time">${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                `;
                container.appendChild(div);
            });

            if (scroll) container.scrollTop = container.scrollHeight;
        }

        async function sendMessage() {
            const input = document.getElementById('msgInput');
            const text = input.value.trim();
            if (!text || !currentContactId) return;

            input.value = ''; // Limpa rápido para UX

            // Adiciona visualmente (otimista)
            const container = document.getElementById('messagesList');
            const div = document.createElement('div');
            div.className = 'message msg-out';
            div.innerText = text; // Simples (já protege XSS aqui pois usa innerText)
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;

            await fetch('api.php?action=send_message', {
                method: 'POST',
                body: JSON.stringify({ contact_id: currentContactId, text: text })
            });
            loadMessages(currentContactId, false);
        }

        function handleEnter(e) {
            if (e.key === 'Enter') sendMessage();
        }

        async function closeChat() {
            if(!confirm('Deseja encerrar este atendimento?')) return;
            await fetch('api.php?action=close_chat', {
                method: 'POST',
                body: JSON.stringify({ contact_id: currentContactId })
            });
            currentContactId = null;
            document.getElementById('chatTitle').innerText = 'Selecione uma conversa';
            document.getElementById('messagesList').innerHTML = '';
            document.getElementById('chatInputArea').classList.add('hidden');
            document.getElementById('btnCloseChat').classList.add('hidden');
            loadContacts();
        }

        // Settings Logic
        async function loadSettings() {
            const res = await fetch('api.php?action=get_settings');
            const data = await res.json();

            const agentList = document.getElementById('agentList');
            agentList.innerHTML = '<h4>Agentes Cadastrados:</h4>' + data.agents.map(a => `<div>• ${a.name} (${a.email})</div>`).join('');

            const deptList = document.getElementById('deptList');
            deptList.innerHTML = '<h4>Departamentos:</h4>' + data.departments.map(d => `<div>• ${d.name}</div>`).join('');
        }

        async function addAgent() {
            const name = document.getElementById('newAgentName').value;
            const email = document.getElementById('newAgentEmail').value;
            const password = document.getElementById('newAgentPass').value;

            if(!name || !email || !password) return alert('Preencha tudo');

            await fetch('api.php?action=add_agent', {
                method: 'POST',
                body: JSON.stringify({ name, email, password })
            });
            alert('Agente criado!');
            loadSettings();
        }

        async function addDept() {
            const name = document.getElementById('newDeptName').value;
            const description = document.getElementById('newDeptDesc').value;
             if(!name) return alert('Nome obrigatório');

            await fetch('api.php?action=add_dept', {
                method: 'POST',
                body: JSON.stringify({ name, description })
            });
            alert('Departamento criado!');
            loadSettings();
        }
    </script>
</body>
</html>
