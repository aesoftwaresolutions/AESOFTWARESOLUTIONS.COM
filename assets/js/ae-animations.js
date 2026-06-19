/**
 * AE Software Solutions — Scroll & Load Animations
 * Inspired by Opal Camera's design language:
 *   • Black page loader that fades out after load
 *   • Scroll-driven text reveal (words illuminate as you scroll past)
 *   • Hero canvas — intelligence network graph (nodes + gold data connections)
 *   • Hero headline — lines clip-slide in after loader clears
 *   • Process connector — gold line draws across 4 steps on scroll entry
 *   • Fade-in for cards/sections on IntersectionObserver
 */
(function () {
  'use strict';

  /* ══════════════════════════════════════════════════════════════════════════
     1. PAGE LOADER
     Black overlay covers the page on load, then fades out — exactly like Opal.
  ══════════════════════════════════════════════════════════════════════════ */
  var loader = document.getElementById('ae-loader');

  function dismissLoader() {
    if (!loader) return;
    loader.classList.add('ae-loader--out');
    loader.addEventListener('transitionend', function () {
      if (loader.parentNode) loader.parentNode.removeChild(loader);
    }, { once: true });
  }

  if (document.readyState === 'complete') {
    setTimeout(dismissLoader, 250);
  } else {
    window.addEventListener('load', function () {
      setTimeout(dismissLoader, 250);
    });
  }

  /* ══════════════════════════════════════════════════════════════════════════
     2. SCROLL TEXT REVEAL
     Elements with [data-reveal-text] get their words wrapped in .rw spans.
     On each scroll tick, each word's opacity is driven by its position
     relative to the viewport — words above the trigger line are fully lit,
     words below are ghosted at 0.12 opacity. Matches the Opal effect exactly.
  ══════════════════════════════════════════════════════════════════════════ */

  function wrapWords(el) {
    var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
    var textNodes = [];
    var node;
    while ((node = walker.nextNode())) {
      if (node.textContent.trim()) textNodes.push(node);
    }

    textNodes.forEach(function (tn) {
      var frag = document.createDocumentFragment();
      tn.textContent.split(/(\s+)/).forEach(function (chunk) {
        if (!chunk) return;
        if (/^\s+$/.test(chunk)) {
          frag.appendChild(document.createTextNode(chunk));
        } else {
          var span = document.createElement('span');
          span.className = 'rw';
          span.textContent = chunk;
          frag.appendChild(span);
        }
      });
      tn.parentNode.replaceChild(frag, tn);
    });
  }

  function updateReveal() {
    var vh = window.innerHeight;
    var trigger = vh * 0.82; // words below this Y are still ghosted

    var words = document.querySelectorAll('.rw');
    for (var i = 0; i < words.length; i++) {
      var top = words[i].getBoundingClientRect().top;
      // progress 0→1 as word moves from trigger line up to 30% of viewport
      var progress = 1 - (top / trigger);
      var opacity = Math.min(1, Math.max(0.12, progress * 1.6));
      words[i].style.opacity = opacity.toFixed(3);
    }
  }

  function initRevealText() {
    var blocks = document.querySelectorAll('[data-reveal-text]');
    if (!blocks.length) return;
    blocks.forEach(wrapWords);

    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () { updateReveal(); ticking = false; });
        ticking = true;
      }
    }, { passive: true });

    updateReveal(); // run once on load for above-fold words
  }

  /* ══════════════════════════════════════════════════════════════════════════
     3. FADE-IN ON SCROLL
     Elements with .js-fade start invisible and slide up as they enter view.
     Staggered via --delay CSS custom property on each element.
  ══════════════════════════════════════════════════════════════════════════ */

  function initFadeIn() {
    var els = document.querySelectorAll('.js-fade');
    if (!els.length || !('IntersectionObserver' in window)) {
      // Fallback: show everything immediately
      els.forEach(function (el) { el.classList.add('js-fade--in'); });
      return;
    }

    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('js-fade--in');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.06, rootMargin: '0px 0px -48px 0px' });

    els.forEach(function (el) { obs.observe(el); });
  }

  /* ══════════════════════════════════════════════════════════════════════════
     4. NAV SCROLL STATE
     Adds .scrolled to .site-header after 80px scroll.
     Matches existing CSS which already handles the visual change.
  ══════════════════════════════════════════════════════════════════════════ */

  function initNav() {
    var header = document.querySelector('.site-header');
    if (!header) return;
    function check() {
      header.classList.toggle('scrolled', window.scrollY > 80);
    }
    window.addEventListener('scroll', check, { passive: true });
    check();
  }

  /* ══════════════════════════════════════════════════════════════════════════
     5. STAT COUNTER
     Animates numbers from 0 to data-target on scroll into view.
  ══════════════════════════════════════════════════════════════════════════ */

  function initCounters() {
    var counters = document.querySelectorAll('.stat-number[data-target]');
    if (!counters.length || !('IntersectionObserver' in window)) return;

    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = parseInt(el.dataset.target, 10);
        var suffix = el.dataset.suffix || '';
        var start = null;
        var duration = 1200;

        function step(ts) {
          if (!start) start = ts;
          var progress = Math.min((ts - start) / duration, 1);
          // Ease out cubic
          var ease = 1 - Math.pow(1 - progress, 3);
          el.textContent = Math.round(ease * target) + suffix;
          if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
        obs.unobserve(el);
      });
    }, { threshold: 0.5 });

    counters.forEach(function (c) { obs.observe(c); });
  }

  /* ══════════════════════════════════════════════════════════════════════════
     6. MOBILE NAV OVERLAY
     Full-screen dark overlay with large-type links — matches Opal's mobile
     menu aesthetic. Closes on link click or Escape key.
  ══════════════════════════════════════════════════════════════════════════ */

  function initMobileNav() {
    var toggle  = document.getElementById('nav-toggle');
    var overlay = document.getElementById('mobile-nav');
    if (!toggle || !overlay) return;

    function openNav() {
      overlay.classList.add('is-open');
      toggle.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
      toggle.setAttribute('aria-label', 'Close menu');
      document.body.classList.add('nav-open');
    }

    function closeNav() {
      overlay.classList.remove('is-open');
      toggle.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open menu');
      document.body.classList.remove('nav-open');
    }

    toggle.addEventListener('click', function () {
      overlay.classList.contains('is-open') ? closeNav() : openNav();
    });

    // Close on any overlay link click
    overlay.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closeNav);
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeNav();
    });
  }

  /* ══════════════════════════════════════════════════════════════════════════
     7. HERO CANVAS — Intelligence network graph
     Floating nodes connected by data paths. Gold nodes = compliance
     checkpoints. Connections glow gold when touching a gold node.
     Canvas sits behind hero text (z-index 1). Pauses when off-screen.
  ══════════════════════════════════════════════════════════════════════════ */

  function initHeroCanvas() {
    var hero = document.querySelector('.hero-section');
    if (!hero) return;

    var canvas = document.createElement('canvas');
    canvas.id = 'hero-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    hero.insertBefore(canvas, hero.firstChild);

    var ctx = canvas.getContext('2d');
    var W, H, nodes;
    var NODE_COUNT = 38;
    var MAX_DIST   = 170;
    var time       = 0;
    var animId     = null;

    function mkNode() {
      return {
        x:     Math.random() * W,
        y:     Math.random() * H,
        vx:    (Math.random() - 0.5) * 0.3,
        vy:    (Math.random() - 0.5) * 0.3,
        r:     Math.random() * 1.8 + 0.6,
        gold:  Math.random() > 0.78,
        phase: Math.random() * Math.PI * 2
      };
    }

    function resize() {
      W = canvas.width  = hero.offsetWidth;
      H = canvas.height = hero.offsetHeight;
    }

    function buildNodes() {
      nodes = [];
      for (var i = 0; i < NODE_COUNT; i++) nodes.push(mkNode());
    }

    function tick() {
      time += 0.01;
      ctx.clearRect(0, 0, W, H);

      /* connections */
      for (var i = 0; i < nodes.length; i++) {
        for (var j = i + 1; j < nodes.length; j++) {
          var dx = nodes[i].x - nodes[j].x;
          var dy = nodes[i].y - nodes[j].y;
          var d  = Math.sqrt(dx * dx + dy * dy);
          if (d >= MAX_DIST) continue;

          var t = 1 - d / MAX_DIST;
          var isGold = nodes[i].gold || nodes[j].gold;

          ctx.beginPath();
          ctx.moveTo(nodes[i].x, nodes[i].y);
          ctx.lineTo(nodes[j].x, nodes[j].y);

          if (isGold) {
            ctx.strokeStyle = 'rgba(201,169,110,' + (t * 0.28) + ')';
            ctx.lineWidth   = t * 1.3;
          } else {
            ctx.strokeStyle = 'rgba(255,255,255,' + (t * 0.07) + ')';
            ctx.lineWidth   = t * 0.7;
          }
          ctx.stroke();
        }
      }

      /* nodes */
      for (var k = 0; k < nodes.length; k++) {
        var n = nodes[k];
        var pulse = Math.sin(time + n.phase);

        if (n.gold) {
          /* outer glow */
          var grd = ctx.createRadialGradient(n.x, n.y, 0, n.x, n.y, 18 + pulse * 4);
          grd.addColorStop(0, 'rgba(201,169,110,0.18)');
          grd.addColorStop(1, 'rgba(201,169,110,0)');
          ctx.beginPath();
          ctx.arc(n.x, n.y, 18 + pulse * 4, 0, Math.PI * 2);
          ctx.fillStyle = grd;
          ctx.fill();

          /* core dot */
          ctx.beginPath();
          ctx.arc(n.x, n.y, n.r + pulse * 0.4, 0, Math.PI * 2);
          ctx.fillStyle = 'rgba(201,169,110,0.9)';
          ctx.fill();
        } else {
          ctx.beginPath();
          ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
          ctx.fillStyle = 'rgba(255,255,255,0.22)';
          ctx.fill();
        }

        /* drift */
        n.x += n.vx;
        n.y += n.vy;
        if (n.x < -24) n.x = W + 24;
        if (n.x > W + 24) n.x = -24;
        if (n.y < -24) n.y = H + 24;
        if (n.y > H + 24) n.y = -24;
      }

      animId = requestAnimationFrame(tick);
    }

    function startCanvas() { if (!animId) tick(); }
    function stopCanvas()  { cancelAnimationFrame(animId); animId = null; }

    resize();
    buildNodes();
    startCanvas();

    /* pause/resume on visibility */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function(entries) {
        entries[0].isIntersecting ? startCanvas() : stopCanvas();
      }, { threshold: 0 }).observe(hero);
    }

    window.addEventListener('resize', function() {
      resize(); buildNodes();
    }, { passive: true });
  }


  /* ══════════════════════════════════════════════════════════════════════════
     8. HERO HEADLINE ENTRANCE
     Front page: each .hero-headline > span line clips and slides up
     sequentially (Opal-style stagger).
     Inner pages: .page-hero__headline and .assessment-hero__headline slide
     up as a unit; eyebrow/sub text fades in slightly earlier.
  ══════════════════════════════════════════════════════════════════════════ */

  function initHeroHeadline() {

    /* ── Front page: line-by-line clip slide-up ─────────────────────────── */
    var lines = document.querySelectorAll('.hero-headline > span');
    if (lines.length) {
      lines.forEach(function(line) {
        line.style.cssText += 'display:block;overflow:hidden;';
        var inner = document.createElement('span');
        inner.className = 'hline-inner';
        inner.style.cssText = 'display:block;transform:translateY(108%);' +
          'transition:transform 0.9s cubic-bezier(0.16,1,0.3,1);';
        while (line.firstChild) inner.appendChild(line.firstChild);
        line.appendChild(inner);
      });
      lines.forEach(function(line, i) {
        setTimeout(function() {
          var inner = line.querySelector('.hline-inner');
          if (inner) inner.style.transform = 'translateY(0)';
        }, 550 + i * 140);
      });
    }

    /* ── Inner pages: full headline + eyebrow fade-up as a unit ─────────── */
    var easing = 'cubic-bezier(0.16,1,0.3,1)';
    var fadeUp = 'opacity:0;transform:translateY(28px);will-change:transform,opacity;';

    /* Targets: page-hero__headline, assessment-hero__headline */
    ['.page-hero__headline', '.assessment-hero__headline'].forEach(function(sel) {
      var el = document.querySelector(sel);
      if (!el) return;
      el.style.cssText += fadeUp + 'transition:opacity 0.85s ' + easing + ',transform 0.85s ' + easing + ';';
      setTimeout(function() { el.style.opacity='1'; el.style.transform='translateY(0)'; }, 420);
    });

    /* Eyebrow labels slide in first */
    ['.page-hero__eyebrow', '.assessment-hero__eyebrow'].forEach(function(sel) {
      var el = document.querySelector(sel);
      if (!el) return;
      el.style.cssText += 'opacity:0;transform:translateY(14px);' +
        'transition:opacity 0.55s ease,transform 0.55s ease;';
      setTimeout(function() { el.style.opacity='1'; el.style.transform='translateY(0)'; }, 230);
    });

    /* Sub-text fades in last */
    ['.page-hero__subtitle', '.assessment-hero__sub'].forEach(function(sel) {
      var el = document.querySelector(sel);
      if (!el) return;
      el.style.cssText += 'opacity:0;transform:translateY(14px);' +
        'transition:opacity 0.7s ease,transform 0.7s ease;';
      setTimeout(function() { el.style.opacity='1'; el.style.transform='translateY(0)'; }, 620);
    });
  }


  /* ══════════════════════════════════════════════════════════════════════════
     9. PROCESS CONNECTOR
     A gold line draws itself left→right across the process track when it
     enters the viewport — visually links the 4 steps together.
  ══════════════════════════════════════════════════════════════════════════ */

  function initProcessConnector() {
    var track = document.querySelector('.process-track');
    if (!track || !('IntersectionObserver' in window)) return;

    new IntersectionObserver(function(entries) {
      if (entries[0].isIntersecting) {
        track.classList.add('connector-drawn');
      }
    }, { threshold: 0.25 }).observe(track);
  }


  /* ══════════════════════════════════════════════════════════════════════════
     10. FLOATING COMPLIANCE BADGES (assessment page)
     Subtle animated badge icons that drift into view on the assessment hero.
  ══════════════════════════════════════════════════════════════════════════ */

  function initAssessmentBadges() {
    var hero = document.querySelector('.assessment-hero');
    if (!hero) return;
    hero.classList.add('badges-ready');
  }


  /* ══════════════════════════════════════════════════════════════════════════
     11. PAGE HERO CANVAS — same particle network on all inner pages
     Targets every .page-hero section. Injects dot-grid, orbs, and canvas.
     Uses 22 nodes (lighter than front-page 38) to match smaller hero height.
  ══════════════════════════════════════════════════════════════════════════ */

  function initPageHeroCanvas() {
    var heroes = document.querySelectorAll('.page-hero');
    if (!heroes.length) return;

    /* Inject shared CSS once */
    var styleTag = document.createElement('style');
    styleTag.textContent =
      '.page-hero{position:relative;overflow:hidden;}' +
      '.page-hero-canvas{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;}' +
      '.page-hero>*:not(.page-hero-canvas):not(.page-hero-bg-dots):not(.page-hero-orb--1):not(.page-hero-orb--2){position:relative;z-index:1;}' +
      '.page-hero-bg-dots{position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:28px 28px;pointer-events:none;z-index:0;}' +
      '.page-hero-orb--1{position:absolute;width:480px;height:480px;top:-160px;right:-100px;border-radius:50%;background:radial-gradient(circle,rgba(201,169,110,0.065) 0%,transparent 70%);pointer-events:none;z-index:0;}' +
      '.page-hero-orb--2{position:absolute;width:320px;height:320px;bottom:-110px;left:-70px;border-radius:50%;background:radial-gradient(circle,rgba(120,100,220,0.04) 0%,transparent 70%);pointer-events:none;z-index:0;}';
    document.head.appendChild(styleTag);

    heroes.forEach(function(hero) {
      /* dot grid */
      var dots = document.createElement('div');
      dots.className = 'page-hero-bg-dots';
      dots.setAttribute('aria-hidden', 'true');
      hero.insertBefore(dots, hero.firstChild);

      /* orbs */
      var orb2 = document.createElement('div');
      orb2.className = 'page-hero-orb--2';
      orb2.setAttribute('aria-hidden', 'true');
      hero.insertBefore(orb2, hero.firstChild);

      var orb1 = document.createElement('div');
      orb1.className = 'page-hero-orb--1';
      orb1.setAttribute('aria-hidden', 'true');
      hero.insertBefore(orb1, hero.firstChild);

      /* canvas */
      var canvas = document.createElement('canvas');
      canvas.className = 'page-hero-canvas';
      canvas.setAttribute('aria-hidden', 'true');
      hero.insertBefore(canvas, hero.firstChild);

      var ctx = canvas.getContext('2d');
      var W, H, nodes;
      var NODE_COUNT = 22;
      var MAX_DIST   = 140;
      var time       = 0;
      var animId     = null;

      function mkNode() {
        return {
          x:     Math.random() * W,
          y:     Math.random() * H,
          vx:    (Math.random() - 0.5) * 0.25,
          vy:    (Math.random() - 0.5) * 0.25,
          r:     Math.random() * 1.6 + 0.5,
          gold:  Math.random() > 0.8,
          phase: Math.random() * Math.PI * 2
        };
      }

      function resize() {
        W = canvas.width  = hero.offsetWidth;
        H = canvas.height = hero.offsetHeight;
      }

      function buildNodes() {
        nodes = [];
        for (var i = 0; i < NODE_COUNT; i++) nodes.push(mkNode());
      }

      function tick() {
        time += 0.008;
        ctx.clearRect(0, 0, W, H);

        for (var i = 0; i < nodes.length; i++) {
          for (var j = i + 1; j < nodes.length; j++) {
            var dx = nodes[i].x - nodes[j].x;
            var dy = nodes[i].y - nodes[j].y;
            var d  = Math.sqrt(dx * dx + dy * dy);
            if (d >= MAX_DIST) continue;
            var t = 1 - d / MAX_DIST;
            var isGold = nodes[i].gold || nodes[j].gold;
            ctx.beginPath();
            ctx.moveTo(nodes[i].x, nodes[i].y);
            ctx.lineTo(nodes[j].x, nodes[j].y);
            if (isGold) {
              ctx.strokeStyle = 'rgba(201,169,110,' + (t * 0.22) + ')';
              ctx.lineWidth   = t * 1.1;
            } else {
              ctx.strokeStyle = 'rgba(255,255,255,' + (t * 0.06) + ')';
              ctx.lineWidth   = t * 0.6;
            }
            ctx.stroke();
          }
        }

        for (var k = 0; k < nodes.length; k++) {
          var n = nodes[k];
          var pulse = Math.sin(time + n.phase);
          if (n.gold) {
            var grd = ctx.createRadialGradient(n.x, n.y, 0, n.x, n.y, 14 + pulse * 3);
            grd.addColorStop(0, 'rgba(201,169,110,0.15)');
            grd.addColorStop(1, 'rgba(201,169,110,0)');
            ctx.beginPath();
            ctx.arc(n.x, n.y, 14 + pulse * 3, 0, Math.PI * 2);
            ctx.fillStyle = grd;
            ctx.fill();
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r + pulse * 0.3, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(201,169,110,0.85)';
            ctx.fill();
          } else {
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255,255,255,0.18)';
            ctx.fill();
          }
          n.x += n.vx; n.y += n.vy;
          if (n.x < -20) n.x = W + 20;
          if (n.x > W + 20) n.x = -20;
          if (n.y < -20) n.y = H + 20;
          if (n.y > H + 20) n.y = -20;
        }

        animId = requestAnimationFrame(tick);
      }

      function startCanvas() { if (!animId) tick(); }
      function stopCanvas()  { cancelAnimationFrame(animId); animId = null; }

      resize();
      buildNodes();
      startCanvas();

      if ('IntersectionObserver' in window) {
        new IntersectionObserver(function(entries) {
          entries[0].isIntersecting ? startCanvas() : stopCanvas();
        }, { threshold: 0 }).observe(hero);
      }

      window.addEventListener('resize', function() { resize(); buildNodes(); }, { passive: true });
    });
  }


  /* ══════════════════════════════════════════════════════════════════════════
     12. AUTO FADE-STAGGER
     Walks card grids + standalone section elements, assigns .js-fade + a
     CSS --delay custom property so the IntersectionObserver in initFadeIn()
     picks them up with a natural stagger. Must run BEFORE initFadeIn().
  ══════════════════════════════════════════════════════════════════════════ */

  function initAutoFade() {
    /* Single block elements that should slide up on entry */
    [
      '.stats-row',
      '.process-header',
      '.section-header',
      '.subscription-section .subscription-lede',
      '.subscription-section .subscription-features'
    ].forEach(function(sel) {
      document.querySelectorAll(sel).forEach(function(el) {
        if (!el.classList.contains('js-fade')) el.classList.add('js-fade');
      });
    });

    /* Card grids — stagger children */
    [
      '.tiers-grid',
      '.tier-cols',
      '.bs-cards',
      '.service-cards-grid',
      '.values-grid',
      '.bs-features-grid'
    ].forEach(function(parentSel) {
      document.querySelectorAll(parentSel).forEach(function(parent) {
        Array.prototype.slice.call(parent.children).forEach(function(child, i) {
          if (!child.classList.contains('js-fade')) {
            child.classList.add('js-fade');
            child.style.setProperty('--delay', (i * 0.09) + 's');
          }
        });
      });
    });

    /* Service card-fulls — stagger individually */
    document.querySelectorAll('.service-card-full').forEach(function(el, i) {
      if (!el.classList.contains('js-fade')) {
        el.classList.add('js-fade');
        el.style.setProperty('--delay', (i * 0.08) + 's');
      }
    });
  }


  /* ══════════════════════════════════════════════════════════════════════════
     13. EYEBROW LINE DRAW
     Adds .ae-eyebrow-in to eyebrow elements when they scroll into view,
     triggering the CSS ::after gold underline animation.
  ══════════════════════════════════════════════════════════════════════════ */

  function initEyebrowLines() {
    var eyebrows = document.querySelectorAll(
      '.eyebrow, .section-eyebrow, .hero-eyebrow, ' +
      '.page-hero__eyebrow, .reveal-label, .process-eyebrow'
    );
    if (!eyebrows.length) return;

    if (!('IntersectionObserver' in window)) {
      eyebrows.forEach(function(el) { el.classList.add('ae-eyebrow-in'); });
      return;
    }

    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('ae-eyebrow-in');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    eyebrows.forEach(function(el) { obs.observe(el); });
  }


  /* ══════════════════════════════════════════════════════════════════════════
     14. GOLD RULE INJECTOR
     Inserts a thin .ae-gold-rule div before each major page section
     (between consecutive <section> siblings). The rule scales from 0→1
     when it enters the viewport — a tactile visual beat between sections.
  ══════════════════════════════════════════════════════════════════════════ */

  function initGoldRules() {
    if (!('IntersectionObserver' in window)) return;

    /* Only inject on inner pages (not the front page with its own elaborate design) */
    if (document.querySelector('.hero-section')) return;

    var sections = document.querySelectorAll('.site-main > section');
    sections.forEach(function(section, i) {
      if (i === 0) return; /* skip the first (page hero) */

      var rule = document.createElement('span');
      rule.className = 'ae-gold-rule';
      rule.setAttribute('aria-hidden', 'true');
      section.parentNode.insertBefore(rule, section);

      var obs = new IntersectionObserver(function(entries) {
        if (entries[0].isIntersecting) {
          rule.classList.add('ae-drawn');
          obs.disconnect();
        }
      }, { threshold: 0.4 });
      obs.observe(rule);
    });
  }


  /* ── Init ──────────────────────────────────────────────────────────────── */
  function init() {
    initAutoFade();    /* must be before initFadeIn so grids get .js-fade first */
    initRevealText();
    initFadeIn();
    initEyebrowLines();
    initGoldRules();
    initNav();
    initCounters();
    initMobileNav();
    initHeroCanvas();
    initPageHeroCanvas();
    initHeroHeadline();
    initProcessConnector();
    initAssessmentBadges();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();