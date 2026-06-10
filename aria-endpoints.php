<?php
/**
 * Aria autonomous agent REST endpoints and settings
 *
 * @package AESoftwaresTheme
 */

// Register new settings
add_action(
    'init',
    function() {
        register_setting(
            'aesoftwares_settings',
            'aesoftwares_aria_push_token',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest'      => false,
            ]
        );

        register_setting(
            'aesoftwares_settings',
            'aesoftwares_aria_vps_url',
            [
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'show_in_rest'      => false,
                'default'           => 'http://100.92.197.12:8788',
            ]
        );

        register_setting(
            'aesoftwares_settings',
            'aesoftwares_bb_host',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest'      => false,
            ]
        );

        register_setting(
            'aesoftwares_settings',
            'aesoftwares_bb_password',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest'      => false,
            ]
        );
    },
    10
);

// Add settings to AE Settings admin page
add_action(
    'aesoftwares_settings_fields',
    function() {
        $aria_push_token = get_option( 'aesoftwares_aria_push_token', '' );
        $aria_vps_url    = get_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788' );
        $bb_host         = get_option( 'aesoftwares_bb_host', '' );
        $bb_password     = get_option( 'aesoftwares_bb_password', '' );
        ?>
        <tr>
            <th scope="row"><label for="aria-push-token">Aria Push Token</label></th>
            <td>
                <input type="password" id="aria-push-token" name="aesoftwares_aria_push_token" value="<?php echo esc_attr( $aria_push_token ); ?>" class="large-text">
                <p class="description">Secret token Aria uses to push status updates</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="aria-vps-url">Aria VPS URL</label></th>
            <td>
                <input type="url" id="aria-vps-url" name="aesoftwares_aria_vps_url" value="<?php echo esc_attr( $aria_vps_url ); ?>" class="large-text">
                <p class="description">VPS API base URL for Aria webhook and file access</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bb-host">BlueBubbles Host</label></th>
            <td>
                <input type="text" id="bb-host" name="aesoftwares_bb_host" value="<?php echo esc_attr( $bb_host ); ?>" class="large-text">
                <p class="description">BlueBubbles server hostname or IP</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bb-password">BlueBubbles Password</label></th>
            <td>
                <input type="password" id="bb-password" name="aesoftwares_bb_password" value="<?php echo esc_attr( $bb_password ); ?>" class="large-text">
                <p class="description">BlueBubbles server password</p>
            </td>
        </tr>
        <?php
    }
);

// REST API: GET /aesoftwares/v1/aria/status
register_rest_route(
    'aesoftwares/v1',
    '/aria/status',
    [
        'methods'             => 'GET',
        'callback'            => function( \WP_REST_Request $request ) {
            $status = get_transient( 'aria_status' );
            if ( ! $status ) {
                $status = [
                    'status'           => 'unknown',
                    'last_heartbeat'   => null,
                    'current_task'     => null,
                    'eval_score'       => 0,
                ];
            }
            return new \WP_REST_Response( $status, 200 );
        },
        'permission_callback' => '__return_true',
    ]
);

// REST API: POST /aesoftwares/v1/aria/status (Aria pushes here)
register_rest_route(
    'aesoftwares/v1',
    '/aria/status',
    [
        'methods'             => 'POST',
        'callback'            => function( \WP_REST_Request $request ) {
            $push_token = $request->get_header( 'Authorization' );
            $stored_token = get_option( 'aesoftwares_aria_push_token', '' );

            // Validate bearer token
            if ( ! $stored_token || ! $push_token || 'Bearer ' . $stored_token !== $push_token ) {
                return new \WP_REST_Response(
                    [ 'error' => 'Invalid push token' ],
                    401
                );
            }

            $data = $request->get_json_params();
            set_transient( 'aria_status', $data, HOUR_IN_SECONDS );

            return new \WP_REST_Response( [ 'success' => true ], 200 );
        },
        'permission_callback' => '__return_true',
    ]
);

// REST API: GET /aesoftwares/v1/aria/tasks
register_rest_route(
    'aesoftwares/v1',
    '/aria/tasks',
    [
        'methods'             => 'GET',
        'callback'            => function( \WP_REST_Request $request ) {
            $vps_url = get_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788' );

            // Try to fetch TASKS.md from VPS
            $response = wp_remote_get( $vps_url . '/aria/tasks', [
                'timeout'   => 10,
                'sslverify' => false,
            ] );

            if ( is_wp_error( $response ) ) {
                // Fall back to cached version
                $cached = get_transient( 'aria_tasks_cache' );
                return new \WP_REST_Response( $cached ?: '', 200 );
            }

            $body = wp_remote_retrieve_body( $response );
            set_transient( 'aria_tasks_cache', $body, HOUR_IN_SECONDS );

            return new \WP_REST_Response( $body, 200 );
        },
        'permission_callback' => '__return_true',
    ]
);

// REST API: GET /aesoftwares/v1/aria/products
register_rest_route(
    'aesoftwares/v1',
    '/aria/products',
    [
        'methods'             => 'GET',
        'callback'            => function( \WP_REST_Request $request ) {
            $vps_url = get_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788' );

            // Try to fetch products from VPS
            $response = wp_remote_get( $vps_url . '/aria/products', [
                'timeout'   => 10,
                'sslverify' => false,
            ] );

            if ( is_wp_error( $response ) ) {
                // Fall back to cached version
                $cached = get_transient( 'aria_products_cache' );
                return new \WP_REST_Response( $cached ?: [], 200 );
            }

            $body = wp_remote_retrieve_body( $response );
            $products = json_decode( $body, true );

            if ( ! is_array( $products ) ) {
                $products = [];
            }

            set_transient( 'aria_products_cache', $products, HOUR_IN_SECONDS );

            return new \WP_REST_Response( $products, 200 );
        },
        'permission_callback' => '__return_true',
    ]
);

// REST API: POST /aesoftwares/v1/aria/control (admin only)
register_rest_route(
    'aesoftwares/v1',
    '/aria/control',
    [
        'methods'             => 'POST',
        'callback'            => function( \WP_REST_Request $request ) {
            if ( ! current_user_can( 'manage_options' ) ) {
                return new \WP_REST_Response(
                    [ 'error' => 'Insufficient permissions' ],
                    403
                );
            }

            $data = $request->get_json_params();
            $action = $data['action'] ?? null;
            $text = $data['text'] ?? null;

            if ( ! $action ) {
                return new \WP_REST_Response(
                    [ 'error' => 'Missing action parameter' ],
                    400
                );
            }

            $vps_url = get_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788' );
            $vps_url = trailingslashit( $vps_url ) . 'aria/webhook';

            $payload = [
                'action' => $action,
                'text'   => $text,
            ];

            $response = wp_remote_post( $vps_url, [
                'body'      => wp_json_encode( $payload ),
                'headers'   => [
                    'Content-Type' => 'application/json',
                ],
                'timeout'   => 10,
                'sslverify' => false,
            ] );

            if ( is_wp_error( $response ) ) {
                return new \WP_REST_Response(
                    [ 'error' => $response->get_error_message() ],
                    500
                );
            }

            return new \WP_REST_Response( [ 'success' => true ], 200 );
        },
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
    ]
);

// REST API: GET /aesoftwares/v1/aria/messages (admin only)
register_rest_route(
    'aesoftwares/v1',
    '/aria/messages',
    [
        'methods'             => 'GET',
        'callback'            => function( \WP_REST_Request $request ) {
            if ( ! current_user_can( 'manage_options' ) ) {
                return new \WP_REST_Response(
                    [ 'error' => 'Insufficient permissions' ],
                    403
                );
            }

            $bb_host     = get_option( 'aesoftwares_bb_host', '' );
            $bb_password = get_option( 'aesoftwares_bb_password', '' );

            if ( ! $bb_host || ! $bb_password ) {
                return new \WP_REST_Response( [], 200 );
            }

            // Fetch recent iMessages from BlueBubbles
            $bb_url = 'http://' . $bb_host . '/api/v1/messages?limit=10&sort=date&order=desc';

            $response = wp_remote_get( $bb_url, [
                'headers'   => [
                    'Authorization' => 'Bearer ' . $bb_password,
                ],
                'timeout'   => 10,
                'sslverify' => false,
            ] );

            if ( is_wp_error( $response ) ) {
                return new \WP_REST_Response( [], 200 );
            }

            $body = wp_remote_retrieve_body( $response );
            $messages = json_decode( $body, true );

            if ( ! is_array( $messages ) || ! isset( $messages['data'] ) ) {
                return new \WP_REST_Response( [], 200 );
            }

            // Format for frontend
            $formatted = array_map(
                function( $msg ) {
                    return [
                        'timestamp' => $msg['date'] ?? null,
                        'direction' => isset( $msg['is_from_me'] ) && $msg['is_from_me'] ? 'from_anthony' : 'from_aria',
                        'text'      => $msg['text'] ?? '',
                    ];
                },
                $messages['data']
            );

            return new \WP_REST_Response( $formatted, 200 );
        },
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
    ]
);
