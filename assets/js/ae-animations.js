/**
 * AE Software Solutions — Scroll & Load Animations
 * Inspired by Opal Camera's design language:
 *   • Black page loader that fades out after load
 *   • Scroll-driven text reveal (words illuminate as you scroll past)
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

  /* ── Init ──────────────────────────────────────────────────────────────── */
  function init() {
    initRevealText();
    initFadeIn();
    initNav();
    initCounters();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
