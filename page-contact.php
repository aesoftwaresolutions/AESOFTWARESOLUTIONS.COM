<?php
/**
 * Template Name: Contact
 *
 * Approved copy (2026-11-10): H1 "Tell us about the project.", a 24 hour reply
 * promise, a free 30 minute discovery call, checklists for services, budget and
 * timeline, and a short message box. Form field names, the honeypot and the
 * lead endpoint are unchanged from the previous version.
 *
 * @package AE_Softwares
 */
get_header();

$ae_services = [
	'website'              => 'Website',
	'online-presence'      => 'Online Presence Management',
	'front-desk'           => 'Front Desk phone answering',
	'social-front-desk'    => 'Social Front Desk',
	'missed-lead-audit'    => 'Missed-Lead Audit',
	'ai-tools-assessment'  => 'AI Tools Assessment (add on to the Missed-Lead Audit)',
	'custom'               => 'Something custom',
];
$ae_budgets = [
	'under-1k' => 'Under $1,000',
	'1k-5k'    => '$1,000 to $5,000',
	'5k-10k'   => '$5,000 to $10,000',
	'over-10k' => 'Over $10,000',
];
$ae_timelines = [
	'asap'        => 'As soon as possible',
	'this-month'  => 'This month',
	'1-3-months'  => 'In the next 1 to 3 months',
	'exploring'   => 'Just exploring for now',
];
?>

<section class="page-hero contact-page-hero">
    <p class="page-hero__eyebrow">Get in touch</p>
    <h1 class="page-hero__headline">Tell us about the project.</h1>
    <p class="page-hero__subtitle">We read every message ourselves and reply within 24 hours. The first call is a free 30 minute discovery call.</p>
</section>

<section class="ae-contact" aria-label="Contact form">

    <div class="ae-contact__form" id="ae-contact-box" data-reveal>
        <form class="contact-form" id="ae-contact-form" action="#" method="post" novalidate>

            <!-- Honeypot: bots fill every field; humans never see this one -->
            <input type="text" name="website_url" id="contact-hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;height:0;width:0;opacity:0;">

            <div class="form-row">
                <div class="form-field">
                    <label for="contact-name">Name</label>
                    <input type="text" id="contact-name" name="name" autocomplete="name" placeholder="Your full name" required>
                </div>
                <div class="form-field">
                    <label for="contact-email">Email</label>
                    <input type="email" id="contact-email" name="email" autocomplete="email" placeholder="you@company.com" required>
                </div>
            </div>

            <div class="form-field">
                <label for="contact-company">Company <span>(optional)</span></label>
                <input type="text" id="contact-company" name="company" autocomplete="organization" placeholder="Your company name">
            </div>

            <fieldset class="ae-checks" id="contact-services">
                <legend>What do you need? Pick any that apply.</legend>
                <div class="ae-checks__grid">
                    <?php foreach ( $ae_services as $val => $label ) : ?>
                        <label class="ae-check">
                            <input type="checkbox" name="service" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
                            <span class="ae-check__box" aria-hidden="true"></span>
                            <span><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="ae-checks" id="contact-budget">
                <legend>Roughly what budget do you have in mind?</legend>
                <div class="ae-checks__grid">
                    <?php foreach ( $ae_budgets as $val => $label ) : ?>
                        <label class="ae-check">
                            <input type="radio" name="budget" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
                            <span class="ae-check__box ae-check__box--radio" aria-hidden="true"></span>
                            <span><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="ae-checks" id="contact-timeline">
                <legend>When do you want to start?</legend>
                <div class="ae-checks__grid">
                    <?php foreach ( $ae_timelines as $val => $label ) : ?>
                        <label class="ae-check">
                            <input type="radio" name="timeline" value="<?php echo esc_attr( $val ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
                            <span class="ae-check__box ae-check__box--radio" aria-hidden="true"></span>
                            <span><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="form-field">
                <label for="contact-message">A few words about the project</label>
                <textarea id="contact-message" name="message" rows="4" placeholder="What you do, what is not working, anything we should know." required></textarea>
            </div>

            <div>
                <button type="submit" class="v5-button" id="contact-submit">Send message</button>
                <p class="contact-form__note" style="margin-top:12px">We reply within 24 hours. Your information is never shared.</p>
                <p class="contact-form__status" id="contact-form-status" role="status" aria-live="polite" hidden></p>
            </div>

        </form>

        <script>
        (function () {
            var form = document.getElementById('ae-contact-form');
            if (!form) return;
            var box = document.getElementById('ae-contact-box');
            var statusEl = document.getElementById('contact-form-status');
            var submitBtn = document.getElementById('contact-submit');
            var idleLabel = submitBtn.textContent;
            var busy = false; // double submit guard: true from the first valid click until the request ends

            function clearErrors() {
                form.querySelectorAll('[data-invalid]').forEach(function (el) { el.removeAttribute('data-invalid'); });
                form.querySelectorAll('[aria-invalid]').forEach(function (el) { el.removeAttribute('aria-invalid'); });
                form.querySelectorAll('.form-field__error').forEach(function (el) { el.parentNode.removeChild(el); });
                statusEl.hidden = true;
                statusEl.removeAttribute('data-state');
            }
            function flag(holder, input, message) {
                holder.setAttribute('data-invalid', '');
                if (input) { input.setAttribute('aria-invalid', 'true'); }
                var p = document.createElement('p');
                p.className = 'form-field__error';
                p.setAttribute('role', 'alert');
                p.textContent = message;
                holder.appendChild(p);
            }
            function labels(name) {
                return Array.prototype.map.call(form.querySelectorAll('input[name="' + name + '"]:checked'), function (i) { return i.getAttribute('data-label'); });
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (busy) return;
                if (document.getElementById('contact-hp').value) return; // bot
                clearErrors();

                var f = new FormData(form);
                var name = (f.get('name') || '').toString().trim();
                var email = (f.get('email') || '').toString().trim();
                var message = (f.get('message') || '').toString().trim();
                var services = labels('service');
                var first = null;

                if (!name) { var n = document.getElementById('contact-name'); flag(n.parentNode, n, 'Please enter your name.'); first = first || n; }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { var m = document.getElementById('contact-email'); flag(m.parentNode, m, 'Please enter a valid email address.'); first = first || m; }
                if (!services.length) { var s = document.getElementById('contact-services'); flag(s, null, 'Pick at least one.'); first = first || s.querySelector('input'); }
                if (!message) { var t = document.getElementById('contact-message'); flag(t.parentNode, t, 'Please add a few words about the project.'); first = first || t; }
                if (first) { first.focus(); return; }

                var budget = labels('budget')[0] || 'not given';
                var timeline = labels('timeline')[0] || 'not given';
                var notes = 'Services: ' + services.join(', ') +
                            ' | Budget: ' + budget +
                            ' | Timeline: ' + timeline +
                            '\n\n' + message;
                busy = true;
                submitBtn.disabled = true;
                submitBtn.setAttribute('aria-busy', 'true');
                submitBtn.textContent = 'Sending';

                fetch('https://admin.aesoftwaresolutions.com/api/business/webhook/inbound-lead', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        company: f.get('company') || null,
                        source: 'website_contact_form',
                        notes: notes
                    })
                }).then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    // Success replaces the form with a plain confirmation in the same box.
                    box.innerHTML = '<div class="contact-done" role="status"><h2 class="contact-done__title">Message sent.</h2><p>We read every message ourselves. You will hear from us within 24 hours.</p></div>';
                    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }).catch(function () {
                    // The post failed: say so plainly, keep what they typed, let them retry or email us.
                    busy = false;
                    submitBtn.disabled = false;
                    submitBtn.removeAttribute('aria-busy');
                    submitBtn.textContent = idleLabel;
                    statusEl.hidden = false;
                    statusEl.setAttribute('data-state', 'error');
                    statusEl.textContent = 'Your message did not go through. Check your connection and try again, or email us at ';
                    var mail = document.createElement('a');
                    mail.href = 'mailto:anthony@aesoftwaresolutions.com';
                    mail.textContent = 'anthony@aesoftwaresolutions.com';
                    statusEl.appendChild(mail);
                    statusEl.appendChild(document.createTextNode('.'));
                });
            });
        })();
        </script>
    </div>

    <aside class="ae-contact__aside" data-reveal>

        <div class="contact-sidebar__info">
            <p class="contact-sidebar__label">Email us directly</p>
            <a href="mailto:anthony@aesoftwaresolutions.com" class="contact-sidebar__email">anthony@aesoftwaresolutions.com</a>
        </div>

        <div class="contact-sidebar__info">
            <p class="contact-sidebar__label">What happens next</p>
            <ol class="contact-sidebar__next">
                <li>We read your message ourselves.</li>
                <li>We reply within 24 hours.</li>
                <li>You and we set up a free 30 minute discovery call.</li>
            </ol>
        </div>

    </aside>

</section>

<?php get_footer(); ?>
