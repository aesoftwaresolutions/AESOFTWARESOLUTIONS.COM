<?php
/**
 * AE Software Solutions — the offer lineup, in one place.
 *
 * Change a price or a bullet here and it updates the Pricing page, the
 * Services page and the structured data Google reads. Nothing else needs
 * editing.
 *
 * @package AE_Softwares
 */

function aesoftwares_offers() {
	$contact = home_url( '/contact/' );

	return [
		// ── Start here ──────────────────────────────────────────────────────
		'start' => [
			[
				'id'      => 'presence-check',
				'name'    => 'Presence check',
				'price'   => 'Free',
				'cadence' => '15-minute call',
				'pitch'   => 'We look at what a customer sees when they search for you and tell you the three things to fix first.',
				'points'  => [ 'Google, Maps and website reviewed live', 'No obligation, no sales script' ],
				'cta'     => [ 'Run the check', home_url( '/online-presence/#presence-check' ) ],
			],
			[
				'id'      => 'audit',
				'name'    => 'Missed-Lead Audit',
				'price'   => '$250',
				'amount'  => 250,
				'cadence' => 'one-time · credited to any build',
				'pitch'   => 'Find out exactly where inquiries leak before you spend money fixing anything.',
				'points'  => [
					'Every way a customer can reach you, mapped',
					'Which ones go unanswered after hours',
					'What it is costing you, in your own numbers',
					'Written report in 5 business days',
					'Full $250 credited if you go ahead',
				],
				'cta'     => [ 'Book the audit', $contact ],
			],
		],

		// ── Websites (one-time) ─────────────────────────────────────────────
		'websites' => [
			[
				'id'      => 'starter-site',
				'name'    => 'Starter Site',
				'price'   => 'from $1,500',
				'amount'  => 1500,
				'cadence' => 'one-time · 5–10 business days',
				'pitch'   => 'A fast, professional site that explains what you do and makes it easy to call or book.',
				'points'  => [
					'5–8 custom-designed pages',
					'Mobile-first, fast loading',
					'Google Business Profile set up and verified',
					'Contact form and click-to-call',
					'Search basics: titles, descriptions, sitemap',
					'30 days of fixes after launch',
				],
				'cta'     => [ 'Get a quote', $contact ],
			],
			[
				'id'       => 'lead-funnel',
				'name'     => 'Lead Funnel Site',
				'price'    => 'from $4,500',
				'amount'   => 4500,
				'cadence'  => 'one-time · 10–18 business days',
				'pitch'    => 'Everything in Starter, built to capture and follow up on every lead automatically.',
				'points'   => [
					'Everything in Starter Site',
					'Conversion-focused copywriting',
					'Online booking connected to your calendar',
					'Lead forms routed to your CRM',
					'Automatic email/text follow-up',
					'60 days of fixes after launch',
				],
				'featured' => true,
				'cta'      => [ 'Get a quote', $contact ],
			],
			[
				'id'      => 'custom',
				'name'    => 'Custom Platform',
				'price'   => 'Quoted',
				'cadence' => 'after a scoping call',
				'pitch'   => 'Client portals, custom dashboards and integrations with the tools you already use.',
				'points'  => [
					'Connects to your existing software',
					'Client or staff portal',
					'Custom reporting',
					'90 days of support and training',
				],
				'cta'     => [ 'Start a conversation', $contact ],
			],
		],

		// ── Monthly ─────────────────────────────────────────────────────────
		'monthly' => [
			[
				'id'       => 'presence-care',
				'name'     => 'Presence Care',
				'price'    => '$199',
				'amount'   => 199,
				'cadence'  => 'per month · cancel anytime',
				'pitch'    => 'We look after everything people see before they call you, every month.',
				'points'   => [
					'Website hosting, updates, backups and small edits',
					'Weekly Google Business Profile posts',
					'A drafted reply to every new review',
					'Listings kept matching on Google, Bing, Apple Maps, Yelp, Facebook',
					'One-page monthly report',
				],
				'featured' => true,
				'cta'      => [ 'Start Presence Care', $contact ],
			],
			[
				'id'      => 'front-desk',
				'name'    => 'Always-On Front Desk',
				'price'   => '$250',
				'amount'  => 250,
				'cadence' => 'per month · plus one-time setup, quoted after the audit',
				'pitch'   => 'Missed calls, after-hours forms and new inquiries answered in seconds, day or night.',
				'points'  => [
					'Missed-call text-back',
					'Instant reply to web and Facebook inquiries',
					'Leads saved to a simple CRM',
					'Booking link sent automatically',
					'Review requests after every job',
				],
				'guarantee' => true,
				'cta'     => [ 'Ask about Front Desk', $contact ],
			],
		],

		// ── Bundle ──────────────────────────────────────────────────────────
		'bundle' => [
			'id'      => 'launch',
			'name'    => 'Launch Package',
			'price'   => '$1,950',
			'amount'  => 1950,
			'was'     => '$2,097',
			'cadence' => 'one payment',
			'pitch'   => 'A new Starter Site, your Google Business Profile set up properly, and the first three months of Presence Care.',
			'points'  => [ 'Starter Site', 'Google Business Profile setup', '3 months of Presence Care', 'Then $199/month, cancel anytime' ],
			'cta'     => [ 'Book the Launch Package', $contact ],
		],

		// ── Promises ────────────────────────────────────────────────────────
		'guarantee' => 'If Always-On Front Desk does not capture at least one lead you would otherwise have missed within 60 days of launch, we refund every monthly payment.',
		'terms'     => 'Website builds: 50% to start, 50% at launch. Monthly plans bill from launch day and are month to month. You own your website, domain and data.',
	];
}
