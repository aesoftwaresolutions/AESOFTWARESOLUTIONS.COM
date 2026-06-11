<?php
/**
 * Template Name: Front Page
 * The front page template.
 *
 * @package AE_Softwares
 */
add_action( 'wp_head', function () { ?>
<style>
/* ── Business Starter promo strip ──────────────────────────────────────── */
.starter-promo-section{padding:clamp(64px,8vw,112px) clamp(1.5rem,5vw,4rem);background:var(--bg-surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border);}
.starter-promo-inner{max-width:var(--max-width);margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;}
.starter-promo__headline{font-family:var(--font-editorial);font-size:clamp(2rem,3.5vw,3rem);font-weight:400;color:var(--text);line-height:1.1;margin:.75rem 0 1rem;}
.starter-promo__headline em{font-style:italic;color:var(--accent);}
.starter-promo__sub{font-size:1rem;color:var(--text-muted);line-height:1.75;max-width:46ch;margin:0 0 2rem;}
.starter-promo-features{display:flex;flex-direction:column;gap:.75rem;}
.starter-feature-pill{display:flex;align-items:center;gap:.65rem;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:.85rem 1.1rem;font-size:.9rem;color:var(--text-muted);transition:border-color .2s ease,color .2s ease;}
.starter-feature-pill svg{color:var(--accent);flex-shrink:0;}
.starter-feature-pill:hover{border-color:var(--accent);color:var(--text);}
@media(max-width:768px){.starter-promo-inner{grid-template-columns:1fr;gap:2.5rem;}}
</style>
<?php }, 20 );
get_header(); ?>

<div class="front-page-wrapper">

	<!-- HERO -->
	<section class="hero-section" id="hero" aria-label="Hero">
		<div class="hero-bg-dots" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--1" aria-hidden="true"></div>
		<div class="hero-orb hero-orb--2" aria-hidden="true"></div>
		<div class="hero-grid">
			<div class="hero-text">
				<span class="hero-eyebrow">Business Operations &amp; Efficiency Platform</span>
				<h1 class="hero-headline">
					<span>We support your</span>
					<span>business. We optimize</span>
					<span>your <em>efficiency.</em></span>
				</h1>
				<p class="hero-sub" data-reveal-text>From CRM and accounting tools to compliance guides and automation — we give growing businesses the systems they need to operate faster, smarter, and with confidence.</p>
				<div class="hero-actions">
					<a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="btn btn--primary">Get the Starter Package</a>
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
				<div class="stat-label">Businesses supported</div>
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
				<span class="reveal-line-1">We don't just sell software.</span>
				<span class="reveal-line-2">We <em>empower</em></span>
				<span class="reveal-line-3">your business to grow.</span>
			</h2>
		</div>
	</section>

	<!-- SERVICE TIERS -->
	<section class="tiers-section" id="services" aria-label="Service tiers">
		<div class="section-header">
			<span class="section-eyebrow">How we help</span>
			<h2 class="section-title">Three ways.<br>One goal: your growth.</h2>
		</div>
		<div class="tiers-grid">

			<div class="tier-card js-fade" style="--delay:0s">
				<div class="tier-card__icon" aria-hidden="true">
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="4" y="6" width="10" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
						<rect x="18" y="6" width="10" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
						<rect x="4" y="20" width="10" height="6" rx="2" stroke="currentColor" stroke-width="1.5" opacity="0.45"/>
						<rect x="18" y="20" width="10" height="6" rx="2" stroke="currentColor" stroke-width="1.5" opacity="0.45"/>
					</svg>
				</div>
				<div class="tier-card__num">01</div>
				<div class="tier-card__label">Starter</div>
				<h3 class="tier-card__title">Business Starter Package</h3>
				<p class="tier-card__desc">Everything a growing business needs in one affordable subscription — CRM, accounting tools, and state-specific compliance guides, all under one roof.</p>
				<ul class="tier-card__list">
					<li>aeCRM — manage leads &amp; clients</li>
					<li>Accounting &amp; invoicing tools</li>
					<li>Compliance guides for any state</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="tier-card__link">See the $50/mo plan &rarr;</a>
			</div>

			<div class="tier-card tier-card--featured js-fade" style="--delay:0.12s">
				<div class="tier-card__icon" aria-hidden="true">
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
				<p class="tier-card__desc">We map your current workflows, identify bottlenecks, and build a clear roadmap for efficiency gains — with ROI projections and quick wins prioritized first.</p>
				<ul class="tier-card__list">
					<li>10-phase business assessment</li>
					<li>Process maps &amp; SOPs</li>
					<li>Automation roadmap &amp; ROI plan</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="tier-card__link">Start free assessment &rarr;</a>
			</div>

			<div class="tier-card js-fade" style="--delay:0.24s">
				<div class="tier-card__icon" aria-hidden="true">
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
				<p class="tier-card__desc">We build and deploy your automation stack — workflows, AI agents, integrations, and monitoring — and hand you a fully operational system ready to scale.</p>
				<ul class="tier-card__list">
					<li>Custom workflow automation</li>
					<li>AI agents &amp; integrations</li>
					<li>Ongoing support &amp; optimization</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="tier-card__link">Get a proposal &rarr;</a>
			</div>

		</div>
	</section>

	<!-- BUSINESS STARTER PACKAGE PROMO -->
	<section class="starter-promo-section js-fade" aria-label="Business Starter Package">
		<div class="starter-promo-inner">
			<div class="starter-promo-text">
				<span class="section-eyebrow">New</span>
				<h2 class="starter-promo__headline">Everything your business needs.<br><em>One simple subscription.</em></h2>
				<p class="starter-promo__sub">For just $50/month, get access to aeCRM, accounting and invoicing tools, and compliance &amp; registration guides for any U.S. state — the essential stack for any growing business.</p>
				<a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="btn btn--primary">Get Started for $50/mo</a>
			</div>
			<div class="starter-promo-features" aria-hidden="true">
				<div class="starter-feature-pill">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					aeCRM
				</div>
				<div class="starter-feature-pill">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Accounting &amp; Invoicing
				</div>
				<div class="starter-feature-pill">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Compliance Guides — All 50 States
				</div>
				<div class="starter-feature-pill">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Business Registration Guides
				</div>
			</div>
		</div>
	</section>

	<!-- ARIA INTELLIGENCE REPORTS -->
	<section class="aria-intel-section" aria-label="Intelligence Reports by Aria">
	<style>
	.aria-intel-section{padding:clamp(64px,8vw,112px) clamp(1.5rem,5vw,4rem);background:var(--bg-surface);border-top:1px solid var(--border);}
	.aria-intel-inner{max-width:var(--max-width);margin:0 auto;}
	.aria-intel-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:3rem;gap:2rem;flex-wrap:wrap;}
	.aria-intel-badge{display:inline-flex;align-items:center;gap:.5rem;background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 30%,transparent);border-radius:100px;padding:.35rem .9rem;font-size:.78rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--accent);margin-bottom:.75rem;}
	.aria-intel-badge::before{content:'';width:7px;height:7px;border-radius:50%;background:var(--accent);animation:fp-aria-pulse 2s ease-in-out infinite;}
	@keyframes fp-aria-pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
	.aria-intel-title{font-family:var(--font-editorial);font-size:clamp(2rem,3.5vw,3rem);font-weight:400;color:var(--text);line-height:1.1;margin:.5rem 0 1rem;}
	.aria-intel-title em{font-style:italic;color:var(--accent);}
	.aria-intel-sub{font-size:.95rem;color:var(--text-muted);line-height:1.7;max-width:44ch;margin:0;}
	.aria-card{background:var(--bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:border-color .2s,transform .2s;display:flex;flex-direction:column;}
	.aria-card:hover{border-color:var(--accent);transform:translateY(-2px);}
	.aria-card__img{aspect-ratio:16/9;background:color-mix(in srgb,var(--accent) 5%,var(--bg-surface));overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
	.aria-card__img img{width:100%;height:100%;object-fit:cover;display:block;}
	.aria-card__body{padding:1.25rem;flex:1;display:flex;flex-direction:column;}
	.aria-card__cat{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--accent);margin-bottom:.5rem;}
	.aria-card__title{font-size:1rem;font-weight:600;color:var(--text);line-height:1.35;margin-bottom:.6rem;}
	.aria-card__desc{font-size:.875rem;color:var(--text-muted);line-height:1.6;flex:1;}
	.aria-card__footer{margin-top:1rem;display:flex;align-items:center;justify-content:space-between;}
	.aria-card__price{font-size:1.1rem;font-weight:700;color:var(--text);}
	.aria-card__btn{font-size:.8rem;font-weight:600;color:var(--accent);text-decoration:none;border:1px solid var(--accent);border-radius:6px;padding:.35rem .8rem;transition:background .2s,color .2s;}
	.aria-card__btn:hover{background:var(--accent);color:#fff;}
	.aria-intel-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;}
	.aria-intel-skeleton{background:var(--bg);border:1px solid var(--border);border-radius:12px;height:280px;animation:fp-shimmer 1.5s infinite;}
	@keyframes fp-shimmer{0%{opacity:.4}50%{opacity:.9}100%{opacity:.4}}
	@media(max-width:640px){.aria-intel-header{flex-direction:column;align-items:flex-start;}}
	</style>
	<div class="aria-intel-inner">
		<div class="aria-intel-header">
			<div>
				<div class="aria-intel-badge">Built by Aria &middot; AI Research Agent</div>
				<h2 class="aria-intel-title">Market intelligence,<br><em>ready to deploy.</em></h2>
				<p class="aria-intel-sub">Aria continuously researches your industry, synthesizes competitive data, and publishes actionable reports &mdash; available instantly.</p>
			</div>
			<a href="<?php echo esc_url( home_url( '/aria-marketplace/' ) ); ?>" class="btn btn--ghost" style="white-space:nowrap;flex-shrink:0;">Browse all reports &rarr;</a>
		</div>
		<div class="aria-intel-grid" id="fp-aria-grid">
			<div class="aria-intel-skeleton"></div>
			<div class="aria-intel-skeleton"></div>
			<div class="aria-intel-skeleton"></div>
		</div>
	</div>
	<script>
	(function(){
		var grid=document.getElementById('fp-aria-grid');
		if(!grid)return;
		function escH(s){var d=document.createElement('div');d.textContent=String(s||'');return d.innerHTML;}
		fetch('<?php echo esc_url( rest_url( 'aesoftwares/v1/aria/products' ) ); ?>')
			.then(function(r){return r.ok?r.json():Promise.reject(r.status);})
			.then(function(data){
				var products=Array.isArray(data)?data:(data.products||[]);
				products=products.slice(0,3);
				if(!products.length){grid.innerHTML='';return;}
				grid.innerHTML=products.map(function(p){
					var imgHtml=p.image
						?'<img src="'+escH(p.image)+'" alt="'+escH(p.title)+'" loading="lazy">'
						:'<svg width="36" height="36" viewBox="0 0 36 36" fill="none" style="opacity:.25"><rect x="4" y="6" width="28" height="24" rx="3" stroke="currentColor" stroke-width="1.5"/><path d="M4 22l9-8 6 6 5-4 9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
					var price=parseFloat(p.price)||0;
					var desc=(p.description||'').substring(0,110);
					if(desc.length===110)desc+='…';
					return '<div class="aria-card">'
						+'<div class="aria-card__img">'+imgHtml+'</div>'
						+'<div class="aria-card__body">'
						+'<div class="aria-card__cat">'+escH(p.category||'Intelligence Report')+'</div>'
						+'<h3 class="aria-card__title">'+escH(p.title)+'</h3>'
						+'<p class="aria-card__desc">'+escH(desc)+'</p>'
						+'<div class="aria-card__footer">'
						+'<span class="aria-card__price">$'+price.toFixed(2)+'</span>'
						+'<a href="'+escH(p.url||'#')+'" class="aria-card__btn">View Report</a>'
						+'</div></div></div>';
				}).join('');
			})
			.catch(function(){grid.innerHTML='<p style="color:var(--text-muted);font-size:.9rem;grid-column:1/-1;opacity:.6">Reports loading&hellip;</p>';});
	})();
	</script>
	</section>

	<!-- PROCESS -->
	<section class="process-section" id="process" aria-label="Our process">
		<div class="process-header">
			<span class="process-eyebrow">How we work</span>
			<h2 class="process-title">From onboarding<br>to results.</h2>
		</div>
		<div class="process-track">

			<div class="process-step js-fade" style="--delay:0s">
				<div class="process-step__icon" aria-hidden="true">
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
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 9L10 3L17 9V17H13V13H7V17H3V9Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
						</svg>
					</span>
					<span class="industry-chip__name">Mortgage &amp; Lending</span>
					<span class="industry-chip__regs">RESPA · TILA · HMDA</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#insurance" class="industry-chip js-fade" style="--delay:0.08s">
					<span class="industry-chip__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 2L3 5V10C3 13.87 6.08 17.5 10 18.5C13.92 17.5 17 13.87 17 10V5L10 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
						</svg>
					</span>
					<span class="industry-chip__name">Insurance</span>
					<span class="industry-chip__regs">State filings · Claims · Audit</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>#healthcare" class="industry-chip js-fade" style="--delay:0.16s">
					<span class="industry-chip__icon" aria-hidden="true">
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