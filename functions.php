<?php
/**
 * AE Softwares — functions.php
 * Handles all script/style enqueuing, admin settings, REST proxy, and cinematic stack.
 */


// ══════════════════════════════════════════════════════════════════════════════
// 1. ENQUEUE SCRIPTS & STYLES
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_enqueue_scripts() {

    $uri  = get_template_directory_uri();
    $path = get_template_directory();

    // Main stylesheet (always loaded)
    wp_enqueue_style( 'aesoftwares-style', get_stylesheet_uri() );

    // ── Web component JS (all pages) ──────────────────────────────────────
    // Use filemtime() for automatic cache busting on every file change.
    $js = $path . '/assets/js/';

    wp_enqueue_script( 'ae-greetings',    $uri . '/assets/js/ae-greetings.js',    [], filemtime( $js . 'ae-greetings.js' ),    [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-products',     $uri . '/assets/js/ae-products.js',     [], filemtime( $js . 'ae-products.js' ),     [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-testimonials', $uri . '/assets/js/ae-testimonials.js', [], filemtime( $js . 'ae-testimonials.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-cta',          $uri . '/assets/js/ae-cta.js',          [], filemtime( $js . 'ae-cta.js' ),          [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-assistant',    $uri . '/assets/js/ae-assistant.js',    [], filemtime( $js . 'ae-assistant.js' ),    [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'main',            $uri . '/assets/js/main.js',            [], filemtime( $js . 'main.js' ),            [ 'in_footer' => true, 'strategy' => 'defer' ] );

    // Pass the WordPress REST proxy URL to the assistant component
    wp_localize_script( 'ae-assistant', 'ae_settings', [
        'proxy_url' => get_rest_url( null, 'aesoftwares/v1/chat' ),
    ] );

    // ── Cinematic stack (front page only) ─────────────────────────────────
    if ( is_front_page() ) {

        // GSAP core — hosted locally (no CDN dependency, reliable cache busting)
        // IMPORTANT: vendor/ files must exist. Download from https://gsap.com/docs/v3/Installation/
        $vendor = $path . '/assets/js/vendor/';
        $vendor_uri = $uri . '/assets/js/vendor/';

        if ( file_exists( $vendor . 'gsap.min.js' ) ) {
            wp_enqueue_script( 'gsap',
                $vendor_uri . 'gsap.min.js',
                [],
                filemtime( $vendor . 'gsap.min.js' ),
                [ 'in_footer' => true, 'strategy' => 'defer' ]
            );

            wp_enqueue_script( 'gsap-scrolltrigger',
                $vendor_uri . 'ScrollTrigger.min.js',
                [ 'gsap' ],
                filemtime( $vendor . 'ScrollTrigger.min.js' ),
                [ 'in_footer' => true, 'strategy' => 'defer' ]
            );
        } else {
            // Fallback to CDN if local files aren't placed yet (remove once files are installed)
            wp_enqueue_script( 'gsap',               'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',               [], '3.12.5', [ 'in_footer' => true, 'strategy' => 'defer' ] );
            wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',      [ 'gsap' ], '3.12.5', [ 'in_footer' => true, 'strategy' => 'defer' ] );
        }

        // Lenis smooth scroll
        wp_enqueue_script( 'lenis', 'https://unpkg.com/lenis@1.3.23/dist/lenis.min.js', [], '1.3.23', [ 'in_footer' => true, 'strategy' => 'defer' ] );

        // Our cinematic controller — depends on gsap + scrolltrigger + lenis
        wp_enqueue_script( 'ae-cinematic',
            $uri . '/assets/js/cinematic.js',
            [ 'gsap', 'gsap-scrolltrigger', 'lenis' ],
            filemtime( $js . 'cinematic.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aesoftwares_enqueue_scripts' );


// ── Strip WordPress bloat from the front page ─────────────────────────────
function aesoftwares_clean_front_page() {
    if ( ! is_front_page() ) return;
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'aesoftwares_clean_front_page', 100 );

add_action( 'init', function () {
    remove_action( 'wp_head',         'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head',         'wp_generator' );
} );


// ── Preconnect hints (saves ~150ms on CDN scripts) ────────────────────────
function aesoftwares_preconnect() {
    if ( ! is_front_page() ) return;
    echo '<link rel="preconnect" href="https://unpkg.com">' . "\n";
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net">' . "\n";
}
add_action( 'wp_head', 'aesoftwares_preconnect', 1 );


// ── Theme supports ────────────────────────────────────────────────────────
function aesoftwares_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
}
add_action( 'after_setup_theme', 'aesoftwares_setup' );


// ══════════════════════════════════════════════════════════════════════════════
// 2. ADMIN SETTINGS PAGE (n8n Webhook URL)
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

    add_settings_section(
        'aesoftwares_n8n_section',
        'n8n Webhook Settings',
        null,
        'aesoftwares_settings'
    );

    add_settings_field(
        'aesoftwares_n8n_webhook_url_field',
        'n8n Webhook URL',
        'aesoftwares_n8n_webhook_url_field_html',
        'aesoftwares_settings',
        'aesoftwares_n8n_section'
    );
}
add_action( 'admin_init', 'aesoftwares_settings_init' );

function aesoftwares_n8n_webhook_url_field_html() {
    $url = get_option( 'aesoftwares_n8n_webhook_url', '' );
    ?>
    <input type="url" name="aesoftwares_n8n_webhook_url"
           value="<?php echo esc_attr( $url ); ?>"
           class="regular-text"
           placeholder="https://your-n8n-instance.com/webhook/path">
    <p class="description">Enter the full URL for your n8n webhook.</p>
    <?php
}


// ══════════════════════════════════════════════════════════════════════════════
// 3. REST API PROXY — forwards chatbot messages to n8n webhook
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_register_chat_route() {
    register_rest_route( 'aesoftwares/v1', '/chat', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_chat_message',
        'permission_callback' => '__return_true',
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_chat_route' );

function aesoftwares_handle_chat_message( WP_REST_Request $request ) {
    $n8n_url = get_option( 'aesoftwares_n8n_webhook_url' );

    if ( empty( $n8n_url ) ) {
        return new WP_Error( 'no_webhook_url', 'The n8n webhook URL is not configured.', [ 'status' => 500 ] );
    }

    $body = $request->get_json_params();

    $response = wp_remote_post( $n8n_url, [
        'method'      => 'POST',
        'headers'     => [ 'Content-Type' => 'application/json; charset=utf-8' ],
        'body'        => json_encode( $body ),
        'data_format' => 'body',
        'timeout'     => 15,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'proxy_error', 'Failed to connect to the webhook.', [ 'status' => 502 ] );
    }

    $response_body = wp_remote_retrieve_body( $response );
    $response_code = wp_remote_retrieve_response_code( $response );

    $forward = new WP_REST_Response( json_decode( $response_body ) );
    $forward->set_status( $response_code );
    $forward->header( 'Content-Type', 'application/json' );

    return $forward;
}
