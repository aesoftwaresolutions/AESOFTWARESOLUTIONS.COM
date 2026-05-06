<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
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
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="site-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>

		<nav class="site-header__nav">
			<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link">Services</a>
			<a href="<?php echo esc_url( home_url( '/process/' ) ); ?>" class="site-header__nav-link">Process</a>
			<a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="site-header__nav-link">Work</a>
			<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="site-header__nav-link">About</a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__nav-link">Contact</a>
			<a href="<?php echo esc_url( home_url( '/rewriter/' ) ); ?>" class="site-header__nav-link site-header__nav-link--tool">Rewriter <span class="nav-badge">Free</span></a>
		</nav>

		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__cta btn btn--outline">Get in touch</a>
	</div>
</header>

<main class="site-main">
