<?php
/**
 * Template Name: Online Presence
 *
 * Online presence management: Google Business Profile, reviews, listings,
 * social profiles and website care. Includes the interactive "presence check"
 * (assets/js/ae-presence.js) and Service + FAQ structured data.
 *
 * @package AE_Softwares
 */

get_header();

$ae_presence_faq = [
	[
		'q' => 'What is online presence management?',
		'a' => 'It is the ongoing work of keeping your business easy to find and trustworthy online: your Google Business Profile, your reviews, your listings on maps and directories, your social profiles, and your website. I set each one up properly, keep the details matching everywhere, and look after them every month.',
	],
	[
		'q' => 'Do I need a new website to start?',
		'a' => 'No. I can manage your presence around the site you already have. If the site is holding you back, I will tell you during the free presence check and quote a rebuild separately.',
	],
	[
		'q' => 'How long until I see results on Google?',
		'a' => 'It depends on your market and on how much is already in place. Ranking higher in local results builds over time as reviews, posts and consistent listings add up. I will not promise a ranking, but I will show you what changed in the monthly report.',
	],
	[
		'q' => 'Will you reply to reviews for me?',
		'a' => 'Yes. I draft replies to every new review in your voice. You can approve them first or let me post them directly.',
	],
	[
		'q' => 'Which areas do you work with?',
		'a' => 'I work with businesses across the US, remotely and in person where it makes sense.',
	],
];
?>

<section class="page-hero op-hero">
	<p class="page-hero__eyebrow">Online presence management</p>
	<h1 class="page-hero__headline">Be the business they find first.</h1>
	<p class="page-hero__subtitle">I set up and look after everything people see before they call you: your Google Business Profile, reviews, listings and website, for small businesses everywhere.</p>
	<div class="op-hero__actions" data-reveal>
		<a class="v5-button" href="#presence-check">Run the free presence check</a>
		<a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Talk to me &rarr;</a>
	</div>
</section>

<!-- Interactive presence check -->
<section class="op-check ae-rule" id="presence-check" aria-labelledby="op-check-title">
	<header class="v5-section-head">
		<h2 class="v5-section-title" id="op-check-title" data-split>Quick presence check</h2>
		<span class="v5-label" data-reveal>Two minutes, nothing to sign up for</span>
	</header>

	<div class="op-check__grid">
		<form class="op-check__list" id="ae-presence" data-reveal aria-describedby="op-check-hint">
			<p class="op-check__hint" id="op-check-hint">Tick everything that is true for your business today.</p>
			<?php
			$ae_checks = [
				[ 'gbp',      'You have claimed your Google Business Profile',             'Google Business Profile', 3 ],
				[ 'hours',    'Your hours, phone and address are correct on Google',       'Accurate details',        2 ],
				[ 'photos',   'You have posted photos in the last three months',           'Fresh photos',            1 ],
				[ 'reviews',  'You have 20 or more Google reviews',                        'Review volume',           2 ],
				[ 'replies',  'You reply to every review, good or bad',                    'Review replies',          2 ],
				[ 'listings', 'Your name, address and phone match on Bing, Apple Maps, Yelp and Facebook', 'Consistent listings', 2 ],
				[ 'mobile',   'Your website loads fast and works well on a phone',         'Mobile website',          2 ],
				[ 'contact',  'Visitors can call, book or request a quote in one tap',     'One tap contact',         2 ],
			];
			foreach ( $ae_checks as $c ) :
				?>
				<label class="op-item">
					<input type="checkbox" name="<?php echo esc_attr( $c[0] ); ?>" data-weight="<?php echo esc_attr( $c[3] ); ?>" data-label="<?php echo esc_attr( $c[2] ); ?>">
					<span class="op-item__box" aria-hidden="true"></span>
					<span class="op-item__text"><?php echo esc_html( $c[1] ); ?></span>
				</label>
			<?php endforeach; ?>
		</form>

		<aside class="op-score" data-reveal aria-live="polite">
			<div class="op-score__dial" aria-hidden="true">
				<svg viewBox="0 0 120 120">
					<circle class="op-score__track" cx="60" cy="60" r="52"></circle>
					<circle class="op-score__bar" cx="60" cy="60" r="52" pathLength="100"></circle>
				</svg>
				<span class="op-score__num" id="op-score-num">0</span>
			</div>
			<p class="v5-label">Your presence score</p>
			<p class="op-score__verdict" id="op-score-verdict">Start ticking and your score updates as you go.</p>
			<div class="op-score__gaps" id="op-score-gaps" hidden>
				<p class="v5-label">Biggest gaps</p>
				<ul id="op-score-gap-list"></ul>
			</div>
			<a class="v5-button op-score__cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get these fixed</a>
		</aside>
	</div>
</section>

<!-- What's included -->
<section class="v5-services" aria-labelledby="op-included-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="op-included-title" data-split>What I look after</h2>
		<span class="v5-label" data-reveal>Every month</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Google Business Profile</h3>
			<p class="v5-service__text">Claimed, verified and filled in completely: categories, services, hours, photos and weekly posts, so you show up in the map results for the searches that matter.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Reviews</h3>
			<p class="v5-service__text">Automatic review requests after every job, plus a drafted reply to every review in your voice, so your rating grows and nothing sits unanswered.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Listings everywhere</h3>
			<p class="v5-service__text">Your name, address and phone made identical on Google, Bing, Apple Maps, Yelp, Facebook and the directories that feed them. Matching details are a local ranking signal.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Website care</h3>
			<p class="v5-service__text">Updates, backups, uptime checks and small edits handled for you, with page titles and descriptions kept current for search.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">05</span>
			<h3 class="v5-service__title">A plain monthly report</h3>
			<p class="v5-service__text">How many people found you, called, asked for directions or visited the site, and what I changed. One page, no jargon.</p>
		</li>
	</ol>
</section>

<!-- How it works -->
<section class="op-related ae-rule" aria-labelledby="op-price-title">
	<p class="v5-label" data-reveal>Presence Care</p>
	<h2 class="op-related__title" id="op-price-title" data-split>$199 a month. Cancel anytime.</h2>
	<p class="op-related__text" data-reveal>Everything above, every month, plus website hosting, updates and small edits. Starting fresh? The Launch Package bundles a new site, Google profile setup and three months of Presence Care for $595.</p>
	<a class="v5-link op-related__link" href="<?php echo esc_url( home_url( '/pricing/#monthly' ) ); ?>" data-reveal>See all pricing &rarr;</a>
</section>

<section class="op-steps ae-rule" aria-labelledby="op-steps-title">
	<h2 class="v5-section-title" id="op-steps-title" data-split>How it works</h2>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">01</span><h3>Presence check</h3><p>A free 15-minute call. We look at what a customer sees when they search for you and list what to fix first.</p></li>
		<li data-reveal><span class="v5-label">02</span><h3>Cleanup</h3><p>I fix the profile, listings and website basics first, so the foundation is right.</p></li>
		<li data-reveal><span class="v5-label">03</span><h3>Ongoing care</h3><p>Posts, review replies and listing upkeep every month, with a report so you can see what changed.</p></li>
	</ol>
</section>

<!-- Related: website creation -->
<section class="op-related ae-rule" aria-labelledby="op-related-title">
	<p class="v5-label" data-reveal>Need a new site too?</p>
	<h2 class="op-related__title" id="op-related-title" data-split>Your website is the centre of your presence.</h2>
	<p class="op-related__text" data-reveal>I design and build fast, mobile first websites for small businesses, from a simple five page site to a lead funnel with booking and follow up built in.</p>
	<a class="v5-link op-related__link" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" data-reveal>See website design &amp; pricing &rarr;</a>
</section>

<!-- FAQ -->
<section class="op-faq ae-rule" aria-labelledby="op-faq-title">
	<h2 class="v5-section-title" id="op-faq-title" data-split>Questions</h2>
	<div class="op-faq__list">
		<?php foreach ( $ae_presence_faq as $f ) : ?>
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
				'@type'       => 'Service',
				'name'        => 'Online presence management',
				'serviceType' => 'Online presence management',
				'description' => 'Google Business Profile management, review management, local listings consistency, website care and monthly reporting for small businesses.',
				'url'         => home_url( '/online-presence/' ),
				'provider'    => [ '@id' => home_url( '/#localbusiness' ) ],
				'areaServed'  => aesoftwares_service_area(),
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
					$ae_presence_faq
				),
			],
		],
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
