const testimonialsTemplate2 = document.createElement('template');
testimonialsTemplate2.innerHTML = `
    <style>
        :host {
            display: block;
            --surface-color: #ffffff;
            --text-color: #212529;
            --subtle-text: #6c757d;
            --shadow-color: rgba(0, 0, 0, 0.06);
            --border-color: #dee2e6;
        }
        .testimonials-section {
            padding: 5rem 2rem;
            background-color: var(--surface-color);
        }
        .section-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--text-color);
            text-align: center;
            margin: 0 0 3.5rem 0;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .testimonial-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px var(--shadow-color);
            position: relative;
        }
        .testimonial-card::before {
            content: 'â€œ';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 5rem;
            color: rgba(0, 123, 255, 0.1);
            line-height: 1;
            z-index: 1;
        }
        .testimonial-text {
            font-style: italic;
            font-size: 1.1rem;
            color: var(--subtle-text);
            margin: 0 0 1.5rem 0;
            flex-grow: 1;
            z-index: 2;
            position: relative;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            margin-top: auto;
            z-index: 2;
            position: relative;
        }
        .author-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            margin-right: 1rem;
            border: 2px solid var(--border-color);
        }
        .author-info {
            line-height: 1.3;
        }
        .author-name {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--text-color);
        }
        .author-title {
            color: var(--subtle-text);
            font-size: 0.95rem;
        }
    </style>
    <section class="testimonials-section">
        <h2 class="section-title">What Our Clients Say</h2>
        <div class="testimonials-grid"></div>
    </section>
`;

const testimonialsData = [
    {
        quote: "The AI chatbot AE Softwares built for us has been a game-changer. Our customer satisfaction is up 30%, and we've automated over 50% of our support inquiries!",
        avatar: "https://randomuser.me/api/portraits/men/32.jpg",
        name: "Johnathan Miller",
        title: "CEO, Innovate Inc."
    },
    {
        quote: "I was blown away by the workflow automation solution. It has saved our team countless hours and eliminated human error in our reporting. Truly transformative.",
        avatar: "https://randomuser.me/api/portraits/women/44.jpg",
        name: "Sarah Chen",
        title: "Director of Operations, FutureGadget"
    },
    {
        quote: "The interactive data dashboard gives us a real-time pulse on our business. The clarity and insights we've gained are invaluable. The team was professional and delivered beyond our expectations.",
        avatar: "https://randomuser.me/api/portraits/men/56.jpg",
        name: "David Rodriguez",
        title: "VP of Analytics, MarketMinds"
    },
    {
        quote: "Our new website is not only beautiful but also incredibly fast and functional. The AI-driven content suggestions are a feature we didn't even know we needed but now can't live without.",
        avatar: "https://randomuser.me/api/portraits/women/21.jpg",
        name: "Emily White",
        title: "Marketing Manager, Stellar Goods"
    },
];

class AeTestimonials extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(testimonialsTemplate2.content.cloneNode(true));
    }

    connectedCallback() {
        const grid = this.shadowRoot.querySelector('.testimonials-grid');
        const title = this.shadowRoot.querySelector('.section-title');
        const showAll = this.hasAttribute('show-all');
        const testimonialsToShow = showAll ? testimonialsData : testimonialsData.slice(0, 3);

        if (this.hasAttribute('title')) {
            title.innerText = this.getAttribute('title');
        }

        testimonialsToShow.forEach(testimonial => {
            const card = document.createElement('div');
            card.classList.add('testimonial-card');
            card.innerHTML = `
                <p class="testimonial-text">${testimonial.quote}</p>
                <div class="testimonial-author">
                    <img src="${testimonial.avatar}" alt="Avatar of ${testimonial.name}" class="author-avatar">
                    <div class="author-info">
                        <div class="author-name">${testimonial.name}</div>
                        <div class="author-title">${testimonial.title}</div>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }
}

customElements.define('ae-testimonials', AeTestimonials);
