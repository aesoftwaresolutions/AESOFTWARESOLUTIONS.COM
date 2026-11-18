/**
 * <ae-cta>: closing call to action used on inner pages (v6 redesign).
 * Dark band, white text, a hairline across the top, one button and the email
 * link below it. The block fades up once when it scrolls into view. Reduced
 * motion (and no IntersectionObserver) shows it immediately.
 */
(function () {
  'use strict';
  if (!window.customElements || customElements.get('ae-cta')) return;

  var tpl = document.createElement('template');
  tpl.innerHTML = '' +
    '<style>' +
    ':host{display:block;--ink:#FFFFFF;--paper:#0B0B0D;--muted:rgba(255,255,255,.78);--gold:#C9A96E;--rule:rgba(255,255,255,.16);--ease:cubic-bezier(.18,.66,.18,1)}' +
    '.cta{box-sizing:border-box;max-width:1200px;margin:0 auto;padding:96px clamp(20px,4vw,40px);border-top:1px solid var(--rule);font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","SF Pro Display",system-ui,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;color:var(--ink);opacity:0;transform:translateY(12px);transition:opacity .4s var(--ease),transform .4s var(--ease)}' +
    '.cta.in{opacity:1;transform:none}' +
    '.label{font-size:.875rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin:0 0 16px}' +
    'h2{font-size:clamp(2rem,4.6vw,3.5rem);font-weight:600;line-height:1.1;letter-spacing:-.025em;margin:0 0 32px;max-width:18ch}' +
    '.actions{display:flex;flex-wrap:wrap;align-items:center;gap:16px 24px}' +
    'a.btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 20px;border-radius:6px;background:var(--ink) linear-gradient(var(--gold),var(--gold)) no-repeat 0 100%/0 2px;color:var(--paper);border:1px solid var(--ink);font-weight:600;font-size:.95rem;text-decoration:none;transition:background-size .25s var(--ease),transform .2s var(--ease)}' +
    'a.btn:hover,a.btn:focus-visible{background-size:100% 2px;transform:translateY(-1px)}' +
    'a.btn:active{transform:translateY(1px)}' +
    'a:focus-visible{outline:2px solid var(--ink);outline-offset:3px;box-shadow:0 0 0 2px var(--gold)}' +
    '.note{font-size:.9375rem;color:var(--muted)}' +
    '.mail{margin:20px 0 0;font-size:.9375rem;color:var(--muted)}' +
    '.mail a{color:var(--ink);font-weight:600;text-decoration:none;background:linear-gradient(currentColor,currentColor) no-repeat 0 100%/100% 1px;padding-bottom:1px}' +
    '@media (max-width:640px){.cta{padding-top:56px;padding-bottom:56px}}' +
    '@media (prefers-reduced-motion:reduce){.cta{opacity:1;transform:none;transition:none}a.btn{transition:none}}' +
    '</style>' +
    '<section class="cta" aria-labelledby="cta-title">' +
    '  <p class="label">Next step</p>' +
    '  <h2 id="cta-title">Tell us what slows you down. We&rsquo;ll map the fix.</h2>' +
    '  <div class="actions">' +
    '    <a class="btn" href="/contact/">Book a discovery call</a>' +
    '    <span class="note">Free 30 minute discovery call</span>' +
    '  </div>' +
    '  <p class="mail">Or write to <a href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a></p>' +
    '</section>';

  class AeCta extends HTMLElement {
    constructor() {
      super();
      this.attachShadow({ mode: 'open' }).appendChild(tpl.content.cloneNode(true));
    }
    connectedCallback() {
      var box = this.shadowRoot.querySelector('.cta');
      var calm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (calm || !('IntersectionObserver' in window)) { box.classList.add('in'); return; }
      var io = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) { box.classList.add('in'); io.disconnect(); }
      }, { rootMargin: '0px 0px -10% 0px' });
      io.observe(this);
      // Never leave the block hidden if the observer does not fire.
      setTimeout(function () { box.classList.add('in'); }, 4000);
    }
  }

  customElements.define('ae-cta', AeCta);
})();
