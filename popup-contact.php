<?php
/**
 * Glass card contact popup, printed once in footer.php on every page except the
 * Contact page (which shows the same form as a full page).
 *
 * Opened by assets/js/ae-popup.js when a link or button carries data-ae-popup.
 * Optional attributes on the trigger:
 *   data-ae-service  comma list of service values to tick (see $ae_pop_services)
 *   data-ae-plan     plan name shown at the top and added to the lead notes
 *
 * Field names, honeypot and lead endpoint match page-contact.php. If you change
 * the service, budget or timeline lists there, change them here too.
 *
 * @package AE_Softwares
 */
$ae_pop_services = [
	'website'              => 'Website',
	'online-presence'      => 'Online Presence Management',
	'front-desk'           => 'Front Desk phone answering',
	'social-front-desk'    => 'Social Front Desk',
	'missed-lead-audit'    => 'Missed-Lead Audit',
	'ai-tools-assessment'  => 'AI Tools Assessment (add on to the Missed-Lead Audit)',
	'custom'               => 'Something custom',
];
$ae_pop_budgets = [
	'under-1k' => 'Under $1,000',
	'1k-5k'    => '$1,000 to $5,000',
	'5k-10k'   => '$5,000 to $10,000',
	'over-10k' => 'Over $10,000',
];
$ae_pop_timelines = [
	'asap'        => 'As soon as possible',
	'this-month'  => 'This month',
	'1-3-months'  => 'In the next 1 to 3 months',
	'exploring'   => 'Just exploring for now',
];
?>
<div class="ae-pop" id="ae-pop" hidden>
	<div class="ae-pop__card" role="dialog" aria-modal="true" aria-labelledby="ae-pop-title" tabindex="-1">
		<span class="ae-sheet__grabber ae-pop__grabber" aria-hidden="true"></span>
		<button type="button" class="ae-pop__close" data-ae-pop-close aria-label="Close">
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
		</button>

		<div class="ae-pop__body" id="ae-pop-body">
			<p class="ae-pop__eyebrow" id="ae-pop-eyebrow">Get in touch</p>
			<h2 class="ae-pop__title" id="ae-pop-title">Tell us about the project.</h2>
			<p class="ae-pop__lead">We read every message ourselves. The first call is a free 30 minute discovery call.</p>

			<form class="contact-form ae-pop__form" id="ae-pop-form" action="#" method="post" novalidate>
				<input type="text" name="website_url" id="pop-hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;height:0;width:0;opacity:0;">

				<div class="form-row">
					<div class="form-field">
						<label for="pop-name">Name</label>
						<input type="text" id="pop-name" name="name" autocomplete="name" placeholder="Your full name" required>
					</div>
					<div class="form-field">
						<label for="pop-email">Email</label>
						<input type="email" id="pop-email" name="email" autocomplete="email" placeholder="you@company.com" required>
					</div>
				</div>

				<div class="form-field">
					<label for="pop-company">Company <span>(optional)</span></label>
					<input type="text" id="pop-company" name="company" autocomplete="organization" placeholder="Your company name">
				</div>

				<fieldset class="ae-checks" id="pop-services">
					<legend>What do you need? Pick any that apply.</legend>
					<div class="ae-checks__grid">
						<?php foreach ( $ae_pop_services as $val => $label ) : ?>
							<label class="ae-check">
								<input type="checkbox" name="service" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
								<span class="ae-check__box" aria-hidden="true"></span>
								<span><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<fieldset class="ae-checks" id="pop-budget">
					<legend>Roughly what budget do you have in mind?</legend>
					<div class="ae-checks__grid">
						<?php foreach ( $ae_pop_budgets as $val => $label ) : ?>
							<label class="ae-check">
								<input type="radio" name="budget" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
								<span class="ae-check__box ae-check__box--radio" aria-hidden="true"></span>
								<span><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<fieldset class="ae-checks" id="pop-timeline">
					<legend>When do you want to start?</legend>
					<div class="ae-checks__grid">
						<?php foreach ( $ae_pop_timelines as $val => $label ) : ?>
							<label class="ae-check">
								<input type="radio" name="timeline" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
								<span class="ae-check__box ae-check__box--radio" aria-hidden="true"></span>
								<span><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<div class="form-field">
					<label for="pop-message">A few words about the project</label>
					<textarea id="pop-message" name="message" rows="4" placeholder="What you do, what is not working, anything we should know." required></textarea>
				</div>

				<div>
					<button type="submit" class="v5-button" id="pop-submit">Send message</button>
					<p class="contact-form__note" style="margin-top:12px">Your information is never shared.</p>
					<p class="contact-form__status" id="pop-status" role="status" aria-live="polite" hidden></p>
				</div>
			</form>
		</div>
	</div>
</div>
