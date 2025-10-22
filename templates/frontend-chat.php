<div class="onlymatt-chat-container" style="max-width: 600px; margin: 20px auto;">
    <div class="onlymatt-chat-inline" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="onlymatt-chat-header" style="background: #007cba; color: white; padding: 15px; font-weight: bold;">
            ONLYMATT AI Assistant
        </div>
        <div class="onlymatt-chat-messages" id="onlymatt-frontend-messages" style="height: 300px; overflow-y: auto; padding: 15px; background: #f9f9f9;">
            <div class="onlymatt-message assistant" style="margin-bottom: 10px; padding: 10px; border-radius: 8px; background: white; border: 1px solid #e0e0e0;">
                Hello! I'm your AI assistant. How can I help you today?
            </div>
        </div>
        <div class="onlymatt-typing" id="onlymatt-frontend-typing" style="display: none; padding: 10px; font-style: italic; color: #666; background: #f9f9f9;">
            AI is typing...
        </div>
        <div class="onlymatt-chat-input-area" style="padding: 15px; background: white; border-top: 1px solid #e0e0e0;">
            <div class="input-group">
                <input type="text" id="onlymatt-frontend-input" class="form-control" placeholder="Type your message..." style="border-radius: 20px 0 0 20px;">
                <button id="onlymatt-frontend-send" class="btn btn-primary" style="border-radius: 0 20px 20px 0;">Send</button>
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

    function addMessage(message, type) {
        const messageDiv = $('<div class="onlymatt-message ' + type + '">' + escapeHtml(message) + '</div>');
        $messages.append(messageDiv);
        $messages.scrollTop($messages[0].scrollHeight);
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

    $sendBtn.on('click', sendMessage);
    $input.on('keypress', function(e) {
        if (e.which === 13) {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = $input.val().trim();
        if (!message) return;

        addMessage(message, 'user');
        $input.val('');
        showTyping();

        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_chat',
            message: message,
            nonce: onlymatt_ajax.nonce
        })
        .done(function(response) {
            hideTyping();
            if (response.success) {
                addMessage(response.data.response || 'I received your message!', 'assistant');
            } else {
                addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
            }
        })
        .fail(function() {
            hideTyping();
            addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
        });
    }
});
</script>