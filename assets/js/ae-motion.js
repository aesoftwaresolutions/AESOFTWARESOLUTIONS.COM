/**
 * AE Software Solutions: motion and behavior (v8)
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
 *  8. Nav pill      black pill glides between the nav links, rests on the current section
 *
 *  9. Page wipe     a dark panel slides over the page on internal links, then away on arrival
 * 10. Intro         first page of a visit only: a 1.2s curtain with a thin progress line (CSS driven)
 * 11. Split heads   h1, section titles and [data-split] rise word by word from a mask
 * 12. Magnetic      buttons lean toward a fine pointer (up to 6px)
 * 13. Cursor glow   a faint white light trails a fine pointer
 * 14. Stagger       sibling cards, rows and steps reveal a few ms apart as they scroll in
 * 15. Card light    a faint spotlight follows the pointer across glass cards (CSS reads --sx, --sy)
 * (The contact popup lives in ae-popup.js.)
 *
 * Reduced motion: content is shown at once (html.ae-calm).
 * html.ae-js is set in <head>; if this file never runs, html.ae-motion-failed
 * shows everything after 4 seconds, so text is never stuck hidden.
 * Sections 9 to 13 are the preview motion layer, restored in v7 as decoration only:
 * each one is skipped under reduced motion, a hard timer in header.php clears the
 * intro and wipe even if this file never runs, and split words show at once on failure.
 * The footer wordmark slide is NOT restored: the v6 footer has no wordmark element.
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
  function initStagger() {
    // Siblings that appear together arrive one after another, 60ms apart (max 4 steps).
    ['.ae-offers', '.v5-cards', '.v5-proof', '.v5-prices', '.op-steps__list', '.ae-promises__grid', '.ae-values'].forEach(function (sel) {
      doc.querySelectorAll(sel).forEach(function (parent) {
        Array.prototype.forEach.call(parent.children, function (child, i) {
          if (!child.style.getPropertyValue('--delay')) child.style.setProperty('--delay', (i % 4) * 0.06 + 's');
        });
      });
    });
  }

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
    // The stagger delay is only for the entrance: drop it so hover lifts respond at once.
    if (el.style.getPropertyValue('--delay')) setTimeout(function () { el.style.removeProperty('--delay'); }, 1200);
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

  /* 8. NAV PILL: a black pill glides to the hovered or focused link, follows
        clicks, and rests on the current section (or the current page).
        Works on the desktop nav and on the mobile menu list. */
  function initNavPill() {
    var groups = [
      { box: doc.querySelector('.site-header__nav'), sel: '.site-header__nav-link', px: 14, py: -5 },
      { box: doc.querySelector('.mobile-nav-overlay__links'), sel: '.mobile-nav-overlay__link', px: 14, py: -6 }
    ].filter(function (g) { return g.box; });
    if (!groups.length) return;

    function slugOf(a) {
      var parts = (a.pathname || '').split('/').filter(Boolean);
      return parts.length ? parts[parts.length - 1] : '';
    }

    var sectionSlug = null; // set by scroll, null means "use the page"

    groups.forEach(function (g) {
      g.links = Array.prototype.slice.call(g.box.querySelectorAll(g.sel));
      if (!g.links.length) return;
      g.pill = doc.createElement('span');
      g.pill.className = 'ae-navpill is-still';
      g.pill.setAttribute('aria-hidden', 'true');
      g.box.insertBefore(g.pill, g.box.firstChild);
      g.page = g.links.filter(function (a) { return a.hasAttribute('aria-current'); })[0] || null;
      g.hover = null;
      g.rest = g.page;
      g.shown = null;
    });
    groups = groups.filter(function (g) { return g.pill; });
    if (!groups.length) return;
    html.classList.add('ae-navpill-on');

    function place(g, still) {
      var target = g.hover || g.rest;
      var box = g.box;
      // Hidden (mobile menu closed, or nav hidden under 960px): nothing to measure.
      if (!target || !box.offsetWidth || !target.offsetWidth) {
        g.pill.classList.remove('is-on');
        g.links.forEach(function (a) { a.classList.remove('is-lit'); });
        g.shown = null;
        return;
      }
      // First show: jump to place, then glide from there on.
      var jump = still || !g.shown;
      if (jump) g.pill.classList.add('is-still');
      g.pill.style.setProperty('--px', (target.offsetLeft - g.px) + 'px');
      g.pill.style.setProperty('--py', (target.offsetTop - g.py) + 'px');
      g.pill.style.setProperty('--pw', (target.offsetWidth + g.px * 2) + 'px');
      g.pill.style.setProperty('--ph', (target.offsetHeight + g.py * 2) + 'px');
      g.pill.classList.add('is-on');
      g.links.forEach(function (a) { a.classList.toggle('is-lit', a === target); });
      g.shown = target;
      if (jump) {
        void g.pill.offsetWidth; // commit the jump before transitions come back
        g.pill.classList.remove('is-still');
      }
    }
    function placeAll(still) { groups.forEach(function (g) { place(g, still); }); }

    function pointerKind(e) { return e.pointerType === 'mouse' || e.pointerType === 'pen' || !e.pointerType; }

    groups.forEach(function (g) {
      g.links.forEach(function (a) {
        a.addEventListener('pointerover', function (e) {
          if (!pointerKind(e)) return;
          g.hover = a; place(g);
        });
        a.addEventListener('focus', function () {
          var vis = true;
          try { vis = a.matches(':focus-visible'); } catch (err) {}
          if (vis) { g.hover = a; place(g); }
        });
        a.addEventListener('blur', function () {
          if (g.hover === a) { g.hover = null; place(g); }
        });
        a.addEventListener('click', function () {
          g.rest = a; g.hover = null; place(g);
        });
      });
      g.box.addEventListener('pointerleave', function () {
        if (g.hover && g.hover !== doc.activeElement) { g.hover = null; place(g); }
        else if (g.hover && !g.hover.matches(':focus-visible')) { g.hover = null; place(g); }
      });
    });

    // Rest on the section in view. Only the home topics (#services, #pricing...)
    // and anything marked data-nav-section count, so other pages keep their page link.
    var slugs = {};
    groups[0].links.forEach(function (a) { slugs[slugOf(a)] = true; });
    var sections = Array.prototype.slice.call(
      doc.querySelectorAll('[data-nav-section], details[data-topic]')
    ).map(function (el) {
      return { el: el, slug: el.getAttribute('data-nav-section') || el.id };
    }).filter(function (s) { return s.slug && slugs[s.slug]; });

    function updateSection() {
      if (!sections.length) return;
      var line = (parseFloat(getComputedStyle(html).getPropertyValue('--header-h')) || 72) + 24;
      var found = null;
      sections.forEach(function (s) {
        var r = s.el.getBoundingClientRect();
        if (r.top <= line && r.bottom > line) found = s.slug;
      });
      if (found === sectionSlug) return;
      sectionSlug = found;
      groups.forEach(function (g) {
        var match = null;
        if (found) g.links.forEach(function (a) { if (slugOf(a) === found) match = a; });
        g.rest = match || g.page;
        place(g);
      });
    }
    if (sections.length) {
      onScroll(updateSection);
      doc.addEventListener('toggle', updateSection, true);
    }

    // Reposition on resize, font load, wrap, and when the mobile menu opens.
    var queued = false;
    function relayout() {
      if (queued) return;
      queued = true;
      requestAnimationFrame(function () { queued = false; placeAll(true); updateSection(); });
    }
    window.addEventListener('resize', relayout);
    window.addEventListener('orientationchange', relayout);
    window.addEventListener('load', relayout);
    if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(relayout);
    if ('ResizeObserver' in window) {
      var ro = new ResizeObserver(relayout);
      groups.forEach(function (g) { ro.observe(g.box); });
    }
    var overlayEl = doc.getElementById('mobile-nav');
    if (overlayEl && 'MutationObserver' in window) {
      new MutationObserver(relayout).observe(overlayEl, { attributes: true, attributeFilter: ['class'] });
    }
    placeAll(true);
    updateSection();
  }

  var finePointer = !!(window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches);

  /* 9. PAGE WIPE */
  function initWipe() {
    if (calm) return;
    var arriving = html.classList.contains('ae-arriving');
    if (arriving) setTimeout(function () { html.classList.remove('ae-arriving'); }, 700);
    // Coming back through the back button must never show a stuck panel.
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) html.classList.remove('ae-wiping', 'ae-arriving', 'ae-intro-on');
    });
    doc.addEventListener('click', function (e) {
      if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      var a = e.target.closest && e.target.closest('a[href]');
      if (!a || (a.target && a.target !== '_self') || a.hasAttribute('download')) return;
      if (a.origin !== location.origin || /^(mailto|tel):/i.test(a.getAttribute('href'))) return;
      if (a.pathname === location.pathname && a.search === location.search) return; // same page or hash jump
      if (/\.(pdf|zip|png|jpe?g|gif|webp|svg|mp4)$/i.test(a.pathname)) return;
      e.preventDefault();
      try { sessionStorage.setItem('ae_wipe', '1'); } catch (err) {}
      html.classList.add('ae-wiping');
      var go = function () { location.href = a.href; };
      setTimeout(go, 340);
      // If the navigation is cancelled or slow, never leave the panel up.
      setTimeout(function () { html.classList.remove('ae-wiping'); }, 3000);
    });
  }

  /* 10. INTRO is pure CSS (html.ae-intro-on, set before paint in header.php). */

  /* 11. SPLIT HEADLINES */
  function splitNode(node, state) {
    Array.prototype.slice.call(node.childNodes).forEach(function (n) {
      if (n.nodeType === 3) {
        var parts = n.nodeValue.split(/(\s+)/);
        var frag = doc.createDocumentFragment();
        parts.forEach(function (part) {
          if (!part) return;
          if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(part)); return; }
          var w = doc.createElement('span'); w.className = 'ae-split__w';
          var i = doc.createElement('span'); i.className = 'ae-split__i';
          i.style.setProperty('--i', Math.min(state.n++, 14));
          i.textContent = part;
          w.appendChild(i); frag.appendChild(w);
        });
        node.replaceChild(frag, n);
      } else if (n.nodeType === 1 && !/^(BR|SCRIPT|STYLE|SVG|IMG)$/i.test(n.nodeName)) {
        splitNode(n, state);
      }
    });
  }

  function initSplit() {
    if (calm || !('IntersectionObserver' in window)) return;
    // The home hero headline is never split: it must be whole and readable on
    // first paint, not rise word by word from a mask.
    var heads = doc.querySelectorAll('h1:not(.v5-hero__title), .v5-section-title, [data-split]');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        io.unobserve(entry.target);
        var el = entry.target;
        requestAnimationFrame(function () { requestAnimationFrame(function () { el.classList.add('is-in'); }); });
      });
    }, { threshold: 0.2 });
    heads.forEach(function (el) {
      if (el.classList.contains('ae-split') || el.closest('.ae-split')) return;
      if (!el.textContent.trim()) return;
      var label = el.textContent.replace(/\s+/g, ' ').trim();
      splitNode(el, { n: 0 });
      el.setAttribute('aria-label', label);
      el.classList.add('ae-split');
      io.observe(el);
    });
    // Backstop: whatever has not revealed after 3s is shown.
    setTimeout(function () {
      doc.querySelectorAll('.ae-split:not(.is-in)').forEach(function (el) { el.classList.add('is-in'); });
    }, 3000);
  }

  /* 12. MAGNETIC BUTTONS */
  function initMagnetic() {
    if (calm || !finePointer) return;
    var RANGE = 6;
    doc.addEventListener('pointermove', function (e) {
      if (e.pointerType !== 'mouse') return;
      var b = e.target.closest && e.target.closest('.btn, .v5-button');
      if (!b || b.disabled) return;
      var r = b.getBoundingClientRect();
      var dx = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
      var dy = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
      b.classList.remove('is-magnet-out');
      b.style.setProperty('--mx', (dx * RANGE).toFixed(1) + 'px');
      b.style.setProperty('--my', (dy * RANGE).toFixed(1) + 'px');
    }, { passive: true });
    doc.addEventListener('pointerout', function (e) {
      var b = e.target.closest && e.target.closest('.btn, .v5-button');
      if (!b || (e.relatedTarget && b.contains(e.relatedTarget))) return;
      b.classList.add('is-magnet-out');
      b.style.removeProperty('--mx');
      b.style.removeProperty('--my');
    }, { passive: true });
  }

  /* 13. CURSOR GLOW */
  function initGlow() {
    if (calm || !finePointer) return;
    var g = doc.createElement('div');
    g.className = 'ae-glow';
    g.setAttribute('aria-hidden', 'true');
    doc.body.appendChild(g);
    var x = -999, y = -999, tx = -999, ty = -999, raf = 0;
    function step() {
      x += (tx - x) * 0.14; y += (ty - y) * 0.14;
      g.style.setProperty('--gx', x.toFixed(1) + 'px');
      g.style.setProperty('--gy', y.toFixed(1) + 'px');
      raf = (Math.abs(tx - x) + Math.abs(ty - y) > 0.5) ? requestAnimationFrame(step) : 0;
    }
    window.addEventListener('pointermove', function (e) {
      if (e.pointerType !== 'mouse') return;
      if (!g.classList.contains('is-on')) { x = tx = e.clientX; y = ty = e.clientY; g.classList.add('is-on'); }
      tx = e.clientX; ty = e.clientY;
      if (!raf) raf = requestAnimationFrame(step);
    }, { passive: true });
    doc.addEventListener('pointerleave', function () { g.classList.remove('is-on'); });
  }

  /* 15. CARD LIGHT: feed the pointer position to the card spotlight in CSS */
  function initCardLight() {
    if (calm || !finePointer) return;
    var sel = '.ae-offer, .ae-launch__deal, .v5-card';
    doc.addEventListener('pointermove', function (e) {
      if (e.pointerType !== 'mouse') return;
      var c = e.target.closest && e.target.closest(sel);
      if (!c) return;
      var r = c.getBoundingClientRect();
      c.style.setProperty('--sx', (e.clientX - r.left).toFixed(0) + 'px');
      c.style.setProperty('--sy', (e.clientY - r.top).toFixed(0) + 'px');
    }, { passive: true });
  }

  function init() {
    initStagger();
    initAutoFade();
    initReveals();
    initHeader();
    initMenu();
    initTopics();
    initSequence();
    initJumpBar();
    initLegacy();
    initNavPill();
    [initWipe, initSplit, initMagnetic, initGlow, initCardLight].forEach(function (fn) {
      try { fn(); } catch (err) { /* decoration only: never break the page */ }
    });
    html.classList.add('ae-motion-ready');
    doc.body.classList.add('loaded'); // hook some older rules still read
  }

  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init);
  else init();
})();
