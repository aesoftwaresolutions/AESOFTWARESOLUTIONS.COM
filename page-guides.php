<?php
/**
 * Template Name: Guides
 *
 * Five free PDF guides for contractors, served from wp-content/uploads/guides/.
 * NOTE: this is the PHP WordPress template (page-guides.php in the repo), staged as .html for review.
 *
 * @package AE_Softwares
 */

get_header();

// Google Form embed URL for the email signup. Leave empty until Anthony pastes it in.
// Example shape: https://docs.google.com/forms/d/e/XXXXXXXX/viewform?embedded=true
$signup_form_url = '';

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
);

$faqs = array(
	array( 'Are the guides really free?', 'Yes. Click Download free on any guide and the PDF opens or saves. You do not have to give me your email to get them.' ),
	array( 'Who are these for?', 'Contractors and home service owners: roofers, HVAC, plumbers, electricians, remodelers and similar trades. If you quote jobs and answer a phone, the guides apply to you.' ),
	array( 'Why give them away?', 'The guides are how I introduce my work to contractors. If one helps you, great. If you want help putting any of it in place, you can reach me at anthony@aesoftwaresolutions.com.' ),
);

// Only mention the email signup once the form is live.
if ( '' !== $signup_form_url ) {
	$faqs[] = array( 'Will I hear from you if I download?', 'Not unless you sign up for new guides on this page. Downloading a guide does not add you to anything.' );
}
?>

<style>
	.ae-guides .ae-offer__price { color: #C9A96E; }
	.ae-guides .ae-offer__name { font-family: Georgia, 'Times New Roman', serif; }
	.ae-guides .ae-offer { border-top: 2px solid #C9A96E; }
	.ae-guides__meta { margin: 0 0 12px; }
	.ae-guides__faq { max-width: 1040px; }
	.ae-guides__faq dt { margin-top: 28px; font-family: Georgia, 'Times New Roman', serif; font-size: 1.25rem; }
	.ae-guides__faq dd { margin: 8px 0 0; line-height: 1.6; }
	.ae-guides__signup-copy { max-width: 720px; margin: 0 0 24px; line-height: 1.6; }
	.ae-guides__signup-frame { display: block; width: 100%; max-width: 720px; min-height: 640px; border: 0; border-top: 2px solid #C9A96E; background: #fff; }
</style>

<section class="page-hero pricing-hero">
	<p class="page-hero__eyebrow">Free guides</p>
	<h1 class="page-hero__headline">Win more of the jobs you already quote.</h1>
	<p class="page-hero__subtitle">Five short PDF guides for contractors, and every one is free to download. They cover how to close more estimates, what missed calls cost you each month, how to answer new leads in minutes, how to show up on the Google map, and how to earn more 5 star reviews.</p>
</section>

<section class="ae-pricing ae-guides" id="guides" aria-labelledby="gd-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="gd-title" data-split>The guides</h2>
		<span class="v5-label" data-reveal>[ Free PDF downloads ]</span>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $guides as $g ) : ?>
			<article class="ae-offer" id="<?php echo esc_attr( $g['id'] ); ?>" data-reveal>
				<h3 class="ae-offer__name"><?php echo esc_html( $g['name'] ); ?></h3>
				<p class="ae-offer__price">Free</p>
				<p class="ae-offer__cadence ae-guides__meta">PDF &middot; <?php echo esc_html( $g['pages'] ); ?></p>
				<p class="ae-offer__pitch"><?php echo esc_html( $g['pitch'] ); ?></p>
				<a class="v5-button" href="<?php echo esc_url( $guides_uri . $g['file'] ); ?>" download>Download free</a>
			</article>
		<?php endforeach; ?>
	</div>
	<p class="ae-guarantee" data-reveal><strong>No email needed.</strong> Download any guide with one click.<?php if ( '' !== $signup_form_url ) : ?> Sign up below only if you want the next ones as I release them.<?php endif; ?></p>
</section>

<!-- ANTHONY-ACTION: paste Google Form embed URL here -->
<!-- Set $signup_form_url at the top of this file. This section stays hidden until it has a value. -->
<?php if ( '' !== $signup_form_url ) : ?>
<section class="ae-pricing ae-guides" id="signup" aria-labelledby="gd-signup">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="gd-signup" data-split>Get new guides as I release them</h2>
		<span class="v5-label" data-reveal>[ Optional email signup ]</span>
	</header>
	<p class="ae-guides__signup-copy" data-reveal>Leave your email and I will send you each new guide when it is ready. You do not need to sign up to download the guides above.</p>
	<iframe class="ae-guides__signup-frame" src="<?php echo esc_url( $signup_form_url ); ?>" title="Sign up for new guides" loading="lazy">Loading form&hellip;</iframe>
</section>
<?php endif; ?>

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

<ae-cta></ae-cta>

<?php get_footer(); ?>
