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
    font-size: 0.75rem;
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
    background: #111;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
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
    background: rgba(255,255,255,0.06);
    border-radius: 4px;
    height: 16px;
    margin-left: 4px;
}
.web-scroll-card__preview {
    display: block;
    width: 100%;
    height: 220px;
}
.web-scroll-card__label {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid var(--border);
}
.web-scroll-card__label-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text);
}
.web-scroll-card__label-type {
    font-size: 0.75rem;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: 0.07em;
    text-transform: uppercase;
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

<!-- TEMPLATE SCROLL -->
<section class="web-scroll-section">
    <div class="web-scroll-section__header js-fade">
        <span class="eyebrow">Example templates</span>
        <h2>Sites we've designed<br>for your industry</h2>
    </div>
    <div class="web-scroll-track-wrap">
        <div class="web-scroll-track">

            <!-- Real Estate Agent — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg -->
                    <rect width="320" height="220" fill="#0a1628"/>
                    <!-- nav bar -->
                    <rect width="320" height="36" fill="#07101e"/>
                    <rect x="16" y="13" width="60" height="10" rx="3" fill="#c9a96e" opacity=".9"/>
                    <rect x="212" y="14" width="44" height="8" rx="3" fill="rgba(255,255,255,.12)"/>
                    <rect x="260" y="12" width="44" height="12" rx="4" fill="#c9a96e"/>
                    <!-- hero headline -->
                    <rect x="60" y="54" width="200" height="14" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="80" y="74" width="160" height="14" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="96" y="94" width="128" height="10" rx="3" fill="#c9a96e" opacity=".8"/>
                    <!-- sub copy -->
                    <rect x="72" y="114" width="176" height="6" rx="2" fill="rgba(255,255,255,.3)"/>
                    <rect x="88" y="124" width="144" height="6" rx="2" fill="rgba(255,255,255,.3)"/>
                    <!-- CTA buttons -->
                    <rect x="80" y="140" width="72" height="22" rx="5" fill="#c9a96e"/>
                    <rect x="164" y="140" width="76" height="22" rx="5" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"/>
                    <!-- stats bar -->
                    <rect x="0" y="172" width="320" height="48" fill="#c9a96e" opacity=".15"/>
                    <rect x="24" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/>
                    <rect x="134" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/>
                    <rect x="244" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/>
                    <rect x="24" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/>
                    <rect x="134" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/>
                    <rect x="244" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Real Estate Agent</span>
                    <span class="web-scroll-card__label-type">Lead Funnel</span>
                </div>
            </div>

            <!-- Home Services — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg -->
                    <rect width="320" height="220" fill="#111827"/>
                    <!-- nav - orange accent -->
                    <rect width="320" height="38" fill="#0d1117"/>
                    <rect x="14" y="14" width="64" height="10" rx="3" fill="#f97316" opacity=".9"/>
                    <rect x="180" y="16" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="224" y="16" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="268" y="13" width="38" height="12" rx="4" fill="#f97316"/>
                    <!-- trust bar -->
                    <rect x="0" y="38" width="320" height="24" fill="#f97316" opacity=".12"/>
                    <rect x="20" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/>
                    <rect x="130" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/>
                    <rect x="240" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/>
                    <!-- hero -->
                    <rect x="24" y="76" width="180" height="14" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="24" y="96" width="152" height="14" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="24" y="116" width="120" height="8" rx="2" fill="#f97316" opacity=".7"/>
                    <!-- body copy lines -->
                    <rect x="24" y="134" width="168" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <rect x="24" y="143" width="140" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <!-- CTA -->
                    <rect x="24" y="158" width="84" height="24" rx="5" fill="#f97316"/>
                    <rect x="118" y="158" width="80" height="24" rx="5" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="1.5"/>
                    <!-- service cards bottom strip -->
                    <rect x="0" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/>
                    <rect x="82" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/>
                    <rect x="164" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/>
                    <rect x="246" y="193" width="74" height="27" fill="rgba(249,115,22,.1)" rx="0"/>
                    <rect x="12" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/>
                    <rect x="94" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/>
                    <rect x="176" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/>
                    <rect x="258" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Home Services</span>
                    <span class="web-scroll-card__label-type">Lead Funnel</span>
                </div>
            </div>

            <!-- Mortgage Broker — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg split: left text / right rate card -->
                    <rect width="320" height="220" fill="#0f1f3d"/>
                    <!-- nav -->
                    <rect width="320" height="36" fill="#08142a"/>
                    <rect x="14" y="13" width="70" height="10" rx="3" fill="white" opacity=".8"/>
                    <rect x="16" y="26" width="30" height="4" rx="2" fill="#c9a96e" opacity=".6"/>
                    <rect x="206" y="15" width="36" height="7" rx="2" fill="rgba(255,255,255,.1)"/>
                    <rect x="250" y="14" width="54" height="10" rx="4" fill="#16a34a"/>
                    <!-- left hero copy -->
                    <rect x="16" y="50" width="140" height="12" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="16" y="68" width="120" height="12" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="16" y="86" width="100" height="12" rx="3" fill="#16a34a" opacity=".8"/>
                    <rect x="16" y="108" width="130" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <rect x="16" y="118" width="110" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <!-- CTA row -->
                    <rect x="16" y="134" width="68" height="20" rx="5" fill="#16a34a"/>
                    <rect x="92" y="134" width="60" height="20" rx="5" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"/>
                    <!-- right: rate card -->
                    <rect x="174" y="44" width="134" height="148" rx="8" fill="rgba(255,255,255,.05)" stroke="rgba(255,255,255,.1)" stroke-width="1"/>
                    <rect x="184" y="56" width="60" height="6" rx="2" fill="#c9a96e" opacity=".7"/>
                    <!-- rate rows -->
                    <rect x="184" y="70" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/>
                    <rect x="290" y="68" width="8" height="11" rx="2" fill="white" opacity=".8"/>
                    <rect x="286" y="67" width="16" height="13" rx="2" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1"/>
                    <rect x="184" y="88" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/>
                    <rect x="290" y="86" width="8" height="11" rx="2" fill="white" opacity=".8"/>
                    <rect x="184" y="106" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/>
                    <rect x="290" y="104" width="8" height="11" rx="2" fill="white" opacity=".8"/>
                    <!-- divider -->
                    <line x1="184" y1="122" x2="298" y2="122" stroke="rgba(255,255,255,.1)" stroke-width="1"/>
                    <!-- lock rate btn -->
                    <rect x="184" y="130" width="114" height="22" rx="5" fill="#c9a96e"/>
                    <!-- green stats bar -->
                    <rect x="0" y="198" width="320" height="22" fill="#16a34a" opacity=".2"/>
                    <rect x="20" y="205" width="50" height="6" rx="2" fill="#16a34a" opacity=".7"/>
                    <rect x="130" y="205" width="60" height="6" rx="2" fill="#16a34a" opacity=".7"/>
                    <rect x="250" y="205" width="50" height="6" rx="2" fill="#16a34a" opacity=".7"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Mortgage Broker</span>
                    <span class="web-scroll-card__label-type">Lead Funnel</span>
                </div>
            </div>

            <!-- Insurance Agent — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg -->
                    <rect width="320" height="220" fill="#1a1a2e"/>
                    <!-- nav -->
                    <rect width="320" height="36" fill="#12122a"/>
                    <rect x="14" y="13" width="62" height="10" rx="3" fill="#818cf8" opacity=".9"/>
                    <rect x="200" y="14" width="40" height="8" rx="3" fill="rgba(255,255,255,.1)"/>
                    <rect x="250" y="12" width="54" height="12" rx="4" fill="#6366f1"/>
                    <!-- hero section (dark left panel) -->
                    <rect x="0" y="36" width="180" height="130" fill="#0f0f23"/>
                    <rect x="14" y="52" width="148" height="12" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="14" y="70" width="120" height="12" rx="3" fill="rgba(255,255,255,.85)"/>
                    <rect x="14" y="88" width="100" height="8" rx="3" fill="#818cf8" opacity=".7"/>
                    <rect x="14" y="106" width="140" height="5" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="14" y="116" width="120" height="5" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="14" y="132" width="72" height="20" rx="5" fill="#6366f1"/>
                    <rect x="96" y="132" width="72" height="20" rx="5" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="1.5"/>
                    <!-- right: quote box -->
                    <rect x="186" y="44" width="120" height="116" rx="8" fill="rgba(255,255,255,.04)" stroke="rgba(129,140,248,.2)" stroke-width="1"/>
                    <rect x="196" y="56" width="70" height="7" rx="2" fill="rgba(255,255,255,.6)"/>
                    <rect x="196" y="70" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/>
                    <rect x="196" y="86" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/>
                    <rect x="196" y="102" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/>
                    <rect x="196" y="122" width="100" height="22" rx="5" fill="#6366f1"/>
                    <!-- trust strip -->
                    <rect x="0" y="166" width="320" height="32" fill="rgba(99,102,241,.1)"/>
                    <rect x="20" y="175" width="52" height="6" rx="2" fill="rgba(129,140,248,.5)"/>
                    <rect x="130" y="175" width="60" height="6" rx="2" fill="rgba(129,140,248,.5)"/>
                    <rect x="248" y="175" width="52" height="6" rx="2" fill="rgba(129,140,248,.5)"/>
                    <!-- testimonials strip -->
                    <rect x="0" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/>
                    <rect x="108" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/>
                    <rect x="216" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/>
                    <rect x="10" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="118" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="226" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Insurance Agent</span>
                    <span class="web-scroll-card__label-type">Quote Funnel</span>
                </div>
            </div>

            <!-- Law Firm — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg -->
                    <rect width="320" height="220" fill="#0c0c0c"/>
                    <!-- full-bleed hero image simulation -->
                    <rect width="320" height="150" fill="#1a1008"/>
                    <!-- dark overlay gradient -->
                    <rect width="320" height="150" fill="url(#law-grad)"/>
                    <defs>
                        <linearGradient id="law-grad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#0c0c0c" stop-opacity=".5"/>
                            <stop offset="100%" stop-color="#0c0c0c" stop-opacity=".85"/>
                        </linearGradient>
                    </defs>
                    <!-- nav -->
                    <rect x="14" y="12" width="72" height="10" rx="3" fill="#b8922a" opacity=".9"/>
                    <rect x="14" y="25" width="40" height="4" rx="2" fill="rgba(184,146,42,.4)"/>
                    <rect x="200" y="13" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="244" y="13" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/>
                    <rect x="284" y="11" width="22" height="12" rx="4" fill="#b8922a"/>
                    <!-- serif headline -->
                    <rect x="24" y="52" width="200" height="16" rx="2" fill="rgba(255,255,255,.9)"/>
                    <rect x="24" y="74" width="170" height="16" rx="2" fill="rgba(255,255,255,.9)"/>
                    <rect x="24" y="96" width="140" height="10" rx="2" fill="#b8922a" opacity=".75"/>
                    <!-- tagline -->
                    <rect x="24" y="116" width="180" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <rect x="24" y="126" width="160" height="5" rx="2" fill="rgba(255,255,255,.25)"/>
                    <rect x="24" y="140" width="76" height="20" rx="0" fill="#b8922a"/>
                    <!-- practice areas cards -->
                    <rect x="0" y="158" width="80" height="62" fill="#141414"/>
                    <rect x="84" y="158" width="80" height="62" fill="#141414"/>
                    <rect x="168" y="158" width="80" height="62" fill="#141414"/>
                    <rect x="252" y="158" width="68" height="62" fill="#141414"/>
                    <rect x="10" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/>
                    <rect x="94" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/>
                    <rect x="178" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/>
                    <rect x="262" y="168" width="44" height="6" rx="2" fill="#b8922a" opacity=".7"/>
                    <rect x="10" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="10" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="94" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="94" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="178" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="178" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="262" y="180" width="42" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                    <rect x="262" y="188" width="36" height="4" rx="2" fill="rgba(255,255,255,.2)"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Law Firm</span>
                    <span class="web-scroll-card__label-type">Intake Funnel</span>
                </div>
            </div>

            <!-- Consultant / Coach — original -->
            <div class="web-scroll-card">
                <div class="web-scroll-card__browser">
                    <span class="web-scroll-card__dot web-scroll-card__dot--r"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--y"></span>
                    <span class="web-scroll-card__dot web-scroll-card__dot--g"></span>
                    <span class="web-scroll-card__url"></span>
                </div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- bg -->
                    <rect width="320" height="220" fill="#f8f7f4"/>
                    <!-- nav light -->
                    <rect width="320" height="38" fill="#fff" stroke="#e5e7eb" stroke-width="1"/>
                    <rect x="16" y="14" width="68" height="10" rx="3" fill="#1a1a1a"/>
                    <rect x="180" y="16" width="36" height="7" rx="2" fill="#9ca3af"/>
                    <rect x="224" y="16" width="36" height="7" rx="2" fill="#9ca3af"/>
                    <rect x="272" y="13" width="32" height="12" rx="4" fill="#1a1a1a"/>
                    <!-- hero center -->
                    <rect x="60" y="56" width="200" height="14" rx="3" fill="#1a1a1a" opacity=".85"/>
                    <rect x="80" y="76" width="160" height="14" rx="3" fill="#1a1a1a" opacity=".85"/>
                    <!-- accent underline -->
                    <rect x="100" y="95" width="120" height="3" rx="1.5" fill="#c9a96e"/>
                    <!-- subtext -->
                    <rect x="80" y="108" width="160" height="5" rx="2" fill="#9ca3af"/>
                    <rect x="96" y="118" width="128" height="5" rx="2" fill="#9ca3af"/>
                    <!-- CTA -->
                    <rect x="108" y="134" width="104" height="24" rx="5" fill="#1a1a1a"/>
                    <!-- social proof row -->
                    <rect x="80" y="168" width="160" height="5" rx="2" fill="#d1d5db"/>
                    <!-- testimonial cards -->
                    <rect x="12" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/>
                    <rect x="114" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/>
                    <rect x="216" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/>
                    <rect x="20" y="192" width="52" height="4" rx="2" fill="#374151"/>
                    <rect x="20" y="200" width="40" height="4" rx="2" fill="#9ca3af"/>
                    <rect x="122" y="192" width="52" height="4" rx="2" fill="#374151"/>
                    <rect x="122" y="200" width="40" height="4" rx="2" fill="#9ca3af"/>
                    <rect x="224" y="192" width="52" height="4" rx="2" fill="#374151"/>
                    <rect x="224" y="200" width="40" height="4" rx="2" fill="#9ca3af"/>
                </svg>
                <div class="web-scroll-card__label">
                    <span class="web-scroll-card__label-name">Consultant / Coach</span>
                    <span class="web-scroll-card__label-type">Portfolio + Booking</span>
                </div>
            </div>

            <!-- Duplicate set for seamless loop -->

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#0a1628"/><rect width="320" height="36" fill="#07101e"/><rect x="16" y="13" width="60" height="10" rx="3" fill="#c9a96e" opacity=".9"/><rect x="212" y="14" width="44" height="8" rx="3" fill="rgba(255,255,255,.12)"/><rect x="260" y="12" width="44" height="12" rx="4" fill="#c9a96e"/><rect x="60" y="54" width="200" height="14" rx="3" fill="rgba(255,255,255,.85)"/><rect x="80" y="74" width="160" height="14" rx="3" fill="rgba(255,255,255,.85)"/><rect x="96" y="94" width="128" height="10" rx="3" fill="#c9a96e" opacity=".8"/><rect x="72" y="114" width="176" height="6" rx="2" fill="rgba(255,255,255,.3)"/><rect x="88" y="124" width="144" height="6" rx="2" fill="rgba(255,255,255,.3)"/><rect x="80" y="140" width="72" height="22" rx="5" fill="#c9a96e"/><rect x="164" y="140" width="76" height="22" rx="5" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"/><rect x="0" y="172" width="320" height="48" fill="#c9a96e" opacity=".15"/><rect x="24" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/><rect x="134" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/><rect x="244" y="183" width="52" height="12" rx="3" fill="#c9a96e" opacity=".9"/><rect x="24" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/><rect x="134" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/><rect x="244" y="199" width="36" height="6" rx="2" fill="rgba(255,255,255,.3)"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Real Estate Agent</span><span class="web-scroll-card__label-type">Lead Funnel</span></div>
            </div>

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#111827"/><rect width="320" height="38" fill="#0d1117"/><rect x="14" y="14" width="64" height="10" rx="3" fill="#f97316" opacity=".9"/><rect x="180" y="16" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/><rect x="224" y="16" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/><rect x="268" y="13" width="38" height="12" rx="4" fill="#f97316"/><rect x="0" y="38" width="320" height="24" fill="#f97316" opacity=".12"/><rect x="20" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/><rect x="130" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/><rect x="240" y="44" width="60" height="6" rx="2" fill="rgba(255,165,0,.5)"/><rect x="24" y="76" width="180" height="14" rx="3" fill="rgba(255,255,255,.85)"/><rect x="24" y="96" width="152" height="14" rx="3" fill="rgba(255,255,255,.85)"/><rect x="24" y="116" width="120" height="8" rx="2" fill="#f97316" opacity=".7"/><rect x="24" y="134" width="168" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="24" y="143" width="140" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="24" y="158" width="84" height="24" rx="5" fill="#f97316"/><rect x="118" y="158" width="80" height="24" rx="5" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="1.5"/><rect x="0" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/><rect x="82" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/><rect x="164" y="193" width="76" height="27" fill="rgba(249,115,22,.1)" rx="0"/><rect x="246" y="193" width="74" height="27" fill="rgba(249,115,22,.1)" rx="0"/><rect x="12" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/><rect x="94" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/><rect x="176" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/><rect x="258" y="201" width="48" height="5" rx="2" fill="rgba(249,115,22,.6)"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Home Services</span><span class="web-scroll-card__label-type">Lead Funnel</span></div>
            </div>

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#0f1f3d"/><rect width="320" height="36" fill="#08142a"/><rect x="14" y="13" width="70" height="10" rx="3" fill="white" opacity=".8"/><rect x="16" y="26" width="30" height="4" rx="2" fill="#c9a96e" opacity=".6"/><rect x="206" y="15" width="36" height="7" rx="2" fill="rgba(255,255,255,.1)"/><rect x="250" y="14" width="54" height="10" rx="4" fill="#16a34a"/><rect x="16" y="50" width="140" height="12" rx="3" fill="rgba(255,255,255,.85)"/><rect x="16" y="68" width="120" height="12" rx="3" fill="rgba(255,255,255,.85)"/><rect x="16" y="86" width="100" height="12" rx="3" fill="#16a34a" opacity=".8"/><rect x="16" y="108" width="130" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="16" y="118" width="110" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="16" y="134" width="68" height="20" rx="5" fill="#16a34a"/><rect x="92" y="134" width="60" height="20" rx="5" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"/><rect x="174" y="44" width="134" height="148" rx="8" fill="rgba(255,255,255,.05)" stroke="rgba(255,255,255,.1)" stroke-width="1"/><rect x="184" y="56" width="60" height="6" rx="2" fill="#c9a96e" opacity=".7"/><rect x="184" y="70" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/><rect x="290" y="68" width="8" height="11" rx="2" fill="white" opacity=".8"/><rect x="286" y="67" width="16" height="13" rx="2" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1"/><rect x="184" y="88" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/><rect x="290" y="86" width="8" height="11" rx="2" fill="white" opacity=".8"/><rect x="184" y="106" width="80" height="7" rx="2" fill="rgba(255,255,255,.5)"/><rect x="290" y="104" width="8" height="11" rx="2" fill="white" opacity=".8"/><line x1="184" y1="122" x2="298" y2="122" stroke="rgba(255,255,255,.1)" stroke-width="1"/><rect x="184" y="130" width="114" height="22" rx="5" fill="#c9a96e"/><rect x="0" y="198" width="320" height="22" fill="#16a34a" opacity=".2"/><rect x="20" y="205" width="50" height="6" rx="2" fill="#16a34a" opacity=".7"/><rect x="130" y="205" width="60" height="6" rx="2" fill="#16a34a" opacity=".7"/><rect x="250" y="205" width="50" height="6" rx="2" fill="#16a34a" opacity=".7"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Mortgage Broker</span><span class="web-scroll-card__label-type">Lead Funnel</span></div>
            </div>

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#1a1a2e"/><rect width="320" height="36" fill="#12122a"/><rect x="14" y="13" width="62" height="10" rx="3" fill="#818cf8" opacity=".9"/><rect x="200" y="14" width="40" height="8" rx="3" fill="rgba(255,255,255,.1)"/><rect x="250" y="12" width="54" height="12" rx="4" fill="#6366f1"/><rect x="0" y="36" width="180" height="130" fill="#0f0f23"/><rect x="14" y="52" width="148" height="12" rx="3" fill="rgba(255,255,255,.85)"/><rect x="14" y="70" width="120" height="12" rx="3" fill="rgba(255,255,255,.85)"/><rect x="14" y="88" width="100" height="8" rx="3" fill="#818cf8" opacity=".7"/><rect x="14" y="106" width="140" height="5" rx="2" fill="rgba(255,255,255,.2)"/><rect x="14" y="116" width="120" height="5" rx="2" fill="rgba(255,255,255,.2)"/><rect x="14" y="132" width="72" height="20" rx="5" fill="#6366f1"/><rect x="96" y="132" width="72" height="20" rx="5" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="1.5"/><rect x="186" y="44" width="120" height="116" rx="8" fill="rgba(255,255,255,.04)" stroke="rgba(129,140,248,.2)" stroke-width="1"/><rect x="196" y="56" width="70" height="7" rx="2" fill="rgba(255,255,255,.6)"/><rect x="196" y="70" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/><rect x="196" y="86" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/><rect x="196" y="102" width="100" height="10" rx="3" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.12)" stroke-width="1"/><rect x="196" y="122" width="100" height="22" rx="5" fill="#6366f1"/><rect x="0" y="166" width="320" height="32" fill="rgba(99,102,241,.1)"/><rect x="20" y="175" width="52" height="6" rx="2" fill="rgba(129,140,248,.5)"/><rect x="130" y="175" width="60" height="6" rx="2" fill="rgba(129,140,248,.5)"/><rect x="248" y="175" width="52" height="6" rx="2" fill="rgba(129,140,248,.5)"/><rect x="0" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/><rect x="108" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/><rect x="216" y="198" width="104" height="22" fill="rgba(255,255,255,.03)"/><rect x="10" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/><rect x="118" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/><rect x="226" y="206" width="70" height="5" rx="2" fill="rgba(255,255,255,.2)"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Insurance Agent</span><span class="web-scroll-card__label-type">Quote Funnel</span></div>
            </div>

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#0c0c0c"/><rect width="320" height="150" fill="#1a1008"/><rect width="320" height="150" fill="url(#law-grad2)"/><defs><linearGradient id="law-grad2" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#0c0c0c" stop-opacity=".5"/><stop offset="100%" stop-color="#0c0c0c" stop-opacity=".85"/></linearGradient></defs><rect x="14" y="12" width="72" height="10" rx="3" fill="#b8922a" opacity=".9"/><rect x="14" y="25" width="40" height="4" rx="2" fill="rgba(184,146,42,.4)"/><rect x="200" y="13" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/><rect x="244" y="13" width="36" height="7" rx="2" fill="rgba(255,255,255,.12)"/><rect x="284" y="11" width="22" height="12" rx="4" fill="#b8922a"/><rect x="24" y="52" width="200" height="16" rx="2" fill="rgba(255,255,255,.9)"/><rect x="24" y="74" width="170" height="16" rx="2" fill="rgba(255,255,255,.9)"/><rect x="24" y="96" width="140" height="10" rx="2" fill="#b8922a" opacity=".75"/><rect x="24" y="116" width="180" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="24" y="126" width="160" height="5" rx="2" fill="rgba(255,255,255,.25)"/><rect x="24" y="140" width="76" height="20" rx="0" fill="#b8922a"/><rect x="0" y="158" width="80" height="62" fill="#141414"/><rect x="84" y="158" width="80" height="62" fill="#141414"/><rect x="168" y="158" width="80" height="62" fill="#141414"/><rect x="252" y="158" width="68" height="62" fill="#141414"/><rect x="10" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/><rect x="94" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/><rect x="178" y="168" width="56" height="6" rx="2" fill="#b8922a" opacity=".7"/><rect x="262" y="168" width="44" height="6" rx="2" fill="#b8922a" opacity=".7"/><rect x="10" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="10" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="94" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="94" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="178" y="180" width="52" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="178" y="188" width="46" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="262" y="180" width="42" height="4" rx="2" fill="rgba(255,255,255,.2)"/><rect x="262" y="188" width="36" height="4" rx="2" fill="rgba(255,255,255,.2)"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Law Firm</span><span class="web-scroll-card__label-type">Intake Funnel</span></div>
            </div>

            <div class="web-scroll-card">
                <div class="web-scroll-card__browser"><span class="web-scroll-card__dot web-scroll-card__dot--r"></span><span class="web-scroll-card__dot web-scroll-card__dot--y"></span><span class="web-scroll-card__dot web-scroll-card__dot--g"></span><span class="web-scroll-card__url"></span></div>
                <svg class="web-scroll-card__preview" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg"><rect width="320" height="220" fill="#f8f7f4"/><rect width="320" height="38" fill="#fff" stroke="#e5e7eb" stroke-width="1"/><rect x="16" y="14" width="68" height="10" rx="3" fill="#1a1a1a"/><rect x="180" y="16" width="36" height="7" rx="2" fill="#9ca3af"/><rect x="224" y="16" width="36" height="7" rx="2" fill="#9ca3af"/><rect x="272" y="13" width="32" height="12" rx="4" fill="#1a1a1a"/><rect x="60" y="56" width="200" height="14" rx="3" fill="#1a1a1a" opacity=".85"/><rect x="80" y="76" width="160" height="14" rx="3" fill="#1a1a1a" opacity=".85"/><rect x="100" y="95" width="120" height="3" rx="1.5" fill="#c9a96e"/><rect x="80" y="108" width="160" height="5" rx="2" fill="#9ca3af"/><rect x="96" y="118" width="128" height="5" rx="2" fill="#9ca3af"/><rect x="108" y="134" width="104" height="24" rx="5" fill="#1a1a1a"/><rect x="80" y="168" width="160" height="5" rx="2" fill="#d1d5db"/><rect x="12" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/><rect x="114" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/><rect x="216" y="183" width="92" height="37" rx="6" fill="#fff" stroke="#e5e7eb" stroke-width="1"/><rect x="20" y="192" width="52" height="4" rx="2" fill="#374151"/><rect x="20" y="200" width="40" height="4" rx="2" fill="#9ca3af"/><rect x="122" y="192" width="52" height="4" rx="2" fill="#374151"/><rect x="122" y="200" width="40" height="4" rx="2" fill="#9ca3af"/><rect x="224" y="192" width="52" height="4" rx="2" fill="#374151"/><rect x="224" y="200" width="40" height="4" rx="2" fill="#9ca3af"/></svg>
                <div class="web-scroll-card__label"><span class="web-scroll-card__label-name">Consultant / Coach</span><span class="web-scroll-card__label-type">Portfolio + Booking</span></div>
            </div>

        </div><!-- .web-scroll-track -->
    </div><!-- .web-scroll-track-wrap -->
</section>

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
