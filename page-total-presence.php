<?php
/**
 * Template Name: Total Presence
 *
 * Product page: Total Presence.
 * Copy comes from the Stage 1 product page brief (product-pages-hydrogen-20261122.md),
 * reviewed in Stage 2. Layout reuses existing theme classes only (page-hero, v5-services,
 * op-related, op-steps, ae-pricing, ae-offer, ae-terms, ae-faq), styled by
 * assets/refresh/ae-v5.css. No new classes, no gold, no page specific scripts.
 *
 * Hero image slot: assets/img/services/total-presence.jpg. It renders only once that file exists,
 * so the page never shows a broken image while the photo is being produced.
 *
 * Self contained: every string and price on this page lives in this file.
 *
 * @package AE_Softwares
 */

get_header();

$ae_book_url    = 'https://calendar.app.google/oWNCzxkmP43DBkhbA';
$ae_buy_url     = ae_checkout_url( 'total-presence' );
$ae_pricing_url = home_url( '/pricing/' );
$ae_slug        = 'total-presence';
$ae_img_rel     = 'assets/img/services/' . $ae_slug . '.jpg';
$ae_has_img     = file_exists( get_theme_file_path( $ae_img_rel ) );

$ae_page = [
	'name'        => 'Total Presence',
	'headline'    => 'Combine AI social message handling and Google Business Profile management in one bundle.',
	'description' => 'Social Front Desk plus ongoing Google Business Profile management together, so local searches and social inquiries are both covered.',
	'price'       => '$199 per month · Founding rate $149 per month for the first 3 clients for 3 months, then $199 per month',
	'fits'        => 'Local and regional businesses with 5 to 50 employees that generate inquiries from both Google Maps searches and social media channels, and want both front doors covered cleanly under one monthly plan.',
	'terms'       => 'The standard Total Presence rate is $199 per month. The first 3 clients who enroll pay the founding rate of $149 per month for their first 3 months, then $199 per month from month 4 onward.',
	'cta_title'   => 'Cover both your Google Business Profile and your social inbox under one simple plan.',
	'cta_text'    => 'Start online now, or book a discovery call to claim a founding spot.',
];

// Hero buttons. The first is the solid button, the rest are text links.
$ae_hero_actions = [
	[ 'label' => 'Get started', 'url' => $ae_buy_url ],
	[ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	[ 'label' => 'See full pricing', 'url' => home_url( '/pricing/' ) ],
];

$ae_includes = [
	[
		'title' => 'Social Front Desk',
		'text' => 'Everything in Social Front Desk: prompt AI responses to incoming social messages and comments.',
	],
	[
		'title' => 'Google Business Profile',
		'text' => 'Full Google Business Profile management, review responses, and profile updates.',
	],
	[
		'title' => 'Unified coverage',
		'text' => 'Coverage across both local search discovery and social inbox inquiries.',
	],
	[
		'title' => 'One bundle',
		'text' => 'A single monthly bundle priced below purchasing both services separately.',
	],
];

$ae_steps = [
	[
		'title' => 'Connect your profiles',
		'text' => 'We link your Google Business Profile and your business social accounts during onboarding.',
	],
	[
		'title' => 'Align your business info',
		'text' => 'We verify your hours, services, review response rules, and AI message scripts so every channel gives consistent answers.',
	],
	[
		'title' => 'Run both channels daily',
		'text' => 'We manage your Google profile and reviews while Social Front Desk responds to incoming social messages and captures leads.',
	],
];

$ae_cards = [
	[
		'name'     => 'Total Presence',
		'price'    => '$199',
		'cadence'  => 'per month · standard rate',
		'pitch'    => 'Social Front Desk and Google Business Profile management in one plan.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Get started', 'url' => $ae_buy_url ],
	],
	[
		'name'     => 'Founding client rate',
		'price'    => '$149',
		'cadence'  => 'per month for your first 3 months · then $199 per month',
		'pitch'    => 'Open to the first 3 businesses that enroll in Total Presence.',
		'points'   => [ '$149 per month for months 1 to 3', '$199 per month from month 4 onward', 'Limited to the first 3 clients' ],
		'featured' => true,
		'flag'     => 'Founding rate',
		'cta'      => [ 'label' => 'Claim a founding spot', 'url' => $ae_buy_url ],
	],
];

$ae_faq = [
	[
		'q' => 'How much does Total Presence save compared to buying both services separately?',
		'a' => 'Purchased separately, Social Front Desk ($199 per month) and Online Presence Management ($199 per month) total $398 per month. Total Presence bundles Social Front Desk and Google Business Profile management at $199 per month, or $149 per month for the first 3 founding clients for 3 months.',
	],
	[
		'q' => 'How do we qualify for the $149 founding client rate?',
		'a' => 'The founding rate is open to the first 3 businesses that enroll in Total Presence. You receive the $149 monthly rate for your first 3 months, moving to $199 per month in month 4.',
	],
	[
		'q' => 'Can we add Total Presence if we already use Front Desk?',
		'a' => 'Yes. Total Presence pairs cleanly with our phone answering plans so your calls, social messages, and Google Business Profile are all covered.',
	],
];

// One or two cards sit best in the two column grid, and four cards read as two rows of two.
$ae_offer_grid = ( 3 === count( $ae_cards ) || count( $ae_cards ) > 4 ) ? 'ae-offers' : 'ae-offers ae-offers--two';
?>

<section class="page-hero op-hero" aria-labelledby="pp-hero-title">
	<p class="page-hero__eyebrow"><?php echo esc_html( $ae_page['name'] ); ?></p>
	<h1 class="page-hero__headline" id="pp-hero-title"><?php echo esc_html( $ae_page['headline'] ); ?></h1>
	<p class="page-hero__subtitle"><?php echo esc_html( $ae_page['description'] ); ?></p>
	<p class="v5-lead" data-reveal><strong><?php echo esc_html( $ae_page['price'] ); ?></strong></p>
	<div class="op-hero__actions" data-reveal>
		<?php foreach ( $ae_hero_actions as $i => $a ) : ?>
			<a class="<?php echo 0 === $i ? 'v5-button' : 'v5-link'; ?>" href="<?php echo esc_url( $a['url'] ); ?>"><?php echo esc_html( $a['label'] ); ?><?php echo 0 === $i ? '' : ' &rarr;'; ?></a>
		<?php endforeach; ?>
	</div>
</section>

<?php if ( $ae_has_img ) : ?>
<div class="ae-wrap">
	<div class="ae-media v5-card__media">
		<img src="<?php echo esc_url( get_theme_file_uri( $ae_img_rel ) ); ?>" alt="" width="1600" height="1000" fetchpriority="high" decoding="async">
	</div>
</div>
<?php endif; ?>

<!-- What it includes -->
<section class="v5-services" aria-labelledby="pp-includes-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="pp-includes-title" data-split>What it includes</h2>
		<span class="v5-label" data-reveal>Both front doors</span>
	</header>
	<ol class="v5-services__list">
		<?php foreach ( $ae_includes as $i => $item ) : ?>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
			<h3 class="v5-service__title"><?php echo esc_html( $item['title'] ); ?></h3>
			<p class="v5-service__text"><?php echo esc_html( $item['text'] ); ?></p>
		</li>
		<?php endforeach; ?>
	</ol>
</section>

<!-- Who it fits -->
<section class="op-related ae-rule" aria-labelledby="pp-fits-title">
	<p class="v5-label" data-reveal>Who it fits</p>
	<h2 class="op-related__title" id="pp-fits-title" data-split>Built for teams like yours.</h2>
	<p class="op-related__text" data-reveal><?php echo esc_html( $ae_page['fits'] ); ?></p>
</section>

<!-- How it works -->
<section class="op-steps ae-rule" aria-labelledby="pp-steps-title">
	<h2 class="v5-section-title" id="pp-steps-title" data-split>How it works</h2>
	<ol class="op-steps__list">
		<?php foreach ( $ae_steps as $i => $step ) : ?>
		<li data-reveal><span class="v5-label"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step['title'] ); ?></h3><p><?php echo esc_html( $step['text'] ); ?></p></li>
		<?php endforeach; ?>
	</ol>
</section>

<!-- Pricing -->
<section class="ae-pricing" id="pricing" aria-labelledby="pp-pricing-title">
	<header class="v5-section-head">
		<p class="v5-label" data-reveal>Monthly</p>
		<h2 class="v5-section-title" id="pp-pricing-title" data-reveal>Pricing</h2>
	</header>
	<div class="<?php echo esc_attr( $ae_offer_grid ); ?>">
		<?php foreach ( $ae_cards as $card ) : ?>
		<article class="ae-offer<?php echo $card['featured'] ? ' ae-offer--featured' : ''; ?>" data-reveal>
			<?php if ( $card['featured'] && '' !== $card['flag'] ) : ?><span class="ae-offer__flag"><?php echo esc_html( $card['flag'] ); ?></span><?php endif; ?>
			<h3 class="ae-offer__name"><?php echo esc_html( $card['name'] ); ?></h3>
			<p class="ae-offer__price"><?php echo esc_html( $card['price'] ); ?></p>
			<p class="ae-offer__cadence"><?php echo esc_html( $card['cadence'] ); ?></p>
			<p class="ae-offer__pitch"><?php echo esc_html( $card['pitch'] ); ?></p>
			<?php if ( ! empty( $card['points'] ) ) : ?>
			<ul class="ae-offer__points">
				<?php foreach ( $card['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<a class="<?php echo $card['featured'] ? 'v5-button' : 'ae-offer__cta'; ?>" href="<?php echo esc_url( $card['cta']['url'] ); ?>"><?php echo esc_html( $card['cta']['label'] ); ?></a>
		</article>
		<?php endforeach; ?>
	</div>
</section>

<section class="ae-pricing ae-terms ae-alt" aria-label="Terms">
	<div class="ae-terms__inner">
		<p class="v5-label" data-reveal>The terms, in plain English</p>
		<p class="ae-terms__text" data-reveal><?php echo esc_html( $ae_page['terms'] ); ?></p>
	</div>
</section>

<!-- FAQ: native details and summary, so it works without page specific scripts -->
<section class="op-faq ae-rule" aria-labelledby="pp-faq-title">
	<h2 class="v5-section-title" id="pp-faq-title" data-split>Questions</h2>
	<div class="ae-faq">
		<?php foreach ( $ae_faq as $f ) : ?>
		<details class="ae-faq__item" data-reveal>
			<summary><?php echo esc_html( $f['q'] ); ?></summary>
			<p><?php echo esc_html( $f['a'] ); ?></p>
		</details>
		<?php endforeach; ?>
	</div>
</section>

<!-- Call to action -->
<section class="op-related ae-rule" aria-labelledby="pp-cta-title">
	<p class="v5-label" data-reveal>Next step</p>
	<h2 class="op-related__title" id="pp-cta-title" data-split><?php echo esc_html( $ae_page['cta_title'] ); ?></h2>
	<p class="op-related__text" data-reveal><?php echo esc_html( $ae_page['cta_text'] ); ?></p>
	<div class="op-hero__actions" data-reveal>
			<a class="v5-button" href="<?php echo esc_url( $ae_buy_url ); ?>">Get started</a>
		<a class="v5-link" href="<?php echo esc_url( $ae_book_url ); ?>">Book a discovery call &rarr;</a>
		<a class="v5-link" href="<?php echo esc_url( $ae_pricing_url ); ?>">View full pricing &rarr;</a>
	</div>
</section>

<script type="application/ld+json">
<?php
$ae_graph = [];
$ae_service = [
	'@type'       => 'Service',
	'name'        => $ae_page['name'],
	'serviceType' => $ae_page['name'],
	'description' => $ae_page['description'],
	'url'         => get_permalink(),
	'provider'    => [ '@id' => home_url( '/#localbusiness' ) ],
];
if ( function_exists( 'aesoftwares_service_area' ) ) {
	$ae_service['areaServed'] = aesoftwares_service_area();
}
$ae_graph[] = $ae_service;
$ae_graph[] = [
	'@type'      => 'FAQPage',
	'mainEntity' => array_map(
		function ( $f ) {
			return [
				'@type'          => 'Question',
				'name'           => $f['q'],
				'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $f['a'] ],
			];
		},
		$ae_faq
	),
];
echo wp_json_encode(
	[
		'@context' => 'https://schema.org',
		'@graph'   => $ae_graph,
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
