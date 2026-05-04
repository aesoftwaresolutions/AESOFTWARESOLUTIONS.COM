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
        'standard' => 'Rewrite the text with a natural, grounded voice — the kind a thoughtful person uses when writing seriously but not stiffly. Keep one consistent register throughout; do not mix casual filler words ("really", "lots of", "you might think") with formal prose in the same paragraph. Vary sentence length genuinely: some sentences should be very short. Others can run longer and carry more than one idea, connected naturally. Not every sentence needs to alternate. Let paragraph endings land on the idea itself — do not add a sentence that restates what the paragraph just said. Do not open consecutive paragraphs with the same word or phrase. Introduce evidence mid-thought or at the end of a claim, not always at the start of a sentence. Use contractions where they feel natural, not on a schedule.',
        'academic' => 'Rewrite the text in a scholarly voice that still sounds like a person wrote it. Use discipline-appropriate vocabulary and hedging ("suggests", "appears to", "the evidence indicates") but vary where hedges appear — not every sentence, and not always in the same position. Break the formulaic paragraph structure: not every paragraph needs a topic sentence, supporting evidence, citation, and closing restatement in that order. Some paragraphs can open with the evidence and build to the claim. Some can end on a question or an unresolved tension. Citations should be embedded mid-clause or at the end of a point, not always in their own standalone sentence. No two consecutive paragraphs should open with the same type of construction.',
        'casual'   => 'Rewrite the text the way someone would write a personal blog post or a message to a smart friend. Short sentences are fine. So are fragments, if they land right. Use contractions naturally, not in every sentence just to hit a quota. Pick one consistent voice — either informal throughout or semi-formal throughout — and stick to it. Do not drop in phrases like "you might think" or "many people overlook this" as paragraph openers; start with the actual point. Vary how paragraphs end: some can end on a statement, some on a question, some just stop when the thought is done.',
    ];

    $system_prompt = 'You are rewriting text to sound naturally human. Preserve all original meaning, facts, citations, and argument structure. Your most important job is to avoid the specific patterns that AI detectors flag.

FORBIDDEN — never use these:
- Connector phrases: "This means", "This shows", "This is important because", "This demonstrates", "By doing X, Y can Z", "In addition", "Furthermore", "Moreover"
- Padding openers: "Many people overlook", "It is worth noting that", "It is important to remember", "At first glance", "Interestingly"
- Paragraph-closing restatements: do not end a paragraph with a sentence that summarises what the paragraph just said
- Formulaic paragraph structure: claim → explanation → example → citation → restate — break this pattern in at least half the paragraphs
- Consecutive paragraphs opening with the same word, phrase, or sentence type
- Register mixing: do not use casual filler words ("really", "lots of", "pretty much", "just") in the same paragraph as formal vocabulary

REQUIRED:
- Genuine sentence length variation — not a pattern of alternating short and long, but truly irregular rhythm
- Consistent register throughout the full piece (choose one and hold it)
- Citations introduced mid-clause or at the end of a point, not always in their own lead sentence
- Some paragraphs should end on the idea itself, without a wrap-up sentence
- Where the original uses first person, keep first person

Output ONLY the rewritten text. No preamble, no commentary, no markdown.';

    // Split into ~500-word chunks on paragraph boundaries for large inputs.
    $chunks        = $word_count > 500 ? aesoftwares_chunk_text( $text ) : [ $text ];
    $total         = count( $chunks );
    $parts         = [];
    $overlap_limit = 0.15; // flag if >15% of 6-grams are unchanged from source
    $max_retries   = 2;

    foreach ( $chunks as $i => $chunk ) {
        $chunk_note   = $total > 1
            ? 'This is section ' . ( $i + 1 ) . " of {$total} from a longer piece. Match the same voice and register you have used in the other sections.\n\n"
            : '';
        $base_message = $mode_instructions[ $mode ] . "\n\n" . $chunk_note . "Text to rewrite:\n\n" . $chunk;

        $result = null;

        for ( $attempt = 0; $attempt <= $max_retries; $attempt++ ) {
            if ( $attempt === 0 ) {
                $user_message = $base_message;
            } else {
                $pct          = round( aesoftwares_ngram_overlap_ratio( $chunk, $result ) * 100 );
                $user_message = $base_message . "\n\nORIGINALITY FAILURE — your previous attempt kept approximately {$pct}% of the original phrasing intact (measured as shared 6-word sequences). You must rephrase far more aggressively. Reconstruct every sentence from scratch using different words, different sentence openings, and different clause order. Do not carry over any sequence of five or more consecutive words from the source text. Preserve only the meaning, the facts, and any citations.";
            }

            $candidate = aesoftwares_call_ai_backend( $system_prompt, $user_message );

            if ( is_wp_error( $candidate ) ) {
                return $candidate;
            }

            $result  = $candidate;
            $overlap = aesoftwares_ngram_overlap_ratio( $chunk, $result );

            if ( $overlap <= $overlap_limit || $attempt === $max_retries ) {
                break;
            }
        }

        $parts[] = $result;
    }

    return new WP_REST_Response( [ 'rewritten' => implode( "\n\n", $parts ) ], 200 );
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
