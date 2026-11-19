/**
 * AE Software Solutions: glass detail popup
 *
 * One reusable popup (markup in popup-detail.php, styles in ae-v5.css under
 * "Glass card popup" plus "Glass detail popup"). Any button with data-ae-detail
 * opens it with the matching hidden content block. Attributes on the trigger:
 *   data-ae-detail-target="approach"     slug of the hidden div ae-detail-approach
 *   data-ae-detail-title="How we work"   card title
 *   data-ae-detail-eyebrow="..."         optional eyebrow above the title
 *
 * The hidden block stays in the page markup. On open its content is cloned into
 * the card (ids stripped from clones so nothing is duplicated). The original
 * stays hidden in place, so opening again is a fresh clone.
 *
 * Behaviour mirrors the contact popup: closes on the close button, Escape, a tap
 * outside the card, and a downward drag on the phone sheet grabber. Keyboard
 * focus is trapped inside while it is open (the rest of the page is made inert)
 * and returns to the opener. The page behind blurs via html.ae-pop-lock.
 *
 * Coexisting with the contact popup: a "Book a discovery call" link inside a
 * detail popup opens the contact popup instead. The detail popup closes first,
 * and the blur lock is only released when neither popup is open.
 *
 * Reduced motion: opens and closes at once, no animation.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var pop = doc.getElementById('ae-detail-pop');
  if (!pop) return;

  var card = pop.querySelector('.ae-pop__card');
  var body = doc.getElementById('ae-detail-body');
  var content = doc.getElementById('ae-detail-content');
  var titleEl = doc.getElementById('ae-detail-title');
  var eyebrowEl = doc.getElementById('ae-detail-eyebrow');
  var contactPop = doc.getElementById('ae-pop');
  var isOpen = false;
  var opener = null;
  var closeTimer = 0;
  var inerted = [];
  var pointerDownOnScrim = false;

  function calm() {
    return html.classList.contains('ae-calm') ||
      (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  }

  function contactIsOpen() {
    return !!contactPop && contactPop.classList.contains('is-open');
  }

  /* ---------- helpers ---------- */
  function focusables() {
    return Array.prototype.filter.call(
      card.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]'),
      function (el) {
        if (el.getAttribute('tabindex') === '-1' && el !== card) return false;
        if (el === card) return false;
        return el.offsetWidth || el.offsetHeight || el.getClientRects().length;
      }
    );
  }

  function stripIds(node) {
    if (node.nodeType !== 1) return;
    node.removeAttribute('id');
    Array.prototype.forEach.call(node.querySelectorAll('[id]'), function (el) { el.removeAttribute('id'); });
  }

  /* ---------- open / close ---------- */
  function setInert(on) {
    if (on) {
      inerted = [];
      Array.prototype.forEach.call(doc.body.children, function (el) {
        if (el === pop || el.tagName === 'SCRIPT' || el.hasAttribute('inert')) return;
        el.setAttribute('inert', '');
        inerted.push(el);
      });
    } else {
      inerted.forEach(function (el) { el.removeAttribute('inert'); });
      inerted = [];
    }
  }

  function open(trigger) {
    clearTimeout(closeTimer);
    var slug = (trigger.getAttribute('data-ae-detail-target') || '').trim();
    var source = slug ? doc.getElementById('ae-detail-' + slug) : null;
    if (!source) return;
    if (isOpen) return;

    // Fresh clone every time, so a previous open leaves nothing behind.
    content.innerHTML = '';
    var clone = source.cloneNode(true);
    stripIds(clone);
    while (clone.firstChild) content.appendChild(clone.firstChild);

    var title = (trigger.getAttribute('data-ae-detail-title') || '').trim();
    var eyebrow = (trigger.getAttribute('data-ae-detail-eyebrow') || '').trim();
    titleEl.textContent = title;
    eyebrowEl.textContent = eyebrow;
    eyebrowEl.style.display = eyebrow ? '' : 'none';

    opener = trigger;
    isOpen = true;

    var gap = window.innerWidth - html.clientWidth;
    if (gap > 0) html.style.paddingRight = gap + 'px';
    html.classList.add('ae-pop-lock');
    pop.hidden = false;
    body.scrollTop = 0;
    void pop.offsetWidth; // start the entrance from its hidden state
    pop.classList.add('is-open');
    setInert(true);

    setTimeout(function () { card.focus({ preventScroll: true }); }, calm() ? 0 : 60);
  }

  function close() {
    if (!isOpen) return;
    isOpen = false;
    pop.classList.remove('is-open');
    setInert(false);
    // Keep the blur while the contact popup is open (it opened from inside here).
    if (!contactIsOpen()) {
      html.classList.remove('ae-pop-lock');
      html.style.paddingRight = '';
    }
    card.style.transform = '';
    card.style.transition = '';
    if (opener && opener.isConnected && !contactIsOpen()) {
      try { opener.focus({ preventScroll: true }); } catch (e) {}
    }
    closeTimer = setTimeout(function () {
      if (!isOpen) {
        pop.hidden = true;
        content.innerHTML = '';
      }
    }, calm() ? 0 : 260);
  }

  /* ---------- triggers (capture phase) ---------- */
  doc.addEventListener('click', function (e) {
    if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    var path = e.composedPath ? e.composedPath() : [];
    var trigger = null;
    var contactTrigger = null;
    for (var i = 0; i < path.length; i++) {
      var n = path[i];
      if (!n || n.nodeType !== 1 || !n.hasAttribute) continue;
      if (!trigger && n.hasAttribute('data-ae-detail')) trigger = n;
      if (!contactTrigger && n.hasAttribute('data-ae-popup')) contactTrigger = n;
    }
    // A contact link inside the open detail popup hands over to the contact popup.
    // This runs before the defaultPrevented check: ae-popup.js (registered first)
    // already preventDefaulted the click by the time this handler runs.
    if (contactTrigger && isOpen) { close(); return; }
    if (e.defaultPrevented) return;
    if (!trigger) return;
    e.preventDefault();
    open(trigger);
  }, true);

  /* ---------- close: button, outside tap, Escape ---------- */
  pop.addEventListener('pointerdown', function (e) { pointerDownOnScrim = (e.target === pop); });
  pop.addEventListener('click', function (e) {
    if (e.target === pop && pointerDownOnScrim) { close(); return; }
    if (e.target.closest && e.target.closest('[data-ae-detail-close]')) close();
  });

  doc.addEventListener('keydown', function (e) {
    if (!isOpen) return;
    if (e.key === 'Escape') { e.preventDefault(); close(); return; }
    if (e.key !== 'Tab') return;
    var f = focusables();
    if (!f.length) { e.preventDefault(); card.focus(); return; }
    var first = f[0], last = f[f.length - 1], active = doc.activeElement;
    if (e.shiftKey && (active === first || active === card || !card.contains(active))) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && (active === last || !card.contains(active))) { e.preventDefault(); first.focus(); }
  });
  // Belt and braces: if focus ever lands outside the card, pull it back.
  doc.addEventListener('focusin', function (e) {
    if (isOpen && !pop.contains(e.target) && !contactIsOpen()) { (focusables()[0] || card).focus({ preventScroll: true }); }
  });

  /* ---------- phone sheet: drag the grabber down to dismiss ---------- */
  (function () {
    var grab = card.querySelector('.ae-pop__grabber');
    if (!grab || !window.PointerEvent) return;
    var startY = 0, dy = 0, dragging = false;
    grab.addEventListener('pointerdown', function (e) {
      dragging = true; startY = e.clientY; dy = 0;
      try { grab.setPointerCapture(e.pointerId); } catch (x) {}
      card.style.transition = 'none';
    });
    grab.addEventListener('pointermove', function (e) {
      if (!dragging) return;
      dy = Math.max(0, e.clientY - startY);
      card.style.transform = 'translateY(' + dy + 'px)';
    });
    function end() {
      if (!dragging) return;
      dragging = false;
      card.style.transition = '';
      if (dy > 90) { close(); }
      else { card.style.transform = ''; }
      dy = 0;
    }
    grab.addEventListener('pointerup', end);
    grab.addEventListener('pointercancel', end);
  })();
})();
