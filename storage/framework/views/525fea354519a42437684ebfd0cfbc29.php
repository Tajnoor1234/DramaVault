<!-- AI Chatbot Widget (Only for authenticated users) -->
<?php if(auth()->guard()->check()): ?>
<div id="chatbotWidget" class="chatbot-widget">
    <!-- Chatbot Toggle Button -->
    <button id="chatbotToggle" class="chatbot-toggle" title="AI Assistant">
        <i class="fas fa-robot"></i>
        <span class="pulse-dot"></span>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbotWindow" class="chatbot-window" style="display: none;">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0">DramaVault AI</h6>
                    <small class="text-white-50">Powered by Gemini</small>
                </div>
            </div>
            <button id="chatbotClose" class="btn-close btn-close-white"></button>
        </div>

        <!-- Messages Area -->
        <div id="chatbotMessages" class="chatbot-messages">
            <!-- Welcome Message -->
            <div class="message bot-message">
                <div class="message-content">
                    <p>👋 Hello! I'm your AI assistant. I can help you find the perfect drama, movie, or series to watch!</p>
                    <p class="mb-0">Try asking me:</p>
                    <ul class="mb-0 mt-2">
                        <li>"What are the top-rated dramas?"</li>
                        <li>"Recommend a romantic comedy"</li>
                        <li>"Show me recent action movies"</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div id="chatbotSuggestions" class="chatbot-suggestions">
            <button class="suggestion-chip" data-message="What are the top-rated dramas?">
                Top rated dramas
            </button>
            <button class="suggestion-chip" data-message="Recommend a romantic comedy drama">
                Romantic comedy
            </button>
            <button class="suggestion-chip" data-message="Show me recent action movies">
                Action movies
            </button>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input">
            <form id="chatbotForm">
                <div class="input-group">
                    <input type="text" 
                           id="chatbotInput" 
                           class="form-control" 
                           placeholder="Ask me anything..."
                           autocomplete="off"
                           maxlength="500">
                    <button type="submit" class="btn btn-primary" id="chatbotSend">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            <small class="text-muted d-block mt-1 text-center">
                <i class="fas fa-info-circle"></i> AI responses may not always be accurate
            </small>
        </div>
    </div>
</div>

<style>
.chatbot-widget {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 1001;
}

.chatbot-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 28px;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
}

.chatbot-toggle .pulse-dot {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 16px;
    height: 16px;
    background: #10b981;
    border-radius: 50%;
    border: 3px solid white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

.chatbot-window {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 550px;
    max-height: calc(100vh - 100px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
    z-index: 1000;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.chatbot-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f8f9fa;
    min-height: 0;
}

.message {
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-content {
    padding: 12px 16px;
    border-radius: 12px;
    max-width: 85%;
    word-wrap: break-word;
}

.bot-message .message-content {
    background: white;
    border: 1px solid #e5e7eb;
    margin-right: auto;
}

.user-message {
    display: flex;
    justify-content: flex-end;
}

.user-message .message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    margin-left: auto;
}

.recommendation-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    margin-top: 8px;
    display: flex;
    gap: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.recommendation-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.recommendation-card img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.recommendation-info {
    flex: 1;
}

.recommendation-info h6 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.recommendation-info small {
    color: #6b7280;
}

.chatbot-suggestions {
    padding: 10px 16px;
    background: white;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 8px;
    overflow-x: auto;
    flex-shrink: 0;
}

.suggestion-chip {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 12px;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s;
}

.suggestion-chip:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.chatbot-input {
    padding: 16px;
    background: white;
    border-top: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.chatbot-input .input-group {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 25px;
    overflow: hidden;
}

.chatbot-input input {
    border: none;
    padding: 12px 20px;
}

.chatbot-input input:focus {
    box-shadow: none;
    outline: none;
}

.chatbot-input button {
    border: none;
    border-radius: 0;
    padding: 0 20px;
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    width: fit-content;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

@media (max-width: 768px) {
    .chatbot-window {
        width: calc(100vw - 30px);
        height: calc(100vh - 120px);
        right: 15px;
        bottom: 15px;
        border-radius: 12px;
    }
    
    .chatbot-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .chatbot-toggle {
        width: 55px;
        height: 55px;
        font-size: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('chatbotToggle');
    const close = document.getElementById('chatbotClose');
    const window = document.getElementById('chatbotWindow');
    const messages = document.getElementById('chatbotMessages');
    const form = document.getElementById('chatbotForm');
    const input = document.getElementById('chatbotInput');
    const suggestions = document.querySelectorAll('.suggestion-chip');

    // Toggle chatbot
    toggle.addEventListener('click', function() {
        if (window.style.display === 'none') {
            window.style.display = 'flex';
            toggle.style.display = 'none';
            input.focus();
        }
    });

    close.addEventListener('click', function() {
        window.style.display = 'none';
        toggle.style.display = 'block';
    });

    // Suggestion chips
    suggestions.forEach(chip => {
        chip.addEventListener('click', function() {
            const message = this.dataset.message;
            input.value = message;
            form.dispatchEvent(new Event('submit'));
        });
    });

    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        input.value = '';

        // Show typing indicator
        const typingIndicator = showTypingIndicator();

        try {
            // Send to backend
            const response = await fetch('<?php echo e(route('chatbot.chat')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            // Remove typing indicator
            typingIndicator.remove();

            if (data.success) {
                // Add bot response
                addMessage(data.message, 'bot');

                // Add recommendations if any
                if (data.recommendations && data.recommendations.length > 0) {
                    addRecommendations(data.recommendations);
                }
            } else {
                addMessage('Sorry, I encountered an error. Please try again.', 'bot');
            }
        } catch (error) {
            typingIndicator.remove();
            addMessage('Sorry, I encountered an error. Please try again.', 'bot');
            console.error('Chatbot error:', error);
        }
    });

    function addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = text.replace(/\n/g, '<br>');
        
        messageDiv.appendChild(contentDiv);
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTypingIndicator() {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot-message';
        
        const indicator = document.createElement('div');
        indicator.className = 'typing-indicator';
        indicator.innerHTML = '<span></span><span></span><span></span>';
        
        messageDiv.appendChild(indicator);
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
        
        return messageDiv;
    }

    function addRecommendations(recommendations) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot-message';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.style.maxWidth = '100%';
        
        recommendations.forEach(rec => {
            const card = document.createElement('a');
            card.href = rec.url;
            card.className = 'recommendation-card';
            card.target = '_blank';
            
            card.innerHTML = `
                <img src="${rec.poster}" alt="${rec.title}">
                <div class="recommendation-info">
                    <h6>${rec.title}</h6>
                    <small>${rec.type.charAt(0).toUpperCase() + rec.type.slice(1)}</small>
                    ${rec.rating > 0 ? `<br><small>⭐ ${rec.rating}/10</small>` : ''}
                </div>
            `;
            
            contentDiv.appendChild(card);
        });
        
        messageDiv.appendChild(contentDiv);
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }
});
</script>
<?php endif; ?>
<?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/partials/chatbot.blade.php ENDPATH**/ ?>