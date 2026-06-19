<?php
/**
 * Template Name: Website Creation
 * Website design & build service — portfolio to full CRM platforms, $1,500–$13,000.
 *
 * @package AE_Softwares
 */
add_action( 'wp_head', function () { ?>
<style>
/* ── Website Creation page ────────────────────────────────────────────────── */

/* HERO */
.web-hero { text-align: center; }

/* TIER CARDS */
.web-tiers-section {
    padding: clamp(64px, 7vw, 112px) clamp(1.5rem, 5vw, 4rem);
    max-width: var(--max-width);
    margin: 0 auto;
}
.web-tiers-section__header {
    max-width: 640px;
    margin: 0 auto 3.5rem;
    text-align: center;
}
.web-tiers-section__header .eyebrow {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 0.75rem;
}
.web-tiers-section__header h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 400;
    line-height: 1.18;
    margin: 0 0 1rem;
    color: var(--text);
}
.web-tiers-section__header p {
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.7;
    margin: 0;
}

/* Tier grid */
.web-tier-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 960px) { .web-tier-grid { grid-template-columns: 1fr; max-width: 560px; margin: 0 auto; } }

.web-tier {
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2.25rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 0;
    transition: border-color 0.2s ease, transform 0.2s ease;
    position: relative;
}
.web-tier:hover { border-color: var(--accent); transform: translateY(-2px); }
.web-tier--featured {
    border-color: var(--accent);
    background: linear-gradient(160deg, rgba(201,169,110,0.06) 0%, var(--bg-surface) 60%);
}
.web-tier__badge {
    position: absolute;
    top: -13px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent);
    color: var(--bg);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.3rem 1rem;
    border-radius: 100px;
    white-space: nowrap;
}

.web-tier__num {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 0.6rem;
}
.web-tier__name {
    font-family: var(--font-editorial);
    font-size: 1.55rem;
    font-weight: 400;
    color: var(--text);
    margin: 0 0 0.5rem;
    line-height: 1.2;
}
.web-tier__tagline {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0 0 1.75rem;
    line-height: 1.6;
}
.web-tier__price {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
    margin-bottom: 1.75rem;
    padding-bottom: 1.75rem;
    border-bottom: 1px solid var(--border);
}
.web-tier__price-from { font-size: 0.8rem; color: var(--text-muted); }
.web-tier__price-num {
    font-size: 2.4rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
}
.web-tier__price-range { font-size: 0.85rem; color: var(--text-muted); }

.web-tier__includes-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 0.85rem;
}
.web-tier__list {
    list-style: none;
    margin: 0 0 2rem;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
    flex: 1;
}
.web-tier__list li {
    font-size: 0.9rem;
    color: var(--text-muted);
    padding-left: 1.4rem;
    position: relative;
    line-height: 1.5;
}
.web-tier__list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--accent);
    font-weight: 700;
    font-size: 0.8rem;
}
.web-tier__timeline {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.web-tier__timeline::before { content: '⏱'; font-size: 0.85rem; }
.web-tier__cta { display: block; text-align: center; }

/* PROCESS */
.web-process-section {
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: clamp(64px, 7vw, 112px) clamp(1.5rem, 5vw, 4rem);
}
.web-process-inner {
    max-width: var(--max-width);
    margin: 0 auto;
}
.web-process-header { margin-bottom: 3.5rem; }
.web-process-header .eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    display: block;
    margin-bottom: 0.75rem;
}
.web-process-header h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 400;
    color: var(--text);
    margin: 0 0 0.75rem;
    line-height: 1.18;
}
.web-process-header p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; max-width: 540px; margin: 0; }

.web-process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 2rem;
}
.web-process-step { display: flex; flex-direction: column; gap: 0.75rem; }
.web-process-step__num {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--accent);
}
.web-process-step__title {
    font-family: var(--font-editorial);
    font-size: 1.25rem;
    font-weight: 400;
    color: var(--text);
    margin: 0;
    line-height: 1.2;
}
.web-process-step__desc { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; margin: 0; }

/* DIFFERENTIATORS */
.web-why-section {
    padding: clamp(64px, 7vw, 112px) clamp(1.5rem, 5vw, 4rem);
    max-width: var(--max-width);
    margin: 0 auto;
}
.web-why-header { margin-bottom: 3rem; }
.web-why-header .eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    display: block;
    margin-bottom: 0.75rem;
}
.web-why-header h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 400;
    color: var(--text);
    margin: 0;
    line-height: 1.18;
}
.web-why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.web-why-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.75rem;
    transition: border-color 0.2s;
}
.web-why-card:hover { border-color: var(--accent); }
.web-why-card__icon {
    width: 40px; height: 40px;
    background: rgba(201,169,110,0.1);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--accent);
    font-size: 1.1rem;
    margin-bottom: 1.1rem;
}
.web-why-card h3 {
    font-family: var(--font-editorial);
    font-size: 1.2rem;
    font-weight: 400;
    color: var(--text);
    margin: 0 0 0.6rem;
}
.web-why-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; margin: 0; }

/* INDUSTRIES STRIP */
.web-industries-strip {
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: clamp(48px, 5vw, 80px) clamp(1.5rem, 5vw, 4rem);
}
.web-industries-strip-inner { max-width: var(--max-width); margin: 0 auto; }
.web-industries-strip h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 400;
    color: var(--text);
    margin: 0 0 2.5rem;
    line-height: 1.2;
}
.web-industries-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.web-industry-chip {
    padding: 1rem 1.25rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.4;
}
.web-industry-chip strong { display: block; color: var(--text); font-size: 0.95rem; margin-bottom: 0.2rem; }

/* CTA STRIP */
.web-cta-strip {
    padding: clamp(64px, 7vw, 112px) clamp(1.5rem, 5vw, 4rem);
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}
.web-cta-strip h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 400;
    color: var(--text);
    line-height: 1.15;
    margin: 0 0 1rem;
}
.web-cta-strip h2 em { color: var(--accent); font-style: normal; }
.web-cta-strip p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin: 0 0 2.25rem; max-width: 520px; margin-left: auto; margin-right: auto; }
.web-cta-strip .btn-row { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
</style>
<?php } );
get_header(); ?>

<!-- HERO -->
<div class="page-hero web-hero">
    <div class="page-hero__eyebrow">Website Creation</div>
    <h1 class="page-hero__headline">A website that<br><em>works harder</em><br>than a business card.</h1>
    <p class="page-hero__subtitle" data-reveal-text>Every site we build has one job: convert a visitor into a lead, a client, or a customer. Portfolio to full CRM platform — three tiers, one team.</p>
</div>

<!-- TIER CARDS -->
<section class="web-tiers-section">
    <div class="web-tiers-section__header js-fade">
        <span class="eyebrow">Choose your tier</span>
        <h2>Built for where<br>your business is now</h2>
        <p>We match the build to your actual needs — no bloated platforms, no templates you can't edit. Every project is designed, built, and handed off with full ownership to you.</p>
    </div>

    <div class="web-tier-grid">

        <!-- TIER 1: Portfolio / Online Presence -->
        <div class="web-tier js-fade" style="--delay:0s">
            <div class="web-tier__num">Tier 01</div>
            <h2 class="web-tier__name">Online Presence</h2>
            <p class="web-tier__tagline">Your professional home on the internet — built to impress, fast to load, and easy to update.</p>
            <div class="web-tier__price">
                <span class="web-tier__price-from">from</span>
                <span class="web-tier__price-num">$1,500</span>
                <span class="web-tier__price-range">– $3,500</span>
            </div>
            <div class="web-tier__includes-label">What's included</div>
            <ul class="web-tier__list">
                <li>5–8 page custom-designed site</li>
                <li>Mobile-responsive on all devices</li>
                <li>Contact form with email delivery</li>
                <li>Google Analytics setup</li>
                <li>Basic SEO — titles, meta, sitemap</li>
                <li>Portfolio or services showcase</li>
                <li>Social profile links</li>
                <li>30 days post-launch support</li>
            </ul>
            <div class="web-tier__timeline">Delivered in 5–10 business days</div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-presence" class="btn btn--ghost web-tier__cta">Get a Quote</a>
        </div>

        <!-- TIER 2: Lead Funnel -->
        <div class="web-tier web-tier--featured js-fade" style="--delay:0.1s">
            <span class="web-tier__badge">Most Popular</span>
            <div class="web-tier__num">Tier 02</div>
            <h2 class="web-tier__name">Business Lead Funnel</h2>
            <p class="web-tier__tagline">A conversion-engineered site that captures, qualifies, and routes leads automatically — so you wake up to booked appointments.</p>
            <div class="web-tier__price">
                <span class="web-tier__price-from">from</span>
                <span class="web-tier__price-num">$4,500</span>
                <span class="web-tier__price-range">– $7,500</span>
            </div>
            <div class="web-tier__includes-label">Everything in Tier 01, plus</div>
            <ul class="web-tier__list">
                <li>Conversion-focused copywriting</li>
                <li>Lead capture forms with CRM routing</li>
                <li>Automated email/SMS follow-up sequence</li>
                <li>Appointment booking integration</li>
                <li>Chat widget or AI intake flow</li>
                <li>Heatmap &amp; session recording setup</li>
                <li>A/B-ready landing page structure</li>
                <li>60 days post-launch support</li>
            </ul>
            <div class="web-tier__timeline">Delivered in 10–18 business days</div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-funnel" class="btn btn--primary web-tier__cta">Get a Quote</a>
        </div>

        <!-- TIER 3: CRM Platform -->
        <div class="web-tier js-fade" style="--delay:0.2s">
            <div class="web-tier__num">Tier 03</div>
            <h2 class="web-tier__name">Digital Operations Platform</h2>
            <p class="web-tier__tagline">A full-stack web presence with a custom CRM, AI automation, and everything connected — built for operators who are done duct-taping tools together.</p>
            <div class="web-tier__price">
                <span class="web-tier__price-from">from</span>
                <span class="web-tier__price-num">$8,000</span>
                <span class="web-tier__price-range">– $13,000</span>
            </div>
            <div class="web-tier__includes-label">Everything in Tier 02, plus</div>
            <ul class="web-tier__list">
                <li>Custom CRM portal — contacts, pipeline, deals</li>
                <li>AI agent for follow-ups, drafts, scheduling</li>
                <li>Client-facing portal or intake system</li>
                <li>Workflow automation (n8n or Zapier)</li>
                <li>Payment or invoice integration</li>
                <li>Role-based access for your team</li>
                <li>Custom reporting dashboard</li>
                <li>90 days post-launch support + training</li>
            </ul>
            <div class="web-tier__timeline">Delivered in 3–6 weeks</div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-platform" class="btn btn--ghost web-tier__cta">Get a Quote</a>
        </div>

    </div>
</section>

<!-- PROCESS -->
<section class="web-process-section">
    <div class="web-process-inner">
        <div class="web-process-header js-fade">
            <span class="eyebrow">How it works</span>
            <h2>From brief to live<br>in a straight line.</h2>
            <p>No bloated discovery phases or endless revision loops. We move fast, communicate clearly, and ship sites that are ready to work on day one.</p>
        </div>
        <div class="web-process-steps">
            <div class="web-process-step js-fade" style="--delay:0s">
                <div class="web-process-step__num">01 — Discovery</div>
                <h3 class="web-process-step__title">One focused call</h3>
                <p class="web-process-step__desc">We learn your business, your customers, and your goal for the site. You answer questions; we take notes. No homework required.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.1s">
                <div class="web-process-step__num">02 — Design</div>
                <h3 class="web-process-step__title">High-fidelity mockup first</h3>
                <p class="web-process-step__desc">You see the full design before a single line of code is written. One round of revisions included. Approval locks the build.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.2s">
                <div class="web-process-step__num">03 — Build</div>
                <h3 class="web-process-step__title">Fast, clean, production-ready</h3>
                <p class="web-process-step__desc">We build to your timeline. You see a live staging link before handoff. Nothing ships that hasn't been tested on mobile and desktop.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.3s">
                <div class="web-process-step__num">04 — Launch</div>
                <h3 class="web-process-step__title">Full handoff, full ownership</h3>
                <p class="web-process-step__desc">We deploy, configure DNS, connect your analytics, and walk you through the site. You own everything — code, domain, credentials.</p>
            </div>
        </div>
    </div>
</section>

<!-- WHY US -->
<section class="web-why-section">
    <div class="web-why-header js-fade">
        <span class="eyebrow">Why AE</span>
        <h2>We build sites like<br>operators, not designers.</h2>
    </div>
    <div class="web-why-grid">
        <div class="web-why-card js-fade" style="--delay:0s">
            <div class="web-why-card__icon">⚡</div>
            <h3>Conversion-first architecture</h3>
            <p>Every layout decision, headline, and call-to-action is informed by what gets visitors to take the next step — not what looks good in a portfolio screenshot.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.1s">
            <div class="web-why-card__icon">🔗</div>
            <h3>Built to integrate</h3>
            <p>We wire your site into your actual workflow — CRM, calendar, email, payment processor. Not a disconnected brochure that creates more manual work.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.2s">
            <div class="web-why-card__icon">🤖</div>
            <h3>AI where it makes sense</h3>
            <p>We add AI intake, follow-up sequences, and chat agents where they save you real time — not as a buzzword feature that complicates the UX.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.3s">
            <div class="web-why-card__icon">🏠</div>
            <h3>You own it completely</h3>
            <p>No proprietary builders that lock you in. You get the code, the hosting setup, and the credentials. If you ever want to move, you can — no ransom.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.4s">
            <div class="web-why-card__icon">📊</div>
            <h3>Measurable from day one</h3>
            <p>Analytics, lead tracking, and conversion events are configured at launch — not an afterthought. You'll know exactly what's working within the first 30 days.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.5s">
            <div class="web-why-card__icon">🛡</div>
            <h3>One team, start to finish</h3>
            <p>You work with one person who understands your business, not a rotating cast of designers, developers, and project managers who've never met each other.</p>
        </div>
    </div>
</section>

<!-- INDUSTRIES -->
<section class="web-industries-strip">
    <div class="web-industries-strip-inner">
        <h2 class="js-fade">Industries we've built for</h2>
        <div class="web-industries-grid">
            <div class="web-industry-chip js-fade" style="--delay:0s">
                <strong>Real Estate Agents</strong>
                IDX search, lead capture, property showcase
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.05s">
                <strong>Home Services</strong>
                Quote forms, booking, service area pages
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.1s">
                <strong>Mortgage Brokers</strong>
                Rate tools, application intake, compliance-ready copy
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.15s">
                <strong>Insurance Agents</strong>
                Quote funnels, trust signals, appointment booking
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.2s">
                <strong>Law Firms</strong>
                Practice area pages, case intake, attorney profiles
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.25s">
                <strong>Healthcare &amp; Wellness</strong>
                Provider bios, scheduling, HIPAA-aware forms
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.3s">
                <strong>Consultants &amp; Coaches</strong>
                Authority building, speaking, program enrollment
            </div>
            <div class="web-industry-chip js-fade" style="--delay:0.35s">
                <strong>Small Business</strong>
                Local SEO, reviews integration, Google Business sync
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="web-cta-strip">
    <h2 class="js-fade">Ready to build something<br>that <em>actually converts</em>?</h2>
    <p class="js-fade" style="--delay:0.1s">Tell us what you need and we'll put together a scope and quote within 24 hours. No pressure, no bait-and-switch pricing.</p>
    <div class="btn-row js-fade" style="--delay:0.2s">
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website" class="btn btn--primary">Get a Quote</a>
        <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="btn btn--ghost">See Our Work</a>
    </div>
</section>

<?php get_footer(); ?>
