<?php
/**
 * The header for our theme — v4.0 Matthieu Givelet Inspired
 *
 * @package AE_Softwares
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/refresh/ae-chrome.css?v=4.0.0">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Page loader: smooth reveal counter -->
<div id="ae-loader" style="position:fixed;inset:0;background:#F7F7F5;z-index:99999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:transform 0.8s cubic-bezier(0.65, 0, 0.35, 1);">
  <div id="ae-loader-num" style="font-family:'Space Grotesk',monospace;font-size:3rem;font-weight:600;color:#121212;">0%</div>
  <div style="font-family:'Space Grotesk',monospace;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.18em;color:#999994;margin-top:8px;">AE Software Solutions</div>
</div>

<header class="site-header">
  <div class="site-header__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
      AE Software
      <span class="sub">Pennsylvania [US]</span>
    </a>

    <nav class="site-header__nav">
      <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link">Services<sup>04</sup></a>
      <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="site-header__nav-link">Work<sup>03</sup></a>
      <a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" class="site-header__nav-link">Websites</a>
      <a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" class="site-header__nav-link">Pricing</a>
      <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="site-header__nav-link">About</a>
    </nav>

    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__cta btn">Get in touch</a>

    <!-- Hamburger mobile only -->
    <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false" style="display:none;background:none;border:none;cursor:pointer;">
      <span style="display:block;width:24px;height:2px;background:#121212;margin:5px 0;"></span>
      <span style="display:block;width:24px;height:2px;background:#121212;margin:5px 0;"></span>
    </button>
  </div>
</header>

<main class="site-main">
