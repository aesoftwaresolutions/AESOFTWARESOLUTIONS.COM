/**
 * AE Software Solutions: liquid glass refraction (vanilla port).
 *
 * Ported from the refraction engine of @zakisheriff/liquid-glass
 * (https://github.com/zakisheriff/Liquid-Glass, MIT License, Copyright (c) 2026
 * Zaki Sheriff). Only the core optics are ported, in plain JavaScript: no React,
 * no npm, no build step.
 *
 * How it works: for each glass element we ray-trace light through a convex
 * squircle bevel along its rounded edge (Snell's law, index of refraction 1.5)
 * and bake the sideways shift into a displacement map image. An SVG
 * feDisplacementMap then bends whatever sits behind the glass by that map,
 * through `backdrop-filter: url(#id) blur(...)`. A faint white specular rim is
 * baked into the same filter. The surface bends light inward at the rim and
 * leaves the middle flat, like a thick pane of glass.
 *
 * Left out on purpose: colour dispersion (the site is black and white), lens
 * magnification, the Safari and Firefox "mirror copy" fallback, and the touch
 * wobble. See HANDOFF.md.
 *
 * Two modes:
 *  - 'refract': the full effect (bend + rim, through backdrop-filter). Chromium
 *    only, because it is the only engine that runs SVG filters inside
 *    backdrop-filter: desktop Chrome and Edge, and Android Chrome, Samsung
 *    Internet and Edge on phones and tablets.
 *  - 'rim': phones and tablets whose browser cannot refract (every iOS browser
 *    is WebKit underneath, and Firefox). The same baked white rim is drawn as a
 *    static background layer on each surface: the glass edge, without the bend.
 *    No filter, no per-frame cost.
 * A frame probe watches each visit; a phone that drops frames with the
 * refraction falls back to 'rim' (a desktop falls back to plain frosted glass).
 * Desktop Safari and Firefox keep the existing frosted glass, untouched.
 * Static effect: no animation, so reduced motion needs nothing extra.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var nav = navigator;

  // ── Gate ────────────────────────────────────────────────────────────────
  var ua = nav.userAgent || '';
  var chromium = /Chrom(e|ium)\/|Edg\//.test(ua) && !/CriOS|FxiOS|EdgiOS/.test(ua);
  var mq = function (q) { return !!(window.matchMedia && window.matchMedia(q).matches); };
  var desktop = mq('(min-width: 961px) and (pointer: fine)');
  var touch = !desktop && (mq('(pointer: coarse)') || !mq('(min-width: 961px)'));   // phones and tablets
  var mem = nav.deviceMemory, cores = nav.hardwareConcurrency;
  // Phones report less memory than desktops (Chrome caps it), so they get their
  // own floor; the frame probe does the real policing.
  var weak = touch
    ? ((typeof mem === 'number' && mem < 2) || (typeof cores === 'number' && cores < 4))
    : ((typeof mem === 'number' && mem <= 4) || (typeof cores === 'number' && cores <= 4));
  // Quality pin, for the owner or a test: localStorage aeGlass = 'high' (always
  // refract where the browser can, skip the probe), 'rim' (rim only), 'off'
  // (never), anything else = automatic.
  var pin = '';
  try { pin = localStorage.getItem('aeGlass') || ''; } catch (e) {}
  var lowThisVisit = false;
  try { lowThisVisit = sessionStorage.getItem('aeGlassLow') === '1'; } catch (e) {}
  if (pin === 'off' || !window.CSS || !(desktop || touch)) return;
  // A visitor who asked the system for less transparency gets the plain glass
  // (made near-solid in ae-v5.css), never the bend or the rim. Added Nov 25;
  // every gate above and below is unchanged.
  if (mq('(prefers-reduced-transparency: reduce)')) return;
  var canRefract = chromium && CSS.supports('backdrop-filter', 'blur(1px)');
  var MODE;
  if (pin === 'rim') MODE = 'rim';
  else if (canRefract && (pin === 'high' || (!weak && !lowThisVisit))) MODE = 'refract';
  else if (touch) MODE = 'rim';                     // iOS, Firefox, weak or slow phones
  else return;                                      // desktop Safari/Firefox, weak or slow desktops

  // Which surfaces get glass. 'card' matches the shared frosted card rule in ae-v5.css.
  var TARGETS = [
    ['header.site-header', 'bar'],
    ['.site-header__panel', 'panel'],
    ['.v5-card, .ae-offer, .op-steps__list > li, .v5-proof li, .v5-prices__row, .ae-faq details, ' +
     '.op-faq__item, .faq-item, .ae-quote, .work-card, .web-tier, .tier-card, .value-card, .ae-card', 'card']
  ];

  // Modest optics: the brief is subtle glass, not a magnifier.
  var STRENGTH = 0.6;
  var IOR = 1.5;
  // White rim strength. The header bar and the Services panel carry a dark fill
  // over the refraction, so their edges need more light to read as glass at all.
  var RIM_OPACITY = { card: 0.55, panel: 0.9, bar: 0.9 };
  // In 'rim' mode the rim is painted on top of the fill, not under it, so the
  // dark-filled surfaces need less to match.
  var RIM_ONLY_OPACITY = { card: 0.55, panel: 0.45, bar: 0.45 };

  var SVG_NS = 'http://www.w3.org/2000/svg';
  var defs = null;
  var cache = {};
  var count = 0;

  function el(name, attrs) {
    var n = doc.createElementNS(SVG_NS, name);
    for (var k in attrs) n.setAttribute(k, String(attrs[k]));
    return n;
  }
  function container() {
    if (defs && defs.isConnected) return defs;
    var root = el('svg', { width: 0, height: 0, 'aria-hidden': 'true', focusable: 'false' });
    root.style.cssText = 'position:absolute;width:0;height:0;overflow:hidden;pointer-events:none';
    defs = el('defs', {});
    root.appendChild(defs);
    doc.body.appendChild(root);
    return defs;
  }
  function clamp(v, a, b) { return Math.min(b, Math.max(a, v)); }

  // PNG-encode off the main thread (toBlob), as the library does: toDataURL
  // encodes in place and holds up the page for tens of ms on a large surface.
  function encode(canvas) {
    return new Promise(function (done) {
      if (typeof canvas.toBlob !== 'function' || typeof FileReader !== 'function') return done(canvas.toDataURL());
      canvas.toBlob(function (blob) {
        if (!blob) return done(canvas.toDataURL());
        var reader = new FileReader();
        reader.onload = function () { done(reader.result); };
        reader.onerror = function () { done(canvas.toDataURL()); };
        reader.readAsDataURL(blob);
      });
    });
  }

  // Sideways shift (px) of light leaving a convex squircle bevel, by depth into the rim.
  function bevelProfile(bezel, thickness) {
    var N = 256, out = new Float32Array(N);
    for (var i = 0; i < N; i++) {
      var t = Math.max(0.002, i / (N - 1));
      var q = 1 - t;
      var base = 1 - Math.pow(q, 4);
      var height = Math.pow(base, 0.25);
      var slope = Math.pow(q, 3) / Math.max(1e-4, Math.pow(base, 0.75));
      var incident = Math.atan(slope);
      var refracted = Math.asin(Math.sin(incident) / IOR);      // Snell's law
      out[i] = Math.tan(incident - refracted) * thickness * (0.35 + 0.65 * height);
    }
    return function (d) {
      if (d < 0 || d >= bezel) return 0;
      var f = (d / bezel) * (N - 1), j = Math.floor(f), a = out[j], b = out[Math.min(j + 1, N - 1)];
      return a + (b - a) * (f - j);
    };
  }

  // Bake the displacement field and rim for one size, build the SVG filter.
  function bake(w, h, r, kind, mode) {
    var rimOnly = mode === 'rim';
    var rimOpacity = (rimOnly ? RIM_ONLY_OPACITY : RIM_OPACITY)[kind] || 0.55;
    var short = Math.min(w, h);
    var bezel = clamp(short * 0.2, 5, 16);
    var thickness = clamp(short * 0.32, 8, 26);
    var shift = bevelProfile(bezel, thickness);
    // Past the bevel there is no bend, and the rim glow has decayed below 1%:
    // treat it as flat (the library scans 1.7x the bevel; this is cheaper).
    var flat = Math.max(bezel + 1, 5);
    var hw = w / 2, hh = h / 2;

    var n = w * h;
    var dx = new Float32Array(n), dy = new Float32Array(n), rim = new Float32Array(n);
    var outer = new Int32Array(n), edges = 0, max = 0.0001;

    // Only the rim band can bend or shine: rows near the top and bottom are
    // scanned in full, the rest only within `band` px of the left and right edges.
    var band = Math.ceil(flat + r + 2);
    for (var y = 0; y < h; y++) {
      var full = y < band || y >= h - band;
      for (var x = 0; x < w; x++) {
        if (!full && x === band && w - band > band) x = w - band;   // skip the flat middle
        var px = x + 0.5 - hw, py = y + 0.5 - hh;
        // Signed distance to the rounded rect, positive inside.
        var qx = Math.abs(px) - (hw - r), qy = Math.abs(py) - (hh - r);
        var ox = Math.max(qx, 0), oy = Math.max(qy, 0);
        var len = Math.sqrt(ox * ox + oy * oy);
        var d = r - (len + Math.min(Math.max(qx, qy), 0));
        if (d > flat) continue;                       // flat interior: no bend, no rim
        var i = y * w + x;
        outer[edges++] = i;
        var nx, ny;
        if (len > 0) { nx = (ox / len) * Math.sign(px); ny = (oy / len) * Math.sign(py); }
        else if (qx > qy) { nx = Math.sign(px); ny = 0; }
        else { nx = 0; ny = Math.sign(py); }
        var s = -shift(d) * STRENGTH;                 // a surface bends light inward
        dx[i] = nx * s; dy[i] = ny * s;
        var m = Math.max(Math.abs(dx[i]), Math.abs(dy[i]));
        if (m > max) max = m;
        // A full width bar (the header) has one real glass edge, the bottom: no
        // rim down the screen sides or along the top.
        if (d >= -1 && d < bezel && !(kind === 'bar' && ny < 0.7)) {
          // Light from the upper left; both diagonal corners catch it.
          var facing = -(nx + ny) * Math.SQRT1_2;
          var light = 0.22 + 0.78 * Math.pow(Math.abs(facing), 2.2) * (facing > 0 ? 1 : 0.62);
          var hair = 0.75;
          var line = Math.exp(-Math.pow((d - hair) / hair, 2));
          var inner = Math.exp(-Math.pow(d / (bezel * 0.55), 2)) * 0.14;
          rim[i] = clamp((line * 0.7 + inner) * light * clamp(d + 1, 0, 1), 0, 1);
        }
      }
    }

    var e, o, k;
    if (rimOnly) {
      // 'rim' mode: just the white edge, used as a CSS background image.
      var rc = doc.createElement('canvas');
      rc.width = w; rc.height = h;
      var rctx = rc.getContext('2d'), rimg = rctx.createImageData(w, h), rd = rimg.data;
      for (e = 0; e < edges; e++) {
        k = outer[e]; o = k * 4;
        rd[o] = rd[o + 1] = rd[o + 2] = 255;            // white only
        rd[o + 3] = Math.round(rim[k] * 255 * rimOpacity);
      }
      rctx.putImageData(rimg, 0, 0);
      return encode(rc).then(function (url) { return 'url("' + url + '")'; });
    }

    var canvas = doc.createElement('canvas');
    canvas.width = w; canvas.height = h;
    var ctx = canvas.getContext('2d');
    var img = ctx.createImageData(w, h), data = img.data;
    new Uint32Array(data.buffer).fill(new Uint32Array(new Uint8ClampedArray([128, 128, 128, 255]).buffer)[0]);  // neutral: no shift
    for (e = 0; e < edges; e++) {
      k = outer[e]; o = k * 4;
      data[o] = Math.round(127.5 + (dx[k] / max) * 127.5);
      data[o + 1] = Math.round(127.5 + (dy[k] / max) * 127.5);
    }
    ctx.putImageData(img, 0, 0);
    var mapURL = encode(canvas);

    var shine = ctx.createImageData(w, h), sd = shine.data;
    for (e = 0; e < edges; e++) {
      k = outer[e]; o = k * 4;
      sd[o] = sd[o + 1] = sd[o + 2] = 255;            // white only
      sd[o + 3] = Math.round(rim[k] * 255 * rimOpacity);
    }
    // toBlob copies the pixels at call time, so the canvas can be reused at once.
    var canvas2 = doc.createElement('canvas');
    canvas2.width = w; canvas2.height = h;
    canvas2.getContext('2d').putImageData(shine, 0, 0);
    var rimURL = encode(canvas2);

    var scale = max * 2;
    return Promise.all([mapURL, rimURL]).then(function (urls) { return assemble(w, h, scale, urls[0], urls[1]); });
  }

  function assemble(w, h, scale, mapURL, rimURL) {
    var id = 'ae-glass-' + (++count);
    var area = { x: 0, y: 0, width: w, height: h, preserveAspectRatio: 'none' };
    var f = el('filter', { id: id, x: -0.25, y: -0.25, width: 1.5, height: 1.5, 'color-interpolation-filters': 'sRGB' });
    f.appendChild(el('feImage', Object.assign({ href: mapURL, result: 'field' }, area)));
    f.appendChild(el('feDisplacementMap', { in: 'SourceGraphic', in2: 'field', scale: scale, xChannelSelector: 'R', yChannelSelector: 'G', result: 'bent' }));
    f.appendChild(el('feImage', Object.assign({ href: rimURL, result: 'rim' }, area)));
    f.appendChild(el('feComposite', { in: 'rim', in2: 'bent', operator: 'over' }));
    container().appendChild(f);
    return 'url(#' + id + ')';
  }

  function radiusOf(node, w, h) {
    var v = getComputedStyle(node).borderTopLeftRadius, n = parseFloat(v);
    if (!isFinite(n)) return 0;
    return Math.min(w / 2, h / 2, v.slice(-1) === '%' ? (Math.min(w, h) * n) / 100 : n);
  }

  // Bake one element for its current size (cached by size), on idle time.
  function fit(node) {
    if (MODE !== 'refract' && MODE !== 'rim') return;
    var w = Math.round(node.offsetWidth), h = Math.round(node.offsetHeight);
    if (!w || !h || w * h > 1600000) return;          // nothing to draw, or too big to bake cheaply
    if (MODE === 'refract' && node.getAttribute('data-ae-glass') === 'card' && !node.hasAttribute('data-ae-glass-key')) {
      // Keep the card's own frosting exactly; refraction is added in front of it.
      var base = getComputedStyle(node).backdropFilter;
      if (!base || base === 'none') return;            // not a glass card here: leave it alone
      node.style.setProperty('--ae-glass-base', base);
    }
    var r = radiusOf(node, w, h);
    var kind = node.getAttribute('data-ae-glass');
    var mode = MODE;
    var key = mode + ':' + kind + ':' + w + 'x' + h + 'r' + Math.round(r);
    if (!cache[key]) cache[key] = bake(w, h, r, kind, mode);
    node.setAttribute('data-ae-glass-key', key);
    pending++;
    cache[key].then(function (url) {
      pending--;
      // A newer size or mode may have been baked meanwhile: only the current one applies.
      if (node.getAttribute('data-ae-glass-key') !== key) return;
      if (mode === 'rim') { node.style.setProperty('--ae-rim-img', url); node.setAttribute('data-ae-rim', ''); }
      else node.style.setProperty('--ae-refract-src', url);
      // Probe once everything near the viewport is baked and showing.
      if (mode === 'refract' && !pending && !queue.length) probe();
    });
  }

  var queue = [];
  var pending = 0;
  var pumping = false;
  var idle = window.requestIdleCallback || function (cb) { return setTimeout(function () { cb({ timeRemaining: function () { return 8; } }); }, 16); };
  function pump() {
    pumping = true;
    idle(function (deadline) {
      var ran = 0;
      while (queue.length && (ran === 0 || deadline.timeRemaining() > 4)) { fit(queue.shift()); ran++; }
      if (queue.length) pump(); else pumping = false;
    }, { timeout: 1500 });
  }
  // Quality governor (ported from the library's quality.ts). The page background
  // drifts and the home carousel glides behind the glass, so the refraction
  // re-runs on those frames. Once glass is showing, watch the next 8 seconds of
  // frames (long enough to include a carousel glide). If 12 or more miss the
  // frame budget, this device can't carry it: drop back to the plain frosted
  // glass for the rest of the visit (later pages skip the work entirely).
  // On a phone the glass is redrawn mostly while scrolling, so a phone also
  // gets a second 8 second window starting at its first scroll. A phone that
  // fails falls back to 'rim' (the glass edge, no per-frame cost); a desktop
  // falls back to plain frosted glass.
  var probes = 0, probing = false;
  function probe() {
    if (probing || pin === 'high' || MODE !== 'refract' || probes >= (touch ? 2 : 1)) return;
    probes++; probing = true;
    var slow = 0, last = 0, end = performance.now() + 8000;
    var step = function (t) {
      if (MODE !== 'refract') { probing = false; return; }
      if (doc.hidden) { last = 0; requestAnimationFrame(step); return; }   // a hidden tab says nothing
      if (last && t - last > 26 && t - last < 1000) slow++;
      last = t;
      if (slow >= 12) { probing = false; fallBack(); return; }
      if (t < end) requestAnimationFrame(step);
      else probing = false;
    };
    requestAnimationFrame(step);
  }
  if (touch) {
    window.addEventListener('scroll', function first() {
      window.removeEventListener('scroll', first);
      if (probing) return;              // the load window is still running and covers it
      probe();
    }, { passive: true });
  }
  function fallBack() {
    try { sessionStorage.setItem('aeGlassLow', '1'); } catch (e) {}
    html.classList.remove('ae-glass-on');
    html.classList.add('ae-glass-low');
    if (!touch) { MODE = 'off'; return; }
    // Phones keep the look: re-bake every surface as rim only.
    MODE = 'rim';
    html.classList.add('ae-glass-rim');
    Array.prototype.forEach.call(doc.querySelectorAll('[data-ae-glass-key]'), function (n) {
      n.style.removeProperty('--ae-refract-src');
      n.removeAttribute('data-ae-glass-key');
      enqueue(n, true);
    });
  }

  function enqueue(node, urgent) {
    if (queue.indexOf(node) !== -1) return;
    if (urgent) queue.unshift(node); else queue.push(node);
    if (!pumping) pump();
  }

  function start() {
    var nodes = [];
    TARGETS.forEach(function (t) {
      Array.prototype.forEach.call(doc.querySelectorAll(t[0]), function (node) {
        if (node.hasAttribute('data-ae-glass')) return;
        if (t[1] === 'panel' && touch) return;          // the dropdown is desktop only
        node.setAttribute('data-ae-glass', t[1]);
        nodes.push(node);
      });
    });
    if (!nodes.length) return;
    html.classList.add(MODE === 'refract' ? 'ae-glass-on' : 'ae-glass-rim');

    // Glass well off screen skips refraction (and is baked when it nears view).
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var node = entry.target;
        if (entry.isIntersecting) {
          node.removeAttribute('data-ae-glass-off');
          if (!node.hasAttribute('data-ae-glass-key')) enqueue(node, true);
        } else {
          node.setAttribute('data-ae-glass-off', '');
        }
      });
    }, { rootMargin: '300px 0px' });
    nodes.forEach(function (node) {
      // Fixed surfaces (header, panel) are always "on screen": bake them first.
      if (node.matches('header.site-header, .site-header__panel')) enqueue(node, true);
      else io.observe(node);
    });

    // Re-bake when a surface changes size (window resize, wrapping text).
    if ('ResizeObserver' in window) {
      var timer = 0, dirty = [];
      var ro = new ResizeObserver(function (entries) {
        entries.forEach(function (en) { if (en.target.hasAttribute('data-ae-glass-key')) dirty.push(en.target); });
        clearTimeout(timer);
        timer = setTimeout(function () { dirty.splice(0).forEach(function (n) { enqueue(n, false); }); }, 180);
      });
      nodes.forEach(function (node) { ro.observe(node); });
    }
  }

  // Decoration only: start after the page has loaded, so baking never competes
  // with first paint, the hero or the carousel images.
  if (doc.readyState === 'complete') start();
  else window.addEventListener('load', start);
})();
