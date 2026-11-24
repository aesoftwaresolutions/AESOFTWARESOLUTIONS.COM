<?php
/**
 * Checkout links: the one place every "Start now" / "Buy" button gets its URL.
 *
 * Each product has a Stripe Payment Link (buy.stripe.com/...).
 *
 * Every product now has a Stripe link (Stripe account acct_1TOO79Epan91NQaR,
 * checked Nov 23 2026: each link's recurring price and one time setup fee
 * match the prices on the site).
 * To change a price, make a new Payment Link in Stripe and paste it here.
 *
 * Prices and terms live on the pages and in Stripe, never here.
 *
 * @package AE_Softwares
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product slug => checkout targets.
 *
 * @return array<string, array{stripe: string}>
 */
function ae_checkout_links() {
	return [
		// Calls.
		'front-desk'                   => [ 'stripe' => 'https://buy.stripe.com/fZu8wP8Mg0P4eBd8d3abK0q' ],
		'always-on-front-desk'         => [ 'stripe' => 'https://buy.stripe.com/6oUeVd9QkapEct52SJabK01' ],
		// Front Desk tiers: the $75 setup is in the Stripe link's first payment.
		'front-desk-150'               => [ 'stripe' => 'https://buy.stripe.com/cNiaEX7IceFUct53WNabK0t' ],
		'front-desk-300'               => [ 'stripe' => 'https://buy.stripe.com/aFa28r6E82Xc78LbpfabK0u' ],
		// Social media for businesses.
		'online-presence-management'   => [ 'stripe' => 'https://buy.stripe.com/5kQcN50fK55keBdgJzabK0r' ],
		'total-presence'               => [ 'stripe' => 'https://buy.stripe.com/3cI5kD2nScxMakXfFvabK0s' ],
		'social-front-desk'            => [ 'stripe' => 'https://buy.stripe.com/7sYbJ1d2w2XcakXfFvabK0h' ],
		'social-management'            => [ 'stripe' => 'https://buy.stripe.com/7sY14n9QkapE2Sv2SJabK0b' ],
		'social-management-growth'     => [ 'stripe' => 'https://buy.stripe.com/3cIfZh3rWcxMakXalbabK0i' ],
		'social-management-scale'      => [ 'stripe' => 'https://buy.stripe.com/8x2fZhaUoapEdx9gJzabK0j' ],
		// Social media for creators.
		'creator-launch'               => [ 'stripe' => 'https://buy.stripe.com/eVq28r4w0gO2gJlctjabK0k' ],
		'creator-growth'               => [ 'stripe' => 'https://buy.stripe.com/6oUaEX5A48hwct578ZabK0l' ],
		'creator-authority'            => [ 'stripe' => 'https://buy.stripe.com/7sY28r9QkdBQ2Sv2SJabK0m' ],
		// One time and small subscriptions.
		'business-starter-package'     => [ 'stripe' => 'https://buy.stripe.com/dRm14n3rW7dseBd1OFabK0v' ],
		'missed-lead-audit'            => [ 'stripe' => 'https://buy.stripe.com/00wbJ15A4dBQgJl8d3abK03' ],
		'ai-setup-guides-subscription' => [ 'stripe' => 'https://buy.stripe.com/5kQ5kDbYs7dsfFheBrabK0a' ],
	];
}

/**
 * Checkout URL for a product: its Stripe link.
 * An unknown slug goes to the contact page rather than a dead link.
 *
 * @param string $slug Product slug from ae_checkout_links().
 * @return string Unescaped URL. Escape with esc_url() when printing.
 */
function ae_checkout_url( $slug ) {
	$links = ae_checkout_links();
	if ( ! isset( $links[ $slug ] ) ) {
		return home_url( '/contact/' );
	}
	return $links[ $slug ]['stripe'];
}
