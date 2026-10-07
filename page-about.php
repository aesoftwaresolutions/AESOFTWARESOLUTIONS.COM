<?php /* Template Name: About */ get_header(); ?>

<div class="page-hero about-page-hero">
    <div class="page-hero__eyebrow">Who we are</div>
    <h1 class="page-hero__headline">Intelligence before<br><em>automation.</em><br>Always.</h1>
    <p class="page-hero__subtitle" data-reveal-text>AE Software Solutions is a Compliance-Aware Operational Intelligence consultancy. We map your business before we build anything — because the wrong automation at scale is worse than no automation at all.</p>
</div>

<div class="mission-block">
    <blockquote class="mission-pullquote js-fade" style="--delay:0s" data-reveal-text>Our mission is to give regulated businesses the operational clarity they need to grow without outrunning their compliance.</blockquote>
    <div class="mission-body js-fade" style="--delay:0.15s">
        <p>We built AE Software Solutions because we kept seeing the same problem: companies would automate the wrong things, skip the compliance check, and end up with fast-moving liability. The technology wasn't the issue — the discovery process was.</p>
        <p>So we designed a different approach. Every engagement starts with a structured 10-phase business assessment — mapping operations, surfacing compliance exposure, and scoring automation opportunities — before a single workflow gets built.</p>
        <p>Every engagement is led personally by our founder, Anthony, with a network of specialist engineers brought in as the project demands. You always have a human who understands your business, not a rotating team of juniors executing a ticket queue.</p>
    </div>
</div>

<section class="inner-section-pad about-values-section">
    <div class="inner-section-header">
        <span class="section-eyebrow">What we stand for</span>
        <h2 class="section-title">Three principles<br>we never compromise.</h2>
    </div>
    <div class="values-grid">
        <div class="value-card js-fade" style="--delay:0s">
            <span class="value-card__num">01</span>
            <h3 class="value-card__title">Intelligence before automation</h3>
            <p class="value-card__text">We map before we build. Every system we deploy is grounded in a thorough operational assessment — so you're automating the right things, at the right time, with the right controls in place.</p>
        </div>
        <div class="value-card js-fade" style="--delay:0.12s">
            <span class="value-card__num">02</span>
            <h3 class="value-card__title">Compliance as a feature</h3>
            <p class="value-card__text">In regulated industries, compliance isn't a constraint — it's a competitive advantage. We build compliance monitoring and audit trails into every system from day one, not as an afterthought.</p>
        </div>
        <div class="value-card js-fade" style="--delay:0.24s">
            <span class="value-card__num">03</span>
            <h3 class="value-card__title">Results over activity</h3>
            <p class="value-card__text">We measure success by business outcomes — risks reduced, hours saved, revenue protected. Not lines of code written, meetings held, or tickets closed.</p>
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
