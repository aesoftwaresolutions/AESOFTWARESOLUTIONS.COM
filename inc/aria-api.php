<?php
/**
 * Aria REST API
 *
 * POST /wp-json/aesoftwares/v1/aria/push          — Aria pushes heartbeat status (Bearer token)
 * GET  /wp-json/aesoftwares/v1/aria/status        — Dashboard reads status (admin-only)
 * POST /wp-json/aesoftwares/v1/aria/assets        — Aria creates a product (WooCommerce or CPT fallback)
 * GET  /wp-json/aesoftwares/v1/aria/assets        — List Aria's assets (public)
 * POST /wp-json/aesoftwares/v1/aria/upload-report — Upload PDF report file (Bearer token)
 */

// ── Custom Post Type: ae_product (WooCommerce-free product store) ─────────────
add_action( 'init', function () {
    register_post_type( 'ae_product', [
        'labels'       => [
            'name'          => 'Aria Products',
            'singular_name' => 'Aria Product',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_rest' => false,
        'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'menu_icon'    => 'dashicons-store',
    ] );
} );

add_action( 'rest_api_init', function () {

    register_rest_route( 'aesoftwares/v1', '/aria/push', [
        'methods'             => 'POST',
        'callback'            => 'ae_aria_handle_push',
        'permission_callback' => 'ae_aria_verify_token',
    ] );

    register_rest_route( 'aesoftwares/v1', '/aria/status', [
        'methods'             => 'GET',
        'callback'            => 'ae_aria_handle_status',
        'permission_callback' => function () { return current_user_can( 'manage_options' ); },
    ] );

    register_rest_route( 'aesoftwares/v1', '/aria/assets', [
        [
            'methods'             => 'POST',
            'callback'            => 'ae_aria_create_asset',
            'permission_callback' => 'ae_aria_verify_token',
        ],
        [
            'methods'             => 'GET',
            'callback'            => 'ae_aria_list_assets',
            'permission_callback' => '__return_true',
        ],
    ] );

    register_rest_route( 'aesoftwares/v1', '/aria/tasks', [
        'methods'             => 'GET',
        'callback'            => 'ae_aria_handle_tasks',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( 'aesoftwares/v1', '/aria/upload-report', [
        'methods'             => 'POST',
        'callback'            => 'ae_aria_upload_report',
        'permission_callback' => 'ae_aria_verify_token',
    ] );

    register_rest_route( 'aesoftwares/v1', '/aria/products', [
        'methods'             => 'GET',
        'callback'            => 'ae_aria_list_assets',
        'permission_callback' => '__return_true',
    ] );
} );

/** Verify Bearer token from Authorization header */
function ae_aria_verify_token() {
    // Option stored by aria-endpoints.php settings form under 'aesoftwares_aria_push_token';
    // fall back to legacy 'ae_aria_push_token' key so either works.
    $expected = get_option( 'aesoftwares_aria_push_token', get_option( 'ae_aria_push_token', '' ) );
    if ( empty( $expected ) ) return false;
    $auth = isset( $_SERVER['HTTP_AUTHORIZATION'] ) ? $_SERVER['HTTP_AUTHORIZATION'] : (
        isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : ''
    );
    if ( preg_match( '/^Bearer\s+(.+)$/i', trim( $auth ), $m ) ) {
        return hash_equals( $expected, trim( $m[1] ) );
    }
    return false;
}

/** Aria → WP status push */
function ae_aria_handle_push( WP_REST_Request $req ) {
    $body = $req->get_json_params();
    if ( empty( $body ) ) {
        return new WP_Error( 'no_data', 'No JSON body.', [ 'status' => 422 ] );
    }

    $status = [
        'pushed_at'           => time(),
        'model'               => sanitize_text_field( $body['model']               ?? '' ),
        'vram_gb'             => floatval(             $body['vram_gb']             ?? 0 ),
        'active_requests'     => intval(               $body['active_requests']     ?? 0 ),
        'status'              => sanitize_text_field( $body['status']              ?? 'unknown' ),
        'current_task'        => sanitize_text_field( $body['current_task']        ?? '' ),
        'current_phase'       => sanitize_text_field( $body['current_phase']       ?? '' ),
        'paused'              => (bool) ( $body['paused']              ?? false ),
        'waiting_approval'    => (bool) ( $body['waiting_approval']    ?? false ),
        'waiting_for_reply'   => sanitize_text_field( $body['waiting_for_reply']   ?? '' ),
        'waiting_for_phase'   => sanitize_text_field( $body['waiting_for_phase']   ?? '' ),
        'pending_instruction' => sanitize_text_field( $body['pending_instruction'] ?? '' ),
        'last_message_scan'   => sanitize_text_field( $body['last_message_scan']   ?? '' ),
        'browser_task'        => sanitize_text_field( $body['browser_task']        ?? '' ),
        'browser_step'        => sanitize_text_field( $body['browser_step']        ?? '' ),
        'phase_stats'         => ( is_array( $body['phase_stats'] ?? null ) ? $body['phase_stats'] : [] ),
        'activity_log'        => ( is_array( $body['activity_log'] ?? null )
            ? array_slice( array_map( 'sanitize_text_field', $body['activity_log'] ), -40 )
            : [] ),
    ];

    $tasks_md = sanitize_textarea_field( $body['tasks_md'] ?? '' );
    if ( $tasks_md ) {
        update_option( 'ae_aria_tasks_md', $tasks_md, false );
    }

    update_option( 'ae_aria_status', $status, false );
    return new WP_REST_Response( [ 'ok' => true, 'received_at' => $status['pushed_at'] ], 200 );
}

/** Dashboard reads Aria's last-known status */
function ae_aria_handle_status() {
    $status = get_option( 'ae_aria_status', null );
    if ( $status === null ) {
        return new WP_REST_Response( [ 'online' => false, 'message' => 'No status received yet. Aria has not pushed since the endpoint was created.' ], 200 );
    }
    $age              = time() - ( (int) $status['pushed_at'] );
    $status['age_s']  = $age;
    $status['online'] = $age < 300; // offline if no push in 5 min
    return new WP_REST_Response( $status, 200 );
}

/** Return Aria's stored tasks markdown */
function ae_aria_handle_tasks() {
    $tasks = get_option( 'ae_aria_tasks_md', '' );
    return new WP_REST_Response( $tasks, 200 );
}

/** Shared cover-image attachment helper */
function ae_aria_attach_cover( $product_id, $cover_b64, $cover_filename, $name ) {
    if ( empty( $cover_b64 ) ) return;
    $image_bytes = base64_decode( $cover_b64 );
    if ( ! $image_bytes || strlen( $image_bytes ) < 100 ) return;

    $upload_dir = wp_upload_dir();
    $covers_dir = $upload_dir['basedir'] . '/aria-covers';
    if ( ! file_exists( $covers_dir ) ) wp_mkdir_p( $covers_dir );
    $cover_path = $covers_dir . '/' . sanitize_file_name( $cover_filename );
    file_put_contents( $cover_path, $image_bytes );

    $attach_id = wp_insert_attachment( [
        'guid'           => $upload_dir['baseurl'] . '/aria-covers/' . sanitize_file_name( $cover_filename ),
        'post_mime_type' => 'image/png',
        'post_title'     => sanitize_text_field( $name . ' Cover' ),
        'post_status'    => 'inherit',
    ], $cover_path, $product_id );

    if ( $attach_id && ! is_wp_error( $attach_id ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $cover_path ) );
        set_post_thumbnail( $product_id, $attach_id );
    }
}

/** Aria creates a digital product — WooCommerce if active, custom ae_product CPT otherwise */
function ae_aria_create_asset( WP_REST_Request $req ) {
    $body = $req->get_json_params();

    $name     = sanitize_text_field( $body['name']              ?? '' );
    $desc     = wp_kses_post(         $body['description']       ?? '' );
    $short    = sanitize_textarea_field( $body['short_description'] ?? '' );
    $price    = sanitize_text_field( $body['price']              ?? '0' );
    $file_url = esc_url_raw(          $body['file_url']          ?? '' );
    $file_name = sanitize_text_field( $body['file_name']         ?? 'Download' );
    $category = sanitize_text_field( $body['category']           ?? 'Digital Assets' );
    $tags     = is_array( $body['tags'] ?? null ) ? array_map( 'sanitize_text_field', $body['tags'] ) : [];
    $cover_b64  = $body['cover_image_b64'] ?? '';
    $cover_file = sanitize_file_name( $body['cover_filename'] ?? 'cover.png' );

    if ( empty( $name ) ) {
        return new WP_Error( 'missing_name', 'Product name is required.', [ 'status' => 422 ] );
    }

    // ── Path A: WooCommerce active ────────────────────────────────────────────
    if ( class_exists( 'WooCommerce' ) ) {
        $product = new WC_Product_Simple();
        $product->set_name( $name );
        $product->set_description( $desc );
        $product->set_short_description( $short );
        $product->set_regular_price( $price );
        $product->set_downloadable( true );
        $product->set_virtual( true );
        $product->set_status( 'publish' );

        if ( ! empty( $file_url ) ) {
            // Approve the aria-reports upload directory in WooCommerce's download allowlist
            if ( class_exists( 'WC_Downloads_Approved_Directories' ) ) {
                $adi = WC_Downloads_Approved_Directories::get_instance();
                $adi->add_approved_directory( trailingslashit( dirname( $file_url ) ) );
            }
            try {
                $dl = new WC_Product_Download();
                $dl->set_name( $file_name );
                $dl->set_file( $file_url );
                $dl->set_id( md5( $file_url ) );
                $product->set_downloads( [ $dl ] );
                $product->set_download_limit( -1 );
                $product->set_download_expiry( -1 );
            } catch ( \Exception $e ) {
                // File URL stored in _ae_file_url meta; WC download attachment skipped
                error_log( '[aria-api] set_downloads skipped: ' . $e->getMessage() );
            }
        }

        $all_tags = array_merge( $tags, [ 'aria-generated' ] );
        $tag_ids  = [];
        foreach ( $all_tags as $tn ) {
            $t = get_term_by( 'name', $tn, 'product_tag' );
            if ( ! $t ) {
                $r = wp_insert_term( $tn, 'product_tag' );
                $tag_ids[] = is_array( $r ) ? $r['term_id'] : 0;
            } else {
                $tag_ids[] = $t->term_id;
            }
        }
        $product->set_tag_ids( array_filter( $tag_ids ) );

        $cat = get_term_by( 'name', $category, 'product_cat' );
        if ( ! $cat ) {
            $r = wp_insert_term( $category, 'product_cat' );
            $cat_id = is_array( $r ) ? $r['term_id'] : 0;
        } else {
            $cat_id = $cat->term_id;
        }
        if ( $cat_id ) $product->set_category_ids( [ $cat_id ] );

        $product_id = $product->save();
        if ( ! $product_id ) {
            return new WP_Error( 'save_failed', 'Failed to save WooCommerce product.', [ 'status' => 500 ] );
        }
    } else {
        // ── Path B: No WooCommerce — use ae_product CPT ───────────────────────
        $product_id = wp_insert_post( [
            'post_type'    => 'ae_product',
            'post_title'   => $name,
            'post_content' => $desc,
            'post_excerpt' => $short,
            'post_status'  => 'publish',
        ] );
        if ( is_wp_error( $product_id ) || ! $product_id ) {
            return new WP_Error( 'save_failed', 'Failed to create ae_product post.', [ 'status' => 500 ] );
        }
    }

    // ── Shared post meta (both paths) ─────────────────────────────────────────
    update_post_meta( $product_id, '_aria_created',    1 );
    update_post_meta( $product_id, '_aria_created_at', time() );
    update_post_meta( $product_id, '_ae_price',        $price );
    update_post_meta( $product_id, '_ae_file_url',     $file_url );
    update_post_meta( $product_id, '_ae_file_name',    $file_name );
    update_post_meta( $product_id, '_ae_category',     $category );
    update_post_meta( $product_id, '_ae_tags',         implode( ',', $tags ) );

    // Cover image (shared helper handles both WC and CPT paths)
    ae_aria_attach_cover( $product_id, $cover_b64, $cover_file, $name );

    return new WP_REST_Response( [
        'ok'         => true,
        'product_id' => $product_id,
        'url'        => get_permalink( $product_id ),
        'cart_url'   => ( class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '' ),
    ], 201 );
}

/** Aria uploads a PDF report; saves to wp-content/uploads/aria-reports/ */
function ae_aria_upload_report( WP_REST_Request $req ) {
    $body     = $req->get_json_params();
    $filename = sanitize_file_name( $body['filename'] ?? 'report.pdf' );
    $b64      = $body['data'] ?? '';

    if ( empty( $b64 ) ) {
        return new WP_Error( 'no_data', 'No PDF data provided.', [ 'status' => 422 ] );
    }

    $pdf_bytes = base64_decode( $b64 );
    if ( $pdf_bytes === false || strlen( $pdf_bytes ) < 100 ) {
        return new WP_Error( 'bad_data', 'Invalid base64 PDF.', [ 'status' => 422 ] );
    }

    $upload_dir  = wp_upload_dir();
    $reports_dir = $upload_dir['basedir'] . '/aria-reports';
    if ( ! file_exists( $reports_dir ) ) {
        wp_mkdir_p( $reports_dir );
    }

    $filepath = $reports_dir . '/' . $filename;
    $written  = file_put_contents( $filepath, $pdf_bytes );
    if ( $written === false ) {
        return new WP_Error( 'write_fail', 'Could not write PDF to disk.', [ 'status' => 500 ] );
    }

    $url = $upload_dir['baseurl'] . '/aria-reports/' . $filename;
    return new WP_REST_Response( [ 'ok' => true, 'url' => $url, 'size' => $written ], 200 );
}

/** List all Aria-created products — WooCommerce if active, ae_product CPT otherwise */
function ae_aria_list_assets() {
    $products = [];

    if ( class_exists( 'WooCommerce' ) ) {
        // ── Path A: WooCommerce ───────────────────────────────────────────────
        $query = new WP_Query( [
            'post_type'      => 'product',
            'posts_per_page' => 60,
            'post_status'    => 'publish',
            'meta_key'       => '_aria_created',
            'meta_value'     => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        foreach ( $query->posts as $post ) {
            $p = wc_get_product( $post->ID );
            if ( ! $p ) continue;
            $products[] = [
                'id'          => $post->ID,
                'title'       => $p->get_name(),
                'name'        => $p->get_name(),
                'description' => $p->get_short_description() ?: wp_trim_words( wp_strip_all_tags( $p->get_description() ), 25 ),
                'price'       => $p->get_price(),
                'price_html'  => $p->get_price_html(),
                'url'         => get_permalink( $post->ID ),
                'add_to_cart' => $p->add_to_cart_url(),
                'image'       => get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '',
                'created_at'  => (int) get_post_meta( $post->ID, '_aria_created_at', true ),
                'tags'        => wp_get_post_terms( $post->ID, 'product_tag', [ 'fields' => 'names' ] ),
                'category'    => get_post_meta( $post->ID, '_ae_category', true )
                                 ?: implode( ', ', wp_get_post_terms( $post->ID, 'product_cat', [ 'fields' => 'names' ] ) ),
                'file_url'    => get_post_meta( $post->ID, '_ae_file_url', true ),
            ];
        }
    } else {
        // ── Path B: ae_product CPT ────────────────────────────────────────────
        $query = new WP_Query( [
            'post_type'      => 'ae_product',
            'posts_per_page' => 60,
            'post_status'    => 'publish',
            'meta_key'       => '_aria_created',
            'meta_value'     => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        foreach ( $query->posts as $post ) {
            $price    = get_post_meta( $post->ID, '_ae_price',    true );
            $file_url = get_post_meta( $post->ID, '_ae_file_url', true );
            $category = get_post_meta( $post->ID, '_ae_category', true );
            $tags_raw = get_post_meta( $post->ID, '_ae_tags',     true );
            $products[] = [
                'id'          => $post->ID,
                'title'       => $post->post_title,
                'name'        => $post->post_title,
                'description' => $post->post_excerpt ?: wp_trim_words( wp_strip_all_tags( $post->post_content ), 25 ),
                'price'       => $price,
                'price_html'  => '$' . esc_html( $price ),
                'url'         => get_permalink( $post->ID ),
                'add_to_cart' => '',
                'image'       => get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '',
                'created_at'  => (int) get_post_meta( $post->ID, '_aria_created_at', true ),
                'tags'        => $tags_raw ? explode( ',', $tags_raw ) : [],
                'category'    => $category,
                'file_url'    => $file_url,
            ];
        }
    }

    return new WP_REST_Response( [ 'products' => $products, 'count' => count( $products ) ], 200 );
}
