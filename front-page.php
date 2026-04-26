<?php
/*
Template Name: Front Page
*/
get_header();
?>

<div class="front-page-wrapper">

    <!-- ═══════════════════════════════════════════════════
         SECTION 1 — HERO
         Three depth layers: dot grid (back) → orbs (mid) → iPhone (front)
         GSAP parallaxes each layer at a different speed on desktop.
    ═══════════════════════════════════════════════════════ -->
    <section class="hero-section" aria-label="Hero">

        <div class="hero-bg-dots" aria-hidden="true"></div>
        <div class="hero-orb hero-orb--1" aria-hidden="true"></div>
        <div class="hero-orb hero-orb--2" aria-hidden="true"></div>

        <div class="hero-grid">
            <div class="hero-text">
                <span class="hero-eyebrow">AI Software Development</span>

                <ae-greetings
                    greeting-title="Transform Your Business with Custom AI Solutions"
                    greeting-text="We design, build, and deploy intelligent applications that automate processes, enhance customer engagement, and drive measurable growth."
                ></ae-greetings>

                <div class="hero-badge">
                    <span class="hero-badge__dot"></span>
                    Available for new projects
                </div>
            </div>

            <div class="hero-visual">
                <div class="iphone-container">
                    <div class="iphone-screen">
                        <ae-assistant></ae-assistant>
                    </div>
                    <svg class="iphone-svg" viewBox="0 0 400 794" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M0 59C0 26.4152 26.4152 0 59 0H341C373.585 0 400 26.4152 400 59V735C400 767.585 373.585 794 341 794H59C26.4152 794 0 767.585 0 735V59Z" fill="#1C1C1E"/>
                        <path d="M13.5 61C13.5 35.2324 35.2324 13.5 61 13.5H339C364.768 13.5 386.5 35.2324 386.5 61V733C386.5 758.768 364.768 780.5 339 780.5H61C35.2324 780.5 13.5 758.768 13.5 733V61Z" fill="#000000" stroke="#333333" stroke-width="1"/>
                        <rect x="392" y="116" width="8" height="35" rx="4" fill="#1C1C1E"/>
                        <rect x="392" y="177" width="8" height="60" rx="4" fill="#1C1C1E"/>
                        <rect x="392" y="247" width="8" height="60" rx="4" fill="#1C1C1E"/>
                        <path d="M119.5 32H280.5C281.052 32 281.5 32.4477 281.5 33V43C281.5 43.5523 281.052 44 280.5 44H119.5C118.948 44 118.5 43.5523 118.5 43V33C118.5 32.4477 118.948 32 119.5 32Z" fill="#1C1C1E"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="scroll-cue" aria-hidden="true">
            <span class="scroll-cue__line"></span>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 2 — STATS
         Three numbers that count up when they scroll into view.
    ═══════════════════════════════════════════════════════ -->
    <section class="stats-section" aria-label="Company stats">
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-number" data-target="50" data-suffix="+">0+</div>
                <div class="stat-label">Projects delivered</div>
            </div>
            <div class="stat-divider" aria-hidden="true"></div>
            <div class="stat-item">
                <div class="stat-number" data-target="98" data-suffix="%">0%</div>
                <div class="stat-label">Client satisfaction</div>
            </div>
            <div class="stat-divider" aria-hidden="true"></div>
            <div class="stat-item">
                <div class="stat-number" data-target="5" data-suffix="×">0×</div>
                <div class="stat-label">Avg efficiency gain</div>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 3 — PINNED "WHAT WE DO" REVEAL
         Desktop: section pins while three headline lines scrub in.
         Mobile: simple staggered fade-up.
    ═══════════════════════════════════════════════════════ -->
    <section class="reveal-section" aria-label="What we do">
        <span class="reveal-bg-number" aria-hidden="true">01</span>
        <div class="reveal-inner">
            <p class="reveal-label">What we do</p>
            <h2 class="reveal-headline">
                <span class="reveal-line-1">We build AI that</span>
                <span class="reveal-line-2">works for <em>your</em></span>
                <span class="reveal-line-3">business.</span>
            </h2>
            <div class="reveal-caret" aria-hidden="true"></div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 4 — SERVICES (web component)
    ═══════════════════════════════════════════════════════ -->
    <section class="services-section" aria-label="Our services">
        <ae-products show-all="false"></ae-products>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 5 — HORIZONTAL PROCESS TRACK
         Desktop: vertical scroll becomes a horizontal pan.
         Mobile: vertical stack with staggered fade-ups.
    ═══════════════════════════════════════════════════════ -->
    <section class="process-section" aria-label="Our process">
        <div class="process-header">
            <span class="process-eyebrow">How we work</span>
            <h2 class="process-title">From idea to impact</h2>
        </div>
        <div class="process-track">
            <div class="process-step">
                <span class="process-step__num">01</span>
                <h3 class="process-step__title">Discover</h3>
                <p class="process-step__text">We map your workflows, data sources, and bottlenecks to find where AI can deliver the highest ROI.</p>
            </div>
            <div class="process-step">
                <span class="process-step__num">02</span>
                <h3 class="process-step__title">Design</h3>
                <p class="process-step__text">Wireframes, architecture diagrams, and a prototype — before a single line of production code is written.</p>
            </div>
            <div class="process-step">
                <span class="process-step__num">03</span>
                <h3 class="process-step__title">Build</h3>
                <p class="process-step__text">Agile sprints, weekly demos, and a staging environment so you can see real progress every step of the way.</p>
            </div>
            <div class="process-step">
                <span class="process-step__num">04</span>
                <h3 class="process-step__title">Deploy & Grow</h3>
                <p class="process-step__text">We handle hosting, monitoring, and ongoing optimisation so your AI solution keeps delivering value.</p>
            </div>
        </div>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 6 — TESTIMONIALS (web component)
    ═══════════════════════════════════════════════════════ -->
    <section class="testimonials-section-wrap" aria-label="Client testimonials">
        <ae-testimonials show-all="false"></ae-testimonials>
    </section>


    <!-- ═══════════════════════════════════════════════════
         SECTION 7 — CTA (web component)
    ═══════════════════════════════════════════════════════ -->
    <ae-cta></ae-cta>

</div><!-- /.front-page-wrapper -->

<?php get_footer(); ?>
