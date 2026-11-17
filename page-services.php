<?php
/**
 * Template Name: Services
 *
 * What I do, for local service businesses: websites, Google presence and
 * follow up automation. Prices come from inc/offers.php. Work and Online
 * presence are reached from here (and from the footer), not from the nav.
 *
 * @package AE_Softwares
 */

get_header();
$o = aesoftwares_offers();
$price = function ( $group, $id ) use ( $o ) {
	foreach ( $o[ $group ] as $item ) {
		if ( $item['id'] === $id ) {
			return $item['price'] . ( false !== strpos( $item['cadence'], 'per month' ) ? '/mo' : '' );
		}
	}
	return '';
};
?>

<section class="page-hero services-page-hero">
	<p class="page-hero__eyebrow">Services</p>
	<h1 class="page-hero__headline">Get found, look trustworthy, never miss a lead.</h1>
	<p class="page-hero__subtitle">I help local service businesses with three things: a website that brings in calls, a Google presence people trust, and follow up that happens even when you&rsquo;re on a job.</p>
</section>

<section class="v5-services" aria-labelledby="svc-what">
	<header class="v5-section-head">
		<h2 class="v5-section-title" id="svc-what" data-reveal>What I do</h2>
		<a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>" data-reveal>&rarr; All prices</a>
	</header>

	<ol class="v5-services__list svc-list">
		<li class="v5-service v5-service--priced" data-reveal>
			<span class="v5-service__num">01</span>
			<h3 class="v5-service__title">Website design</h3>
			<div class="v5-service__text">
				<p>Fast, mobile first websites that explain what you do and make it easy to call, book or ask for a quote. Every site includes your Google Business Profile set up properly.</p>
			</div>
			<div class="v5-service__side">
				<p class="svc-meta">Starter Site <?php echo esc_html( $price( 'websites', 'starter-site' ) ); ?> &middot; Lead Funnel <?php echo esc_html( $price( 'websites', 'lead-funnel' ) ); ?></p>
				<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>">Website design</a>
			</div>
		</li>
		<li class="v5-service v5-service--priced" data-reveal>
			<span class="v5-service__num">02</span>
			<h3 class="v5-service__title">Online presence</h3>
			<div class="v5-service__text">
				<p>Your Google Business Profile, reviews and listings looked after every month: weekly posts, a reply to every review, details that match everywhere, and a one page report.</p>
			</div>
			<div class="v5-service__side">
				<p class="svc-meta">Online Presence Management <?php echo esc_html( $price( 'monthly', 'online-presence' ) ); ?> &middot; cancel anytime</p>
				<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>">Online presence</a>
			</div>
		</li>
		<li class="v5-service v5-service--priced" data-reveal>
			<span class="v5-service__num">03</span>
			<h3 class="v5-service__title">Follow up automation</h3>
			<div class="v5-service__text">
				<p>Missed call text back, instant replies to web and Facebook inquiries, booking links and review requests. It connects to the tools you already use rather than replacing them.</p>
			</div>
			<div class="v5-service__side">
				<p class="svc-meta">Front Desk <?php echo esc_html( $price( 'monthly', 'front-desk' ) ); ?> &middot; Always-On Front Desk <?php echo esc_html( $price( 'monthly', 'front-desk-always-on' ) ); ?> &middot; 60 day guarantee</p>
				<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/pricing/#front-desk' ) ); ?>">Front Desk</a>
			</div>
		</li>
		<li class="v5-service v5-service--priced" data-reveal>
			<span class="v5-service__num">04</span>
			<h3 class="v5-service__title">Missed-Lead Audit</h3>
			<div class="v5-service__text">
				<p>Not sure where to start? I map every way a customer can reach you, find the ones that go unanswered, and put a dollar figure on it. Credited in full if you go ahead.</p>
			</div>
			<div class="v5-service__side">
				<p class="svc-meta"><?php echo esc_html( $o['start'][1]['price'] ); ?> one time</p>
				<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/pricing/#audit' ) ); ?>">Audit</a>
			</div>
		</li>
		<li class="v5-service v5-service--priced" data-reveal>
			<span class="v5-service__num">05</span>
			<h3 class="v5-service__title">Social media management</h3>
			<div class="v5-service__text">
				<p>Content, short form video, posting and replies handled for you across your platforms, with a monthly report on what it brought in. Plans for businesses and for creators.</p>
			</div>
			<div class="v5-service__side">
				<p class="svc-meta">Social Front Desk <?php echo esc_html( $price( 'monthly', 'social-front-desk' ) ); ?> &middot; Total Presence <?php echo esc_html( $price( 'monthly', 'total-presence' ) ); ?> &middot; Social Management <?php echo esc_html( $price( 'monthly', 'social-management' ) ); ?> &middot; Creator plans from $499/mo</p>
				<a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/social-management/' ) ); ?>">Social media management</a>
			</div>
		</li>
	</ol>
	<p class="ae-cta-row" data-reveal>
		<a class="v5-textlink" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">See recent work &rarr;</a>
		<a class="v5-textlink" href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>">Check your online presence &rarr;</a>
	</p>
</section>

<section class="op-steps" aria-labelledby="svc-who">
	<header class="v5-section-head">
		<h2 class="v5-section-title" id="svc-who" data-reveal>Who it&rsquo;s for</h2>
	</header>
	<ol class="op-steps__list">
		<li data-reveal><span class="v5-label">Trades &amp; home services</span><h3>Calls you can&rsquo;t answer on a job</h3><p>Plumbers, electricians, HVAC, roofers and cleaners who lose work to voicemail and slow follow up.</p></li>
		<li data-reveal><span class="v5-label">Offices &amp; clinics</span><h3>Patients and clients who check you first</h3><p>Dental, chiropractic, legal and accounting offices where reviews, hours and easy booking decide who gets the call.</p></li>
		<li data-reveal><span class="v5-label">New &amp; growing businesses</span><h3>No website, or only a Facebook page</h3><p>Owners ready for a proper online home. The Launch Package covers the site, Google profile and three months of care.</p></li>
	</ol>
</section>

<section class="op-related ae-alt" aria-labelledby="svc-promise">
	<p class="v5-label" data-reveal>How I work</p>
	<h2 class="op-related__title v5-section-title" id="svc-promise" data-reveal>Fixed prices, plain English, and you own everything.</h2>
	<p class="op-related__text" data-reveal>Every project has a written scope and a fixed price before work starts. Your website, domain, accounts and data stay in your name, so nothing switches off if you ever leave. <?php echo esc_html( $o['guarantee'] ); ?></p>
	<a class="v5-link op-related__link" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>" data-reveal>See pricing &rarr;</a>
</section>

<ae-cta></ae-cta>

<?php get_footer(); ?>
