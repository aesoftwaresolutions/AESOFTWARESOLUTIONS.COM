/**
 * cinematic.js — AE Softwares Scroll Story
 * Stack: GSAP + ScrollTrigger + Lenis (no Three.js, no Spline)
 * Strategy: CSS 3D + parallax layers = cinematic feel at zero GPU cost
 * Mobile-first: all heavy effects gated behind matchMedia
 */

(function () {
  'use strict';

  // ─── 0. Wait for GSAP + ScrollTrigger to be ready ─────────────────────────
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    console.warn('[cinematic] GSAP or ScrollTrigger not found. Check enqueue order.');
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  // ─── 1. Lenis smooth scroll ↔ ScrollTrigger bridge ────────────────────────
  // Only init Lenis on desktop — touch devices have native momentum already
  let lenis;
  if (typeof Lenis !== 'undefined' && window.matchMedia('(pointer: fine)').matches) {
    lenis = new Lenis({ duration: 1.15, smoothWheel: true, smoothTouch: false });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
  }

  // ─── 2. Responsive + reduced-motion branches ──────────────────────────────
  const mm = gsap.matchMedia();

  mm.add(
    {
      isDesktop: '(min-width: 900px) and (prefers-reduced-motion: no-preference)',
      isTablet:  '(min-width: 600px) and (max-width: 899px) and (prefers-reduced-motion: no-preference)',
      isMobile:  '(max-width: 599px)',
      noMotion:  '(prefers-reduced-motion: reduce)',
    },
    (ctx) => {
      const { isDesktop, isTablet, noMotion } = ctx.conditions;
      const canAnimate = isDesktop || isTablet;

      // ── SECTION 1: Hero — staggered entrance + parallax depth layers ──────
      if (!noMotion) {
        // Stagger-in the hero text on load
        gsap.from('.hero-eyebrow, .hero-title, .hero-sub, .hero-badge', {
          opacity: 0,
          y: 40,
          stagger: 0.12,
          duration: 0.9,
          ease: 'power3.out',
          delay: 0.2,
        });

        // iPhone mock floats in from the right
        gsap.from('.iphone-container', {
          opacity: 0,
          x: 60,
          rotate: 4,
          duration: 1.1,
          ease: 'expo.out',
          delay: 0.5,
        });
      }

      if (isDesktop) {
        // Parallax depth layers — back moves slowest, front fastest
        // Back layer (dots grid background)
        gsap.to('.hero-bg-dots', {
          yPercent: -12,
          ease: 'none',
          scrollTrigger: {
            trigger: '.hero-section',
            start: 'top top',
            end: 'bottom top',
            scrub: 1.5,
          },
        });

        // Mid layer (floating orbs)
        gsap.to('.hero-orb', {
          yPercent: -28,
          ease: 'none',
          scrollTrigger: {
            trigger: '.hero-section',
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
          },
        });

        // iPhone drifts up faster (foreground)
        gsap.to('.hero-visual', {
          yPercent: -18,
          ease: 'none',
          scrollTrigger: {
            trigger: '.hero-section',
            start: 'top top',
            end: 'bottom top',
            scrub: 0.8,
          },
        });

        // Hero text fades + pushes up as we scroll away
        gsap.to('.hero-text', {
          opacity: 0,
          yPercent: -20,
          ease: 'none',
          scrollTrigger: {
            trigger: '.hero-section',
            start: '40% top',
            end: 'bottom top',
            scrub: 1,
          },
        });
      }

      // ── SECTION 2: Stats ticker — count-up on enter ───────────────────────
      if (canAnimate && !noMotion) {
        document.querySelectorAll('.stat-number[data-target]').forEach((el) => {
          const target = parseInt(el.dataset.target, 10);
          const suffix = el.dataset.suffix || '';
          ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: () => {
              gsap.fromTo(
                { val: 0 },
                { val: target, duration: 1.6, ease: 'power2.out',
                  onUpdate: function () { el.textContent = Math.round(this.targets()[0].val) + suffix; },
                }
              );
            },
          });
        });

        // Stats row slides in
        gsap.from('.stats-row .stat-item', {
          opacity: 0,
          y: 30,
          stagger: 0.15,
          duration: 0.7,
          ease: 'power2.out',
          scrollTrigger: { trigger: '.stats-section', start: 'top 80%' },
        });
      }

      // ── SECTION 3: Pinned "What we do" reveal (desktop only) ─────────────
      if (isDesktop && !noMotion) {
        const revealTl = gsap.timeline({
          scrollTrigger: {
            trigger: '.reveal-section',
            start: 'top top',
            end: '+=140%',
            pin: true,
            scrub: 1,
            anticipatePin: 1,
          },
        });

        revealTl
          .from('.reveal-label',   { opacity: 0, y: 20 }, 0)
          .from('.reveal-line-1',  { opacity: 0, y: 50, skewY: 3 }, 0.05)
          .from('.reveal-line-2',  { opacity: 0, y: 50, skewY: 3 }, 0.15)
          .from('.reveal-line-3',  { opacity: 0, y: 50, skewY: 3 }, 0.25)
          .from('.reveal-caret',   { scaleX: 0, transformOrigin: 'left center' }, 0.35)
          .to('.reveal-bg-number', { opacity: 0.06, scale: 1.08, ease: 'none' }, 0);
      } else if (!noMotion) {
        // Mobile/tablet: simple fade-in (no pin)
        gsap.from('.reveal-line-1, .reveal-line-2, .reveal-line-3', {
          opacity: 0,
          y: 30,
          stagger: 0.15,
          duration: 0.8,
          ease: 'power2.out',
          scrollTrigger: { trigger: '.reveal-section', start: 'top 75%' },
        });
      }

      // ── SECTION 4: Services — staggered card entrance ─────────────────────
      if (!noMotion) {
        gsap.from('ae-products', {
          opacity: 0,
          y: 50,
          duration: 0.9,
          ease: 'power3.out',
          scrollTrigger: { trigger: 'ae-products', start: 'top 80%' },
        });
      }

      // ── SECTION 5: Horizontal process track (desktop only) ───────────────
      if (isDesktop && !noMotion) {
        const track = document.querySelector('.process-track');
        if (track) {
          const getDistance = () => track.scrollWidth - window.innerWidth + 80;

          gsap.to(track, {
            x: () => -getDistance(),
            ease: 'none',
            scrollTrigger: {
              trigger: '.process-section',
              start: 'top top',
              end: () => '+=' + getDistance(),
              pin: true,
              scrub: true,
              invalidateOnRefresh: true,
              anticipatePin: 1,
            },
          });

          // Process step numbers count up as they enter
          document.querySelectorAll('.process-step').forEach((step, i) => {
            gsap.from(step, {
              opacity: 0,
              x: 40,
              duration: 0.6,
              ease: 'power2.out',
              scrollTrigger: {
                trigger: step,
                containerAnimation: ScrollTrigger.getAll().find(
                  (t) => t.vars.trigger === '.process-section'
                ),
                start: 'left 85%',
              },
            });
          });
        }
      } else if (!noMotion) {
        // Vertical fallback on mobile — simple stagger
        gsap.from('.process-step', {
          opacity: 0,
          y: 30,
          stagger: 0.15,
          duration: 0.7,
          ease: 'power2.out',
          scrollTrigger: { trigger: '.process-section', start: 'top 75%' },
        });
      }

      // ── SECTION 6: Testimonials ──────────────────────────────────────────
      if (!noMotion) {
        gsap.from('ae-testimonials', {
          opacity: 0,
          y: 40,
          duration: 0.9,
          ease: 'power3.out',
          scrollTrigger: { trigger: 'ae-testimonials', start: 'top 80%' },
        });
      }

      // ── SECTION 7: CTA — scale up from centre ────────────────────────────
      if (!noMotion) {
        gsap.from('ae-cta', {
          scale: 0.94,
          opacity: 0,
          duration: 1,
          ease: 'expo.out',
          scrollTrigger: {
            trigger: 'ae-cta',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
          },
        });
      }

      // ── Floating orbs: subtle idle rotation (desktop) ────────────────────
      if (isDesktop) {
        document.querySelectorAll('.hero-orb').forEach((orb, i) => {
          gsap.to(orb, {
            rotation: i % 2 === 0 ? 360 : -360,
            duration: 20 + i * 5,
            ease: 'none',
            repeat: -1,
            transformOrigin: '50% 50%',
          });
        });
      }
    }
  );

  // ─── 3. Refresh on font/image load ────────────────────────────────────────
  window.addEventListener('load', () => {
    ScrollTrigger.refresh();
  });

  // ─── 4. Debounced resize refresh ──────────────────────────────────────────
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => ScrollTrigger.refresh(), 150);
  });

})();
