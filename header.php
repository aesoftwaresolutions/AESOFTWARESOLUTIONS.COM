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
  // Show the counter loader only on the first page of a visit, and never for
  // people who asked for reduced motion. Runs before paint, so no flash.
  (function () {
    try {
      var seen = sessionStorage.getItem('ae_loader_seen') === '1';
      var calm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (seen || calm) { document.documentElement.classList.add('ae-skip-loader'); }
      sessionStorage.setItem('ae_loader_seen', '1');
    } catch (e) {}
  })();
</script>
<style>.ae-skip-loader #ae-loader{display:none!important}</style>
<noscript><style>
  #ae-loader{display:none!important}
  [data-reveal],[data-reveal] *{opacity:1!important;transform:none!important;clip-path:none!important}
  .mask-line span{transform:none!important}
</style></noscript>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-chrome.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-chrome.css' ) ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-fixes.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-fixes.css' ) ); ?>">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<!-- Page loader: smooth reveal counter (first page of a visit only) -->
<div id="ae-loader" aria-hidden="true" style="position:fixed;inset:0;background:#F7F7F5;z-index:99999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);">
  <div id="ae-loader-num" style="font-family:'Space Grotesk',monospace;font-size:3rem;font-weight:600;color:#121212;">0%</div>
  <div style="font-family:'Space Grotesk',monospace;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.18em;color:#6B6B67;margin-top:8px;">AE Software Solutions</div>
</div>

<header class="site-header">
  <div class="site-header__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
      AE Software
      <span class="sub">Pennsylvania [US]</span>
    </a>

    <nav class="site-header__nav" aria-label="Main">
      <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link">Services<sup>04</sup></a>
      <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="site-header__nav-link">Work<sup>03</sup></a>
      <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="site-header__nav-link">Websites</a>
      <a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="site-header__nav-link">Pricing</a>
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
      <li><a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="mobile-nav-overlay__link">Pricing</a></li>
      <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="mobile-nav-overlay__link">About</a></li>
    </ul>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn mobile-nav-overlay__cta">Get in touch</a>
  </nav>
  <p class="mobile-nav-overlay__footer">anthony@aesoftwaresolutions.com &middot; Chambersburg, PA</p>
</div>

<main class="site-main" id="main-content">
