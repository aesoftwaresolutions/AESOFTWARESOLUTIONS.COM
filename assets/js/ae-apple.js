/**
 * AE Software Solutions: Apple style touches (Dynamic Island + iOS sheets)
 *
 * 1. Island: a small floating pill at the top centre. It starts compact (just
 *    the dot), opens to show a short status line, then settles back. With
 *    reduced motion it stays as a static pill and nothing animates.
 * 2. Sheets: the mobile menu is an iOS bottom sheet. Tapping the dimmed area
 *    or dragging the grabber down closes it. It reuses the existing hamburger
 *    toggle (ae-motion.js owns open and close), so focus handling is unchanged.
 * The glass cursor (ae-cursor.js) is not touched.
 */
(function () {
  'use strict';
  var doc = document;
  var html = doc.documentElement;
  var calm = html.classList.contains('ae-calm') ||
    (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

  /* 1. ISLAND */
  // Centre the pill in the free space between the logo and the nav (or the
  // CTA and hamburger on phones), so it never sits on a link. If there is no
  // room it is hidden instead of covering the header.
  function placeIsland(island) {
    var logo = doc.querySelector('.site-header__logo');
    var right = Infinity;
    ['.site-header__nav', '.site-header__cta', '#nav-toggle'].forEach(function (sel) {
      var el = doc.querySelector(sel);
      if (!el || !el.offsetWidth) return;
      right = Math.min(right, el.getBoundingClientRect().left);
    });
    if (!logo || right === Infinity) return;
    var left = logo.getBoundingClientRect().right;
    var room = right - left - 24;
    var narrow = room < 250;
    island.classList.toggle('is-squeezed', narrow);
    island.style.setProperty('--island-room', Math.max(0, Math.floor(room)) + 'px');
    island.style.left = Math.round((left + right) / 2) + 'px';
    html.classList.add('ae-island-placed');
  }

  function initIsland() {
    var island = doc.getElementById('ae-island');
    if (!island) return;
    var place = function () { placeIsland(island); };
    place();
    window.addEventListener('resize', place);
    window.addEventListener('load', place);
    if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(place);
    var text = island.querySelector('.ae-island__text');
    var lines = (island.getAttribute('data-lines') || '').split('|').filter(Boolean);
    if (calm || !text || !lines.length) { island.classList.add('is-open'); return; }
    var i = 0;
    var timers = [];
    function later(fn, ms) { timers.push(setTimeout(fn, ms)); }
    function cycle() {
      text.textContent = lines[i % lines.length];
      island.classList.add('is-open');
      i++;
      later(cycle, 7000);
    }
    later(cycle, 900);
    doc.addEventListener('visibilitychange', function () {
      if (doc.hidden) { timers.forEach(clearTimeout); timers = []; island.classList.add('is-open'); }
    });
  }

  /* 2. MOBILE MENU SHEET: scrim tap and drag down to close */
  function initSheet() {
    var overlay = doc.getElementById('mobile-nav');
    var toggle = doc.getElementById('nav-toggle');
    if (!overlay || !toggle) return;
    var sheet = overlay.querySelector('[data-sheet]');
    function close() { if (overlay.classList.contains('is-open')) toggle.click(); }
    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
    if (!sheet || !window.PointerEvent) return;
    var startY = null, dy = 0;
    sheet.addEventListener('pointerdown', function (e) {
      // Only drag from the top strip of the sheet (grabber area), never from links.
      var r = sheet.getBoundingClientRect();
      if (e.clientY - r.top > 44) return;
      startY = e.clientY; dy = 0;
      try { sheet.setPointerCapture(e.pointerId); } catch (x) {}
      sheet.style.transition = 'none';
    });
    sheet.addEventListener('pointermove', function (e) {
      if (startY === null) return;
      dy = Math.max(0, e.clientY - startY);
      sheet.style.transform = 'translateY(' + dy + 'px)';
    });
    function end() {
      if (startY === null) return;
      var shouldClose = dy > 80;
      startY = null;
      sheet.style.transition = '';
      sheet.style.transform = '';
      if (shouldClose) close();
    }
    sheet.addEventListener('pointerup', end);
    sheet.addEventListener('pointercancel', end);
  }

  function init() { initIsland(); initSheet(); }
  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init);
  else init();
})();
