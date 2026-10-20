<?php
/**
 * Template Name: Checkout
 *
 * Plan checkout for social media management: every plan links to its Stripe
 * payment page, with a short explanation of what happens next. Includes
 * WebPage + FAQ structured data.
 *
 * @package AE_Softwares
 */

get_header();

$ae_checkout_faq = [
	[
		'q' => 'What does the setup fee cover?',
		'a' => 'Setting up your accounts (Meta, Claude and Canva, in your business name), connecting your social media accounts, gathering your brand assets, the voice interview, and building your first content calendar.',
	],
	[
		'q' => 'How do discount codes work?',
		'a' => 'If you have a code, enter it in the code field on the secure payment page before you pay. The discount comes off your first payment. One code per signup.',
	],
	[
		'q' => 'When am I charged?',
		'a' => 'Your card is charged today for the first month plus the one time setup fee. After that, the monthly price is charged on the same day each month until you cancel.',
	],
	[
		'q' => 'What happens after I pay?',
		'a' => 'We contact you within one business day to book your kickoff call. Account setup starts right after your payment, and your first content calendar follows the kickoff call.',
	],
	[
		'q' => 'Can I cancel?',
		'a' => 'Yes. Plans run a 3 month minimum, then month to month. Give us 30 days notice and we will hand over your content files and remove our access to your accounts.',
	],
];
?>

<section class="page-hero op-hero">
	<p class="page-hero__eyebrow">[ Checkout ]</p>
	<h1 class="page-hero__headline">Pick your plan. Check out in minutes.</h1>
	<p class="page-hero__subtitle">Every plan is billed monthly by card through Stripe, our payment provider. Your first payment includes the one time setup fee for your plan, then it is just the monthly price. If you have a discount code, you will enter it on the secure payment page before you pay.</p>
</section>

<!-- Plans for businesses -->
<section class="v5-services" id="plans" aria-labelledby="co-business-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="co-business-title" data-split>Plans for businesses</h2>
		<span class="v5-label" data-reveal>[ Monthly ]</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Social Front Desk</h3>
			<div class="v5-service__text">
				<p><strong>$199 a month plus a one time $75 setup.</strong> Posting handled for you with light planning, so your business shows up consistently.</p>
				<p>First 3 clients: $149 a month for 3 months.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sYbJ1d2w2XcakXfFvabK0h">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Social Management</h3>
			<div class="v5-service__text">
				<p><strong>$799 a month plus a one time $300 setup.</strong> Up to 4 platforms, 12 to 16 posts a month, strategy and calendar, replies handled, 1 email campaign, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sY14n9QkapE2Sv2SJabK0b">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Social Management Growth</h3>
			<div class="v5-service__text">
				<p><strong>$1,299 a month plus a one time $495 setup.</strong> Up to 6 platforms, 20 to 24 posts, 8 short form videos, 2 email campaigns, priority turnaround.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/3cIfZh3rWcxMakXalbabK0i">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Social Management Scale</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 8 platforms including TikTok and YouTube, 28 to 32 posts, 12 short form videos, daily engagement checks.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/8x2fZhaUoapEdx9gJzabK0j">Start now</a>
		</li>
	</ol>
</section>

<!-- Plans for creators -->
<section class="v5-services" aria-labelledby="co-creator-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="co-creator-title" data-split>Plans for creators</h2>
		<span class="v5-label" data-reveal>[ Influencers and creators ]</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Creator Launch</h3>
			<div class="v5-service__text">
				<p><strong>$499 a month plus a one time $195 setup.</strong> 3 platforms, 12 short form videos a month cross posted, captions and hashtags per platform.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/eVq28r4w0gO2gJlctjabK0k">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Creator Growth</h3>
			<div class="v5-service__text">
				<p><strong>$899 a month plus a one time $345 setup.</strong> Up to 5 platforms, 20 short form videos, comment handling on business days, profile polish.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/6oUaEX5A48hwct578ZabK0l">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Creator Authority</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 6 platforms, 30 short form videos, daily engagement checks, sponsor inquiries flagged with drafted replies.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sY28r9QkdBQ2Sv2SJabK0m">Start now</a>
		</li>
	</ol>
</section>

<!-- Discount codes -->
<section class="op-related ae-rule" aria-labelledby="co-code-title">
	<p class="v5-label" data-reveal>[ Discount codes ]</p>
	<h2 class="op-related__title" id="co-code-title" data-split>Have a discount code?</h2>
	<p class="op-related__text" data-reveal>Every plan above accepts discount codes. When you reach the secure payment page, enter your code in the code field before you pay, and the discount comes off your first payment right away. Codes are shared with eligible clients and partners directly, so if you were given one, this is where it works.</p>
</section>

<!-- How it works -->
<section class="op-steps ae-rule" aria-labelledby="co-steps-title">
	<h2 class="v5-section-title" id="co-steps-title" data-split>How it works</h2>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">[ 01 ]</span><h3>Pay securely</h3><p>Choose a plan and pay on the Stripe payment page. Today you pay the first month plus the one time setup fee.</p></li>
		<li data-reveal><span class="v5-label">[ 02 ]</span><h3>We set up your accounts</h3><p>After your payment, we create your Meta, Claude and Canva accounts in your business name and connect your social media accounts. The accounts are yours.</p></li>
		<li data-reveal><span class="v5-label">[ 03 ]</span><h3>Kickoff call</h3><p>A short call to confirm your goals and offers, gather your brand assets, and record a 30 to 45 minute voice interview so the content sounds like you.</p></li>
		<li data-reveal><span class="v5-label">[ 04 ]</span><h3>First calendar for approval</h3><p>You approve the first month of content before anything is published. Nothing unapproved ever goes out.</p></li>
	</ol>
</section>

<!-- Other services -->
<section class="op-related ae-rule" aria-labelledby="co-other-title">
	<p class="v5-label" data-reveal>[ Other services ]</p>
	<h2 class="op-related__title" id="co-other-title" data-split>Also from AE:</h2>
	<p class="op-related__text" data-reveal><a class="v5-link" href="https://buy.stripe.com/eVqaEX0fKfJY0KndxnabK04">Front Desk</a> $99 a month</p>
	<p class="op-related__text" data-reveal><a class="v5-link" href="https://buy.stripe.com/6oUeVd9QkapEct52SJabK01">Always-On Front Desk</a> $250 a month</p>
	<p class="op-related__text" data-reveal><a class="v5-link" href="https://buy.stripe.com/8x25kDfaEfJYdx964VabK02">Online Presence Management</a> $49 a month</p>
	<p class="op-related__text" data-reveal><a class="v5-link" href="https://buy.stripe.com/00wbJ15A4dBQgJl8d3abK03">Missed-Lead Audit</a> $250 one time</p>
	<p class="op-related__text" data-reveal><a class="v5-link" href="https://buy.stripe.com/5kQ5kDbYs7dsfFheBrabK0a">AI Setup Guides Subscription</a> $19 a month</p>
	<a class="v5-link op-related__link" href="<?php echo esc_url( home_url( '/social-management/' ) ); ?>" data-reveal>Compare the social plans in detail &rarr;</a>
</section>

<!-- Terms -->
<section class="op-related ae-rule" aria-labelledby="co-terms-title">
	<p class="v5-label" data-reveal>[ Terms ]</p>
	<h2 class="op-related__title" id="co-terms-title" data-split>The plain terms.</h2>
	<p class="op-related__text" data-reveal>Social plans run a 3 month minimum, then month to month with 30 days notice to cancel. The setup fee is charged once, at signup. You own your accounts, your content, and your lists. We never buy followers or engagement. Plan details are on the <a class="v5-link" href="<?php echo esc_url( home_url( '/social-management/' ) ); ?>">Social Media Management page</a>.</p>
</section>

<!-- FAQ -->
<section class="op-faq ae-rule" aria-labelledby="co-faq-title">
	<h2 class="v5-section-title" id="co-faq-title" data-split>Questions</h2>
	<div class="op-faq__list">
		<?php foreach ( $ae_checkout_faq as $f ) : ?>
			<details class="op-faq__item" data-reveal>
				<summary><?php echo esc_html( $f['q'] ); ?></summary>
				<p><?php echo esc_html( $f['a'] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
</section>

<ae-cta></ae-cta>

<script type="application/ld+json">
<?php
echo wp_json_encode(
	[
		'@context' => 'https://schema.org',
		'@graph'   => [
			[
				'@type'       => 'WebPage',
				'name'        => 'Checkout',
				'description' => 'Pick a social media management plan and check out securely through Stripe.',
				'url'         => home_url( '/checkout/' ),
			],
			[
				'@type'      => 'FAQPage',
				'mainEntity' => array_map(
					function ( $f ) {
						return [
							'@type'          => 'Question',
							'name'           => $f['q'],
							'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $f['a'] ],
						];
					},
					$ae_checkout_faq
				),
			],
		],
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
