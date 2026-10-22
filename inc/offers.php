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
				'icon'    => 'presence',
				'name'    => 'Presence check',
				'price'   => 'Free',
				'cadence' => '15-minute call',
				'pitch'   => 'We look at what a customer sees when they search for you and tell you the three things to fix first.',
				'points'  => [ 'Google, Maps and website reviewed live', 'No obligation, no sales script' ],
				'cta'     => [ 'Run the check', home_url( '/online-presence/#presence-check' ) ],
			],
			[
				'id'      => 'audit',
				'icon'    => 'audit',
				'name'    => 'Missed-Lead Audit',
				'price'   => '$250',
				'amount'  => 250,
				'cadence' => 'one time · credited to any build',
				'pitch'   => 'Find out exactly where inquiries leak before you spend money fixing anything.',
				'points'  => [
					'Every way a customer can reach you, mapped',
					'Which ones go unanswered after hours',
					'What it is costing you, in your own numbers',
					'Written report in 5 business days',
					'Full $250 credited if you go ahead',
				],
				'cta'     => [ 'Buy the audit', 'https://buy.stripe.com/00wbJ15A4dBQgJl8d3abK03' ],
			],
		],

		// ── Websites (one-time) ─────────────────────────────────────────────
		'websites' => [
			[
				'id'      => 'starter-site',
				'icon'    => 'websites',
				'name'    => 'Starter Site',
				'price'   => 'from $495',
				'amount'  => 495,
				'cadence' => 'one time · 5 to 10 business days',
				'pitch'   => 'A fast, professional site that explains what you do and makes it easy to call or book.',
				'points'  => [
					'5 to 8 custom designed pages',
					'Mobile first, fast loading',
					'Google Business Profile set up and verified',
					'Contact form and click to call',
					'Search basics: titles, descriptions, sitemap',
					'30 days of fixes after launch',
				],
				'cta'     => [ 'Get a quote', $contact ],
			],
			[
				'id'       => 'lead-funnel',
				'icon'    => 'websites',
				'name'     => 'Lead Funnel Site',
				'price'    => 'from $4,500',
				'amount'   => 4500,
				'cadence'  => 'one time · 10 to 18 business days',
				'pitch'    => 'Everything in Starter, built to capture and follow up on every lead automatically.',
				'points'   => [
					'Everything in Starter Site',
					'Conversion focused copywriting',
					'Online booking connected to your calendar',
					'Lead forms routed to your CRM',
					'Automatic email and text follow up',
					'60 days of fixes after launch',
				],
				'featured' => true,
				'cta'      => [ 'Get a quote', $contact ],
			],
			[
				'id'      => 'custom',
				'icon'    => 'websites',
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
				'id'      => 'ai-setup-guides',
				'name'    => 'AI Setup Guides Subscription',
				'price'   => '$19',
				'amount'  => 19,
				'cadence' => 'per month · cancel anytime',
				'pitch'   => 'Step by step guides that show you how to set up AI tools for your business, kept current as the tools change.',
				'points'  => [
					'Every guide in the library, current edition',
					'Guides refreshed as the tools change',
					'New guides included while you subscribe',
				],
				'cta'     => [ 'Subscribe', 'https://buy.stripe.com/5kQ5kDbYs7dsfFheBrabK0a' ],
			],
			[
				'id'      => 'online-presence',
				'icon'    => 'presence',
				'name'    => 'Online Presence Management',
				'price'   => '$49',
				'amount'  => 49,
				'cadence' => 'per month · cancel anytime',
				'pitch'   => 'We keep your Google Business Profile active and accurate, every month.',
				'points'  => [
					'Weekly Google Business Profile posts',
					'A drafted reply to every new review',
					'Listings kept matching on Google, Bing, Apple Maps, Yelp, Facebook',
					'One page monthly report',
				],
				'cta'     => [ 'Start now', 'https://buy.stripe.com/8x25kDfaEfJYdx964VabK02' ],
			],
			[
				'id'       => 'front-desk',
				'icon'    => 'front-desk',
				'name'     => 'Front Desk',
				'price'    => '$99',
				'amount'   => 99,
				'cadence'  => 'per month · cancel anytime',
				'pitch'    => 'Your calls answered while you work. No lead goes cold.',
				'points'   => [
						'AI answers your calls in your business name',
						'Text back on missed calls',
						'Call summary and recording for every call',
						'Booking link sent automatically',
				],
				'featured' => true,
				'cta'      => [ 'Start now', 'https://buy.stripe.com/eVqaEX0fKfJY0KndxnabK04' ],
			],
			[
				'id'        => 'front-desk-always-on',
				'icon'    => 'front-desk',
				'name'      => 'Always-On Front Desk',
				'price'     => '$250',
				'amount'    => 250,
				'cadence'   => 'per month · plus one time setup, quoted after the audit',
				'pitch'     => 'Missed calls answered in seconds, day or night.',
				'points'    => [
						'AI answers your calls in your business name, day or night',
						'Text back on missed calls',
						'Call summary and recording for every call',
						'Booking link sent automatically',
						'Review requests after every job',
				],
				'guarantee' => true,
				'cta'       => [ 'Start now', 'https://buy.stripe.com/6oUeVd9QkapEct52SJabK01' ],
			],
			[
				'id'      => 'social-management',
				'name'    => 'Social Management',
				'price'   => '$799',
				'amount'  => 799,
				'cadence' => 'per month · plus one time $300 setup',
				'pitch'   => 'Your social media, outreach and email handled for you, with a monthly report on the leads it brings in.',
				'points'  => [
					'Monthly content strategy and calendar, approved by you',
					'Posts written, designed and scheduled across your platforms',
					'Community outreach and replies handled for you',
					'One email campaign a month to your list',
					'Monthly report and review call',
				],
				'cta'     => [ 'Start now', 'https://buy.stripe.com/7sY14n9QkapE2Sv2SJabK0b' ],
			],
		],

		// ── Bundle ──────────────────────────────────────────────────────────
		'bundle' => [
			'id'      => 'launch',
			'name'    => 'Launch Package',
			'price'   => '$595',
			'amount'  => 595,
			'was'     => '$642',
			'cadence' => 'one payment',
			'pitch'   => 'A new Starter Site, your Google Business Profile set up properly, and the first three months of Online Presence Management.',
			'points'  => [ 'Starter Site', 'Google Business Profile setup', '3 months of Online Presence Management', 'Then $49/month, cancel anytime' ],
			'cta'     => [ 'Get the Launch Package', $contact ],
		],

		// ── Promises ────────────────────────────────────────────────────────
		'guarantee' => 'If Always-On Front Desk does not capture at least one qualifying lead within 60 days of go live, we refund your first two monthly payments. Conditions apply.',
		'terms'     => 'Website builds: 50% to start, 50% at launch. Monthly plans are billed at signup and are month to month. You own your website, domain and data.',
	];
}

