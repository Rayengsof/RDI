let isSending = false; // Flag para controlar se a mensagem está sendo enviada

// Função para abrir/fechar o chat
function toggleChat() {
    const chatBox = document.getElementById('chatBox');
    const chatButton = document.getElementById('chatButton');
    
    if (chatBox.style.display === 'none' || chatBox.style.display === '') {
        // Exibe o chat
        chatBox.style.display = 'block';
        chatButton.classList.remove('new-message');  // Remove o destaque do botão quando o chat é aberto
        chatButton.classList.remove('blinking-button'); // Remove o piscar se o chat estiver aberto

        // Marcar mensagens como lidas
        markMessagesAsRead();  
    } else {
        // Esconde o chat
        chatBox.style.display = 'none';
    }
}

// Função para enviar mensagem
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    const sendButton = document.getElementById('sendMessageButton');

    // Verifica se a mensagem não está vazia
    if (message !== '' && !isSending) {
        isSending = true; // Define o flag para indicar que a mensagem está sendo enviada
        sendButton.disabled = true; // Desabilita o botão para evitar múltiplos cliques

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_message.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Ao finalizar o envio, habilita o botão novamente
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Mensagem enviada com sucesso');
                messageInput.value = ''; // Limpar o campo de texto após o envio
                loadMessages(); // Carregar novas mensagens
            } else {
                console.error('Erro ao enviar a mensagem: ' + xhr.status);
            }
            // Reabilitar o botão de envio e resetar o flag
            isSending = false;
            sendButton.disabled = false;
        };

        // Envia a mensagem
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

            let newMessages = false; // Flag para saber se há mensagens novas

            // Adicionar novas mensagens no topo da lista
            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.textContent = message.user_name + ": " + message.message;
                messageDiv.classList.add('chat-message'); // Adiciona uma classe para formatação

                // Se a mensagem não foi lida (is_read = 1) e não foi verificada (is_verified = 0), adicionar um estilo
                if (message.is_read === 1 && message.is_verified === 0) {
                    messageDiv.classList.add('unread-message');
                    newMessages = true; // Marcar que há mensagens novas
                } else if (message.is_verified === 1) {
                    messageDiv.classList.add('verified-message');  // Se foi verificada pelo destinatário
                }

                // Adicionar a mensagem ao topo da lista
                messagesDiv.insertBefore(messageDiv, messagesDiv.firstChild);
            });

            // Garantir que a barra de rolagem desça até o final
            scrollToBottom();

            // Acionar o alerta de nova mensagem no botão de chat
            if (newMessages) {
                showNewMessageAlert();
            }

            // Se o chat estiver visível, marcar mensagens como lidas
            if (document.getElementById('chatBox').style.display === 'block') {
                markMessagesAsRead(); // Marcar como lida se o chat estiver aberto
            }
        }
    };
    xhr.send();
}

// Função para marcar mensagens como lidas
function markMessagesAsRead() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_messages_as_read.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Mensagens marcadas como lidas');
        } else {
            console.error('Erro ao marcar mensagens como lidas: ' + xhr.status);
        }
    };
    xhr.send();
}

// Função para exibir alerta de nova mensagem (fazendo o botão piscar)
function showNewMessageAlert() {
    const chatButton = document.getElementById('chatButton');
    chatButton.classList.add('blinking-button');  // Adiciona o efeito de piscar ao botão
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

// Adicionar o evento de clique no botão de envio
const sendButton = document.getElementById('sendMessageButton');
sendButton.removeEventListener('click', sendMessage); // Remove qualquer ouvinte anterior
sendButton.addEventListener('click', sendMessage); // Adiciona o ouvinte de evento uma vez
