/**
 * <ae-cta> — closing call to action used on inner pages (v5 editorial).
 * Light paper band, headline rises line by line when it scrolls into view,
 * hairline draws across the top. Reduced motion shows it immediately.
 */
(function () {
  'use strict';
  if (!window.customElements || customElements.get('ae-cta')) return;

  var tpl = document.createElement('template');
  tpl.innerHTML = '' +
    '<style>' +
    ':host{display:block;--ink:#121212;--paper:#F7F7F5;--muted:#5E5E5A;--accent:#7A5F20;--rule:rgba(18,18,18,.12);--ease:cubic-bezier(.18,.66,.18,1)}' +
    '.cta{max-width:1400px;margin:0 auto;padding:72px clamp(20px,4vw,40px) 140px;position:relative;font-family:"Plus Jakarta Sans",-apple-system,BlinkMacSystemFont,sans-serif;color:var(--ink)}' +
    '.rule{position:absolute;top:0;left:clamp(20px,4vw,40px);right:clamp(20px,4vw,40px);height:1px;background:var(--rule);transform:scaleX(0);transform-origin:left;transition:transform 1.6s var(--ease)}' +
    '.label{font-family:"Space Grotesk",ui-monospace,monospace;font-size:.8rem;color:var(--muted);margin:0 0 20px}' +
    'h2{font-size:clamp(2.5rem,7vw,6.5rem);font-weight:600;letter-spacing:-.05em;line-height:.98;margin:0 0 40px;max-width:14ch}' +
    '.line{display:block;overflow:hidden;padding-bottom:.08em;margin-bottom:-.08em}' +
    '.line>span{display:block;transform:translateY(110%);transition:transform 1.1s var(--ease)}' +
    '.line:nth-child(2)>span{transition-delay:.09s}' +
    '.line:nth-child(3)>span{transition-delay:.18s}' +
    '.accent{color:var(--accent)}' +
    '.actions{display:flex;flex-wrap:wrap;align-items:center;gap:20px;opacity:0;transform:translateY(16px);transition:opacity 1s var(--ease) .3s,transform 1s var(--ease) .3s}' +
    'a.btn{display:inline-flex;align-items:center;border-radius:999px;background:var(--ink);color:var(--paper);border:1px solid var(--ink);padding:.9rem 1.6rem;font-weight:600;font-size:.95rem;text-decoration:none;transition:background-color .35s var(--ease),border-color .35s var(--ease)}' +
    'a.btn:hover{background:var(--accent);border-color:var(--accent)}' +
    'a.btn:focus-visible{outline:2px solid var(--ink);outline-offset:3px}' +
    '.note{font-family:"Space Grotesk",ui-monospace,monospace;font-size:.8rem;color:var(--muted)}' +
    '.in .rule{transform:scaleX(1)}' +
    '.in .line>span{transform:none}' +
    '.in .actions{opacity:1;transform:none}' +
    '@media (prefers-reduced-motion:reduce){.rule,.line>span,.actions{transition:none;transform:none;opacity:1}}' +
    '</style>' +
    '<section class="cta" aria-labelledby="cta-title">' +
    '  <span class="rule" aria-hidden="true"></span>' +
    '  <p class="label">[ Next step ]</p>' +
    '  <h2 id="cta-title"><span class="line"><span>Tell us what</span></span><span class="line"><span>slows you down.</span></span><span class="line"><span class="accent">We&rsquo;ll map the fix.</span></span></h2>' +
    '  <div class="actions">' +
    '    <a class="btn" href="/contact/">Book a discovery call</a>' +
    '    <span class="note">Free 15-minute call &middot; reply within one business day</span>' +
    '  </div>' +
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
    }
  }

  customElements.define('ae-cta', AeCta);
})();
