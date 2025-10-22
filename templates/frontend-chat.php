<style>
.onlymatt-chat-container {
    max-width: 600px;
    margin: 20px auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.onlymatt-chat-inline {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.onlymatt-chat-header {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    color: white;
    padding: 20px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.onlymatt-chat-header::before {
    content: '🤖';
    font-size: 20px;
    margin-right: 10px;
}

.onlymatt-chat-messages {
    height: 350px;
    overflow-y: auto;
    padding: 20px;
    background: #ffffff;
    scroll-behavior: smooth;
}

.onlymatt-message {
    margin-bottom: 16px;
    padding: 12px 16px;
    border-radius: 18px;
    max-width: 80%;
    word-wrap: break-word;
    line-height: 1.4;
    font-size: 14px;
    animation: messageSlideIn 0.3s ease-out;
}

.onlymatt-message.user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    margin-left: auto;
    text-align: right;
    border-bottom-right-radius: 4px;
}

.onlymatt-message.assistant {
    background: #f8f9fa;
    color: #2c3e50;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
    position: relative;
}

.onlymatt-message.assistant::before {
    content: '🤖';
    position: absolute;
    left: -35px;
    top: 12px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.onlymatt-typing {
    display: none;
    padding: 15px 20px;
    font-style: italic;
    color: #6c757d;
    background: #ffffff;
    border-top: 1px solid #e9ecef;
    animation: typingPulse 1.5s ease-in-out infinite;
}

.onlymatt-chat-input-area {
    padding: 20px;
    background: #ffffff;
    border-top: 1px solid #e9ecef;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

#onlymatt-frontend-input {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 12px 50px 12px 20px;
    font-size: 14px;
    flex: 1;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

#onlymatt-frontend-input:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

#onlymatt-frontend-send {
    position: absolute;
    right: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

#onlymatt-frontend-send:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

#onlymatt-frontend-send:active {
    transform: scale(0.95);
}

#onlymatt-frontend-send::before {
    content: '📤';
    font-size: 16px;
}

.onlymatt-chat-messages::-webkit-scrollbar {
    width: 6px;
}

.onlymatt-chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.onlymatt-chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.onlymatt-chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes typingPulse {
    0%, 100% {
        opacity: 0.6;
    }
    50% {
        opacity: 1;
    }
}

.onlymatt-chat-inline {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    transform: translateY(20px);
    opacity: 0;
    animation: chatSlideIn 0.5s ease-out 0.1s forwards;
}

@keyframes chatSlideIn {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.onlymatt-message.user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    margin-left: auto;
    text-align: right;
    border-bottom-right-radius: 4px;
    position: relative;
    animation: messageSlideInUser 0.3s ease-out;
}

.onlymatt-message.user::after {
    content: '';
    position: absolute;
    right: -8px;
    bottom: 0;
    width: 0;
    height: 0;
    border-left: 8px solid #667eea;
    border-bottom: 8px solid transparent;
}

@keyframes messageSlideInUser {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.onlymatt-message.assistant {
    background: #f8f9fa;
    color: #2c3e50;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
    position: relative;
    animation: messageSlideInAssistant 0.3s ease-out;
}

.onlymatt-message.assistant::after {
    content: '';
    position: absolute;
    left: -8px;
    bottom: 0;
    width: 0;
    height: 0;
    border-right: 8px solid #f8f9fa;
    border-bottom: 8px solid transparent;
}

@keyframes messageSlideInAssistant {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.onlymatt-typing {
    display: none;
    padding: 15px 20px;
    font-style: italic;
    color: #6c757d;
    background: #ffffff;
    border-top: 1px solid #e9ecef;
    animation: typingPulse 1.5s ease-in-out infinite;
}

.onlymatt-typing::before {
    content: '🤖';
    margin-right: 8px;
}

#onlymatt-frontend-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

#onlymatt-frontend-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Hover effects */
.onlymatt-message:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

.onlymatt-chat-header:hover {
    background: rgba(255,255,255,0.15);
    transition: background 0.3s ease;
}

@media (max-width: 768px) {
    .onlymatt-chat-container {
        margin: 10px;
        max-width: none;
    }

    .onlymatt-chat-messages {
        height: 300px;
    }

    .onlymatt-message {
        max-width: 90%;
    }

    .onlymatt-message.assistant::before {
        display: none;
    }
}
</style>

<div class="onlymatt-chat-container">
    <div class="onlymatt-chat-inline">
        <div class="onlymatt-chat-header">
            <span>ONLYMATT AI Assistant</span>
            <span style="font-size: 12px; opacity: 0.8;">Online</span>
        </div>
        <div class="onlymatt-chat-messages" id="onlymatt-frontend-messages">
            <div class="onlymatt-message assistant">
                👋 Hello! I'm your AI assistant powered by advanced language models. How can I help you today?
            </div>
        </div>
        <div class="onlymatt-typing" id="onlymatt-frontend-typing">
            🤖 AI is thinking...
        </div>
        <div class="onlymatt-chat-input-area">
            <div class="input-group">
                <input type="text" id="onlymatt-frontend-input" placeholder="Type your message here..." autocomplete="off">
                <button id="onlymatt-frontend-send" title="Send message"></button>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    const $messages = $('#onlymatt-frontend-messages');
    const $input = $('#onlymatt-frontend-input');
    const $sendBtn = $('#onlymatt-frontend-send');
    const $typing = $('#onlymatt-frontend-typing');

    // Auto-resize input field
    $input.on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        if (this.scrollHeight > 100) {
            this.style.height = '100px';
            this.style.overflowY = 'auto';
        } else {
            this.style.overflowY = 'hidden';
        }
    });

    function addMessage(message, type) {
        const messageDiv = $('<div class="onlymatt-message ' + type + '"></div>');
        messageDiv.html(formatMessage(message));

        // Add timestamp
        const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        messageDiv.attr('data-timestamp', timestamp);

        $messages.append(messageDiv);
        $messages.scrollTop($messages[0].scrollHeight);

        // Add click to show timestamp
        messageDiv.on('click', function() {
            const existingTimestamp = $(this).find('.timestamp');
            if (existingTimestamp.length) {
                existingTimestamp.remove();
            } else {
                $('<div class="timestamp" style="font-size: 10px; color: #999; margin-top: 4px;">' + timestamp + '</div>').appendTo($(this));
            }
        });
    }

    function formatMessage(text) {
        // Basic markdown-like formatting
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code>$1</code>')
            .replace(/\n/g, '<br>');
    }

    function showTyping() {
        $typing.show();
        $messages.scrollTop($messages[0].scrollHeight);
    }

    function hideTyping() {
        $typing.hide();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Enhanced send message function
    function sendMessage() {
        const message = $input.val().trim();
        if (!message) return;

        // Disable input while sending
        $input.prop('disabled', true);
        $sendBtn.prop('disabled', true);

        addMessage(message, 'user');
        $input.val('');
        $input.css('height', 'auto');
        showTyping();

        // Add loading animation to send button
        $sendBtn.html('⏳');

        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_chat',
            message: message,
            nonce: onlymatt_ajax.nonce
        })
        .done(function(response) {
            hideTyping();
            $input.prop('disabled', false);
            $sendBtn.prop('disabled', false);
            $sendBtn.html('📤');

            if (response.success) {
                const aiResponse = response.data.response || 'I received your message!';
                // Simulate typing delay for better UX
                setTimeout(function() {
                    addMessage(aiResponse, 'assistant');
                }, 500);
            } else {
                addMessage('❌ Sorry, I encountered an error. Please try again.', 'assistant');
            }
        })
        .fail(function(xhr, status, error) {
            hideTyping();
            $input.prop('disabled', false);
            $sendBtn.prop('disabled', false);
            $sendBtn.html('📤');

            let errorMessage = '❌ Sorry, I encountered an error. Please try again.';
            if (xhr.status === 429) {
                errorMessage = '⏰ Too many requests. Please wait a moment and try again.';
            } else if (xhr.status === 0) {
                errorMessage = '📡 Connection error. Please check your internet connection.';
            }
            addMessage(errorMessage, 'assistant');
        });
    }

    $sendBtn.on('click', sendMessage);
    $input.on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Focus input on load
    $input.focus();

    // Add keyboard shortcuts hint
    $input.attr('title', 'Press Enter to send, Shift+Enter for new line');

    // Add welcome animation
    setTimeout(function() {
        $('.onlymatt-chat-inline').addClass('animate-in');
    }, 100);
});
</script>