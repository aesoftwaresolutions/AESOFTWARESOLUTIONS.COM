<?php
/**
 * Front page — v5 editorial layout.
 *
 * After the hero, the page is a short index of topics. Each topic is a
 * button that opens its panel (ae-motion.js: initTopics), so visitors pick
 * what they want to read instead of scrolling through everything at once.
 * Panel content stays in the HTML, so search engines still read all of it.
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

$ae_offers = aesoftwares_offers();
$ae_offer  = function ( $id ) use ( $ae_offers ) {
	foreach ( [ 'start', 'websites', 'monthly' ] as $group ) {
		foreach ( $ae_offers[ $group ] as $item ) {
			if ( $item['id'] === $id ) {
				return $item;
			}
		}
	}
	return $ae_offers['bundle']['id'] === $id ? $ae_offers['bundle'] : null;
};
$ae_starter  = $ae_offer( 'starter-site' );
$ae_presence = $ae_offer( 'online-presence' );
$ae_desk     = $ae_offer( 'front-desk' );
$ae_launch   = $ae_offer( 'launch' );
$ae_audit    = $ae_offer( 'audit' );

/**
 * Opens one topic row: the button (inside an h3) and the start of its panel.
 */
$ae_topic_open = function ( $slug, $num, $title, $teaser ) {
	?>
	<div class="v5-topic" id="<?php echo esc_attr( $slug ); ?>" data-topic>
		<h3 class="v5-topic__head" data-reveal>
			<button class="v5-topic__btn" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $slug ); ?>-panel" id="<?php echo esc_attr( $slug ); ?>-btn">
				<span class="v5-topic__num"><?php echo esc_html( $num ); ?></span>
				<span class="v5-topic__title"><?php echo esc_html( $title ); ?></span>
				<span class="v5-topic__teaser"><?php echo esc_html( $teaser ); ?></span>
				<span class="v5-topic__icon" aria-hidden="true"></span>
			</button>
		</h3>
		<div class="v5-topic__panel" id="<?php echo esc_attr( $slug ); ?>-panel" role="region" aria-labelledby="<?php echo esc_attr( $slug ); ?>-btn">
			<div class="v5-topic__inner">
	<?php
};
$ae_topic_close = function () {
	echo "\t\t\t</div>\n\t\t</div>\n\t</div>\n";
};

?>

<!-- 1. HERO -->
<section class="v5-hero" aria-labelledby="v5-hero-title">
  <canvas class="ae-hero-motion" data-concept="lines" aria-hidden="true"></canvas>
  <img class="ae-hero-art" src="<?php echo esc_url( $ae_img . 'graphics/ae-hero-v2.svg' ); ?>" alt="" aria-hidden="true" width="1600" height="900" decoding="async">
  <p class="v5-hero__kicker v5-label" data-reveal>[ Website design, online presence &amp; automation ]</p>
  <h1 class="v5-hero__title" id="v5-hero-title">
    <span class="v5-hero__line" data-split data-split-delay="0">Helping your</span>
    <span class="v5-hero__line" data-split data-split-delay="0.08">business work</span>
    <span class="v5-hero__line v5-hero__line--last">
      <span class="v5-hero__accent" data-split data-split-delay="0.16">better.</span>
    </span>
  </h1>

  <div class="v5-hero__meta ae-rule" data-reveal>
    <p class="v5-label" data-reveal>[ Focus ]<br>Business systems &amp; websites</p>
    <p class="v5-hero__lead" data-reveal>
      We partner with small businesses to untangle daily workflows, build clear customer-facing websites, and put practical systems in place that free up your time.
    </p>
    <p class="v5-label v5-label--end" data-reveal>Working with clients nationwide</p>
  </div>
</section>

<img class="ae-divider" src="<?php echo esc_url( $ae_img . 'graphics/ae-divider.svg' ); ?>" alt="" aria-hidden="true" width="1600" height="40" loading="lazy" decoding="async">

<!-- 2. TOPICS — pick one to open it -->
<section class="v5-topics" id="topics" aria-labelledby="v5-topics-title">
  <header class="v5-section-head ae-rule">
    <h2 class="v5-section-title" id="v5-topics-title" data-split>Start with what matters to you</h2>
    <span class="v5-label" data-reveal>[ Open a topic to read more ]</span>
  </header>

  <?php $ae_topic_open( 'approach', '01', 'How we work', 'Your week first, software second' ); ?>
    <div class="v5-approach v5-approach--panel">
      <p class="v5-approach__statement">
        We start with how your week really runs: the calls, the quotes, the follow ups. Then we build the smallest system that takes that work off your plate. <span class="v5-muted">You keep the keys to everything we set up.</span>
      </p>
      <ul class="v5-approach__links">
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">&rarr; Our process, step by step</a></li>
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">&rarr; About the studio</a></li>
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">&rarr; Book a discovery call</a></li>
      </ul>
    </div>
  <?php $ae_topic_close(); ?>

  <?php $ae_topic_open( 'work', '02', 'Selected work', 'Recent client websites' ); ?>
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
    <p class="v5-topic__more"><a class="v5-link" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">&rarr; See all work</a></p>
  <?php $ae_topic_close(); ?>

  <?php $ae_topic_open( 'in-action', '03', 'See it in action', 'A missed call at 6:42 pm, handled' ); ?>
    <div class="v5-seq">
      <div class="v5-seq__intro">
        <p class="v5-seq__lead">You&rsquo;re on a job. The phone rings and you can&rsquo;t pick up. Here&rsquo;s what happens next with a system we&rsquo;ve set up.</p>
        <p class="v5-label v5-seq__note">Example scenario &middot; not a real customer</p>
      </div>

      <ol class="v5-seq__log" aria-label="Example timeline">
        <li class="v5-seq__step" data-step>
          <time>6:42 pm</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2" /><path d="M15 3l6 6M21 3l-6 6"/></svg></span>
          <div><strong>Missed call</strong><span>New number, no voicemail.</span></div>
        </li>
        <li class="v5-seq__step" data-step>
          <time>6:42 pm</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 5h16v11H8l-4 4z"/></svg></span>
          <div><strong>Text sent automatically</strong><span>&ldquo;Sorry we missed you. What can we help with? Reply here and we&rsquo;ll get you a quote.&rdquo;</span></div>
        </li>
        <li class="v5-seq__step" data-step>
          <time>6:49 pm</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 5H4v11h12l4 4z"/></svg></span>
          <div><strong>Customer replies</strong><span>&ldquo;Water heater is leaking. Can someone come tomorrow?&rdquo;</span></div>
        </li>
        <li class="v5-seq__step" data-step>
          <time>6:49 pm</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 9h8M8 13h5"/></svg></span>
          <div><strong>Lead saved to your CRM</strong><span>Name, number, job and urgency, tagged &ldquo;new&rdquo;.</span></div>
        </li>
        <li class="v5-seq__step" data-step>
          <time>6:50 pm</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/></svg></span>
          <div><strong>Booking link sent</strong><span>They pick 9:30 am tomorrow. It lands on your calendar.</span></div>
        </li>
        <li class="v5-seq__step v5-seq__step--done" data-step>
          <time>7:00 am</time>
          <span class="v5-seq__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
          <div><strong>You start the day with a booked job</strong><span>No call back tag, no lost lead.</span></div>
        </li>
      </ol>
    </div>
  <?php $ae_topic_close(); ?>

  <?php $ae_topic_open( 'services', '04', 'What we do', 'Websites, Google presence, follow up' ); ?>
    <ol class="v5-services__list">
      <li class="v5-service">
        <span class="v5-service__num">01</span>
        <h4 class="v5-service__title">Website design</h4>
        <p class="v5-service__text">Fast, mobile first websites that explain what you do and turn visitors into calls, bookings and quote requests. <?php echo esc_html( ucfirst( $ae_starter['price'] ) ); ?>, Google profile setup included.</p>
        <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>">Website design</a>
      </li>
      <li class="v5-service">
        <span class="v5-service__num">02</span>
        <h4 class="v5-service__title">Online presence</h4>
        <p class="v5-service__text">Google Business Profile, reviews and listings looked after every month so you&rsquo;re the business people find first. <?php echo esc_html( $ae_presence['name'] . ', ' . $ae_presence['price'] ); ?>/month.</p>
        <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>">Online presence</a>
      </li>
      <li class="v5-service">
        <span class="v5-service__num">03</span>
        <h4 class="v5-service__title">Follow up automation</h4>
        <p class="v5-service__text">Missed call text back, instant lead replies, booking links and review requests that run while you&rsquo;re on the job. <?php echo esc_html( $ae_desk['name'] . ' from ' . $ae_desk['price'] ); ?>/month.</p>
        <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/pricing/#monthly' ) ); ?>">Front Desk</a>
      </li>
      <li class="v5-service">
        <span class="v5-service__num">04</span>
        <h4 class="v5-service__title">Missed-Lead Audit</h4>
        <p class="v5-service__text">Not sure where to start? We find where inquiries slip through and what it costs you. <?php echo esc_html( $ae_audit['price'] ); ?>, credited in full if you go ahead.</p>
        <a class="v5-link v5-service__more" href="<?php echo esc_url( home_url( '/pricing/#audit' ) ); ?>">Audit</a>
      </li>
    </ol>
  <?php $ae_topic_close(); ?>

  <?php $ae_topic_open( 'pricing', '05', 'Pricing', 'Websites ' . $ae_starter['price'] ); ?>
    <ul class="v5-prices">
      <?php
      $ae_rows = [
        [ $ae_starter, '', home_url( '/pricing/#websites' ) ],
        [ $ae_presence, '/mo', home_url( '/pricing/#monthly' ) ],
        [ $ae_desk, '/mo', home_url( '/pricing/#monthly' ) ],
        [ $ae_launch, '', home_url( '/pricing/#launch' ) ],
      ];
      foreach ( $ae_rows as $row ) :
        list( $item, $per, $link ) = $row;
        ?>
        <li class="v5-prices__row">
          <a href="<?php echo esc_url( $link ); ?>">
            <span class="v5-prices__name"><?php echo esc_html( $item['name'] ); ?></span>
            <span class="v5-prices__pitch"><?php echo esc_html( $item['pitch'] ); ?></span>
            <span class="v5-prices__price"><?php echo esc_html( ucfirst( $item['price'] ) . $per ); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="v5-topic__more"><a class="v5-link" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">&rarr; See full pricing and what&rsquo;s included</a></p>
  <?php $ae_topic_close(); ?>
</section>

<!-- 5. CLOSING CTA -->
<section class="v5-closing ae-rule" aria-labelledby="v5-closing-title">
  <h2 class="v5-closing__title" id="v5-closing-title" data-split>Have a process that eats your week? Let&rsquo;s take it apart.</h2>
  <a class="v5-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" data-reveal>Book a discovery call</a>
</section>

<?php
get_footer();
