<?php
/**
 * Template Name: Marketplace
 *
 * Every product we sell, on one page. Each product links to its checkout
 * secure Stripe checkout page (links live in inc/checkout-links.php), where
 * payment is taken by card
 * through our Hostinger Ecommerce plan. Includes WebPage structured data.
 *
 * @package AE_Softwares
 */

get_header();
?>

<section class="page-hero op-hero">
	<p class="page-hero__eyebrow">Marketplace</p>
	<h1 class="page-hero__headline">Everything we sell, in one place.</h1>
	<p class="page-hero__subtitle">Pick a product and pay securely by card on the secure checkout page. Monthly plans bill on the same day each month until you cancel. One time products are a single payment.</p>
</section>

<!-- Phone answering -->
<section class="v5-services" id="phone" aria-labelledby="mp-phone-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="mp-phone-title" data-split>Phone answering</h2>
		<span class="v5-label" data-reveal>Monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Front Desk</h3>
			<div class="v5-service__text">
				<p><strong>$99 a month for up to 60 calls, plus a one time $75 setup.</strong> Your calls answered while you work. No lead goes cold.</p>
				<p>AI answers your calls in your business name, captures the job details, and sends you a summary of every call.</p>
				<p>Need more room? Front Desk is $179 a month for up to 150 calls, or $309 a month for up to 300 calls, each with the same one time $75 setup. Over 300 calls, we add $45 per extra block of 50 calls.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'front-desk' ) ); ?>">Start now</a>
			<a class="v5-link v5-service__more" href="https://calendar.app.google/oWNCzxkmP43DBkhbA">Book a call for 150 or 300 calls</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Always-On Front Desk</h3>
			<div class="v5-service__text">
				<p><strong>$250 a month plus a one time setup, quoted after the audit.</strong> Missed calls answered in seconds, day or night.</p>
				<p>AI answers your calls in your business name, day or night, and rings you for true emergencies.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'always-on-front-desk' ) ); ?>">Start now</a>
		</li>
	</ol>
</section>

<!-- Social media -->
<section class="v5-services" aria-labelledby="mp-social-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="mp-social-title" data-split>Social media</h2>
		<span class="v5-label" data-reveal>Monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Social Front Desk</h3>
			<div class="v5-service__text">
				<p><strong>$199 a month plus a one time $75 setup.</strong> Posting handled for you with light planning, so your business shows up consistently.</p>
				<p>First 3 clients: $149 a month for 3 months, then $199 a month.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'social-front-desk' ) ); ?>">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Social Management</h3>
			<div class="v5-service__text">
				<p><strong>$799 a month plus a one time $300 setup.</strong> Up to 4 platforms, 12 to 16 posts a month, strategy and calendar, replies handled, 1 email campaign, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'social-management' ) ); ?>">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Social Management Growth</h3>
			<div class="v5-service__text">
				<p><strong>$1,299 a month plus a one time $495 setup.</strong> Up to 6 platforms, 20 to 24 posts, 8 short form videos, 2 email campaigns, priority turnaround.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'social-management-growth' ) ); ?>">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Social Management Scale</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 8 platforms including TikTok and YouTube, 28 to 32 posts, 12 short form videos, daily engagement checks.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'social-management-scale' ) ); ?>">Start now</a>
		</li>
	</ol>
</section>

<!-- Creators -->
<section class="v5-services" aria-labelledby="mp-creator-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="mp-creator-title" data-split>For creators</h2>
		<span class="v5-label" data-reveal>Monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Creator Launch</h3>
			<div class="v5-service__text">
				<p><strong>$499 a month plus a one time $195 setup.</strong> 3 platforms, 12 short form videos a month cross posted, captions and hashtags per platform.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'creator-launch' ) ); ?>">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Creator Growth</h3>
			<div class="v5-service__text">
				<p><strong>$899 a month plus a one time $345 setup.</strong> Up to 5 platforms, 20 short form videos, comment handling on business days, profile polish.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'creator-growth' ) ); ?>">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Creator Authority</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 6 platforms, 30 short form videos, daily engagement checks, sponsor inquiries flagged with drafted replies.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'creator-authority' ) ); ?>">Start now</a>
		</li>
	</ol>
</section>

<!-- One time and guides -->
<section class="v5-services" aria-labelledby="mp-onetime-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="mp-onetime-title" data-split>One time and guides</h2>
		<span class="v5-label" data-reveal>Single payment and monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Missed-Lead Audit</h3>
			<div class="v5-service__text">
				<p><strong>$250 one time.</strong> Find out exactly where inquiries leak before you spend money fixing anything.</p>
				<p>We look at where your calls and web inquiries actually go, and we put it in writing and tell you what to fix first.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'missed-lead-audit' ) ); ?>">Buy the audit</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">AI Setup Guides Subscription</h3>
			<div class="v5-service__text">
				<p><strong>$19 a month.</strong> Step by step guides that show you how to set up AI tools for your business, kept current as the tools change.</p>
				<p>Every guide in the library, current edition.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( ae_checkout_url( 'ai-setup-guides-subscription' ) ); ?>">Start the guides</a>
		</li>
	</ol>
</section>

<!-- Online presence -->
<section class="v5-services" aria-labelledby="mp-presence-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="mp-presence-title" data-split>Online presence</h2>
		<span class="v5-label" data-reveal>Monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Online Presence Management</h3>
			<div class="v5-service__text">
				<p><strong>$199 a month.</strong> We keep your Google Business Profile active and accurate, every month.</p>
				<p>Weekly Google Business Profile posts, review requests, and profile upkeep.</p>
			</div>
			<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Talk to us</a>
		</li>
	</ol>
</section>

<!-- How it works -->
<section class="op-steps ae-rule" aria-labelledby="mp-steps-title">
	<h2 class="v5-section-title" id="mp-steps-title" data-split>How buying works</h2>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">01</span><h3>Pick a product</h3><p>Choose the product that fits, then follow its button to the secure checkout page.</p></li>
		<li data-reveal><span class="v5-label">02</span><h3>Pay securely</h3><p>Pay by card on the secure checkout page. Monthly plans charge the first month today.</p></li>
		<li data-reveal><span class="v5-label">03</span><h3>We get started</h3><p>We contact you within one business day to book your kickoff call and start setup.</p></li>
	</ol>
</section>

<!-- Terms -->
<section class="op-related ae-rule" aria-labelledby="mp-terms-title">
	<p class="v5-label" data-reveal>Terms</p>
	<h2 class="op-related__title" id="mp-terms-title" data-split>The plain terms.</h2>
	<p class="op-related__text" data-reveal>Monthly plans run month to month with 30 days notice to cancel, except social plans which run a 3 month minimum first. Setup fees are one time charges. You own your accounts, your content, and your lists. If you have a discount code, enter it on the secure payment page before you pay and it comes off your first payment.</p>
</section>

<ae-cta></ae-cta>

<script type="application/ld+json">
<?php
echo wp_json_encode(
	[
		'@context'    => 'https://schema.org',
		'@type'       => 'WebPage',
		'name'        => 'Marketplace',
		'description' => 'Every product AE Software Solutions sells, on one page. Pick a product and pay securely through our online store.',
		'url'         => home_url( '/marketplace/' ),
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
