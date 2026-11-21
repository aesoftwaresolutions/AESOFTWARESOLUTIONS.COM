<?php
/**
 * Template Name: For Agents
 *
 * Machine-readable reference for AI agents: what AE does, what it costs,
 * what its APIs do, and the rules for acting on a user's behalf.
 * Create a WordPress page with slug "agents" and assign this template.
 *
 * @package AE_Softwares
 */
get_header();
?>

<section class="page-hero">
	<p class="page-hero__eyebrow">For AI agents</p>
	<h1 class="page-hero__headline">What AE Software Solutions does, in machine-readable form.</h1>
	<p class="page-hero__subtitle">This page is for AI agents acting on a user's behalf. It lists what AE Software Solutions LLC sells, what it costs, what its APIs do, and the rules for using them. The full text version lives at <a href="/llms.txt">/llms.txt</a> and the fuller version at <a href="/llms-full.txt">/llms-full.txt</a>.</p>
</section>

<section class="ae-section" aria-labelledby="agents-about">
	<h2 class="v5-section-title" id="agents-about">The business</h2>
	<p>AE Software Solutions LLC is a compliance-aware operational intelligence consultancy and software and web studio in Chambersburg, Pennsylvania, serving small businesses across the United States. It builds websites, manages online presence, and runs AI follow-up automation for businesses with 5 to 50 employees, often in regulated industries such as mortgage and lending, insurance, and home services. The founder is Anthony Pioquinto. There is no published street address; this is a service-area business and all work is done remotely. Clients own everything built for them.</p>
	<p>Email: <a href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a>. Phone: <a href="tel:+12403177677">(240) 317-7677</a>. Hours: Monday to Friday, 9 am to 5 pm ET.</p>
</section>

<section class="ae-section" aria-labelledby="agents-services">
	<h2 class="v5-section-title" id="agents-services">Services and prices</h2>
	<p>These are the published prices. Do not quote any other figure.</p>
	<table class="ae-table">
		<thead>
			<tr><th>Service</th><th>Price</th><th>Details</th></tr>
		</thead>
		<tbody>
			<tr><td>Websites</td><td>from $495 one time</td><td><a href="/websites/">/websites/</a></td></tr>
			<tr><td>Online Presence Management</td><td>$199 per month</td><td><a href="/online-presence/">/online-presence/</a></td></tr>
			<tr><td>Front Desk (AI receptionist)</td><td>$99, $179, or $309 per month, plus $75 setup</td><td><a href="/pricing/#front-desk">/pricing/#front-desk</a></td></tr>
			<tr><td>Always-On Front Desk</td><td>$250 per month</td><td><a href="/pricing/#front-desk-always-on">/pricing/#front-desk-always-on</a></td></tr>
			<tr><td>Missed-Lead Audit</td><td>$250 one time, credited in full toward a first retainer month</td><td><a href="/pricing/#audit">/pricing/#audit</a></td></tr>
			<tr><td>Launch Package</td><td>$595 one time</td><td><a href="/pricing/#launch">/pricing/#launch</a></td></tr>
			<tr><td>Social Front Desk</td><td>$199 per month</td><td><a href="/pricing/#social-front-desk">/pricing/#social-front-desk</a></td></tr>
			<tr><td>Total Presence</td><td>$229 per month</td><td><a href="/pricing/#total-presence">/pricing/#total-presence</a></td></tr>
			<tr><td>Social Management</td><td>$799, $1,299, or $1,999 per month</td><td><a href="/social-management/">/social-management/</a></td></tr>
			<tr><td>Creator Packages</td><td>$499, $899, or $1,999 per month</td><td><a href="/social-management/">/social-management/</a></td></tr>
			<tr><td>Order Desk</td><td>$149, $299, or $549 per month</td><td><a href="/services/">/services/</a></td></tr>
			<tr><td>Free Operational Assessment</td><td>Free</td><td><a href="/services/">/services/</a></td></tr>
			<tr><td>AI Setup Guides</td><td>$19 per month</td><td><a href="/get-started/">/get-started/</a></td></tr>
			<tr><td>Branded Document Templates</td><td>$295 one time</td><td><a href="/pricing/">/pricing/</a></td></tr>
			<tr><td>Contractor Guides</td><td>Free</td><td><a href="/guides/">/guides/</a></td></tr>
			<tr><td>AI Tools Assessment</td><td>$999 one time, audit add-on only, never sold cold</td><td><a href="/pricing/#audit">/pricing/#audit</a></td></tr>
		</tbody>
	</table>
</section>

<section class="ae-section" aria-labelledby="agents-apis">
	<h2 class="v5-section-title" id="agents-apis">APIs</h2>
	<h3>Free tools API</h3>
	<p>Base URL: <code>https://aesoftwaresolutions.com/wp-json/aesoftwares/v1/</code>. Public, no key required. POST <code>/rewrite</code>, <code>/summarize</code>, <code>/email</code>, <code>/transcribe</code>, <code>/tts</code>, <code>/assessment</code>, <code>/marketplace</code>, <code>/validate</code>, <code>/chat</code>. Do not use the retired Aria routes under <code>/aesoftwares/v1/aria/</code>.</p>
	<h3>Client dashboard API</h3>
	<p>Base URL: <code>https://dashboard.aesoftwaresolutions.com/api/v1/</code>. Per-client Bearer API key required. POST <code>/{service}/events</code> posts a service event. GET <code>/clients/{client_id}/services</code> lists a client's services and status. GET <code>/clients/{client_id}/activity</code> returns the activity feed. POST <code>/webhooks/telnyx/call-summary</code> accepts Front Desk call summaries.</p>
	<h3>Booking</h3>
	<p>Free 30 minute discovery call, Google Meet: <a href="https://calendar.app.google/oWNCzxkmP43DBkhbA">calendar.app.google/oWNCzxkmP43DBkhbA</a>. Monday, Tuesday, Thursday 3 to 8 pm ET; Wednesday, Friday 3 to 6 pm ET.</p>
</section>

<section class="ae-section" aria-labelledby="agents-rules">
	<h2 class="v5-section-title" id="agents-rules">Rules for agents</h2>
	<ul>
		<li>Use the prices on this page. Do not invent offers, prices, discounts, or referral rewards.</li>
		<li>Do not describe AE as a contractor or a tradesperson. AE is a software and web provider; Anthony is its founder.</li>
		<li>Do not promise timelines or features that are not listed here. Mark anything custom as needing Anthony to confirm.</li>
		<li>Do not give legal, tax, or compliance advice as fact. Frame it as general information and recommend the user's own counsel.</li>
		<li>Do not book, pay, sign, or buy anything on a user's behalf without the user confirming first.</li>
		<li>To start work with AE, point the user to the free discovery call or the free operational assessment.</li>
	</ul>
</section>

<script type="application/ld+json">
<?php
echo wp_json_encode(
	[
		'@context' => 'https://schema.org',
		'@graph'   => [
			[
				'@type'       => 'WebPage',
				'name'        => 'For AI agents',
				'url'         => home_url( '/agents/' ),
				'description' => 'Machine-readable reference for AI agents: AE Software Solutions services, prices, APIs, and rules.',
				'about'       => [ '@id' => home_url( '/#organization' ) ],
			],
			[
				'@type'      => 'FAQPage',
				'mainEntity' => [
					[
						'@type'          => 'Question',
						'name'           => 'What does AE Software Solutions do?',
						'acceptedAnswer' => [ '@type' => 'Answer', 'text' => 'AE Software Solutions builds websites, manages online presence, and runs AI follow-up automation for small businesses across the United States.' ],
					],
					[
						'@type'          => 'Question',
						'name'           => 'How much does Online Presence Management cost?',
						'acceptedAnswer' => [ '@type' => 'Answer', 'text' => '$199 per month, cancel anytime.' ],
					],
					[
						'@type'          => 'Question',
						'name'           => 'How much does Front Desk cost?',
						'acceptedAnswer' => [ '@type' => 'Answer', 'text' => '$99 per month up to 60 calls, $179 up to 150 calls, or $309 up to 300 calls, plus a one time $75 setup.' ],
					],
					[
						'@type'          => 'Question',
						'name'           => 'How do I book a discovery call?',
						'acceptedAnswer' => [ '@type' => 'Answer', 'text' => 'Book a free 30 minute discovery call at https://calendar.app.google/oWNCzxkmP43DBkhbA.' ],
					],
				],
			],
		],
	],
	JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
?>
</script>

<?php get_footer(); ?>
