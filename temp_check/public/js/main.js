document.addEventListener('DOMContentLoaded', () => {
    // Lucide Icons
    lucide.createIcons();

    // AI Chat Widget
    const chatInput = document.getElementById('ai-chat-input');
    const chatBtn = document.getElementById('ai-chat-btn');
    const chatResponse = document.getElementById('ai-chat-response');

    if (chatBtn && chatInput) {
        chatBtn.addEventListener('click', async () => {
            const query = chatInput.value.trim();
            if (!query) return;

            // UI Loading State
            chatBtn.disabled = true;
            chatBtn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>';
            lucide.createIcons();

            chatResponse.classList.remove('hidden');
            chatResponse.innerHTML = '<div class="animate-pulse flex space-x-4"><div class="flex-1 space-y-4 py-1"><div class="h-4 bg-gray-200 rounded w-3/4"></div><div class="space-y-2"><div class="h-4 bg-gray-200 rounded"></div><div class="h-4 bg-gray-200 rounded w-5/6"></div></div></div></div>';

            try {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ query: query })
                });

                const data = await response.json();

                if (data.response) {
                    // Typewriter effect or simple HTML render
                    chatResponse.innerHTML = data.response;
                } else {
                    chatResponse.innerHTML = '<span class="text-red-500">Desculpe, não consegui processar sua pergunta agora.</span>';
                }
            } catch (error) {
                console.error('Error:', error);
                chatResponse.innerHTML = '<span class="text-red-500">Erro de conexão. Tente novamente.</span>';
            } finally {
                chatBtn.disabled = false;
                chatBtn.innerHTML = '<i data-lucide="send" class="w-5 h-5"></i>';
                lucide.createIcons();
            }
        });

        // Allow Enter key
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                chatBtn.click();
            }
        });
    }
});
