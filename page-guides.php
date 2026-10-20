<?php
/**
 * Template Name: Guides
 *
 * Ten free PDF guides for contractors, served from wp-content/uploads/guides/.
 * The guides stay hidden behind a newsletter signup popup. Signups post to
 * admin-ajax.php (action ae_guides_signup), handled in inc/guides-newsletter.php.
 *
 * @package AE_Softwares
 */

get_header();

$guides_uri = content_url( '/uploads/guides/' );

$guides = array(
	array(
		'id'    => 'closing-playbook',
		'name'  => "The Contractor's Closing Playbook",
		'pages' => '18 pages',
		'pitch' => 'How to turn more qualified estimates into signed jobs without racing to the lowest price. Covers a response system, estimate structure, objection scripts, follow up cadence, and field worksheets.',
		'file'  => 'contractors-closing-playbook.pdf',
	),
	array(
		'id'    => 'missed-call-math',
		'name'  => 'The Missed Call Math',
		'pages' => '16 pages',
		'pitch' => 'How much revenue leaks through unanswered calls, and how to measure it yourself in two weeks. Includes the fixes in payback order and a weekly scoreboard.',
		'file'  => 'missed-call-math.pdf',
	),
	array(
		'id'    => 'speed-to-lead-playbook',
		'name'  => 'The Speed to Lead Playbook',
		'pages' => '19 pages',
		'pitch' => 'How to turn new inquiries into real conversations before the buyer moves on. Response standards, first reply scripts, coverage plans, follow up cadence, and a weekly scorecard.',
		'file'  => 'speed-to-lead-playbook.pdf',
	),
	array(
		'id'    => 'google-business-profile-playbook',
		'name'  => 'The Google Business Profile Playbook',
		'pages' => '17 pages',
		'pitch' => 'How to get found, get chosen, and turn local searches into calls. Profile setup, categories, reviews, posts, a 30 day setup sprint, and a weekly routine.',
		'file'  => 'google-business-profile-playbook.pdf',
	),
	array(
		'id'    => 'review-generation-system',
		'name'  => 'The Review Generation System',
		'pages' => '16 pages',
		'pitch' => 'A system for a steady flow of 5 star reviews without begging. When to ask, what to say, how to respond to every review, and a 90 day plan.',
		'file'  => 'review-generation-system.pdf',
	),
	array(
		'id'    => 'pricing-without-fear',
		'name'  => 'Pricing Without Fear',
		'pages' => '19 pages',
		'pitch' => 'How contractors can raise prices and still close: find your true cost, set a real margin, present options customers understand, and know when to walk away. Includes a 30 day plan.',
		'file'  => 'pricing-without-fear.pdf',
	),
	array(
		'id'    => 'speed-to-lead-mortgage-lenders',
		'name'  => 'Speed-to-Lead for Mortgage Lenders',
		'pages' => '19 pages',
		'pitch' => 'How loan officers and mortgage brokers can answer purchase leads in minutes and win the deals they already paid for. Covers the five minute playbook, routing and after hours coverage for small teams, a call then text cadence, and a 10 minute self audit.',
		'file'  => 'speed-to-lead-for-mortgage-lenders.pdf',
	),
	array(
		'id'    => 'website-conversion-basics-for-trades',
		'name'  => 'Website Conversion Basics for Trades',
		'pages' => '16 pages',
		'pitch' => 'Turn your contractor website from a brochure into an estimate machine. Layout, trust signals, short forms, speed, local SEO, and a 30 day plan.',
		'file'  => 'website-conversion-basics-for-trades.pdf',
	),
	array(
		'id'    => 'the-follow-up-system',
		'name'  => 'The Follow-Up System',
		'pages' => '22 pages',
		'pitch' => 'Stop letting quiet estimates die. Follow up cadence, scripts for every touch, the breakup email, and a one page tracker.',
		'file'  => 'the-follow-up-system.pdf',
	),
	array(
		'id'    => 'realtors-closing-playbook',
		'name'  => "The Realtor's Closing Playbook",
		'pages' => '20 pages',
		'pitch' => 'How to turn more showings into signed listings. The calls that matter, objection scripts that keep buyers calm, and a follow up cadence that carries a showing all the way to the closing table.',
		'file'  => 'the-realtors-closing-playbook.pdf',
	),
);

$faqs = array(
	array( 'What am I signing up for?', 'My monthly newsletter. Each issue covers business updates from AE Software Solutions, current world events that affect small businesses and contractors, and how AE can help. If you want off the list, email me at anthony@aesoftwaresolutions.com and I will take you off.' ),
	array( 'Is it really free?', 'Yes. The guides cost nothing when you join the newsletter. I ask for your name, company and email, and nothing else.' ),
	array( 'Who are these for?', 'Contractors and home service owners: roofers, HVAC, plumbers, electricians, remodelers and similar trades. If you quote jobs and answer a phone, the guides apply to you.' ),
	array( 'Why give them away?', 'The guides are how I introduce my work to contractors. If one helps you, great. If you want help putting any of it in place, you can reach me at anthony@aesoftwaresolutions.com.' ),
);
?>

<style>
	.ae-guides .ae-offer__price { color: var(--gold-ink); }
	.ae-guides .ae-offer__name { font-family: Georgia, 'Times New Roman', serif; }
	.ae-guides .ae-offer { border-top: 2px solid var(--gold); }
	.ae-guides__meta { margin: 0 0 12px; }
	.ae-guides__faq { max-width: 1040px; }
	.ae-guides__faq dt { margin-top: 28px; font-family: Georgia, 'Times New Roman', serif; font-size: 1.25rem; }
	.ae-guides__faq dd { margin: 8px 0 0; line-height: 1.6; }
	.ae-guides__nojs { max-width: 720px; margin: 24px auto 0; padding: 0 20px; line-height: 1.6; }

	/* Gate: the guides section stays hidden until the visitor has joined. */
	html:not(.ae-guides-unlocked) .ae-guides-gated { display: none; }
	html.ae-gate-lock { overflow: hidden; }

	.ae-gate {
		position: fixed;
		inset: 0;
		z-index: 100000;
		display: none;
		align-items: center;
		justify-content: center;
		padding: 20px;
		overflow-y: auto;
		background: rgb(var(--ink-rgb) / .88);
		opacity: 0;
		transition: opacity .45s ease;
	}
	@supports ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
		.ae-gate {
			background: rgb(var(--ink-rgb) / .45);
			-webkit-backdrop-filter: blur(10px);
			backdrop-filter: blur(10px);
		}
	}
	.ae-gate.is-open { display: flex; }
	.ae-gate.is-visible { opacity: 1; }
	.ae-gate__card {
		width: 100%;
		max-width: 480px;
		margin: auto;
		padding: 32px 28px 28px;
		background: var(--paper);
		color: var(--ink);
		border-top: 3px solid var(--gold);
		border-radius: 10px;
		box-shadow: 0 24px 64px rgb(var(--ink-rgb) / .35);
	}
	.ae-gate__eyebrow { margin: 0 0 8px; font-size: .8rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--forest); }
	.ae-gate__title { margin: 0 0 14px; font-family: Georgia, 'Times New Roman', serif; font-size: 1.75rem; font-weight: 400; line-height: 1.2; color: var(--ink); opacity: 1 !important; }
	.ae-gate__copy { margin: 0 0 20px; line-height: 1.6; color: var(--ink); }
	.ae-gate__field { display: block; margin: 0 0 14px; }
	.ae-gate__label { display: block; margin: 0 0 6px; font-size: .9rem; font-weight: 600; }
	.ae-gate__input {
		display: block;
		width: 100%;
		box-sizing: border-box;
		padding: .8rem .9rem;
		font: inherit;
		font-size: 1rem;
		color: var(--ink);
		background: var(--surface);
		border: 1px solid var(--border-hover);
		border-radius: 8px;
	}
	.ae-gate__input:focus { outline: 2px solid var(--forest); outline-offset: 1px; border-color: var(--forest); }
	.ae-gate__trap { position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden; }
	.ae-gate__error { min-height: 1.4em; margin: 0 0 12px; font-size: .95rem; line-height: 1.4; color: var(--error); }
	.ae-gate__submit {
		width: 100%;
		cursor: pointer;
		background: var(--forest);
		border-color: var(--forest);
		color: var(--paper);
	}
	.ae-gate__submit:hover, .ae-gate__submit:focus-visible { background: var(--forest-900); border-color: var(--forest-900); }
	.ae-gate__submit[disabled] { opacity: .7; cursor: default; }
	.ae-gate__fine { margin: 14px 0 0; font-size: .85rem; line-height: 1.5; color: var(--ink-70); }
	@media (prefers-reduced-motion: reduce) { .ae-gate { transition: none; } }
</style>

<section class="page-hero pricing-hero">
	<p class="page-hero__eyebrow">Free guides</p>
	<h1 class="page-hero__headline">Win more of the jobs you already quote.</h1>
	<p class="page-hero__subtitle">Nine short PDF guides, free when you join my monthly newsletter. They cover how to close more estimates, what missed calls cost you each month, how to answer new leads in minutes, how to show up on the Google map, how to earn more 5 star reviews, how to raise your prices and still close, how mortgage lenders can answer purchase leads in minutes, how to turn your website into an estimate machine, and how to follow up until estimates close.</p>
	<noscript><p class="ae-guides__nojs">The signup form needs JavaScript. Turn it on and reload this page to join the newsletter and get the guides.</p></noscript>
</section>

<section class="ae-pricing ae-guides ae-guides-gated" id="guides" aria-labelledby="gd-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="gd-title" data-split>The guides</h2>
		<span class="v5-label" data-reveal>[ Free with the newsletter ]</span>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $guides as $g ) : ?>
			<article class="ae-offer" id="<?php echo esc_attr( $g['id'] ); ?>" data-reveal>
				<h3 class="ae-offer__name"><?php echo esc_html( $g['name'] ); ?></h3>
				<p class="ae-offer__price">Free</p>
				<p class="ae-offer__cadence ae-guides__meta">PDF &middot; <?php echo esc_html( $g['pages'] ); ?></p>
				<p class="ae-offer__pitch"><?php echo esc_html( $g['pitch'] ); ?></p>
				<a class="v5-button" href="<?php echo esc_url( $guides_uri . $g['file'] ); ?>" download>Download the guide</a>
			</article>
		<?php endforeach; ?>
	</div>
	<p class="ae-guarantee" data-reveal><strong>You are on the list.</strong> Pick any guide and download it. My newsletter arrives once a month.</p>
</section>

<section class="ae-pricing ae-guides" id="faq" aria-labelledby="gd-faq">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="gd-faq" data-split>Questions</h2>
		<span class="v5-label" data-reveal>[ Plain answers ]</span>
	</header>
	<dl class="ae-guides__faq" data-reveal>
		<?php foreach ( $faqs as $f ) : ?>
			<dt><?php echo esc_html( $f[0] ); ?></dt>
			<dd><?php echo esc_html( $f[1] ); ?></dd>
		<?php endforeach; ?>
	</dl>
</section>

<!-- Signup popup. Not dismissible: no close button, no click outside, no Escape. -->
<div class="ae-gate" id="ae-gate" role="dialog" aria-modal="true" aria-labelledby="ae-gate-title" aria-describedby="ae-gate-copy"
	data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'ae_guides_signup' ) ); ?>">
	<div class="ae-gate__card">
		<p class="ae-gate__eyebrow">Free guides</p>
		<h2 class="ae-gate__title" id="ae-gate-title">Join my monthly newsletter and get all six guides.</h2>
		<p class="ae-gate__copy" id="ae-gate-copy">I send one newsletter a month. It covers what is happening at AE Software Solutions, current world events that affect small businesses and contractors, and how AE can help. The six guides are free when you join.</p>
		<form class="ae-gate__form" id="ae-gate-form" novalidate>
			<label class="ae-gate__field">
				<span class="ae-gate__label">Name</span>
				<input class="ae-gate__input" type="text" name="name" autocomplete="name" maxlength="100" required>
			</label>
			<label class="ae-gate__field">
				<span class="ae-gate__label">Company</span>
				<input class="ae-gate__input" type="text" name="company" autocomplete="organization" maxlength="150" required>
			</label>
			<label class="ae-gate__field">
				<span class="ae-gate__label">Email</span>
				<input class="ae-gate__input" type="email" name="email" autocomplete="email" maxlength="254" required>
			</label>
			<div class="ae-gate__trap" aria-hidden="true">
				<label>Leave this field empty<input type="text" name="ae_extra_info" value="" tabindex="-1" autocomplete="off"></label>
			</div>
			<p class="ae-gate__error" id="ae-gate-error" role="alert"></p>
			<button class="v5-button ae-gate__submit" type="submit">Join and get the guides</button>
			<p class="ae-gate__fine">If you want off the list, email me at anthony@aesoftwaresolutions.com and I will take you off.</p>
		</form>
	</div>
</div>

<script data-no-optimize="1" data-no-defer="1" data-cfasync="false">
(function () {
	var COOKIE = 'ae_guides_unlocked';
	var root = document.documentElement;
	var gate = document.getElementById('ae-gate');
	if (!gate) { return; }

	function hasCookie() {
		return document.cookie.split(';').some(function (c) {
			return c.replace(/^\s+/, '') === COOKIE + '=1';
		});
	}
	function setCookie() {
		var secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = COOKIE + '=1; max-age=31536000; path=/; SameSite=Lax' + secure;
	}

	// Returning visitor: show the guides at once and skip the popup.
	if (hasCookie()) {
		root.classList.add('ae-guides-unlocked');
		return;
	}

	var form = document.getElementById('ae-gate-form');
	var errorBox = document.getElementById('ae-gate-error');
	var button = form.querySelector('button[type="submit"]');
	var buttonText = button.textContent;
	var busy = false;
	var isOpen = false;

	function openGate() {
		isOpen = true;
		root.classList.add('ae-gate-lock');
		gate.classList.add('is-open');
		void gate.offsetWidth; // let the fade start from opacity 0
		gate.classList.add('is-visible');
		window.setTimeout(function () { form.elements.name.focus(); }, 50);
	}

	// Keep keyboard focus inside the popup while it is open.
	document.addEventListener('focusin', function (e) {
		if (isOpen && !gate.contains(e.target)) { form.elements.name.focus(); }
	});

	function showError(msg) { errorBox.textContent = msg; }

	function unlock() {
		setCookie();
		gate.classList.remove('is-visible');
		window.setTimeout(function () {
			isOpen = false;
			gate.classList.remove('is-open');
			root.classList.remove('ae-gate-lock');
			root.classList.add('ae-guides-unlocked');
			var target = document.getElementById('guides');
			if (target && target.scrollIntoView) {
				var calm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				target.scrollIntoView({ behavior: calm ? 'auto' : 'smooth', block: 'start' });
			}
		}, 450);
	}

	function post(data, retried) {
		var body = new URLSearchParams();
		body.append('action', 'ae_guides_signup');
		body.append('nonce', gate.getAttribute('data-nonce'));
		Object.keys(data).forEach(function (k) { body.append(k, data[k]); });

		return fetch(gate.getAttribute('data-ajax-url'), {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		}).then(function (r) { return r.json(); }).then(function (res) {
			// A cached page can carry an old nonce. Take the fresh one and retry once.
			if (res && !res.success && res.nonce && !retried) {
				gate.setAttribute('data-nonce', res.nonce);
				return post(data, true);
			}
			return res;
		});
	}

	form.addEventListener('submit', function (e) {
		e.preventDefault();
		if (busy) { return; }
		showError('');

		var data = {
			name: form.elements.name.value.trim(),
			company: form.elements.company.value.trim(),
			email: form.elements.email.value.trim(),
			ae_extra_info: form.elements.ae_extra_info.value
		};
		if (!data.name) { showError('Please enter your name.'); form.elements.name.focus(); return; }
		if (!data.company) { showError('Please enter your company.'); form.elements.company.focus(); return; }
		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) { showError('Please enter a valid email address.'); form.elements.email.focus(); return; }

		busy = true;
		button.disabled = true;
		button.textContent = 'Sending…';

		post(data, false).then(function (res) {
			if (res && res.success) { unlock(); return; }
			showError((res && res.message) || 'Something went wrong. Please try again.');
		}).catch(function () {
			showError('I could not reach the server. Check your connection and try again.');
		}).then(function () {
			busy = false;
			button.disabled = false;
			button.textContent = buttonText;
		});
	});

	window.setTimeout(openGate, 1500);
})();
</script>

<ae-cta></ae-cta>

<?php get_footer(); ?>
