const assessmentTemplate = document.createElement('template');
assessmentTemplate.innerHTML = `
<style>
    :host {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
    }

    * { box-sizing: border-box; }

    .chat-wrap {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #111111;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 4px;
        overflow: hidden;
    }

    .chat-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .chat-header__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #C9A96E;
        flex-shrink: 0;
    }

    .chat-header__dot--pulse {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .chat-header__title {
        font-size: 0.8rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #888884;
        font-weight: 500;
    }

    .chat-header__status {
        margin-left: auto;
        font-size: 0.72rem;
        color: #888884;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        scroll-behavior: smooth;
    }

    .chat-messages::-webkit-scrollbar { width: 4px; }
    .chat-messages::-webkit-scrollbar-track { background: transparent; }
    .chat-messages::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 2px;
    }

    .msg {
        max-width: 78%;
        line-height: 1.6;
        font-size: 0.9375rem;
        color: #EDEDE9;
    }

    .msg--bot {
        align-self: flex-start;
    }

    .msg--user {
        align-self: flex-end;
        background: rgba(201,169,110,0.12);
        border: 1px solid rgba(201,169,110,0.2);
        padding: 0.75rem 1rem;
        border-radius: 4px 4px 0 4px;
        color: #EDEDE9;
    }

    .msg--bot .msg-inner {
        background: #141414;
        border: 1px solid rgba(255,255,255,0.08);
        padding: 0.875rem 1rem;
        border-radius: 0 4px 4px 4px;
    }

    .msg-inner p { margin: 0 0 0.5rem 0; }
    .msg-inner p:last-child { margin-bottom: 0; }

    .typing-indicator {
        align-self: flex-start;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.875rem 1rem;
        background: #141414;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 0 4px 4px 4px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #888884;
        animation: typingBounce 1.4s ease-in-out infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-4px); opacity: 1; }
    }

    .chat-input-wrap {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.08);
        flex-shrink: 0;
        background: #111111;
    }

    .chat-input-row {
        display: flex;
        gap: 0.75rem;
        align-items: flex-end;
    }

    .chat-input {
        flex: 1;
        background: #141414;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 4px;
        padding: 0.75rem 1rem;
        color: #EDEDE9;
        font-size: 0.9375rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        line-height: 1.5;
        resize: none;
        min-height: 48px;
        max-height: 140px;
        transition: border-color 0.2s;
        overflow-y: auto;
    }

    .chat-input:focus {
        outline: none;
        border-color: rgba(201,169,110,0.4);
    }

    .chat-input::placeholder { color: #888884; }

    .chat-send {
        background: #C9A96E;
        color: #080808;
        border: none;
        border-radius: 4px;
        width: 48px;
        height: 48px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s;
        font-size: 1.1rem;
    }

    .chat-send:hover:not(:disabled) { background: #a8853d; }
    .chat-send:disabled { opacity: 0.4; cursor: not-allowed; }

    .chat-error {
        font-size: 0.8rem;
        color: #e07070;
        margin-top: 0.5rem;
    }
</style>
<div class="chat-wrap">
    <div class="chat-header">
        <span class="chat-header__dot chat-header__dot--pulse"></span>
        <span class="chat-header__title">Operational Intelligence Assessment</span>
        <span class="chat-header__status" id="chat-status">Ready</span>
    </div>
    <div class="chat-messages" id="chat-messages"></div>
    <div class="chat-input-wrap">
        <div class="chat-input-row">
            <textarea class="chat-input" id="chat-input" placeholder="Type your response…" rows="1" aria-label="Your response"></textarea>
            <button class="chat-send" id="chat-send" aria-label="Send">&#10148;</button>
        </div>
        <div class="chat-error" id="chat-error" hidden></div>
    </div>
</div>
`;

class AEAssessment extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(assessmentTemplate.content.cloneNode(true));

        this._messages  = this.shadowRoot.getElementById('chat-messages');
        this._input     = this.shadowRoot.getElementById('chat-input');
        this._send      = this.shadowRoot.getElementById('chat-send');
        this._status    = this.shadowRoot.getElementById('chat-status');
        this._error     = this.shadowRoot.getElementById('chat-error');
        this._history   = [];
        this._busy      = false;
    }

    connectedCallback() {
        this._endpoint = this.getAttribute('endpoint') || '/wp-json/aesoftwares/v1/assessment';

        this._send.addEventListener('click', () => this._handleSend());
        this._input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this._handleSend();
            }
        });
        this._input.addEventListener('input', () => this._autoResize());

        // Show the opening greeting instantly (no API call) so there's zero wait on init.
        // The first real API call fires when the user submits their first reply.
        this._showOpener();
    }

    _showOpener() {
        const opener = "Hello! I'm your Operational Intelligence Consultant with AE Software Solutions.\n\nI'll be guiding you through a structured business assessment — we'll map your operations, surface compliance risks, and identify where automation can have the biggest impact.\n\nTo get started, what's the name of your business?";
        // Push it into history so the model has context when the user replies
        this._history.push({ role: 'assistant', content: opener });
        this._appendMsg(opener, 'bot');
        this._input.focus();
    }

    _autoResize() {
        const el = this._input;
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 140) + 'px';
    }

    _handleSend() {
        const text = this._input.value.trim();
        if (!text || this._busy) return;

        this._appendMsg(text, 'user');
        this._history.push({ role: 'user', content: text });
        this._input.value = '';
        this._input.style.height = 'auto';
        this._fetchReply(text);
    }

    async _fetchReply(userMessage) {
        this._busy = true;
        this._send.disabled = true;
        this._status.textContent = 'Analyzing…';
        this._error.hidden = true;

        const typing = this._showTyping();

        try {
            const body = userMessage
                ? { message: userMessage, history: this._history.slice(0, -1) }
                : { message: 'BEGIN_ASSESSMENT', history: [] };

            const res = await fetch(this._endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body),
            });

            this._removeTyping(typing);

            if (!res.ok) {
                const err = await res.text();
                throw new Error(err || 'Server error');
            }

            const data = await res.json();
            const reply = data.reply || data.data?.reply || '';

            if (!reply) throw new Error('Empty response from server.');

            this._history.push({ role: 'assistant', content: reply });
            this._appendMsg(reply, 'bot');

        } catch (err) {
            this._removeTyping(typing);
            this._error.textContent = 'Something went wrong. Please try again.';
            this._error.hidden = false;
            console.error('[ae-assessment]', err);
        } finally {
            this._busy = false;
            this._send.disabled = false;
            this._status.textContent = 'Ready';
            this._input.focus();
        }
    }

    _appendMsg(text, sender) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('msg', 'msg--' + sender);

        if (sender === 'bot') {
            const inner = document.createElement('div');
            inner.classList.add('msg-inner');
            inner.innerHTML = this._renderText(text);
            wrapper.appendChild(inner);
        } else {
            wrapper.textContent = text;
        }

        this._messages.appendChild(wrapper);
        this._messages.scrollTop = this._messages.scrollHeight;
    }

    _renderText(text) {
        // Basic markdown: bold, line breaks, numbered lists
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/^/, '<p>')
            .replace(/$/, '</p>');
    }

    _showTyping() {
        const el = document.createElement('div');
        el.classList.add('typing-indicator');
        el.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
        this._messages.appendChild(el);
        this._messages.scrollTop = this._messages.scrollHeight;
        return el;
    }

    _removeTyping(el) {
        if (el && el.parentNode) el.parentNode.removeChild(el);
    }
}

customElements.define('ae-assessment', AEAssessment);
