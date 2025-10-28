/**
 * AI Website Assistant - JavaScript
 */

(function($) {
    'use strict';

    class AIAssistant {
        constructor(wrapper) {
            this.wrapper = wrapper;
            this.logo = wrapper.querySelector('.ai-assistant-logo');
            this.window = wrapper.querySelector('.ai-assistant-window');
            this.closeBtn = wrapper.querySelector('.ai-assistant-close');
            this.messages = wrapper.querySelector('.ai-assistant-messages');
            this.input = wrapper.querySelector('#ai-assistant-input');
            this.sendBtn = wrapper.querySelector('#ai-assistant-send');

            this.conversationHistory = [];
            this.isOpen = false;
            this.isTyping = false;

            this.init();
        }

        init() {
            this.bindEvents();
            this.showWelcomeMessage();
        }

        bindEvents() {
            // Ouvrir/fermer la fenêtre
            this.logo.addEventListener('click', () => this.toggleWindow());
            this.closeBtn.addEventListener('click', () => this.closeWindow());

            // Envoyer un message
            this.sendBtn.addEventListener('click', () => this.sendMessage());
            this.input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            // Fermer en cliquant en dehors
            document.addEventListener('click', (e) => {
                if (!this.wrapper.contains(e.target) && this.isOpen) {
                    this.closeWindow();
                }
            });

            // Détection d'inactivité pour afficher des suggestions
            this.inactivityTimer = setTimeout(() => {
                if (!this.isOpen) {
                    this.showProactiveMessage();
                }
            }, 30000); // 30 secondes
        }

        toggleWindow() {
            if (this.isOpen) {
                this.closeWindow();
            } else {
                this.openWindow();
            }
        }

        openWindow() {
            this.window.style.display = 'flex';
            this.window.classList.add('fade-in');
            this.isOpen = true;
            this.input.focus();

            // Réinitialiser le timer d'inactivité
            clearTimeout(this.inactivityTimer);

            // Marquer comme vu pour éviter les messages proactifs
            this.hasBeenOpened = true;
        }

        closeWindow() {
            this.window.classList.add('fade-out');
            setTimeout(() => {
                this.window.style.display = 'none';
                this.window.classList.remove('fade-in', 'fade-out');
            }, 300);
            this.isOpen = false;
        }

        showWelcomeMessage() {
            const welcomeMessage = {
                role: 'assistant',
                content: 'Bonjour ! 👋 Je suis votre assistant IA. Je connais parfaitement votre site et peux vous aider à :\n\n' +
                        '• Découvrir nos produits/services\n' +
                        '• Trouver des informations spécifiques\n' +
                        '• Vous guider dans vos achats\n' +
                        '• Répondre à vos questions\n\n' +
                        'Comment puis-je vous aider aujourd\'hui ?'
            };

            this.addMessageToUI(welcomeMessage);
            this.conversationHistory.push(welcomeMessage);
        }

        showProactiveMessage() {
            if (this.hasBeenOpened) return;

            // Créer un message proactif subtil
            const proactiveMessage = {
                role: 'assistant',
                content: '💡 Besoin d\'aide pour naviguer sur notre site ? Cliquez sur le logo pour discuter avec moi !'
            };

            // Afficher temporairement
            this.addMessageToUI(proactiveMessage, true);

            // Masquer après 5 secondes
            setTimeout(() => {
                const messages = this.messages.querySelectorAll('.ai-message');
                const lastMessage = messages[messages.length - 1];
                if (lastMessage && lastMessage.classList.contains('proactive')) {
                    lastMessage.style.opacity = '0';
                    setTimeout(() => lastMessage.remove(), 300);
                }
            }, 5000);
        }

        sendMessage() {
            const message = this.input.value.trim();
            if (!message || this.isTyping) return;

            // Ajouter le message utilisateur
            const userMessage = {
                role: 'user',
                content: message,
                timestamp: new Date().toISOString()
            };

            this.addMessageToUI(userMessage);
            this.conversationHistory.push(userMessage);
            this.input.value = '';

            // Afficher l'indicateur de frappe
            this.showTypingIndicator();

            // Envoyer à l'API
            this.sendToAPI(message);
        }

        showTypingIndicator() {
            this.isTyping = true;
            this.sendBtn.disabled = true;

            const typingDiv = document.createElement('div');
            typingDiv.className = 'ai-assistant-typing';
            typingDiv.innerHTML = `
                <span>L'assistant écrit</span>
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            `;

            this.messages.appendChild(typingDiv);
            this.scrollToBottom();
        }

        hideTypingIndicator() {
            const typingIndicator = this.messages.querySelector('.ai-assistant-typing');
            if (typingIndicator) {
                typingIndicator.remove();
            }
            this.isTyping = false;
            this.sendBtn.disabled = false;
        }

        sendToAPI(message) {
            const data = {
                action: 'aiwa_chat',
                nonce: aiwa_ajax.nonce,
                message: message,
                history: JSON.stringify(this.conversationHistory)
            };

            $.ajax({
                url: aiwa_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    this.hideTypingIndicator();

                    if (response.success) {
                        const botMessage = {
                            role: 'assistant',
                            content: response.data.reply,
                            timestamp: new Date().toISOString()
                        };

                        this.addMessageToUI(botMessage);
                        this.conversationHistory.push(botMessage);

                        // Suggestions automatiques basées sur la réponse
                        this.showSuggestions(botMessage.content);
                    } else {
                        console.error('AIWA Error Response:', response);
                        const errorMsg = response.data && response.data.message ? response.data.message : 'Désolé, une erreur s\'est produite. Veuillez réessayer.';
                        this.showError(errorMsg);
                    }
                },
                error: (xhr, status, error) => {
                    this.hideTypingIndicator();
                    console.error('AIWA AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });
                    this.showError('Erreur de connexion. Veuillez vérifier votre connexion internet et réessayer.');
                }
            });
        }

        addMessageToUI(message, isProactive = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `ai-message ai-message-${message.role}`;

            if (isProactive) {
                messageDiv.classList.add('proactive');
            }

            messageDiv.innerHTML = `
                <div class="ai-message-content">
                    ${this.formatMessage(message.content)}
                </div>
            `;

            this.messages.appendChild(messageDiv);
            this.scrollToBottom();
        }

        formatMessage(content) {
            // Convertir les liens en cliquables
            content = content.replace(
                /(https?:\/\/[^\s]+)/g,
                '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
            );

            // Convertir les sauts de ligne en <br>
            content = content.replace(/\n/g, '<br>');

            // Convertir les listes
            content = content.replace(/• (.*?)(?=\n• |\n\n|$)/g, '<li>$1</li>');
            content = content.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');

            return content;
        }

        showError(message) {
            const errorMessage = {
                role: 'assistant',
                content: `❌ ${message}`
            };

            this.addMessageToUI(errorMessage);
            this.conversationHistory.push(errorMessage);
        }

        showSuggestions(response) {
            // Analyser la réponse pour proposer des actions
            const suggestions = this.extractSuggestions(response);

            if (suggestions.length > 0) {
                setTimeout(() => {
                    const suggestionMessage = {
                        role: 'assistant',
                        content: '💡 Suggestions :\n' + suggestions.map(s => `• ${s}`).join('\n')
                    };

                    this.addMessageToUI(suggestionMessage);
                }, 1000);
            }
        }

        extractSuggestions(response) {
            const suggestions = [];
            const lowerResponse = response.toLowerCase();

            // Suggestions basées sur le contenu de la réponse
            if (lowerResponse.includes('contact') || lowerResponse.includes('téléphone')) {
                suggestions.push('Consulter nos coordonnées');
            }

            if (lowerResponse.includes('prix') || lowerResponse.includes('tarif')) {
                suggestions.push('Voir nos tarifs détaillés');
            }

            if (lowerResponse.includes('produit') || lowerResponse.includes('service')) {
                suggestions.push('Découvrir notre catalogue');
            }

            if (lowerResponse.includes('rendez-vous') || lowerResponse.includes('réservation')) {
                suggestions.push('Prendre rendez-vous');
            }

            return suggestions.slice(0, 3); // Maximum 3 suggestions
        }

        scrollToBottom() {
            this.messages.scrollTop = this.messages.scrollHeight;
        }

        // Méthodes utilitaires pour les interactions externes
        showMessage(content, role = 'assistant') {
            const message = {
                role: role,
                content: content,
                timestamp: new Date().toISOString()
            };

            this.addMessageToUI(message);
            if (role === 'user') {
                this.conversationHistory.push(message);
            }
        }

        openChat() {
            this.openWindow();
        }

        closeChat() {
            this.closeWindow();
        }
    }

    // Initialisation
    $(document).ready(function() {
        $('.ai-assistant-wrapper').each(function() {
            new AIAssistant(this);
        });
    });

    // API globale pour interactions externes
    window.AIAssistantAPI = {
        openChat: function() {
            $('.ai-assistant-wrapper').each(function() {
                const instance = $(this).data('ai-assistant');
                if (instance) {
                    instance.openChat();
                }
            });
        },

        closeChat: function() {
            $('.ai-assistant-wrapper').each(function() {
                const instance = $(this).data('ai-assistant');
                if (instance) {
                    instance.closeChat();
                }
            });
        },

        sendMessage: function(message) {
            $('.ai-assistant-wrapper').each(function() {
                const instance = $(this).data('ai-assistant');
                if (instance) {
                    instance.showMessage(message, 'user');
                    // Déclencher l'envoi automatique
                    setTimeout(() => {
                        instance.sendToAPI(message);
                    }, 500);
                }
            });
        }
    };

})(jQuery);