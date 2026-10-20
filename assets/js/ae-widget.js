(function() {
  // ══════════════════════════════════════════════════════════════════════════════
  // CONFIGURATION
  // ══════════════════════════════════════════════════════════════════════════════

  const BASE_URL = window.AE_WIDGET_URL || 'https://aesoftwaresolutions.com';
  const CHAT_ENDPOINT = `${BASE_URL}/api/widget/chat`;

  // ══════════════════════════════════════════════════════════════════════════════
  // STATE MANAGEMENT
  // ══════════════════════════════════════════════════════════════════════════════

  let sessionId = null;
  let visitorId = null;
  let isOpen = false;
  let isWaiting = false;

  // ══════════════════════════════════════════════════════════════════════════════
  // INITIALIZATION
  // ══════════════════════════════════════════════════════════════════════════════

  function initializeVisitor() {
    // Get or create persistent visitor ID
    const stored = localStorage.getItem('ae_widget_visitor_id');
    if (stored) {
      visitorId = stored;
    } else {
      visitorId = crypto.randomUUID();
      localStorage.setItem('ae_widget_visitor_id', visitorId);
    }

    // Get session ID from sessionStorage (exists during page navigation)
    sessionId = sessionStorage.getItem('ae_widget_session_id');
  }

  function injectStyles() {
    const styleId = 'ae-widget-styles';
    if (document.getElementById(styleId)) {
      return; // Already injected
    }

    const style = document.createElement('style');
    style.id = styleId;
    style.textContent = `
      /* ──────────────────────────────────────────────────────────────────────── */
      /* Chat Widget Container */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        z-index: 999999;
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Floating Button */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--gold, #C9A96E);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgb(var(--ink-rgb, 18 18 18) / .15);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
      }

      .ae-widget-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgb(var(--gold-rgb, 201 169 110) / .35);
      }

      .ae-widget-button:active {
        transform: scale(0.95);
      }

      /* Chat bubble SVG icon (ink on gold) */
      .ae-widget-button svg {
        width: 28px;
        height: 28px;
        stroke: var(--ink, #121212);
        stroke-width: 2;
        fill: none;
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Chat Panel */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-panel {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 380px;
        height: 520px;
        background-color: var(--surface, #FFFFFF);
        border: 1px solid var(--border-strong, rgba(18, 18, 18, 0.22));
        border-radius: 12px;
        box-shadow: 0 8px 24px rgb(var(--ink-rgb, 18 18 18) / .16);
        display: flex;
        flex-direction: column;
        visibility: hidden;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
      }

      .ae-widget-panel.open {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
      }

      @media (max-width: 480px) {
        .ae-widget-panel {
          position: fixed;
          bottom: 0;
          right: 0;
          left: 0;
          top: auto;
          width: 100%;
          height: 100vh;
          border-radius: 0;
          max-height: 100vh;
          transform: translateY(100%);
        }

        .ae-widget-panel.open {
          transform: translateY(0);
        }
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Panel Header */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-header {
        background-color: var(--surface, #FFFFFF);
        border-bottom: 1px solid var(--border, rgba(18, 18, 18, 0.10));
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-shrink: 0;
      }

      .ae-widget-header-text h3 {
        font-family: var(--font-main, "Plus Jakarta Sans", sans-serif);
        font-weight: 600;
        font-size: 18px;
        color: var(--gold-ink, #7A5F20);
        margin: 0 0 4px 0;
      }

      .ae-widget-header-text p {
        font-size: 12px;
        color: var(--text-secondary, #5E5E5A);
        margin: 0;
      }

      .ae-widget-close-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-tertiary, #6E6E6A);
        font-size: 18px;
        transition: color 0.2s ease;
      }

      .ae-widget-close-btn:hover {
        color: var(--text, #121212);
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Messages Area */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-messages {
        flex: 1;
        overflow-y: auto;
        background-color: var(--bg, #F7F7F5);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
      }

      /* Scrollbar styling */
      .ae-widget-messages::-webkit-scrollbar {
        width: 6px;
      }

      .ae-widget-messages::-webkit-scrollbar-track {
        background: var(--bg, #F7F7F5);
      }

      .ae-widget-messages::-webkit-scrollbar-thumb {
        background: var(--border-strong, rgba(18, 18, 18, 0.22));
        border-radius: 3px;
      }

      .ae-widget-messages::-webkit-scrollbar-thumb:hover {
        background: var(--border-hover, rgba(18, 18, 18, 0.32));
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Message Bubbles */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-message {
        display: flex;
        margin-bottom: 4px;
      }

      .ae-widget-message.user {
        justify-content: flex-end;
      }

      .ae-widget-message.assistant {
        justify-content: flex-start;
      }

      .ae-widget-message-bubble {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
      }

      .ae-widget-message.user .ae-widget-message-bubble {
        background-color: var(--gold, #C9A96E);
        color: var(--ink, #121212);
      }

      .ae-widget-message.assistant .ae-widget-message-bubble {
        background-color: var(--surface, #FFFFFF);
        border: 1px solid var(--border, rgba(18, 18, 18, 0.10));
        color: var(--text, #121212);
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Typing Indicator */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-typing {
        display: flex;
        gap: 4px;
        padding: 10px 14px;
        background-color: var(--surface, #FFFFFF);
        border: 1px solid var(--border, rgba(18, 18, 18, 0.10));
        border-radius: 8px;
        width: fit-content;
      }

      .ae-widget-typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: var(--text-tertiary, #6E6E6A);
        animation: ae-widget-bounce 1.4s infinite;
      }

      .ae-widget-typing-dot:nth-child(2) {
        animation-delay: 0.2s;
      }

      .ae-widget-typing-dot:nth-child(3) {
        animation-delay: 0.4s;
      }

      @keyframes ae-widget-bounce {
        0%, 60%, 100% {
          transform: translateY(0);
          opacity: 0.6;
        }
        30% {
          transform: translateY(-8px);
          opacity: 1;
        }
      }

      /* Reduce motion support */
      @media (prefers-reduced-motion: reduce) {
        .ae-widget-panel,
        .ae-widget-button {
          transition: none;
        }

        @keyframes ae-widget-bounce {
          0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.6;
          }
          30% {
            transform: translateY(0);
            opacity: 1;
          }
        }
      }

      /* ──────────────────────────────────────────────────────────────────────── */
      /* Input Area */
      /* ──────────────────────────────────────────────────────────────────────── */

      .ae-widget-input-area {
        border-top: 1px solid var(--border, rgba(18, 18, 18, 0.10));
        padding: 12px;
        display: flex;
        gap: 8px;
        background-color: var(--surface, #FFFFFF);
        flex-shrink: 0;
      }

      .ae-widget-input {
        flex: 1;
        background-color: var(--bg, #F7F7F5);
        border: 1px solid var(--border-strong, rgba(18, 18, 18, 0.22));
        color: var(--text, #121212);
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 16px; /* 16px minimum: iOS Safari zooms the page on smaller inputs */
        font-family: Inter, sans-serif;
        outline: none;
        transition: background-color 0.2s ease;
      }

      .ae-widget-input:focus {
        background-color: var(--surface, #FFFFFF);
        border-color: var(--gold, #C9A96E);
      }

      .ae-widget-input::placeholder {
        color: var(--text-tertiary, #6E6E6A);
      }

      .ae-widget-send-btn {
        background-color: var(--gold, #C9A96E);
        border: none;
        color: var(--ink, #121212);
        cursor: pointer;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        transition: opacity 0.2s ease, transform 0.2s ease;
      }

      .ae-widget-send-btn:hover {
        opacity: 0.9;
      }

      .ae-widget-send-btn:active {
        transform: scale(0.95);
      }

      .ae-widget-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
    `;

    document.head.appendChild(style);
  }

  function createChatIcon() {
    // SVG chat bubble icon
    return `<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
    </svg>`;
  }

  function createWidget() {
    const container = document.createElement('div');
    container.className = 'ae-widget-container';

    // Button
    const button = document.createElement('button');
    button.className = 'ae-widget-button';
    button.innerHTML = createChatIcon();
    button.title = 'Chat with Ava';

    // Panel
    const panel = document.createElement('div');
    panel.className = 'ae-widget-panel';

    // Header
    const header = document.createElement('div');
    header.className = 'ae-widget-header';
    header.innerHTML = `
      <div class="ae-widget-header-text">
        <h3>AE Software Solutions</h3>
        <p>Ask Ava anything</p>
      </div>
      <button class="ae-widget-close-btn" title="Close">✕</button>
    `;

    // Messages container
    const messagesArea = document.createElement('div');
    messagesArea.className = 'ae-widget-messages';

    // Input area
    const inputArea = document.createElement('div');
    inputArea.className = 'ae-widget-input-area';

    const input = document.createElement('input');
    input.className = 'ae-widget-input';
    input.type = 'text';
    input.placeholder = 'Type your message...';

    const sendBtn = document.createElement('button');
    sendBtn.className = 'ae-widget-send-btn';
    sendBtn.textContent = 'Send';

    inputArea.appendChild(input);
    inputArea.appendChild(sendBtn);

    // Assemble panel
    panel.appendChild(header);
    panel.appendChild(messagesArea);
    panel.appendChild(inputArea);

    // Assemble container
    container.appendChild(button);
    container.appendChild(panel);

    // ────────────────────────────────────────────────────────────────────────────
    // EVENT HANDLERS
    // ────────────────────────────────────────────────────────────────────────────

    const closeBtn = header.querySelector('.ae-widget-close-btn');

    function togglePanel() {
      isOpen = !isOpen;
      if (isOpen) {
        panel.classList.add('open');
        // Auto-focus input
        setTimeout(() => input.focus(), 300);
        // Load initial greeting if first open
        if (messagesArea.children.length === 0) {
          loadInitialGreeting();
        }
      } else {
        panel.classList.remove('open');
      }
    }

    function loadInitialGreeting() {
      const greeting = 'Hi! I\'m Ava from AE Software Solutions. What kind of business do you run?';
      addMessage('assistant', greeting);
    }

    function addMessage(role, text) {
      const msgDiv = document.createElement('div');
      msgDiv.className = `ae-widget-message ${role}`;

      const bubble = document.createElement('div');
      bubble.className = 'ae-widget-message-bubble';
      bubble.textContent = text;

      msgDiv.appendChild(bubble);
      messagesArea.appendChild(msgDiv);

      // Auto-scroll to bottom
      messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function showTypingIndicator() {
      const msgDiv = document.createElement('div');
      msgDiv.className = 'ae-widget-message assistant';

      const typingDiv = document.createElement('div');
      typingDiv.className = 'ae-widget-typing';

      for (let i = 0; i < 3; i++) {
        const dot = document.createElement('div');
        dot.className = 'ae-widget-typing-dot';
        typingDiv.appendChild(dot);
      }

      msgDiv.appendChild(typingDiv);
      messagesArea.appendChild(msgDiv);
      messagesArea.scrollTop = messagesArea.scrollHeight;

      return msgDiv;
    }

    function removeTypingIndicator(element) {
      element.remove();
    }

    async function sendMessage() {
      const text = input.value.trim();
      if (!text || isWaiting) return;

      // Add user message to UI
      addMessage('user', text);
      input.value = '';
      input.disabled = true;
      sendBtn.disabled = true;
      isWaiting = true;

      // Show typing indicator
      const typingEl = showTypingIndicator();

      try {
        const response = await fetch(CHAT_ENDPOINT, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            message: text,
            session_id: sessionId,
            visitor_id: visitorId,
          }),
        });

        removeTypingIndicator(typingEl);

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Update session ID for future messages
        if (data.session_id) {
          sessionId = data.session_id;
          sessionStorage.setItem('ae_widget_session_id', sessionId);
        }

        // Add assistant response
        addMessage('assistant', data.reply);

        // Note: lead_captured is handled silently (no visible UI change)
      } catch (error) {
        removeTypingIndicator(typingEl);
        console.error('Widget error:', error);
        addMessage('assistant', 'Sorry, I had an issue. Could you try again?');
      } finally {
        input.disabled = false;
        sendBtn.disabled = false;
        isWaiting = false;
        input.focus();
      }
    }

    // Button click
    button.addEventListener('click', togglePanel);

    // Close button
    closeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      togglePanel();
    });

    // Input enter key
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !isWaiting) {
        e.preventDefault();
        sendMessage();
      }
    });

    // Send button click
    sendBtn.addEventListener('click', sendMessage);

    return container;
  }

  // ══════════════════════════════════════════════════════════════════════════════
  // ENTRY POINT
  // ══════════════════════════════════════════════════════════════════════════════

  function init() {
    // Ensure DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
      return;
    }

    // Initialize state
    initializeVisitor();

    // Inject styles
    injectStyles();

    // Create and mount widget
    const widget = createWidget();
    document.body.appendChild(widget);
  }

  // Start initialization
  init();
})();
