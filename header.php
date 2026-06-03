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

<!-- Page loader — black screen that fades out after load (Opal-style) -->
<div id="ae-loader" aria-hidden="true"></div>

<header class="site-header">
	<div class="site-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>

		<nav class="site-header__nav">
			<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link">Services</a>
			<a href="<?php echo esc_url( home_url( '/software/' ) ); ?>" class="site-header__nav-link">Software</a>
			<a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>" class="site-header__nav-link">Industries</a>
			<a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="site-header__nav-link">Case Studies</a>
			<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="site-header__nav-link">About</a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="site-header__nav-link">Contact</a>
		</nav>

		<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="site-header__cta btn btn--primary">Start Assessment</a>

		<!-- Hamburger — mobile only -->
		<button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
		</button>
	</div>
</header>

<!-- Mobile nav overlay -->
<nav class="mobile-nav-overlay" id="mobile-nav" aria-label="Mobile navigation">
	<ul class="mobile-nav-overlay__links">
		<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="mobile-nav-overlay__link">Services</a></li>
		<li><a href="<?php echo esc_url( home_url( '/software/' ) ); ?>" class="mobile-nav-overlay__link">Software</a></li>
		<li><a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>" class="mobile-nav-overlay__link">Industries</a></li>
		<li><a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" class="mobile-nav-overlay__link">Case Studies</a></li>
		<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="mobile-nav-overlay__link">About</a></li>
		<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="mobile-nav-overlay__link">Contact</a></li>
	</ul>
	<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn--primary mobile-nav-overlay__cta">Start Assessment</a>
	<p class="mobile-nav-overlay__footer">AE Software Solutions</p>
</nav>

<?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) : ?>
<div class="admin-bar-chip admin-bar-chip--in">
	<a href="<?php echo esc_url( home_url( '/console/' ) ); ?>" class="admin-bar-chip__link" title="Admin Console">
		<span class="admin-bar-chip__avatar">
			<?php echo get_avatar( get_current_user_id(), 28, '', '', [ 'class' => 'admin-bar-chip__img' ] ); ?>
		</span>
		<span class="admin-bar-chip__label">Console</span>
	</a>
	<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="admin-bar-chip__logout" title="Log out">&#x2715;</a>
</div>
<?php else : ?>
<a href="<?php echo esc_url( wp_login_url( home_url( '/console/' ) ) ); ?>" class="admin-bar-chip admin-bar-chip--out" title="Admin login">
	<svg class="admin-bar-chip__icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
		<circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.4"/>
		<path d="M3 17c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
	</svg>
	<span class="admin-bar-chip__label">Admin</span>
</a>
<?php endif; ?>

<main class="site-main">
