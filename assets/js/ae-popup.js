/**
 * AE Software Solutions: glass card contact popup
 *
 * One reusable popup (markup in popup-contact.php, styles in ae-v5.css under
 * "Glass card popup"). Any link or button with data-ae-popup opens it instead of
 * going to the contact page. Optional attributes on the trigger:
 *   data-ae-service="website,online-presence"  service values to tick
 *   data-ae-plan="Starter Site"                plan name shown on the card and sent with the lead
 *
 * Behaviour: closes on the close button, Escape, a tap outside the card, and a
 * downward drag on the phone sheet grabber. Keyboard focus is trapped inside while
 * it is open (the rest of the page is made inert) and returns to the opener. Click
 * with a modifier key (new tab) or without JavaScript still follows the normal
 * link to /contact/. The form posts to the same lead endpoint as page-contact.php
 * and ends on the same "Message sent." state.
 *
 * Reduced motion: opens and closes at once, no animation.
 */
(function () {
  'use strict';

  var doc = document;
  var html = doc.documentElement;
  var pop = doc.getElementById('ae-pop');
  if (!pop) return; // Contact page (no popup markup) or markup missing: links just navigate.

  var card = pop.querySelector('.ae-pop__card');
  var body = doc.getElementById('ae-pop-body');
  var initialHTML = body.innerHTML;
  var ENDPOINT = 'https://admin.aesoftwaresolutions.com/api/business/webhook/inbound-lead';
  var isOpen = false;
  var opener = null;
  var busy = false;
  var closeTimer = 0;
  var inerted = [];
  var pointerDownOnScrim = false;

  function calm() {
    return html.classList.contains('ae-calm') ||
      (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  }

  /* ---------- helpers ---------- */
  function $(id) { return doc.getElementById(id); }
  function form() { return $('ae-pop-form'); }

  function focusables() {
    return Array.prototype.filter.call(
      card.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]'),
      function (el) {
        if (el.getAttribute('tabindex') === '-1' && el !== card) return false; // honeypot, headings
        if (el === card) return false;
        return el.offsetWidth || el.offsetHeight || el.getClientRects().length;
      }
    );
  }

  function labels(name) {
    return Array.prototype.map.call(form().querySelectorAll('input[name="' + name + '"]:checked'), function (i) { return i.getAttribute('data-label'); });
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

  function applyTrigger(trigger) {
    var plan = (trigger.getAttribute('data-ae-plan') || '').trim();
    var services = (trigger.getAttribute('data-ae-service') || '').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
    var eyebrow = $('ae-pop-eyebrow');
    eyebrow.textContent = plan || 'Get in touch';
    pop.setAttribute('data-plan', plan);
    if (services.length) {
      Array.prototype.forEach.call(form().querySelectorAll('input[name="service"]'), function (i) {
        i.checked = services.indexOf(i.value) !== -1;
      });
    }
  }

  function open(trigger) {
    clearTimeout(closeTimer);
    if (isOpen) return;
    // A finished or errored form from last time starts fresh.
    if (!form() || pop.getAttribute('data-done') === '1') {
      body.innerHTML = initialHTML;
      pop.removeAttribute('data-done');
      busy = false;
    }
    applyTrigger(trigger);
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

    var coarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
    var first = $('pop-name');
    setTimeout(function () {
      // On a phone, skip auto focus on the field so the keyboard does not cover the sheet.
      (coarse || !first ? card : first).focus({ preventScroll: true });
    }, calm() ? 0 : 60);
  }

  function close() {
    if (!isOpen) return;
    isOpen = false;
    pop.classList.remove('is-open');
    setInert(false);
    html.classList.remove('ae-pop-lock');
    html.style.paddingRight = '';
    card.style.transform = '';
    card.style.transition = '';
    if (opener && opener.isConnected) {
      try { opener.focus({ preventScroll: true }); } catch (e) {}
    }
    closeTimer = setTimeout(function () { if (!isOpen) pop.hidden = true; }, calm() ? 0 : 260);
  }

  /* ---------- triggers (capture phase: runs before the page wipe's link handler) ---------- */
  doc.addEventListener('click', function (e) {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    var path = e.composedPath ? e.composedPath() : [];
    var trigger = null;
    for (var i = 0; i < path.length; i++) {
      var n = path[i];
      if (n && n.nodeType === 1 && n.hasAttribute && n.hasAttribute('data-ae-popup')) { trigger = n; break; }
    }
    if (!trigger) return;
    e.preventDefault();
    open(trigger);
  }, true);

  /* ---------- close: button, outside tap, Escape ---------- */
  pop.addEventListener('pointerdown', function (e) { pointerDownOnScrim = (e.target === pop); });
  pop.addEventListener('click', function (e) {
    if (e.target === pop && pointerDownOnScrim) { close(); return; }
    if (e.target.closest && e.target.closest('[data-ae-pop-close]')) close();
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
    if (isOpen && !pop.contains(e.target)) { (focusables()[0] || card).focus({ preventScroll: true }); }
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

  /* ---------- the form: same validation, endpoint and success state as the Contact page ---------- */
  function clearErrors(f, statusEl) {
    f.querySelectorAll('[data-invalid]').forEach(function (el) { el.removeAttribute('data-invalid'); });
    f.querySelectorAll('[aria-invalid]').forEach(function (el) { el.removeAttribute('aria-invalid'); });
    f.querySelectorAll('.form-field__error').forEach(function (el) { el.parentNode.removeChild(el); });
    statusEl.hidden = true;
    statusEl.removeAttribute('data-state');
  }
  function flag(holder, input, message) {
    holder.setAttribute('data-invalid', '');
    if (input) input.setAttribute('aria-invalid', 'true');
    var p = doc.createElement('p');
    p.className = 'form-field__error';
    p.setAttribute('role', 'alert');
    p.textContent = message;
    holder.appendChild(p);
  }

  pop.addEventListener('submit', function (e) {
    var f = e.target;
    if (!f || f.id !== 'ae-pop-form') return;
    e.preventDefault();
    if (busy) return;
    if ($('pop-hp').value) return; // bot
    var statusEl = $('pop-status');
    var submitBtn = $('pop-submit');
    clearErrors(f, statusEl);

    var data = new FormData(f);
    var name = (data.get('name') || '').toString().trim();
    var email = (data.get('email') || '').toString().trim();
    var message = (data.get('message') || '').toString().trim();
    var services = labels('service');
    var first = null;

    if (!name) { var n = $('pop-name'); flag(n.parentNode, n, 'Please enter your name.'); first = first || n; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { var m = $('pop-email'); flag(m.parentNode, m, 'Please enter a valid email address.'); first = first || m; }
    if (!services.length) { var s = $('pop-services'); flag(s, null, 'Pick at least one.'); first = first || s.querySelector('input'); }
    if (!message) { var t = $('pop-message'); flag(t.parentNode, t, 'Please add a few words about the project.'); first = first || t; }
    if (first) { first.focus(); return; }

    var plan = pop.getAttribute('data-plan') || '';
    var budget = labels('budget')[0] || 'not given';
    var timeline = labels('timeline')[0] || 'not given';
    var notes = (plan ? 'Plan: ' + plan + ' | ' : '') +
                'Services: ' + services.join(', ') +
                ' | Budget: ' + budget +
                ' | Timeline: ' + timeline +
                '\n\n' + message;
    var idleLabel = 'Send message';
    busy = true;
    submitBtn.disabled = true;
    submitBtn.setAttribute('aria-busy', 'true');
    submitBtn.textContent = 'Sending';

    fetch(ENDPOINT, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name,
        email: email,
        company: data.get('company') || null,
        source: 'website_contact_form',
        notes: notes
      })
    }).then(function (r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      // Same confirmation as the Contact page, plus a way out of the popup.
      pop.setAttribute('data-done', '1');
      body.innerHTML = '<div class="contact-done" role="status"><h2 class="contact-done__title" tabindex="-1" id="ae-pop-done">Message sent.</h2><p>We read every message ourselves. You will hear from us within 24 hours.</p><button type="button" class="v5-button ae-pop__done-btn" data-ae-pop-close>Close</button></div>';
      var h = $('ae-pop-done');
      if (h) h.focus({ preventScroll: true });
      body.scrollTop = 0;
    }).catch(function () {
      busy = false;
      submitBtn.disabled = false;
      submitBtn.removeAttribute('aria-busy');
      submitBtn.textContent = idleLabel;
      statusEl.hidden = false;
      statusEl.setAttribute('data-state', 'error');
      statusEl.textContent = 'Your message did not go through. Check your connection and try again, or email us at ';
      var mail = doc.createElement('a');
      mail.href = 'mailto:anthony@aesoftwaresolutions.com';
      mail.textContent = 'anthony@aesoftwaresolutions.com';
      statusEl.appendChild(mail);
      statusEl.appendChild(doc.createTextNode('.'));
    });
  });
})();
