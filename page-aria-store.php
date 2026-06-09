<?php
/**
 * Template Name: Aria Store
 *
 * Public storefront for digital assets created by Aria.
 * Lists WooCommerce products tagged aria-generated.
 * Falls back gracefully if WooCommerce is not active.
 */
get_header();
?>

<div class="page-hero aria-store-hero">
  <div class="page-hero__eyebrow">Built by Aria &mdash; AI-Powered</div>
  <h1 class="page-hero__headline">Aria&rsquo;s<br><em>Workshop</em></h1>
  <p class="page-hero__subtitle">Templates, playbooks, and tools that Aria researched, designed, and published. Each product is downloadable instantly after purchase.</p>
</div>

<section class="aria-store-section">
  <div class="aria-store-wrap">

<?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
    <div class="aria-store-notice">
      <p>The store is not yet active — WooCommerce needs to be installed and activated by the site admin.</p>
    </div>
<?php else :
  $products_query = new WP_Query( [
    'post_type'      => 'product',
    'posts_per_page' => 60,
    'post_status'    => 'publish',
    'meta_key'       => '_aria_created',
    'meta_value'     => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ] );
?>

<?php if ( ! $products_query->have_posts() ) : ?>
    <div class="aria-store-empty">
      <div class="aria-store-empty__icon">🌸</div>
      <h2 class="aria-store-empty__headline">Nothing here yet.</h2>
      <p class="aria-store-empty__sub">Aria is still building. Check back soon — products appear here automatically as she creates them.</p>
    </div>
<?php else : ?>

    <div class="aria-store-grid">
      <?php while ( $products_query->have_posts() ) : $products_query->the_post();
        $product    = wc_get_product( get_the_ID() );
        if ( ! $product ) continue;
        $tags       = wp_get_post_terms( get_the_ID(), 'product_tag', [ 'fields' => 'names' ] );
        $tags       = array_diff( $tags, [ 'aria-generated' ] ); // hide the internal tag
        $img        = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
        $created_ts = (int) get_post_meta( get_the_ID(), '_aria_created_at', true );
      ?>
      <div class="aria-product-card">
        <?php if ( $img ) : ?>
        <div class="aria-product-card__img">
          <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
        </div>
        <?php else : ?>
        <div class="aria-product-card__img aria-product-card__img--placeholder">
          <span class="aria-product-card__icon">📄</span>
        </div>
        <?php endif; ?>

        <div class="aria-product-card__body">
          <div class="aria-product-card__badge">Built by Aria</div>
          <h3 class="aria-product-card__name"><?php the_title(); ?></h3>

          <?php if ( $product->get_short_description() ) : ?>
          <p class="aria-product-card__desc"><?php echo wp_kses_post( wp_trim_words( $product->get_short_description(), 24 ) ); ?></p>
          <?php elseif ( get_the_excerpt() ) : ?>
          <p class="aria-product-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
          <?php endif; ?>

          <?php if ( ! empty( $tags ) ) : ?>
          <div class="aria-product-card__tags">
            <?php foreach ( array_slice( $tags, 0, 4 ) as $tag ) : ?>
            <span class="aria-product-card__tag"><?php echo esc_html( $tag ); ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <div class="aria-product-card__footer">
            <span class="aria-product-card__price"><?php echo $product->get_price_html(); ?></span>
            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
               class="aria-product-card__btn"
               data-product-id="<?php echo esc_attr( get_the_ID() ); ?>"
               rel="nofollow">
              Buy Now &rarr;
            </a>
          </div>

          <?php if ( $created_ts ) : ?>
          <div class="aria-product-card__meta">Published <?php echo esc_html( date_i18n( 'M j, Y', $created_ts ) ); ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

<?php endif; // have_posts ?>
<?php endif; // WooCommerce ?>

  </div><!-- .aria-store-wrap -->
</section>

<?php get_footer(); ?>
