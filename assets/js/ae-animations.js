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
     Each line slides up from beneath an overflow:hidden clip.
     Triggered after the page loader dismisses (~650ms after load).
  ══════════════════════════════════════════════════════════════════════════ */

  function initHeroHeadline() {
    var lines = document.querySelectorAll('.hero-headline > span');
    if (!lines.length) return;

    lines.forEach(function(line) {
      line.style.cssText += 'display:block;overflow:hidden;';

      var inner = document.createElement('span');
      inner.className = 'hline-inner';
      inner.style.cssText = 'display:block;transform:translateY(108%);' +
        'transition:transform 0.9s cubic-bezier(0.16,1,0.3,1);';
      while (line.firstChild) inner.appendChild(line.firstChild);
      line.appendChild(inner);
    });

    /* trigger after loader + small offset per line */
    lines.forEach(function(line, i) {
      setTimeout(function() {
        var inner = line.querySelector('.hline-inner');
        if (inner) inner.style.transform = 'translateY(0)';
      }, 550 + i * 140);
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


  /* ── Init ──────────────────────────────────────────────────────────────── */
  function init() {
    initRevealText();
    initFadeIn();
    initNav();
    initCounters();
    initMobileNav();
    initHeroCanvas();
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
