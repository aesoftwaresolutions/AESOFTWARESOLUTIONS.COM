<?php
/**
 * Telnyx Front Desk call summary webhook — AE Software Solutions LLC.
 *
 * Receives Telnyx Voice AI events for the Front Desk assistant and sends ONE
 * short email per call to the owner, plus a text for urgent calls, plus a
 * relay of the call into the AE client dashboard.
 *
 * Public URL (after the theme push):
 *   https://aesoftwaresolutions.com/wp-content/themes/aesoftwares-theme/telnyx-call-summary.php?key=YOUR_SECRET
 *
 * Events handled (both come from one call):
 *   call.conversation.ended               — transcript, duration, numbers, reason
 *   call.conversation_insights.generated  — insight results (summary, name, urgency, ...)
 * The two events share call_control_id (the insights event carries NO
 * conversation_id), so call_control_id is the merge key. The endpoint stores
 * each event, sends once both have arrived, and falls back to sending with a
 * single event if the other never shows up within 5 minutes.
 *
 * BEFORE GOING LIVE set the REPLACE_ME values below (secret token, dashboard
 * key, dashboard client id). No real secret is stored in this file.
 * See call-summary-webhook-REPORT.md for the setup steps.
 *
 * Signature check (Ed25519). Every request must ALSO carry a valid Telnyx
 * signature: headers telnyx-signature-ed25519 (base64) and telnyx-timestamp
 * (unix seconds), signed over "timestamp|raw body" with the account key.
 * Requests older or newer than 300 seconds are rejected. The public key is
 * read, in order, from a TELNYX_PUBLIC_KEY constant, then the TELNYX_PUBLIC_KEY
 * environment variable (getenv, $_SERVER, $_ENV). This file runs on its own,
 * WITHOUT WordPress, so a constant in wp-config.php is NOT visible here: set it
 * as an env var (for example "SetEnv TELNYX_PUBLIC_KEY ..." in the site
 * .htaccess) or define it in the Config block below. With no valid key the
 * endpoint rejects everything (fails closed). The ?key= check stays in place.
 */

// ---------- Config (edit the REPLACE_ME values before going live) ----------
define('AE_OWNER_EMAIL', 'anthony@aesoftwaresolutions.com');
define('AE_OWNER_SMS_EMAIL', '7179771021@tmomail.net'); // T-Mobile email-to-SMS
define('AE_WEBHOOK_KEY', '3c1623b813e4fe6c5ca4bbc2b176e37fa6c52fb9974529f1'); // secret token; must match ?key= in status_callback
define('AE_DASHBOARD_URL', 'https://dashboard.aesoftwaresolutions.com/api/v1/webhooks/telnyx/call-summary');
define('AE_DASHBOARD_KEY', 'aek_y0AUEC2K-q8Oinx9tunL80oxxTenSQVcgmfgsWWQI84'); // per-client Bearer key for AE, from the dashboard
define('AE_DASHBOARD_CLIENT_ID', 'cl_7c6471482e77ce6e'); // AE's own client id in the dashboard
define('AE_STORE_DIR', __DIR__ . '/telnyx-call-store');
define('AE_LOG_FILE', __DIR__ . '/telnyx-call-summary.log');
define('AE_SINGLE_EVENT_WAIT_SEC', 300); // send with one event if the other is 5+ min late
define('AE_MAX_STORE_AGE_SEC', 86400); // drop unsent entries older than 24h
define('AE_FROM_EMAIL', 'noreply@aesoftwaresolutions.com');
define('AE_TELNYX_TOLERANCE_SEC', 300); // replay window for telnyx-timestamp
// Optional: the Telnyx account PUBLIC key (base64), if you prefer it here over an env var.
// It is a public key, not a secret. Copy it from Mission Control > Keys and Credentials.
define('TELNYX_PUBLIC_KEY', 'R4Ik8yZl0xaV/TuEkAueD1NMYGEubiCwa5VKpiHX+lk=');

// ---------- Helpers ----------
function ae_log($msg) {
    $line = gmdate('Y-m-d\TH:i:s\Z') . ' ' . $msg . "\n";
    @file_put_contents(AE_LOG_FILE, $line, FILE_APPEND | LOCK_EX);
}

function ae_store_path($ccid) {
    $safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $ccid);
    if ($safe === '' || $safe === '.' || $safe === '..') { $safe = 'unknown'; }
    return AE_STORE_DIR . '/' . $safe . '.json';
}

function ae_ensure_store_dir() {
    if (!is_dir(AE_STORE_DIR)) { @mkdir(AE_STORE_DIR, 0750, true); }
    // Keep merge files out of the web: block direct access on Apache hosts.
    $ht = AE_STORE_DIR . '/.htaccess';
    if (!file_exists($ht)) { @file_put_contents($ht, "Deny from all\n"); }
    $ix = AE_STORE_DIR . '/index.php';
    if (!file_exists($ix)) { @file_put_contents($ix, "<?php // no direct access\n"); }
}

function ae_load_entry($ccid) {
    $p = ae_store_path($ccid);
    if (!file_exists($p)) { return null; }
    $raw = @file_get_contents($p);
    if ($raw === false) { return null; }
    $d = json_decode($raw, true);
    return is_array($d) ? $d : null;
}

function ae_save_entry($ccid, $entry) {
    $p = ae_store_path($ccid);
    $fp = @fopen($p, 'c');
    if ($fp === false) { return false; }
    if (!flock($fp, LOCK_EX)) { fclose($fp); return false; }
    ftruncate($fp, 0);
    fwrite($fp, json_encode($entry));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

function ae_delete_entry($ccid) { @unlink(ae_store_path($ccid)); }

// Best-effort caller name from the transcript: the user turn right after the
// assistant asks for a name. Returns '' when it cannot be found.
function ae_extract_name($messages) {
    if (!is_array($messages)) { return ''; }
    $n = count($messages);
    for ($i = 0; $i < $n; $i++) {
        $m = $messages[$i];
        if (($m['role'] ?? '') === 'assistant' && stripos((string)($m['content'] ?? ''), 'name') !== false) {
            for ($j = $i + 1; $j < $n; $j++) {
                if (($messages[$j]['role'] ?? '') === 'user') {
                    $c = trim((string)($messages[$j]['content'] ?? ''));
                    if ($c !== '') { return mb_substr($c, 0, 80); }
                    break;
                }
            }
        }
    }
    return '';
}

function ae_is_urgent($text) {
    $t = strtolower((string) $text);
    $words = ['emergency', 'urgent', '911', 'website is down', 'site is down',
        'store is down', "can't take payments", 'cannot take payments',
        'gas smell', 'carbon monoxide', 'fire', 'asap', 'immediately', 'right away'];
    foreach ($words as $w) { if (strpos($t, $w) !== false) { return true; } }
    return false;
}

function ae_transcript_text($messages) {
    if (!is_array($messages)) { return ''; }
    $lines = [];
    foreach ($messages as $m) {
        $role = ($m['role'] ?? '');
        if ($role !== 'user' && $role !== 'assistant') { continue; }
        $c = trim((string)($m['content'] ?? ''));
        if ($c === '') { continue; }
        $lines[] = ($role === 'user' ? 'Caller: ' : 'Assistant: ') . $c;
    }
    return implode("\n", $lines);
}

// Build the one summary from the stored ended + insights payloads.
function ae_build_summary($entry) {
    $ended = $entry['ended']['payload'] ?? [];
    $insights = $entry['insights']['payload'] ?? [];
    $messages = $ended['messages'] ?? [];
    $results = $insights['results'] ?? [];

    $insight_texts = [];
    if (is_array($results)) {
        foreach ($results as $r) {
            $v = trim((string)($r['result'] ?? ''));
            if ($v !== '') { $insight_texts[] = $v; }
        }
    }
    $insight_blob = implode("\n", $insight_texts);
    $transcript = ae_transcript_text($messages);

    // Summary: prefer the insights text; fall back to the caller's own words.
    $summary = $insight_blob !== '' ? $insight_blob : '';
    if ($summary === '' && $transcript !== '') {
        $user_turns = [];
        foreach ((array) $messages as $m) {
            if (($m['role'] ?? '') === 'user') { $user_turns[] = trim((string)($m['content'] ?? '')); }
        }
        $summary = implode(' ', array_filter($user_turns));
    }
    $summary = trim($summary);
    if ($summary === '') { $summary = 'No summary captured.'; }

    $caller_number = (string)($ended['from'] ?? '');
    $caller_name = ae_extract_name($messages);
    $duration = (int)($ended['duration_sec'] ?? 0);
    $reason = (string)($ended['reason'] ?? '');
    $conversation_id = (string)($ended['conversation_id'] ?? '');
    $occurred_at = (string)($entry['ended']['occurred_at'] ?? $entry['insights']['occurred_at'] ?? '');

    $urgent = ae_is_urgent($insight_blob . "\n" . $transcript);

    // Outcome for the dashboard: answered when the assistant spoke to someone.
    $has_user = false; $has_asst = false;
    foreach ((array) $messages as $m) {
        if (($m['role'] ?? '') === 'user') { $has_user = true; }
        if (($m['role'] ?? '') === 'assistant') { $has_asst = true; }
    }
    $outcome = ($has_asst && $duration > 0) ? 'answered' : 'missed';
    if ($has_user) { $outcome = 'answered'; }

    // Portal link is a best-effort format (unverified, see REPORT.md).
    $portal = $conversation_id !== ''
        ? 'https://portal.telnyx.com/voice-ai/conversations/' . $conversation_id
        : 'https://portal.telnyx.com/';

    return [
        'caller_name' => $caller_name,
        'caller_number' => $caller_number,
        'summary' => mb_substr($summary, 0, 2000),
        'outcome' => $outcome,
        'urgency' => $urgent ? 'urgent' : 'routine',
        'urgent' => $urgent,
        'duration_sec' => $duration,
        'reason' => $reason,
        'conversation_id' => $conversation_id,
        'occurred_at' => $occurred_at,
        'portal_url' => $portal,
    ];
}

function ae_send_email($s) {
    $who = $s['caller_name'] !== '' ? $s['caller_name'] : $s['caller_number'];
    if ($who === '') { $who = 'Unknown caller'; }
    $subject = 'Front Desk call: ' . $who . ' - ' . $s['outcome'];
    if ($s['urgent']) { $subject = '[URGENT] ' . $subject; }
    $dur = $s['duration_sec'] > 0 ? gmdate('i:s', $s['duration_sec']) . ' (' . $s['duration_sec'] . 's)' : 'n/a';
    $body = "Front Desk call summary\n"
        . "Caller: " . ($s['caller_name'] !== '' ? $s['caller_name'] : 'Not provided') . "\n"
        . "Number: " . ($s['caller_number'] !== '' ? $s['caller_number'] : 'Not provided') . "\n"
        . "What they wanted: " . $s['summary'] . "\n"
        . "Outcome: " . $s['outcome'] . ($s['reason'] !== '' ? ' (' . $s['reason'] . ')' : '') . "\n"
        . "Urgency: " . $s['urgency'] . "\n"
        . "Duration: " . $dur . "\n"
        . "Recording and transcript: " . $s['portal_url'] . "\n"
        . "Conversation id: " . $s['conversation_id'] . "\n";
    $headers = 'From: ' . AE_FROM_EMAIL . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';
    return mail(AE_OWNER_EMAIL, $subject, $body, $headers);
}

function ae_send_text($s) {
    $who = $s['caller_name'] !== '' ? $s['caller_name'] : $s['caller_number'];
    $body = 'URGENT Front Desk call: ' . $who
        . ($s['caller_number'] !== '' ? ' ' . $s['caller_number'] : '')
        . ' - ' . mb_substr($s['summary'], 0, 120);
    $headers = 'From: ' . AE_FROM_EMAIL . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';
    return mail(AE_OWNER_SMS_EMAIL, '', $body, $headers);
}

// Relay to the AE client dashboard. Never blocks the email; logs failures.
function ae_relay_dashboard($s) {
    if (AE_DASHBOARD_KEY === 'REPLACE_ME' || AE_DASHBOARD_CLIENT_ID === 'REPLACE_ME') {
        ae_log('relay skipped: dashboard key/client id not configured');
        return false;
    }
    $started = $s['occurred_at'] !== '' ? $s['occurred_at'] : gmdate('Y-m-d\TH:i:s\Z');
    $body = [
        'client_id' => AE_DASHBOARD_CLIENT_ID,
        'service' => 'front-desk',
        'outcome' => $s['outcome'],
        'caller_number' => $s['caller_number'],
        'summary' => $s['summary'],
        'duration_seconds' => $s['duration_sec'],
        'started_at' => $started,
    ];
    if ($s['caller_name'] !== '') { $body['caller_name'] = $s['caller_name']; }
    $json = json_encode($body);
    $ch = curl_init(AE_DASHBOARD_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . AE_DASHBOARD_KEY,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    $data = json_decode((string) $resp, true);
    if ($code === 200 && is_array($data) && !empty($data['ok'])) {
        ae_log('relay ok: event ' . ($data['event_id'] ?? ''));
        return true;
    }
    ae_log('relay FAILED: http ' . $code . ' ' . ($err !== '' ? $err : mb_substr((string) $resp, 0, 200)));
    return false;
}

// Send the one summary for a stored call. Returns true when the email went out.
function ae_send_once($ccid, $entry) {
    $s = ae_build_summary($entry);
    $emailed = ae_send_email($s);
    $texted = false;
    if ($s['urgent']) { $texted = ae_send_text($s); }
    ae_relay_dashboard($s); // best effort; never blocks the email
    ae_log('sent ccid=' . $ccid . ' conv=' . $s['conversation_id']
        . ' email=' . ($emailed ? 'yes' : 'FAILED')
        . ' text=' . ($s['urgent'] ? ($texted ? 'yes' : 'FAILED') : 'n/a')
        . ' outcome=' . $s['outcome']);
    return $emailed;
}

// ---------- Telnyx signature (Ed25519) ----------
// Returns the 32 raw key bytes, or null when the key is missing or malformed.
function ae_telnyx_public_key() {
    $v = '';
    if (defined('TELNYX_PUBLIC_KEY')) {
        $v = (string) constant('TELNYX_PUBLIC_KEY');
    } else {
        $env = getenv('TELNYX_PUBLIC_KEY');
        if ($env !== false && $env !== '') { $v = (string) $env; }
        elseif (!empty($_SERVER['TELNYX_PUBLIC_KEY'])) { $v = (string) $_SERVER['TELNYX_PUBLIC_KEY']; }
        elseif (!empty($_SERVER['REDIRECT_TELNYX_PUBLIC_KEY'])) { $v = (string) $_SERVER['REDIRECT_TELNYX_PUBLIC_KEY']; }
        elseif (!empty($_ENV['TELNYX_PUBLIC_KEY'])) { $v = (string) $_ENV['TELNYX_PUBLIC_KEY']; }
    }
    $v = preg_replace('/\s+/', '', $v);
    if ($v === '' || $v === null) { return null; }
    $raw = base64_decode($v, true);
    return ($raw !== false && strlen($raw) === 32) ? $raw : null;
}

function ae_request_header($name) {
    $k = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return isset($_SERVER[$k]) ? trim((string) $_SERVER[$k]) : '';
}

function ae_ed25519_verify_sodium($message, $sig, $pk) {
    try { return sodium_crypto_sign_verify_detached($sig, $message, $pk) === true; }
    catch (Throwable $e) { return false; }
}

// Fallback when ext-sodium is missing. Wraps the raw key as SPKI PEM. Any error,
// or an OpenSSL build without Ed25519 support, returns false (reject).
function ae_ed25519_verify_openssl($message, $sig, $pk) {
    $der = hex2bin('302a300506032b6570032100') . $pk;
    $pem = "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($der), 64, "\n") . "-----END PUBLIC KEY-----\n";
    $key = @openssl_pkey_get_public($pem);
    if ($key === false) { return false; }
    try { $ok = @openssl_verify($message, $sig, $key, 0); }
    catch (Throwable $e) { return false; }
    return $ok === 1;
}

// sodium first, then openssl, else reject.
function ae_ed25519_verify($message, $sig, $pk) {
    if (function_exists('sodium_crypto_sign_verify_detached')) { return ae_ed25519_verify_sodium($message, $sig, $pk); }
    if (function_exists('openssl_verify') && function_exists('openssl_pkey_get_public')) { return ae_ed25519_verify_openssl($message, $sig, $pk); }
    return false;
}

// Checks the Telnyx signature over the exact raw body. Returns '' when valid,
// otherwise a short reason. $pk null means not configured (fail closed).
function ae_telnyx_check($raw, $pk, $sig_b64, $ts, $now) {
    if ($pk === null) { return 'not configured'; }
    if ($sig_b64 === '' || $ts === '') { return 'missing signature headers'; }
    if (!preg_match('/^\d{1,12}$/', $ts)) { return 'bad timestamp'; }
    if (abs($now - (int) $ts) > AE_TELNYX_TOLERANCE_SEC) { return 'timestamp outside window'; }
    $sig = base64_decode($sig_b64, true);
    if ($sig === false || strlen($sig) !== 64) { return 'bad signature'; }
    return ae_ed25519_verify($ts . '|' . $raw, $sig, $pk) ? '' : 'bad signature';
}

// ---------- Main ----------
ae_ensure_store_dir();
http_response_code(200);
header('Content-Type: application/json');

// Fail closed when the secret is still the placeholder.
if (AE_WEBHOOK_KEY === 'REPLACE_ME') {
    ae_log('rejected: webhook secret not configured');
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'webhook not configured']);
    exit;
}
$given = isset($_GET['key']) ? (string) $_GET['key'] : '';
if (!hash_equals(AE_WEBHOOK_KEY, $given)) {
    ae_log('rejected: bad or missing key');
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'forbidden']);
    exit;
}

$raw = (string) file_get_contents('php://input');

// Ed25519 signature over the raw body bytes, before anything is parsed or stored.
$sig_problem = ae_telnyx_check($raw, ae_telnyx_public_key(),
    ae_request_header('telnyx-signature-ed25519'), ae_request_header('telnyx-timestamp'), time());
if ($sig_problem === 'not configured') {
    ae_log('rejected: TELNYX_PUBLIC_KEY not configured or invalid');
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'webhook not configured']);
    exit;
}
if ($sig_problem !== '') {
    ae_log('rejected: signature ' . $sig_problem);
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'invalid signature']);
    exit;
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    ae_log('ignored: malformed JSON');
    echo json_encode(['ok' => true]);
    exit;
}
$event_type = (string)($data['data']['event_type'] ?? '');
$payload = $data['data']['payload'] ?? null;
$occurred_at = (string)($data['data']['occurred_at'] ?? '');
$event_id = (string)($data['data']['id'] ?? '');
if (!is_array($payload) || ($event_type !== 'call.conversation.ended'
        && $event_type !== 'call.conversation_insights.generated')) {
    ae_log('ignored: event_type=' . $event_type);
    echo json_encode(['ok' => true]);
    exit;
}
$ccid = (string)($payload['call_control_id'] ?? '');
if ($ccid === '') {
    ae_log('ignored: no call_control_id for ' . $event_type);
    echo json_encode(['ok' => true]);
    exit;
}

$kind = $event_type === 'call.conversation.ended' ? 'ended' : 'insights';
$entry = ae_load_entry($ccid);
if ($entry === null) {
    $entry = ['first_seen' => time(), 'sent' => false];
}
// Dedupe a retried delivery of the same event.
if (isset($entry[$kind]['event_id']) && $entry[$kind]['event_id'] === $event_id && $event_id !== '') {
    ae_log('ignored: duplicate ' . $kind . ' ccid=' . $ccid);
    echo json_encode(['ok' => true]);
    exit;
}
$entry[$kind] = ['event_id' => $event_id, 'occurred_at' => $occurred_at, 'payload' => $payload];
ae_save_entry($ccid, $entry);
ae_log('received: ' . $kind . ' ccid=' . $ccid . ' conv=' . ($payload['conversation_id'] ?? ''));

$now = time();
$has_both = isset($entry['ended']) && isset($entry['insights']);
$single_stale = !$has_both && ($now - (int)($entry['first_seen'] ?? $now)) >= AE_SINGLE_EVENT_WAIT_SEC;

if (!$entry['sent'] && ($has_both || $single_stale)) {
    if (ae_send_once($ccid, $entry)) {
        ae_delete_entry($ccid);
    } else {
        // Email failed; keep the entry so a later sweep can retry it.
        $entry['sent'] = false;
        ae_save_entry($ccid, $entry);
    }
}

// Sweep stale single-event entries so a lone event still sends eventually.
$files = glob(AE_STORE_DIR . '/*.json');
if (is_array($files)) {
    foreach ($files as $f) {
        $id = basename($f, '.json');
        if ($id === $ccid) { continue; }
        $e = ae_load_entry($id);
        if ($e === null || !empty($e['sent'])) { continue; }
        $age = $now - (int)($e['first_seen'] ?? $now);
        if ($age > AE_MAX_STORE_AGE_SEC) { ae_delete_entry($id); continue; }
        $both = isset($e['ended']) && isset($e['insights']);
        if ($both || $age >= AE_SINGLE_EVENT_WAIT_SEC) {
            if (ae_send_once($id, $e)) { ae_delete_entry($id); }
        }
    }
}

echo json_encode(['ok' => true]);
