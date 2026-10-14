<?php
/**
 * Template Name: Guides
 *
 * Four PDF guides for contractors, sold through Stripe payment links.
 *
 * @package AE_Softwares
 */

get_header();

$guides = array(
	array(
		'id'    => 'closing-playbook',
		'name'  => "The Contractor's Closing Playbook",
		'words' => '4,800 words',
		'pitch' => 'A 4,800-word playbook for contractors: turn more estimates into signed jobs. Estimate call structure, objection responses, follow-up cadence, and benchmarks.',
		'url'   => 'https://buy.stripe.com/9B6bJ1geIgO2akXfFvabK05',
	),
	array(
		'id'    => 'missed-call-math',
		'name'  => 'The Missed-Call Math',
		'words' => '3,500 words',
		'pitch' => 'A 3,500-word guide for contractors: measure how much revenue leaks through missed calls, with a do-it-yourself method and the fixes ranked.',
		'url'   => 'https://buy.stripe.com/28E14n0fK7ds1Or2SJabK06',
	),
	array(
		'id'    => 'speed-to-lead-playbook',
		'name'  => 'Speed-to-Lead Playbook',
		'words' => '4,100 words',
		'pitch' => 'A 4,100-word guide for contractors: why the first business to answer wins the job, and the systems to respond in minutes.',
		'url'   => 'https://buy.stripe.com/dRmbJ12nS69o3WzbpfabK07',
	),
	array(
		'id'    => 'google-business-profile-playbook',
		'name'  => 'Google Business Profile Playbook',
		'words' => '3,500 words',
		'pitch' => 'A 3,500-word guide for contractors: own the local map pack. Setup, categories, posts, reviews, and a 30-day routine.',
		'url'   => 'https://buy.stripe.com/3cIfZh3rWfJY2SvdxnabK08',
	),
);

$faqs = array(
	array( 'How do I get the guide?', 'Right after you pay, the PDF is emailed to the address you used at checkout. Check your spam folder if it has not arrived in a few minutes, or email anthony@aesoftwaresolutions.com and we will send it again.' ),
	array( 'Who are these for?', 'Contractors and home-service owners: roofers, HVAC, plumbers, electricians, remodelers and similar trades. If you quote jobs and answer a phone, the guides apply to you.' ),
	array( 'Is this a subscription?', 'No. Each guide is a one-time $47 payment through Stripe. You keep the PDF.' ),
	array( 'What if it is not useful?', 'Email us within 7 days of buying and we will refund you. No forms and no questions.' ),
);
?>

<style>
	.ae-guides .ae-offer__price { color: #C9A96E; }
	.ae-guides .ae-offer__name { font-family: Georgia, 'Times New Roman', serif; }
	.ae-guides .ae-offer { border-top: 2px solid #C9A96E; }
	.ae-guides__meta { margin: 0 0 12px; }
	.ae-guides__faq { max-width: 1040px; }
	.ae-guides__faq dt { margin-top: 28px; font-family: Georgia, 'Times New Roman', serif; font-size: 1.25rem; }
	.ae-guides__faq dd { margin: 8px 0 0; line-height: 1.6; }
</style>

<section class="page-hero pricing-hero">
	<p class="page-hero__eyebrow">Guides</p>
	<h1 class="page-hero__headline">Win more of the jobs you already quote.</h1>
	<p class="page-hero__subtitle">Four short PDF guides for contractors. They cover how to close more estimates, what missed calls cost you each month, how to answer new leads in minutes, and how to show up on the Google map. $47 each, one time.</p>
</section>

<section class="ae-pricing ae-guides" id="guides" aria-labelledby="gd-title">
	<header class="v5-section-head ae-rule">
		<h2 class="v5-section-title" id="gd-title" data-split>The guides</h2>
		<span class="v5-label" data-reveal>[ PDF · emailed after purchase ]</span>
	</header>
	<div class="ae-offers ae-offers--two">
		<?php foreach ( $guides as $g ) : ?>
			<article class="ae-offer" id="<?php echo esc_attr( $g['id'] ); ?>" data-reveal>
				<h3 class="ae-offer__name"><?php echo esc_html( $g['name'] ); ?></h3>
				<p class="ae-offer__price">$47</p>
				<p class="ae-offer__cadence ae-guides__meta">One time &middot; PDF &middot; <?php echo esc_html( $g['words'] ); ?></p>
				<p class="ae-offer__pitch"><?php echo esc_html( $g['pitch'] ); ?></p>
				<a class="v5-button" href="<?php echo esc_url( $g['url'] ); ?>" rel="noopener">Buy for $47</a>
			</article>
		<?php endforeach; ?>
	</div>
	<p class="ae-guarantee" data-reveal><strong>7 day guarantee.</strong> If a guide is not useful to you, email us within 7 days of buying and we will refund you in full.</p>
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

<ae-cta></ae-cta>

<?php get_footer(); ?>
