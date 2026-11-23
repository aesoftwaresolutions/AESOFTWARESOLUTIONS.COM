<?php
/**
 * Template Name: Front Desk
 *
 * Product page: Front Desk.
 * Copy comes from the Stage 1 product page brief (product-pages-hydrogen-20261122.md),
 * reviewed in Stage 2. Layout reuses existing theme classes only (page-hero, v5-services,
 * op-related, op-steps, ae-pricing, ae-offer, ae-terms, ae-faq), styled by
 * assets/refresh/ae-v5.css. No new classes, no gold, no page specific scripts.
 *
 * Hero image slot: assets/img/services/front-desk.jpg. It renders only once that file exists,
 * so the page never shows a broken image while the photo is being produced.
 *
 * Self contained: every string and price on this page lives in this file.
 *
 * @package AE_Softwares
 */

get_header();

$ae_book_url    = 'https://calendar.app.google/oWNCzxkmP43DBkhbA';
$ae_pricing_url = home_url( '/pricing/' );
$ae_slug        = 'front-desk';
$ae_img_rel     = 'assets/img/services/' . $ae_slug . '.jpg';
$ae_has_img     = file_exists( get_theme_file_path( $ae_img_rel ) );

$ae_page = [
	'name'        => 'Front Desk',
	'headline'    => 'Answer every business call during the workday without pulling your team off the job.',
	'description' => 'AI phone answering that works with your existing business number and captures every caller\'s details.',
	'price'       => '$99 per month for up to 60 calls · $179 for up to 150 · $309 for up to 300 · $75 one time setup on every tier',
	'fits'        => 'Service businesses, medical and dental offices, law firms, and local operators with 5 to 50 employees who miss calls while helping clients in person or working in the field. It fits teams that want every caller greeted promptly without hiring a full time receptionist.',
	'terms'       => 'Every plan keeps your existing phone number and has a $75 one time setup. If your calls go past 60 or 150 in a month, calls keep getting answered and you move to the next tier. Over 300 calls a month, we bill $45 per extra block of 50 calls.',
	'cta_title'   => 'Stop letting unanswered calls turn into lost jobs.',
	'cta_text'    => 'Book a discovery call with our team, or view our full pricing page to compare all plans.',
];

// Hero buttons. The first is the solid button, the rest are text links.
$ae_hero_actions = [
	[ 'label' => 'See plans', 'url' => '#pricing' ],
	[ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	[ 'label' => 'See full pricing', 'url' => home_url( '/pricing/' ) ],
];

$ae_includes = [
	[
		'title' => 'Keep your number',
		'text' => 'Keep your existing business phone number with simple call forwarding.',
	],
	[
		'title' => 'Plain spoken answering',
		'text' => 'Natural, plain spoken call answering tailored to your business services and intake questions.',
	],
	[
		'title' => 'Call summaries',
		'text' => 'Instant call summaries and caller details delivered to your team for prompt follow up.',
	],
	[
		'title' => 'Call routing',
		'text' => 'Clear call routing rules so urgent matters reach the right person.',
	],
];

$ae_steps = [
	[
		'title' => 'Configure your call flow',
		'text' => 'We build your call script, common questions, and routing rules around how your front desk handles inquiries.',
	],
	[
		'title' => 'Connect your existing number',
		'text' => 'You keep your current business phone number and forward unanswered or busy lines to your Front Desk line.',
	],
	[
		'title' => 'Review and follow up',
		'text' => 'Every call is answered, logged, and summarized so your team can return calls with full context.',
	],
];

$ae_cards = [
	[
		'name'     => 'Up to 60 calls',
		'price'    => '$99',
		'cadence'  => 'per month · plus $75 one time setup',
		'pitch'    => 'Front Desk for businesses with a steady, manageable call volume.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Start now', 'url' => ae_checkout_url( 'front-desk' ) ],
	],
	[
		'name'     => 'Up to 150 calls',
		'price'    => '$179',
		'cadence'  => 'per month · plus $75 one time setup',
		'pitch'    => 'The same Front Desk with room for up to 150 calls a month.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
	[
		'name'     => 'Up to 300 calls',
		'price'    => '$309',
		'cadence'  => 'per month · plus $75 one time setup',
		'pitch'    => 'The same Front Desk with room for up to 300 calls a month.',
		'points'   => [ '$45 per extra block of 50 calls over 300' ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
];

$ae_faq = [
	[
		'q' => 'Do we have to change our existing business phone number?',
		'a' => 'No. You keep the exact business number your customers already know and use conditional or direct call forwarding to send calls to Front Desk.',
	],
	[
		'q' => 'What happens if our call volume goes over our monthly tier?',
		'a' => 'Your calls continue to be answered without interruption. If you go past 60 or 150 calls in a month, you move to the next tier. Any volume above 300 calls is billed in straightforward blocks of 50 calls at $45 per block.',
	],
	[
		'q' => 'Can Front Desk transfer urgent calls to a live person?',
		'a' => 'Yes. During setup we define which call types should be transferred directly to a staff member and which should be captured as detailed messages.',
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
		<span class="v5-label" data-reveal>On every plan</span>
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
		<p class="v5-label" data-reveal>Three call tiers</p>
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
		<a class="v5-button" href="<?php echo esc_url( $ae_book_url ); ?>">Book a discovery call</a>
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
