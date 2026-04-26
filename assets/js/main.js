document.addEventListener('DOMContentLoaded', () => {

    // ── Stat counter animation ─────────────────────────────────────────────
    const counters = document.querySelectorAll('.stat-number[data-target]');

    if (counters.length && 'IntersectionObserver' in window) {
        const ease = (t) => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;

        const animateCounter = (el) => {
            const target = parseFloat(el.dataset.target);
            const suffix = el.dataset.suffix || '';
            const duration = 1800;
            const start = performance.now();

            const tick = (now) => {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const value = Math.round(ease(progress) * target);
                el.textContent = value + suffix;
                if (progress < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        counters.forEach(el => observer.observe(el));
    }

    // ── Scroll-reveal for process steps ───────────────────────────────────
    const steps = document.querySelectorAll('.process-step');

    if (steps.length && 'IntersectionObserver' in window) {
        steps.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, i * 80);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        steps.forEach(el => revealObserver.observe(el));
    }

    // ── Fade-up for section headers ────────────────────────────────────────
    const fadeEls = document.querySelectorAll('.section-header, .reveal-inner');

    if (fadeEls.length && 'IntersectionObserver' in window) {
        fadeEls.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(32px)';
            el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        });

        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        fadeEls.forEach(el => fadeObserver.observe(el));
    }

});
