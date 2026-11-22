<?php
/**
 * Template Name: Websites
 *
 * Product page: Websites.
 * Copy comes from the Stage 1 product page brief (product-pages-hydrogen-20261122.md),
 * reviewed in Stage 2. Layout reuses existing theme classes only (page-hero, v5-services,
 * op-related, op-steps, ae-pricing, ae-offer, ae-terms, ae-faq), styled by
 * assets/refresh/ae-v5.css. No new classes, no gold, no page specific scripts.
 *
 * Hero image slot: assets/img/services/websites.jpg. It renders only once that file exists,
 * so the page never shows a broken image while the photo is being produced.
 *
 * Self contained: every string and price on this page lives in this file.
 *
 * @package AE_Softwares
 */

get_header();

$ae_book_url    = 'https://calendar.app.google/oWNCzxkmP43DBkhbA';
$ae_pricing_url = home_url( '/pricing/' );
$ae_slug        = 'websites';
$ae_img_rel     = 'assets/img/services/' . $ae_slug . '.jpg';
$ae_has_img     = file_exists( get_theme_file_path( $ae_img_rel ) );

$ae_page = [
	'name'        => 'Websites',
	'headline'    => 'Websites built to bring in calls, book jobs, and support daily operations.',
	'description' => 'Custom websites for small businesses, from a clean starter presence to a full digital operations platform.',
	'price'       => 'Starter Site from $495 · Online Presence $1,500 to $3,500 · Business Lead Funnel $4,500 to $7,500 · Digital Operations Platform $8,000 to $13,000',
	'fits'        => 'Small business owners with 5 to 50 employees who need a website that matches the real quality of their work. It fits local service companies, clinics, legal practices, and growing operators who want a clear path from visitor to booked client without bloated agency retainers.',
	'terms'       => 'Final pricing within each tier depends on page count, custom intake logic, and required integrations. We walk through the payment schedule and timeline with you on your discovery call.',
	'cta_title'   => 'Ready to scope the right website for your business?',
	'cta_text'    => 'Book a discovery call with our team, or view our full pricing page to compare all packages.',
];

// Hero buttons. The first is the solid button, the rest are text links.
$ae_hero_actions = [
	[ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	[ 'label' => 'See full pricing', 'url' => home_url( '/pricing/' ) ],
];

$ae_includes = [
	[
		'title' => 'Starter Site',
		'text' => 'A clean, mobile ready foundational site for new or local businesses that need a credible home online.',
	],
	[
		'title' => 'Tier 1 Online Presence',
		'text' => 'A structured multi page company website built for clear service positioning, local search visibility, and straightforward contact paths.',
	],
	[
		'title' => 'Tier 2 Business Lead Funnel',
		'text' => 'A conversion focused website built to capture, qualify, and route inbound inquiries into your sales or intake workflow.',
	],
	[
		'title' => 'Tier 3 Digital Operations Platform',
		'text' => 'A custom web platform combining client portals, intake workflows, internal dashboards, and operational integrations for complex or regulated teams.',
	],
];

$ae_steps = [
	[
		'title' => 'Scope and structure',
		'text' => 'We review your services, client intake process, and compliance needs, then map out the exact pages and workflows required.',
	],
	[
		'title' => 'Design and build',
		'text' => 'We build your site in a clean, fast architecture tailored to how your customers search, read, and reach out.',
	],
	[
		'title' => 'Review and launch',
		'text' => 'You review every page with us before launch, and we verify forms, phone links, and live domain settings before handing over full access.',
	],
];

$ae_cards = [
	[
		'name'     => 'Starter Site',
		'price'    => 'from $495',
		'cadence'  => 'Starting price',
		'pitch'    => 'A clean, mobile ready foundational site for new or local businesses that need a credible home online.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
	[
		'name'     => 'Tier 1 Online Presence',
		'price'    => '$1,500 to $3,500',
		'cadence'  => 'Price range',
		'pitch'    => 'A structured multi page company website for clear service positioning, local search visibility, and straightforward contact paths.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
	[
		'name'     => 'Tier 2 Business Lead Funnel',
		'price'    => '$4,500 to $7,500',
		'cadence'  => 'Price range',
		'pitch'    => 'A conversion focused website that captures, qualifies, and routes inbound inquiries into your sales or intake workflow.',
		'points'   => [  ],
		'featured' => true,
		'flag'     => 'Most popular',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
	[
		'name'     => 'Tier 3 Digital Operations Platform',
		'price'    => '$8,000 to $13,000',
		'cadence'  => 'Price range',
		'pitch'    => 'Client portals, intake workflows, internal dashboards, and operational integrations for complex or regulated teams.',
		'points'   => [  ],
		'featured' => false,
		'flag'     => '',
		'cta'      => [ 'label' => 'Book a discovery call', 'url' => $ae_book_url ],
	],
];

$ae_faq = [
	[
		'q' => 'How do we know which website tier fits our business?',
		'a' => 'If you simply need a clean, trustworthy online brochure, Starter Site or Tier 1 Online Presence is the right fit. If your main goal is turning website traffic into booked calls and qualified leads, Tier 2 Business Lead Funnel is built for that job. If your team needs client portals, custom forms, or connected back office workflows, Tier 3 Digital Operations Platform covers it.',
	],
	[
		'q' => 'Do we own our website and domain once it is built?',
		'a' => 'Yes. You own your domain, your content, and your site assets. We never lock your business into proprietary website hosting that holds your pages hostage.',
	],
	[
		'q' => 'Can we start with a smaller tier and expand later?',
		'a' => 'Yes. Every site is structured cleanly so you can launch with what your business needs today and add lead funnels, booking tools, or client workflows as you grow.',
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
		<span class="v5-label" data-reveal>Four tiers</span>
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
		<p class="v5-label" data-reveal>Project pricing</p>
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
// Service schema for this page is already printed by functions.php, so only the FAQ is added here.
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
