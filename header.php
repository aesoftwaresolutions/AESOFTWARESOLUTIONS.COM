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

// Liquid glass (assets/js/ae-glass.js): refraction in Chromium (desktop and
// Android), the glass rim only on iOS and Firefox phones. Kept out of LiteSpeed
// "delay JS".
wp_enqueue_script( 'ae-glass', $ae_theme_uri . '/assets/js/ae-glass.js', [], $ae_asset_ver( '/assets/js/ae-glass.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
// Liquid glass controls (assets/js/ae-glass-ui.js): input clear buttons, the
// segmented mode switcher on the free tools, range sliders. Progressive and
// cheap, so it is not gated like the refraction above.
wp_enqueue_script( 'ae-glass-ui', $ae_theme_uri . '/assets/js/ae-glass-ui.js', [], $ae_asset_ver( '/assets/js/ae-glass-ui.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( 'ae-glass' === $handle && false === strpos( $tag, 'data-no-optimize' ) ) {
		$tag = str_replace( '<script ', '<script data-no-optimize="1" data-no-defer="1" data-cfasync="false" ', $tag );
	}
	return $tag;
}, 10, 2 );

// Current section, for the active link underline. Work, Online presence and
// Social management sit under Services, so that link stays marked on them.
$ae_home_path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
$ae_req_path  = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
if ( '' !== $ae_home_path && 0 === strpos( $ae_req_path, $ae_home_path ) ) {
	$ae_req_path = trim( substr( $ae_req_path, strlen( $ae_home_path ) ), '/' );
}
$ae_req_seg = '' === $ae_req_path ? '' : explode( '/', $ae_req_path )[0];

// Five links. [ label, path, extra sections that count as "inside" this link ].
$ae_calls = aesoftwares_calls(); // AI call handling: shop links and the demo line (inc/offers.php)
$ae_nav = [
	// AI call handling leads the site: its own nav item, before Services.
	[ 'AI Front Desk', 'front-desk', [ 'always-on-front-desk' ] ],
	[ 'Services', 'services', [ 'work', 'online-presence', 'social-management', 'software', 'industries', 'process',
		// Product pages (inc/products.php) also count as inside Services.
		'social-front-desk', 'order-desk', 'total-presence', 'creator-packages',
		'launch-package', 'business-starter-package', 'branded-templates', 'missed-lead-audit', 'free-assessment',
		'ai-tools-assessment', 'ai-setup-guides', 'contractor-guides' ] ],
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
    // No intro curtain and no arrival wipe on any page: every headline must be
    // visible on first paint, not hidden behind a full-screen panel.
    try {
      if (!html.classList.contains('ae-calm')) {
  
  
        sessionStorage.removeItem('ae_wipe');
        sessionStorage.setItem('ae_seen', '1');
  
  
  
  
  
  
      }
    } catch (e) {}
  })();
</script>
<noscript><style>
  [data-reveal],[data-reveal] *,.js-fade{opacity:1!important;transform:none!important}
</style></noscript>
<?php wp_head(); ?>
<?php /* One set of icons, all the Æ mark. favicon.ico covers browsers and search engines that ask for it by default. */ ?>
<link rel="icon" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/favicon.ico?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/favicon.ico' ) ); ?>" sizes="any">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-icon-192.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-icon-192.png' ) ); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-favicon-32.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-favicon-32.png' ) ); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-favicon-16.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-favicon-16.png' ) ); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-apple-touch-icon.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-apple-touch-icon.png' ) ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $ae_theme_uri ); ?>/assets/refresh/ae-v5.css?v=<?php echo esc_attr( $ae_asset_ver( '/assets/refresh/ae-v5.css' ) ); ?>">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>

<!-- Decoration only (ae-motion.js): intro curtain and page wipe. Hidden unless html has ae-intro-on, ae-arriving or ae-wiping. -->
<div class="ae-intro" id="ae-intro" aria-hidden="true"><span class="ae-intro__bar"></span></div>
<div class="ae-wipe" id="ae-wipe" aria-hidden="true"></div>

<header class="site-header">
  <div class="site-header__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo" rel="home">
      <img src="<?php echo esc_url( $ae_theme_uri ); ?>/assets/img/brand/ae-logo-mark.png?v=<?php echo esc_attr( $ae_asset_ver( '/assets/img/brand/ae-logo-mark.png' ) ); ?>" alt="AE Software Solutions" width="30" height="30" class="site-header__logo-mark">
    </a>

    <nav class="site-header__nav" aria-label="Main">
      <?php foreach ( $ae_nav as $item ) : ?>
        <?php if ( 'services' === $item[1] ) : ?>
          <?php
          // Services opens a panel of every product page on hover or keyboard
          // focus (desktop only; the nav is hidden under 960px, where the mobile
          // sheet links to /services/). The wrapper is not positioned, so the
          // nav pill still measures the Services link against the nav. Panel
          // links use their own class, so the pill never glides onto them.
          require_once get_template_directory() . '/inc/products.php';
          ?>
          <div class="site-header__menu" data-ae-menu>
            <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="site-header__nav-link" aria-controls="site-services-panel" aria-expanded="false"<?php echo $ae_current( $item[1], $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $item[0] ); ?></a>
            <div class="site-header__panel" id="site-services-panel" role="group" aria-label="All services">
              <div class="site-header__panel-grid">
                <?php foreach ( aesoftwares_products() as $group ) : ?>
                  <div class="site-header__panel-group">
                    <p class="site-header__panel-title"><?php echo esc_html( $group['title'] ); ?></p>
                    <ul class="site-header__panel-list">
                      <?php foreach ( $group['items'] as $p ) : ?>
                        <li><a class="site-header__panel-link" href="<?php echo esc_url( home_url( '/' . $p['slug'] . '/' ) ); ?>"><span class="site-header__panel-name"><?php echo esc_html( $p['name'] ); ?></span> <span class="site-header__panel-price"><?php echo esc_html( $p['price'] ); ?></span></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endforeach; ?>
              </div>
              <a class="site-header__panel-all v5-link" href="<?php echo esc_url( home_url( '/services/#all-services' ) ); ?>">Compare every service &rarr;</a>
            </div>
          </div>
        <?php else : ?>
          <a href="<?php echo esc_url( home_url( '/' . $item[1] . '/' ) ); ?>" class="site-header__nav-link"<?php echo $ae_current( $item[1], $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $item[0] ); ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <a href="https://dashboard.aesoftwaresolutions.com/login" class="site-header__signin">Sign in</a>

    <a href="<?php echo esc_url( $ae_calls['front_desk'] ); ?>" class="site-header__cta btn btn--primary">Start Front Desk</a>

    <!-- Hamburger: shown below 960px, wired up in ae-motion.js -->
    <button class="nav-toggle" id="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
      <span class="nav-toggle__bar"></span>
      <span class="nav-toggle__bar"></span>
      <span class="nav-toggle__bar"></span>
    </button>
  </div>
</header>
<script data-no-optimize="1" data-no-defer="1" data-cfasync="false">
/* Services panel: hover and focus open it in CSS. When a mouse slips off, the
   panel stays open for a short grace period (is-lingering) so it can be reached.
   Escape closes it. */
(function () {
  var m = document.querySelector('[data-ae-menu]');
  if (!m) return;
  var link = m.querySelector('.site-header__nav-link');
  var GRACE = 350; // ms the panel stays open after the pointer leaves
  var timer = null;
  function stopLinger() {
    if (timer) { clearTimeout(timer); timer = null; }
    m.classList.remove('is-lingering');
  }
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape' || m.classList.contains('is-dismissed')) return;
    var inside = m.contains(document.activeElement);
    if (!inside && !m.matches(':hover') && !m.classList.contains('is-lingering')) return;
    stopLinger();
    m.classList.add('is-dismissed');
    if (inside && link) link.focus();
  });
  m.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') stopLinger(); });
  m.addEventListener('pointerleave', function (e) {
    var dismissed = m.classList.contains('is-dismissed');
    m.classList.remove('is-dismissed');
    if (e.pointerType !== 'mouse' || dismissed) return;
    stopLinger();
    m.classList.add('is-lingering');
    timer = setTimeout(stopLinger, GRACE);
  });
  m.addEventListener('focusout', function (e) {
    if (!m.contains(e.relatedTarget)) m.classList.remove('is-dismissed');
  });

  /* GlassMenu keyboard (Nov 25). This is site navigation, so it stays a
     disclosure of links (WAI-ARIA disclosure nav), not role="menu", which would
     change how screen readers read and drive it. Down arrow on Services opens
     the panel and moves into it; arrows, Home and End move between its links;
     Up from the first link, or Escape, returns to Services. aria-expanded on
     the Services link tracks whether the panel is showing. */
  var panel = m.querySelector('.site-header__panel');
  if (!link || !panel) return;
  function items() { return Array.prototype.slice.call(panel.querySelectorAll('a[href]')); }
  function expanded() {
    var open = !m.classList.contains('is-dismissed') &&
      (m.matches(':hover') || m.classList.contains('is-lingering') || m.contains(document.activeElement));
    link.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  link.addEventListener('keydown', function (e) {
    if (e.key !== 'ArrowDown') return;
    var list = items();
    if (!list.length) return;
    e.preventDefault();
    m.classList.remove('is-dismissed');
    list[0].focus();
  });
  panel.addEventListener('keydown', function (e) {
    var list = items(), i = list.indexOf(document.activeElement), n = null;
    if (i === -1) return;
    if (e.key === 'ArrowDown' || e.key === 'ArrowRight') n = Math.min(i + 1, list.length - 1);
    else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') n = i - 1;
    else if (e.key === 'Home') n = 0;
    else if (e.key === 'End') n = list.length - 1;
    if (n === null) return;
    e.preventDefault();
    if (n < 0) link.focus(); else list[n].focus();
  });
  ['pointerenter', 'pointerleave', 'focusin', 'focusout', 'keyup'].forEach(function (t) {
    m.addEventListener(t, function () { setTimeout(expanded, 0); });
  });
  m.addEventListener('pointerleave', function () { setTimeout(expanded, GRACE + 20); });
  expanded();
})();
</script>

<!-- Mobile menu: an iOS style bottom sheet (grabber, rounded top corners, slides up). -->
<div class="mobile-nav-overlay" id="mobile-nav">
  <div class="ae-sheet mobile-nav-sheet" data-sheet>
    <span class="ae-sheet__grabber" data-sheet-grab aria-hidden="true"></span>
    <nav aria-label="Mobile">
      <ul class="mobile-nav-overlay__links">
        <?php foreach ( $ae_nav as $item ) : ?>
          <li><a href="<?php echo esc_url( home_url( '/' . $item[1] . '/' ) ); ?>" class="mobile-nav-overlay__link"<?php echo $ae_current( $item[1], $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $item[0] ); ?></a></li>
        <?php endforeach; ?>
        <li><a href="https://dashboard.aesoftwaresolutions.com/login" class="mobile-nav-overlay__link">Sign in</a></li>
      </ul>
      <a href="<?php echo esc_url( $ae_calls['front_desk'] ); ?>" class="btn btn--primary mobile-nav-overlay__cta">Start Front Desk</a>
      <a href="<?php echo esc_url( $ae_calls['demo_tel'] ); ?>" class="v5-link mobile-nav-overlay__demo">Hear it answer: <?php echo esc_html( $ae_calls['demo_label'] ); ?></a>
    </nav>
    <p class="mobile-nav-overlay__footer"><a href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a></p>
  </div>
</div>

<main class="site-main" id="main-content">
