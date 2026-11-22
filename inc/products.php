<?php
/**
 * The 17 product pages, grouped, for the Services page catalog and the
 * desktop Services dropdown in the header. One list, so both stay in step.
 *
 * Names, lines and prices are taken from each product page template
 * (page-SLUG.php: name, description, first price). Change a price on the
 * product page and here together. Slugs match the live WordPress pages;
 * Online Presence Management lives at /online-presence/.
 *
 * @package AE_Softwares
 */

if ( ! function_exists( 'aesoftwares_products' ) ) {
	function aesoftwares_products() {
		return [
			[
				'title' => 'Calls, messages and orders',
				'items' => [
					[ 'slug' => 'front-desk',           'name' => 'Front Desk',           'price' => 'from $99/mo',  'line' => 'AI phone answering that works with your existing business number and captures every caller’s details.' ],
					[ 'slug' => 'always-on-front-desk', 'name' => 'Always-On Front Desk', 'price' => '$250/mo',      'line' => 'Round the clock call coverage that captures every after hours and weekend lead automatically.' ],
					[ 'slug' => 'social-front-desk',    'name' => 'Social Front Desk',    'price' => '$199/mo',      'line' => 'AI handling of your incoming Facebook and Instagram messages and post comments so no customer inquiry sits unanswered.' ],
					[ 'slug' => 'order-desk',           'name' => 'Order Desk',           'price' => 'from $149/mo', 'line' => 'A managed AI back office for small B2B sellers, combining an Order Entry Agent and Pricing Agent on a simple month to month plan.' ],
				],
			],
			[
				'title' => 'Presence and social',
				'items' => [
					[ 'slug' => 'online-presence',   'name' => 'Online Presence Management', 'price' => '$199/mo',      'line' => 'Ongoing management of your Google Business Profile, reviews, and local listings across key platforms.' ],
					[ 'slug' => 'total-presence',    'name' => 'Total Presence',             'price' => '$229/mo',      'line' => 'Social Front Desk plus ongoing Google Business Profile management together, so local searches and social inquiries are both covered.' ],
					[ 'slug' => 'social-management', 'name' => 'Social Management',          'price' => 'from $799/mo', 'line' => 'Full social media management covering content creation, scheduling, engagement, and plain spoken monthly reporting.' ],
					[ 'slug' => 'creator-packages',  'name' => 'Creator Packages',           'price' => 'from $499/mo', 'line' => 'Dedicated content production, channel growth, and authority building packages for founders, advisors, and independent creators.' ],
				],
			],
			[
				'title' => 'Websites and packages',
				'items' => [
					[ 'slug' => 'websites',                 'name' => 'Websites',                   'price' => 'from $495',     'line' => 'Custom websites for small businesses, from a clean starter presence to a full digital operations platform.' ],
					[ 'slug' => 'launch-package',           'name' => 'Launch Package',             'price' => '$595 one time', 'line' => 'Essential technical launch setup, core local listings, and a verified go live checklist for a new site.' ],
					[ 'slug' => 'business-starter-package', 'name' => 'Business Starter Package',   'price' => '$50/mo',        'line' => 'An essential operational foundation combining aeCRM, invoicing, and 50 state compliance guides for small regulated teams.' ],
					[ 'slug' => 'branded-templates',        'name' => 'Branded Document Templates', 'price' => '$295 one time', 'line' => 'Custom designed, reusable document templates tailored to your brand for every piece of client facing paperwork.' ],
				],
			],
			[
				'title' => 'Audits and assessments',
				'items' => [
					[ 'slug' => 'missed-lead-audit',   'name' => 'Missed-Lead Audit',           'price' => '$250 one time', 'line' => 'A thorough review of your missed calls, follow up speed, and lead handling, delivered as a plain written report with concrete next steps.' ],
					[ 'slug' => 'free-assessment',     'name' => 'Free Operational Assessment', 'price' => 'Free',          'line' => 'A 10 phase AI guided discovery interview that delivers a risk report, compliance register, automation ROI matrix, and roadmap, plus a free 30 minute discovery call.' ],
					[ 'slug' => 'ai-tools-assessment', 'name' => 'AI Tools Assessment',         'price' => '$999 one time', 'line' => 'A comprehensive audit of the AI software your team uses or plans to adopt, with plain spoken recommendations on what to keep, cut, or fix. Offered only after a completed Missed-Lead Audit.' ],
				],
			],
			[
				'title' => 'Guides',
				'items' => [
					[ 'slug' => 'ai-setup-guides',   'name' => 'AI Setup Guides',       'price' => '$19/mo', 'line' => 'A growing library of plain spoken, step by step AI setup guides, with new guides added regularly.' ],
					[ 'slug' => 'contractor-guides', 'name' => 'Contractor PDF Guides', 'price' => 'Free',   'line' => 'Downloadable PDF guides for trade and home service contractors, available free with our newsletter signup.' ],
				],
			],
		];
	}
}
