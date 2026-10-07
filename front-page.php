<?php
/**
 * Front page — v5 editorial layout.
 *
 * Motion is driven by assets/js/ae-motion.js:
 *   [data-split]  headings rise line by line from a mask
 *   [data-reveal] blocks fade/rise in when they enter the viewport
 *   .ae-rule      hairlines draw left to right
 *   .ae-media     images fade and settle from a slight zoom
 *
 * @package AE_Softwares
 */

get_header();

$ae_img = get_template_directory_uri() . '/assets/img/';

// Selected work: real client sites. (The industry templates still show
// {{PLACEHOLDER}} text, so they stay on /websites/ as examples, not here.)
// Screenshots live in assets/img/work/ (captured from the live sites).
$ae_work = [
	[
		'title' => 'Monarch MVC',
		'type'  => 'Client',
		'tag'   => 'Health platform',
		'img'   => $ae_img . 'work/monarch-mvc.jpg',
		'alt'   => 'Monarch MVC men’s health platform home page',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Patient portal and provider dashboard for a clinician-led men’s health practice.',
	],
	[
		'title' => 'Learn English with Evie',
		'type'  => 'Client',
		'tag'   => 'Education',
		'img'   => $ae_img . 'work/learn-english-with-evie.jpg',
		'alt'   => 'Learn English with Evie lesson booking site',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Brand site that turns YouTube and podcast listeners into booked 1-on-1 lessons.',
	],
	[
		'title' => 'Anthony Pioquinto',
		'type'  => 'Client',
		'tag'   => 'Personal brand',
		'img'   => $ae_img . 'work/anthony-pioquinto.jpg',
		'alt'   => 'Anthony Pioquinto real estate portfolio site',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Editorial portfolio for a business development manager in real estate lending.',
	],
];

// The image window in the headline cycles through the same screenshots.
$ae_window = array_column( $ae_work, 'img' );
?>

<!-- 1. HERO -->
<section class="v5-hero" aria-labelledby="v5-hero-title">
  <h1 class="v5-hero__title" id="v5-hero-title">
    <span class="v5-hero__line" data-split data-split-delay="0">Helping your</span>
    <span class="v5-hero__line" data-split data-split-delay="0.08">business work</span>
    <span class="v5-hero__line v5-hero__line--last">
      <span class="v5-hero__accent" data-split data-split-delay="0.16">better.</span>
      <span class="v5-window" aria-hidden="true">
        <?php foreach ( $ae_window as $i => $src ) : ?>
          <img src="<?php echo esc_url( $src ); ?>" alt="" class="v5-window__img<?php echo 0 === $i ? ' is-active' : ''; ?>" decoding="async" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
        <?php endforeach; ?>
      </span>
    </span>
  </h1>

  <div class="v5-hero__meta ae-rule" data-reveal>
    <p class="v5-label" data-reveal>[ Focus ]<br>Business systems &amp; websites</p>
    <p class="v5-hero__lead" data-reveal>
      We partner with small businesses to untangle daily workflows, build clear customer-facing websites, and put practical systems in place that free up your time.
    </p>
    <p class="v5-label v5-label--end" data-reveal>[ Based in ]<br>Chambersburg, PA<br>Working with clients nationwide</p>
  </div>
</section>

<!-- 2. APPROACH -->
<section class="v5-approach ae-rule" id="approach">
  <p class="v5-label" data-reveal>[ Approach ]</p>

  <p class="v5-approach__statement" data-split>
    <span class="v5-approach__num">01</span>
    We start with how your week really runs &mdash; the calls, the quotes, the follow-ups &mdash; and build the smallest system that takes that work off your plate. <span class="v5-muted">You keep the keys to everything we set up.</span>
  </p>

  <ul class="v5-approach__links" data-reveal>
    <li><a class="v5-link" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">&rarr; How we work</a></li>
    <li><a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">&rarr; Book a discovery call</a></li>
  </ul>
</section>

<!-- 3. SELECTED WORK -->
<section class="v5-work" id="work" aria-labelledby="v5-work-title">
  <header class="v5-section-head ae-rule">
    <h2 class="v5-section-title" id="v5-work-title" data-split>Selected work</h2>
    <a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/work/' ) ); ?>" data-reveal>&rarr; See all work</a>
  </header>

  <div class="v5-work__grid">
    <?php foreach ( $ae_work as $i => $item ) : ?>
      <a class="v5-card<?php echo 0 === $i ? ' v5-card--wide' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>" data-reveal>
        <span class="v5-card__media ae-media">
          <img src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" loading="lazy" decoding="async" width="1440" height="900">
        </span>
        <span class="v5-card__caption">
          <span class="v5-card__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
          <span class="v5-card__title"><?php echo esc_html( $item['title'] ); ?></span>
          <span class="v5-card__tag"><?php echo esc_html( $item['type'] . ' · ' . $item['tag'] ); ?></span>
        </span>
        <span class="v5-card__desc"><?php echo esc_html( $item['desc'] ); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- 4. WHAT WE DO -->
<section class="v5-services" id="services" aria-labelledby="v5-services-title">
  <header class="v5-section-head ae-rule">
    <h2 class="v5-section-title" id="v5-services-title" data-split>What we do</h2>
    <span class="v5-label" data-reveal>[ Four ways in ]</span>
  </header>

  <ol class="v5-services__list">
    <li class="v5-service ae-rule" data-reveal>
      <span class="v5-service__num">01</span>
      <h3 class="v5-service__title">Business review</h3>
      <p class="v5-service__text">We sit with how your team works today, find where hours and leads slip away, and hand you a plain-English plan.</p>
      <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Details</a>
    </li>
    <li class="v5-service ae-rule" data-reveal>
      <span class="v5-service__num">02</span>
      <h3 class="v5-service__title">Workflow automation</h3>
      <p class="v5-service__text">Missed-call text-back, instant lead replies, quote follow-ups and booking reminders that run without anyone remembering to.</p>
      <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Details</a>
    </li>
    <li class="v5-service ae-rule" data-reveal>
      <span class="v5-service__num">03</span>
      <h3 class="v5-service__title">Websites</h3>
      <p class="v5-service__text">Fast, clear sites that explain what you do and turn visitors into calls, bookings and quote requests.</p>
      <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>">Details</a>
    </li>
    <li class="v5-service ae-rule" data-reveal>
      <span class="v5-service__num">04</span>
      <h3 class="v5-service__title">CRM &amp; systems</h3>
      <p class="v5-service__text">One place for leads, client history and documents, set up around how your staff already works.</p>
      <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>">Details</a>
    </li>
  </ol>
</section>

<!-- 5. CLOSING CTA -->
<section class="v5-closing ae-rule" aria-labelledby="v5-closing-title">
  <h2 class="v5-closing__title" id="v5-closing-title" data-split>Have a process that eats your week? Let&rsquo;s take it apart.</h2>
  <a class="v5-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" data-reveal>Book a discovery call</a>
</section>

<?php
get_footer();
