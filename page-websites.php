<?php
/**
 * Template Name: Website Creation
 * Website design and build service, from a simple site to a custom platform.
 *
 * @package AE_Softwares
 */
add_action( 'wp_head', function () { ?>
<style>
/* Website Creation page */

/* HERO */
.web-hero { text-align: center; }

/* TEMPLATE SCROLL */
.web-scroll-section {
    padding: clamp(48px, 5vw, 80px) 0;
    background: var(--bg-surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    overflow: hidden;
}
.web-scroll-section__header {
    text-align: center;
    padding: 0 clamp(1.5rem, 5vw, 4rem);
    margin-bottom: 2.5rem;
}
.web-scroll-section__header .eyebrow {
    display: block;
    font-size: 0.875rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 0.65rem;
}
.web-scroll-section__header h2 {
    font-family: var(--font-editorial);
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 400;
    color: var(--text);
    margin: 0;
    line-height: 1.2;
}
.web-scroll-track-wrap {
    position: relative;
}
/* Fade edges */
.web-scroll-track-wrap::before,
.web-scroll-track-wrap::after {
    content: '';
    position: absolute;
    top: 0; bottom: 0;
    width: 120px;
    z-index: 2;
    pointer-events: none;
}
.web-scroll-track-wrap::before {
    left: 0;
    background: linear-gradient(to right, var(--bg-surface), transparent);
}
.web-scroll-track-wrap::after {
    right: 0;
    background: linear-gradient(to left, var(--bg-surface), transparent);
}
.web-scroll-track {
    display: flex;
    gap: 1.5rem;
    width: max-content;
    animation: web-marquee 38s linear infinite;
    will-change: transform;
}
.web-scroll-track:hover { animation-play-state: paused; }
@keyframes web-marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.web-scroll-card {
    flex-shrink: 0;
    width: 320px;
    border-radius: 14px;
    border: 1px solid var(--border);
    overflow: hidden;
    background: var(--bg);
    transition: border-color 0.2s, transform 0.25s;
    cursor: default;
}
.web-scroll-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
}
.web-scroll-card__browser {
    background: var(--surface-subtle);
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid var(--border);
}
.web-scroll-card__dot {
    width: 8px; height: 8px;
    border-radius: 50%;
}
.web-scroll-card__dot--r { background: #ff5f57; }
.web-scroll-card__dot--y { background: #febc2e; }
.web-scroll-card__dot--g { background: #28c840; }
.web-scroll-card__url {
    flex: 1;
    background: rgb(var(--ink-rgb) / .06);
    border-radius: 4px;
    height: 16px;
    margin-left: 4px;
}
.web-scroll-card__preview {
    display: block;
    width: 100%;
    height: 220px;
    object-fit: cover;
    object-position: top center;
}
.web-scroll-card__label {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 4px 12px;
    border-top: 1px solid var(--border);
}
.web-scroll-card__label-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text);
}
.web-scroll-card__label-type {
    font-size: 0.7rem;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
}

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
    font-size: 0.875rem;
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
    align-items: stretch;
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
    background: linear-gradient(160deg, rgb(var(--gold-rgb) / .06) 0%, var(--bg-surface) 60%);
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
    gap: 0.3rem;
    margin-top: auto;
    padding-top: 1.25rem;
    border-top: 1px solid var(--border);
    margin-bottom: 1.25rem;
}
.web-tier__price-from { font-size: 0.72rem; color: var(--text-muted); }
.web-tier__price-num {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
}
.web-tier__price-range { font-size: 0.78rem; color: var(--text-muted); }

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
    font-size: 0.875rem;
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
    font-size: 0.875rem;
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
    background: rgb(var(--gold-rgb) / .1);
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
    <div class="page-hero__eyebrow">Website design</div>
    <h1 class="page-hero__headline">A website that<br><em>works harder</em><br>than a business card.</h1>
    <p class="page-hero__subtitle" data-reveal-text>Every site we build has one job: turn a visitor into a lead, a client, or a customer. From a simple site to a custom platform, three tiers, one person.</p>
</div>

<!-- TEMPLATE SCROLL -->
<section class="web-scroll-section">
    <div class="web-scroll-section__header js-fade">
        <span class="eyebrow">Example templates</span>
        <h2>Example designs<br>for your industry</h2>
        <p>These are example templates we made to show what a site for your industry can look like. They are not client sites.</p>
    </div>
    <div class="web-scroll-track-wrap">
        <div class="web-scroll-track">
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/real-estate-agent.jpg" alt="Real Estate Agent website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Real Estate Agent</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/home-services.jpg" alt="Home Services website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Home Services</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/mortgage-broker.jpg" alt="Mortgage Broker website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Mortgage Broker</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/law-firm.jpg" alt="Law Firm website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Law Firm</span>
                    <span class="web-scroll-card__label-type">Example: Intake Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/dental-practice.jpg" alt="Dental Practice website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Dental Practice</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/restaurant.jpg" alt="Restaurant website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Restaurant</span>
                    <span class="web-scroll-card__label-type">Example: Reservation Funnel</span>
                </div>
            </div>

            <!-- Duplicate set for seamless loop -->

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/real-estate-agent.jpg" alt="Real Estate Agent website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Real Estate Agent</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/home-services.jpg" alt="Home Services website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Home Services</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/mortgage-broker.jpg" alt="Mortgage Broker website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Mortgage Broker</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/law-firm.jpg" alt="Law Firm website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Law Firm</span>
                    <span class="web-scroll-card__label-type">Example: Intake Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/dental-practice.jpg" alt="Dental Practice website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Dental Practice</span>
                    <span class="web-scroll-card__label-type">Example: Lead Funnel</span>
                </div>
            </div>
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <img class="web-scroll-card__preview" src="<?php echo get_template_directory_uri(); ?>/assets/img/template-previews/restaurant.jpg" alt="Restaurant website template" loading="lazy">
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Restaurant</span>
                    <span class="web-scroll-card__label-type">Example: Reservation Funnel</span>
                </div>
            </div>

        </div><!-- .web-scroll-track -->
    </div><!-- .web-scroll-track-wrap -->
</section>

<!-- TIER CARDS -->
<section class="web-tiers-section">
    <div class="web-tiers-section__header js-fade">
        <span class="eyebrow">Choose your tier</span>
        <h2>Built for where<br>your business is now</h2>
        <p>We match the build to what you actually need. No bloated platforms, no templates you can't edit. Every project is designed, built, and handed off with full ownership to you.</p>
    </div>

    <div class="web-tier-grid">

        <!-- TIER 1: Portfolio / Online Presence -->
        <div class="web-tier js-fade" style="--delay:0s">
            <div class="web-tier__num">Tier 01</div>
            <h2 class="web-tier__name">Starter Site</h2>
            <p class="web-tier__tagline">Your professional home on the internet. Built to look good, fast to load, and easy to update.</p>
            <div class="web-tier__includes-label">What's included</div>
            <ul class="web-tier__list">
                <li>5 to 8 page custom designed site</li>
                <li>Mobile friendly on all devices</li>
                <li>Contact form with email delivery</li>
                <li>Google Business Profile set up &amp; verified</li>
                <li>Google Analytics setup</li>
                <li>Basic SEO: titles, meta, sitemap</li>
                <li>Portfolio or services showcase</li>
                <li>Social profile links</li>
            </ul>
            <div class="web-tier__price">
                <span class="web-tier__price-from">from</span>
                <span class="web-tier__price-num">$495</span>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-presence" class="btn btn--ghost web-tier__cta">Get a Quote</a>
        </div>

        <!-- TIER 2: Lead Funnel -->
        <div class="web-tier web-tier--featured js-fade" style="--delay:0.1s">
            <span class="web-tier__badge">Recommended</span>
            <div class="web-tier__num">Tier 02</div>
            <h2 class="web-tier__name">Lead Funnel Site</h2>
            <p class="web-tier__tagline">A site built to capture leads, qualify them, and send them to you automatically.</p>
            <div class="web-tier__includes-label">Everything in Tier 01, plus</div>
            <ul class="web-tier__list">
                <li>Copywriting focused on calls and bookings</li>
                <li>Lead capture forms with CRM routing</li>
                <li>Automated email and text follow up</li>
                <li>Appointment booking integration</li>
                <li>Chat widget or AI intake flow</li>
                <li>Heatmap &amp; session recording setup</li>
                <li>Landing page structure ready for testing</li>
            </ul>
            <div class="web-tier__price">
                <span class="web-tier__price-from">from</span>
                <span class="web-tier__price-num">$4,500</span>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-funnel" class="btn btn--primary web-tier__cta">Get a Quote</a>
        </div>

        <!-- TIER 3: CRM Platform -->
        <div class="web-tier js-fade" style="--delay:0.2s">
            <div class="web-tier__num">Tier 03</div>
            <h2 class="web-tier__name">Custom Platform</h2>
            <p class="web-tier__tagline">Client portals, custom dashboards and integrations with the software you already use. Scoped and priced after a call.</p>
            <div class="web-tier__includes-label">Everything in Tier 02, plus</div>
            <ul class="web-tier__list">
                <li>Connects to your existing job or booking software</li>
                <li>Client facing portal or intake system</li>
                <li>Workflow automation between your tools</li>
                <li>Payment or invoice integration</li>
                <li>Role based access for your team</li>
                <li>Custom reporting dashboard</li>
                <li>Support and training agreed in the scope</li>
            </ul>
            <div class="web-tier__timeline">Timeline set during scoping</div>
            <div class="web-tier__price">
                <span class="web-tier__price-num">Quoted</span>
                <span class="web-tier__price-range">after a scoping call</span>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website-platform" class="btn btn--ghost web-tier__cta">Start a conversation</a>
        </div>

    </div>
    <p class="web-tiers-note js-fade">Keep it working after launch with <a href="<?php echo esc_url( home_url( '/pricing/#online-presence' ) ); ?>">Online Presence Management</a>: Google profile, reviews and listings looked after for $199 a month. New business? The <a href="<?php echo esc_url( home_url( '/pricing/#launch' ) ); ?>">Launch Package</a> bundles a Starter Site, Google profile setup and three months of care for $595.</p>
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
                <div class="web-process-step__num">01 Discovery</div>
                <h3 class="web-process-step__title">One focused call</h3>
                <p class="web-process-step__desc">We learn your business, your customers, and your goal for the site. You answer questions and we take notes. No homework required.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.1s">
                <div class="web-process-step__num">02 Design</div>
                <h3 class="web-process-step__title">High fidelity mockup first</h3>
                <p class="web-process-step__desc">You see the full design before a single line of code is written. One round of revisions included. Approval locks the build.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.2s">
                <div class="web-process-step__num">03 Build</div>
                <h3 class="web-process-step__title">Fast, clean, ready to launch</h3>
                <p class="web-process-step__desc">We build to the timeline we agree on. You see a live staging link before handoff. Nothing ships that hasn't been tested on mobile and desktop.</p>
            </div>
            <div class="web-process-step js-fade" style="--delay:0.3s">
                <div class="web-process-step__num">04 Launch</div>
                <h3 class="web-process-step__title">Full handoff, full ownership</h3>
                <p class="web-process-step__desc">We deploy, configure DNS, connect your analytics, and walk you through the site. You own everything: code, domain, credentials.</p>
            </div>
        </div>
    </div>
</section>

<!-- WHY US -->
<section class="web-why-section">
    <div class="web-why-header js-fade">
        <span class="eyebrow">Why AE</span>
        <h2>We build sites like<br>an operator, not a designer.</h2>
    </div>
    <div class="web-why-grid">
        <div class="web-why-card js-fade" style="--delay:0s">
            <div class="web-why-card__icon" aria-hidden="true">⚡</div>
            <h3>Built around the next step</h3>
            <p>Every layout decision, headline, and call to action is informed by what gets visitors to take the next step, not what looks good in a portfolio screenshot.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.1s">
            <div class="web-why-card__icon" aria-hidden="true">🔗</div>
            <h3>Built to integrate</h3>
            <p>We wire your site into your actual workflow: CRM, calendar, email, payment processor. Not a disconnected brochure that creates more manual work.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.2s">
            <div class="web-why-card__icon" aria-hidden="true">🤖</div>
            <h3>AI where it makes sense</h3>
            <p>We add AI intake, follow up sequences, and chat agents where they save you real time, not as a buzzword feature that complicates the experience.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.3s">
            <div class="web-why-card__icon" aria-hidden="true">🏠</div>
            <h3>You own it completely</h3>
            <p>No proprietary builders that lock you in. You get the code, the hosting setup, and the credentials. If you ever want to move, you can.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.4s">
            <div class="web-why-card__icon" aria-hidden="true">📊</div>
            <h3>Measurable from the start</h3>
            <p>Analytics, lead tracking, and conversion events are configured at launch, not as an afterthought, so you can see what is working.</p>
        </div>
        <div class="web-why-card js-fade" style="--delay:0.5s">
            <div class="web-why-card__icon" aria-hidden="true">🛡</div>
            <h3>One team, start to finish</h3>
            <p>You work with one person who understands your business, not a rotating cast of designers, developers, and project managers who've never met each other.</p>
        </div>
    </div>
</section>

<!-- INDUSTRIES -->
<section class="web-industries-strip">
    <div class="web-industries-strip-inner">
        <h2 class="js-fade">Industries these example designs cover</h2>
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
                Rate tools, application intake, plain copy
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
                Provider bios, scheduling, intake forms
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
    <h2 class="js-fade">Ready for a site<br>that <em>brings in calls</em>?</h2>
    <p class="js-fade" style="--delay:0.1s">Tell us what you need and we will reply within 24 hours with next steps. No pressure, no bait and switch pricing.</p>
    <div class="btn-row js-fade" style="--delay:0.2s">
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?service=website" class="btn btn--primary">Get a Quote</a>
        <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="btn btn--ghost">See our work</a>
    </div>
</section>

<?php get_footer(); ?>
