<?php /* Template Name: About */ get_header(); ?>

<div class="page-hero about-page-hero">
    <div class="page-hero__eyebrow">About</div>
    <h1 class="page-hero__headline">A small studio for <em>small</em> businesses.</h1>
    <p class="page-hero__subtitle">AE Software Solutions builds websites, looks after Google presence and sets up follow-up automation for small businesses that would rather be doing the work than chasing leads.</p>
</div>

<div class="mission-block">
    <blockquote class="mission-pullquote js-fade" style="--delay:0s">Most small businesses don&rsquo;t need more software. They need the calls they&rsquo;re already getting to turn into booked work.</blockquote>
    <div class="mission-body js-fade" style="--delay:0.15s">
        <p>That&rsquo;s the whole focus. A website that makes it obvious what you do and how to reach you. A Google profile with current hours, recent photos and replies to every review. And a system that answers when you can&rsquo;t &mdash; a text back after a missed call, a booking link, a reminder to follow up on a quote.</p>
        <p>Every project is led by our founder, Anthony, start to finish. You talk to the person building your system, not an account manager, and you get a fixed price in writing before any work starts.</p>
        <p>We keep the tools simple and connect to what you already use. If you have booking or job software that works, we build around it instead of asking you to switch.</p>
    </div>
</div>

<section class="inner-section-pad about-values-section">
    <div class="inner-section-header">
        <span class="section-eyebrow">How we work</span>
        <h2 class="section-title">Three promises.</h2>
    </div>
    <div class="values-grid">
        <div class="value-card js-fade" style="--delay:0s">
            <span class="value-card__num">01</span>
            <h3 class="value-card__title">Fixed prices, in writing</h3>
            <p class="value-card__text">Every package has a price on the website. Anything outside the scope gets a written quote before it starts. No hourly billing, no surprise invoices.</p>
        </div>
        <div class="value-card js-fade" style="--delay:0.12s">
            <span class="value-card__num">02</span>
            <h3 class="value-card__title">You own everything</h3>
            <p class="value-card__text">Your website, domain, Google profile, accounts and data stay in your name. Monthly plans are month to month, and nothing switches off if you leave.</p>
        </div>
        <div class="value-card js-fade" style="--delay:0.24s">
            <span class="value-card__num">03</span>
            <h3 class="value-card__title">We stand behind it</h3>
            <p class="value-card__text">If our Always-On Front Desk doesn&rsquo;t catch a lead you would have missed within 60 days, every monthly payment is refunded. If we don&rsquo;t think we can help, we&rsquo;ll say so on the first call.</p>
        </div>
    </div>
</section>

<?php
// Real client work in place of the old stats row (50+ / 98% / 5x could not be backed up).
$ae_about_work = [
    [ 'Monarch MVC', 'Health platform', 'monarch-mvc.jpg', 'Monarch MVC men’s health platform home page' ],
    [ 'Learn English with Evie', 'Education', 'learn-english-with-evie.jpg', 'Learn English with Evie lesson booking site' ],
    [ 'Anthony Pioquinto', 'Personal brand', 'anthony-pioquinto.jpg', 'Anthony Pioquinto real estate portfolio site' ],
];
?>
<section class="v5-recent" aria-labelledby="about-recent-title">
    <header class="v5-section-head ae-rule">
        <h2 class="v5-section-title" id="about-recent-title" data-split>Recent client work</h2>
        <a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/work/' ) ); ?>" data-reveal>&rarr; See all work</a>
    </header>
    <div class="v5-work__grid v5-work__grid--three">
        <?php foreach ( $ae_about_work as $i => $w ) : ?>
            <a class="v5-card" href="<?php echo esc_url( home_url( '/work/' ) ); ?>" data-reveal>
                <span class="v5-card__media ae-media">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/work/' . $w[2] ); ?>" alt="<?php echo esc_attr( $w[3] ); ?>" loading="lazy" decoding="async" width="1440" height="900">
                </span>
                <span class="v5-card__caption">
                    <span class="v5-card__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
                    <span class="v5-card__title"><?php echo esc_html( $w[0] ); ?></span>
                    <span class="v5-card__tag"><?php echo esc_html( $w[1] ); ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- "What clients say" removed: it showed invented clients with stock photos.
     Add it back only with real, named clients who agreed in writing. -->

<ae-cta></ae-cta>

<?php get_footer(); ?>
