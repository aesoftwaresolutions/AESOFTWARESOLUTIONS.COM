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

        add_option( 'aesoftwares_aria_push_token', 'aria-push-ea5c53c9a54e46ec6dfe5924b6567cab', '', 'no' );
        add_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788', '', 'no' );
        add_option( 'aesoftwares_bb_host', '100.112.238.20:1234', '', 'no' );
        add_option( 'aesoftwares_bb_password', 'Leslee1126!', '', 'no' );
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

// REST API: GET /aesoftwares/v1/aria/reports (public)
// Lists PDFs from wp-content/uploads/aria-reports/
register_rest_route(
    'aesoftwares/v1',
    '/aria/reports',
    [
        'methods'             => 'GET',
        'callback'            => function() {
            $upload_dir  = wp_upload_dir();
            $reports_dir = $upload_dir['basedir'] . '/aria-reports/';
            $reports_url = $upload_dir['baseurl'] . '/aria-reports/';

            if ( ! is_dir( $reports_dir ) ) {
                return new \WP_REST_Response( [], 200 );
            }

            $files = glob( $reports_dir . '*.pdf' );
            if ( ! $files ) {
                return new \WP_REST_Response( [], 200 );
            }

            usort( $files, function( $a, $b ) {
                return filemtime( $b ) - filemtime( $a );
            } );

            $reports = array_map(
                function( $file ) use ( $reports_url ) {
                    $basename = basename( $file, '.pdf' );

                    $industry = 'Research';
                    if ( preg_match( '/^ae-(.+?)-\d{6}$/', $basename, $m ) ) {
                        $slug = $m[1];
                        if ( stripos( $slug, 'law' ) !== false || stripos( $slug, 'legal' ) !== false ) {
                            $industry = 'Legal';
                        } elseif ( stripos( $slug, 'home' ) !== false || stripos( $slug, 'service' ) !== false ) {
                            $industry = 'Home Services';
                        } elseif ( stripos( $slug, 'mortgage' ) !== false || stripos( $slug, 'lending' ) !== false ) {
                            $industry = 'Mortgage';
                        } elseif ( stripos( $slug, 'market' ) !== false ) {
                            $industry = 'Market Intel';
                        } elseif ( stripos( $slug, 'content' ) !== false ) {
                            $industry = 'Content Strategy';
                        }
                    }

                    $title = preg_replace( '/-\d{6}$/', '', $basename );
                    $title = preg_replace( '/^ae-/', '', $title );
                    $title = ucwords( str_replace( '-', ' ', $title ) );

                    return [
                        'title'    => $title,
                        'industry' => $industry,
                        'url'      => $reports_url . basename( $file ),
                        'date'     => gmdate( 'F Y', filemtime( $file ) ),
                    ];
                },
                $files
            );

            return new \WP_REST_Response( $reports, 200 );
        },
        'permission_callback' => '__return_true',
    ]
);

// REST API: POST /aesoftwares/v1/aria/report-request (public)
// Emails Anthony + fires a research directive to Aria
register_rest_route(
    'aesoftwares/v1',
    '/aria/report-request',
    [
        'methods'             => 'POST',
        'callback'            => function( \WP_REST_Request $request ) {
            $data     = $request->get_json_params();
            $name     = sanitize_text_field( $data['name'] ?? '' );
            $email    = sanitize_email( $data['email'] ?? '' );
            $business = sanitize_text_field( $data['business'] ?? '' );
            $topic    = sanitize_textarea_field( $data['topic'] ?? '' );

            if ( ! $name || ! $email || ! $business || ! $topic ) {
                return new \WP_REST_Response( [ 'message' => 'All fields are required.' ], 422 );
            }

            if ( ! is_email( $email ) ) {
                return new \WP_REST_Response( [ 'message' => 'Invalid email address.' ], 422 );
            }

            // 1. Email Anthony
            $to      = 'anthony.aesoftwaresolutions@gmail.com';
            $subject = '[AE Research Request] ' . $business . ' — ' . wp_trim_words( $topic, 8, '...' );
            $body    = "New research report request from the website.\n\n";
            $body   .= "Name:     {$name}\n";
            $body   .= "Email:    {$email}\n";
            $body   .= "Business: {$business}\n\n";
            $body   .= "Research topic:\n{$topic}\n\n";
            $body   .= "---\nSent from aesoftwaresolutions.com/aria-marketplace/\n";

            wp_mail( $to, $subject, $body, [
                'Content-Type: text/plain; charset=UTF-8',
                "Reply-To: {$name} <{$email}>",
            ] );

            // 2. Send directive to Aria (non-blocking)
            $vps_url   = get_option( 'aesoftwares_aria_vps_url', 'http://100.92.197.12:8788' );
            $directive = "RESEARCH REQUEST from {$name} ({$email}), {$business}: {$topic}";

            wp_remote_post(
                trailingslashit( $vps_url ) . 'aria/webhook',
                [
                    'body'      => wp_json_encode( [
                        'action' => 'directive',
                        'text'   => $directive,
                    ] ),
                    'headers'   => [ 'Content-Type' => 'application/json' ],
                    'timeout'   => 8,
                    'sslverify' => false,
                    'blocking'  => false,
                ]
            );

            return new \WP_REST_Response( [ 'success' => true ], 200 );
        },
        'permission_callback' => '__return_true',
    ]
);
