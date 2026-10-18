/*!
 * ae-hero-motion.js — calm canvas backgrounds for the AE homepage hero.
 * Two concepts, one file, no dependencies.
 *
 *   <canvas class="ae-hero-motion" data-concept="lines" aria-hidden="true"></canvas>
 *   data-concept="lines"  Concept 1 · Settling lines — a faint field of gold lines that
 *                         wander on the left and settle into parallel order on the right.
 *   data-concept="rings"  Concept 2 · Quiet pulse — slow rings breathing out from the
 *                         hero's ring/dot, over a barely-there gold/forest wash.
 *
 * Behaviour: honours prefers-reduced-motion (draws one static frame, live-updates if the
 * setting changes), pauses when offscreen or when the tab is hidden, caps at ~30 fps and
 * DPR 2. The canvas is sized to its parent (the hero); if an `.ae-hero-art` <img> exists,
 * the ring centre is aligned to the ring/dot inside that image (1210,450 of 1600x900).
 */
(function () {
  'use strict';
  var GOLD = '201,169,110', FOREST = '42,69,53';
  var RING = { x: 1210 / 1600, y: 450 / 900 };           // ring/dot centre in ae-hero-v2.svg
  var mq = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;

  function smooth(t) { t = t < 0 ? 0 : t > 1 ? 1 : t; return t * t * (3 - 2 * t); }

  function init(cv) {
    var ctx = cv.getContext('2d');
    var concept = cv.getAttribute('data-concept') === 'rings' ? 'rings' : 'lines';
    var host = cv.parentElement, art = host.querySelector('.ae-hero-art');
    var w = 0, h = 0, cx = 0, cy = 0, unit = 1, visible = false, raf = 0, last = 0, t0 = performance.now();

    function measure() {
      var r = host.getBoundingClientRect(), dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = r.width; h = r.height;
      cv.width = Math.round(w * dpr); cv.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      cx = w * 0.76; cy = h * 0.5; unit = Math.min(w, 1400) / 1600;
      if (art) {                                           // lock to the art's ring
        var a = art.getBoundingClientRect();
        if (a.width) { cx = a.left - r.left + a.width * RING.x; cy = a.top - r.top + a.height * RING.y; unit = a.width / 1600; }
      }
      draw(reduced() ? 0 : (performance.now() - t0) / 1000);
    }
    function reduced() { return !!(mq && mq.matches); }

    /* ---------- Concept 1: settling lines ---------- */
    function lines(t) {
      var n = Math.max(10, Math.round(h / 48)), step = h / (n + 1), xs = 8;
      ctx.lineWidth = 1; ctx.lineJoin = 'round';
      for (var i = 1; i <= n; i++) {
        var y0 = i * step, edge = 0.08 + 0.1 * Math.sin(i * 1.7);       // per-line phase
        ctx.beginPath();
        for (var x = 0; x <= w + xs; x += xs) {
          var u = x / w, chaos = 1 - smooth((u - 0.15) / 0.7);          // 1 on the left → 0 on the right
          var a = chaos * step * 1.15;
          var y = y0 + a * (Math.sin(u * 5.2 + t * 0.16 + i * 0.9) * 0.6 + Math.sin(u * 11 - t * 0.11 + i * 2.1 + edge) * 0.4);
          x ? ctx.lineTo(x, y) : ctx.moveTo(x, y);
        }
        var fadeIn = 0.5 + 0.5 * Math.sin(i * 0.55 + t * 0.08);          // lines very slowly rise / fall in presence
        ctx.strokeStyle = 'rgba(' + GOLD + ',' + (0.05 + 0.07 * fadeIn).toFixed(3) + ')';
        ctx.stroke();
      }
    }

    /* ---------- Concept 2: quiet pulse ---------- */
    function rings(t) {
      var R = Math.max(w, h) * 0.55, g;
      // wash 1: gold bloom behind the ring, breathing on a ~16 s cycle
      var b = 0.5 + 0.5 * Math.sin(t * (2 * Math.PI / 16));
      g = ctx.createRadialGradient(cx, cy, 0, cx, cy, R);
      g.addColorStop(0, 'rgba(' + GOLD + ',' + (0.10 + 0.06 * b).toFixed(3) + ')');
      g.addColorStop(1, 'rgba(' + GOLD + ',0)');
      ctx.fillStyle = g; ctx.fillRect(0, 0, w, h);
      // wash 2: forest tint drifting slowly low-left, the only cool note
      var dx = Math.sin(t * 0.05) * w * 0.05, dy = Math.cos(t * 0.04) * h * 0.04;
      g = ctx.createRadialGradient(w * 0.18 + dx, h * 0.85 + dy, 0, w * 0.18 + dx, h * 0.85 + dy, R * 0.9);
      g.addColorStop(0, 'rgba(' + FOREST + ',0.035)'); g.addColorStop(1, 'rgba(' + FOREST + ',0)');
      ctx.fillStyle = g; ctx.fillRect(0, 0, w, h);
      // three rings expanding from the dot, staggered, fading as they grow
      var period = 12, maxR = 430 * unit * 1.35;
      ctx.lineWidth = 1.2;
      for (var k = 0; k < 3; k++) {
        var p = ((t / period) + k / 3) % 1, r = (34 + p * maxR) , al = Math.sin(Math.PI * Math.pow(p, 0.7)) * 0.34;
        ctx.strokeStyle = 'rgba(' + GOLD + ',' + al.toFixed(3) + ')';
        ctx.beginPath(); ctx.arc(cx, cy, r, 0, 6.2832); ctx.stroke();
      }
      // dot halo, a slow breath
      var hb = 0.5 + 0.5 * Math.sin(t * (2 * Math.PI / 6));
      ctx.fillStyle = 'rgba(' + GOLD + ',' + (0.10 + 0.10 * hb).toFixed(3) + ')';
      ctx.beginPath(); ctx.arc(cx, cy, (13 + 7 * hb) * unit * 1.2, 0, 6.2832); ctx.fill();
    }

    function draw(t) {
      ctx.clearRect(0, 0, w, h);
      concept === 'rings' ? rings(t) : lines(t);
    }
    function frame(now) {
      raf = 0;
      if (!visible || reduced() || document.hidden) return;
      if (now - last >= 33) { last = now; draw((now - t0) / 1000); }   // ~30 fps
      raf = requestAnimationFrame(frame);
    }
    function sync() {
      var run = visible && !reduced() && !document.hidden;
      if (run && !raf) raf = requestAnimationFrame(frame);
      if (!run && raf) { cancelAnimationFrame(raf); raf = 0; }
    }

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (e) { visible = e[0].isIntersecting; sync(); }).observe(host);
    } else { visible = true; }
    if ('ResizeObserver' in window) new ResizeObserver(measure).observe(host); else window.addEventListener('resize', measure);
    if (art && !art.complete) art.addEventListener('load', measure);
    document.addEventListener('visibilitychange', sync);
    if (mq) (mq.addEventListener ? mq.addEventListener('change', function () { measure(); sync(); }) : mq.addListener(function () { measure(); sync(); }));
    measure(); sync();
  }

  function boot() {
    var list = document.querySelectorAll('canvas.ae-hero-motion');
    for (var i = 0; i < list.length; i++) init(list[i]);
  }
  document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();
})();
