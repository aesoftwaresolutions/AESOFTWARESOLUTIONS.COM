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
				<p class="hero-sub" data-reveal-text>A structured discovery process that finds bottlenecks, compliance gaps, and automation opportunities — then builds the systems to fix them.</p>
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
			<h2 class="reveal-headline" data-reveal-text>
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

			<div class="tier-card js-fade" style="--delay:0s">
				<div class="tier-card__icon" aria-hidden="true">
					<!-- Scan / magnifying glass with data lines -->
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="13" cy="13" r="8" stroke="currentColor" stroke-width="1.5"/>
						<line x1="19" y1="19" x2="27" y2="27" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						<line x1="9.5" y1="13" x2="16.5" y2="13" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.5"/>
						<line x1="9.5" y1="10" x2="14.5" y2="10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.35"/>
						<line x1="9.5" y1="16" x2="15.5" y2="16" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.35"/>
					</svg>
				</div>
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

			<div class="tier-card tier-card--featured js-fade" style="--delay:0.12s">
				<div class="tier-card__icon" aria-hidden="true">
					<!-- Ascending bar chart with trend line -->
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="3" y="20" width="5.5" height="9" rx="1" fill="currentColor" opacity="0.25"/>
						<rect x="12" y="13" width="5.5" height="16" rx="1" fill="currentColor" opacity="0.5"/>
						<rect x="21" y="5" width="5.5" height="24" rx="1" fill="currentColor" opacity="0.85"/>
						<polyline points="5.75,19 14.75,12 23.75,4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.55"/>
					</svg>
				</div>
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

			<div class="tier-card js-fade" style="--delay:0.24s">
				<div class="tier-card__icon" aria-hidden="true">
					<!-- Connected nodes / deployment flow -->
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="5" cy="16" r="3" stroke="currentColor" stroke-width="1.5"/>
						<circle cx="16" cy="7" r="3" stroke="currentColor" stroke-width="1.5"/>
						<circle cx="16" cy="25" r="3" stroke="currentColor" stroke-width="1.5"/>
						<circle cx="27" cy="16" r="3" stroke="currentColor" stroke-width="1.5"/>
						<line x1="7.8" y1="14.2" x2="13.2" y2="8.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
						<line x1="7.8" y1="17.8" x2="13.2" y2="23.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
						<line x1="18.8" y1="8.8" x2="24.2" y2="14.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
						<line x1="18.8" y1="23.2" x2="24.2" y2="17.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
					</svg>
				</div>
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

			<div class="process-step js-fade" style="--delay:0s">
				<div class="process-step__icon" aria-hidden="true">
					<!-- Document scan / clipboard -->
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="4" width="14" height="16" rx="2" stroke="currentColor" stroke-width="1.4"/>
						<path d="M9 4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
						<line x1="8" y1="10" x2="16" y2="10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.5"/>
						<line x1="8" y1="13" x2="16" y2="13" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.5"/>
						<line x1="8" y1="16" x2="12" y2="16" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.5"/>
					</svg>
				</div>
				<span class="process-step__num">01</span>
				<h3 class="process-step__title">Assess</h3>
				<p class="process-step__text">Our structured discovery interview extracts your workflows, compliance requirements, technology stack, and financial leaks — phase by phase, question by question.</p>
			</div>

			<div class="process-step js-fade" style="--delay:0.1s">
				<div class="process-step__icon" aria-hidden="true">
					<!-- Bar chart / data analysis -->
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<line x1="3" y1="20" x2="3" y2="4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity="0.4"/>
						<line x1="3" y1="20" x2="21" y2="20" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity="0.4"/>
						<rect x="5" y="14" width="3.5" height="6" fill="currentColor" opacity="0.3" rx="0.5"/>
						<rect x="10" y="9" width="3.5" height="11" fill="currentColor" opacity="0.55" rx="0.5"/>
						<rect x="15" y="5" width="3.5" height="15" fill="currentColor" opacity="0.85" rx="0.5"/>
					</svg>
				</div>
				<span class="process-step__num">02</span>
				<h3 class="process-step__title">Analyze</h3>
				<p class="process-step__text">We score your operations across six dimensions, map your compliance exposure, and identify automation opportunities ranked by ROI and implementation effort.</p>
			</div>

			<div class="process-step js-fade" style="--delay:0.2s">
				<div class="process-step__icon" aria-hidden="true">
					<!-- Blueprint / layout grid -->
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.4"/>
						<line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="1.2" opacity="0.45"/>
						<line x1="3" y1="15" x2="21" y2="15" stroke="currentColor" stroke-width="1.2" opacity="0.45"/>
						<line x1="9" y1="9" x2="9" y2="21" stroke="currentColor" stroke-width="1.2" opacity="0.45"/>
					</svg>
				</div>
				<span class="process-step__num">03</span>
				<h3 class="process-step__title">Design</h3>
				<p class="process-step__text">Process maps, architecture diagrams, and a phased roadmap — quick wins in 30 days, mid-term projects in 90, long-term transformation in 12 months.</p>
			</div>

			<div class="process-step js-fade" style="--delay:0.3s">
				<div class="process-step__icon" aria-hidden="true">
					<!-- Lightning bolt / deploy -->
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M13 2L4.5 13.5H12L11 22L19.5 10.5H12L13 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
					</svg>
				</div>
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

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#mortgage" class="industry-chip js-fade" style="--delay:0s">
					<span class="industry-chip__icon" aria-hidden="true">
						<!-- House outline -->
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 9L10 3L17 9V17H13V13H7V17H3V9Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
						</svg>
					</span>
					<span class="industry-chip__name">Mortgage &amp; Lending</span>
					<span class="industry-chip__regs">RESPA · TILA · HMDA</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#insurance" class="industry-chip js-fade" style="--delay:0.08s">
					<span class="industry-chip__icon" aria-hidden="true">
						<!-- Shield outline -->
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 2L3 5V10C3 13.87 6.08 17.5 10 18.5C13.92 17.5 17 13.87 17 10V5L10 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
						</svg>
					</span>
					<span class="industry-chip__name">Insurance</span>
					<span class="industry-chip__regs">State filings · Claims · Audit</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#healthcare" class="industry-chip js-fade" style="--delay:0.16s">
					<span class="industry-chip__icon" aria-hidden="true">
						<!-- Medical cross -->
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect x="8" y="2" width="4" height="16" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
							<rect x="2" y="8" width="16" height="4" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
						</svg>
					</span>
					<span class="industry-chip__name">Healthcare</span>
					<span class="industry-chip__regs">HIPAA · Billing · Credentialing</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#construction" class="industry-chip js-fade" style="--delay:0.24s">
					<span class="industry-chip__icon" aria-hidden="true">
						<!-- Hard hat -->
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 13H17V15H3V13Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
							<path d="M5 13C5 9.13 7.24 6 10 6C12.76 6 15 9.13 15 13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
							<line x1="10" y1="4" x2="10" y2="7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
						</svg>
					</span>
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
