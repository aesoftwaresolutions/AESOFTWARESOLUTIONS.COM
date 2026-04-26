const productsTemplate2 = document.createElement('template');
productsTemplate2.innerHTML = `
    <style>
        :host {
            display: block;
            --primary-color: #007BFF;
            --surface-color: #ffffff;
            --text-color: #212529;
            --subtle-text: #6c757d;
            --shadow-color: rgba(0, 0, 0, 0.07);
            --border-color: #dee2e6;
        }
        .products-section {
            padding: 4rem 2rem;
            background-color: var(--background-color, #f8f9fa);
        }
        .section-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--text-color);
            text-align: center;
            margin: 0 0 3rem 0;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .product-card {
            background-color: var(--surface-color);
            border-radius: 12px;
            box-shadow: 0 4px 25px var(--shadow-color);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.09);
        }
        .product-icon {
            font-size: 2.5rem;
            line-height: 1;
            padding: 1.5rem;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
        }
        .product-content {
            padding: 1.5rem;
            flex-grow: 1;
        }
        h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0 0 0.75rem 0;
            color: var(--text-color);
        }
        p {
            color: var(--subtle-text);
            line-height: 1.7;
            margin: 0;
        }
    </style>
    <section class="products-section">
        <h2 class="section-title">Our Core Services</h2>
        <div class="products-grid"></div>
    </section>
`;

const productsData = [
    {
        icon: 'ðŸ¤–',
        title: 'Custom AI Chatbots',
        description: 'Engage your users with intelligent, 24/7 conversational AI. We design and build chatbots that provide support, generate leads, and automate customer interactions seamlessly.'
    },
    {
        icon: 'âš™ï¸',
        title: 'Workflow Automation',
        description: 'Streamline your business processes by automating repetitive tasks. We create robust systems that connect your apps, process data, and improve operational efficiency.'
    },
    {
        icon: 'ðŸ“Š',
        title: 'Interactive Data Dashboards',
        description: 'Turn complex data into actionable insights. We develop beautiful, intuitive dashboards that help you visualize your KPIs and make data-driven decisions with confidence.'
    },
    {
        icon: 'ðŸŒ',
        title: 'Web & Application Development',
        description: 'From marketing websites to complex SaaS platforms, our team builds high-performance, scalable web applications tailored to your specific business needs using modern technologies.'
    },
    {
        icon: 'ðŸ§ ',
        title: 'AI-Powered Content Generation',
        description: 'Leverage the latest generative AI models to create high-quality content at scale. We integrate AI to help you produce blog posts, marketing copy, and more, saving you time and resources.'
    },
    {
        icon: 'â˜ï¸',
        title: 'Cloud Solutions & Integration',
        description: 'We design and implement scalable cloud infrastructure and ensure your various software systems communicate effectively, creating a unified and powerful digital ecosystem.'
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
        const title = this.shadowRoot.querySelector('.section-title');
        const showAll = this.hasAttribute('show-all');
        const productsToShow = showAll ? productsData : productsData.slice(0, 3);

        if (this.hasAttribute('title')) {
            title.innerText = this.getAttribute('title');
        }

        productsToShow.forEach(product => {
            const card = document.createElement('div');
            card.classList.add('product-card');
            card.innerHTML = `
                <div class="product-icon">${product.icon}</div>
                <div class="product-content">
                    <h3>${product.title}</h3>
                    <p>${product.description}</p>
                </div>
            `;
            grid.appendChild(card);
        });
    }
}

customElements.define('ae-products', AeProducts);
