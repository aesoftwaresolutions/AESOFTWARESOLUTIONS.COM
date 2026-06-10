<?php
/**
 * Aria REST API
 *
 * POST /wp-json/aesoftwares/v1/aria/push   — Aria pushes heartbeat status (Bearer token)
 * GET  /wp-json/aesoftwares/v1/aria/status — Dashboard reads status (admin-only)
 * POST /wp-json/aesoftwares/v1/aria/assets — Aria creates a WooCommerce product (Bearer token)
 * GET  /wp-json/aesoftwares/v1/aria/assets — List Aria's assets (public)
 */

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

/** Aria creates a WooCommerce digital product */
function ae_aria_create_asset( WP_REST_Request $req ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return new WP_Error( 'no_woo', 'WooCommerce is not active on this site.', [ 'status' => 500 ] );
    }

    $body = $req->get_json_params();

    $name       = sanitize_text_field( $body['name']              ?? '' );
    $desc       = wp_kses_post(         $body['description']       ?? '' );
    $short      = sanitize_textarea_field( $body['short_description'] ?? '' );
    $price      = sanitize_text_field( $body['price']              ?? '0' );
    $file_url   = esc_url_raw(          $body['file_url']          ?? '' );
    $file_name  = sanitize_text_field( $body['file_name']          ?? 'Download' );
    $category   = sanitize_text_field( $body['category']           ?? 'Digital Assets' );
    $extra_tags = is_array( $body['tags'] ?? null ) ? array_map( 'sanitize_text_field', $body['tags'] ) : [];

    if ( empty( $name ) ) {
        return new WP_Error( 'missing_name', 'Product name is required.', [ 'status' => 422 ] );
    }

    $product = new WC_Product_Simple();
    $product->set_name( $name );
    $product->set_description( $desc );
    $product->set_short_description( $short );
    $product->set_regular_price( $price );
    $product->set_downloadable( true );
    $product->set_virtual( true );
    $product->set_status( 'publish' );

    if ( ! empty( $file_url ) ) {
        $dl = new WC_Product_Download();
        $dl->set_name( $file_name );
        $dl->set_file( $file_url );
        $dl->set_id( md5( $file_url ) );
        $product->set_downloads( [ $dl ] );
        $product->set_download_limit( -1 );
        $product->set_download_expiry( -1 );
    }

    // Tags: always add aria-generated
    $all_tags = array_merge( $extra_tags, [ 'aria-generated' ] );
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

    // Category
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
        return new WP_Error( 'save_failed', 'Failed to save the product.', [ 'status' => 500 ] );
    }

    update_post_meta( $product_id, '_aria_created',    1 );
    update_post_meta( $product_id, '_aria_created_at', time() );

    // Optional: attach a cover image if provided as base64 PNG
    $cover_b64 = $body['cover_image_b64'] ?? '';
    $cover_filename = sanitize_file_name( $body['cover_filename'] ?? 'cover.png' );
    if ( ! empty( $cover_b64 ) ) {
        $image_bytes = base64_decode( $cover_b64 );
        if ( $image_bytes && strlen( $image_bytes ) > 100 ) {
            $upload_dir  = wp_upload_dir();
            $covers_dir  = $upload_dir['basedir'] . '/aria-covers';
            if ( ! file_exists( $covers_dir ) ) wp_mkdir_p( $covers_dir );
            $cover_path = $covers_dir . '/' . $cover_filename;
            file_put_contents( $cover_path, $image_bytes );

            // Register as WP attachment and set as product thumbnail
            $attach_id = wp_insert_attachment( [
                'guid'           => $upload_dir['baseurl'] . '/aria-covers/' . $cover_filename,
                'post_mime_type' => 'image/png',
                'post_title'     => sanitize_text_field( $name . ' Cover' ),
                'post_status'    => 'inherit',
            ], $cover_path, $product_id );

            if ( $attach_id && ! is_wp_error( $attach_id ) ) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                $attach_data = wp_generate_attachment_metadata( $attach_id, $cover_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $product_id, $attach_id );
            }
        }
    }

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

/** List all Aria-created products */
function ae_aria_list_assets() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return new WP_REST_Response( [ 'products' => [], 'count' => 0 ], 200 );
    }

    $query = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => 60,
        'post_status'    => 'publish',
        'meta_key'       => '_aria_created',
        'meta_value'     => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );

    $products = [];
    foreach ( $query->posts as $post ) {
        $p = wc_get_product( $post->ID );
        if ( ! $p ) continue;
        $products[] = [
            'id'          => $post->ID,
            'name'        => $p->get_name(),
            'description' => $p->get_short_description() ?: wp_trim_words( wp_strip_all_tags( $p->get_description() ), 25 ),
            'price'       => $p->get_price(),
            'price_html'  => $p->get_price_html(),
            'url'         => get_permalink( $post->ID ),
            'add_to_cart' => $p->add_to_cart_url(),
            'image'       => get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '',
            'created_at'  => (int) get_post_meta( $post->ID, '_aria_created_at', true ),
            'tags'        => wp_get_post_terms( $post->ID, 'product_tag', [ 'fields' => 'names' ] ),
            'category'    => wp_get_post_terms( $post->ID, 'product_cat', [ 'fields' => 'names' ] ),
        ];
    }

    return new WP_REST_Response( [ 'products' => $products, 'count' => count( $products ) ], 200 );
}
