<?php
/**
 * Template Name: About
 *
 * Single column story, founder photo, three promises, recent work, closing band.
 *
 * @package AE_Softwares
 */
get_header();

$ae_img = get_template_directory_uri() . '/assets/img/';

// Testimonials. None exist and none may be invented. The component below is
// built and renders nothing while this list is empty. To add one, supply a
// real, named client who agreed in writing:
//   [ 'quote' => '...', 'name' => '...', 'business' => '...', 'source' => 'https://...' ]
$ae_testimonials = [];
?>

<section class="page-hero about-page-hero">
    <p class="page-hero__eyebrow">About</p>
    <h1 class="page-hero__headline">I build for <em>small</em> businesses.</h1>
    <p class="page-hero__subtitle">I&rsquo;m Anthony. I build websites, look after Google presence and set up follow up automation for small businesses that would rather be doing the work than chasing leads.</p>
</section>

<section class="ae-about-story" aria-label="My story">
    <div class="ae-about-story__text">
        <blockquote class="ae-pullquote" data-reveal>Most small businesses don&rsquo;t need more software. They need the calls they&rsquo;re already getting to turn into booked work.</blockquote>
        <div class="ae-about-body" data-reveal>
            <p>That&rsquo;s the whole focus. A website that makes it obvious what you do and how to reach you. A Google profile with current hours, recent photos and replies to every review. And a system that answers when you can&rsquo;t: a text back after a missed call, a booking link, a reminder to follow up on a quote.</p>
            <p>I lead every project myself, start to finish. You talk to the person building your system, not an account manager, and you get a fixed price in writing before any work starts.</p>
            <p>I keep the tools simple and connect to what you already use. If you have booking or job software that works, I build around it instead of asking you to switch.</p>
        </div>
    </div>
    <figure class="ae-about-photo">
        <img src="<?php echo esc_url( $ae_img . 'brand/founder-anthony-nobg.jpg' ); ?>" alt="Anthony, founder of AE Software Solutions" width="1514" height="1821" decoding="async">
        <figcaption>Anthony, founder</figcaption>
    </figure>
</section>

<section class="ae-promises ae-alt" aria-labelledby="about-promises">
    <header class="v5-section-head">
        <p class="v5-label" data-reveal>How I work</p>
        <h2 class="v5-section-title" id="about-promises" data-reveal>Three promises.</h2>
    </header>
    <div class="values-grid">
        <div class="value-card ae-card" data-reveal>
            <span class="value-card__num">01</span>
            <h3 class="value-card__title">Fixed prices, in writing</h3>
            <p class="value-card__text">Every package has a price on the website. Anything outside the scope gets a written quote before it starts. No hourly billing, no surprise invoices.</p>
        </div>
        <div class="value-card ae-card" data-reveal>
            <span class="value-card__num">02</span>
            <h3 class="value-card__title">You own everything</h3>
            <p class="value-card__text">Your website, domain, Google profile, accounts and data stay in your name. Monthly plans are month to month, and nothing switches off if you leave.</p>
        </div>
        <div class="value-card ae-card" data-reveal>
            <span class="value-card__num">03</span>
            <h3 class="value-card__title">I stand behind it</h3>
            <p class="value-card__text">If my Always-On Front Desk doesn&rsquo;t catch a lead you would have missed within 60 days, I refund your first two monthly payments. Conditions apply. If I don&rsquo;t think I can help, I&rsquo;ll say so on the first call.</p>
        </div>
    </div>
</section>

<?php
// Recent websites in place of the old stats row (50+ / 98% / 5x could not be backed up).
$ae_about_work = [
    [ 'Monarch MVC', 'Health platform', 'monarch-mvc.jpg', 'Monarch MVC men’s health platform home page' ],
    [ 'Learn English with Evie', 'Education', 'learn-english-with-evie.jpg', 'Learn English with Evie lesson booking site' ],
    [ 'Anthony Pioquinto', 'My own site', 'anthony-pioquinto.jpg', 'Anthony Pioquinto real estate portfolio site' ],
];
?>
<section class="v5-recent" aria-labelledby="about-recent-title">
    <header class="v5-section-head">
        <h2 class="v5-section-title" id="about-recent-title" data-reveal>Recent websites</h2>
        <a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/work/' ) ); ?>" data-reveal>&rarr; See all work</a>
    </header>
    <div class="v5-work__grid">
        <?php foreach ( $ae_about_work as $w ) : ?>
            <a class="v5-card" href="<?php echo esc_url( home_url( '/work/' ) ); ?>" data-reveal>
                <span class="v5-card__media ae-media">
                    <img src="<?php echo esc_url( $ae_img . 'work/' . $w[2] ); ?>" alt="<?php echo esc_attr( $w[3] ); ?>" loading="lazy" decoding="async" width="1440" height="900">
                </span>
                <span class="v5-card__body">
                    <span class="v5-card__tag"><?php echo esc_html( $w[1] ); ?></span>
                    <span class="v5-card__title"><?php echo esc_html( $w[0] ); ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php if ( ! empty( $ae_testimonials ) ) : ?>
<!-- Testimonials: renders only when real, approved entries exist above. -->
<section class="ae-section ae-testimonials" aria-labelledby="about-quotes">
    <header class="v5-section-head">
        <h2 class="v5-section-title" id="about-quotes">What clients say</h2>
    </header>
    <div class="v5-work__grid">
        <?php foreach ( $ae_testimonials as $t ) : ?>
            <figure class="ae-quote" data-reveal>
                <blockquote class="ae-quote__text"><?php echo esc_html( $t['quote'] ); ?></blockquote>
                <figcaption class="ae-quote__who">
                    <?php echo esc_html( $t['name'] ); ?>
                    <span class="ae-quote__biz"><?php echo esc_html( $t['business'] ); ?></span>
                    <?php if ( ! empty( $t['source'] ) ) : ?>
                        <a class="ae-quote__src v5-link" href="<?php echo esc_url( $t['source'] ); ?>" rel="noopener">Source</a>
                    <?php endif; ?>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<ae-cta></ae-cta>

<?php get_footer(); ?>
