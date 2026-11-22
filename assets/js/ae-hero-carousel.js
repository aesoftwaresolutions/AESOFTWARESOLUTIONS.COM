/**
 * AE Software Solutions: front page hero carousel.
 *
 * Glides from product to product on its own. There are no visible controls.
 *  - One slide is shown at a time; the track moves sideways by one slide width.
 *  - A copy of the first slide sits at the end, so the last slide glides into
 *    slide 1 and the loop never runs backwards.
 *  - It pauses while a pointer is over it or keyboard focus is inside it, while
 *    the tab is hidden, and while the carousel is off screen.
 *  - Slides that are not showing are inert, so focus never lands off screen.
 *  - Reduced motion: slides swap without the glide, on a slower timer.
 *  - Without this file the first slide shows on its own (the others are inert
 *    in the HTML).
 */
(function () {
  'use strict';

  var root = document.querySelector('[data-ae-hc]');
  if (!root) return;
  var track = root.querySelector('[data-ae-hc-track]');
  var slides = Array.prototype.slice.call(root.querySelectorAll('[data-ae-hc-slide]'));
  var count = slides.length;
  if (!track || count < 2) return;

  var calm = document.documentElement.classList.contains('ae-calm') ||
    (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  var DWELL = calm ? 8000 : 5500;   // time each slide stays in view, in ms
  var GLIDE = 900;                  // must match the CSS transition on .ae-hc__track

  // Load every slide image up front so a slide never glides in blank. If a
  // lazy loader (LiteSpeed) still swapped the real image for a placeholder
  // (data-src / data-srcset), put the real one back first. This runs before
  // slide 1 is cloned, so the clone gets the real image too.
  slides.forEach(function (s) {
    var img = s.querySelector('img');
    if (!img) return;
    var ds = img.getAttribute('data-src');
    var dss = img.getAttribute('data-srcset');
    var dsz = img.getAttribute('data-sizes');
    if (dsz) img.setAttribute('sizes', dsz);
    if (dss) img.setAttribute('srcset', dss);
    if (ds) img.setAttribute('src', ds);
    ['data-src', 'data-srcset', 'data-sizes', 'data-lazyloaded'].forEach(function (a) { img.removeAttribute(a); });
    img.loading = 'eager';
  });

  // Clone of slide 1 at the end, for a seamless wrap. Never focusable or read.
  var clone = slides[0].cloneNode(true);
  clone.setAttribute('aria-hidden', 'true');
  clone.setAttribute('inert', '');
  clone.removeAttribute('data-ae-hc-slide');
  clone.removeAttribute('aria-roledescription');
  clone.removeAttribute('aria-label');
  var cloneImg = clone.querySelector('img');
  if (cloneImg) { cloneImg.removeAttribute('fetchpriority'); cloneImg.alt = ''; }
  track.appendChild(clone);

  root.classList.add('is-ready');
  if (calm) root.classList.add('is-calm');

  var index = 0;
  var timer = null;
  var hovered = false;
  var focused = false;
  var visible = true;

  function setActive(i) {
    slides.forEach(function (s, n) {
      var on = n === i;
      if (on) { s.removeAttribute('inert'); s.removeAttribute('aria-hidden'); }
      else { s.setAttribute('inert', ''); s.setAttribute('aria-hidden', 'true'); }
    });
  }

  function place(i, animate) {
    if (!animate) track.classList.add('is-jump');
    track.style.transform = 'translate3d(' + (-100 * i) + '%, 0, 0)';
    if (!animate) {
      void track.offsetWidth; // apply the jump before the transition comes back
      track.classList.remove('is-jump');
    }
  }

  function next() {
    index += 1;
    if (calm) {
      if (index >= count) index = 0;
      place(index, false);
      setActive(index);
      return;
    }
    place(index, true);
    if (index === count) {
      // Showing the clone of slide 1: once the glide ends, jump to the real one.
      setActive(0);
      window.setTimeout(function () {
        index = 0;
        place(0, false);
      }, GLIDE + 50);
    } else {
      setActive(index);
    }
  }

  function running() { return !hovered && !focused && visible && !document.hidden; }

  function stop() { if (timer) { window.clearInterval(timer); timer = null; } }
  function sync() {
    if (running()) { if (!timer) timer = window.setInterval(next, DWELL); }
    else stop();
  }

  root.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') { hovered = true; sync(); } });
  root.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') { hovered = false; sync(); } });
  root.addEventListener('focusin', function () { focused = true; sync(); });
  root.addEventListener('focusout', function (e) {
    if (!root.contains(e.relatedTarget)) { focused = false; sync(); }
  });
  document.addEventListener('visibilitychange', sync);

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      sync();
    }, { threshold: 0.15 }).observe(root);
  }

  place(0, false);
  setActive(0);
  sync();
})();
