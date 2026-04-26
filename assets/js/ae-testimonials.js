const testimonialsTemplate2 = document.createElement('template');
testimonialsTemplate2.innerHTML = `
    <style>
        :host { display: block; }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .testimonial-card {
            background: #141414;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
            transition: background 0.3s ease;
        }

        .testimonial-card:hover { background: #1a1a1a; }

        .quote-mark {
            font-family: Georgia, serif;
            font-size: 4rem;
            color: rgba(201,169,110,0.2);
            line-height: 0.8;
            user-select: none;
        }

        .testimonial-text {
            font-size: 0.95rem;
            color: #EDEDE9;
            line-height: 1.75;
            margin: 0;
            font-style: italic;
            flex-grow: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 1.25rem;
        }

        .author-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.1);
            filter: grayscale(20%);
        }

        .author-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #EDEDE9;
            letter-spacing: 0.02em;
        }

        .author-title {
            font-size: 0.775rem;
            color: #888884;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 0.2rem;
        }
    </style>
    <div class="testimonials-grid"></div>
`;

const testimonialsData = [
    {
        quote: "The AI chatbot AE Softwares built for us has been a game-changer. Our customer satisfaction is up 30%, and we've automated over 50% of our support inquiries.",
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
        quote: "The interactive data dashboard gives us a real-time pulse on our business. The clarity and insights we've gained are invaluable. Delivered beyond our expectations.",
        avatar: "https://randomuser.me/api/portraits/men/56.jpg",
        name: "David Rodriguez",
        title: "VP of Analytics, MarketMinds"
    }
];

class AeTestimonials extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.appendChild(testimonialsTemplate2.content.cloneNode(true));
    }

    connectedCallback() {
        const grid = this.shadowRoot.querySelector('.testimonials-grid');
        const showAll = this.getAttribute('show-all') !== 'false';
        const toShow = showAll ? testimonialsData : testimonialsData.slice(0, 3);

        toShow.forEach(t => {
            const card = document.createElement('div');
            card.classList.add('testimonial-card');
            card.innerHTML = `
                <div class="quote-mark">&ldquo;</div>
                <p class="testimonial-text">${t.quote}</p>
                <div class="testimonial-author">
                    <img src="${t.avatar}" alt="${t.name}" class="author-avatar">
                    <div>
                        <div class="author-name">${t.name}</div>
                        <div class="author-title">${t.title}</div>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }
}

customElements.define('ae-testimonials', AeTestimonials);
