<?php
/**
 * The header for our theme (v6 redesign).
 *
 * @package AE_Softwares
 */

$ae_theme_uri  = get_template_directory_uri();
$ae_theme_path = get_template_directory();
$ae_asset_ver  = function ( $rel ) use ( $ae_theme_path ) {
	$file = $ae_theme_path . $rel;
	return file_exists( $file ) ? filemtime( $file ) : '6.0.0';
};

// Current section, for the active link underline. Work, Online presence and
// Social management sit under Services, so that link stays marked on them.
$ae_home_path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
$ae_req_path  = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
if ( '' !== $ae_home_path && 0 === strpos( $ae_req_path, $ae_home_path ) ) {
	$ae_req_path = trim( substr( $ae_req_path, strlen( $ae_home_path ) ), '/' );
}
$ae_req_seg = '' === $ae_req_path ? '' : explode( '/', $ae_req_path )[0];

// Five links. [ label, path, extra sections that count as "inside" this link ].
$ae_nav = [
	[ 'Services', 'services', [ 'work', 'online-presence', 'social-management', 'software', 'industries', 'process' ] ],
	[ 'Websites', 'websites', [] ],
	[ 'Pricing',  'pricing',  [] ],
	[ 'Guides',   'guides',   [] ],
	[ 'About',    'about',    [] ],
];
$ae_current = function ( $slug, $inside ) use ( $ae_req_seg ) {
	if ( $ae_req_seg === $slug && '' !== $slug ) {
		return ' aria-current="page"';
	}
	return in_array( $ae_req_seg, $inside, true ) ? ' aria-current="true"' : '';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<script data-no-optimize="1" data-no-defer="1" data-cfasync="false">
  // Runs before first paint (kept out of LiteSpeed delay JS on purpose).
  //  ae-js            : reveal CSS may hide blocks that ae-motion.js will fade in
  //  ae-calm          : reduced motion, so nothing animates and nothing is hidden
  //  ae-motion-failed : safety net, shows everything if the motion script never runs
  (function () {
    var html = document.documentElement;
    html.classList.add('ae-js');
    try {
      if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { html.classList.add('ae-calm'); }
    } catch (e) {}
    setTimeout(function () {
      if (!html.classList.contains('ae-motion-ready')) { html.classList.add('ae-motion-failed'); }
    }, 4000);
  })();
</script>
<noscript><style>
  [data-reveal],[data-reveal] *,.js-fade{opacity:1!important;transform:none!important}
</style></noscript>
<link rel="preload" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/fonts/plus-jakarta-sans-400.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/fonts/plus-jakarta-sans-700.woff2" as="font" type="font/woff2" crossorigin>
<?php wp_head(); ?>
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-favicon-32.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-favicon-32.png' ) ); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-favicon-16.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-favicon-16.png' ) ); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-apple-touch-icon.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-apple-touch-icon.png' ) ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-v5.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-v5.css' ) ); ?>">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header">
  <div class="site-header__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
      <img src="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-logo-mark.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-logo-mark.png' ) ); ?>" alt="AE Software Solutions" width="30" height="30" class="site-header__logo-mark">
    </a>

    <nav class="site-header__nav" aria-label="Main">
      <?php foreach ( $ae_nav as $item ) : ?>
        <a href="<?php echo esc_url( home_url( '/' . $item[1] . '/' ) ); ?>" class="site-header__nav-link"<?php echo $ae_current( $item[1], $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $item[0] ); ?></a>
      <?php endforeach; ?>
    </nav>

    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__cta btn btn--primary">Get in touch</a>

    <!-- Hamburger: shown below 960px, wired up in ae-motion.js -->
    <button class="nav-toggle" id="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
      <span class="nav-toggle__bar"></span>
      <span class="nav-toggle__bar"></span>
      <span class="nav-toggle__bar"></span>
    </button>
  </div>
</header>

<div class="mobile-nav-overlay" id="mobile-nav">
  <nav aria-label="Mobile">
    <ul class="mobile-nav-overlay__links">
      <?php foreach ( $ae_nav as $item ) : ?>
        <li><a href="<?php echo esc_url( home_url( '/' . $item[1] . '/' ) ); ?>" class="mobile-nav-overlay__link"<?php echo $ae_current( $item[1], $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $item[0] ); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary mobile-nav-overlay__cta">Get in touch</a>
  </nav>
  <p class="mobile-nav-overlay__footer"><a href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a></p>
</div>

<main class="site-main" id="main-content">
