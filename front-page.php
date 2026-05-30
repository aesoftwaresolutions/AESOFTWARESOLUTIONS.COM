<?php
/**
 * Template Name: Front Page
 * The front page template.
 *
 * @package AE_Softwares
 */
get_header(); ?>

<div class="front-page-wrapper">

	<!-- HERO -->
	<section class="hero-section" id="hero" aria-label="Hero">
		<div class="hero-bg-dots" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--1" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--2" aria-hidden="true"></div>
		<div class="hero-grid">
			<div class="hero-text">
				<span class="hero-eyebrow">Compliance-Aware Operational Intelligence</span>
				<h1 class="hero-headline">
					<span>We map your</span>
					<span>operations. We protect</span>
					<span>your <em>compliance.</em></span>
				</h1>
				<p class="hero-sub">A structured discovery process that finds bottlenecks, compliance gaps, and automation opportunities — then builds the systems to fix them.</p>
				<div class="hero-actions">
					<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn--primary">Start Your Assessment</a>
					<a href="#process" class="btn btn--ghost">See how it works</a>
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
				<div class="stat-label">Assessments delivered</div>
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
				<span class="reveal-line-1">We don't just automate.</span>
				<span class="reveal-line-2">We <em>understand</em></span>
				<span class="reveal-line-3">your business first.</span>
			</h2>
		</div>
	</section>

	<!-- SERVICE TIERS -->
	<section class="tiers-section" id="services" aria-label="Service tiers">
		<div class="section-header">
			<span class="section-eyebrow">How we work</span>
			<h2 class="section-title">Three tiers.<br>One outcome.</h2>
		</div>
		<div class="tiers-grid">
			<div class="tier-card">
				<div class="tier-card__num">01</div>
				<div class="tier-card__label">Assessment</div>
				<h3 class="tier-card__title">Operational Discovery</h3>
				<p class="tier-card__desc">Our AI-guided discovery interview maps your workflows, identifies compliance exposure, and surfaces bottlenecks. You receive a full operational intelligence report.</p>
				<ul class="tier-card__list">
					<li>10-phase business analysis</li>
					<li>Compliance risk mapping</li>
					<li>Automation opportunity score</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="tier-card__link">Start free assessment &rarr;</a>
			</div>
			<div class="tier-card tier-card--featured">
				<div class="tier-card__num">02</div>
				<div class="tier-card__label">Optimization</div>
				<h3 class="tier-card__title">Process Intelligence</h3>
				<p class="tier-card__desc">We personally review your findings, build process maps, design your automation architecture, and deliver a prioritized roadmap with ROI projections.</p>
				<ul class="tier-card__list">
					<li>Process maps &amp; SOPs</li>
					<li>Automation design plan</li>
					<li>ROI projections &amp; roadmap</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="tier-card__link">View services &rarr;</a>
			</div>
			<div class="tier-card">
				<div class="tier-card__num">03</div>
				<div class="tier-card__label">Implementation</div>
				<h3 class="tier-card__title">Intelligent Deployment</h3>
				<p class="tier-card__desc">We build and deploy the actual systems — n8n workflows, AI agents, compliance monitoring, integrations — and hand you a fully operational platform.</p>
				<ul class="tier-card__list">
					<li>n8n workflow automation</li>
					<li>AI agents &amp; integrations</li>
					<li>Compliance monitoring</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="tier-card__link">Get a proposal &rarr;</a>
			</div>
		</div>
	</section>

	<!-- PROCESS -->
	<section class="process-section" id="process" aria-label="Our process">
		<div class="process-header">
			<span class="process-eyebrow">How we work</span>
			<h2 class="process-title">From discovery<br>to deployment.</h2>
		</div>
		<div class="process-track">
			<div class="process-step">
				<span class="process-step__num">01</span>
				<h3 class="process-step__title">Assess</h3>
				<p class="process-step__text">Our structured discovery interview extracts your workflows, compliance requirements, technology stack, and financial leaks — phase by phase, question by question.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">02</span>
				<h3 class="process-step__title">Analyze</h3>
				<p class="process-step__text">We score your operations across six dimensions, map your compliance exposure, and identify automation opportunities ranked by ROI and implementation effort.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">03</span>
				<h3 class="process-step__title">Design</h3>
				<p class="process-step__text">Process maps, architecture diagrams, and a phased roadmap — quick wins in 30 days, mid-term projects in 90, long-term transformation in 12 months.</p>
			</div>
			<div class="process-step">
				<span class="process-step__num">04</span>
				<h3 class="process-step__title">Deploy</h3>
				<p class="process-step__text">We build and deploy your automation stack — workflows, agents, dashboards, and compliance monitoring — with ongoing support and optimization.</p>
			</div>
		</div>
	</section>

	<!-- INDUSTRIES -->
	<section class="industries-strip" aria-label="Industries we serve">
		<div class="industries-strip__inner">
			<div class="industries-strip__header">
				<span class="section-eyebrow">Regulated industries</span>
				<h2 class="section-title">We specialize in<br>compliance-heavy sectors.</h2>
			</div>
			<div class="industries-strip__chips">
				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#mortgage" class="industry-chip">
					<span class="industry-chip__icon">&#9670;</span>
					<span class="industry-chip__name">Mortgage &amp; Lending</span>
					<span class="industry-chip__regs">RESPA · TILA · HMDA</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#insurance" class="industry-chip">
					<span class="industry-chip__icon">&#9670;</span>
					<span class="industry-chip__name">Insurance</span>
					<span class="industry-chip__regs">State filings · Claims · Audit</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#healthcare" class="industry-chip">
					<span class="industry-chip__icon">&#9670;</span>
					<span class="industry-chip__name">Healthcare</span>
					<span class="industry-chip__regs">HIPAA · Billing · Credentialing</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#construction" class="industry-chip">
					<span class="industry-chip__icon">&#9670;</span>
					<span class="industry-chip__name">Construction &amp; Trades</span>
					<span class="industry-chip__regs">Permits · Lien waivers · OSHA</span>
				</a>
			</div>
			<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>" class="btn btn--ghost industries-strip__cta">See all industries</a>
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
