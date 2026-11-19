<?php
/**
 * Template Name: Book a Call
 *
 * WordPress Page slug: book-a-call. Page title: "Book a Discovery Call".
 * Native booking form. Slots and bookings go through inc/ae-booking.php
 * (admin-ajax), which talks to Google Calendar over OAuth 2.0.
 *
 * @package AE_Softwares
 */

$ae_book_uri  = get_template_directory_uri();
$ae_book_path = get_template_directory();
$ae_book_ver  = function ( $file ) use ( $ae_book_path ) {
	return file_exists( $ae_book_path . $file ) ? filemtime( $ae_book_path . $file ) : '1.0.0';
};
$ae_book_live = function_exists( 'ae_booking_front_config' );

// Enqueue before get_header() so wp_head() prints the stylesheet.
wp_enqueue_style( 'ae-booking', $ae_book_uri . '/booking.css', [], $ae_book_ver( '/booking.css' ) );
if ( $ae_book_live ) {
	wp_enqueue_script( 'ae-booking', $ae_book_uri . '/booking.js', [], $ae_book_ver( '/booking.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
	wp_localize_script( 'ae-booking', 'AE_BOOKING', ae_booking_front_config() );
}

get_header();
?>

<section class="page-hero book-page-hero">
    <p class="page-hero__eyebrow">Discovery call</p>
    <h1 class="page-hero__headline">Book a Discovery Call</h1>
    <p class="page-hero__subtitle">Pick a time that works for you. The call is 30 minutes on Google Meet, and it is free.</p>
</section>

<section class="ae-contact ae-book" aria-label="Book a Discovery Call">

    <?php if ( $ae_book_live ) : ?>
    <div class="ae-book__panel" id="ae-book" data-reveal data-ae-booking>

        <div class="ae-book__flow" id="ae-book-flow">

            <div class="ae-book__step" id="ae-book-step-day">
                <p class="ae-book__eyebrow">Step 1</p>
                <h2 class="ae-book__title">Pick a day</h2>
                <p class="ae-book__note">We are open Monday to Friday. Weekends are closed.</p>
                <div class="ae-book__weekbar">
                    <button type="button" class="ae-book__nav" id="ae-book-prev" aria-label="Previous week">&larr;</button>
                    <p class="ae-book__month" id="ae-book-month" aria-live="polite"></p>
                    <button type="button" class="ae-book__nav" id="ae-book-next" aria-label="Next week">&rarr;</button>
                </div>
                <div class="ae-book__days" id="ae-book-days" role="group" aria-label="Choose a day"></div>
            </div>

            <div class="ae-book__step" id="ae-book-step-time" hidden>
                <p class="ae-book__eyebrow">Step 2</p>
                <h2 class="ae-book__title">Pick a time</h2>
                <p class="ae-book__note" id="ae-book-timenote">All times are Eastern Time.</p>
                <div class="ae-book__slots" id="ae-book-slots" role="group" aria-label="Choose a time"></div>
                <p class="ae-book__status" id="ae-book-slotstatus" role="status"></p>
            </div>

            <div class="ae-book__step" id="ae-book-step-details" hidden>
                <p class="ae-book__eyebrow">Step 3</p>
                <h2 class="ae-book__title">Your details</h2>
                <p class="ae-book__chosen" id="ae-book-chosen"></p>
                <form class="contact-form ae-book__form" id="ae-book-form" action="#" method="post" novalidate>
                    <input type="text" name="website_url" id="ae-book-hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;height:0;width:0;opacity:0;">
                    <div class="form-row">
                        <div class="form-field">
                            <label for="ae-book-first">First name</label>
                            <input type="text" id="ae-book-first" name="first_name" autocomplete="given-name" maxlength="60" required>
                        </div>
                        <div class="form-field">
                            <label for="ae-book-last">Last name</label>
                            <input type="text" id="ae-book-last" name="last_name" autocomplete="family-name" maxlength="60" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="ae-book-email">Email</label>
                        <input type="email" id="ae-book-email" name="email" autocomplete="email" maxlength="254" placeholder="you@company.com" required>
                    </div>
                    <p class="ae-book__error" id="ae-book-error" role="alert" hidden></p>
                    <div class="ae-book__actions">
                        <button type="submit" class="v5-button" id="ae-book-submit">Book the call</button>
                        <span class="ae-book__hint">We will email you a calendar invite with the Google Meet link.</span>
                    </div>
                </form>
            </div>

        </div>

        <div class="ae-book__done" id="ae-book-done" tabindex="-1" hidden>
            <p class="ae-book__eyebrow">Confirmed</p>
            <h2 class="ae-book__title">You are booked</h2>
            <p class="ae-book__when" id="ae-book-when"></p>
            <p class="ae-book__note" id="ae-book-donemsg"></p>
            <p class="ae-book__meetrow" id="ae-book-meetrow" hidden><a class="v5-button" id="ae-book-meet" href="#" target="_blank" rel="noopener">Open Google Meet</a></p>
        </div>

        <noscript><p class="ae-book__note">Booking here needs JavaScript. Please use the direct link below.</p></noscript>
    </div>
    <?php endif; ?>

    <p class="ae-book__fallback">Prefer the direct link? <a href="https://calendar.app.google/oWNCzxkmP43DBkhbA" target="_blank" rel="noopener">Book here.</a></p>

</section>

<?php get_footer(); ?>
