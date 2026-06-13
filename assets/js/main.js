document.addEventListener('DOMContentLoaded', () => {

    // ── Stat counter animation ─────────────────────────────────────────────
    // Runs once when the stats section scrolls into view (or after 800ms if
    // IntersectionObserver isn't available / fires late).
    const counters = document.querySelectorAll('.stat-number[data-target]');

    if (!counters.length) {
        initScrollHeader();
        return;
    }

    const ease = (t) => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;

    const runCounters = () => {
        counters.forEach(el => {
            if (el.dataset.animated) return;
            el.dataset.animated = '1';
            const target = parseFloat(el.dataset.target);
            const suffix = el.dataset.suffix || '';
            const duration = 1600;
            const start = performance.now();
            const tick = (now) => {
                const p = Math.min((now - start) / duration, 1);
                el.textContent = Math.round(ease(p) * target) + suffix;
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    };

    // Try IntersectionObserver first, fall back to a 1s timeout
    const statsSection = document.querySelector('.stats-section');
    if (statsSection && 'IntersectionObserver' in window) {
        const obs = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) { runCounters(); obs.disconnect(); }
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        obs.observe(statsSection);
    } else {
        setTimeout(runCounters, 1000);
    }

    initScrollHeader();

});

function initScrollHeader() {
    const h = document.querySelector('.site-header');
    if (!h) return;
    window.addEventListener('scroll', () => {
        h.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });
}
