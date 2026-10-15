<?php
/**
 * The header for our theme — v4.0 Matthieu Givelet Inspired
 *
 * @package AE_Softwares
 */

$ae_theme_uri  = get_template_directory_uri();
$ae_theme_path = get_template_directory();
$ae_asset_ver  = function ( $rel ) use ( $ae_theme_path ) {
	$file = $ae_theme_path . $rel;
	return file_exists( $file ) ? filemtime( $file ) : '4.3.0';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<script>
  // Runs before first paint.
  //  ae-js            : motion CSS may hide elements that ae-motion.js will reveal
  //  ae-skip-loader   : the counter loader shows only on the first page of a visit
  //  ae-arriving      : we got here through a page transition, so start covered
  //  ae-motion-failed : safety net, reveals everything if the motion script never runs
  (function () {
    var html = document.documentElement;
    html.classList.add('ae-js');
    try {
      var calm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (calm) { html.classList.add('ae-calm'); }
      if (sessionStorage.getItem('ae_loader_seen') === '1' || calm) { html.classList.add('ae-skip-loader'); }
      sessionStorage.setItem('ae_loader_seen', '1');
      if (!calm && sessionStorage.getItem('ae_transition') === '1') { html.classList.add('ae-arriving'); }
      sessionStorage.removeItem('ae_transition');
    } catch (e) {}
    setTimeout(function () {
      if (!html.classList.contains('ae-motion-ready')) { html.classList.add('ae-motion-failed'); }
    }, 4000);
  })();
</script>
<style>
  .ae-skip-loader #ae-loader{display:none!important}
  .ae-arriving #ae-transition{transform:translateY(0)!important}
</style>
<noscript><style>
  #ae-loader{display:none!important}
  [data-reveal],[data-reveal] *{opacity:1!important;transform:none!important;clip-path:none!important}
  .mask-line span{transform:none!important}
</style></noscript>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-chrome.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-chrome.css' ) ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-fixes.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-fixes.css' ) ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-v5.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-v5.css' ) ); ?>">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<!-- Page loader: first page of a visit only; the counter tracks real image/font loading (ae-motion.js) -->
<div id="ae-loader" aria-hidden="true">
  <span id="ae-loader-num" class="ae-loader__num">0%</span>
  <span class="ae-loader__name">AE Software Solutions</span>
</div>

<!-- Page transition panel (ae-motion.js) -->
<div id="ae-transition" aria-hidden="true"></div>

<header class="site-header">
  <div class="site-header__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
      AE Software Solutions
    </a>

    <nav class="site-header__nav" aria-label="Main">
      <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link">Services<sup>04</sup></a>
      <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="site-header__nav-link">Work<sup>07</sup></a>
      <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="site-header__nav-link">Websites</a>
      <a href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>" class="site-header__nav-link">Presence</a>
      <a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>" class="site-header__nav-link">Pricing</a>
      <a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>" class="site-header__nav-link">Guides</a>
      <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="site-header__nav-link">About</a>
    </nav>

    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__cta btn">Get in touch</a>

    <!-- Hamburger: shown below 960px by ae-fixes.css, wired up in ae-animations.js -->
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
      <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="mobile-nav-overlay__link">Services</a></li>
      <li><a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="mobile-nav-overlay__link">Work</a></li>
      <li><a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="mobile-nav-overlay__link">Websites</a></li>
      <li><a href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>" class="mobile-nav-overlay__link">Online presence</a></li>
      <li><a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>" class="mobile-nav-overlay__link">Pricing</a></li>
      <li><a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>" class="mobile-nav-overlay__link">Guides</a></li>
      <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="mobile-nav-overlay__link">About</a></li>
    </ul>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn mobile-nav-overlay__cta">Get in touch</a>
  </nav>
  <p class="mobile-nav-overlay__footer">anthony@aesoftwaresolutions.com &middot; Chambersburg, PA</p>
</div>

<main class="site-main" id="main-content">
