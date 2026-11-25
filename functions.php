<?php
/**
 * AE Softwares — functions.php
 * Handles all script/style enqueuing, admin settings and REST endpoints.
 */


// ── Aria REST API endpoints (push/status/assets) ─────────────────────────────
require_once get_template_directory() . '/inc/aria-api.php';

// ── Offer lineup (prices, plans, guarantee) — edit prices in inc/offers.php ──
require_once get_template_directory() . '/inc/checkout-links.php'; // every checkout button URL (Stripe, shop fallback)
require_once get_template_directory() . '/inc/offers.php';
require_once get_template_directory() . '/inc/hero-carousel.php'; // product carousel (Services hero)

// ── Guides page newsletter signup (admin-ajax: ae_guides_signup) ─────────────
require_once get_template_directory() . '/inc/guides-newsletter.php';

// ══════════════════════════════════════════════════════════════════════════════
// 1. ENQUEUE SCRIPTS & STYLES
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_enqueue_scripts() {

    $uri  = get_template_directory_uri();
    $path = get_template_directory();

    // Primary font is now the Apple system stack (--font-main in ae-v5.css), so
    // Apple devices render San Francisco. Plus Jakarta Sans stays in assets/fonts/
    // and ae-fonts.css as an unused fallback (font-display: swap, only fetched if
    // something asks for it by name). The handle stays 'ae-fonts' so style.css
    // still loads after it.
    $fonts_css = $path . '/assets/refresh/ae-fonts.css';
    wp_enqueue_style(
        'ae-fonts',
        $uri . '/assets/refresh/ae-fonts.css',
        [],
        file_exists( $fonts_css ) ? filemtime( $fonts_css ) : '6.0.0'
    );

    // Main stylesheet — filemtime() version for automatic cache busting
    wp_enqueue_style( 'aesoftwares-style', get_stylesheet_uri(), [ 'ae-fonts' ], filemtime( $path . '/style.css' ) );

    // ── Web component JS (all pages) ──────────────────────────────────────
    $js = $path . '/assets/js/';

    // ── Motion (all pages): reveal, header hairline, mobile menu, home topics ─
    wp_enqueue_script(
        'ae-motion',
        $uri . '/assets/js/ae-motion.js',
        [],
        filemtime( $js . 'ae-motion.js' ),
        [ 'in_footer' => false, 'strategy' => 'defer' ]
    );

    // Glass circle cursor: desktop mouse only (the script exits on touch and on
    // reduced motion), so it costs phones nothing beyond the tiny file.
    wp_enqueue_script( 'ae-apple', $uri . '/assets/js/ae-apple.js', [], filemtime( $js . 'ae-apple.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-cursor', $uri . '/assets/js/ae-cursor.js', [], filemtime( $js . 'ae-cursor.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );

    // ae-greetings, ae-products, ae-testimonials and ae-assistant were removed:
    // nothing on the current pages uses them, and ae-testimonials showed
    // invented clients and stock photos. The floating ae-widget chat was
    // removed Nov 17 per Anthony, so it is no longer enqueued.
    // Glass card contact popup: opens from any link or button with data-ae-popup.
    wp_enqueue_script( 'ae-popup',        $uri . '/assets/js/ae-popup.js',        [], filemtime( $js . 'ae-popup.js' ),        [ 'in_footer' => true, 'strategy' => 'defer' ] );
    // Glass detail popup: opens topic rows and FAQ answers in the same glass card.
    wp_enqueue_script( 'ae-detail-popup', $uri . '/assets/js/ae-detail-popup.js', [], filemtime( $js . 'ae-detail-popup.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-cta',          $uri . '/assets/js/ae-cta.js',          [], filemtime( $js . 'ae-cta.js' ),          [ 'in_footer' => true, 'strategy' => 'defer' ] );

    // The hero background canvas (ae-hero-motion.js) was retired in the v6
    // redesign: the home hero is plain text and never waits on a script.

    // ── Free tool JS (one script per tool page) ──────────────────────────
    if ( is_page_template( 'page-rewriter.php' ) ) {
        wp_enqueue_script(
            'ae-rewriter',
            $uri . '/assets/js/ae-rewriter.js',
            [],
            filemtime( $js . 'ae-rewriter.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-rewriter', 'ae_rewriter_settings', [
            'rewrite_url'  => get_rest_url( null, 'aesoftwares/v1/rewrite' ),
            'validate_url' => get_rest_url( null, 'aesoftwares/v1/validate' ),
        ] );
    }

    // ── Transcription tool (transcriber page only) ────────────────────────
    if ( is_page_template( 'page-transcriber.php' ) ) {
        wp_enqueue_script(
            'ae-transcriber',
            $uri . '/assets/js/ae-transcriber.js',
            [],
            filemtime( $js . 'ae-transcriber.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-transcriber', 'ae_transcriber_settings', [
            'transcribe_url' => get_rest_url( null, 'aesoftwares/v1/transcribe' ),
            'tts_url'        => get_rest_url( null, 'aesoftwares/v1/tts' ),
        ] );
    }

    if ( is_page_template( 'page-email-drafter.php' ) ) {
        wp_enqueue_script(
            'ae-email-drafter',
            $uri . '/assets/js/ae-email-drafter.js',
            [],
            filemtime( $js . 'ae-email-drafter.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-email-drafter', 'ae_email_settings', [
            'email_url' => get_rest_url( null, 'aesoftwares/v1/email' ),
        ] );
    }

    if ( is_page_template( 'page-summarizer.php' ) ) {
        wp_enqueue_script(
            'ae-summarizer',
            $uri . '/assets/js/ae-summarizer.js',
            [],
            filemtime( $js . 'ae-summarizer.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-summarizer', 'ae_summarizer_settings', [
            'summarize_url' => get_rest_url( null, 'aesoftwares/v1/summarize' ),
        ] );
    }

    // ── Marketplace scraper (private admin tool) ──────────────────────────
    if ( is_page_template( 'page-marketplace.php' ) ) {
        wp_enqueue_script(
            'ae-marketplace',
            $uri . '/assets/js/ae-marketplace.js',
            [],
            filemtime( $js . 'ae-marketplace.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-marketplace', 'ae_marketplace_settings', [
            'marketplace_url' => get_rest_url( null, 'aesoftwares/v1/marketplace' ),
        ] );
    }

    // ── Assessment discovery agent (assessment page only) ─────────────────
    if ( is_page_template( 'page-online-presence.php' ) ) {
        wp_enqueue_script( 'ae-presence', $uri . '/assets/js/ae-presence.js', [], filemtime( $js . 'ae-presence.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    }

    if ( is_page_template( 'page-assessment.php' ) ) {
        wp_enqueue_script(
            'ae-assessment',
            $uri . '/assets/js/ae-assessment.js',
            [],
            filemtime( $js . 'ae-assessment.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
    }

    // ── Admin console — Cash Flow Intelligence web component ──────────────
    if ( is_page_template( 'page-console.php' ) ) {
        wp_enqueue_script(
            'ae-cashflow',
            $uri . '/assets/js/ae-cashflow.js',
            [],
            filemtime( $js . 'ae-cashflow.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
    }

    // The GSAP + ScrollTrigger + Lenis "cinematic" stack was removed: the v4
    // front page has none of the elements it animated, so it only added ~150KB
    // of script, console warnings, and scroll-jacking on desktop. Reveals are
    // handled by the IntersectionObserver in footer.php and ae-chrome.css.
}
add_action( 'wp_enqueue_scripts', 'aesoftwares_enqueue_scripts' );

// LiteSpeed "delay JS" holds scripts until the first touch or key press, which
// leaves the nav pill and cursor dead on first hover. Keep the two small
// interaction scripts out of it. The detail popup joins them: the topic rows are
// above the fold on the home page, so the first click has to open the popup.
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if ( in_array( $handle, [ 'ae-motion', 'ae-cursor', 'ae-apple', 'ae-detail-popup' ], true ) && false === strpos( $tag, 'data-no-optimize' ) ) {
        $tag = str_replace( '<script ', '<script data-no-optimize="1" data-no-defer="1" data-cfasync="false" ', $tag );
    }
    return $tag;
}, 10, 2 );


// ── Retired Aria pages → homepage ──────────────────────────────────────────
// The Aria console, store and marketplace pages were removed. Their old URLs
// 301 to the homepage. Priority 1 runs before WordPress's canonical redirect.
add_action( 'template_redirect', function () {
    $path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
    if ( in_array( $path, [ 'aria-console', 'aria-store', 'aria-marketplace' ], true ) ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
}, 1 );

// ── Replaced pages → their replacements ───────────────────────────────────
// Old duplicate pages that a newer page replaced. Each old URL 301s to its
// replacement, whether or not the old WordPress page still exists.
add_action( 'template_redirect', function () {
    $moved = [
		'shop' => '/marketplace/',
		'checkout' => '/get-started/',
		'cart' => '/get-started/',
        'business-starter' => '/business-starter-package/',
    ];
    $path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
    if ( isset( $moved[ $path ] ) ) {
        wp_safe_redirect( home_url( $moved[ $path ] ), 301 );
        exit;
    }
}, 1 );


// ── Strip WordPress bloat from the front page ─────────────────────────────
function aesoftwares_clean_front_page() {
    if ( ! is_front_page() ) return;
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'aesoftwares_clean_front_page', 100 );

// ── Icons: the theme prints one set of Æ icons (header.php). WordPress's own
// Site Icon tags printed a second, older set first, so they are turned off.
// Requests for /favicon.ico that reach WordPress get the theme's Æ icon.
add_action( 'init', function () {
    remove_action( 'wp_head', 'wp_site_icon', 99 );
} );
add_action( 'do_faviconico', function () {
    $ico = get_template_directory() . '/assets/img/brand/favicon.ico';
    if ( file_exists( $ico ) ) {
        header( 'Content-Type: image/x-icon' );
        header( 'Cache-Control: public, max-age=604800' );
        readfile( $ico );
        exit;
    }
} );

add_action( 'init', function () {
    remove_action( 'wp_head',         'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head',         'wp_generator' );
} );


// Fonts are self hosted now (see ae-fonts.css), so the old preconnect hints to
// Google Fonts were removed. header.php preloads the 400 and 700 files.


// ── Purge the page cache after a theme deploy ───────────────────────────────
// hPanel Git updates the theme files but LiteSpeed Cache kept serving pages
// rendered by the old theme for more than a day. When any key theme file
// changes, purge LiteSpeed once (no-op if the plugin isn't active).
add_action( 'init', function () {
    $dir   = get_template_directory();
    $files = [ '/functions.php', '/header.php', '/footer.php', '/front-page.php', '/assets/refresh/ae-v5.css', '/assets/js/ae-motion.js', '/assets/js/ae-cursor.js', '/assets/js/ae-apple.js', '/assets/js/ae-cta.js', '/assets/js/ae-popup.js', '/assets/js/ae-detail-popup.js', '/popup-contact.php', '/popup-detail.php', '/page-pricing.php', '/assets/refresh/ae-fonts.css', '/page-front-desk-intake.php', '/inc/front-desk-intake.php', '/page-front-desk-terms.php' ];
    $sig   = '';
    foreach ( $files as $f ) {
        $sig .= file_exists( $dir . $f ) ? filemtime( $dir . $f ) : '0';
    }
    $sig = md5( $sig );
    if ( get_option( 'ae_theme_build_sig2' ) !== $sig ) {
        update_option( 'ae_theme_build_sig2', $sig, false );
        do_action( 'litespeed_purge_all' );          // LiteSpeed Cache plugin, if active
        if ( ! headers_sent() ) {
            header( 'X-LiteSpeed-Purge: *' );        // LiteSpeed server cache, plugin or not
        }
    }
}, 99 );


// ── No Google ads on our own site ───────────────────────────────────────────
// Site Kit's AdSense module was injecting auto ads on every page. These Site
// Kit filters stop the AdSense tag (normal and AMP) while leaving Analytics
// and Search Console untouched.
add_filter( 'googlesitekit_adsense_tag_blocked', '__return_true' );
add_filter( 'googlesitekit_adsense_tag_amp_blocked', '__return_true' );


// ── Theme supports ────────────────────────────────────────────────────────
function aesoftwares_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
}
add_action( 'after_setup_theme', 'aesoftwares_setup' );

// ── Per-IP rate limit for the public AI tool endpoints ───────────────────────
// These call the Anthropic API on every request and had no limit at all, so one
// script could run up API costs or tie up the VPS. 30 calls per 10 minutes per
// tool is far above what a person typing into the free tools will hit.
function aesoftwares_public_rate_limit( $bucket, $limit = 30, $window = 600 ) {
    return function () use ( $bucket, $limit, $window ) {
        $key   = 'ae_rl_' . $bucket . '_' . md5( aesoftwares_client_ip() );
        $count = (int) get_transient( $key );
        if ( $count >= $limit ) {
            return new WP_Error( 'ae_rate_limited', 'Too many requests. Please wait a few minutes and try again.', [ 'status' => 429 ] );
        }
        set_transient( $key, $count + 1, $window );
        return true;
    };
}


// ══════════════════════════════════════════════════════════════════════════════
// 2. ADMIN SETTINGS PAGE
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_add_admin_menu() {
    add_menu_page(
        'AE Settings', 'AE Settings', 'manage_options',
        'ae-softwares-settings', 'aesoftwares_settings_page_html',
        'dashicons-admin-generic', 20
    );
}
add_action( 'admin_menu', 'aesoftwares_add_admin_menu' );

function aesoftwares_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'aesoftwares_settings' );
            do_settings_sections( 'aesoftwares_settings' );
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

function aesoftwares_settings_init() {
    register_setting( 'aesoftwares_settings', 'aesoftwares_n8n_webhook_url' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_anthropic_api_key' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_speaches_url' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_speaches_token' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_marketplace_url' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_marketplace_token' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_fb_email' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_fb_password' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_crm_url' );
    register_setting( 'aesoftwares_settings', 'ae_aria_push_token' );
    add_option( 'ae_aria_push_token', 'aria-push-ea5c53c9a54e46ec6dfe5924b6567cab', '', 'no' );

    add_settings_section( 'aesoftwares_n8n_section', 'n8n Webhook Settings', null, 'aesoftwares_settings' );
    add_settings_field( 'aesoftwares_n8n_webhook_url_field', 'n8n Webhook URL', 'aesoftwares_n8n_webhook_url_field_html', 'aesoftwares_settings', 'aesoftwares_n8n_section' );

    add_settings_section(
        'aesoftwares_ai_section',
        'AI Backend Settings',
        function() { echo '<p style="color:#666;font-size:13px;">The free tools (rewriter, email drafter, summariser) and the assessment call the Anthropic API with this key.</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'aesoftwares_anthropic_api_key_field','Anthropic API Key','aesoftwares_anthropic_api_key_field_html',   'aesoftwares_settings', 'aesoftwares_ai_section' );

    add_settings_section(
        'aesoftwares_speaches_section',
        'Transcription Settings (Speaches / Whisper)',
        function() { echo '<p style="color:#999;font-size:13px;">Speaches is a self-hosted Whisper transcription server. Run <code>bash scripts/deploy-speaches.sh</code> on the KVM4 VPS to set it up, then paste the URL and token here.</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'aesoftwares_speaches_url_field',   'Speaches URL',   'aesoftwares_speaches_url_field_html',   'aesoftwares_settings', 'aesoftwares_speaches_section' );
    add_settings_field( 'aesoftwares_speaches_token_field', 'Speaches Token', 'aesoftwares_speaches_token_field_html', 'aesoftwares_settings', 'aesoftwares_speaches_section' );

    add_settings_section(
        'aesoftwares_marketplace_section',
        'Marketplace Scraper Settings',
        function() { echo '<p style="color:#999;font-size:13px;">Private Facebook Marketplace scraper. Run <code>bash scripts/deploy-marketplace.sh</code> on the VPS to install, then paste the service URL and token here.</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'aesoftwares_marketplace_url_field',   'Marketplace Service URL',   'aesoftwares_marketplace_url_field_html',   'aesoftwares_settings', 'aesoftwares_marketplace_section' );
    add_settings_field( 'aesoftwares_marketplace_token_field', 'Marketplace Service Token', 'aesoftwares_marketplace_token_field_html', 'aesoftwares_settings', 'aesoftwares_marketplace_section' );
    add_settings_field( 'aesoftwares_fb_email_field',           'Facebook Email',            'aesoftwares_fb_email_field_html',           'aesoftwares_settings', 'aesoftwares_marketplace_section' );
    add_settings_field( 'aesoftwares_fb_password_field',        'Facebook Password',         'aesoftwares_fb_password_field_html',        'aesoftwares_settings', 'aesoftwares_marketplace_section' );

    add_settings_section(
        'aesoftwares_console_section',
        'Admin Console Settings',
        function() { echo '<p style="color:#999;font-size:13px;">Configures the gated <code>/console/</code> page that hosts internal tools (aeCRM + Cash Flow analyzer).</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'aesoftwares_crm_url_field', 'aeCRM URL', 'aesoftwares_crm_url_field_html', 'aesoftwares_settings', 'aesoftwares_console_section' );

    add_settings_section(
        'ae_aria_section',
        'Aria Agent Settings',
        function() { echo '<p style="color:#999;font-size:13px;">Aria pushes her live status to this site via the <code>/wp-json/aesoftwares/v1/aria/push</code> endpoint. Set a secret token here and add the same token to HEARTBEAT.md on the Ubuntu agent so her pushes are authenticated.</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'ae_aria_push_token_field', 'Aria Push Token', 'ae_aria_push_token_field_html', 'aesoftwares_settings', 'ae_aria_section' );
}
add_action( 'admin_init', 'aesoftwares_settings_init' );

// The VPS Ollama/Gemma router was retired (2026-10). Remove its stored URL,
// token and model name from the database the first time an admin page loads.
add_action( 'admin_init', function () {
    foreach ( [ 'aesoftwares_ollama_proxy_url', 'aesoftwares_ollama_token', 'aesoftwares_ollama_model' ] as $opt ) {
        if ( false !== get_option( $opt, false ) ) {
            delete_option( $opt );
        }
    }
} );


function aesoftwares_n8n_webhook_url_field_html() {
    $url = get_option( 'aesoftwares_n8n_webhook_url', '' );
    ?><input type="url" name="aesoftwares_n8n_webhook_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://your-n8n-instance.com/webhook/path"><p class="description">Enter the full URL for your n8n webhook.</p><?php
}

function aesoftwares_anthropic_api_key_field_html() {
    $key = get_option( 'aesoftwares_anthropic_api_key', '' );
    ?><input type="password" name="aesoftwares_anthropic_api_key" value="<?php echo esc_attr( $key ); ?>" class="regular-text" placeholder="sk-ant-…"><p class="description">Used by the free tools and the assessment.</p><?php
}

function aesoftwares_speaches_url_field_html() {
    $url = get_option( 'aesoftwares_speaches_url', '' );
    ?><input type="url" name="aesoftwares_speaches_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="http://127.0.0.1:8000"><p class="description">URL of the Speaches server (no trailing slash).</p><?php
}

function aesoftwares_speaches_token_field_html() {
    $token = get_option( 'aesoftwares_speaches_token', '' );
    ?><input type="password" name="aesoftwares_speaches_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" placeholder="your-speaches-api-token"><p class="description">API token. Sent as <code>Authorization: Bearer</code> header.</p><?php
}

function aesoftwares_marketplace_url_field_html() {
    $url = get_option( 'aesoftwares_marketplace_url', '' );
    ?><input type="url" name="aesoftwares_marketplace_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="http://127.0.0.1:8001"><p class="description">URL of the marketplace FastAPI service (no trailing slash).</p><?php
}

function aesoftwares_marketplace_token_field_html() {
    $token = get_option( 'aesoftwares_marketplace_token', '' );
    ?><input type="password" name="aesoftwares_marketplace_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" placeholder="your-marketplace-token"><p class="description">Token sent as <code>X-Marketplace-Token</code> header.</p><?php
}

function aesoftwares_fb_email_field_html() {
    $email = get_option( 'aesoftwares_fb_email', '' );
    ?><input type="email" name="aesoftwares_fb_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" placeholder="your@email.com"><p class="description">Facebook account email used by the headless browser.</p><?php
}

function aesoftwares_fb_password_field_html() {
    $pw = get_option( 'aesoftwares_fb_password', '' );
    ?><input type="password" name="aesoftwares_fb_password" value="<?php echo esc_attr( $pw ); ?>" class="regular-text" placeholder="••••••••"><p class="description">Facebook account password.</p><?php
}

function aesoftwares_crm_url_field_html() {
    $url = get_option( 'aesoftwares_crm_url', '' );
    ?><input type="url" name="aesoftwares_crm_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="<?php echo esc_attr( home_url( '/crm/' ) ); ?>"><p class="description">URL of the aeCRM app. Embedded as an iframe on the <code>/console/</code> page. Defaults to <code><?php echo esc_html( home_url( '/crm/' ) ); ?></code> if blank. CRM must allow framing from this domain (no <code>X-Frame-Options: DENY</code>).</p><?php
}

function ae_aria_push_token_field_html() {
    $token = get_option( 'ae_aria_push_token', '' );
    ?><input type="password" name="ae_aria_push_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" placeholder="aria_push_ae2026_…"><p class="description">Secret Bearer token Aria uses when pushing heartbeat status to <code>POST /wp-json/aesoftwares/v1/aria/push</code>. Copy this value into HEARTBEAT.md on the Ubuntu agent under <strong>WP_PUSH_TOKEN</strong>. Must match exactly — no spaces.</p><?php
}


// ══════════════════════════════════════════════════════════════════════════════
// 3. REST API PROXY — forwards chatbot messages to n8n webhook
// ══════════════════════════════════════════════════════════════════════════════

// ══════════════════════════════════════════════════════════════════════════════
// 4. REST API — AI Rewriter
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_register_rewrite_route() {
    register_rest_route( 'aesoftwares/v1', '/rewrite', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_rewrite',
        'permission_callback' => aesoftwares_public_rate_limit( 'rewrite' ),
        'args'                => [
            'text' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; },
            ],
            'mode' => [
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function ( $v ) { return in_array( $v, [ 'standard', 'academic', 'casual' ], true ); },
            ],
        ],
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_rewrite_route' );

function aesoftwares_ngram_overlap_ratio( $original, $rewritten, $n = 6 ) {
    $normalize = function ( $text ) {
        $text = mb_strtolower( $text );
        $text = preg_replace( '/[^\p{L}\p{N}\s]/u', ' ', $text );
        return array_values( array_filter( preg_split( '/\s+/', trim( $text ) ) ) );
    };

    $orig_words = $normalize( $original );
    $new_words  = $normalize( $rewritten );
    $total      = count( $new_words ) - $n + 1;

    if ( $total <= 0 ) return 0.0;

    $orig_ngrams = [];
    for ( $i = 0; $i <= count( $orig_words ) - $n; $i++ ) {
        $orig_ngrams[ implode( ' ', array_slice( $orig_words, $i, $n ) ) ] = true;
    }

    $matches = 0;
    for ( $i = 0; $i < $total; $i++ ) {
        if ( isset( $orig_ngrams[ implode( ' ', array_slice( $new_words, $i, $n ) ) ] ) ) {
            $matches++;
        }
    }

    return (float) $matches / $total;
}

function aesoftwares_chunk_text( $text, $max_words = 500 ) {
    $paragraphs = preg_split( '/\n\n+/', trim( $text ) );
    $chunks     = [];
    $current    = [];
    $count      = 0;

    foreach ( $paragraphs as $para ) {
        $para = trim( $para );
        if ( $para === '' ) continue;
        $wc = str_word_count( $para );
        if ( $count + $wc > $max_words && ! empty( $current ) ) {
            $chunks[] = implode( "\n\n", $current );
            $current  = [];
            $count    = 0;
        }
        $current[] = $para;
        $count    += $wc;
    }

    if ( ! empty( $current ) ) {
        $chunks[] = implode( "\n\n", $current );
    }

    return $chunks;
}

// ── Anthropic call shared by the free tools and the assessment ──────────────
// Returns the reply text, or a WP_Error. (The VPS Ollama router that used to
// sit in front of this was retired in 2026-10.)
function aesoftwares_anthropic_chat( $system_prompt, $messages, $max_tokens = 1024, $timeout = 60 ) {
    $api_key = get_option( 'aesoftwares_anthropic_api_key' );
    if ( empty( $api_key ) ) {
        return new WP_Error( 'no_backend', 'The AI service is not configured. Add the Anthropic API key in AE Settings.', [ 'status' => 500 ] );
    }

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'headers'     => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
        ],
        'body'        => wp_json_encode( [
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => $max_tokens,
            'system'     => $system_prompt,
            'messages'   => $messages,
        ] ),
        'data_format' => 'body',
        'timeout'     => $timeout,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', 'Failed to reach the AI service.', [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( 200 !== $code ) {
        $msg = $body['error']['message'] ?? 'AI service error.';
        return new WP_Error( 'anthropic_error', $msg, [ 'status' => 502 ] );
    }

    return trim( $body['content'][0]['text'] ?? '' );
}

function aesoftwares_call_ai_backend( $system_prompt, $user_message ) {
    return aesoftwares_anthropic_chat( $system_prompt, [ [ 'role' => 'user', 'content' => $user_message ] ], 4096, 120 );
}

function aesoftwares_call_ai_backend_short( $system_prompt, $user_message ) {
    $text = aesoftwares_anthropic_chat( $system_prompt, [ [ 'role' => 'user', 'content' => $user_message ] ], 1024, 45 );
    return is_wp_error( $text ) ? null : $text;
}

function aesoftwares_validate_content( $text ) {
    $system_prompt = 'You are an AI content detection analyst. Evaluate the text provided against seven signals and return a single JSON object — no other text, no markdown, no explanation.

Score each signal from 0 to 10 where 0 = strongly AI-like and 10 = strongly human-like.

Signals:
1. perplexity_uniformity — Are word choices predictable and safe throughout, or does the writer occasionally reach for an unexpected or unconventional word?
2. burstiness — Does sentence length and complexity vary significantly, or is rhythm uniform?
3. structural_patterns — Do paragraphs all follow the same formula?
4. hedging_and_nuance — Does the writer acknowledge uncertainty or qualify claims where a human would?
5. vocabulary_naturalness — Does word choice feel naturally human or optimised for clarity?
6. grammatical_perfection — Is every sentence flawlessly correct in a way that feels unnatural?
7. semantic_coherence — Does the text flow "too perfectly"?

overall_human_score: integer 0–100, calculated as round(sum_of_all_signal_scores / 7 * 10)
verdict: "LIKELY_HUMAN" if overall >= 85, "BORDERLINE" if 65–84, "LIKELY_AI" if below 65
flags: array of brief descriptions for any signal that scored below 6
pass: true if overall_human_score >= 75, else false

Return exactly this JSON structure and nothing else:
{"overall_human_score":<int>,"verdict":"<string>","flags":[<strings>],"signal_scores":{"perplexity_uniformity":<int>,"burstiness":<int>,"structural_patterns":<int>,"hedging_and_nuance":<int>,"vocabulary_naturalness":<int>,"grammatical_perfection":<int>,"semantic_coherence":<int>},"pass":<bool>}';

    $raw = aesoftwares_call_ai_backend_short( $system_prompt, "Evaluate this text:\n\n" . $text );
    if ( ! $raw || is_wp_error( $raw ) ) return null;

    preg_match( '/\{[\s\S]*\}/s', $raw, $m );
    if ( empty( $m ) ) return null;

    $data = json_decode( $m[0], true );
    return is_array( $data ) ? $data : null;
}

function aesoftwares_handle_rewrite( WP_REST_Request $request ) {
    set_time_limit( 600 );

    $text = $request->get_param( 'text' );
    $mode = $request->get_param( 'mode' );

    $word_count = str_word_count( $text );
    if ( $word_count > 2000 ) {
        return new WP_Error( 'too_long', 'Text exceeds the 2,000 word limit.', [ 'status' => 422 ] );
    }

    $mode_instructions = [
        'standard' => 'Rewrite in a grounded, thoughtful voice — serious but not stiff. Contractions throughout. Vary sentence length erratically: some very short (3–5 words), some long and winding. At least two paragraphs must be a single sentence. Do not summarise at the end of each paragraph. No two paragraphs may open with the same grammatical construction. Embed citations mid-clause.',
        'academic' => 'Rewrite in a scholarly voice that still sounds like a person. Use hedging ("suggests", "appears to", "the data point toward") but place hedges in different positions. Some paragraphs open with evidence; some end on a question or unresolved tension. At least one paragraph should be a single sentence.',
        'casual'   => 'Rewrite as a smart person talking directly to a reader — blog-post energy, not formal writing. Fragments are fine. Contractions everywhere. Some paragraphs one sentence. Start with the point, not a wind-up.',
    ];

    $system_prompt = 'You are a human writer rewriting text so it cannot be detected as AI-generated by tools like CopyLeaks, GPTZero, or Originality.ai. These tools detect AI by measuring how predictable each word choice is (perplexity) and how uniformly predictable the whole document is (burstiness). Your job is to be genuinely unpredictable.

Preserve all original meaning, facts, citations, and argument structure exactly.

SENTENCE-LEVEL TECHNIQUES — apply all of these:
- Choose the less obvious synonym sometimes: not "important" → try "consequential", "telling", or "worth pausing on".
- Start some sentences with conjunctions: And. But. Or. So.
- Use deliberate fragments. Short ones. They work.
- Use em-dashes to interrupt or redirect mid-sentence — like a mind catching itself.
- Insert a parenthetical aside at least once per section.
- Occasionally invert normal word order for emphasis.
- Let one sentence per section feel like an afterthought: "That said, ...", "Then again, ...".

PARAGRAPH-LEVEL TECHNIQUES — apply all of these:
- Make at least two paragraphs exactly one sentence long.
- Vary paragraph length unpredictably: 1 sentence, then 4, then 2, then 5.
- Do NOT end paragraphs with a sentence that restates or summarises.
- Some paragraphs should start with a concrete detail or example, not a topic claim.
- No two consecutive paragraphs may open with the same word or construction.

FORBIDDEN phrases:
- "This shows that", "This means that", "This demonstrates", "This highlights"
- "Furthermore", "Moreover", "In addition", "In conclusion", "It is worth noting"
- "It is important to remember", "At first glance", "When we consider"
- Any 5+ consecutive words carried over unchanged from the source text

STYLE EXAMPLES — study the contrast:

FLAGGED AS AI (avoid this style):
"A further reason why zoos are good is because it has research. Many people overlook this part. Research is very important in zoos. This shows that zoos help animals. Furthermore, zoos are beneficial to society."

PASSED AS HUMAN (write like this):
"I did not think I would choose it when I first started looking into zoos as a topic. One of those subjects on which people have already made up their minds — usually before they have spent much time thinking about it. But the research side surprised me. Not the headline conservation numbers, which get trotted out whenever someone defends a zoo, but the quieter institutional work: the studbooks, the reintroduction logistics, the veterinary data shared across facilities. It is not glamorous. And it probably does not make up for everything. Still."

Output ONLY the rewritten text. No preamble, no labels, no markdown.';

    $chunks = $word_count > 800 ? aesoftwares_chunk_text( $text, 800 ) : [ $text ];
    $total  = count( $chunks );
    $parts  = [];

    foreach ( $chunks as $i => $chunk ) {
        $chunk_note   = $total > 1
            ? 'This is section ' . ( $i + 1 ) . " of {$total} from a longer piece. Match the same voice and register throughout.\n\n"
            : '';
        $user_message = $mode_instructions[ $mode ] . "\n\n" . $chunk_note . "Text to rewrite:\n\n" . $chunk;

        $result = aesoftwares_call_ai_backend( $system_prompt, $user_message );
        if ( is_wp_error( $result ) ) return $result;

        $parts[] = $result;
    }

    $rewritten = implode( "\n\n", $parts );

    return new WP_REST_Response( [ 'rewritten' => $rewritten, 'validation' => null ], 200 );
}


// ══════════════════════════════════════════════════════════════════════════════
// 5. REST API — AI Email Drafter
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_register_email_route() {
    register_rest_route( 'aesoftwares/v1', '/email', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_email',
        'permission_callback' => aesoftwares_public_rate_limit( 'email' ),
        'args'                => [
            'prompt' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; },
            ],
            'tone' => [
                'default'           => 'professional',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function ( $v ) { return in_array( $v, [ 'professional', 'friendly', 'persuasive', 'followup' ], true ); },
            ],
        ],
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_email_route' );

function aesoftwares_handle_email( WP_REST_Request $request ) {
    $prompt = $request->get_param( 'prompt' );
    $tone   = $request->get_param( 'tone' );

    $tone_instructions = [
        'professional' => 'Write a professional, polished email. Include a subject line on the first line prefixed with "Subject:", then a blank line, then the email body.',
        'friendly'     => 'Write a warm, friendly email. Use a conversational tone. Include a subject line on the first line prefixed with "Subject:", then a blank line, then the email body.',
        'persuasive'   => 'Write a persuasive email that motivates action. Lead with value and end with a clear call to action. Include a subject line on the first line prefixed with "Subject:", then a blank line, then the email body.',
        'followup'     => 'Write a polite, non-pushy follow-up email. Include a subject line on the first line prefixed with "Subject:", then a blank line, then the email body.',
    ];

    $system_prompt = 'You are an expert professional email writer. Write complete, ready-to-send emails based on the user\'s brief. Output ONLY the email — subject line first, then the body — with no preamble, explanation, or markdown formatting.';
    $user_message  = $tone_instructions[ $tone ] . "\n\nWrite an email based on this brief:\n\n" . $prompt;

    return aesoftwares_call_vps( $system_prompt, $user_message, 'email' );
}


// ══════════════════════════════════════════════════════════════════════════════
// 6. REST API — AI Summarizer
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_register_summarize_route() {
    register_rest_route( 'aesoftwares/v1', '/summarize', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_summarize',
        'permission_callback' => aesoftwares_public_rate_limit( 'summarize' ),
        'args'                => [
            'text' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; },
            ],
            'length' => [
                'default'           => 'brief',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function ( $v ) { return in_array( $v, [ 'brief', 'standard', 'detailed' ], true ); },
            ],
        ],
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_summarize_route' );

function aesoftwares_handle_summarize( WP_REST_Request $request ) {
    $text   = $request->get_param( 'text' );
    $length = $request->get_param( 'length' );

    $length_instructions = [
        'brief'    => 'Summarise the text in 3–5 concise bullet points. Each bullet should start with "• ". No introduction, no conclusion.',
        'standard' => 'Summarise the text in a single clear paragraph of 4–6 sentences.',
        'detailed' => 'Write a structured summary with: "Overview:" (2–3 sentences), "Key Points:" (4–6 bullets starting with "• "), and "Conclusion:" (1–2 sentences).',
    ];

    $system_prompt = 'You are a precise summarisation assistant. Output ONLY the summary — no preamble, no meta-commentary, no markdown formatting other than bullet characters specified.';
    $user_message  = $length_instructions[ $length ] . "\n\nText to summarise:\n\n" . $text;

    return aesoftwares_call_vps( $system_prompt, $user_message, 'summary' );
}


// ── Shared caller for the email drafter and summariser ──────────────────────
function aesoftwares_call_vps( $system_prompt, $user_message, $response_key ) {
    $text = aesoftwares_anthropic_chat( $system_prompt, [ [ 'role' => 'user', 'content' => $user_message ] ], 4096, 60 );
    if ( is_wp_error( $text ) ) {
        return $text;
    }
    return new WP_REST_Response( [ $response_key => $text ], 200 );
}


// ══════════════════════════════════════════════════════════════════════════════
// 7. REST API — Marketplace Scraper proxy (admin only)
// ══════════════════════════════════════════════════════════════════════════════

add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/marketplace', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_marketplace',
        'permission_callback' => function () { return current_user_can( 'manage_options' ); },
        'args' => [
            'city'      => [ 'required' => true, 'sanitize_callback' => 'sanitize_text_field', 'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; } ],
            'query'     => [ 'required' => true, 'sanitize_callback' => 'sanitize_text_field', 'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; } ],
            'max_price' => [ 'required' => false, 'validate_callback' => function ( $v ) { return is_numeric( $v ) && (int) $v >= 0; } ],
        ],
    ] );
} );

function aesoftwares_handle_marketplace( WP_REST_Request $request ) {
    set_time_limit( 90 );

    $service_url   = get_option( 'aesoftwares_marketplace_url' );
    $service_token = get_option( 'aesoftwares_marketplace_token' );
    $fb_email      = get_option( 'aesoftwares_fb_email' );
    $fb_password   = get_option( 'aesoftwares_fb_password' );

    if ( empty( $service_url ) ) {
        return new WP_Error( 'no_marketplace', 'Marketplace service is not configured.', [ 'status' => 500 ] );
    }

    $city      = $request->get_param( 'city' );
    $query     = $request->get_param( 'query' );
    $max_price = $request->get_param( 'max_price' );

    $params = [ 'city' => $city, 'query' => $query ];
    if ( ! empty( $max_price ) ) $params['max_price'] = (int) $max_price;

    $scraper_endpoint = rtrim( $service_url, '/' ) . '/crawl_facebook_marketplace?' . http_build_query( $params );

    $headers = [
        'Accept'              => 'application/json',
        'X-Marketplace-Token' => $service_token,
    ];
    if ( ! empty( $fb_email ) )    $headers['X-FB-Email']    = $fb_email;
    if ( ! empty( $fb_password ) ) $headers['X-FB-Password'] = $fb_password;

    $response = wp_remote_get( $scraper_endpoint, [ 'headers' => $headers, 'timeout' => 80 ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'scraper_unreachable', 'Could not reach the marketplace scraper: ' . $response->get_error_message(), [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( $code !== 200 ) {
        $msg = is_array( $data ) ? ( $data['detail'] ?? ( 'Scraper returned HTTP ' . $code ) ) : ( 'Scraper returned HTTP ' . $code );
        return new WP_Error( 'scraper_error', $msg, [ 'status' => 502 ] );
    }

    return new WP_REST_Response( [ 'listings' => is_array( $data ) ? $data : [] ], 200 );
}


// ── /chat: n8n proxy ────────────────────────────────────────────────────────
// Previously an open proxy: anyone could POST any JSON, any size, as often as
// they liked, and it was forwarded to n8n untouched. Nothing on the site calls
// it today (the live chat is ae-widget -> CRM), so it now requires a nonce from
// a page load on this site, is rate-limited per IP, and forwards only known
// fields. Front-end code that wants it must send the 'X-WP-Nonce' header.
define( 'AE_CHAT_MAX_MESSAGE_LENGTH', 1000 ); // characters per message
define( 'AE_CHAT_RATE_LIMIT', 20 );           // requests allowed ...
define( 'AE_CHAT_RATE_WINDOW', 600 );         // ... per IP per 10 minutes
define( 'AE_CHAT_MAX_HISTORY_TURNS', 10 );    // conversation turns forwarded

// Only trust Cloudflare's client-IP header when the site is actually behind
// Cloudflare; otherwise anyone can send a fake one to dodge the rate limit.
// Set this to true in wp-config.php if you put Cloudflare in front of the site.
if ( ! defined( 'AE_BEHIND_CLOUDFLARE' ) ) {
    define( 'AE_BEHIND_CLOUDFLARE', false );
}

function aesoftwares_client_ip() {
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
    if ( AE_BEHIND_CLOUDFLARE && ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    $ip = filter_var( wp_unslash( $ip ), FILTER_VALIDATE_IP );
    return $ip ? $ip : '0.0.0.0';
}

function aesoftwares_chat_rate_limited() {
    $key   = 'ae_chat_rl_' . md5( aesoftwares_client_ip() );
    $count = (int) get_transient( $key );
    if ( $count >= AE_CHAT_RATE_LIMIT ) {
        return true;
    }
    set_transient( $key, $count + 1, AE_CHAT_RATE_WINDOW );
    return false;
}

function aesoftwares_chat_permission_check( WP_REST_Request $request ) {
    $nonce = $request->get_header( 'X-WP-Nonce' );
    if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        return new WP_Error( 'ae_chat_bad_nonce', 'Session expired. Reload the page and try again.', [ 'status' => 403 ] );
    }
    if ( aesoftwares_chat_rate_limited() ) {
        return new WP_Error( 'ae_chat_rate_limited', 'Too many messages. Try again in a few minutes.', [ 'status' => 429 ] );
    }
    return true;
}

function aesoftwares_register_chat_route() {
    register_rest_route( 'aesoftwares/v1', '/chat', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_chat_message',
        'permission_callback' => 'aesoftwares_chat_permission_check',
        'args'                => [
            'message'    => [
                'required'          => true,
                'type'              => 'string',
                'validate_callback' => function ( $value ) {
                    $len = function_exists( 'mb_strlen' ) ? mb_strlen( (string) $value ) : strlen( (string) $value );
                    return $len > 0 && $len <= AE_CHAT_MAX_MESSAGE_LENGTH;
                },
                'sanitize_callback' => 'sanitize_textarea_field',
            ],
            'session_id' => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ],
            'page_url'   => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
            ],
            'history'    => [
                'required' => false,
                'type'     => 'array',
            ],
        ],
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_chat_route' );

// On-demand validation endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/validate', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_validate',
        'permission_callback' => aesoftwares_public_rate_limit( 'validate', 60 ),
    ] );
} );

function aesoftwares_handle_validate( WP_REST_Request $request ) {
    $text = sanitize_textarea_field( $request->get_param( 'text' ) );
    if ( empty( $text ) ) {
        return new WP_Error( 'missing_text', 'No text provided.', [ 'status' => 422 ] );
    }
    set_time_limit( 120 );
    $validation = aesoftwares_validate_content( $text );
    return new WP_REST_Response( [ 'validation' => $validation ], 200 );
}

// Transcription endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/transcribe', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_transcribe',
        'permission_callback' => aesoftwares_public_rate_limit( 'transcribe' ),
    ] );
} );

function aesoftwares_handle_transcribe( WP_REST_Request $request ) {
    set_time_limit( 120 );

    $speaches_url   = get_option( 'aesoftwares_speaches_url' );
    $speaches_token = get_option( 'aesoftwares_speaches_token' );

    if ( empty( $speaches_url ) ) {
        return new WP_Error( 'no_speaches', 'Transcription service is not configured. Set the Speaches URL in AE Settings.', [ 'status' => 500 ] );
    }

    $files = ! empty( $_FILES ) ? $_FILES : [];
    if ( empty( $files['audio']['tmp_name'] ) || ! is_uploaded_file( $files['audio']['tmp_name'] ) ) {
        return new WP_Error( 'no_audio', 'No audio file received.', [ 'status' => 422 ] );
    }

    $tmp_path   = $files['audio']['tmp_name'];
    $mime       = $files['audio']['type'] ?? 'audio/webm';
    $orig_name  = sanitize_file_name( $files['audio']['name'] ?? 'audio.webm' );
    $model_name = 'Systran/faster-whisper-small'; // was read from the old Ollama model setting by mistake

    $endpoint = rtrim( $speaches_url, '/' ) . '/v1/audio/transcriptions';
    $headers  = [ 'Authorization: Bearer ' . $speaches_token ];

    $post_fields = [
        'file'  => new CURLFile( $tmp_path, $mime, $orig_name ),
        'model' => $model_name,
    ];

    $ch = curl_init( $endpoint );
    curl_setopt_array( $ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post_fields,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 90,
    ] );

    $body      = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    $curl_err  = curl_error( $ch );
    curl_close( $ch );

    if ( $body === false || ! empty( $curl_err ) ) {
        return new WP_Error( 'speaches_unreachable', 'Could not reach the transcription server: ' . $curl_err, [ 'status' => 502 ] );
    }

    $data = json_decode( $body, true );

    if ( $http_code !== 200 ) {
        $msg = $data['error']['message'] ?? ( 'Speaches returned HTTP ' . $http_code );
        return new WP_Error( 'speaches_error', $msg, [ 'status' => 502 ] );
    }

    return new WP_REST_Response( [ 'text' => $data['text'] ?? '' ], 200 );
}

// ══════════════════════════════════════════════════════════════════════════════
// 8. REST API — Business Intelligence Discovery Assessment
// ══════════════════════════════════════════════════════════════════════════════

add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/assessment', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_assessment',
        'permission_callback' => aesoftwares_public_rate_limit( 'assessment', 80 ),
    ] );
} );

function aesoftwares_handle_assessment( WP_REST_Request $request ) {
    set_time_limit( 300 );

    $body    = $request->get_json_params();
    $message = sanitize_textarea_field( $body['message'] ?? '' );
    $history = isset( $body['history'] ) && is_array( $body['history'] ) ? $body['history'] : [];

    if ( empty( $message ) ) {
        return new WP_Error( 'missing_message', 'No message provided.', [ 'status' => 422 ] );
    }

    // Cap history to last 40 turns to avoid context overflow
    $history = array_slice( $history, -40 );

    $safe_history = [];
    foreach ( $history as $entry ) {
        $role    = in_array( $entry['role'] ?? '', [ 'user', 'assistant' ], true ) ? $entry['role'] : 'user';
        $content = sanitize_textarea_field( $entry['content'] ?? '' );
        if ( $content !== '' ) {
            $safe_history[] = [ 'role' => $role, 'content' => $content ];
        }
    }

    $safe_history[] = [ 'role' => 'user', 'content' => $message ];

    $system_prompt = 'You are an Operational Intelligence and Business Process Analysis Consultant for AE Software Solutions.

Your purpose is to help business owners identify operational inefficiencies, automation opportunities, compliance risks, revenue leaks, documentation gaps, and growth opportunities.

You are NOT a chatbot. You are conducting a structured business assessment. Your objective is to build a complete operational profile of the organization.

CRITICAL RULES:
- Ask ONLY ONE question at a time. Never list multiple questions in a single message.
- Ask the single most valuable next question based on what you have already learned.
- Keep your messages concise and conversational. No walls of text.
- After gathering sufficient information across all phases, offer to generate a full Operational Intelligence Report.
- Never mention phase numbers to the user — the conversation must feel natural.
- Speak as AE in the first person plural: say "we" and "us", never "I", "me" or "my" when referring to yourself. Example opening: "We will guide you through a structured business assessment."
- Never use em dashes, en dashes or hyphens as punctuation. Use commas, periods or the word "and" instead. Write compound words without a hyphen.

ASSESSMENT PHASES (work through these in order, following the conversation naturally):

PHASE 1 — BUSINESS PROFILE
Collect: business name, industry, number of employees, years in operation, revenue range, number of locations, primary products/services, geographic service area. Determine business maturity, operational complexity, and regulatory exposure.

PHASE 2 — CUSTOMER JOURNEY
Map lead generation (how customers find them, lead volume, conversion rates, follow-up procedures). Identify sales process (cycle length, manual tasks, approval steps, CRM usage, bottlenecks, abandoned opportunities). Document customer onboarding steps, personnel, systems, and manual work.

PHASE 3 — OPERATIONAL ANALYSIS
Identify what happens daily, weekly, and monthly. Find repetitive tasks, administrative burden, and human dependencies. Build a picture of: Task / Owner / Frequency / Systems Used / Pain Points.

PHASE 4 — TECHNOLOGY AUDIT
Collect existing systems (CRM, accounting, scheduling, inventory, HR, email). For each: purpose, user count, satisfaction level, integration capabilities. Identify data silos, duplicate data entry, and manual transfers between systems.

PHASE 5 — COMPLIANCE ANALYSIS
Determine regulatory requirements specific to their industry (mortgage: RESPA/TILA/HMDA; insurance: state filings/E&O; healthcare: HIPAA/billing; construction: permits/lien waivers). Collect required documentation, audit procedures, record retention policies, and reporting requirements. Identify compliance risks, missing controls, and manual tracking.

PHASE 6 — KNOWLEDGE MANAGEMENT
Assess whether SOPs, training manuals, and employee handbooks exist. Identify tribal knowledge, missing documentation, and training dependencies that create key-person risk.

PHASE 7 — FINANCIAL LEAK ANALYSIS
Identify revenue leakage: missed follow-ups, lost leads, slow invoicing, delayed collections. Identify cost leakage: duplicate software subscriptions, repetitive manual work, excessive labor costs, redundant approval chains.

PHASE 8 — AUTOMATION DISCOVERY
For every major workflow, evaluate automation potential in: email automation, CRM automation, document automation, customer service, reporting, compliance monitoring. Mentally note: Automation Opportunity / Estimated Time Saved / Implementation Complexity / Expected ROI.

PHASE 9 — INTERNAL SCORING (do not reveal scores until report)
Track internal scores 1–10 for: Operations, Automation, Compliance, Documentation, Technology, Scalability.

PHASE 10 — REPORT GENERATION
When sufficient information is gathered across phases 1–8, offer to generate the full Operational Intelligence Report. The report should include: Executive Summary, Operational Risks, Compliance Risks, Automation Opportunities (with estimated savings), Technology Recommendations, Operational Intelligence Scores, Implementation Roadmap (30/90/365 days), and Next Actions.

Begin by warmly introducing yourself as an Operational Intelligence Consultant with AE Software Solutions and asking for their business name.';

    $reply = aesoftwares_anthropic_chat(
        $system_prompt,
        $safe_history ?: [ [ 'role' => 'user', 'content' => 'Begin the assessment.' ] ],
        1024,
        120
    );
    if ( is_wp_error( $reply ) ) {
        return $reply;
    }
    return new WP_REST_Response( [ 'reply' => $reply ], 200 );
}

// ══════════════════════════════════════════════════════════════════════════════
// 9. REST API — Text-to-Speech (Speaches / Kokoro)
// ══════════════════════════════════════════════════════════════════════════════

// TTS endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/tts', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_tts',
        'permission_callback' => aesoftwares_public_rate_limit( 'tts' ),
        'args'                => [
            'text'  => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => function ( $v ) { return is_string( $v ) && strlen( trim( $v ) ) > 0; },
            ],
            'voice' => [
                'default'           => 'af_bella',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ] );
} );

function aesoftwares_handle_tts( WP_REST_Request $request ) {
    set_time_limit( 120 );

    $speaches_url   = get_option( 'aesoftwares_speaches_url' );
    $speaches_token = get_option( 'aesoftwares_speaches_token' );

    if ( empty( $speaches_url ) ) {
        return new WP_Error( 'no_speaches', 'TTS service is not configured. Set the Speaches URL in AE Settings.', [ 'status' => 500 ] );
    }

    $text  = $request->get_param( 'text' );
    $voice = $request->get_param( 'voice' );

    $allowed_voices = [ 'af_bella', 'af_sarah', 'af_sky', 'am_adam', 'am_michael', 'bf_emma', 'bm_george' ];
    if ( ! in_array( $voice, $allowed_voices, true ) ) {
        $voice = 'af_bella';
    }

    $payload = json_encode( [
        'model'           => 'hexgrad/Kokoro-82M',
        'input'           => $text,
        'voice'           => $voice,
        'response_format' => 'mp3',
    ] );

    $ch = curl_init( rtrim( $speaches_url, '/' ) . '/v1/audio/speech' );
    curl_setopt_array( $ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $speaches_token,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 120,
    ] );

    $body      = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    $curl_err  = curl_error( $ch );
    curl_close( $ch );

    if ( $body === false || ! empty( $curl_err ) ) {
        return new WP_Error( 'tts_unreachable', 'Could not reach the TTS server: ' . $curl_err, [ 'status' => 502 ] );
    }

    if ( $http_code !== 200 ) {
        $data = json_decode( $body, true );
        $msg  = $data['detail'] ?? $data['error']['message'] ?? ( 'TTS service returned HTTP ' . $http_code );
        return new WP_Error( 'tts_error', $msg, [ 'status' => 502 ] );
    }

    return new WP_REST_Response( [
        'audio'  => base64_encode( $body ),
        'format' => 'mp3',
    ], 200 );
}

function aesoftwares_sanitize_chat_history( $raw ) {
    if ( ! is_array( $raw ) ) {
        return [];
    }
    $clean = [];
    foreach ( array_slice( $raw, -AE_CHAT_MAX_HISTORY_TURNS ) as $turn ) {
        if ( ! is_array( $turn ) || ! isset( $turn['role'], $turn['content'] ) || ! is_string( $turn['content'] ) ) {
            continue;
        }
        if ( ! in_array( $turn['role'], [ 'user', 'assistant' ], true ) ) {
            continue;
        }
        $content = sanitize_textarea_field( $turn['content'] );
        $content = function_exists( 'mb_substr' ) ? mb_substr( $content, 0, AE_CHAT_MAX_MESSAGE_LENGTH ) : substr( $content, 0, AE_CHAT_MAX_MESSAGE_LENGTH );
        if ( '' !== trim( $content ) ) {
            $clean[] = [ 'role' => $turn['role'], 'content' => $content ];
        }
    }
    return $clean;
}

function aesoftwares_handle_chat_message( WP_REST_Request $request ) {
    $n8n_url = get_option( 'aesoftwares_n8n_webhook_url' );

    if ( empty( $n8n_url ) || ! wp_http_validate_url( $n8n_url ) ) {
        return new WP_Error( 'no_webhook_url', 'The n8n webhook URL is not configured.', [ 'status' => 500 ] );
    }

    // Allowlist: only these fields reach n8n, whatever else was posted.
    $payload = [
        'message'    => $request->get_param( 'message' ),
        'session_id' => $request->get_param( 'session_id' ),
        'page_url'   => $request->get_param( 'page_url' ),
        'history'    => aesoftwares_sanitize_chat_history( $request->get_param( 'history' ) ),
        'source'     => 'website',
    ];

    $response = wp_remote_post( $n8n_url, [
        'headers'     => [ 'Content-Type' => 'application/json; charset=utf-8' ],
        'body'        => wp_json_encode( $payload ),
        'data_format' => 'body',
        'timeout'     => 20,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'proxy_error', 'Failed to connect to the assistant.', [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    // Never hand the raw upstream body back to the browser: return only the reply.
    $reply = '';
    if ( is_array( $body ) ) {
        foreach ( [ 'reply', 'output', 'text', 'message' ] as $key ) {
            if ( ! empty( $body[ $key ] ) && is_string( $body[ $key ] ) ) {
                $reply = $body[ $key ];
                break;
            }
        }
    }
    if ( '' === $reply ) {
        return new WP_Error( 'proxy_empty', 'The assistant did not return a reply.', [ 'status' => 502 ] );
    }

    $out = new WP_REST_Response( [ 'reply' => $reply ] );
    $out->set_status( $code >= 200 && $code < 300 ? 200 : 502 );
    return $out;
}
require_once get_template_directory() . '/aria-endpoints.php';

// ── SEO: default og:image (Yoast emits none when a page has no featured
// image, so social shares render without a preview card) ─────────────────────
add_action( 'wpseo_add_opengraph_images', function ( $image_container ) {
    if ( method_exists( $image_container, 'has_images' ) && $image_container->has_images() ) {
        return;
    }
    $image_container->add_image_by_url( get_template_directory_uri() . '/assets/img/brand/ae-social-share.png' );
} );

// ══════════════════════════════════════════════════════════════════════════════
// SEO — local business data, page titles/descriptions, Online Presence page
// ══════════════════════════════════════════════════════════════════════════════

// Service area for schema. Nationwide; the business address is kept private.
function aesoftwares_service_area() {
    return [ [ '@type' => 'Country', 'name' => 'United States' ] ];
}

// LocalBusiness on every page (Yoast only emits a generic Organization).
// Services are listed so Google can match "website design" and "online
// presence management" searches near Chambersburg to this business.
add_action( 'wp_head', function () {
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfessionalService',
        '@id'         => home_url( '/#localbusiness' ),
        'name'        => 'AE Software Solutions LLC',
        'url'         => home_url( '/' ),
        'email'       => 'anthony@aesoftwaresolutions.com',
        'image'       => get_template_directory_uri() . '/assets/img/work/monarch-mvc.jpg',
        'description' => 'Website design, online presence management and follow up automation for small businesses across the United States.',
        'areaServed'   => aesoftwares_service_area(),
        'openingHours' => 'Mo-Fr 09:00-22:00',
        'priceRange'   => '$$',
        'hasOfferCatalog' => [
            '@type'           => 'OfferCatalog',
            'name'            => 'Services',
            'itemListElement' => [
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Website design', 'url' => home_url( '/websites/' ) ], 'priceSpecification' => [ '@type' => 'PriceSpecification', 'minPrice' => 495, 'priceCurrency' => 'USD' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Online presence management', 'url' => home_url( '/online-presence/' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 199, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Front Desk AI receptionist', 'url' => home_url( '/pricing/#front-desk' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 99, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Always-On Front Desk', 'url' => home_url( '/pricing/#front-desk-always-on' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 250, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Missed-Lead Audit', 'url' => home_url( '/pricing/#audit' ) ], 'price' => 250, 'priceCurrency' => 'USD' ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Launch Package', 'url' => home_url( '/pricing/#launch' ) ], 'price' => 595, 'priceCurrency' => 'USD' ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Social Front Desk', 'url' => home_url( '/pricing/#social-front-desk' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 199, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Total Presence', 'url' => home_url( '/pricing/#total-presence' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 199, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Social Management', 'url' => home_url( '/social-management/' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 799, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Order Desk', 'url' => home_url( '/services/' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 149, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'AI Setup Guides Subscription', 'url' => home_url( '/get-started/' ) ], 'priceSpecification' => [ '@type' => 'UnitPriceSpecification', 'price' => 19, 'priceCurrency' => 'USD', 'unitText' => 'MONTH' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Branded Document Templates', 'url' => home_url( '/pricing/' ) ], 'price' => 295, 'priceCurrency' => 'USD' ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Free Operational Assessment', 'url' => home_url( '/services/' ) ], 'price' => 0, 'priceCurrency' => 'USD' ],
            ],
        ],
    ];
    if ( is_page( 'websites' ) ) {
        echo '<script type="application/ld+json">' . wp_json_encode( [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => 'Website design',
            'serviceType' => 'Website design and development',
            'description' => 'Custom, mobile first websites for small businesses, from five page sites to lead funnels with booking and CRM built in.',
            'url'         => home_url( '/websites/' ),
            'provider'    => [ '@id' => home_url( '/#localbusiness' ) ],
            'areaServed'  => aesoftwares_service_area(),
            'offers'      => [
                [ '@type' => 'Offer', 'name' => 'Starter Site', 'priceSpecification' => [ '@type' => 'PriceSpecification', 'minPrice' => 495, 'priceCurrency' => 'USD' ] ],
                [ '@type' => 'Offer', 'name' => 'Lead Funnel Site', 'priceSpecification' => [ '@type' => 'PriceSpecification', 'minPrice' => 4500, 'priceCurrency' => 'USD' ] ],
                [ '@type' => 'Offer', 'name' => 'Launch Package', 'price' => 595, 'priceCurrency' => 'USD' ],
            ],
        ], JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
    // Agent visibility (Nov 20): Organization block with contact and social
    // profiles, so agents and search engines get the full business identity.
    $ae_organization = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        '@id'         => home_url( '/#organization' ),
        'name'        => 'AE Software Solutions LLC',
        'url'         => home_url( '/' ),
        'email'       => 'anthony@aesoftwaresolutions.com',
        'telephone'   => '+1-240-317-7677',
        'description' => 'Website design, online presence management and AI follow-up automation for small businesses across the United States.',
        'foundingDate' => '2025-08-12',
        'knowsAbout'  => [
            'Website design',
            'Google Business Profile management',
            'Online presence management',
            'Review management',
            'AI call answering',
            'Missed call follow up automation',
            'Social media management',
            'Order entry automation',
            'Small business automation',
        ],
        'sameAs'      => [
            'https://www.linkedin.com/company/111336788',
            'https://www.youtube.com/@AESoftwareSolutionsLLC',
            'https://www.instagram.com/aesoftwaresolutionsllc',
            'https://x.com/aesoftwarellc',
            'https://www.facebook.com/813603801839060',
        ],
        'contactPoint' => [
            '@type'           => 'ContactPoint',
            'contactType'     => 'sales',
            'email'           => 'anthony@aesoftwaresolutions.com',
            'telephone'       => '+1-240-317-7677',
            'areaServed'      => 'US',
            'availableLanguage' => 'en',
            'hoursAvailable'  => 'Mo-Fr 09:00-22:00',
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $ae_organization, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 20 );

// Titles and descriptions for the key pages, aimed at the searches we want:
// "website design Chambersburg PA", "online presence management", etc.
// These override whatever is typed into Yoast for these pages only.
function aesoftwares_seo_meta() {
    static $map = null;
    if ( null === $map ) {
        $map = [
            '__front' => [
                'Website Design & Online Presence Management | AE Software Solutions',
                'Website design, Google Business Profile and online presence management, and automation for small businesses nationwide. Book a free call.',
            ],
            'websites' => [
                'Website Design for Small Businesses | AE Software Solutions',
                'Custom, mobile first website design for small businesses nationwide. Fixed price builds from $495. Get a quote.',
            ],
            'online-presence' => [
                'Online Presence Management & Google Reviews | AE Software Solutions',
                'Online presence management for small businesses nationwide: Google Business Profile, review replies, consistent listings and website care. Free check.',
            ],
            'social-management' => [
                'Social Media Management | AE Software Solutions',
                'Social media managed for you: content, short form video, posting, replies and a monthly report. Business plans from $199/mo, creator plans from $499/mo.',
            ],
            'get-started' => [
                'Choose a Social Media Plan | AE Software Solutions',
                'Pick a social media management plan and pay securely. Business plans from $199 a month, creator plans from $499 a month. Discount codes accepted at checkout.',
            ],
            'services' => [
                'Services: Website Design, Google Presence & Follow Up | AE Software Solutions',
                'Websites, Google Business Profile and review management, and missed call follow up automation for small service businesses nationwide.',
            ],
            'pricing' => [
                'Pricing: Websites from $495, Online Presence $199/mo | AE Software Solutions',
                'Clear prices for small business websites, Google presence management and follow up automation. Free presence check, $250 audit, 60 day guarantee.',
            ],
            'work' => [
                'Recent Websites for Small Businesses | AE Software Solutions',
                'Websites we have designed and built, from a men’s health platform to an online English school.',
            ],
            'about' => [
                'About AE Software Solutions | Web Design & Online Presence for Small Business',
                'Anthony builds websites, Google presence and follow up automation for small businesses nationwide. Fixed prices, and you own everything.',
            ],
            'contact' => [
                'Contact AE Software Solutions | Free Website & Presence Consultation',
                'Book a free discovery call about a new website, your Google presence or automating your follow ups. We work with small businesses nationwide.',
            ],
            'business-starter' => [
                'Business Starter Plan: CRM, Invoicing & Registration Guides from $50/mo',
                'One $50/month plan for small businesses: aeCRM, invoicing and accounting tools, and business registration and compliance guides for all 50 states.',
            ],
        ];
    }
    if ( is_front_page() ) {
        return $map['__front'];
    }
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        return $map[ $slug ] ?? null;
    }
    return null;
}

add_filter( 'wpseo_title', function ( $title ) {
    $m = aesoftwares_seo_meta();
    return $m ? $m[0] : $title;
}, 20 );
add_filter( 'wpseo_opengraph_title', function ( $title ) {
    $m = aesoftwares_seo_meta();
    return $m ? $m[0] : $title;
}, 20 );
add_filter( 'wpseo_metadesc', function ( $desc ) {
    $m = aesoftwares_seo_meta();
    return $m ? $m[1] : $desc;
}, 20 );
add_filter( 'wpseo_opengraph_desc', function ( $desc ) {
    $m = aesoftwares_seo_meta();
    return $m ? $m[1] : $desc;
}, 20 );
// Without Yoast, still give WordPress a good <title>.
add_filter( 'pre_get_document_title', function ( $title ) {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return $title;
    }
    $m = aesoftwares_seo_meta();
    return $m ? $m[0] : $title;
}, 20 );
add_action( 'wp_head', function () {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }
    $m = aesoftwares_seo_meta();
    if ( $m ) {
        echo '<meta name="description" content="' . esc_attr( $m[1] ) . '">' . "\n";
    }
}, 2 );

// The pricing page (business-starter) was set to noindex in Yoast, so Google
// was told not to list it. Allow indexing for the pages above.
add_filter( 'wpseo_robots', function ( $robots ) {
    if ( is_page( 'rewriter' ) ) {
        return 'noindex, follow'; // parked: off-brand for business buyers
    }
    if ( aesoftwares_seo_meta() ) {
        return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    }
    return $robots;
}, 20 );
// Newer Yoast versions build robots from an array; cover that path too.
add_filter( 'wpseo_robots_array', function ( $robots ) {
    if ( is_page( 'rewriter' ) && is_array( $robots ) ) {
        $robots['index'] = 'noindex';
        return $robots;
    }
    if ( aesoftwares_seo_meta() && is_array( $robots ) ) {
        $robots['index']  = 'index';
        $robots['follow'] = 'follow';
    }
    return $robots;
}, 20 );

// Agent visibility (Nov 20): AI crawlers are welcome. WordPress builds the
// virtual robots.txt through this filter; Yoast hooks into the same filter,
// so this appends after Yoast's output. To block a crawler from training
// use, change its Allow to Disallow for that user-agent only.
add_filter( 'robots_txt', function ( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	$bots = [
		'GPTBot',
		'OAI-SearchBot',
		'ChatGPT-User',
		'ClaudeBot',
		'Claude-Web',
		'anthropic-ai',
		'Google-Extended',
		'CCBot',
		'PerplexityBot',
		'Perplexity-User',
		'Applebot-Extended',
		'Meta-ExternalAgent',
		'Bytespider',
		'cohere-ai',
		'DuckAssistBot',
	];
	$rules = "\n# AI crawlers are welcome. See /llms.txt for a machine-readable index.\n";
	foreach ( $bots as $bot ) {
		$rules .= "User-agent: {$bot}\nAllow: /\n\n";
	}
	return $output . $rules;
}, 20, 2 );

// Agent visibility (Nov 20): serve /llms.txt and /llms-full.txt from the
// theme. The two text files must be committed to the theme directory
// alongside functions.php. After pushing, flush rewrite rules once:
// WP Admin > Settings > Permalinks > Save, or the rules below flush on
// theme activation.
add_action( 'init', function () {
	add_rewrite_rule( '^llms\.txt$', 'index.php?ae_llms=1', 'top' );
	add_rewrite_rule( '^llms-full\.txt$', 'index.php?ae_llms=full', 'top' );
} );
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'ae_llms';
	return $vars;
} );
add_action( 'template_redirect', function () {
	$llms = get_query_var( 'ae_llms' );
	if ( '' === $llms ) {
		return;
	}
	$file = ( 'full' === $llms ) ? 'llms-full.txt' : 'llms.txt';
	$path = get_template_directory() . '/' . $file;
	if ( ! file_exists( $path ) ) {
		status_header( 404 );
		exit;
	}
	header( 'Content-Type: text/plain; charset=utf-8' );
	readfile( $path );
	exit;
} );
add_action( 'after_switch_theme', function () {
	flush_rewrite_rules();
} );

// Create the Online Presence page once, assigned to its template, so it
// exists without anyone having to add it in WP Admin.
add_action( 'init', function () {
    if ( get_option( 'ae_pricing_page_created' ) ) {
        return;
    }
    if ( ! get_page_by_path( 'pricing' ) ) {
        $id = wp_insert_post( [
            'post_type'   => 'page',
            'post_status' => 'publish',
            'post_title'  => 'Pricing',
            'post_name'   => 'pricing',
        ] );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', 'page-pricing.php' );
        }
    }
    update_option( 'ae_pricing_page_created', 1, false );
}, 31 );

add_action( 'init', function () {
    if ( get_option( 'ae_online_presence_page_created' ) ) {
        return;
    }
    $existing = get_page_by_path( 'online-presence' );
    if ( ! $existing ) {
        $id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => 'Online Presence Management',
            'post_name'    => 'online-presence',
            'post_content' => '',
        ] );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', 'page-online-presence.php' );
        }
    }
    update_option( 'ae_online_presence_page_created', 1, false );
}, 30 );

// Create the Social Media Management page once, assigned to its template, so
// it exists without anyone having to add it in WP Admin.
add_action( 'init', function () {
    if ( get_option( 'ae_social_management_page_created' ) ) {
        return;
    }
    $existing = get_page_by_path( 'social-management' );
    if ( ! $existing ) {
        $id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => 'Social Media Management',
            'post_name'    => 'social-management',
            'post_content' => '',
        ] );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', 'page-social-management.php' );
        }
    }
    update_option( 'ae_social_management_page_created', 1, false );
}, 30 );

// Create the plan picker page once, at the get-started slug because WooCommerce
// owns /checkout/. Assigned to its template so it exists without anyone
// having to add it in WP Admin.
add_action( 'init', function () {
    if ( get_option( 'ae_get_started_page_created' ) ) {
        return;
    }
    $existing = get_page_by_path( 'get-started' );
    if ( ! $existing ) {
        $id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => 'Choose a Social Media Plan',
            'post_name'    => 'get-started',
            'post_content' => '',
        ] );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', 'page-checkout.php' );
        }
    }
    update_option( 'ae_get_started_page_created', 1, false );
}, 30 );

// ── Route all WP mail through Zoho SMTP so it passes SPF/DKIM ────────────────
add_action( 'phpmailer_init', function ( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.zoho.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username   = 'admin@aesoftwaresolutions.com';
    $phpmailer->Password   = get_option( 'ae_zoho_smtp_pass', '' );
    $phpmailer->From       = 'admin@aesoftwaresolutions.com';
    $phpmailer->FromName   = 'AE Software Solutions';
} );

// ── Auto-create the Guides sales page (one-time) ─────────────────────────────
add_action( 'init', function () {
    if ( get_option( 'ae_guides_page_created' ) ) {
        return;
    }
    $existing = get_page_by_path( 'guides' );
    if ( ! $existing ) {
        $id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => 'Guides',
            'post_name'    => 'guides',
            'post_content' => '',
        ] );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', 'page-guides.php' );
        }
    }
    update_option( 'ae_guides_page_created', 1, false );
}, 30 );

// ── De-localize Yoast schema ─────────────────────────────────────────────────
// Some pages carry "Chambersburg PA" in Yoast post-meta (not in this repo).
// Rewrite only name/description strings in the JSON-LD graph that mention it.
function aesoftwares_delocalize_text( $text ) {
    $city = 'Chambersburg(?:,?\s+(?:PA|Pennsylvania))?';
    $text = preg_replace( '/\bin\s+' . $city . '\b/i', 'nationwide', $text );
    $text = preg_replace( '/\s*\b' . $city . '\b/i', '', $text );
    return preg_replace( '/\s{2,}/', ' ', $text );
}

function aesoftwares_delocalize_schema( $node ) {
    if ( ! is_array( $node ) ) {
        return $node;
    }
    foreach ( $node as $key => $value ) {
        if ( is_array( $value ) ) {
            $node[ $key ] = aesoftwares_delocalize_schema( $value );
        } elseif ( is_string( $value ) && in_array( $key, [ 'name', 'description' ], true )
            && stripos( $value, 'Chambersburg' ) !== false ) {
            $node[ $key ] = aesoftwares_delocalize_text( $value );
        }
    }
    return $node;
}

add_filter( 'wpseo_schema_graph', function ( $graph ) {
    return aesoftwares_delocalize_schema( $graph );
}, 99 );
