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

    // ── Rewriter tool (rewriter page only) ───────────────────────────────
    if ( is_page_template( 'page-rewriter.php' ) ) {
        wp_enqueue_script(
            'ae-rewriter',
            $uri . '/assets/js/ae-rewriter.js',
            [],
            filemtime( $js . 'ae-rewriter.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );
        wp_localize_script( 'ae-rewriter', 'ae_rewriter_settings', [
            'rewrite_url' => get_rest_url( null, 'aesoftwares/v1/rewrite' ),
        ] );
    }

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
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_proxy_url' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_token' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_ollama_model' );
    register_setting( 'aesoftwares_settings', 'aesoftwares_anthropic_api_key' );

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

    add_settings_section(
        'aesoftwares_ai_section',
        'AI Rewriter Settings',
        function() {
            echo '<p style="color:#999;font-size:13px;">Primary: Ollama on VPS. Fallback: Anthropic API (used if Ollama Proxy URL is empty).</p>';
        },
        'aesoftwares_settings'
    );

    add_settings_field(
        'aesoftwares_ollama_proxy_url_field',
        'Ollama Proxy URL',
        'aesoftwares_ollama_proxy_url_field_html',
        'aesoftwares_settings',
        'aesoftwares_ai_section'
    );

    add_settings_field(
        'aesoftwares_ollama_token_field',
        'Ollama Secret Token',
        'aesoftwares_ollama_token_field_html',
        'aesoftwares_settings',
        'aesoftwares_ai_section'
    );

    add_settings_field(
        'aesoftwares_ollama_model_field',
        'Ollama Model',
        'aesoftwares_ollama_model_field_html',
        'aesoftwares_settings',
        'aesoftwares_ai_section'
    );

    add_settings_field(
        'aesoftwares_anthropic_api_key_field',
        'Anthropic API Key (fallback)',
        'aesoftwares_anthropic_api_key_field_html',
        'aesoftwares_settings',
        'aesoftwares_ai_section'
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

function aesoftwares_ollama_proxy_url_field_html() {
    $url = get_option( 'aesoftwares_ollama_proxy_url', '' );
    ?>
    <input type="url" name="aesoftwares_ollama_proxy_url"
           value="<?php echo esc_attr( $url ); ?>"
           class="regular-text"
           placeholder="http://145.79.x.x:11435">
    <p class="description">VPS Nginx proxy URL (no trailing slash). Requests go to <code>{url}/api/chat</code>.</p>
    <?php
}

function aesoftwares_ollama_token_field_html() {
    $token = get_option( 'aesoftwares_ollama_token', '' );
    ?>
    <input type="password" name="aesoftwares_ollama_token"
           value="<?php echo esc_attr( $token ); ?>"
           class="regular-text"
           placeholder="your-secret-token">
    <p class="description">Sent as <code>x-ollama-token</code> header. Must match the Nginx proxy config.</p>
    <?php
}

function aesoftwares_ollama_model_field_html() {
    $model = get_option( 'aesoftwares_ollama_model', 'qwen3:8b' );
    ?>
    <input type="text" name="aesoftwares_ollama_model"
           value="<?php echo esc_attr( $model ); ?>"
           class="regular-text"
           placeholder="qwen3:8b">
    <p class="description">Ollama model name. Default: <code>qwen3:8b</code>.</p>
    <?php
}

function aesoftwares_anthropic_api_key_field_html() {
    $key = get_option( 'aesoftwares_anthropic_api_key', '' );
    ?>
    <input type="password" name="aesoftwares_anthropic_api_key"
           value="<?php echo esc_attr( $key ); ?>"
           class="regular-text"
           placeholder="sk-ant-…">
    <p class="description">Fallback only — used when Ollama Proxy URL is empty. Get a key at console.anthropic.com.</p>
    <?php
}


// ══════════════════════════════════════════════════════════════════════════════
// 3. REST API PROXY — forwards chatbot messages to n8n webhook
// ══════════════════════════════════════════════════════════════════════════════

// ══════════════════════════════════════════════════════════════════════════════
// 4. REST API — AI Rewriter (Ollama VPS primary, Anthropic fallback)
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

// Returns the fraction of $n-word sequences in $rewritten that also appear in $original.
// Used to catch rewrites that are too close to the source.
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

// Split text into ~$max_words chunks on paragraph boundaries.
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

// Send one system + user message to the configured AI backend.
// Returns the rewritten string or a WP_Error.
function aesoftwares_call_ai_backend( $system_prompt, $user_message ) {
    $ollama_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $ollama_token = get_option( 'aesoftwares_ollama_token' );
    $ollama_model = get_option( 'aesoftwares_ollama_model', 'qwen3:8b' );

    if ( ! empty( $ollama_url ) ) {
        $payload = [
            'model'    => $ollama_model,
            'stream'   => false,
            'options'  => [
                'temperature' => 0.95,  // higher = less predictable word choices = lower perplexity score on detectors
                'top_p'       => 0.95,
                'top_k'       => 40,
                'think'       => false, // disable qwen3 thinking mode — deliberate reasoning produces uniformly high-probability output
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
        // Fall through to Anthropic if Ollama failed
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

// Short-timeout variant for validation — avoids blocking the response on a slow eval call.
function aesoftwares_call_ai_backend_short( $system_prompt, $user_message ) {
    $ollama_url   = get_option( 'aesoftwares_ollama_proxy_url' );
    $ollama_token = get_option( 'aesoftwares_ollama_token' );
    $ollama_model = get_option( 'aesoftwares_ollama_model', 'qwen3:8b' );

    if ( ! empty( $ollama_url ) ) {
        $payload = [
            'model'    => $ollama_model,
            'stream'   => false,
            'options'  => [
                'temperature' => 0.3,  // low temp for validation — we want consistent JSON, not creative output
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

// Evaluate text against 7 human-writing signals. Returns parsed array or null on failure.
// Uses the short-timeout backend so it never blocks the rewrite response for long.
function aesoftwares_validate_content( $text ) {
    $system_prompt = 'You are an AI content detection analyst. Evaluate the text provided against seven signals and return a single JSON object — no other text, no markdown, no explanation.

Score each signal from 0 to 10 where 0 = strongly AI-like and 10 = strongly human-like.

Signals:
1. perplexity_uniformity — Are word choices predictable and safe throughout, or does the writer occasionally reach for an unexpected or unconventional word? (0 = every word is the most probable choice; 10 = meaningfully unpredictable vocabulary)
2. burstiness — Does sentence length and complexity vary significantly, or is rhythm uniform? (0 = all sentences roughly the same length/structure; 10 = genuine mix of very short and very long sentences)
3. structural_patterns — Do paragraphs all follow the same formula (topic sentence → support → close) with repeated transition words? (0 = rigid formulaic structure; 10 = varied paragraph shapes and openers)
4. hedging_and_nuance — Does the writer acknowledge uncertainty or qualify claims where a human would? (0 = all absolute declarative statements; 10 = natural hedging like "it seems", "arguably", "tends to")
5. vocabulary_naturalness — Does word choice feel naturally human or optimised for clarity and correctness? Contractions, idioms, colloquialisms where appropriate. (0 = sterile/formal throughout; 10 = naturally chosen vocabulary)
6. grammatical_perfection — Is every sentence flawlessly correct in a way that feels unnatural? (0 = robotically perfect; 10 = minor natural imperfections like comma splices, casual fragments)
7. semantic_coherence — Does the text flow "too perfectly", every sentence connecting to the next without any tangent or aside? (0 = over-optimised flow; 10 = organic, occasionally digressive)

overall_human_score: integer 0–100, calculated as round(sum_of_all_signal_scores / 7 * 10)
verdict: "LIKELY_HUMAN" if overall >= 85, "BORDERLINE" if 65–84, "LIKELY_AI" if below 65
flags: array of brief descriptions for any signal that scored below 6 (empty array if none)
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
    // Budget 10 min total to cover multiple chunks on large submissions.
    set_time_limit( 600 );

    $text = $request->get_param( 'text' );
    $mode = $request->get_param( 'mode' );

    $word_count = str_word_count( $text );
    if ( $word_count > 2000 ) {
        return new WP_Error( 'too_long', 'Text exceeds the 2,000 word limit.', [ 'status' => 422 ] );
    }

    $mode_instructions = [
        'standard' => 'Rewrite in a grounded, thoughtful voice — serious but not stiff. Contractions throughout. Vary sentence length erratically: some very short (3–5 words), some long and winding. At least two paragraphs must be a single sentence. Do not summarise at the end of each paragraph. No two paragraphs may open with the same grammatical construction. Embed citations mid-clause.',

        'academic' => 'Rewrite in a scholarly voice that still sounds like a person. Use hedging ("suggests", "appears to", "the data point toward") but place hedges in different positions — mid-clause, end of sentence, start of paragraph — never the same slot twice in a row. Some paragraphs open with evidence; some end on a question or unresolved tension. Citations embedded mid-thought, not announced. At least one paragraph should be a single sentence.',

        'casual'   => 'Rewrite as a smart person talking directly to a reader — blog-post energy, not formal writing. Fragments are fine. Contractions everywhere. Some paragraphs one sentence. Start with the point, not a wind-up. End paragraphs when the thought is done, not with a summary.',
    ];

    $system_prompt = 'You are a human writer rewriting text so it cannot be detected as AI-generated by tools like CopyLeaks, GPTZero, or Originality.ai. These tools detect AI by measuring how predictable each word choice is (perplexity) and how uniformly predictable the whole document is (burstiness). Your job is to be genuinely unpredictable.

Preserve all original meaning, facts, citations, and argument structure exactly.

SENTENCE-LEVEL TECHNIQUES — apply all of these:
- Choose the less obvious synonym sometimes: not "important" → try "consequential", "telling", or "worth pausing on". Not "shows" → try "hints at", "points toward", "complicates".
- Start some sentences with conjunctions: And. But. Or. So. (These are low-probability openers for AI.)
- Use deliberate fragments. Short ones. They work.
- Use em-dashes to interrupt or redirect mid-sentence — like a mind catching itself — rather than always using commas.
- Insert a parenthetical aside (a thought the writer has out loud) at least once per section.
- Occasionally invert normal word order for emphasis: "What the data do not show is equally significant."
- Let one sentence per section feel like an afterthought or a concession: "That said, ...", "Then again, ...", "Or perhaps not."

PARAGRAPH-LEVEL TECHNIQUES — apply all of these:
- Make at least two paragraphs exactly one sentence long.
- Vary paragraph length unpredictably: 1 sentence, then 4, then 2, then 5.
- Do NOT end paragraphs with a sentence that restates or summarises what the paragraph just said.
- Some paragraphs should start with a concrete detail or example, not a topic claim.
- No two consecutive paragraphs may open with the same word or the same grammatical construction (e.g. not two "The ..." openers in a row).

FORBIDDEN — these phrases and patterns appear with AI statistical frequency and will be flagged:
- "This shows that", "This means that", "This demonstrates", "This highlights", "This is important because"
- "Furthermore", "Moreover", "In addition", "In conclusion", "It is worth noting", "Notably", "Importantly"
- "It is important to remember", "At first glance", "When we consider", "Taking this into account"
- Any 5+ consecutive words carried over unchanged from the source text
- Paragraph-ending summary sentences
- Two consecutive paragraphs with the same opening word or construction

STYLE EXAMPLES — study the contrast between these two versions. The second version passed human-content detection; the first did not.

FLAGGED AS AI (avoid this style):
"A further reason why zoos are good is because it has research. Many people overlook this part. Research is very important in zoos. This shows that zoos help animals. Furthermore, zoos are beneficial to society. It is important to note that zoos provide conservation. Moreover, zoos educate the public."

PASSED AS HUMAN (write like this):
"I did not think I would choose it when I first started looking into zoos as a topic. One of those subjects on which people have already made up their minds — usually before they have spent much time thinking about it. But the research side surprised me. Not the headline conservation numbers, which get trotted out whenever someone defends a zoo, but the quieter institutional work: the studbooks, the reintroduction logistics, the veterinary data shared across facilities. It is not glamorous. And it probably does not make up for everything. Still."

Notice: the passing version uses first person, admits uncertainty, interrupts itself, ends on an incomplete thought, and never uses transition words like "Furthermore" or "Moreover". The flagged version is structurally predictable — topic sentence, elaboration, transition, repeat.

Output ONLY the rewritten text. No preamble, no labels, no markdown.';

    // Split into ~500-word chunks on paragraph boundaries for large inputs.
    $chunks        = $word_count > 500 ? aesoftwares_chunk_text( $text ) : [ $text ];
    $total         = count( $chunks );
    $parts         = [];
    $overlap_limit = 0.15;
    $max_retries   = 1;

    foreach ( $chunks as $i => $chunk ) {
        $chunk_note   = $total > 1
            ? 'This is section ' . ( $i + 1 ) . " of {$total} from a longer piece. Match the same voice and register throughout.\n\n"
            : '';
        $base_message = $mode_instructions[ $mode ] . "\n\n" . $chunk_note . "Text to rewrite:\n\n" . $chunk;

        $result           = null;
        $retry_notes_prev = [];

        for ( $attempt = 0; $attempt <= $max_retries; $attempt++ ) {
            $user_message = $attempt === 0
                ? $base_message
                : $base_message . "\n\nRETRY FEEDBACK — fix these issues from your previous attempt:\n" . implode( "\n", $retry_notes_prev );

            $candidate = aesoftwares_call_ai_backend( $system_prompt, $user_message );
            if ( is_wp_error( $candidate ) ) return $candidate;
            $result = $candidate;

            $overlap = aesoftwares_ngram_overlap_ratio( $chunk, $result );
            if ( $overlap > $overlap_limit && $attempt < $max_retries ) {
                $pct              = round( $overlap * 100 );
                $retry_notes_prev = [ "PHRASE OVERLAP {$pct}%: reconstruct every sentence — do not carry over any 5+ word sequence from the source." ];
                continue;
            }

            break;
        }

        $parts[] = $result;
    }

    $rewritten  = implode( "\n\n", $parts );
    $validation = aesoftwares_validate_content( $rewritten );

    return new WP_REST_Response( [ 'rewritten' => $rewritten, 'validation' => $validation ], 200 );
}


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
