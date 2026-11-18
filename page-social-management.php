<?php
/**
 * Template Name: Social Media Management
 *
 * Social media management for businesses and creators: strategy, content,
 * short form video, posting, replies and a monthly report. Plans and add ons
 * are listed on the page, with Service + FAQ structured data.
 *
 * @package AE_Softwares
 */

get_header();

$ae_social_faq = [
	[
		'q' => 'Who keeps ownership of the accounts?',
		'a' => 'Yes. Your accounts, followers, content and lists are yours. We work through access you grant, and we remove that access if you leave.',
	],
	[
		'q' => 'Which platforms should be run?',
		'a' => 'The ones your customers or audience actually use. Most clients run three to five. We recommend a set on the discovery call, and we drop anything that does not work.',
	],
	[
		'q' => 'Who makes the videos?',
		'a' => 'We write the scripts. You can film on your phone and we edit, or we render branded videos with captions and a voice you approve. One video is reshaped for every platform in your plan.',
	],
	[
		'q' => 'How much of your time does this take?',
		'a' => 'About 30 minutes a month to approve the calendar and answer a few questions, plus the discovery call and the voice interview at the start.',
	],
	[
		'q' => 'Do you run paid ads?',
		'a' => 'No. Paid advertising is out of scope. Everything here is organic content, posting and engagement.',
	],
	[
		'q' => 'What happens on cancellation?',
		'a' => 'After the minimum term, 30 days notice ends the plan. We hand over your files, calendars and templates, and we remove our access.',
	],
];
?>

<section class="page-hero op-hero">
	<p class="page-hero__eyebrow">Social media management</p>
	<h1 class="page-hero__headline">Your social media, handled properly.</h1>
	<p class="page-hero__subtitle">We plan, write, design, post and reply for businesses that want leads and creators who want to grow, with a monthly report that shows what it brought in.</p>
	<div class="op-hero__actions" data-reveal>
		<a class="v5-button" href="#plans">See the plans</a>
		<a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" data-ae-popup>Book a free discovery call</a>
	</div>
</section>

<!-- Who it is for -->
<section class="op-steps ae-rule" aria-labelledby="sm-who-title">
	<h2 class="v5-section-title" id="sm-who-title" data-split>Who it is for</h2>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">For businesses</span><h3>Owners who want leads, not likes</h3><p>You run a service business or a firm and you do not have time to post, reply and keep up. We run it for you and trace what comes in. You give about 30 minutes a month to approvals.</p></li>
		<li data-reveal><span class="v5-label">For influencers and creators</span><h3>Creators who want consistency</h3><p>We keep you posting on a steady schedule, turn one video into posts for every platform where it fits, and build a profile that sponsors can take seriously.</p></li>
	</ol>
</section>

<!-- What we do every month -->
<section class="v5-services" aria-labelledby="sm-included-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="sm-included-title" data-split>What we do every month</h2>
		<span class="v5-label" data-reveal>Included in management plans</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Monthly strategy and calendar</h3>
			<p class="v5-service__text">A plan for the month and a full content calendar, sent to you for approval by about the 22nd.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Content written and designed</h3>
			<p class="v5-service__text">Posts, graphics and carousels, made in your brand and your voice.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Short form video</h3>
			<p class="v5-service__text">We write the scripts. You film and we edit, or we render branded video with captions. One video is reshaped for TikTok, Reels, Shorts and Threads.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Posting and cross posting</h3>
			<p class="v5-service__text">Scheduled and posted for you, and adapted to each platform. Never copy and paste.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">05</span>
			<h3 class="v5-service__title">Replies and community</h3>
			<p class="v5-service__text">Comments and messages handled from a reply guide you approve. Leads and anything sensitive are flagged to you the same business day.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">06</span>
			<h3 class="v5-service__title">Email campaign</h3>
			<p class="v5-service__text">One campaign a month to your list, sent from your own email platform and approved by you before it goes out. Plans that list 2 campaigns include them, and extra campaigns are an add on.</p>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">07</span>
			<h3 class="v5-service__title">Monthly report and call</h3>
			<p class="v5-service__text">Results first: leads and bookings traced to the work, then reach. Plus what changes next month.</p>
		</li>
	</ol>
</section>

<!-- Platforms -->
<section class="op-related ae-rule" aria-labelledby="sm-platforms-title">
	<p class="v5-label" data-reveal>Platforms we run</p>
	<h2 class="op-related__title" id="sm-platforms-title" data-split>Facebook, Instagram, TikTok, YouTube and more.</h2>
	<p class="op-related__text" data-reveal>Facebook &middot; Instagram &middot; TikTok &middot; YouTube &middot; LinkedIn &middot; X &middot; Threads &middot; Bluesky &middot; Nextdoor &middot; Google Business Profile</p>
	<p class="op-related__text" data-reveal>Facebook, Instagram, LinkedIn, Threads and Bluesky post automatically on a schedule. TikTok, X and YouTube are posted by hand for now. We will always tell you which is which.</p>
	<p class="op-related__text" data-reveal>You own every account. We work through access you grant, and we remove our access if you leave.</p>
</section>

<!-- Plans for businesses -->
<section class="v5-services" id="plans" aria-labelledby="sm-business-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="sm-business-title" data-split>Plans for businesses</h2>
		<span class="v5-label" data-reveal>Monthly</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Social Front Desk</h3>
			<div class="v5-service__text">
				<p><strong>$199 a month plus a one time $75 setup.</strong> Posting handled for you with light planning. A good start if you only need to show up consistently.</p>
				<p>First 3 clients: $149 a month for 3 months, then $199 a month. Use code FOUNDING149 at checkout.</p>
				<p>Want Google Business Profile management too? Total Presence is $229 a month and covers both. <a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Ask us about it</a>.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sYbJ1d2w2XcakXfFvabK0h">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Social Management</h3>
			<div class="v5-service__text">
				<p><strong>$799 a month plus a one time $300 setup.</strong> Up to 4 platforms, 12 to 16 posts a month, strategy and calendar, outreach, replies handled, 1 email campaign, monthly report and review call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sY14n9QkapE2Sv2SJabK0b">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Social Management Growth</h3>
			<div class="v5-service__text">
				<p><strong>$1,299 a month plus a one time $495 setup.</strong> Up to 6 platforms, 20 to 24 posts, 8 short form videos, 2 email campaigns, priority turnaround, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/3cIfZh3rWcxMakXalbabK0i">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Social Management Scale</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 8 platforms including TikTok and YouTube, 28 to 32 posts, 12 short form videos, daily engagement checks, 2 email campaigns, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/8x2fZhaUoapEdx9gJzabK0j">Start now</a>
		</li>
	</ol>
</section>

<!-- Plans for creators -->
<section class="v5-services" aria-labelledby="sm-creator-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="sm-creator-title" data-split>Plans for creators</h2>
		<span class="v5-label" data-reveal>Influencers and creators</span>
	</header>
	<ol class="v5-services__list">
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Creator Launch</h3>
			<div class="v5-service__text">
				<p><strong>$499 a month plus a one time $195 setup.</strong> 3 platforms, 12 short form videos a month cross posted, captions and hashtags written for each platform, posting handled, monthly growth report.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/eVq28r4w0gO2gJlctjabK0k">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Creator Growth</h3>
			<div class="v5-service__text">
				<p><strong>$899 a month plus a one time $345 setup.</strong> Up to 5 platforms, 20 short form videos, comment handling on business days, profile and link page polish, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/6oUaEX5A48hwct578ZabK0l">Start now</a>
		</li>
		<li class="v5-service ae-rule" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Creator Authority</h3>
			<div class="v5-service__text">
				<p><strong>$1,999 a month plus a one time $750 setup.</strong> Up to 6 platforms, 30 short form videos, daily engagement checks, sponsor inquiries flagged with drafted replies you approve, 1 email or newsletter a month, monthly report and call.</p>
			</div>
			<a class="v5-link v5-service__more" href="https://buy.stripe.com/7sY28r9QkdBQ2Sv2SJabK0m">Start now</a>
		</li>
	</ol>
</section>

<!-- Add ons -->
<section class="op-related ae-rule" aria-labelledby="sm-addons-title">
	<p class="v5-label" data-reveal>Add ons</p>
	<h2 class="op-related__title" id="sm-addons-title" data-split>Add what you need, when you need it.</h2>
	<p class="op-related__text" data-reveal>Extra platform $99 a month each &middot; Extra email campaign $150 each &middot; Pack of 4 short videos $199 &middot; Long form YouTube video edit $150 each &middot; Content day planning session $250 &middot; Profile setup or cleanup $150 per platform.</p>
	<p class="op-related__text" data-reveal>We do not manage paid advertising.</p>
</section>

<!-- How it works -->
<section class="op-steps ae-rule" aria-labelledby="sm-steps-title">
	<h2 class="v5-section-title" id="sm-steps-title" data-split>How it works</h2>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">01</span><h3>Discovery call</h3><p>A free 30 minute call about your goals, offers and platforms.</p></li>
		<li data-reveal><span class="v5-label">02</span><h3>Setup</h3><p>Access granted by you, brand assets gathered, and a short voice interview so the content sounds like you. We create your Meta, Claude and Canva accounts in your business name and connect your socials.</p></li>
		<li data-reveal><span class="v5-label">03</span><h3>Calendar approval</h3><p>You approve the next month&rsquo;s calendar within 3 business days. Nothing unapproved goes out.</p></li>
		<li data-reveal><span class="v5-label">04</span><h3>Run and report</h3><p>We post, reply and handle the community, then report results every month.</p></li>
	</ol>
</section>

<!-- Terms -->
<section class="op-related ae-rule" aria-labelledby="sm-terms-title">
	<p class="v5-label" data-reveal>Terms</p>
	<h2 class="op-related__title" id="sm-terms-title" data-split>Terms in plain English</h2>
	<p class="op-related__text" data-reveal>Management plans run a 3 month minimum, then month to month with 30 days notice to cancel. You own your accounts, content and lists. We never buy followers or engagement, and we never promise follower counts or sales. Paid ad spend and email platform fees are yours.</p>
</section>

<!-- FAQ -->
<section class="op-faq ae-rule" aria-labelledby="sm-faq-title">
	<h2 class="v5-section-title" id="sm-faq-title" data-split>Questions</h2>
	<div class="op-faq__list">
		<?php foreach ( $ae_social_faq as $f ) : ?>
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
				'name'        => 'Social media management',
				'serviceType' => 'Social media management',
				'description' => 'Social media management for businesses and creators: monthly strategy and calendar, written and designed content, short form video, posting, replies and a monthly report.',
				'url'         => home_url( '/social-management/' ),
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
					$ae_social_faq
				),
			],
		],
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
