const template = document.createElement('template');
template.innerHTML = `
    <style>
        :host {
            display: flex; /* Changed */
            flex-direction: column; /* Changed */
            height: 100%;
        }

        .chatbot-container {
            background: var(--card-background);
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-header {
            padding: 0.75rem 1.5rem; /* Adjusted padding */
            background-color: #f8f9fa; /* Lighter header */
            border-bottom: 1px solid #e9ecef;
            font-weight: 600; /* Adjusted font weight */
            color: var(--text-color);
            text-align: center;
            flex-shrink: 0; /* Prevent shrinking */
        }

        .chatbot-messages {
            flex-grow: 1;
            padding: 1rem; /* Adjusted padding */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .chatbot-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chatbot-messages::-webkit-scrollbar-thumb {
            background: #dcdcdc;
            border-radius: 3px;
        }

        .message {
            padding: 0.65rem 1.1rem; /* Adjusted padding */
            border-radius: 20px;
            max-width: 80%;
            line-height: 1.4; /* Adjusted line-height */
            font-size: 0.9rem;
        }

        .message.user {
            background-color: var(--primary-color);
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .message.bot {
            background-color: #e9ecef;
            color: var(--text-color);
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        .chatbot-input-container {
            padding: 0.75rem 1rem;
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa; /* Lighter background */
            flex-shrink: 0; /* Prevent shrinking */
        }

        .chatbot-input {
            display: flex;
            align-items: center;
            background-color: #fff; /* White input background */
            border-radius: 25px;
            padding: 0 0.5rem 0 1rem;
            border: 1px solid #ced4da;
        }
        
        .chatbot-input:focus-within {
             border-color: var(--primary-color);
             box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.25);
        }

        #chatbot-input-field {
            flex-grow: 1;
            border: none;
            background: transparent;
            color: var(--text-color);
            padding: 0.6rem 0;
            font-size: 0.9rem;
            outline: none;
        }

        #chatbot-send {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            transition: background-color 0.2s;
            flex-shrink: 0; /* Prevent shrinking */
        }

        #chatbot-send:hover {
            background-color: #3a7ac8; /* Darker hover */
        }
    </style>
    <div class="chatbot-container">
        <div class="chatbot-header">AE Assistant</div>
        <div class="chatbot-messages" id="chatbot-messages"></div>
        <div class="chatbot-input-container">
            <div class="chatbot-input">
                <input type="text" id="chatbot-input-field" placeholder="Ask a question...">
                <button id="chatbot-send" aria-label="Send Message">&#10148;</button>
            </div>
        </div>
    </div>
`;

class AEAssistant extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(template.content.cloneNode(true));

        this.chatbotMessages = this.shadowRoot.getElementById('chatbot-messages');
        this.chatbotInputField = this.shadowRoot.getElementById('chatbot-input-field');
        this.chatbotSendButton = this.shadowRoot.getElementById('chatbot-send');
        this.sessionId = null;
    }

    connectedCallback() {
        this.sessionId = crypto.randomUUID();
        this.chatbotSendButton.addEventListener('click', () => this.handleUserInput());
        this.chatbotInputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.handleUserInput();
            }
        });

        setTimeout(() => {
            this.addMessage('Hello! I am the AE Assistant. How can I help you today?', 'bot');
        }, 500);
    }

    handleUserInput() {
        const userInput = this.chatbotInputField.value.trim();
        if (!userInput) return;

        this.addMessage(userInput, 'user');
        this.chatbotInputField.value = '';
        this.getBotResponse(userInput);
    }

    async getBotResponse(userInput) {
        const proxyUrl = window.ae_settings?.proxy_url;

        if (!proxyUrl) {
            console.error('WordPress proxy URL is not configured. This should be set automatically by the theme.');
            this.addMessage('The chatbot is not configured correctly. Please contact the site administrator.', 'bot');
            return;
        }
        
        try {
            const response = await fetch(proxyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    message: userInput,
                    sessionId: this.sessionId
                }), 
            });

            if (!response.ok) {
                 const errorText = await response.text();
                 console.error('The WordPress proxy returned an error:', response.status, errorText);
                 this.addMessage('Sorry, there was an issue getting a response from the server. Please try again later.', 'bot');
                 return;
            }

            const botResponse = await response.json();
            const replyText = botResponse.data?.reply || botResponse.reply || "I'm not sure how to respond to that.";

            this.addMessage(replyText, 'bot');

        } catch (error) {
            console.error('Error sending data to the WordPress proxy:', error);
            this.addMessage('Sorry, there was an error sending your request. Please check your network connection.', 'bot');
        }
    }

    addMessage(message, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', sender);
        messageElement.textContent = message;
        this.chatbotMessages.appendChild(messageElement);
        this.chatbotMessages.scrollTop = this.chatbotMessages.scrollHeight;
    }
}

customElements.define('ae-assistant', AEAssistant);
