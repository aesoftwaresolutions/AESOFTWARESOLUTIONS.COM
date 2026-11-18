<?php
/**
 * Front page (v6 redesign).
 *
 * Hero with a proof strip, then a short index of topics. Each topic is a
 * native details row (works without JavaScript); panel content stays in the
 * HTML, so search engines still read all of it. Motion is one fade up per
 * block (assets/js/ae-motion.js) and never gates the headline.
 *
 * @package AE_Softwares
 */

get_header();

$ae_img = get_template_directory_uri() . '/assets/img/';

// Selected work: recent websites. (The industry templates stay on /websites/
// as labeled examples, not here.)
// Screenshots live in assets/img/work/ (captured from the live sites).
$ae_work = [
	[
		'title' => 'Monarch MVC',
		'type'  => 'Client',
		'tag'   => 'Health platform',
		'img'   => $ae_img . 'work/monarch-mvc.jpg',
		'alt'   => 'Monarch MVC men’s health platform home page',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Patient portal and provider dashboard for a clinician led men’s health practice.',
	],
	[
		'title' => 'Learn English with Evie',
		'type'  => 'Client',
		'tag'   => 'Education',
		'img'   => $ae_img . 'work/learn-english-with-evie.jpg',
		'alt'   => 'Learn English with Evie lesson booking site',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Brand site that turns YouTube and podcast listeners into booked one on one lessons.',
	],
	[
		'title' => 'Anthony Pioquinto',
		'type'  => 'Anthony&rsquo;s own site',
		'tag'   => 'Personal brand',
		'img'   => $ae_img . 'work/anthony-pioquinto.jpg',
		'alt'   => 'Anthony Pioquinto real estate portfolio site',
		'url'   => home_url( '/work/' ),
		'desc'  => 'Anthony&rsquo;s own portfolio site, built for his work in real estate lending.',
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
 * Opens one topic row: a native details element. Closed by $ae_topic_close().
 */
$ae_topic_open = function ( $slug, $num, $title, $teaser ) {
	?>
	<details class="v5-topic" id="<?php echo esc_attr( $slug ); ?>" name="ae-topics" data-topic>
		<summary class="v5-topic__summary">
			<span class="v5-topic__num"><?php echo esc_html( $num ); ?></span>
			<h3 class="v5-topic__title"><?php echo esc_html( $title ); ?></h3>
			<span class="v5-topic__teaser"><?php echo esc_html( $teaser ); ?></span>
			<span class="v5-topic__icon" aria-hidden="true"></span>
		</summary>
		<div class="v5-topic__inner">
	<?php
};
$ae_topic_close = function () {
	echo "\t\t</div>\n\t</details>\n";
};

?>

<!-- 1. HERO: renders at once, nothing waits on JavaScript -->
<section class="v5-hero" aria-labelledby="v5-hero-title">
  <p class="v5-label">Website design, online presence &amp; automation</p>
  <h1 class="v5-hero__title" id="v5-hero-title">Websites, Google presence, and follow up that wins back missed calls.</h1>
  <p class="v5-hero__lead">
    We work with small businesses to untangle daily workflows, build clear customer facing websites, and put practical systems in place that free up your time.
  </p>
  <div class="v5-hero__actions">
    <a class="v5-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a discovery call</a>
    <a class="v5-textlink" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">See pricing &rarr;</a>
  </div>

  <!-- Proof strip: the two approved stats only, each with its source named -->
  <ul class="v5-proof" aria-label="What the research says">
    <li>
      <strong class="v5-proof__fig">44%</strong>
      <span class="v5-proof__text">of business calls go unanswered</span>
      <span class="v5-proof__src">Invoca, 2026</span>
    </li>
    <li>
      <strong class="v5-proof__fig">97%</strong>
      <span class="v5-proof__text">of consumers read reviews</span>
      <span class="v5-proof__src">BrightLocal, 2026</span>
    </li>
    <li>
      <strong class="v5-proof__fig">47%</strong>
      <span class="v5-proof__text">will not use a business with under 20 reviews</span>
      <span class="v5-proof__src">BrightLocal, 2026</span>
    </li>
  </ul>
</section>

<!-- 1b. SHOWCASE: a recent site inside a dark glass Safari window -->
<?php $ae_show = $ae_work[0]; ?>
<section class="v5-showcase" aria-labelledby="v5-showcase-title">
  <p class="v5-label">A recent build</p>
  <h2 class="v5-showcase__title" id="v5-showcase-title">Here is how our work looks in a browser.</h2>
  <figure class="ae-safari">
    <div class="ae-safari__bar" aria-hidden="true">
      <span class="ae-safari__dots"><i></i><i></i><i></i></span>
      <span class="ae-safari__url">
        <svg viewBox="0 0 12 12" width="10" height="10" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="2.4" y="5.4" width="7.2" height="5" rx="1.3"/><path d="M4 5.4V3.9a2 2 0 0 1 4 0v1.5"/></svg>
        aesoftwaresolutions.com/work
      </span>
      <span class="ae-safari__spacer"></span>
    </div>
    <a class="ae-safari__view" href="<?php echo esc_url( $ae_show['url'] ); ?>">
      <img src="<?php echo esc_url( $ae_show['img'] ); ?>" alt="<?php echo esc_attr( $ae_show['alt'] ); ?>" width="1440" height="900" loading="lazy" decoding="async">
    </a>
    <figcaption class="ae-safari__cap"><?php echo esc_html( $ae_show['title'] ); ?>: <?php echo esc_html( $ae_show['desc'] ); ?></figcaption>
  </figure>
</section>

<!-- 2. TOPICS: pick one to open it -->
<section class="v5-topics" id="topics" aria-labelledby="v5-topics-title">
  <header class="v5-section-head" data-reveal>
    <p class="v5-label">Open a topic to read more</p>
    <h2 class="v5-section-title" id="v5-topics-title">Start with what matters to you</h2>
  </header>

  <?php $ae_topic_open( 'approach', '01', 'How we work', 'Your week first, software second' ); ?>
    <div class="v5-approach v5-approach--panel">
      <p class="v5-approach__statement">
        We start with how your week really runs: the calls, the quotes, the follow ups. Then we build the smallest system that takes that work off your plate. <span class="v5-muted">You keep the keys to everything we set up.</span>
      </p>
      <ul class="v5-approach__links">
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">&rarr; Our process, step by step</a></li>
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">&rarr; About Anthony</a></li>
        <li><a class="v5-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">&rarr; Book a discovery call</a></li>
      </ul>
    </div>
  <?php $ae_topic_close(); ?>

  <?php $ae_topic_open( 'work', '02', 'Recent websites', 'Sites we have built' ); ?>
    <div class="v5-work__grid">
      <?php foreach ( $ae_work as $item ) : ?>
        <a class="v5-card" href="<?php echo esc_url( $item['url'] ); ?>">
          <span class="v5-card__media ae-media">
            <img src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" loading="lazy" decoding="async" width="1440" height="900">
          </span>
          <span class="v5-card__body">
            <span class="v5-card__tag"><?php echo esc_html( $item['type'] . ' · ' . $item['tag'] ); ?></span>
            <span class="v5-card__title"><?php echo esc_html( $item['title'] ); ?></span>
            <span class="v5-card__desc"><?php echo esc_html( $item['desc'] ); ?></span>
          </span>
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

<!-- 5. HOSTING WE RECOMMEND -->
<section class="v5-hosting ae-alt" aria-labelledby="v5-hosting-title">
  <header class="v5-section-head">
    <h2 class="v5-section-title" id="v5-hosting-title">Hosting we recommend</h2>
  </header>
  <p class="v5-muted">Every site we build runs on Hostinger, including this one. If you are setting up hosting for a site we are building for you, start here.</p>
  <p class="v5-hosting__badgewrap">
    <a href="https://www.hostinger.com?REFERRALCODE=1WGANTHONVUP" rel="sponsored noopener" target="_blank"><img src="data:image/png;base64,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" class="v5-hosting__badge" alt="Hostinger Partner" width="640" height="240"></a>
  </p>
</section>

<!-- 6. CLOSING CTA: white, one button, email below -->
<section class="v5-closing" aria-labelledby="v5-closing-title">
  <h2 class="v5-closing__title" id="v5-closing-title">Have a process that eats your week? Let&rsquo;s take it apart.</h2>
  <a class="v5-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a discovery call</a>
  <p class="v5-closing__email">Or write to <a class="v5-link" href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a></p>
</section>

<?php
get_footer();
