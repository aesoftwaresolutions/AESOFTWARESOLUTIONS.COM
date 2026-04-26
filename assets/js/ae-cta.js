const ctaTemplate2 = document.createElement('template');
ctaTemplate2.innerHTML = `
    <style>
        :host {
            display: block;
            --primary-color: #007BFF;
            --primary-dark: #0056b3;
        }
        .cta-section {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 5rem 2.5rem;
            text-align: center;
            border-radius: 16px;
            margin: 5rem auto;
            max-width: 950px;
            box-shadow: 0 15px 40px rgba(0, 123, 255, 0.25);
        }
        .cta-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
            line-height: 1.2;
        }
        .cta-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 650px;
            margin: 0 auto 2.5rem auto;
        }
        .cta-button {
            background-color: white;
            color: var(--primary-color);
            padding: 1rem 2.5rem;
            border: 2px solid transparent;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .cta-button:hover, .cta-button:focus {
            background-color: transparent;
            color: white;
            border-color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
    </style>
    <section class="cta-section">
        <h2 class="cta-title">Ready to Build the Future?</h2>
        <p class="cta-subtitle">Let's turn your ideas into intelligent, powerful applications. Contact us today to get a free consultation and see how AE Softwares can help you innovate.</p>
        <a href="/contact" class="cta-button">Get Started</a>
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
