const ctaTemplate2 = document.createElement('template');
ctaTemplate2.innerHTML = `
    <style>
        :host { display: block; }

        .cta-section {
            background: #0f0f0f;
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: clamp(80px, 12vw, 140px) clamp(1.5rem, 5vw, 4rem);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(201,169,110,0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-eyebrow {
            display: block;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #888884;
            margin-bottom: 1.5rem;
        }

        .cta-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(2.5rem, 5vw, 5rem);
            font-weight: 400;
            color: #EDEDE9;
            line-height: 1.1;
            margin: 0 0 1.5rem 0;
        }

        .cta-title em {
            font-style: italic;
            color: #C9A96E;
        }

        .cta-subtitle {
            font-size: 1rem;
            color: #888884;
            line-height: 1.7;
            max-width: 520px;
            margin: 0 auto 2.5rem auto;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #C9A96E;
            color: #080808;
            padding: 0.9rem 2.25rem;
            border: 2px solid #C9A96E;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        .cta-button:hover, .cta-button:focus {
            background: transparent;
            color: #C9A96E;
            border-color: #C9A96E;
        }

        .cta-note {
            display: block;
            margin-top: 1.25rem;
            font-size: 0.775rem;
            color: rgba(136,136,132,0.6);
            letter-spacing: 0.05em;
        }
    </style>
    <section class="cta-section" id="contact">
        <span class="cta-eyebrow">Let's work together</span>
        <h2 class="cta-title">Ready to build<br><em>something great?</em></h2>
        <p class="cta-subtitle">Tell us about your project and we'll get back to you within 24 hours with a plan to move forward.</p>
        <a href="mailto:anthony@aesoftwaresolutions.com" class="cta-button">Start a conversation</a>
        <span class="cta-note">No commitment. Free consultation.</span>
    </section>
`;

class AeCta extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(ctaTemplate2.content.cloneNode(true));
    }
}

customElements.define('ae-cta', AeCta);
