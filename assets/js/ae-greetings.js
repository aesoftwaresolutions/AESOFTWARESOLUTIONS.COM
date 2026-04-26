const greetingsTemplate = document.createElement('template');
greetingsTemplate.innerHTML = `
    <style>
        .greetings-content {
            text-align: left; /* Aligns with the grid layout for a cleaner look */
        }
        .title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-color, #212529);
            margin: 0 0 1.5rem 0;
        }
        .text {
            font-size: 1.15rem;
            color: var(--subtle-text, #6c757d);
            max-width: 550px;
            margin: 0;
        }
        @media (max-width: 992px) {
            .greetings-content {
                text-align: center;
            }
            .title {
                font-size: 2.5rem;
            }
            .text {
                margin: 0 auto;
            }
        }
    </style>
    <div class="greetings-content">
        <h1 class="title"></h1>
        <p class="text"></p>
    </div>
`;

class AeGreetings extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(greetingsTemplate.content.cloneNode(true));
    }

    connectedCallback() {
        this.shadowRoot.querySelector('.title').innerText = this.getAttribute('greeting-title') || 'Transform Your Business with AI';
        this.shadowRoot.querySelector('.text').innerText = this.getAttribute('greeting-text') || 'We build custom AI solutions that drive growth and efficiency.';
    }
}

customElements.define('ae-greetings', AeGreetings);