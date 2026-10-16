<?php
/**
 * Guides newsletter signup.
 *
 * Handles the popup on the Guides page (page-guides.php). The browser posts
 * name, company and email to admin-ajax.php with action=ae_guides_signup.
 * Each valid signup is appended to uploads/guides-signups/signups.csv and
 * emailed to Anthony.
 *
 * @package AE_Softwares
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AE_GUIDES_NOTIFY_TO', 'anthony@aesoftwaresolutions.com' );
define( 'AE_GUIDES_MAIL_FROM', 'wordpress@aesoftwaresolutions.com' );
define( 'AE_GUIDES_RATE_LIMIT', 5 );                 // signups allowed ...
define( 'AE_GUIDES_RATE_WINDOW', HOUR_IN_SECONDS );  // ... per IP per hour
define( 'AE_GUIDES_NONCE_ACTION', 'ae_guides_signup' );
define( 'AE_GUIDES_HONEYPOT_FIELD', 'ae_extra_info' );

add_action( 'wp_ajax_ae_guides_signup', 'ae_guides_handle_signup' );
add_action( 'wp_ajax_nopriv_ae_guides_signup', 'ae_guides_handle_signup' );

/**
 * Send the JSON reply and stop. Always HTTP 200, so the front end reads the
 * success flag and nothing else.
 */
function ae_guides_reply( $success, $message = '', $extra = array() ) {
	$payload = array( 'success' => (bool) $success );
	if ( ! $success ) {
		$payload['message'] = $message;
	}
	wp_send_json( array_merge( $payload, $extra ) );
}

function ae_guides_client_ip() {
	if ( function_exists( 'aesoftwares_client_ip' ) ) {
		return aesoftwares_client_ip();
	}
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? wp_unslash( $_SERVER['REMOTE_ADDR'] ) : '';
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	return $ip ? $ip : '0.0.0.0';
}

function ae_guides_strlen( $value ) {
	return function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );
}

/**
 * Fixed window rate limit kept in a transient: array( count, window_end ).
 */
function ae_guides_rate_key( $ip ) {
	return 'ae_guides_rl_' . md5( $ip );
}

function ae_guides_rate_limited( $ip ) {
	$state = get_transient( ae_guides_rate_key( $ip ) );
	return is_array( $state ) && (int) $state['count'] >= AE_GUIDES_RATE_LIMIT && (int) $state['end'] > time();
}

function ae_guides_rate_record( $ip ) {
	$key   = ae_guides_rate_key( $ip );
	$state = get_transient( $key );
	if ( ! is_array( $state ) || (int) $state['end'] <= time() ) {
		$state = array( 'count' => 0, 'end' => time() + AE_GUIDES_RATE_WINDOW );
	}
	$state['count'] = (int) $state['count'] + 1;
	set_transient( $key, $state, max( 1, (int) $state['end'] - time() ) );
}

/**
 * Stop spreadsheet programs from running a cell as a formula.
 */
function ae_guides_csv_safe( $value ) {
	$value = (string) $value;
	if ( '' !== $value && false !== strpos( "=+-@\t\r", $value[0] ) ) {
		return "'" . $value;
	}
	return $value;
}

/**
 * Keep the signups folder from being served over HTTP. Apache and LiteSpeed
 * read .htaccess; nginx ignores it (see the note in the staging summary).
 */
function ae_guides_protect_dir( $dir ) {
	$htaccess = $dir . '/.htaccess';
	if ( ! file_exists( $htaccess ) ) {
		$rules  = "# Signup data. Never serve over HTTP.\n";
		$rules .= "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n";
		$rules .= "<IfModule !mod_authz_core.c>\nOrder allow,deny\nDeny from all\n</IfModule>\n";
		file_put_contents( $htaccess, $rules );
	}
	$index = $dir . '/index.php';
	if ( ! file_exists( $index ) ) {
		file_put_contents( $index, "<?php\n// Silence is golden.\n" );
	}
}

/**
 * Append one signup row to uploads/guides-signups/signups.csv, creating the
 * folder and the header row when missing. Returns true on success.
 */
function ae_guides_store_signup( $name, $company, $email, $timestamp, $ip ) {
	$upload = wp_upload_dir( null, false );
	if ( ! empty( $upload['error'] ) ) {
		return false;
	}
	$dir = trailingslashit( $upload['basedir'] ) . 'guides-signups';
	if ( ! wp_mkdir_p( $dir ) ) {
		return false;
	}
	ae_guides_protect_dir( $dir );

	$handle = fopen( $dir . '/signups.csv', 'ab' );
	if ( ! $handle ) {
		return false;
	}

	$ok = false;
	if ( flock( $handle, LOCK_EX ) ) {
		$stat = fstat( $handle );
		if ( $stat && 0 === (int) $stat['size'] ) {
			fputcsv( $handle, array( 'timestamp', 'name', 'company', 'email', 'ip' ), ',', '"', '' );
		}
		$ok = false !== fputcsv(
			$handle,
			array(
				ae_guides_csv_safe( $timestamp ),
				ae_guides_csv_safe( $name ),
				ae_guides_csv_safe( $company ),
				ae_guides_csv_safe( $email ),
				ae_guides_csv_safe( $ip ),
			),
			',',
			'"',
			''
		);
		fflush( $handle );
		flock( $handle, LOCK_UN );
	}
	fclose( $handle );
	return $ok;
}

function ae_guides_notify( $name, $company, $email, $timestamp ) {
	$body  = "Name: {$name}\n";
	$body .= "Company: {$company}\n";
	$body .= "Email: {$email}\n";
	$body .= "Time: {$timestamp}\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: AE Software Solutions <' . AE_GUIDES_MAIL_FROM . '>',
		'Reply-To: ' . $email,
	);

	return wp_mail( AE_GUIDES_NOTIFY_TO, 'New guides signup', $body, $headers );
}

function ae_guides_handle_signup() {
	if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
		ae_guides_reply( false, 'Something went wrong. Please try again.' );
	}

	// Honeypot. Real visitors never see this field. Bots that fill it get a
	// fake success so they learn nothing, and nothing is stored or sent.
	$trap = isset( $_POST[ AE_GUIDES_HONEYPOT_FIELD ] ) ? trim( (string) wp_unslash( $_POST[ AE_GUIDES_HONEYPOT_FIELD ] ) ) : '';
	if ( '' !== $trap ) {
		ae_guides_reply( true );
	}

	// Nonce. A cached copy of the page can carry an old one, so a failure
	// hands back a fresh nonce and the page retries once.
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, AE_GUIDES_NONCE_ACTION ) ) {
		ae_guides_reply(
			false,
			'This page was open for a while. Please try again.',
			array( 'nonce' => wp_create_nonce( AE_GUIDES_NONCE_ACTION ) )
		);
	}

	$ip = ae_guides_client_ip();
	if ( ae_guides_rate_limited( $ip ) ) {
		ae_guides_reply( false, 'Too many signups from your connection. Please try again in an hour.' );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( '' === $name ) {
		ae_guides_reply( false, 'Please enter your name.' );
	}
	if ( '' === $company ) {
		ae_guides_reply( false, 'Please enter your company.' );
	}
	if ( '' === $email || ! is_email( $email ) ) {
		ae_guides_reply( false, 'Please enter a valid email address.' );
	}
	if ( ae_guides_strlen( $name ) > 100 || ae_guides_strlen( $company ) > 150 ) {
		ae_guides_reply( false, 'That name or company is too long. Please shorten it.' );
	}

	ae_guides_rate_record( $ip );

	$timestamp = wp_date( 'Y-m-d H:i:s T' );

	$stored = ae_guides_store_signup( $name, $company, $email, $timestamp, $ip );
	if ( ! $stored ) {
		error_log( 'ae_guides_signup: could not write signups.csv' );
	}

	$mailed = ae_guides_notify( $name, $company, $email, $timestamp );
	if ( ! $mailed ) {
		error_log( 'ae_guides_signup: wp_mail failed for a new signup' );
	}

	// Lose the signup only if both the file and the email failed.
	if ( ! $stored && ! $mailed ) {
		ae_guides_reply( false, 'Something went wrong on my end. Please try again, or email me at ' . AE_GUIDES_NOTIFY_TO . '.' );
	}

	ae_guides_reply( true );
}
