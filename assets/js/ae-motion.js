/**
 * AE Software Solutions — motion engine (v5)
 *
 * One script for every animation on the site. It replaces ae-animations.js,
 * main.js and the inline script that used to live in footer.php.
 *
 *  1. Loader          first page of a visit only; counter follows real loading
 *  2. Split text      [data-split] + key headings rise line by line from a mask
 *  3. Reveals         [data-reveal], .js-fade, .ae-rule, .ae-media on scroll
 *  4. Header          compact state once the page scrolls
 *  5. Mobile menu     open/close, staggered links, Escape, focus return
 *  6. Page transition panel covers the page on internal links, uncovers on arrival
 *  7. Hero window     cycles project screenshots inside the home headline
 *  8. Extras          eyebrow underlines, section rules, process connector
 *
 * Everything respects prefers-reduced-motion: content is shown immediately.
 * Page markup sets html.ae-js in <head> so nothing is hidden without JS.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var calm = html.classList.contains('ae-calm') ||
    (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

  var EASE_MS = 1100;          // line reveal duration (matches CSS)
  var LINE_STAGGER = 0.09;     // seconds between lines
  var ready = false;           // true once the loader / arrival panel is out of the way
  var pending = [];            // reveals that fired before `ready`
  var openWindow = function () {};

  /* ──────────────────────────────────────────────────────────────────────
     2. SPLIT TEXT
     Each word becomes <span class="ae-w"><span class="ae-wi">word</span></span>.
     .ae-w clips, .ae-wi rises. Words on the same rendered line share a delay,
     so a whole line comes up together, the way a masked line would — but it
     still works with <strong>, <em>, <a>, <br> inside the heading.
  ────────────────────────────────────────────────────────────────────── */
  var AUTO_SPLIT = [
    '.site-main h1',
    '.site-main h2',
    '.page-hero__subtitle',
    '.mission-pullquote',
    '[data-reveal-text]'
  ].join(',');

  var SKIP_INSIDE = '.mobile-nav-overlay, .ae-widget-container, form, .web-tier, .starter-pricing-card, .contact-sidebar__badge, ae-cta';

  function canSplit(el) {
    if (el.closest(SKIP_INSIDE)) return false;
    if (el.querySelector('img, svg, input, textarea, select, button, canvas, video')) return false;
    if (el.classList.contains('no-split')) return false;
    return el.textContent.trim().length > 0;
  }

  function splitWords(el) {
    var walker = doc.createTreeWalker(el, NodeFilter.SHOW_TEXT, null);
    var nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);

    nodes.forEach(function (node) {
      var parts = node.nodeValue.split(/(\s+)/);
      if (parts.length === 1 && !parts[0].trim()) return;
      var frag = doc.createDocumentFragment();
      parts.forEach(function (part) {
        if (!part) return;
        if (/^\s+$/.test(part)) {
          frag.appendChild(doc.createTextNode(part));
        } else {
          var w = doc.createElement('span');
          w.className = 'ae-w';
          var wi = doc.createElement('span');
          wi.className = 'ae-wi';
          wi.textContent = part;
          w.appendChild(wi);
          frag.appendChild(w);
        }
      });
      node.parentNode.replaceChild(frag, node);
    });
  }

  // Assign each word a line index from its rendered position.
  function measureLines(el) {
    var words = el.querySelectorAll('.ae-w');
    var line = -1;
    var lastTop = null;
    var base = parseFloat(el.getAttribute('data-split-delay') || '0') || 0;
    words.forEach(function (w) {
      var top = Math.round(w.getBoundingClientRect().top);
      if (lastTop === null || Math.abs(top - lastTop) > 4) {
        line++;
        lastTop = top;
      }
      w.firstChild.style.transitionDelay = (base + line * LINE_STAGGER).toFixed(2) + 's';
    });
  }

  function initSplit() {
    var targets = [];
    doc.querySelectorAll('[data-split]').forEach(function (el) { targets.push(el); });
    doc.querySelectorAll(AUTO_SPLIT).forEach(function (el) {
      if (targets.indexOf(el) === -1) targets.push(el);
    });

    targets.forEach(function (el) {
      // Don't split a heading that sits inside another split element.
      if (el.parentElement && el.parentElement.closest('.ae-split')) return;
      if (!canSplit(el)) {
        el.classList.add('ae-split-skip');
        return;
      }
      splitWords(el);
      el.classList.add('ae-split');
    });
  }

  /* ──────────────────────────────────────────────────────────────────────
     3. REVEALS
  ────────────────────────────────────────────────────────────────────── */
  function reveal(el) {
    if (el.classList.contains('ae-split')) measureLines(el);
    el.classList.add('is-revealed');
    if (el.classList.contains('js-fade')) el.classList.add('js-fade--in');
    if (el.matches('.eyebrow, .section-eyebrow, .hero-eyebrow, .page-hero__eyebrow, .reveal-label, .process-eyebrow')) {
      el.classList.add('ae-eyebrow-in');
    }
    if (el.classList.contains('ae-gold-rule')) el.classList.add('ae-drawn');
  }

  function queue(el) {
    if (ready) reveal(el);
    else pending.push(el);
  }

  function initAutoFade() {
    // Legacy inner-page blocks and card grids: fade/rise with a gentle stagger.
    ['.stats-row', '.process-header', '.section-header', '.inner-section-header'].forEach(function (sel) {
      doc.querySelectorAll(sel).forEach(function (el) { el.classList.add('js-fade'); });
    });
    ['.tiers-grid', '.tier-cols', '.bs-cards', '.service-cards-grid', '.values-grid', '.bs-features-grid',
     '.v5-work__grid', '.web-tier-grid', '.industries-grid-small'].forEach(function (sel) {
      doc.querySelectorAll(sel).forEach(function (parent) {
        Array.prototype.forEach.call(parent.children, function (child, i) {
          child.style.setProperty('--delay', (i % 4) * 0.09 + 's');
          if (!child.hasAttribute('data-reveal')) child.classList.add('js-fade');
        });
      });
    });
  }

  function initSectionRules() {
    // Thin rule between consecutive top-level sections on inner pages.
    if (doc.querySelector('.v5-hero')) return;
    doc.querySelectorAll('.site-main > section').forEach(function (section, i) {
      if (i === 0) return;
      var rule = doc.createElement('span');
      rule.className = 'ae-gold-rule';
      rule.setAttribute('aria-hidden', 'true');
      section.parentNode.insertBefore(rule, section);
    });
  }

  function initReveals() {
    var sel = '[data-reveal], .js-fade, .ae-split, .ae-rule, .ae-media, .ae-gold-rule, ' +
      '.eyebrow, .section-eyebrow, .hero-eyebrow, .page-hero__eyebrow, .reveal-label, .process-eyebrow';
    var els = doc.querySelectorAll(sel);

    if (calm || !('IntersectionObserver' in window)) {
      els.forEach(reveal);
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        io.unobserve(entry.target);
        queue(entry.target);
      });
    }, { rootMargin: '0px 0px -6% 0px', threshold: 0.01 });

    els.forEach(function (el) { io.observe(el); });
  }

  function setReady() {
    if (ready) return;
    ready = true;
    html.classList.add('ae-ready');
    // Keep document order so stagger reads top-to-bottom.
    pending.sort(function (a, b) {
      return a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
    });
    pending.forEach(reveal);
    pending = [];
    openWindow();
  }

  /* ──────────────────────────────────────────────────────────────────────
     1. LOADER — counts real progress: web fonts + images in the first screen.
  ────────────────────────────────────────────────────────────────────── */
  function runLoader(done) {
    var loader = doc.getElementById('ae-loader');
    var num = doc.getElementById('ae-loader-num');
    if (!loader || html.classList.contains('ae-skip-loader') || calm) {
      if (loader) loader.parentNode.removeChild(loader);
      done();
      return;
    }

    var tasks = [];
    if (doc.fonts && doc.fonts.ready) tasks.push(doc.fonts.ready);
    var fold = window.innerHeight * 1.2;
    doc.querySelectorAll('img').forEach(function (img) {
      if (img.getBoundingClientRect().top > fold) return;
      if (img.complete) return;
      tasks.push(new Promise(function (res) {
        img.addEventListener('load', res, { once: true });
        img.addEventListener('error', res, { once: true });
      }));
    });

    var total = Math.max(tasks.length, 1);
    var finished = 0;
    var shown = 0;
    var target = tasks.length ? 0 : 100;
    var start = performance.now();
    var MIN_MS = 700, MAX_MS = 3500;

    tasks.forEach(function (t) {
      Promise.resolve(t).then(function () { finished++; target = Math.round(finished / total * 100); });
    });

    function tick(now) {
      var elapsed = now - start;
      // Never stall at a number for long, never finish before MIN_MS.
      var floor = Math.min(95, elapsed / MAX_MS * 100);
      var goal = Math.max(target, floor);
      if (elapsed < MIN_MS) goal = Math.min(goal, elapsed / MIN_MS * 99);
      shown += (goal - shown) * 0.18;
      if (num) num.textContent = Math.round(shown) + '%';
      if ((target >= 100 && elapsed >= MIN_MS && shown > 99) || elapsed > MAX_MS) {
        if (num) num.textContent = '100%';
        loader.classList.add('is-done');
        setTimeout(done, 250);
        setTimeout(function () { if (loader.parentNode) loader.parentNode.removeChild(loader); }, 1300);
        return;
      }
      requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  /* ──────────────────────────────────────────────────────────────────────
     6. PAGE TRANSITIONS
  ────────────────────────────────────────────────────────────────────── */
  var panel;

  function arrive(done) {
    panel = doc.getElementById('ae-transition');
    if (!panel || !html.classList.contains('ae-arriving')) { done(); return; }
    // Panel starts covering (CSS); slide it off the top.
    requestAnimationFrame(function () {
      panel.classList.add('is-leaving');
      html.classList.remove('ae-arriving');
      setTimeout(done, 280);
      setTimeout(function () {
        // Park it below the screen again without animating across the page.
        panel.classList.add('no-anim');
        panel.classList.remove('is-leaving');
        void panel.offsetWidth;
        panel.classList.remove('no-anim');
      }, 950);
    });
  }

  function shouldTransition(a, e) {
    if (calm || !panel) return false;
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return false;
    if (a.target && a.target !== '_self') return false;
    if (a.hasAttribute('download') || a.hasAttribute('data-no-transition')) return false;
    var url;
    try { url = new URL(a.href, location.href); } catch (err) { return false; }
    if (url.origin !== location.origin) return false;
    if (/^\/(wp-admin|wp-login|wp-json|feed)/.test(url.pathname)) return false;
    if (/\.(pdf|jpe?g|png|gif|webp|zip|mp3|mp4)$/i.test(url.pathname)) return false;
    if (url.pathname === location.pathname && url.search === location.search) return false; // same page / hash
    return url;
  }

  function initTransitions() {
    panel = doc.getElementById('ae-transition');
    doc.addEventListener('click', function (e) {
      var a = e.target.closest && e.target.closest('a[href]');
      if (!a) return;
      var url = shouldTransition(a, e);
      if (!url) return;
      e.preventDefault();
      try { sessionStorage.setItem('ae_transition', '1'); } catch (err) {}
      closeMenu(false);
      panel.classList.add('is-covering');
      setTimeout(function () { location.href = url.href; }, 560);
      // If navigation is cancelled (e.g. a download dialog), don't leave the page covered.
      setTimeout(function () { panel.classList.remove('is-covering'); }, 4000);
    });

    // Back/forward cache: the page comes back exactly as we left it — covered.
    window.addEventListener('pageshow', function (e) {
      if (e.persisted && panel) {
        panel.classList.remove('is-covering');
        html.classList.remove('ae-arriving');
        try { sessionStorage.removeItem('ae_transition'); } catch (err) {}
      }
    });
  }

  /* ──────────────────────────────────────────────────────────────────────
     4. HEADER
  ────────────────────────────────────────────────────────────────────── */
  function initHeader() {
    var header = doc.querySelector('.site-header');
    if (!header) return;
    var ticking = false;
    function update() {
      ticking = false;
      header.classList.toggle('aec--scrolled', window.scrollY > 40);
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
    update();
  }

  /* ──────────────────────────────────────────────────────────────────────
     5. MOBILE MENU
  ────────────────────────────────────────────────────────────────────── */
  var toggle, overlay;

  function openMenu() {
    overlay.classList.add('is-open');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Close menu');
    doc.body.classList.add('nav-open');
    var first = overlay.querySelector('a');
    if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 300);
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
    overlay.querySelectorAll('.mobile-nav-overlay__links li').forEach(function (li, i) {
      li.style.setProperty('--i', i);
    });
    toggle.addEventListener('click', function () {
      overlay.classList.contains('is-open') ? closeMenu() : openMenu();
    });
    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMenu();
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth > 960) closeMenu(false);
    });
  }

  /* ──────────────────────────────────────────────────────────────────────
     7. HERO IMAGE WINDOW
  ────────────────────────────────────────────────────────────────────── */
  function initWindow() {
    var win = doc.querySelector('.v5-window');
    if (!win) return;
    var imgs = win.querySelectorAll('.v5-window__img');
    // Open once the headline has risen (setReady calls openWindow).
    openWindow = function () {
      setTimeout(function () { win.classList.add('is-open'); }, calm ? 0 : 650);
    };
    if (calm || imgs.length < 2) return;
    var i = 0;
    setInterval(function () {
      if (doc.hidden) return;
      var prev = imgs[i];
      i = (i + 1) % imgs.length;
      prev.classList.remove('is-active');
      prev.classList.add('is-leaving');
      imgs[i].classList.add('is-active');
      setTimeout(function () { prev.classList.remove('is-leaving'); }, 900);
    }, 2400);
  }

  /* ──────────────────────────────────────────────────────────────────────
     8. EXTRAS kept from the previous engine
  ────────────────────────────────────────────────────────────────────── */
  function initProcessConnector() {
    var track = doc.querySelector('.process-track');
    if (!track) return;
    if (calm || !('IntersectionObserver' in window)) { track.classList.add('connector-drawn'); return; }
    var io = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) { track.classList.add('connector-drawn'); io.disconnect(); }
    }, { threshold: 0.25 });
    io.observe(track);
  }

  function initAssessmentBadges() {
    var hero = doc.querySelector('.assessment-hero');
    if (hero) hero.classList.add('badges-ready');
  }

  /* ──────────────────────────────────────────────────────────────────────
     INIT
  ────────────────────────────────────────────────────────────────────── */
  function init() {
    initAutoFade();
    initSectionRules();
    initSplit();
    initReveals();
    initHeader();
    initMenu();
    initTransitions();
    initWindow();
    initProcessConnector();
    initAssessmentBadges();
    html.classList.add('ae-motion-ready');

    doc.body.classList.add('loaded'); // legacy hook used by ae-chrome.css
    runLoader(function () { arrive(setReady); });

    // Re-measure line delays after a resize (only matters for unrevealed text).
    var t;
    window.addEventListener('resize', function () {
      clearTimeout(t);
      t = setTimeout(function () {
        doc.querySelectorAll('.ae-split:not(.is-revealed)').forEach(measureLines);
      }, 200);
    });
  }

  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init);
  else init();
})();
