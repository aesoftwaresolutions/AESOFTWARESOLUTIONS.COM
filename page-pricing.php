<?php
/**
 * Template Name: Pricing
 *
 * The full offer ladder. All prices and bullets come from inc/offers.php.
 * No price is typed in this template.
 *
 * @package AE_Softwares
 */

get_header();
$o = aesoftwares_offers();

/**
 * One offer card. Order: name, price, cadence, pitch, points, guarantee, button.
 * The featured card gets a 2px ink border and the gold "Recommended" flag.
 */
function aesoftwares_offer_card( $item, $extra_class = '' ) {
	$featured = ! empty( $item['featured'] );
	?>
	<article class="ae-offer<?php echo $featured ? ' ae-offer--featured' : ''; ?> <?php echo esc_attr( $extra_class ); ?>" id="<?php echo esc_attr( $item['id'] ); ?>" data-reveal>
		<?php if ( $featured ) : ?><span class="ae-offer__flag">Recommended</span><?php endif; ?>
		<h3 class="ae-offer__name"><?php echo esc_html( $item['name'] ); ?></h3>
		<p class="ae-offer__price">
			<?php if ( ! empty( $item['was'] ) ) : ?><s><?php echo esc_html( $item['was'] ); ?></s><?php endif; ?>
			<?php echo esc_html( $item['price'] ); ?>
		</p>
		<p class="ae-offer__cadence"><?php echo esc_html( $item['cadence'] ); ?></p>
		<p class="ae-offer__pitch"><?php echo esc_html( $item['pitch'] ); ?></p>
		<?php if ( ! empty( $item['points'] ) ) : ?>
		<ul class="ae-offer__points">
			<?php foreach ( $item['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<?php if ( ! empty( $item['guarantee'] ) ) : ?>
			<p class="ae-offer__guarantee">60 day guarantee</p>
		<?php endif; ?>
		<a class="<?php echo $featured ? 'v5-button' : 'ae-offer__cta'; ?>" href="<?php echo esc_url( $item['cta'][1] ); ?>"><?php echo esc_html( $item['cta'][0] ); ?></a>
	</article>
	<?php
}

/**
 * One inline call to action under a pricing group.
 */
function aesoftwares_pricing_cta_row() {
	?>
	<p class="ae-cta-row" data-reveal>
		<span>Not sure which one fits?</span>
		<a class="v5-textlink" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Tell us about the project &rarr;</a>
	</p>
	<?php
}
?>

<section class="page-hero pricing-hero">
	<p class="page-hero__eyebrow">Pricing</p>
	<h1 class="page-hero__headline">Clear prices. No surprises.</h1>
	<p class="page-hero__subtitle">Websites, Google presence and follow up automation for small businesses everywhere. Start free, build once, and add monthly care when you want it.</p>
</section>

<!-- The four steps of the ladder: a sticky jump bar (the active step is marked while you scroll) -->
<nav class="ae-jump" aria-label="Pricing sections">
	<div class="ae-jump__inner">
		<a class="ae-jump__step" href="#start">Start</a>
		<a class="ae-jump__step" href="#websites">Build</a>
		<a class="ae-jump__step" href="#monthly">Keep it working</a>
		<a class="ae-jump__step" href="#extras">Extras</a>
		<a class="ae-jump__step" href="#launch">Launch Package</a>
	</div>
</nav>

<section class="ae-pricing" id="start" aria-labelledby="pr-start">
	<header class="v5-section-head">
		<p class="v5-label" data-reveal>Find the leaks first</p>
		<h2 class="v5-section-title" id="pr-start" data-reveal>Start here</h2>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $o['start'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
	<?php aesoftwares_pricing_cta_row(); ?>
</section>

<section class="ae-pricing" id="websites" aria-labelledby="pr-web">
	<header class="v5-section-head">
		<h2 class="v5-section-title" id="pr-web" data-reveal>Websites</h2>
		<a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" data-reveal>&rarr; See website examples</a>
	</header>
	<div class="ae-offers">
		<?php foreach ( $o['websites'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
	<?php aesoftwares_pricing_cta_row(); ?>
</section>

<section class="ae-pricing" id="monthly" aria-labelledby="pr-monthly">
	<header class="v5-section-head">
		<p class="v5-label" data-reveal>Monthly &middot; cancel anytime</p>
		<h2 class="v5-section-title" id="pr-monthly" data-reveal>Keep it working</h2>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $o['monthly'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
	<p class="ae-guarantee" data-reveal><strong>Our guarantee.</strong> <?php echo esc_html( $o['guarantee'] ); ?></p>
	<?php aesoftwares_pricing_cta_row(); ?>
</section>

<section class="ae-pricing" id="extras" aria-labelledby="pr-extras">
	<header class="v5-section-head">
		<p class="v5-label" data-reveal>One time and yearly</p>
		<h2 class="v5-section-title" id="pr-extras" data-reveal>Extras</h2>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $o['extras'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
</section>

<section class="ae-pricing ae-launch ae-alt" id="launch" aria-labelledby="pr-launch">
	<?php $b = $o['bundle']; ?>
	<div class="ae-launch__inner">
		<div class="ae-launch__copy">
			<p class="v5-label" data-reveal>Best value for a new business</p>
			<h2 class="ae-launch__title" id="pr-launch" data-reveal><?php echo esc_html( $b['name'] ); ?></h2>
			<p class="ae-launch__pitch" data-reveal><?php echo esc_html( $b['pitch'] ); ?></p>
		</div>
		<div class="ae-launch__deal" data-reveal>
			<p class="ae-offer__price"><?php echo esc_html( $b['price'] ); ?></p>
			<p class="ae-offer__cadence"><?php echo esc_html( $b['cadence'] ); ?></p>
			<ul class="ae-offer__points">
				<?php foreach ( $b['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
			</ul>
			<a class="v5-button" href="<?php echo esc_url( $b['cta'][1] ); ?>"><?php echo esc_html( $b['cta'][0] ); ?></a>
		</div>
	</div>
</section>

<!-- Terms: a two column paper-2 band -->
<section class="ae-pricing ae-terms ae-alt" aria-label="Terms">
	<div class="ae-terms__inner">
		<p class="v5-label" data-reveal>The small print, in plain English</p>
		<p class="ae-terms__text" data-reveal><?php echo esc_html( $o['terms'] ); ?></p>
	</div>
</section>

<ae-cta></ae-cta>

<?php get_footer(); ?>
