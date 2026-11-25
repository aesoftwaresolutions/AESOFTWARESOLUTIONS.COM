<?php
/**
 * Product carousel: the four product graphics that used to sit behind the
 * home hero. Now the backdrop of the Services hero (page-services.php).
 *
 * Call ae_hero_carousel_enqueue() before get_header() so the script prints in
 * the footer, then ae_hero_carousel() inside a section with the classes
 * "v5-hero v5-hero--carousel". Styles: the HERO CAROUSEL block in ae-v5.css.
 * Behaviour: assets/js/ae-hero-carousel.js.
 *
 * @package AE_Softwares
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slides, in order. The node labels drawn on each graphic are repeated in its
 * alt text.
 *
 * @return array<int, array<string, string>>
 */
function ae_hero_carousel_slides() {
	return [
		[
			'slug'  => 'front-desk',
			'name'  => 'Front Desk',
			'line'  => 'AI phone answering that captures every caller’s details.',
			'price' => 'from $99/mo',
			'url'   => home_url( '/front-desk/' ),
			'alt'   => 'Front Desk: Call in, Details captured, Summary sent.',
		],
		[
			'slug'  => 'always-on-front-desk',
			'name'  => 'Always-On Front Desk',
			'line'  => '24/7 AI phone answering so nights and weekends never go to voicemail.',
			'price' => '$250/mo',
			'url'   => home_url( '/always-on-front-desk/' ),
			'alt'   => 'Always-On Front Desk: Set schedule and rules, Connect your line, Wake up to organized leads.',
		],
		[
			'slug'  => 'online-presence-management',
			'name'  => 'Online Presence Management',
			'line'  => 'Ongoing management of your Google Business Profile, reviews, and listings.',
			'price' => '$199/mo',
			'url'   => home_url( '/online-presence/' ),
			'alt'   => 'Online Presence Management: Audit and claim, Clean up and align, Ongoing upkeep.',
		],
		[
			'slug'  => 'websites',
			'name'  => 'Websites',
			'line'  => 'Websites built to bring in calls and book jobs.',
			'price' => 'from $495',
			'url'   => home_url( '/websites/' ),
			'alt'   => 'Websites: Scope and structure, Design and build, Review and launch.',
		],
	];
}

/**
 * Enqueue the carousel script. Kept out of LiteSpeed "delay JS" so the glide
 * starts without waiting for the first touch or key press.
 */
function ae_hero_carousel_enqueue() {
	$file = get_template_directory() . '/assets/js/ae-hero-carousel.js';
	wp_enqueue_script(
		'ae-hero-carousel',
		get_template_directory_uri() . '/assets/js/ae-hero-carousel.js',
		[],
		file_exists( $file ) ? filemtime( $file ) : '1.0.0',
		[ 'in_footer' => true, 'strategy' => 'defer' ]
	);
	add_filter( 'script_loader_tag', function ( $tag, $handle ) {
		if ( 'ae-hero-carousel' === $handle && false === strpos( $tag, 'data-no-optimize' ) ) {
			$tag = str_replace( '<script ', '<script data-no-optimize="1" data-no-defer="1" data-cfasync="false" ', $tag );
		}
		return $tag;
	}, 10, 2 );
}

/**
 * Print the carousel. No visible controls; slides after the first stay inert
 * until the script shows them, so keyboard focus never lands off screen.
 */
function ae_hero_carousel() {
	$img    = get_template_directory_uri() . '/assets/img/carousel/';
	$slides = ae_hero_carousel_slides();
	?>
  <div class="ae-hc" data-ae-hc role="region" aria-roledescription="carousel" aria-label="Our products">
    <div class="ae-hc__track" data-ae-hc-track>
      <?php foreach ( $slides as $i => $slide ) : ?>
        <article class="ae-hc__slide" data-ae-hc-slide aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' of ' . count( $slides ) . ': ' . $slide['name'] ); ?>"<?php echo $i > 0 ? ' inert aria-hidden="true"' : ''; ?>>
          <?php /* data-no-lazy and skip-lazy keep LiteSpeed lazy load off these images: its placeholder only
                   swaps once a slide is in view, after window load, so slides glided in black. */ ?>
          <img class="ae-hc__img skip-lazy" data-no-lazy="1" data-skip-lazy="1"
            src="<?php echo esc_url( $img . $slide['slug'] . '.jpg' ); ?>"
            srcset="<?php echo esc_url( $img . $slide['slug'] . '-960.jpg' ); ?> 960w, <?php echo esc_url( $img . $slide['slug'] . '.jpg' ); ?> 1920w"
            sizes="(max-width: 1023px) 160vw, 100vw"
            alt="<?php echo esc_attr( $slide['alt'] ); ?>"
            width="1920" height="1200" decoding="async"
            <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
          <div class="ae-hc__cap">
            <h2 class="ae-hc__name"><a href="<?php echo esc_url( $slide['url'] ); ?>"><?php echo esc_html( $slide['name'] ); ?></a></h2>
            <p class="ae-hc__line"><?php echo esc_html( $slide['line'] ); ?></p>
            <p class="ae-hc__price"><?php echo esc_html( $slide['price'] ); ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
	<?php
}
