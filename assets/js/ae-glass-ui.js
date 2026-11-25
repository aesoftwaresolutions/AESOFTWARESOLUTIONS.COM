/**
 * AE Software Solutions: liquid glass controls (vanilla, no build step).
 *
 * The control half of @zakisheriff/liquid-glass (MIT, Zaki Sheriff), ported
 * without React. ae-glass.js does the optics; this file only adds behaviour to
 * markup that already exists, and every piece is progressive: with this script
 * missing, the page works exactly as before.
 *
 *  1. GlassInput   clear button on single-line form fields, shown only when the
 *                  field has text. Fires a real `input` event so validation and
 *                  dirty tracking see the change.
 *  2. GlassSegmented  the Rewriter / Summarizer / Email Drafter mode buttons
 *                  become one glass track with a gliding white pill, aria-pressed
 *                  and arrow-key movement. The tools' own click handlers are not
 *                  touched: this only watches the `active` class they set.
 *  3. GlassSlider  feeds the filled share of any range input to CSS (--ae-fill).
 *
 * Not gated like the refraction: no filters, no per-frame work. Reduced motion
 * is handled in CSS (the pill snaps instead of gliding).
 */
(function () {
  'use strict';

  var doc = document;

  /* 1. GLASS INPUT: clear button ------------------------------------------ */
  var CLEAR_TYPES = { text: 1, email: 1, tel: 1, url: 1, search: 1 };
  var X_ICON = '<svg viewBox="0 0 12 12" aria-hidden="true" focusable="false"><path d="M2 2l8 8M10 2l-8 8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" fill="none"/></svg>';

  function labelFor(input) {
    var l = input.id && doc.querySelector('label[for="' + input.id + '"]');
    var text = (l && l.textContent) || input.getAttribute('aria-label') || input.getAttribute('placeholder') || '';
    text = text.replace(/\*/g, '').replace(/\s+/g, ' ').trim();
    return text ? 'Clear ' + text : 'Clear field';
  }

  function addClear(input) {
    if (input.hasAttribute('data-ae-clear-on')) return;
    if (!CLEAR_TYPES[(input.getAttribute('type') || 'text').toLowerCase()]) return;
    if (input.readOnly || input.disabled) return;
    // Never the hidden honeypot fields (tabindex -1, aria-hidden): only real, visible fields.
    if (input.tabIndex < 0 || input.getAttribute('aria-hidden') === 'true') return;
    var holder = input.parentNode;
    if (!holder) return;
    var optedIn = input.hasAttribute('data-ae-clear') || input.type === 'search';
    if (!optedIn && !holder.classList.contains('form-field')) return;
    input.setAttribute('data-ae-clear-on', '');
    // No wrapper element: the form scripts flag errors on input.parentNode,
    // so the button sits beside the field and is placed over it in CSS.
    var btn = doc.createElement('button');
    btn.type = 'button';
    btn.className = 'ae-clear';
    btn.tabIndex = -1;            // keyboard users clear with select-all + delete; keeps tab order unchanged
    btn.setAttribute('aria-label', labelFor(input));
    btn.innerHTML = X_ICON;
    btn.hidden = true;
    holder.classList.add('ae-clearable');
    input.classList.add('ae-has-clear');
    input.insertAdjacentElement('afterend', btn);

    function place() {
      btn.style.setProperty('--ae-clear-top', (input.offsetTop + input.offsetHeight / 2) + 'px');
    }
    function sync() {
      var show = input.value !== '' && !input.disabled && !input.readOnly;
      if (show) place();
      btn.hidden = !show;
    }
    btn.addEventListener('mousedown', function (e) { e.preventDefault(); }); // keep focus in the field
    btn.addEventListener('click', function () {
      input.value = '';
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('change', { bubbles: true }));
      sync();
      input.focus();
    });
    input.addEventListener('input', sync);
    input.addEventListener('change', sync);
    input.addEventListener('focus', sync);
    // Autofill fills fields without an input event in some browsers.
    setTimeout(sync, 600);
    sync();
  }

  function initClear() {
    var fields = doc.querySelectorAll('.contact-form input, input[type="search"], input[data-ae-clear]');
    Array.prototype.forEach.call(fields, addClear);
    // A form reset empties the fields without input events.
    doc.addEventListener('reset', function (e) {
      setTimeout(function () {
        Array.prototype.forEach.call(e.target.querySelectorAll('[data-ae-clear-on]'), function (i) {
          i.dispatchEvent(new Event('change'));
        });
      }, 0);
    }, true);
  }

  /* 2. GLASS SEGMENTED ------------------------------------------------------ */
  function initSegmented() {
    var groups = doc.querySelectorAll('.rewriter-modes__btns');
    Array.prototype.forEach.call(groups, function (group, gi) {
      var btns = Array.prototype.slice.call(group.querySelectorAll('.rewriter-mode-btn'));
      if (btns.length < 2) return;

      // Accessible name from the visible label beside the buttons.
      var label = group.parentNode && group.parentNode.querySelector('.rewriter-modes__label');
      group.setAttribute('role', 'group');
      if (label) {
        if (!label.id) label.id = 'ae-seg-label-' + gi;
        group.setAttribute('aria-labelledby', label.id);
      }
      btns.forEach(function (b) { if (!b.getAttribute('type')) b.type = 'button'; });

      var pill = doc.createElement('span');
      pill.className = 'ae-seg__pill is-still';
      pill.setAttribute('aria-hidden', 'true');
      group.insertBefore(pill, group.firstChild);
      group.classList.add('ae-seg');

      var shown = null;
      function sync(still) {
        var active = btns.filter(function (b) { return b.classList.contains('active'); })[0] || null;
        btns.forEach(function (b) { b.setAttribute('aria-pressed', b === active ? 'true' : 'false'); });
        if (!active || !active.offsetWidth) { pill.classList.remove('is-on'); shown = null; return; }
        var jump = still || !shown;
        if (jump) pill.classList.add('is-still');
        pill.style.setProperty('--sx', active.offsetLeft + 'px');
        pill.style.setProperty('--sy', active.offsetTop + 'px');
        pill.style.setProperty('--sw', active.offsetWidth + 'px');
        pill.style.setProperty('--sh', active.offsetHeight + 'px');
        pill.classList.add('is-on');
        shown = active;
        if (jump) { void pill.offsetWidth; pill.classList.remove('is-still'); }
      }

      // The tool scripts flip the `active` class on click; follow it.
      if ('MutationObserver' in window) {
        var mo = new MutationObserver(function () { sync(false); });
        btns.forEach(function (b) { mo.observe(b, { attributes: true, attributeFilter: ['class'] }); });
      } else {
        btns.forEach(function (b) { b.addEventListener('click', function () { setTimeout(sync, 0); }); });
      }
      if ('ResizeObserver' in window) new ResizeObserver(function () { sync(true); }).observe(group);
      else window.addEventListener('resize', function () { sync(true); });

      // Arrow keys move to the next mode and choose it (Tab still reaches each button).
      group.addEventListener('keydown', function (e) {
        var i = btns.indexOf(doc.activeElement);
        if (i === -1) return;
        var n = null;
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') n = (i + 1) % btns.length;
        else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') n = (i - 1 + btns.length) % btns.length;
        else if (e.key === 'Home') n = 0;
        else if (e.key === 'End') n = btns.length - 1;
        if (n === null) return;
        e.preventDefault();
        btns[n].focus();
        btns[n].click();
      });

      sync(true);
    });
  }

  /* 3. GLASS SLIDER ----------------------------------------------------------- */
  function fill(r) {
    var min = parseFloat(r.min) || 0, max = parseFloat(r.max);
    if (!isFinite(max)) max = 100;
    var v = parseFloat(r.value) || 0;
    var pct = max > min ? ((v - min) / (max - min)) * 100 : 0;
    r.style.setProperty('--ae-fill', Math.max(0, Math.min(100, pct)) + '%');
  }
  function initSliders() {
    Array.prototype.forEach.call(doc.querySelectorAll('input[type="range"]'), fill);
    doc.addEventListener('input', function (e) {
      if (e.target && e.target.type === 'range') fill(e.target);
    });
  }

  function init() { initClear(); initSegmented(); initSliders(); }
  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init);
  else init();
})();
