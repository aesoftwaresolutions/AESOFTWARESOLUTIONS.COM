<?php
/**
 * Template Name: Pricing
 *
 * The full offer ladder. All prices and bullets come from inc/offers.php.
 *
 * @package AE_Softwares
 */

get_header();
$o = aesoftwares_offers();

/**
 * One offer card.
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
		<ul class="ae-offer__points">
			<?php foreach ( $item['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
		</ul>
		<?php if ( ! empty( $item['guarantee'] ) ) : ?>
			<p class="ae-offer__guarantee">60-day guarantee</p>
		<?php endif; ?>
		<a class="<?php echo $featured ? 'v5-button' : 'ae-offer__cta'; ?>" href="<?php echo esc_url( $item['cta'][1] ); ?>"><?php echo esc_html( $item['cta'][0] ); ?></a>
	</article>
	<?php
}
?>

<section class="page-hero pricing-hero">
	<p class="page-hero__eyebrow">Pricing</p>
	<h1 class="page-hero__headline">Clear prices. No surprises.</h1>
	<p class="page-hero__subtitle">Websites, Google presence and follow-up automation for small businesses in Chambersburg, PA and beyond. Start free, build once, and add monthly care when you want it.</p>
</section>

<!-- Steps of the ladder, as a strip -->
<nav class="ae-ladder ae-rule" aria-label="Pricing sections">
	<a class="ae-ladder__step" href="#start" data-reveal><span class="v5-label">[ 01 ]</span> Start</a>
	<a class="ae-ladder__step" href="#websites" data-reveal><span class="v5-label">[ 02 ]</span> Build</a>
	<a class="ae-ladder__step" href="#monthly" data-reveal><span class="v5-label">[ 03 ]</span> Keep it working</a>
	<a class="ae-ladder__step" href="#launch" data-reveal><span class="v5-label">[ 04 ]</span> Launch Package</a>
</nav>

<section class="ae-pricing" id="start" aria-labelledby="pr-start">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="pr-start" data-split>Start here</h2>
		<span class="v5-label" data-reveal>[ Find the leaks first ]</span>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $o['start'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
</section>

<section class="ae-pricing" id="websites" aria-labelledby="pr-web">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="pr-web" data-split>Websites</h2>
		<a class="v5-link v5-label" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" data-reveal>&rarr; See website examples</a>
	</header>
	<div class="ae-offers">
		<?php foreach ( $o['websites'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
</section>

<section class="ae-pricing" id="monthly" aria-labelledby="pr-monthly">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="pr-monthly" data-split>Keep it working</h2>
		<span class="v5-label" data-reveal>[ Monthly · cancel anytime ]</span>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $o['monthly'] as $item ) { aesoftwares_offer_card( $item ); } ?>
	</div>
	<p class="ae-guarantee" data-reveal><strong>Our guarantee.</strong> <?php echo esc_html( $o['guarantee'] ); ?></p>
</section>

<section class="ae-pricing ae-launch" id="launch" aria-labelledby="pr-launch">
	<?php $b = $o['bundle']; ?>
	<div class="ae-launch__inner ae-rule">
		<div class="ae-launch__copy">
			<p class="v5-label" data-reveal>[ Best value for a new business ]</p>
			<h2 class="ae-launch__title" id="pr-launch" data-split><?php echo esc_html( $b['name'] ); ?></h2>
			<p class="ae-launch__pitch" data-reveal><?php echo esc_html( $b['pitch'] ); ?></p>
		</div>
		<div class="ae-launch__deal" data-reveal>
			<p class="ae-offer__price"><s><?php echo esc_html( $b['was'] ); ?></s> <?php echo esc_html( $b['price'] ); ?></p>
			<p class="ae-offer__cadence"><?php echo esc_html( $b['cadence'] ); ?></p>
			<ul class="ae-offer__points">
				<?php foreach ( $b['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
			</ul>
			<a class="v5-button" href="<?php echo esc_url( $b['cta'][1] ); ?>"><?php echo esc_html( $b['cta'][0] ); ?></a>
		</div>
	</div>
</section>

<section class="ae-pricing ae-terms" aria-label="Terms">
	<p class="v5-label" data-reveal>[ The small print, in plain English ]</p>
	<p class="ae-terms__text" data-reveal><?php echo esc_html( $o['terms'] ); ?></p>
</section>

<ae-cta></ae-cta>

<?php get_footer(); ?>
