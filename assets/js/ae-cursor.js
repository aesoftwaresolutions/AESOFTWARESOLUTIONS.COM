/**
 * AE Software Solutions: glass circle cursor (desktop only)
 *
 * A small frosted ring that trails the pointer. It is created only when the
 * device has a fine, hovering pointer (a mouse) and the visitor has not asked
 * for reduced motion. Touch, phones and tablets never get the element at all.
 * It never takes clicks (pointer-events: none), hides while a form field has
 * focus, and hides when the pointer leaves the window.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var finePointer = window.matchMedia ? window.matchMedia('(hover: hover) and (pointer: fine)') : null;
  var reduced = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
  if (!finePointer || !finePointer.matches) return;
  if (reduced && reduced.matches) return;

  var ring = null;
  var x = -100, y = -100, rx = -100, ry = -100;
  var seen = false, raf = 0, typing = false, outside = false;
  var FIELD = 'input:not([type=button]):not([type=submit]):not([type=checkbox]):not([type=radio]):not([type=range]):not([type=reset]), textarea, select, [contenteditable=""], [contenteditable="true"]';
  var HOT = 'a[href], button, summary, [role="button"], label, .btn';

  function inField(el) { return !!(el && el.closest && el.closest(FIELD)); }

  function build() {
    ring = doc.createElement('div');
    ring.className = 'ae-glass-cursor';
    ring.setAttribute('aria-hidden', 'true');
    doc.body.appendChild(ring);
    html.classList.add('ae-glass-cursor-on');
  }

  function destroy() {
    if (raf) cancelAnimationFrame(raf);
    raf = 0;
    if (ring && ring.parentNode) ring.parentNode.removeChild(ring);
    ring = null;
    html.classList.remove('ae-glass-cursor-on');
  }

  function visible() { return seen && !typing && !outside; }

  function frame() {
    raf = 0;
    if (!ring) return;
    // Ease toward the pointer; snap when close so it settles and stops.
    rx += (x - rx) * 0.22;
    ry += (y - ry) * 0.22;
    if (Math.abs(x - rx) < 0.1) rx = x;
    if (Math.abs(y - ry) < 0.1) ry = y;
    ring.style.transform = 'translate3d(' + rx.toFixed(1) + 'px,' + ry.toFixed(1) + 'px,0)';
    ring.classList.toggle('is-visible', visible());
    if (rx !== x || ry !== y) raf = requestAnimationFrame(frame);
  }
  function kick() { if (!raf && ring) raf = requestAnimationFrame(frame); }

  function onMove(e) {
    if (e.pointerType && e.pointerType !== 'mouse' && e.pointerType !== 'pen') {
      seen = false; kick(); return; // a finger or stylus on a hybrid device: no ring
    }
    if (!seen) { rx = e.clientX; ry = e.clientY; } // first sight: appear in place, no sweep from the corner
    seen = true;
    outside = false;
    x = e.clientX; y = e.clientY;
    if (ring) {
      var hot = e.target && e.target.closest && e.target.closest(HOT);
      ring.classList.toggle('is-hot', !!hot);
    }
    kick();
  }

  function enable() {
    if (ring) return;
    build();
    window.addEventListener('pointermove', onMove, { passive: true });
    doc.addEventListener('pointerdown', function () { if (ring) ring.classList.add('is-down'); }, true);
    doc.addEventListener('pointerup', function () { if (ring) ring.classList.remove('is-down'); }, true);
    doc.documentElement.addEventListener('pointerleave', function () { outside = true; kick(); });
    doc.documentElement.addEventListener('pointerenter', function () { outside = false; kick(); });
    window.addEventListener('blur', function () { outside = true; kick(); });
    doc.addEventListener('focusin', function (e) { typing = inField(e.target); kick(); });
    doc.addEventListener('focusout', function () { typing = false; kick(); });
    doc.addEventListener('visibilitychange', function () { if (doc.hidden) { outside = true; kick(); } });
  }

  function sync() {
    var ok = finePointer.matches && !(reduced && reduced.matches);
    if (!ok) destroy();
    else if (!ring) { seen = false; enable(); }
  }
  function listen(mq) {
    if (!mq) return;
    if (mq.addEventListener) mq.addEventListener('change', sync);
    else if (mq.addListener) mq.addListener(sync);
  }

  function start() { enable(); listen(finePointer); listen(reduced); }
  if (doc.body) start(); else doc.addEventListener('DOMContentLoaded', start);
})();
