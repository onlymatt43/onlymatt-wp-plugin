/**
 * ONLYMATT AI Frontend JavaScript
 * Handles chat widget functionality and API communication
 */

class OnlyMattChatWidget {
    constructor(apiUrl) {
        this.apiUrl = apiUrl || onlymatt_ajax.api_base;
        this.isOpen = false;
        this.isTyping = false;
        this.init();
    }

    init() {
        this.createWidget();
        this.bindEvents();
    }

    createWidget() {
        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'onlymatt-chat-toggle';
        toggleBtn.className = 'onlymatt-chat-toggle';
        toggleBtn.innerHTML = '💬';
        toggleBtn.setAttribute('aria-label', 'Open AI Chat');

        // Create chat widget
        const widget = document.createElement('div');
        widget.id = 'onlymatt-chat-widget';
        widget.className = 'onlymatt-chat-widget';
        widget.innerHTML = `
            <div class="onlymatt-chat-header">
                <h4>ONLYMATT AI Assistant</h4>
            </div>
            <div class="onlymatt-chat-messages" id="onlymatt-messages">
                <div class="onlymatt-message assistant">
                    Hello! I'm your AI assistant. How can I help you today?
                </div>
            </div>
            <div class="onlymatt-typing" id="onlymatt-typing">
                AI is typing...
            </div>
            <div class="onlymatt-chat-input-area">
                <input type="text" id="onlymatt-input" class="onlymatt-chat-input"
                       placeholder="Type your message..." maxlength="500">
                <button id="onlymatt-send" class="onlymatt-btn">Send</button>
            </div>
        `;

        document.body.appendChild(toggleBtn);
        document.body.appendChild(widget);

        this.toggleBtn = toggleBtn;
        this.widget = widget;
        this.messages = widget.querySelector('#onlymatt-messages');
        this.input = widget.querySelector('#onlymatt-input');
        this.sendBtn = widget.querySelector('#onlymatt-send');
        this.typing = widget.querySelector('#onlymatt-typing');
    }

    bindEvents() {
        // Toggle chat widget
        this.toggleBtn.addEventListener('click', () => this.toggle());

        // Send message on button click
        this.sendBtn.addEventListener('click', () => this.sendMessage());

        // Send message on Enter key
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.toggle();
            }
        });
    }

    toggle() {
        this.isOpen = !this.isOpen;
        this.widget.style.display = this.isOpen ? 'flex' : 'none';
        this.toggleBtn.style.display = this.isOpen ? 'none' : 'flex';

        if (this.isOpen) {
            this.input.focus();
            this.scrollToBottom();
        }
    }

    async sendMessage() {
        const message = this.input.value.trim();
        if (!message || this.isTyping) return;

        // Add user message
        this.addMessage(message, 'user');
        this.input.value = '';

        // Show typing indicator
        this.showTyping();

        try {
            const response = await this.callAPI('/ai/chat', { messages: [{role: 'user', content: message}], model: 'llama-3.3-70b-versatile', temperature: 0.7 });
            this.hideTyping();
            this.addMessage(response.response, 'assistant');
        } catch (error) {
            this.hideTyping();
            this.addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
            console.error('Chat API error:', error);
        }
    }

    addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `onlymatt-message ${type}`;
        messageDiv.textContent = text;
        this.messages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    showTyping() {
        this.isTyping = true;
        this.typing.style.display = 'block';
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        this.typing.style.display = 'none';
    }

    scrollToBottom() {
        this.messages.scrollTop = this.messages.scrollHeight;
    }

    async callAPI(endpoint, data) {
        const response = await fetch(`${this.apiUrl}${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`API call failed: ${response.status}`);
        }

        return await response.json();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get API URL from WordPress localized script
    const apiUrl = typeof onlymatt_ajax !== 'undefined' ? onlymatt_ajax.api_url : '';

    if (apiUrl) {
        new OnlyMattChatWidget(apiUrl);
    }
});