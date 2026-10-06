<?php
/**
 * Front Page — AE Software Solutions (Visual Refresh 2026-10)
 * Self-contained: enqueues its own refresh layer from /assets/refresh/
 */
get_header();
$ae_dist = get_template_directory_uri() . '/assets/refresh';
?>
<link rel="stylesheet" href="<?php echo esc_url( $ae_dist . '/refresh.css' ); ?>?v=1.0.0">
<link rel="stylesheet" href="<?php echo esc_url( $ae_dist . '/ae-motion.css' ); ?>?v=2.3.0">

<main id="primary" class="site-main ae-refresh">

  <!-- HERO -->
  <section class="ae-hero">
    <canvas id="ae-fx-canvas" aria-hidden="true"></canvas>
    <div class="ae-wrap ae-hero-grid">
      <div class="ae-hero-copy" data-reveal>
        <p class="ae-eyebrow">AE Software Solutions &middot; Chambersburg, PA &middot; Mid-Atlantic</p>
        <h1>AI-powered software, <span class="ae-grad">built around your business.</span></h1>
        <p class="ae-lede">Custom CRM platforms, AI agents, and workflow automation for businesses across Pennsylvania, Maryland, Virginia, and DC — designed, built, and supported by one studio.</p>
        <div class="ae-cta-row">
          <a class="ae-btn ae-btn-primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a>
          <a class="ae-btn ae-btn-ghost" href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>">Get a free assessment</a>
        </div>
        <ul class="ae-hero-points" data-reveal data-reveal-delay="150">
          <li>Full-stack in-house — no outsourcing</li>
          <li>AI agents &amp; RAG systems that actually ship</li>
          <li>Ongoing support with a direct line</li>
        </ul>
      </div>
      <div class="ae-hero-stage" aria-hidden="true">
        <div class="tilt-card tilt-a" data-tilt>
          <div class="card-label">CRM Pipeline</div>
          <div class="pipe-row"><span>New lead</span><b>12</b></div>
          <div class="pipe-row"><span>Qualified</span><b>7</b></div>
          <div class="pipe-row"><span>Proposal</span><b>3</b></div>
          <div class="pipe-bar"><i style="width:64%"></i></div>
        </div>
        <div class="tilt-card tilt-b" data-tilt>
          <div class="card-label">AI Agent</div>
          <div class="agent-line you">Draft the follow-up email</div>
          <div class="agent-line bot">Done — sent to 7 qualified leads.</div>
        </div>
        <div class="tilt-card tilt-c" data-tilt>
          <div class="card-label">Automation</div>
          <div class="flow"><span>Form</span><em>&rarr;</em><span>CRM</span><em>&rarr;</em><span>Email</span></div>
          <div class="flow-status">Running &middot; 214 tasks today</div>
        </div>
        <a class="tilt-card tilt-d aem-shot" data-tilt href="https://monarchmvc.com" target="_blank" rel="noopener" aria-label="Visit monarchmvc.com — live client platform">
          <img src="https://s0.wp.com/mshots/v1/https%3A%2F%2Fmonarchmvc.com?w=960" alt="Live preview of monarchmvc.com" loading="lazy">
          <div class="card-label">Monarch MVC &middot; live client platform <span aria-hidden="true">&#8599;</span></div>
        </a>
      </div>
    </div>
    <div class="ae-scroll-hint" aria-hidden="true"><span></span></div>
  </section>

  <!-- CAPABILITY TICKER -->
  <div class="aem-marquee" aria-hidden="true">
    <div class="aem-marquee__track">
      <span>Custom CRM platforms</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>AI agents &amp; RAG systems</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Workflow automation</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Websites that convert</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Mobile applications</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>SEO &amp; lead generation</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Full-stack, in-house</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Custom CRM platforms</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>AI agents &amp; RAG systems</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Workflow automation</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Websites that convert</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Mobile applications</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>SEO &amp; lead generation</span><span class="aem-sep" aria-hidden="true">&middot;</span>
      <span>Full-stack, in-house</span><span class="aem-sep" aria-hidden="true">&middot;</span>
    </div>
  </div>

  <!-- STATS STRIP -->
  <section class="ae-strip" data-reveal>
    <div class="ae-wrap ae-strip-grid">
      <div class="ae-stat"><b data-count="6">0</b><span>Core services</span></div>
      <div class="ae-stat"><b data-count="4">0</b><span>States served</span></div>
      <div class="ae-stat"><b data-count="100">0</b><span>% custom code</span></div>
      <div class="ae-stat"><b data-count="1">0</b><span>Dedicated studio</span></div>
    </div>
  </section>

  <!-- SERVICES -->
  <section class="ae-section" id="services">
    <div class="ae-wrap">
      <header class="ae-sec-head" data-reveal>
        <p class="ae-eyebrow">What we build</p>
        <h2>Software that pays for itself</h2>
        <p class="ae-sec-sub">Every engagement is scoped around a business outcome — fewer manual hours, faster follow-up, more closed deals.</p>
      </header>
      <div class="ae-card-grid">
        <a class="ae-card" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" data-reveal>
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 5h18M3 5v14h18V5M3 9h18M6 7h.01M8 7h.01"/></svg></div>
          <h3>Websites</h3><p>Conversion-focused sites engineered for speed, SEO, and easy editing.</p>
        </a>
        <a class="ae-card" href="<?php echo esc_url( home_url( '/software/' ) ); ?>" data-reveal data-reveal-delay="60">
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 6l-5 6 5 6M16 6l5 6-5 6M13 4l-2 16"/></svg></div>
          <h3>Proprietary software &amp; CRM</h3><p>Purpose-built platforms that replace spreadsheets and patchwork tools.</p>
        </a>
        <a class="ae-card" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" data-reveal data-reveal-delay="120">
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3a5 5 0 015 5c0 2-1 3-1 5H8c0-2-1-3-1-5a5 5 0 015-5zM9 17h6M10 21h4"/></svg></div>
          <h3>AI agents</h3><p>Agents that answer, draft, route, and follow up — inside your workflows.</p>
        </a>
        <a class="ae-card" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" data-reveal data-reveal-delay="180">
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 3h10v18H7zM11 18h2"/></svg></div>
          <h3>Mobile applications</h3><p>Native-feel mobile experiences backed by your existing systems.</p>
        </a>
        <a class="ae-card" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" data-reveal data-reveal-delay="240">
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 12h4l2-7 4 14 2-7h4"/></svg></div>
          <h3>Workflow automation</h3><p>Your tools, data, and people — connected, with the busywork removed.</p>
        </a>
        <a class="ae-card" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" data-reveal data-reveal-delay="300">
          <div class="ae-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 21V10l7-7 7 7v11M9 21v-6h4v6"/></svg></div>
          <h3>SEO &amp; lead generation</h3><p>Local search presence and pipelines that keep the phone ringing.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- PROCESS -->
  <section class="ae-section ae-alt" id="process">
    <div class="ae-wrap">
      <header class="ae-sec-head" data-reveal>
        <p class="ae-eyebrow">How it works</p>
        <h2>A process you can follow</h2>
      </header>
      <ol class="ae-steps">
        <li data-reveal><b>01</b><h3>Discover</h3><p>A working session to map your workflow, bottlenecks, and goals.</p></li>
        <li data-reveal data-reveal-delay="80"><b>02</b><h3>Design</h3><p>Architecture, screens, and a fixed scope you sign off on before code.</p></li>
        <li data-reveal data-reveal-delay="160"><b>03</b><h3>Build</h3><p>Weekly demos in a staging environment — you watch it come together.</p></li>
        <li data-reveal data-reveal-delay="240"><b>04</b><h3>Support</h3><p>Launch is the start: hosting, iteration, and a direct line to the builder.</p></li>
      </ol>
      <div class="ae-center" data-reveal><a class="ae-btn ae-btn-ghost" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">See the full process</a></div>
    </div>
  </section>

  <!-- WHY -->
  <section class="ae-section" id="why">
    <div class="ae-wrap ae-why-grid">
      <div data-reveal>
        <p class="ae-eyebrow">Why AE</p>
        <h2>One studio. Zero hand-offs.</h2>
        <p class="ae-sec-sub">The person who scopes your project is the person who builds it — and the person you call afterward. Based in Chambersburg, serving the Mid-Atlantic.</p>
        <a class="ae-btn ae-btn-primary" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">See recent work</a>
      </div>
      <ul class="ae-why-list">
        <li data-reveal><b>Local &amp; accountable</b><span>On-site sessions available across PA, MD, VA, and DC.</span></li>
        <li data-reveal data-reveal-delay="80"><b>AI-first, not AI-washed</b><span>Working agents and RAG systems — not slide-deck promises.</span></li>
        <li data-reveal data-reveal-delay="160"><b>Performance as a feature</b><span>Fast loads, clean Core Web Vitals, and SEO baked in from line one.</span></li>
        <li data-reveal data-reveal-delay="240"><b>You own everything</b><span>Your code, your data, your accounts. No hostage hosting.</span></li>
      </ul>
    </div>
  </section>

  <!-- CTA BAND -->
  <section class="ae-cta-band" data-reveal>
    <div class="ae-wrap ae-center">
      <h2>Not sure where to start?</h2>
      <p class="ae-sec-sub">Take the free assessment — five minutes, and you get a concrete plan, not a sales pitch.</p>
      <div class="ae-cta-row ae-center-row">
        <a class="ae-btn ae-btn-primary" href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>">Start the assessment</a>
        <a class="ae-btn ae-btn-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Just talk to us</a>
      </div>
    </div>
  </section>

</main>

<script defer src="<?php echo esc_url( $ae_dist . '/refresh.js' ); ?>?v=1.0.0"></script>
<script defer src="<?php echo esc_url( $ae_dist . '/ae-motion.js' ); ?>?v=2.3.0"></script>
<?php
get_footer();
