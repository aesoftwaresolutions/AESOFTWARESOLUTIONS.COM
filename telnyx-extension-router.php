<?php
/**
 * Telnyx extension router — AE Software Solutions LLC.
 *
 * Answers the main Front Desk number, plays a short menu, and routes the
 * caller to the right AI assistant by extension. The primary agent (Aria)
 * stays the default: no digit, a timeout, or pressing 1 all reach Aria.
 *
 * Public URL (after the theme push):
 *   https://aesoftwaresolutions.com/wp-content/themes/aesoftwares-theme/telnyx-extension-router.php
 *
 * Point the TeXML app at this URL as its voice_url (voice_method stays GET).
 * The app's status_callback is unchanged.
 *
 * HOW TO ADD AN EXTENSION
 *   1. Create the new AI assistant in Telnyx and copy its assistant id.
 *   2. Add one line to $EXTENSIONS below: '6' => 'assistant-xxxx...',
 *      where '6' is the digit the caller presses.
 *   3. Add the option to the $MENU text so callers know it exists.
 *   4. No other change is needed. The router redirects to that
 *      assistant's TeXML URL.
 */

// ---------- Extension map (digit => Telnyx assistant id) ----------
$ARIA_ID = 'assistant-ef4f6079-9bd0-4279-8679-0b306fb7b22f'; // primary Front Desk agent
$EXTENSIONS = [
    '1' => $ARIA_ID,                                        // Front Desk (Aria)
    '2' => 'assistant-ad7376e0-4639-49d1-b5cf-a97fc1e4a1fc', // Collections demo
    '3' => 'assistant-c91b05a9-3366-449a-b2dd-6a8ad12093af', // Scheduling demo
    '4' => 'assistant-47d71699-4f05-4632-aa82-7d08a16d8571', // Order Desk demo
    '5' => 'assistant-4ae3abe5-9104-442f-a4fe-ec9473c3654a', // Legal intake demo
];
$DEFAULT_ID = $ARIA_ID; // no digit, timeout, or unknown digit

// ---------- Menu (one sentence, plain spoken, no dashes) ----------
$MENU = 'Thanks for calling AE Software Solutions. Press 1 for the front desk, 2 for billing and payments, 3 to book a call, 4 to place an order, or 5 for legal intake.';

// ---------- Router ----------
function ae_assistant_texml_url($assistant_id) {
    return 'https://api.telnyx.com/v2/ai/assistants/' . rawurlencode($assistant_id) . '/texml';
}

function ae_router_url() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'aesoftwaresolutions.com';
    $path = $_SERVER['SCRIPT_NAME'] ?? '/wp-content/themes/aesoftwares-theme/telnyx-extension-router.php';
    return $scheme . '://' . $host . $path;
}

function ae_texml($inner) {
    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<Response>' . $inner . '</Response>';
    exit;
}

$digits = isset($_REQUEST['Digits']) ? preg_replace('/\D/', '', (string) $_REQUEST['Digits']) : '';

if ($digits === '') {
    // No digit yet: play the menu once and gather a single digit.
    // If the caller presses nothing within 5 seconds, fall through to Aria.
    $action = htmlspecialchars(ae_router_url(), ENT_QUOTES, 'UTF-8');
    $menu = htmlspecialchars($MENU, ENT_QUOTES, 'UTF-8');
    $aria = htmlspecialchars(ae_assistant_texml_url($DEFAULT_ID), ENT_QUOTES, 'UTF-8');
    ae_texml(
        '<Gather numDigits="1" action="' . $action . '" method="GET" timeout="5">'
        . '<Say>' . $menu . '</Say>'
        . '</Gather>'
        . '<Redirect>' . $aria . '</Redirect>'
    );
}

// A digit was pressed: redirect to that assistant's TeXML URL.
// Unknown digits fall back to Aria.
$target = $EXTENSIONS[$digits] ?? $DEFAULT_ID;
$target_url = htmlspecialchars(ae_assistant_texml_url($target), ENT_QUOTES, 'UTF-8');
ae_texml('<Redirect>' . $target_url . '</Redirect>');
