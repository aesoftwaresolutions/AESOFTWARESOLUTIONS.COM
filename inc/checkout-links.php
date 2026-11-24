<?php
/**
 * Checkout links: the one place every "Start now" / "Buy" button gets its URL.
 *
 * We are moving checkout from the Hostinger shop (shop.aesoftwaresolutions.com)
 * to Stripe Payment Links, so customers stay on aesoftwaresolutions.com until
 * the secure Stripe payment page.
 *
 * For each product:
 *   'stripe' => the Stripe Payment Link (buy.stripe.com/...). Used when set.
 *   'shop'   => the old Hostinger shop page. Used only while 'stripe' is empty,
 *               so no button breaks before its Stripe link exists. Newer
  *               products have no shop page and omit 'shop'.
 *
 * Every product now has a Stripe link (Stripe account acct_1TOO79Epan91NQaR,
 * checked Nov 23 2026: each link's recurring price and one time setup fee
 * match the prices on the site). The 'shop' entries are kept only as a
 * rollback: empty a 'stripe' value to send that button back to the shop.
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
 * @return array<string, array{stripe: string, shop?: string}>
 */
function ae_checkout_links() {
	$shop = 'https://shop.aesoftwaresolutions.com';
	return [
		// Calls.
		'front-desk'                   => [ 'stripe' => 'https://buy.stripe.com/fZu8wP8Mg0P4eBd8d3abK0q', 'shop' => $shop . '/plans/front-desk' ],
		'always-on-front-desk'         => [ 'stripe' => 'https://buy.stripe.com/6oUeVd9QkapEct52SJabK01', 'shop' => $shop . '/plans/always-on-front-desk' ],
		// Front Desk tiers: the $75 setup is in the Stripe link's first payment.
		'front-desk-150'               => [ 'stripe' => 'https://buy.stripe.com/cNiaEX7IceFUct53WNabK0t' ],
		'front-desk-300'               => [ 'stripe' => 'https://buy.stripe.com/aFa28r6E82Xc78LbpfabK0u' ],
		// Social media for businesses.
		'online-presence-management'   => [ 'stripe' => 'https://buy.stripe.com/5kQcN50fK55keBdgJzabK0r' ],
		'total-presence'               => [ 'stripe' => 'https://buy.stripe.com/3cI5kD2nScxMakXfFvabK0s' ],
		'social-front-desk'            => [ 'stripe' => 'https://buy.stripe.com/7sYbJ1d2w2XcakXfFvabK0h', 'shop' => $shop . '/plans/social-front-desk' ],
		'social-management'            => [ 'stripe' => 'https://buy.stripe.com/7sY14n9QkapE2Sv2SJabK0b', 'shop' => $shop . '/plans/social-management' ],
		'social-management-growth'     => [ 'stripe' => 'https://buy.stripe.com/3cIfZh3rWcxMakXalbabK0i', 'shop' => $shop . '/plans/social-management-growth' ],
		'social-management-scale'      => [ 'stripe' => 'https://buy.stripe.com/8x2fZhaUoapEdx9gJzabK0j', 'shop' => $shop . '/plans/social-management-scale' ],
		// Social media for creators.
		'creator-launch'               => [ 'stripe' => 'https://buy.stripe.com/eVq28r4w0gO2gJlctjabK0k', 'shop' => $shop . '/plans/creator-launch' ],
		'creator-growth'               => [ 'stripe' => 'https://buy.stripe.com/6oUaEX5A48hwct578ZabK0l', 'shop' => $shop . '/plans/creator-growth' ],
		'creator-authority'            => [ 'stripe' => 'https://buy.stripe.com/7sY28r9QkdBQ2Sv2SJabK0m', 'shop' => $shop . '/plans/creator-authority' ],
		// One time and small subscriptions.
		'business-starter-package'     => [ 'stripe' => 'https://buy.stripe.com/dRm14n3rW7dseBd1OFabK0v' ],
		'missed-lead-audit'            => [ 'stripe' => 'https://buy.stripe.com/00wbJ15A4dBQgJl8d3abK03', 'shop' => $shop . '/product/missed-lead-audit' ],
		'ai-setup-guides-subscription' => [ 'stripe' => 'https://buy.stripe.com/5kQ5kDbYs7dsfFheBrabK0a', 'shop' => $shop . '/plans/ai-setup-guides-subscription' ],
	];
}

/**
 * Checkout URL for a product: its Stripe link when set, else its shop page.
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
	if ( '' !== $links[ $slug ]['stripe'] ) {
		return $links[ $slug ]['stripe'];
}
	return $links[ $slug ]['shop'] ?? '#';
}
