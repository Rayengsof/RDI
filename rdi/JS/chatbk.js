// Função para abrir/fechar o chat
function toggleChat() {
    const chatBox = document.getElementById('chatBox');
    const chatButton = document.getElementById('chatButton');
    
    if (chatBox.style.display === 'none' || chatBox.style.display === '') {
        // Exibe o chat
        chatBox.style.display = 'block';
        chatButton.classList.remove('new-message');  // Remove o destaque do botão quando o chat é aberto
    } else {
        // Esconde o chat
        chatBox.style.display = 'none';
    }
}

// Função para enviar mensagem
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (message !== '') {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_message.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Mensagem enviada com sucesso');
                messageInput.value = ''; // Limpar o campo de texto após o envio
                loadMessages(); // Carregar novas mensagens
            } else {
                console.error('Erro ao enviar a mensagem: ' + xhr.status);
            }
        };
        xhr.send('message=' + encodeURIComponent(message));
    }
}

// Função para carregar mensagens
function loadMessages() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_messages.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const messages = JSON.parse(xhr.responseText);
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = ''; // Limpar mensagens anteriores

            // Adicionar novas mensagens no topo da lista
            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.textContent = message.user_name + ": " + message.message;
                messageDiv.classList.add('chat-message'); // Adiciona uma classe para formatação

                // Se a mensagem não foi vista, adicionar um estilo
                if (!message.visto) {
                    messageDiv.classList.add('unread-message');
                }

                // Adicionar a mensagem ao topo da lista
                messagesDiv.insertBefore(messageDiv, messagesDiv.firstChild);
            });

            // Garantir que a barra de rolagem desça até o final
            scrollToBottom();
        }
    };
    xhr.send();
}

// Função para rolar até o final da caixa de mensagens
function scrollToBottom() {
    const messagesDiv = document.getElementById('messages');
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

// Chamar loadMessages quando a página for carregada ou quando o botão for pressionado e a cada 5s 
document.addEventListener('DOMContentLoaded', function() {
    setInterval(loadMessages, 5000); // Atualizar mensagens a cada 5 segundos
});

// Permitir enviar mensagem com Enter
const messageInput = document.getElementById('messageInput');
messageInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault(); // Previne o comportamento padrão de enviar um formulário ou quebra de linha
        sendMessage(); // Chama a função de envio de mensagem
    }
});

// Adicionar o evento de clique no botão de envio também
const sendButton = document.getElementById('sendMessageButton');
sendButton.addEventListener('click', sendMessage);
