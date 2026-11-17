<?php
/**
 * Template Name: Business Starter
 * The Business Starter Package page: $50/month subscription.
 *
 * @package AE_Softwares
 */
add_action( 'wp_head', function () { ?>
<style>
/* Business Starter page */
.starter-hero { text-align: center; }
.starter-price-badge {
	display: inline-flex;
	align-items: baseline;
	gap: 0.2rem;
	background: var(--accent);
	color: var(--bg);
	border-radius: 100px;
	padding: 0.45rem 1.2rem 0.45rem 1rem;
	font-weight: 700;
	font-size: 0.8rem;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin-bottom: 1.75rem;
}
.starter-price-badge strong { font-size: 1.15rem; }

/* Feature sections */
.starter-features-section {
	padding: clamp(48px, 6vw, 96px) clamp(1.5rem, 5vw, 4rem);
	max-width: var(--max-width);
	margin: 0 auto;
}
.starter-features-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	gap: 1.5rem;
	margin-top: 3rem;
}
.starter-feature-card {
	background: var(--bg-surface);
	border: 1px solid var(--border);
	border-radius: 12px;
	padding: 2rem;
	transition: border-color 0.2s ease;
}
.starter-feature-card:hover { border-color: var(--accent); }
.starter-feature-card__icon {
	width: 44px;
	height: 44px;
	background: rgb(var(--gold-rgb) / .1);
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	margin-bottom: 1.25rem;
	color: var(--accent);
}
.starter-feature-card__title {
	font-family: var(--font-editorial);
	font-size: 1.35rem;
	font-weight: 400;
	color: var(--text);
	margin: 0 0 0.75rem;
}
.starter-feature-card__desc {
	font-size: 0.95rem;
	color: var(--text-muted);
	line-height: 1.7;
	margin: 0 0 1.25rem;
}
.starter-feature-card__list {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: 0.5rem;
}
.starter-feature-card__list li {
	font-size: 0.88rem;
	color: var(--text-muted);
	padding-left: 1.2rem;
	position: relative;
}
.starter-feature-card__list li::before {
	content: '';
	position: absolute;
	left: 0;
	top: 0.45em;
	width: 5px;
	height: 5px;
	background: var(--accent);
	border-radius: 50%;
}

/* Pricing card */
.starter-pricing-section {
	padding: clamp(48px, 6vw, 96px) clamp(1.5rem, 5vw, 4rem);
	background: var(--bg-surface);
	border-top: 1px solid var(--border);
	border-bottom: 1px solid var(--border);
}
.starter-pricing-inner {
	max-width: 560px;
	margin: 0 auto;
	text-align: center;
}
.starter-pricing-card {
	background: var(--bg);
	border: 1px solid var(--accent);
	border-radius: 16px;
	padding: 2.5rem 2rem;
	margin-top: 2.5rem;
	position: relative;
	overflow: hidden;
}
.starter-pricing-card::before {
	content: '';
	position: absolute;
	inset: 0;
	background: radial-gradient(ellipse at top center, rgb(var(--gold-rgb) / .06) 0%, transparent 70%);
	pointer-events: none;
}
.starter-pricing-card__badge {
	display: inline-block;
	background: var(--accent);
	color: var(--bg);
	font-size: 0.7rem;
	font-weight: 700;
	letter-spacing: 0.1em;
	text-transform: uppercase;
	padding: 0.3rem 0.8rem;
	border-radius: 100px;
	margin-bottom: 1.25rem;
}
.starter-pricing-card__price {
	font-family: var(--font-editorial);
	font-size: clamp(3.5rem, 10vw, 5.5rem);
	font-weight: 400;
	color: var(--text);
	line-height: 1;
	margin-bottom: 0.25rem;
}
.starter-pricing-card__price span {
	font-size: 1.5rem;
	vertical-align: super;
	color: var(--accent);
}
.starter-pricing-card__period {
	font-size: 0.9rem;
	color: var(--text-muted);
	margin-bottom: 2rem;
}
.starter-pricing-card__includes {
	list-style: none;
	margin: 0 0 2rem;
	padding: 0;
	text-align: left;
	display: flex;
	flex-direction: column;
	gap: 0.8rem;
}
.starter-pricing-card__includes li {
	display: flex;
	align-items: flex-start;
	gap: 0.65rem;
	font-size: 0.95rem;
	color: var(--text-muted);
}
.starter-pricing-card__includes li svg { flex-shrink: 0; color: var(--accent); margin-top: 0.1em; }
.starter-pricing-note {
	font-size: 0.8rem;
	color: var(--text-muted);
	margin-top: 1.25rem;
	opacity: 0.7;
}

/* States section */
.starter-states-section {
	padding: clamp(48px, 6vw, 96px) clamp(1.5rem, 5vw, 4rem);
	max-width: var(--max-width);
	margin: 0 auto;
}
.starter-states-intro { max-width: 600px; }
.starter-states-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 0.75rem;
	margin-top: 2.5rem;
}
.starter-state-item {
	background: var(--bg-surface);
	border: 1px solid var(--border);
	border-radius: 8px;
	padding: 0.75rem 1rem;
	font-size: 0.88rem;
	color: var(--text-muted);
	display: flex;
	align-items: center;
	gap: 0.5rem;
}
.starter-state-item svg { color: var(--accent); flex-shrink: 0; }

/* FAQ section */
.starter-faq-section {
	padding: clamp(48px, 6vw, 96px) clamp(1.5rem, 5vw, 4rem);
	max-width: 720px;
	margin: 0 auto;
}
.starter-faq-item {
	border-top: 1px solid var(--border);
	padding: 1.5rem 0;
}
.starter-faq-item:last-child { border-bottom: 1px solid var(--border); }
.starter-faq-q {
	font-size: 1rem;
	font-weight: 600;
	color: var(--text);
	margin: 0 0 0.6rem;
}
.starter-faq-a {
	font-size: 0.95rem;
	color: var(--text-muted);
	line-height: 1.7;
	margin: 0;
}
</style>
<?php }, 20 );
get_header(); ?>

<div class="page-hero starter-hero">
	<div class="page-hero__eyebrow">Business Starter Package</div>
	<h1 class="page-hero__headline">The essential stack<br><em>for every</em><br>growing business.</h1>
	<p class="page-hero__subtitle">One subscription. A CRM, invoicing, and business registration guides for every U.S. state. Starting at <strong style="color:var(--accent)">$50/month</strong>.</p>
	<a href="#pricing" class="btn btn--primary" style="margin-top:2rem;display:inline-block;">Get started for $50/mo</a>
</div>

<!-- FEATURES -->
<section class="starter-features-section" aria-label="Package features">
	<div class="inner-section-header">
		<span class="section-eyebrow">What's included</span>
		<h2 class="section-title">Three tools.<br>One affordable plan.</h2>
	</div>
	<div class="starter-features-grid">

		<!-- aeCRM -->
		<div class="starter-feature-card js-fade" style="--delay:0s">
			<div class="starter-feature-card__icon" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="9" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/>
					<path d="M3 18c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
					<circle cx="17" cy="10" r="2.5" stroke="currentColor" stroke-width="1.3" opacity="0.6"/>
					<path d="M14 18c0-2.21 1.343-4 3-4s3 1.79 3 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" opacity="0.6"/>
				</svg>
			</div>
			<h3 class="starter-feature-card__title">aeCRM</h3>
			<p class="starter-feature-card__desc">Manage your leads, clients, and follow ups in one clean dashboard. Built for small teams that need clarity without complexity.</p>
			<ul class="starter-feature-card__list">
				<li>Contact &amp; lead management</li>
				<li>Pipeline tracking</li>
				<li>Follow up reminders &amp; notes</li>
				<li>Client communication log</li>
				<li>Task &amp; activity management</li>
			</ul>
		</div>

		<!-- Accounting -->
		<div class="starter-feature-card js-fade" style="--delay:0.1s">
			<div class="starter-feature-card__icon" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.4"/>
					<line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
					<line x1="8" y1="14" x2="11" y2="14" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.5"/>
					<line x1="8" y1="17" x2="13" y2="17" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity="0.35"/>
				</svg>
			</div>
			<h3 class="starter-feature-card__title">Accounting &amp; Invoicing</h3>
			<p class="starter-feature-card__desc">Track income, expenses, and send professional invoices without paying for a bloated accounting suite you'll never fully use.</p>
			<ul class="starter-feature-card__list">
				<li>Income &amp; expense tracking</li>
				<li>Professional invoice generation</li>
				<li>Payment status tracking</li>
				<li>Basic financial reports &amp; P&amp;L</li>
				<li>Expense categories</li>
			</ul>
		</div>

		<!-- Compliance guides -->
		<div class="starter-feature-card js-fade" style="--delay:0.2s">
			<div class="starter-feature-card__icon" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2L4 5.5V11C4 15.4 7.4 19.5 12 21C16.6 19.5 20 15.4 20 11V5.5L12 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
					<path d="M8.5 11.5l2.5 2.5 4-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<h3 class="starter-feature-card__title">Compliance &amp; Registration Guides</h3>
			<p class="starter-feature-card__desc">State specific guides covering how to register your business and what annual requirements to look for. General information only, not legal advice. Check with your own counsel.</p>
			<ul class="starter-feature-card__list">
				<li>Business registration guides for all 50 states</li>
				<li>LLC, S Corp, and sole proprietor guidance</li>
				<li>Annual report &amp; renewal reminders</li>
				<li>Registered agent requirements by state</li>
				<li>EIN registration walkthrough</li>
			</ul>
		</div>

	</div>
</section>

<!-- PRICING -->
<section class="starter-pricing-section" id="pricing" aria-label="Pricing">
	<div class="starter-pricing-inner">
		<span class="section-eyebrow">Simple pricing</span>
		<h2 class="section-title">No surprises.<br>Just results.</h2>
		<div class="starter-pricing-card">
			<div class="starter-pricing-card__badge">Business Starter</div>
			<div class="starter-pricing-card__price"><span>$</span>50</div>
			<div class="starter-pricing-card__period">per month · cancel anytime</div>
			<ul class="starter-pricing-card__includes">
				<li>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					aeCRM, full access
				</li>
				<li>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Accounting &amp; invoicing tools
				</li>
				<li>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Compliance guides for all 50 U.S. states
				</li>
				<li>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Business registration guides &amp; checklists
				</li>
				<li>
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Email support &amp; onboarding walkthrough
				</li>
			</ul>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>?plan=starter" class="btn btn--primary" style="width:100%;text-align:center;display:block;">Start today for $50/mo</a>
			<p class="starter-pricing-note">No long term commitment. Cancel anytime. Billed monthly.</p>
		</div>
	</div>
</section>

<!-- COMPLIANCE COVERAGE -->
<section class="starter-states-section" aria-label="State compliance coverage">
	<div class="starter-states-intro">
		<span class="section-eyebrow">Coverage</span>
		<h2 class="section-title">Guides for<br>every U.S. state.</h2>
		<p style="font-size:1rem;color:var(--text-muted);line-height:1.75;margin-top:1rem;">Whether you're registering an LLC in Texas or renewing your annual report in Delaware, the guides walk you through the steps for your state. General information only, not legal advice.</p>
	</div>
	<div class="starter-states-grid js-fade" style="--delay:0s">
		<?php
		$states = ['Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware',
		           'Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky',
		           'Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi',
		           'Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico',
		           'New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania',
		           'Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont',
		           'Virginia','Washington','West Virginia','Wisconsin','Wyoming'];
		foreach ( $states as $state ) :
		?>
		<div class="starter-state-item">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7l3.5 3.5 6.5-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<?php echo esc_html( $state ); ?>
		</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- FAQ -->
<section class="starter-faq-section" aria-label="Frequently asked questions">
	<div class="inner-section-header" style="margin-bottom:0">
		<span class="section-eyebrow">FAQ</span>
		<h2 class="section-title">Common questions.</h2>
	</div>
	<div class="starter-faq-item js-fade" style="--delay:0s">
		<p class="starter-faq-q">What's included in the $50/month plan?</p>
		<p class="starter-faq-a">The Business Starter Package includes full access to aeCRM, accounting and invoicing tools, and compliance &amp; registration guides for all 50 U.S. states.</p>
	</div>
	<div class="starter-faq-item js-fade" style="--delay:0.08s">
		<p class="starter-faq-q">Do I need to sign a long term contract?</p>
		<p class="starter-faq-a">No. The Business Starter Package is billed month to month. You can cancel anytime, with no minimum commitment.</p>
	</div>
	<div class="starter-faq-item js-fade" style="--delay:0.16s">
		<p class="starter-faq-q">Are the compliance guides kept up to date?</p>
		<p class="starter-faq-a">I update the guides as I learn of changes, but state rules change, so check the state's own website before you file.</p>
	</div>
	<div class="starter-faq-item js-fade" style="--delay:0.24s">
		<p class="starter-faq-q">Can I upgrade to a full automation or consulting engagement?</p>
		<p class="starter-faq-a">Yes. The Starter Package is a foundation. If you later want websites, follow up automation, or a custom build, contact me and I will put together a proposal.</p>
	</div>
	<div class="starter-faq-item js-fade" style="--delay:0.32s">
		<p class="starter-faq-q">Is support included?</p>
		<p class="starter-faq-a">Yes. Email support and an onboarding walkthrough are included with every plan.</p>
	</div>
</section>

<!-- CTA -->
<section id="contact"><ae-cta></ae-cta></section>

<?php get_footer(); ?>