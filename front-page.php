<?php
/**
 * Template Name: Front Page
 * The front page template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AE_Softwares
 */
get_header(); ?>

<div class="front-page-wrapper">

	<!-- HERO: full-viewport, dark, split-line editorial headline -->
	<section class="hero-section" id="hero" aria-label="Hero">
		<div class="hero-bg-dots" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--1" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--2" aria-hidden="true"></div>
		<div class="hero-grid">
			<div class="hero-text">
				<span class="hero-eyebrow">AI Software Development</span>
				<h1 class="hero-headline">
					<span>We build AI</span>
					<span>that works for</span>
					<span><em>your</em> business.</span>
				</h1>
				<p class="hero-sub">Custom intelligent applications that automate processes, enhance engagement, and drive measurable growth.</p>
				<div class="hero-actions">
					<a href="#contact" class="btn btn--primary">Start a project</a>
					<a href="#process" class="btn btn--ghost">See how it works</a>
				</div>
			</div>
			<div class="hero-visual">
				<div class="iphone-container">
					<div class="iphone-screen"><ae-assistant></ae-assistant></div>
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
		<div class="scroll-cue" aria-hidden="true"><span class="scroll-cue__line"></span></div>
	</section>

	<!-- STATS -->
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
				<div class="stat-number" data-target="5" data-suffix="x">0x</div>
				<div class="stat-label">Avg efficiency gain</div>
			</div>
		</div>
	</section>

	<!-- WHAT WE DO reveal -->
	<section class="reveal-section" aria-label="What we do">
		<span class="reveal-bg-number" aria-hidden="true">01</span>
		<div class="reveal-inner">
			<p class="reveal-label">What we do</p>
			<h2 class="reveal-headline">
				<span class="reveal-line-1">We build AI that</span>
				<span class="reveal-line-2">works for <em>your</em></span>
				<span class="reveal-line-3">business.</span>
			</h2>
		</div>
	</section>

	<!-- SERVICES -->
	<section class="services-section" id="services" aria-label="Our services">
		<div class="section-header">
			<span class="section-eyebrow">What we offer</span>
			<h2 class="section-title">Services built<br>for results.</h2>
		</div>
		<ae-products show-all="false"></ae-products>
	</section>

	<!-- PROCESS -->
	<section class="process-section" id="process" aria-label="Our process">
		<div class="process-header">
			<span class="process-eyebrow">How we work</span>
			<h2 class="process-title">From idea<br>to impact.</h2>
		</div>
		<div class="process-track">
			<div class="process-step">
				<span class="process-step__num">01</span>
				<h3 class="process-step__title">Discover</h3>
				<p class="process-step__text">We map your workflows, data sources, and bottlenecks to find where AI delivers the highest ROI.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">02</span>
				<h3 class="process-step__title">Design</h3>
				<p class="process-step__text">Wireframes, architecture diagrams, and a prototype — before a single line of production code is written.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">03</span>
				<h3 class="process-step__title">Build</h3>
				<p class="process-step__text">Agile sprints, weekly demos, and a staging environment so you see real progress every step of the way.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">04</span>
				<h3 class="process-step__title">Deploy & Grow</h3>
				<p class="process-step__text">Hosting, monitoring, and ongoing optimisation so your AI solution keeps delivering value.</p>
			</div>
		</div>
	</section>

	<!-- TESTIMONIALS -->
	<section class="testimonials-section-wrap" id="testimonials" aria-label="Client testimonials">
		<div class="section-header">
			<span class="section-eyebrow">What clients say</span>
			<h2 class="section-title">Results that<br>speak for themselves.</h2>
		</div>
		<ae-testimonials show-all="false"></ae-testimonials>
	</section>

	<!-- CTA -->
	<section id="contact"><ae-cta></ae-cta></section>

</div><!-- .front-page-wrapper -->

<?php get_footer(); ?>
