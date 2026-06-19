const ctaTemplate2 = document.createElement('template');
ctaTemplate2.innerHTML = `
    <style>
        :host { display: block; }

        .cta-section {
            background: #080808;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: clamp(80px, 9vw, 130px) clamp(1.5rem, 5vw, 4rem);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Dual radial orbs */
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 25% 60%, rgba(201,169,110,0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 75% 40%, rgba(100,80,220,0.045) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 100%, rgba(201,169,110,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Dot grid overlay */
        .cta-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.028) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
        }

        .cta-eyebrow {
            display: block;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #C9A96E;
            font-weight: 600;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(12px);
            animation: ctaFadeUp 0.55s cubic-bezier(0.16,1,0.3,1) 0.1s forwards;
        }

        .cta-title {
            font-family: 'DM Serif Display', Georgia, "Times New Roman", serif;
            font-size: clamp(2.5rem, 5vw, 5rem);
            font-weight: 400;
            color: #EDEDE9;
            line-height: 1.08;
            margin: 0 0 1.5rem 0;
            opacity: 0;
            transform: translateY(20px);
            animation: ctaFadeUp 0.75s cubic-bezier(0.16,1,0.3,1) 0.25s forwards;
        }

        .cta-title em {
            font-style: italic;
            color: #C9A96E;
        }

        .cta-subtitle {
            font-size: 1rem;
            color: #888884;
            line-height: 1.75;
            max-width: 520px;
            margin: 0 auto 2.75rem auto;
            opacity: 0;
            transform: translateY(14px);
            animation: ctaFadeUp 0.65s cubic-bezier(0.16,1,0.3,1) 0.4s forwards;
        }

        .cta-actions {
            opacity: 0;
            transform: translateY(12px);
            animation: ctaFadeUp 0.6s cubic-bezier(0.16,1,0.3,1) 0.55s forwards;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #C9A96E;
            color: #080808;
            padding: 1rem 2.5rem;
            border: 2px solid #C9A96E;
            border-radius: 4px;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background 0.32s ease, border-color 0.32s ease, color 0.32s ease, transform 0.24s ease, box-shadow 0.32s ease;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }

        .cta-button:hover { background: transparent; color: #C9A96E; transform: translateY(-2px); box-shadow: 0 8px 28px rgba(201,169,110,0.18); }
        .cta-button:hover::before { left: 160%; }

        .cta-note {
            display: block;
            margin-top: 1.25rem;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 0.775rem;
            color: rgba(136,136,132,0.55);
            letter-spacing: 0.05em;
        }

        /* Thin gold separator at top */
        .cta-rule {
            display: block;
            width: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(201,169,110,0.5), transparent);
            margin: 0 auto 0;
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            animation: ctaRule 1.4s cubic-bezier(0.16,1,0.3,1) 0.05s forwards;
        }

        @keyframes ctaFadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes ctaRule {
            to { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .cta-eyebrow, .cta-title, .cta-subtitle, .cta-actions {
                opacity: 1; transform: none; animation: none;
            }
            .cta-rule { width: 100%; animation: none; }
            .cta-button::before { display: none; }
            .cta-button:hover { transform: none; }
        }
    </style>
    <section class="cta-section" id="contact">
        <span class="cta-rule" aria-hidden="true"></span>
        <div class="cta-inner">
            <span class="cta-eyebrow">Let's work together</span>
            <h2 class="cta-title">Ready to build<br><em>something great?</em></h2>
            <p class="cta-subtitle">Tell us about your project and we'll get back to you within 24 hours with a plan to move forward.</p>
            <div class="cta-actions">
                <a href="/contact/" class="cta-button">Start a conversation</a>
                <span class="cta-note">No commitment. Free consultation.</span>
            </div>
        </div>
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
