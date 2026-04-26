const productsTemplate2 = document.createElement('template');
productsTemplate2.innerHTML = `
    <style>
        :host { display: block; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .product-card {
            background: #141414;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: background 0.3s ease;
            cursor: default;
        }

        .product-card:hover { background: #1a1a1a; }

        .product-num {
            font-family: Georgia, serif;
            font-size: 2rem;
            color: rgba(201,169,110,0.3);
            line-height: 1;
            font-weight: 400;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #EDEDE9;
            letter-spacing: 0.01em;
            margin: 0;
        }

        .product-desc {
            font-size: 0.875rem;
            color: #888884;
            line-height: 1.7;
            margin: 0;
            flex-grow: 1;
        }

        .product-tag {
            display: inline-block;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #C9A96E;
            border-top: 1px solid rgba(201,169,110,0.25);
            padding-top: 1rem;
            margin-top: auto;
        }
    </style>
    <div class="products-grid"></div>
`;

const productsData = [
    {
        title: 'Custom AI Chatbots',
        description: 'Intelligent 24/7 conversational AI that supports customers, generates leads, and automates interactions seamlessly.',
        tag: 'Conversational AI'
    },
    {
        title: 'Workflow Automation',
        description: 'Streamline business processes by automating repetitive tasks and connecting your apps into a unified system.',
        tag: 'Process Automation'
    },
    {
        title: 'Data Dashboards',
        description: 'Turn complex data into actionable insights with intuitive, real-time dashboards built around your KPIs.',
        tag: 'Analytics'
    },
    {
        title: 'Web & App Development',
        description: 'High-performance, scalable web applications — from marketing sites to full SaaS platforms — built with modern tech.',
        tag: 'Development'
    },
    {
        title: 'AI Content Generation',
        description: 'Leverage generative AI to produce blog posts, marketing copy, and product content at scale with your voice.',
        tag: 'Generative AI'
    },
    {
        title: 'Cloud & Integration',
        description: 'Scalable cloud infrastructure and seamless software integrations that unify your digital ecosystem.',
        tag: 'Infrastructure'
    }
];

class AeProducts extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(productsTemplate2.content.cloneNode(true));
    }

    connectedCallback() {
        const grid = this.shadowRoot.querySelector('.products-grid');
        const showAll = this.getAttribute('show-all') !== 'false';
        const productsToShow = showAll ? productsData : productsData.slice(0, 4);

        productsToShow.forEach((product, i) => {
            const card = document.createElement('div');
            card.classList.add('product-card');
            card.innerHTML = `
                <div class="product-num">0${i + 1}</div>
                <h3 class="product-title">${product.title}</h3>
                <p class="product-desc">${product.description}</p>
                <span class="product-tag">${product.tag}</span>
            `;
            grid.appendChild(card);
        });
    }
}

customElements.define('ae-products', AeProducts);
