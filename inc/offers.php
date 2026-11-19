<?php
/**
 * AE Software Solutions: the offer lineup, in one place.
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
		// Start here
		'start' => [
			[
				'id'      => 'presence-check',
				'icon'    => 'presence',
				'name'    => 'Presence check',
				'price'   => 'Free',
				'cadence' => '15 minute call',
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
					'Written report with what we found',
					'Full $250 credited if you go ahead',
					'Add on: AI Tools Assessment, $999 one time, offered only with the Missed-Lead Audit',
				],
				'cta'     => [ 'Buy the audit', 'https://buy.stripe.com/00wbJ15A4dBQgJl8d3abK03' ],
			],
		],

		// Websites (one time)
		'websites' => [
			[
				'id'      => 'starter-site',
				'icon'    => 'websites',
				'name'    => 'Starter Site',
				'price'   => 'from $495',
				'amount'  => 495,
				'cadence' => 'one time',
				'pitch'   => 'A fast, professional site that explains what you do and makes it easy to call or book.',
				'points'  => [
					'5 to 8 custom designed pages',
					'Mobile first, fast loading',
					'Google Business Profile set up and verified',
					'Contact form and click to call',
					'Search basics: titles, descriptions, sitemap',
				],
				'cta'     => [ 'Get a quote', $contact ],
			],
			[
				'id'       => 'lead-funnel',
				'icon'    => 'websites',
				'name'     => 'Lead Funnel Site',
				'price'    => 'from $4,500',
				'amount'   => 4500,
				'cadence'  => 'one time',
				'pitch'    => 'Everything in Starter, built to capture leads and follow up with them automatically.',
				'points'   => [
					'Everything in Starter Site',
					'Copywriting focused on calls and bookings',
					'Online booking connected to your calendar',
					'Lead forms routed to your CRM',
					'Automatic email and text follow up',
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
					'Support and training agreed in the scope',
				],
				'cta'     => [ 'Start a conversation', $contact ],
			],
		],

		// Monthly
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
				'price'   => '$199',
				'amount'  => 199,
				'cadence' => 'per month · cancel anytime',
				'pitch'   => 'We keep your Google Business Profile active and accurate, every month.',
				'points'  => [
					'Weekly Google Business Profile posts',
					'A drafted reply to every new review',
					'Listings kept matching on Google, Bing, Apple Maps, Yelp, Facebook',
					'One page monthly report',
				],
				'cta'     => [ 'Get started', $contact ], // The old Stripe link charges $49. Swap in a $199 link when one exists.
			],
			[
				'id'       => 'front-desk',
				'icon'    => 'front-desk',
				'name'     => 'Front Desk',
				'price'    => '$99',
				'amount'   => 99,
				'cadence'  => 'per month · plus one time $75 setup',
				'pitch'    => 'Your calls answered while you work. No lead goes cold.',
				'points'   => [
						'AI answers your calls in your business name',
						'Text back on missed calls',
						'Call summary and recording for every call',
						'Booking link sent automatically',
				],
				'featured' => true,
				'cta'      => [ 'Start now', 'https://arctic-powderblue-ibex-73.hostingersite.com/plans/front-desk' ],
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
				'cta'       => [ 'Start now', 'https://arctic-powderblue-ibex-73.hostingersite.com/plans/always-on-front-desk' ],
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
			[
				'id'      => 'social-front-desk',
				'name'    => 'Social Front Desk',
				'price'   => '$199',
				'amount'  => 199,
				'cadence' => 'per month · plus one time $75 setup',
				'pitch'   => 'Posting handled for you with light planning, so your business shows up consistently.',
				'points'  => [
					'Founding rate: $149 a month for the first 3 clients, for 3 months, then $199 a month',
				],
				'cta'     => [ 'Start now', 'https://buy.stripe.com/7sYbJ1d2w2XcakXfFvabK0h' ],
			],
			[
				'id'      => 'total-presence',
				'icon'    => 'presence',
				'name'    => 'Total Presence',
				'price'   => '$229',
				'amount'  => 229,
				'cadence' => 'per month · social plus Google Business Profile management',
				'pitch'   => 'Social Front Desk and Google Business Profile management in one plan.',
				'points'  => [
					'Social Front Desk posting',
					'Google Business Profile management',
				],
				'cta'     => [ 'Get started', $contact ],
			],
		],

		// Extras (one time)
		'extras' => [
			[
				'id'      => 'branded-templates',
				'name'    => 'Branded Document Templates',
				'price'   => '$295',
				'amount'  => 295,
				'cadence' => 'one time',
				'pitch'   => 'Branded templates for the documents your business sends out.',
				'points'  => [],
				'cta'     => [ 'Ask about templates', $contact ],
			],
			[
				'id'      => 'annual-prepay',
				'name'    => 'Annual prepay',
				'price'   => '$1,990',
				'amount'  => 1990,
				'cadence' => 'per year',
				'pitch'   => 'Prefer to pay once for the year instead of monthly? Ask us which plan it applies to.',
				'points'  => [],
				'cta'     => [ 'Ask about annual prepay', $contact ],
			],
		],

		// Bundle and extras
		'bundle' => [
			'id'      => 'launch',
			'name'    => 'Launch Package',
			'price'   => '$595',
			'amount'  => 595,
			'cadence' => 'one payment',
			'pitch'   => 'A new Starter Site, your Google Business Profile set up properly, and the first three months of Online Presence Management.',
			'points'  => [ 'Starter Site', 'Google Business Profile setup', '3 months of Online Presence Management', 'Then $199/month, cancel anytime' ],
			'cta'     => [ 'Get the Launch Package', $contact ],
		],

		// Promises
		'guarantee' => 'If Always-On Front Desk does not capture at least one qualifying lead within 60 days of go live, we refund your first two monthly payments. Conditions apply.',
		'terms'     => 'Website builds: 50% to start, 50% at launch. Monthly plans are billed at signup and are month to month. You own your website, domain and data.',
	];
}

