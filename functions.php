<?php
/**
 * AE Softwares — functions.php
 * Handles all script/style enqueuing, admin settings, REST proxy, and cinematic stack.
 */


// ── Aria REST API endpoints (push/status/assets) ─────────────────────────────
require_once get_template_directory() . '/inc/aria-api.php';

// ══════════════════════════════════════════════════════════════════════════════
// 1. ENQUEUE SCRIPTS & STYLES
// ══════════════════════════════════════════════════════════════════════════════

function aesoftwares_enqueue_scripts() {

    $uri  = get_template_directory_uri();
    $path = get_template_directory();

    // Google Fonts: Inter + DM Serif Display
    wp_enqueue_style(
        'ae-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap',
        [],
        null
    );

    // Main stylesheet — filemtime() version for automatic cache busting
    wp_enqueue_style( 'aesoftwares-style', get_stylesheet_uri(), [ 'ae-fonts' ], filemtime( $path . '/style.css' ) );

    // ── Web component JS (all pages) ──────────────────────────────────────
    $js = $path . '/assets/js/';

    // ── Scroll & load animations (all pages, loads early) ─────────────────
    wp_enqueue_script(
        'ae-animations',
        $uri . '/assets/js/ae-animations.js',
        [],
        filemtime( $js . 'ae-animations.js' ),
        [ 'in_footer' => false, 'strategy' => 'defer' ]
    );

    wp_enqueue_script( 'ae-greetings',    $uri . '/assets/js/ae-greetings.js',    [], filemtime( $js . 'ae-greetings.js' ),    [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-products',     $uri . '/assets/js/ae-products.js',     [], filemtime( $js . 'ae-products.js' ),     [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-testimonials', $uri . '/assets/js/ae-testimonials.js', [], filemtime( $js . 'ae-testimonials.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-cta',          $uri . '/assets/js/ae-cta.js',          [], filemtime( $js . 'ae-cta.js' ),          [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-assistant',    $uri . '/assets/js/ae-assistant.js',    [], filemtime( $js . 'ae-assistant.js' ),    [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'main',            $uri . '/assets/js/main.js',            [], filemtime( $js . 'main.js' ),            [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_enqueue_script( 'ae-widget',       $uri . '/assets/js/ae-widget.js',       [], filemtime( $js . 'ae-widget.js' ),       [ 'in_footer' => true, 'strategy' => 'defer' ] );
    wp_add_inline_script( 'ae-widget', 'window.AE_WIDGET_URL = "https://admin.aesoftwaresolutions.com";', 'before' );

    wp_localize_script( 'ae-assistant', 'ae_settings', [
        'proxy_url' => get_rest_url( null, 'aesoftwares/v1/chat' ),
    ] );

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

    // ── Aria dashboard (console page only) ───────────────────────────────
    if ( is_page_template( 'page-console.php' ) ) {
        wp_enqueue_script(
            'ae-aria',
            $uri . '/assets/js/ae-aria.js',
            [],
            filemtime( $js . 'ae-aria.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-aria', 'ae_aria_settings', [
            'status_url' => get_rest_url( null, 'aesoftwares/v1/aria/status' ),
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

    // ── Aria Marketplace (public) ─────────────────────────────────────────
    if ( is_page_template( 'page-aria-marketplace.php' ) ) {
        wp_enqueue_script(
            'ae-aria-marketplace',
            $uri . '/assets/js/ae-aria-marketplace.js',
            [],
            filemtime( $js . 'ae-aria-marketplace.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-aria-marketplace', 'ae_aria_settings', [
            'products_url' => get_rest_url( null, 'aesoftwares/v1/aria/products' ),
            'contact_url'  => home_url( '/contact/' ),
        ] );
    }

    // ── Aria Console (admin) ──────────────────────────────────────────────
    if ( is_page_template( 'page-aria-console.php' ) ) {
        wp_enqueue_script(
            'ae-aria-console',
            $uri . '/assets/js/ae-aria-console.js',
            [],
            filemtime( $js . 'ae-aria-console.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-aria-console', 'ae_aria_console', [
            'status_url'   => get_rest_url( null, 'aesoftwares/v1/aria/status' ),
            'tasks_url'    => get_rest_url( null, 'aesoftwares/v1/aria/tasks' ),
            'control_url'  => get_rest_url( null, 'aesoftwares/v1/aria/control' ),
            'messages_url' => get_rest_url( null, 'aesoftwares/v1/aria/messages' ),
            'products_url' => get_rest_url( null, 'aesoftwares/v1/aria/products' ),
            'nonce'        => wp_create_nonce( 'wp_rest' ),
            'ws_url'       => 'ws://100.102.97.98:7478',
        ] );
    }

    // ── Cinematic stack (front page only) ─────────────────────────────────
    if ( is_front_page() ) {

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
            wp_enqueue_script( 'gsap',               'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',               [], '3.12.5', [ 'in_footer' => true, 'strategy' => 'defer' ] );
            wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',      [ 'gsap' ], '3.12.5', [ 'in_footer' => true, 'strategy' => 'defer' ] );
        }

        wp_enqueue_script( 'lenis', 'https://unpkg.com/lenis@1.3.23/dist/lenis.min.js', [], '1.3.23', [ 'in_footer' => true, 'strategy' => 'defer' ] );

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


// ── Preconnect hints ────────────────────────────────────────────────────────
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
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_proxy_url' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_token' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_model' );
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
        function() { echo '<p style="color:#999;font-size:13px;">Primary: Local AI Router (Gemma / OpenClaw) on VPS — auto-routes between gemma3:4b for short queries and gemma3:12b for long/complex ones. Fallback: Anthropic API (used if router URL is empty or unreachable).</p>'; },
        'aesoftwares_settings'
    );
    add_settings_field( 'aesoftwares_ollama_proxy_url_field', 'Local AI Router URL',        'aesoftwares_ollama_proxy_url_field_html',    'aesoftwares_settings', 'aesoftwares_ai_section' );
    add_settings_field( 'aesoftwares_ollama_token_field',     'Router Secret Token',        'aesoftwares_ollama_token_field_html',         'aesoftwares_settings', 'aesoftwares_ai_section' );
    add_settings_field( 'aesoftwares_ollama_model_field',     'Default Model',              'aesoftwares_ollama_model_field_html',         'aesoftwares_settings', 'aesoftwares_ai_section' );
    add_settings_field( 'aesoftwares_anthropic_api_key_field','Anthropic API Key (fallback)','aesoftwares_anthropic_api_key_field_html',   'aesoftwares_settings', 'aesoftwares_ai_section' );

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

function aesoftwares_n8n_webhook_url_field_html() {
    $url = get_option( 'aesoftwares_n8n_webhook_url', '' );
    ?><input type="url" name="aesoftwares_n8n_webhook_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://your-n8n-instance.com/webhook/path"><p class="description">Enter the full URL for your n8n webhook.</p><?php
}

function aesoftwares_ollama_proxy_url_field_html() {
    $url = get_option( 'aesoftwares_ollama_proxy_url', '' );
    ?><input type="url" name="aesoftwares_ollama_proxy_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="http://145.79.x.x:9000"><p class="description">Local AI Router URL (no trailing slash). Gemma router on port <code>9000</code>, or Ollama directly on <code>11434/11435</code>. Requests go to <code>{url}/api/chat</code>.</p><?php
}

function aesoftwares_ollama_token_field_html() {
    $token = get_option( 'aesoftwares_ollama_token', '' );
    ?><input type="password" name="aesoftwares_ollama_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" placeholder="your-secret-token"><p class="description">Sent as <code>x-ollama-token</code> header. Leave blank if router has no auth.</p><?php
}

function aesoftwares_ollama_model_field_html() {
    $model = get_option( 'aesoftwares_ollama_model', 'gemma3:4b' );
    ?><input type="text" name="aesoftwares_ollama_model" value="<?php echo esc_attr( $model ); ?>" class="regular-text" placeholder="gemma3:4b"><p class="description">Default model. Gemma router auto-escalates to <code>gemma3:12b</code> for long/complex queries. Suggested: <code>gemma3:4b</code> (fastest), <code>gemma3:12b</code> (highest quality), <code>gemma3:1b</code> (instant).</p><?php
}

function aesoftwares_anthropic_api_key_field_html() {
    $key = get_option( 'aesoftwares_anthropic_api_key', '' );
    ?><input type="password" name="aesoftwares_anthropic_api_key" value="<?php echo esc_attr( $key ); ?>" class="regular-text" placeholder="sk-ant-…"><p class="description">Fallback only — used when Ollama Proxy URL is empty.</p><?php
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
        'permission_callback' => '__return_true',
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

function aesoftwares_call_ai_backend( $system_prompt, $user_message ) {
    $ollama_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $ollama_token = get_option( 'aesoftwares_ollama_token' );
    $ollama_model = get_option( 'aesoftwares_ollama_model', 'gemma3:4b' );

    if ( ! empty( $ollama_url ) ) {
        $payload = [
            'model'    => $ollama_model,
            'stream'   => false,
            'options'  => [
                'temperature' => 0.95,
                'top_p'       => 0.95,
                'top_k'       => 40,
                'think'       => false,
                'num_predict' => 1100,
            ],
            'messages' => [
                [ 'role' => 'system', 'content' => $system_prompt ],
                [ 'role' => 'user',   'content' => $user_message  ],
            ],
        ];

        $response = wp_remote_post( rtrim( $ollama_url, '/' ) . '/api/chat', [
            'method'      => 'POST',
            'headers'     => [
                'Content-Type'   => 'application/json',
                'x-ollama-token' => $ollama_token,
            ],
            'body'        => json_encode( $payload ),
            'data_format' => 'body',
            'timeout'     => 300,
        ] );

        if ( ! is_wp_error( $response ) ) {
            $code = wp_remote_retrieve_response_code( $response );
            $body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( $code === 200 ) {
                $rewritten = $body['message']['content'] ?? '';
                $rewritten = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $rewritten );
                $rewritten = trim( $rewritten );
                if ( $rewritten !== '' ) {
                    return $rewritten;
                }
            }
        }
    }

    $api_key = get_option( 'aesoftwares_anthropic_api_key' );
    if ( empty( $api_key ) ) {
        return new WP_Error( 'no_backend', 'No AI backend is configured. Set the Ollama Proxy URL or Anthropic API key in AE Settings.', [ 'status' => 500 ] );
    }

    $payload = [
        'model'      => 'claude-haiku-4-5-20251001',
        'max_tokens' => 4096,
        'system'     => $system_prompt,
        'messages'   => [
            [ 'role' => 'user', 'content' => $user_message ],
        ],
    ];

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'method'      => 'POST',
        'headers'     => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
        ],
        'body'        => json_encode( $payload ),
        'data_format' => 'body',
        'timeout'     => 120,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', 'Failed to reach the Anthropic API.', [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code !== 200 ) {
        $msg = $body['error']['message'] ?? 'Anthropic API error.';
        return new WP_Error( 'anthropic_error', $msg, [ 'status' => 502 ] );
    }

    return $body['content'][0]['text'] ?? '';
}

function aesoftwares_call_ai_backend_short( $system_prompt, $user_message ) {
    $ollama_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $ollama_token = get_option( 'aesoftwares_ollama_token' );
    $ollama_model = get_option( 'aesoftwares_ollama_model', 'gemma3:4b' );

    if ( ! empty( $ollama_url ) ) {
        $payload = [
            'model'    => $ollama_model,
            'stream'   => false,
            'options'  => [
                'temperature' => 0.3,
                'top_p'       => 0.9,
            ],
            'messages' => [
                [ 'role' => 'system', 'content' => $system_prompt ],
                [ 'role' => 'user',   'content' => $user_message  ],
            ],
        ];

        $response = wp_remote_post( rtrim( $ollama_url, '/' ) . '/api/chat', [
            'method'      => 'POST',
            'headers'     => [
                'Content-Type'   => 'application/json',
                'x-ollama-token' => $ollama_token,
            ],
            'body'        => json_encode( $payload ),
            'data_format' => 'body',
            'timeout'     => 45,
        ] );

        if ( ! is_wp_error( $response ) ) {
            $code = wp_remote_retrieve_response_code( $response );
            $body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( $code === 200 ) {
                $text = $body['message']['content'] ?? '';
                $text = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $text );
                $text = trim( $text );
                if ( $text !== '' ) return $text;
            }
        }
    }

    $api_key = get_option( 'aesoftwares_anthropic_api_key' );
    if ( empty( $api_key ) ) return new WP_Error( 'no_backend', 'No AI backend configured.', [ 'status' => 500 ] );

    $payload = [
        'model'      => 'claude-haiku-4-5-20251001',
        'max_tokens' => 1024,
        'system'     => $system_prompt,
        'messages'   => [ [ 'role' => 'user', 'content' => $user_message ] ],
    ];

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'method'      => 'POST',
        'headers'     => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
        ],
        'body'        => json_encode( $payload ),
        'data_format' => 'body',
        'timeout'     => 45,
    ] );

    if ( is_wp_error( $response ) ) return null;

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( $code !== 200 ) return null;

    return $body['content'][0]['text'] ?? '';
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
        'permission_callback' => '__return_true',
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
        'permission_callback' => '__return_true',
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


// ── Shared VPS/Anthropic caller ───────────────────────────────────────────────
function aesoftwares_call_vps( $system_prompt, $user_message, $response_key ) {
    $ollama_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $ollama_token = get_option( 'aesoftwares_ollama_token' );
    $ollama_model = get_option( 'aesoftwares_ollama_model', 'gemma3:4b' );

    if ( ! empty( $ollama_url ) ) {
        $payload = [
            'model'    => $ollama_model,
            'stream'   => false,
            'messages' => [
                [ 'role' => 'system', 'content' => $system_prompt ],
                [ 'role' => 'user',   'content' => $user_message  ],
            ],
        ];

        $response = wp_remote_post( rtrim( $ollama_url, '/' ) . '/api/chat', [
            'method'      => 'POST',
            'headers'     => [
                'Content-Type'   => 'application/json',
                'x-ollama-token' => $ollama_token,
            ],
            'body'        => json_encode( $payload ),
            'data_format' => 'body',
            'timeout'     => 300,
        ] );

        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $body   = json_decode( wp_remote_retrieve_body( $response ), true );
            $result = $body['message']['content'] ?? '';
            $result = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $result );
            $result = trim( $result );
            if ( $result !== '' ) {
                return new WP_REST_Response( [ $response_key => $result ], 200 );
            }
        }
    }

    $api_key = get_option( 'aesoftwares_anthropic_api_key' );
    if ( empty( $api_key ) ) {
        return new WP_Error( 'no_backend', 'No AI backend configured.', [ 'status' => 500 ] );
    }

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'method'      => 'POST',
        'headers'     => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
        ],
        'body'        => json_encode( [
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 4096,
            'system'     => $system_prompt,
            'messages'   => [ [ 'role' => 'user', 'content' => $user_message ] ],
        ] ),
        'data_format' => 'body',
        'timeout'     => 60,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', 'Failed to reach the AI backend.', [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code !== 200 ) {
        $msg = $body['error']['message'] ?? 'AI backend error.';
        return new WP_Error( 'backend_error', $msg, [ 'status' => 502 ] );
    }

    return new WP_REST_Response( [ $response_key => $body['content'][0]['text'] ?? '' ], 200 );
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


function aesoftwares_register_chat_route() {
    register_rest_route( 'aesoftwares/v1', '/chat', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_chat_message',
        'permission_callback' => '__return_true',
    ] );
}
add_action( 'rest_api_init', 'aesoftwares_register_chat_route' );

// On-demand validation endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/validate', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_validate',
        'permission_callback' => '__return_true',
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
        'permission_callback' => '__return_true',
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
    $model_name = get_option( 'aesoftwares_ollama_model', 'Systran/faster-whisper-small' );

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
        'permission_callback' => '__return_true',
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

    // ── Primary: Local AI Router (Gemma 4b/12b via OpenClaw, or Ollama direct) ──
    $router_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $router_token = get_option( 'aesoftwares_ollama_token' );
    $router_model = get_option( 'aesoftwares_ollama_model', 'gemma3:4b' );

    if ( ! empty( $router_url ) ) {
        $payload = [
            'model'   => $router_model,
            'stream'  => false,
            'options' => [
                'temperature' => 0.7,
                'top_p'       => 0.9,
                'think'       => false,
                'num_predict' => 1024,   // Gemma 4b cap; router escalates to 12b @ 4096 for complex
            ],
            'messages' => array_merge(
                [ [ 'role' => 'system', 'content' => $system_prompt ] ],
                $safe_history
            ),
        ];

        $headers = [ 'Content-Type' => 'application/json' ];
        if ( ! empty( $router_token ) ) {
            $headers['x-ollama-token'] = $router_token;
        }

        $response = wp_remote_post( rtrim( $router_url, '/' ) . '/api/chat', [
            'method'      => 'POST',
            'headers'     => $headers,
            'body'        => json_encode( $payload ),
            'data_format' => 'body',
            'timeout'     => 300,   // 12b fallback can take up to 300s on cold start
        ] );

        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $body  = json_decode( wp_remote_retrieve_body( $response ), true );
            $reply = $body['message']['content'] ?? '';
            // Strip <think> blocks from any reasoning model output
            $reply = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $reply );
            $reply = trim( $reply );
            if ( $reply !== '' ) {
                return new WP_REST_Response( [ 'reply' => $reply ], 200 );
            }
        }
    }

    // Fallback: Anthropic
    $api_key = get_option( 'aesoftwares_anthropic_api_key' );
    if ( empty( $api_key ) ) {
        return new WP_Error( 'no_backend', 'No AI backend configured. Set the Ollama Proxy URL or Anthropic API key in AE Settings.', [ 'status' => 500 ] );
    }

    $response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
        'method'      => 'POST',
        'headers'     => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
        ],
        'body'        => json_encode( [
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 1024,
            'system'     => $system_prompt,
            'messages'   => $safe_history ?: [ [ 'role' => 'user', 'content' => 'Begin the assessment.' ] ],
        ] ),
        'data_format' => 'body',
        'timeout'     => 120,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', 'Failed to reach the AI backend.', [ 'status' => 502 ] );
    }

    $code = wp_remote_retrieve_response_code( $response );
    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code !== 200 ) {
        $msg = $data['error']['message'] ?? 'AI backend error.';
        return new WP_Error( 'backend_error', $msg, [ 'status' => 502 ] );
    }

    return new WP_REST_Response( [ 'reply' => $data['content'][0]['text'] ?? '' ], 200 );
}

// ══════════════════════════════════════════════════════════════════════════════
// 9. REST API — Text-to-Speech (Speaches / Kokoro)
// ══════════════════════════════════════════════════════════════════════════════

// TTS endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'aesoftwares/v1', '/tts', [
        'methods'             => 'POST',
        'callback'            => 'aesoftwares_handle_tts',
        'permission_callback' => '__return_true',
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
require_once get_template_directory() . '/aria-endpoints.php';

// ── SEO: default og:image (Yoast emits none when a page has no featured
// image, so social shares render without a preview card) ─────────────────────
add_action( 'wpseo_add_opengraph_images', function ( $image_container ) {
    if ( method_exists( $image_container, 'has_images' ) && $image_container->has_images() ) {
        return;
    }
    $image_container->add_image_by_url( 'https://aesoftwaresolutions.com/wp-content/uploads/2025/11/cropped-Let-us-do-the-work-for-you.jpeg' );
} );

// ── SEO: ProfessionalService schema on the front page. Yoast only emits a
// generic Organization node; this adds the local-business signals (service
// area, locality, price range) that Google uses for "near me" queries. ───────
add_action( 'wp_head', function () {
    if ( ! is_front_page() ) {
        return;
    }
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfessionalService',
        'name'        => 'AE Software Solutions LLC',
        'url'         => home_url( '/' ),
        'email'       => 'admin@aesoftwaresolutions.com',
        'image'       => 'https://aesoftwaresolutions.com/wp-content/uploads/2025/11/cropped-Let-us-do-the-work-for-you.jpeg',
        'description' => 'Custom workflow automation, AI agents, and websites for small businesses — missed-call text-back, instant lead follow-up, and booking automation.',
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Chambersburg',
            'addressRegion'   => 'PA',
            'addressCountry'  => 'US',
        ],
        'areaServed'  => [ 'Pennsylvania', 'Maryland', 'Virginia', 'Washington DC' ],
        'priceRange'  => '$750 - $8,000',
        'makesOffer'  => [
            [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Workflow Automation (missed-call text-back, lead follow-up, booking)' ] ],
            [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Business Websites with Lead Capture' ] ],
            [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'AI Agents and Custom CRM' ] ],
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 20 );

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
