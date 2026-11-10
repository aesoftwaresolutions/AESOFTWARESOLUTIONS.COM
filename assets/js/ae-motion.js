/**
 * AE Software Solutions: motion and behavior (v6)
 *
 * Small on purpose. Nothing here blocks reading, and every effect is optional.
 *
 *  1. Reveal        [data-reveal] and .js-fade fade up 12px once, 400ms
 *  2. Header        a hairline fades in after 8px of scroll
 *  3. Mobile menu   open and close, Escape, focus return
 *  4. Home topics   native details rows: open one at a time, open from a #hash
 *  5. Sequence      the 6:42 pm timeline lights up as it scrolls past
 *  6. Jump bar      the active pricing step is marked while you scroll
 *  7. Legacy hooks  process connector and assessment badges (older pages)
 *
 * Reduced motion: content is shown at once (html.ae-calm).
 * html.ae-js is set in <head>; if this file never runs, html.ae-motion-failed
 * shows everything after 4 seconds, so text is never stuck hidden.
 * The page loader, page transition panel, custom cursor, magnetic buttons,
 * cursor glow, split text headlines and footer wordmark slide are gone.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var calm = html.classList.contains('ae-calm') ||
    (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

  function onScroll(fn) {
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(function () { ticking = false; fn(); });
    }, { passive: true });
  }

  /* 1. REVEAL */
  function initAutoFade() {
    // Older inner pages: card grids and headers get the same gentle fade up.
    ['.stats-row', '.process-header', '.section-header', '.inner-section-header'].forEach(function (sel) {
      doc.querySelectorAll(sel).forEach(function (el) { el.classList.add('js-fade'); });
    });
    ['.tiers-grid', '.tier-cols', '.bs-cards', '.service-cards-grid', '.bs-features-grid',
     '.web-tier-grid', '.industries-grid-small'].forEach(function (sel) {
      doc.querySelectorAll(sel).forEach(function (parent) {
        Array.prototype.forEach.call(parent.children, function (child, i) {
          child.style.setProperty('--delay', (i % 4) * 0.06 + 's');
          if (!child.hasAttribute('data-reveal')) child.classList.add('js-fade');
        });
      });
    });
  }

  function reveal(el) {
    el.classList.add('is-revealed');
    if (el.classList.contains('js-fade')) el.classList.add('js-fade--in');
  }

  function initReveals() {
    var els = doc.querySelectorAll('[data-reveal], .js-fade');
    if (calm || !('IntersectionObserver' in window)) {
      els.forEach(reveal);
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        io.unobserve(entry.target);
        reveal(entry.target);
      });
    }, { rootMargin: '0px 0px -6% 0px', threshold: 0.01 });
    els.forEach(function (el) { io.observe(el); });
  }

  /* 2. HEADER */
  function initHeader() {
    var header = doc.querySelector('.site-header');
    if (!header) return;
    function update() { header.classList.toggle('aec--scrolled', window.scrollY > 8); }
    onScroll(update);
    update();
  }

  /* 3. MOBILE MENU */
  var toggle, overlay;

  function openMenu() {
    overlay.classList.add('is-open');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Close menu');
    doc.body.classList.add('nav-open');
    var first = overlay.querySelector('a');
    if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 50);
  }

  function closeMenu(returnFocus) {
    if (!overlay || !overlay.classList.contains('is-open')) return;
    overlay.classList.remove('is-open');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open menu');
    doc.body.classList.remove('nav-open');
    if (returnFocus !== false) toggle.focus({ preventScroll: true });
  }

  function initMenu() {
    toggle = doc.getElementById('nav-toggle');
    overlay = doc.getElementById('mobile-nav');
    if (!toggle || !overlay) return;
    toggle.addEventListener('click', function () {
      if (overlay.classList.contains('is-open')) closeMenu(); else openMenu();
    });
    overlay.addEventListener('click', function (e) {
      if (e.target.closest && e.target.closest('a')) closeMenu(false);
    });
    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMenu();
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth > 960) closeMenu(false);
    });
  }

  /* 4. HOME TOPICS (native details) */
  function initTopics() {
    var topics = Array.prototype.slice.call(doc.querySelectorAll('details[data-topic]'));
    if (!topics.length) return;

    // One open at a time. Browsers that know details[name] do this themselves;
    // this covers the rest.
    topics.forEach(function (t) {
      t.addEventListener('toggle', function () {
        if (!t.open) return;
        topics.forEach(function (o) { if (o !== t && o.open) o.open = false; });
      });
    });

    // /#work, /#pricing and so on open that topic and scroll to it.
    function fromHash() {
      var id = (location.hash || '').slice(1);
      if (!id) return;
      var target = doc.getElementById(id);
      var t = target && (target.matches('details[data-topic]') ? target : target.closest('details[data-topic]'));
      if (!t) return;
      t.open = true;
      setTimeout(function () {
        var y = t.getBoundingClientRect().top + window.scrollY - 96;
        window.scrollTo({ top: y, behavior: calm ? 'auto' : 'smooth' });
      }, 60);
    }
    window.addEventListener('hashchange', fromHash);
    fromHash();
  }

  /* 5. SEQUENCE (home): each step lights up as it crosses the middle of the screen */
  function initSequence() {
    var log = doc.querySelector('.v5-seq__log');
    if (!log) return;
    var steps = Array.prototype.slice.call(log.querySelectorAll('[data-step]'));
    if (calm) {
      steps.forEach(function (s) { s.classList.add('is-on'); });
      log.style.setProperty('--seq', 1);
      return;
    }
    function update() {
      var mid = window.innerHeight * 0.62;
      var r = log.getBoundingClientRect();
      var p = Math.min(1, Math.max(0, (mid - r.top) / r.height));
      log.style.setProperty('--seq', p.toFixed(3));
      steps.forEach(function (s) {
        var sr = s.getBoundingClientRect();
        s.classList.toggle('is-on', sr.top + sr.height * 0.3 < mid);
      });
    }
    onScroll(update);
    window.addEventListener('resize', update);
    // The panel opens after load, so re-measure when a topic toggles.
    doc.addEventListener('toggle', update, true);
    update();
  }

  /* 6. PRICING JUMP BAR: mark the step whose section is in view */
  function initJumpBar() {
    var links = Array.prototype.slice.call(doc.querySelectorAll('.ae-jump__step'));
    if (!links.length) return;
    var pairs = links.map(function (a) {
      return { a: a, sec: doc.getElementById((a.getAttribute('href') || '').slice(1)) };
    }).filter(function (p) { return p.sec; });
    if (!pairs.length) return;
    function update() {
      var line = 140; // just under the header and the bar
      var current = null;
      pairs.forEach(function (p) {
        if (p.sec.getBoundingClientRect().top <= line) current = p;
      });
      pairs.forEach(function (p) { p.a.classList.toggle('is-active', p === current); });
    }
    onScroll(update);
    window.addEventListener('resize', update);
    update();
  }

  /* 7. LEGACY HOOKS used by older pages */
  function initLegacy() {
    var track = doc.querySelector('.process-track');
    if (track) {
      if (calm || !('IntersectionObserver' in window)) {
        track.classList.add('connector-drawn');
      } else {
        var io = new IntersectionObserver(function (entries) {
          if (entries[0].isIntersecting) { track.classList.add('connector-drawn'); io.disconnect(); }
        }, { threshold: 0.25 });
        io.observe(track);
      }
    }
    var hero = doc.querySelector('.assessment-hero');
    if (hero) hero.classList.add('badges-ready');
  }

  function init() {
    initAutoFade();
    initReveals();
    initHeader();
    initMenu();
    initTopics();
    initSequence();
    initJumpBar();
    initLegacy();
    html.classList.add('ae-motion-ready');
    doc.body.classList.add('loaded'); // hook some older rules still read
  }

  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init);
  else init();
})();
