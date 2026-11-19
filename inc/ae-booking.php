<?php
/**
 * AE Software Solutions: custom booking flow (Google Calendar, OAuth 2.0).
 *
 * Load from functions.php:  require_once get_template_directory() . '/inc/ae-booking.php';
 *
 * What lives here
 *  - Rewrite rule for https://aesoftwaresolutions.com/book-a-call/oauth-callback (no extra page).
 *  - One time owner authorization (admin only start, state checked against a transient on return).
 *  - Server side token handling (refresh token in a 600 file, access token in a transient).
 *  - AJAX: ae_booking_slots (freebusy, free 30 minute starts) and ae_booking_create (recheck + insert).
 *
 * Secrets: the OAuth client JSON and the refresh token live OUTSIDE the web root, in
 * dirname( ABSPATH ) . '/.ae-secrets/'. Nothing secret is ever written into the theme, echoed,
 * or logged. Only plain WordPress HTTP API calls (wp_remote_get / wp_remote_post), no libraries.
 *
 * LAUNCH REQUIREMENT (not code): the Google consent screen "AE Software Solutions Booking" is in
 * Testing mode. Refresh tokens issued to a Testing app EXPIRE AFTER 7 DAYS, so bookings would
 * silently start failing a week after authorizing. The app must be PUBLISHED before launch, and
 * the owner authorization below re run once after publishing.
 *
 * @package AE_Softwares
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const AE_BOOKING_CALENDAR     = 'anthony.aesoftwaresolutions@gmail.com';
const AE_BOOKING_TZ           = 'America/New_York';
const AE_BOOKING_REDIRECT_URI = 'https://aesoftwaresolutions.com/book-a-call/oauth-callback';
const AE_BOOKING_SCOPES       = 'https://www.googleapis.com/auth/calendar.events https://www.googleapis.com/auth/calendar.readonly';
const AE_BOOKING_SLOT_MINUTES = 30;
const AE_BOOKING_DAYS_AHEAD   = 60;
const AE_BOOKING_LEAD_HOURS   = 4;
const AE_BOOKING_EVENT_TITLE  = 'Discovery Call with AE Software Solutions';
const AE_BOOKING_EVENT_DESC   = 'A free 30 minute call to talk through your business and what AE Software Solutions can do for you.';

/* ------------------------------------------------------------------------- *
 * Paths and secrets (read only from outside the web root)
 * ------------------------------------------------------------------------- */

function ae_booking_secrets_dir() {
	return dirname( ABSPATH ) . '/.ae-secrets';
}

function ae_booking_client_path() {
	return ae_booking_secrets_dir() . '/oauth-client.json';
}

function ae_booking_token_path() {
	return ae_booking_secrets_dir() . '/oauth-refresh-token.json';
}

/** Returns [ client_id, client_secret ] or null. Accepts Google's { "web": { ... } } download format. */
function ae_booking_client() {
	$path = ae_booking_client_path();
	if ( ! is_readable( $path ) ) {
		return null;
	}
	$json = json_decode( (string) file_get_contents( $path ), true );
	if ( ! is_array( $json ) ) {
		return null;
	}
	$c = isset( $json['web'] ) && is_array( $json['web'] ) ? $json['web'] : $json;
	if ( empty( $c['client_id'] ) || empty( $c['client_secret'] ) ) {
		return null;
	}
	return [ (string) $c['client_id'], (string) $c['client_secret'] ];
}

function ae_booking_refresh_token() {
	$path = ae_booking_token_path();
	if ( ! is_readable( $path ) ) {
		return '';
	}
	$json = json_decode( (string) file_get_contents( $path ), true );
	return is_array( $json ) && ! empty( $json['refresh_token'] ) ? (string) $json['refresh_token'] : '';
}

/** Writes the refresh token file with permissions 600 (atomic: temp file, chmod, rename). */
function ae_booking_store_refresh_token( $refresh_token, $scope ) {
	$dir = ae_booking_secrets_dir();
	if ( ! is_dir( $dir ) || ! is_writable( $dir ) ) {
		return false;
	}
	$path = ae_booking_token_path();
	$tmp  = $path . '.tmp';
	$old  = umask( 0077 );
	$ok   = false !== file_put_contents(
		$tmp,
		wp_json_encode( [ 'refresh_token' => $refresh_token, 'scope' => $scope, 'saved' => gmdate( 'c' ) ] ),
		LOCK_EX
	);
	umask( $old );
	if ( ! $ok ) {
		return false;
	}
	chmod( $tmp, 0600 );
	return rename( $tmp, $path );
}

/* ------------------------------------------------------------------------- *
 * OAuth: rewrite rule, owner start, callback
 * ------------------------------------------------------------------------- */

add_action( 'init', function () {
	add_rewrite_rule( '^book-a-call/oauth-callback/?$', 'index.php?ae_oauth=1', 'top' );
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'ae_oauth';
	return $vars;
} );

// Flush rules once when this file first ships (no manual Permalinks visit needed).
add_action( 'init', function () {
	if ( '1' !== get_option( 'ae_booking_rewrite_v' ) ) {
		flush_rewrite_rules( false );
		update_option( 'ae_booking_rewrite_v', '1' );
	}
}, 20 );

// Do not let canonical redirects rewrite the callback URL.
add_filter( 'redirect_canonical', function ( $url ) {
	return get_query_var( 'ae_oauth' ) ? false : $url;
} );

function ae_booking_plain_page( $title, $message, $status = 200 ) {
	nocache_headers();
	wp_die( esc_html( $message ), esc_html( $title ), [ 'response' => $status ] );
}

add_action( 'template_redirect', function () {
	if ( ! get_query_var( 'ae_oauth' ) ) {
		return;
	}
	// Only the site owner may start or finish the one time authorization.
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		ae_booking_plain_page( 'Sign in required', 'Sign in to the WordPress admin as the site owner, then open this address again.', 403 );
	}

	$client = ae_booking_client();
	if ( ! $client ) {
		ae_booking_plain_page( 'Client file missing', 'The Google client file was not found on the server.', 500 );
	}

	// Step 1: owner opens .../oauth-callback?start=1 and is sent to Google with a fresh state.
	if ( isset( $_GET['start'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$state = bin2hex( random_bytes( 16 ) );
		set_transient( 'ae_booking_oauth_state', $state, 10 * MINUTE_IN_SECONDS );
		$url = add_query_arg(
			[
				'client_id'     => $client[0],
				'redirect_uri'  => AE_BOOKING_REDIRECT_URI,
				'response_type' => 'code',
				'scope'         => AE_BOOKING_SCOPES,
				'access_type'   => 'offline',
				'prompt'        => 'consent',
				'state'         => $state,
			],
			'https://accounts.google.com/o/oauth2/v2/auth'
		);
		nocache_headers();
		wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect
		exit;
	}

	// Step 2: Google sends the owner back here with ?code=...&state=...
	$expected = get_transient( 'ae_booking_oauth_state' );
	delete_transient( 'ae_booking_oauth_state' ); // single use
	$state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! is_string( $expected ) || '' === $expected || '' === $state || ! hash_equals( $expected, $state ) ) {
		ae_booking_plain_page( 'Authorization stopped', 'The state value did not match, so nothing was stored. Start again from the beginning.', 400 );
	}
	if ( isset( $_GET['error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		ae_booking_plain_page( 'Authorization declined', 'Google did not grant access, so nothing was stored.', 400 );
	}
	$code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( '' === $code ) {
		ae_booking_plain_page( 'Authorization stopped', 'No authorization code came back.', 400 );
	}

	$resp = wp_remote_post( 'https://oauth2.googleapis.com/token', [
		'timeout' => 20,
		'body'    => [
			'code'          => $code,
			'client_id'     => $client[0],
			'client_secret' => $client[1],
			'redirect_uri'  => AE_BOOKING_REDIRECT_URI,
			'grant_type'    => 'authorization_code',
		],
	] );
	$data = is_wp_error( $resp ) ? null : json_decode( wp_remote_retrieve_body( $resp ), true );
	if ( ! is_array( $data ) || empty( $data['refresh_token'] ) ) {
		ae_booking_plain_page( 'No refresh token', 'Google did not return a refresh token. Remove the app from the Google account permissions page, then start again.', 502 );
	}
	$scope = isset( $data['scope'] ) ? (string) $data['scope'] : '';
	if ( false === strpos( $scope, 'calendar.events' ) ) {
		ae_booking_plain_page( 'Missing permission', 'The calendar events permission was not granted. Nothing was stored.', 400 );
	}
	if ( ! ae_booking_store_refresh_token( (string) $data['refresh_token'], $scope ) ) {
		ae_booking_plain_page( 'Could not save', 'The token could not be saved on the server. Check that the secrets folder is writable.', 500 );
	}
	if ( ! empty( $data['access_token'] ) ) {
		set_transient( 'ae_booking_access_token', (string) $data['access_token'], max( 60, (int) ( $data['expires_in'] ?? 3600 ) - 120 ) );
	}
	ae_booking_plain_page( 'Calendar connected', 'Google Calendar is connected for booking. You can close this tab.' );
} );

/* ------------------------------------------------------------------------- *
 * Access token (server side refresh, cached in a transient)
 * ------------------------------------------------------------------------- */

/** @return string|WP_Error */
function ae_booking_access_token() {
	$cached = get_transient( 'ae_booking_access_token' );
	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}
	$client  = ae_booking_client();
	$refresh = ae_booking_refresh_token();
	if ( ! $client || '' === $refresh ) {
		return new WP_Error( 'ae_not_connected', 'not connected' );
	}
	$resp = wp_remote_post( 'https://oauth2.googleapis.com/token', [
		'timeout' => 15,
		'body'    => [
			'client_id'     => $client[0],
			'client_secret' => $client[1],
			'refresh_token' => $refresh,
			'grant_type'    => 'refresh_token',
		],
	] );
	if ( is_wp_error( $resp ) ) {
		return new WP_Error( 'ae_token_http', 'token request failed' );
	}
	$data = json_decode( wp_remote_retrieve_body( $resp ), true );
	if ( ! is_array( $data ) || empty( $data['access_token'] ) ) {
		// Log only Google's short error code (for example invalid_grant), never bodies or tokens.
		$code = is_array( $data ) && isset( $data['error'] ) ? preg_replace( '/[^a-z_]/', '', (string) $data['error'] ) : 'unknown';
		error_log( 'AE booking: token refresh failed (' . $code . ')' );
		return new WP_Error( 'ae_token_refresh', 'token refresh failed' );
	}
	set_transient( 'ae_booking_access_token', (string) $data['access_token'], max( 60, (int) ( $data['expires_in'] ?? 3600 ) - 120 ) );
	return (string) $data['access_token'];
}

/**
 * Authorized call to the Calendar API. Retries once with a fresh token on 401.
 * @return array|WP_Error  [ status, decoded body ]
 */
function ae_booking_google( $method, $url, $body = null ) {
	for ( $try = 0; $try < 2; $try++ ) {
		$token = ae_booking_access_token();
		if ( is_wp_error( $token ) ) {
			return $token;
		}
		$args = [
			'timeout' => 20,
			'headers' => [ 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json' ],
		];
		if ( 'GET' === $method ) {
			$resp = wp_remote_get( $url, $args );
		} else {
			$args['body'] = wp_json_encode( $body );
			$resp         = wp_remote_post( $url, $args );
		}
		if ( is_wp_error( $resp ) ) {
			return new WP_Error( 'ae_http', 'request failed' );
		}
		$status = (int) wp_remote_retrieve_response_code( $resp );
		if ( 401 === $status && 0 === $try ) {
			delete_transient( 'ae_booking_access_token' );
			continue;
		}
		$decoded = json_decode( wp_remote_retrieve_body( $resp ), true );
		return [ $status, is_array( $decoded ) ? $decoded : [] ];
	}
	return new WP_Error( 'ae_http', 'request failed' );
}

/* ------------------------------------------------------------------------- *
 * Availability rules (all slot math in America/New_York)
 * ------------------------------------------------------------------------- */

function ae_booking_tz() {
	return new DateTimeZone( AE_BOOKING_TZ );
}

/** Open and close hour by ISO weekday (1 = Monday). Weekends are absent, so closed. */
function ae_booking_hours( $iso_dow ) {
	$hours = [
		1 => [ 15, 20 ], // Monday 3:00 to 8:00 pm
		2 => [ 15, 20 ], // Tuesday
		3 => [ 15, 18 ], // Wednesday 3:00 to 6:00 pm
		4 => [ 15, 20 ], // Thursday
		5 => [ 15, 18 ], // Friday
	];
	return $hours[ $iso_dow ] ?? null;
}

/** Parses Y-m-d strictly. Returns midnight in New York, or null. */
function ae_booking_parse_date( $date ) {
	if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		return null;
	}
	$d = DateTimeImmutable::createFromFormat( '!Y-m-d', $date, ae_booking_tz() );
	return ( $d && $d->format( 'Y-m-d' ) === $date ) ? $d : null;
}

/** Every start time the schedule offers that day, before availability and lead time filters. */
function ae_booking_day_starts( DateTimeImmutable $day ) {
	$h = ae_booking_hours( (int) $day->format( 'N' ) );
	if ( ! $h ) {
		return [];
	}
	$out   = [];
	$t     = $day->setTime( $h[0], 0 );
	$close = $day->setTime( $h[1], 0 );
	while ( $t->modify( '+' . AE_BOOKING_SLOT_MINUTES . ' minutes' ) <= $close ) {
		$out[] = $t;
		$t     = $t->modify( '+' . AE_BOOKING_SLOT_MINUTES . ' minutes' );
	}
	return $out;
}

/** Is the day inside the booking window (today through 60 days out)? */
function ae_booking_day_in_window( DateTimeImmutable $day, DateTimeImmutable $now ) {
	$today = $now->setTime( 0, 0 );
	$last  = $today->modify( '+' . AE_BOOKING_DAYS_AHEAD . ' days' );
	return $day >= $today && $day <= $last;
}

function ae_booking_overlaps( DateTimeImmutable $start, array $busy ) {
	$s = $start->getTimestamp();
	$e = $s + AE_BOOKING_SLOT_MINUTES * 60;
	foreach ( $busy as $b ) {
		if ( $b[0] < $e && $b[1] > $s ) {
			return true;
		}
	}
	return false;
}

/**
 * Pure slot filter: lead time plus busy overlap. $busy is a list of [ start_ts, end_ts ].
 * @return DateTimeImmutable[]
 */
function ae_booking_free_starts( DateTimeImmutable $day, array $busy, DateTimeImmutable $now ) {
	$earliest = $now->getTimestamp() + AE_BOOKING_LEAD_HOURS * 3600;
	$free     = [];
	foreach ( ae_booking_day_starts( $day ) as $start ) {
		if ( $start->getTimestamp() >= $earliest && ! ae_booking_overlaps( $start, $busy ) ) {
			$free[] = $start;
		}
	}
	return $free;
}

/**
 * Freebusy for a time range on the business calendar.
 * @return array|WP_Error  list of [ start_ts, end_ts ]
 */
function ae_booking_busy( DateTimeImmutable $from, DateTimeImmutable $to ) {
	$res = ae_booking_google( 'POST', 'https://www.googleapis.com/calendar/v3/freeBusy', [
		'timeMin'  => $from->format( DATE_RFC3339 ),
		'timeMax'  => $to->format( DATE_RFC3339 ),
		'timeZone' => AE_BOOKING_TZ,
		'items'    => [ [ 'id' => AE_BOOKING_CALENDAR ] ],
	] );
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	list( $status, $data ) = $res;
	$cal = $data['calendars'][ AE_BOOKING_CALENDAR ] ?? null;
	if ( 200 !== $status || ! is_array( $cal ) || ! empty( $cal['errors'] ) ) {
		return new WP_Error( 'ae_freebusy', 'freebusy failed' );
	}
	$busy = [];
	foreach ( (array) ( $cal['busy'] ?? [] ) as $b ) {
		$s = strtotime( (string) ( $b['start'] ?? '' ) );
		$e = strtotime( (string) ( $b['end'] ?? '' ) );
		if ( $s && $e ) {
			$busy[] = [ $s, $e ];
		}
	}
	return $busy;
}

/* ------------------------------------------------------------------------- *
 * Helpers: IP rate limit, JSON replies, front end config
 * ------------------------------------------------------------------------- */

function ae_booking_client_ip() {
	// REMOTE_ADDR only: forwarded headers can be forged. If the host puts a CDN in front and every
	// visitor shows the same address, return the CDN's real client IP header here via this filter.
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	return (string) apply_filters( 'ae_booking_client_ip', $ip );
}

/** Fixed window counter in a transient. Returns false once the limit is reached. */
function ae_booking_rate_ok( $bucket, $max, $window ) {
	$key  = 'ae_bk_rl_' . $bucket . '_' . substr( hash_hmac( 'sha256', ae_booking_client_ip(), wp_salt() ), 0, 24 );
	$data = get_transient( $key );
	if ( ! is_array( $data ) || ! isset( $data['n'], $data['t'] ) ) {
		$data = [ 'n' => 0, 't' => time() ];
	}
	if ( $data['n'] >= $max ) {
		return false;
	}
	$data['n']++;
	set_transient( $key, $data, max( 1, $window - ( time() - $data['t'] ) ) );
	return true;
}

function ae_booking_fail( $code, $message, $status = 400 ) {
	nocache_headers();
	wp_send_json_error( [ 'code' => $code, 'message' => $message ], $status );
}

function ae_booking_fmt_time( DateTimeImmutable $t ) {
	return $t->format( 'g:i a' );
}

function ae_booking_fmt_when( DateTimeImmutable $t ) {
	return $t->format( 'l, F j, Y' ) . ' at ' . ae_booking_fmt_time( $t ) . ' Eastern Time';
}

/** Values the page script needs. Dates are New York calendar days. */
function ae_booking_front_config() {
	$now = new DateTimeImmutable( 'now', ae_booking_tz() );
	return [
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'today'    => $now->format( 'Y-m-d' ),
		'maxDate'  => $now->modify( '+' . AE_BOOKING_DAYS_AHEAD . ' days' )->format( 'Y-m-d' ),
		'leadNote' => AE_BOOKING_LEAD_HOURS,
	];
}

/* ------------------------------------------------------------------------- *
 * AJAX: slots (public)
 * ------------------------------------------------------------------------- */

add_action( 'wp_ajax_ae_booking_slots', 'ae_booking_ajax_slots' );
add_action( 'wp_ajax_nopriv_ae_booking_slots', 'ae_booking_ajax_slots' );

function ae_booking_ajax_slots() {
	if ( ! ae_booking_rate_ok( 'slots', 60, 10 * MINUTE_IN_SECONDS ) ) {
		ae_booking_fail( 'rate', 'Too many requests. Please wait a few minutes and try again.', 429 );
	}
	$date = isset( $_GET['date'] ) ? sanitize_text_field( wp_unslash( $_GET['date'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$day  = ae_booking_parse_date( $date );
	$now  = new DateTimeImmutable( 'now', ae_booking_tz() );
	if ( ! $day || ! ae_booking_day_in_window( $day, $now ) ) {
		ae_booking_fail( 'bad_date', 'That day is not available. Please pick another day.' );
	}
	if ( ! ae_booking_day_starts( $day ) ) {
		nocache_headers();
		wp_send_json_success( [ 'date' => $date, 'slots' => [] ] );
	}

	// Short cache so a burst of visitors does not hammer the API. The booking path never uses it.
	$cache_key = 'ae_bk_busy_' . $date;
	$busy      = get_transient( $cache_key );
	if ( ! is_array( $busy ) ) {
		$busy = ae_booking_busy( $day, $day->modify( '+1 day' ) );
		if ( is_wp_error( $busy ) ) {
			ae_booking_fail( 'unavailable', 'We could not load times right now. Please try again in a moment, or use the direct link below.', 503 );
		}
		set_transient( $cache_key, $busy, 30 );
	}

	$slots = [];
	foreach ( ae_booking_free_starts( $day, $busy, $now ) as $start ) {
		$slots[] = [ 'time' => $start->format( 'H:i' ), 'label' => ae_booking_fmt_time( $start ) ];
	}
	nocache_headers();
	wp_send_json_success( [ 'date' => $date, 'slots' => $slots ] );
}

/* ------------------------------------------------------------------------- *
 * AJAX: create booking (public)
 * ------------------------------------------------------------------------- */

add_action( 'wp_ajax_ae_booking_create', 'ae_booking_ajax_create' );
add_action( 'wp_ajax_nopriv_ae_booking_create', 'ae_booking_ajax_create' );

function ae_booking_clean_name( $raw ) {
	$v = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $raw ) ) );
	$v = preg_replace( '/[\x00-\x1F\x7F<>]/u', '', $v );
	return $v;
}

function ae_booking_ajax_create() {
	// Honeypot: real visitors never see this field.
	if ( ! empty( $_POST['website_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		ae_booking_fail( 'failed', 'We could not complete your booking. Please try again, or use the direct link below.' );
	}
	if ( ! ae_booking_rate_ok( 'create', 8, HOUR_IN_SECONDS ) ) {
		ae_booking_fail( 'rate', 'Too many booking attempts. Please try again later, or use the direct link below.', 429 );
	}

	// phpcs:disable WordPress.Security.NonceVerification
	$first = ae_booking_clean_name( isset( $_POST['first_name'] ) ? wp_unslash( $_POST['first_name'] ) : '' );
	$last  = ae_booking_clean_name( isset( $_POST['last_name'] ) ? wp_unslash( $_POST['last_name'] ) : '' );
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$date  = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';
	$time  = isset( $_POST['time'] ) ? sanitize_text_field( wp_unslash( $_POST['time'] ) ) : '';
	// phpcs:enable

	$len = function ( $s ) { return function_exists( 'mb_strlen' ) ? mb_strlen( $s ) : strlen( $s ); };
	if ( '' === $first || $len( $first ) > 60 ) {
		ae_booking_fail( 'invalid', 'Please enter your first name.' );
	}
	if ( '' === $last || $len( $last ) > 60 ) {
		ae_booking_fail( 'invalid', 'Please enter your last name.' );
	}
	if ( '' === $email || ! is_email( $email ) || strlen( $email ) > 254 ) {
		ae_booking_fail( 'invalid', 'Please enter a valid email address.' );
	}

	$tz   = ae_booking_tz();
	$now  = new DateTimeImmutable( 'now', $tz );
	$day  = ae_booking_parse_date( $date );
	if ( ! $day || ! preg_match( '/^\d{2}:\d{2}$/', $time ) || ! ae_booking_day_in_window( $day, $now ) ) {
		ae_booking_fail( 'invalid', 'Please pick a day and time from the list.' );
	}
	$start = null;
	foreach ( ae_booking_day_starts( $day ) as $candidate ) {
		if ( $candidate->format( 'H:i' ) === $time ) {
			$start = $candidate;
			break;
		}
	}
	if ( ! $start || $start->getTimestamp() < $now->getTimestamp() + AE_BOOKING_LEAD_HOURS * 3600 ) {
		ae_booking_fail( 'slot_taken', 'That time is no longer available. Please pick another time.', 409 );
	}
	$end = $start->modify( '+' . AE_BOOKING_SLOT_MINUTES . ' minutes' );

	// One booking at a time: an atomic option lock (add_option fails if it already exists).
	$lock = 'ae_booking_lock';
	if ( ! add_option( $lock, time(), '', 'no' ) ) {
		$held = (int) get_option( $lock );
		if ( $held && time() - $held > 45 ) {
			delete_option( $lock ); // stale lock from a crashed request
		}
		if ( ! add_option( $lock, time(), '', 'no' ) ) {
			ae_booking_fail( 'busy', 'Another booking is being confirmed. Please try again in a few seconds.', 409 );
		}
	}

	$result = ae_booking_create_event( $first, $last, $email, $start, $end );
	delete_option( $lock );
	delete_transient( 'ae_bk_busy_' . $date );

	if ( is_wp_error( $result ) ) {
		if ( 'ae_slot_taken' === $result->get_error_code() ) {
			ae_booking_fail( 'slot_taken', 'That time was just taken. Please pick another time.', 409 );
		}
		ae_booking_fail( 'failed', 'We could not complete your booking. Please try again, or use the direct link below.', 502 );
	}

	nocache_headers();
	wp_send_json_success( [
		'meet'    => $result,
		'when'    => ae_booking_fmt_when( $start ),
		'message' => 'You are booked. We sent a calendar invite to your email.',
	] );
}

/**
 * Rechecks freebusy for the exact slot, then inserts the event with a Meet link.
 * @return string|WP_Error  Meet link (may be empty if Google has not attached it yet)
 */
function ae_booking_create_event( $first, $last, $email, DateTimeImmutable $start, DateTimeImmutable $end ) {
	$busy = ae_booking_busy( $start, $end );
	if ( is_wp_error( $busy ) ) {
		return $busy;
	}
	if ( ae_booking_overlaps( $start, $busy ) ) {
		return new WP_Error( 'ae_slot_taken', 'slot taken' );
	}

	$url = 'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode( AE_BOOKING_CALENDAR )
		. '/events?conferenceDataVersion=1&sendUpdates=all';
	$res = ae_booking_google( 'POST', $url, [
		'summary'        => AE_BOOKING_EVENT_TITLE,
		'description'    => AE_BOOKING_EVENT_DESC,
		'start'          => [ 'dateTime' => $start->format( DATE_RFC3339 ), 'timeZone' => AE_BOOKING_TZ ],
		'end'            => [ 'dateTime' => $end->format( DATE_RFC3339 ), 'timeZone' => AE_BOOKING_TZ ],
		'attendees'      => [ [ 'email' => $email, 'displayName' => $first . ' ' . $last ] ],
		'conferenceData' => [
			'createRequest' => [
				'requestId'             => wp_generate_uuid4(),
				'conferenceSolutionKey' => [ 'type' => 'hangoutsMeet' ],
			],
		],
	] );
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	list( $status, $event ) = $res;
	if ( 200 !== $status || empty( $event['id'] ) ) {
		error_log( 'AE booking: event insert failed (HTTP ' . (int) $status . ')' );
		return new WP_Error( 'ae_insert', 'insert failed' );
	}

	$meet = ae_booking_meet_from( $event );
	if ( '' === $meet ) {
		// Meet creation can lag a moment. Look once more.
		sleep( 1 );
		$again = ae_booking_google( 'GET', 'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode( AE_BOOKING_CALENDAR ) . '/events/' . rawurlencode( (string) $event['id'] ) . '?conferenceDataVersion=1' );
		if ( ! is_wp_error( $again ) && 200 === $again[0] ) {
			$meet = ae_booking_meet_from( $again[1] );
		}
	}
	return $meet;
}

function ae_booking_meet_from( array $event ) {
	$link = isset( $event['hangoutLink'] ) ? (string) $event['hangoutLink'] : '';
	if ( '' === $link ) {
		foreach ( (array) ( $event['conferenceData']['entryPoints'] ?? [] ) as $ep ) {
			if ( isset( $ep['entryPointType'], $ep['uri'] ) && 'video' === $ep['entryPointType'] ) {
				$link = (string) $ep['uri'];
				break;
			}
		}
	}
	return 0 === strpos( $link, 'https://meet.google.com/' ) ? $link : '';
}
