<?php /* Template Name: Marketplace */
/**
 * Private Facebook Marketplace scraper tool.
 * Requires WordPress admin login — non-admins are redirected to wp-login.php.
 */
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
	auth_redirect();
}
get_header();
?>

<div class="page-hero marketplace-hero">
	<div class="page-hero__eyebrow">Private Tool &mdash; Admin Only</div>
	<h1 class="page-hero__headline">Facebook<br><em>Marketplace</em></h1>
	<p class="page-hero__subtitle">Search Facebook Marketplace listings by city, keyword, and price — scraped live from the source via a headless browser on the VPS.</p>
</div>

<section class="marketplace-section">
	<div class="marketplace-wrap">

		<form id="marketplace-form" class="marketplace-form" novalidate>
			<?php wp_nonce_field( 'ae_marketplace_search', 'ae_marketplace_nonce' ); ?>

			<div class="marketplace-form__row">

				<div class="marketplace-form__field">
					<label for="marketplace-city" class="marketplace-label">City</label>
					<select id="marketplace-city" name="city" class="marketplace-select">
						<option value="new york">New York</option>
						<option value="los angeles">Los Angeles</option>
						<option value="chicago">Chicago</option>
						<option value="houston">Houston</option>
						<option value="phoenix">Phoenix</option>
						<option value="philadelphia">Philadelphia</option>
						<option value="san antonio">San Antonio</option>
						<option value="san diego">San Diego</option>
						<option value="dallas">Dallas</option>
						<option value="san jose">San Jose</option>
						<option value="austin">Austin</option>
						<option value="jacksonville">Jacksonville</option>
						<option value="fort worth">Fort Worth</option>
						<option value="columbus">Columbus</option>
						<option value="charlotte">Charlotte</option>
						<option value="san francisco">San Francisco</option>
						<option value="indianapolis">Indianapolis</option>
						<option value="seattle">Seattle</option>
						<option value="denver">Denver</option>
						<option value="washington">Washington DC</option>
						<option value="nashville">Nashville</option>
						<option value="oklahoma city">Oklahoma City</option>
						<option value="el paso">El Paso</option>
						<option value="boston">Boston</option>
						<option value="portland">Portland</option>
						<option value="las vegas">Las Vegas</option>
						<option value="memphis">Memphis</option>
						<option value="louisville">Louisville</option>
						<option value="baltimore">Baltimore</option>
						<option value="milwaukee">Milwaukee</option>
						<option value="albuquerque">Albuquerque</option>
						<option value="tucson">Tucson</option>
						<option value="fresno">Fresno</option>
						<option value="sacramento">Sacramento</option>
						<option value="mesa">Mesa</option>
						<option value="kansas city">Kansas City</option>
						<option value="atlanta">Atlanta</option>
						<option value="omaha">Omaha</option>
						<option value="colorado springs">Colorado Springs</option>
						<option value="raleigh">Raleigh</option>
						<option value="long beach">Long Beach</option>
						<option value="virginia beach">Virginia Beach</option>
						<option value="minneapolis">Minneapolis</option>
						<option value="tampa">Tampa</option>
					</select>
				</div>

				<div class="marketplace-form__field marketplace-form__field--grow">
					<label for="marketplace-query" class="marketplace-label">Search keyword</label>
					<input
						type="text"
						id="marketplace-query"
						name="query"
						class="marketplace-input"
						placeholder="e.g. iPhone 15, leather sofa, Toyota Camry…"
						autocomplete="off"
						required
					>
				</div>

				<div class="marketplace-form__field marketplace-form__field--price">
					<label for="marketplace-price" class="marketplace-label">Max price ($)</label>
					<input
						type="number"
						id="marketplace-price"
						name="max_price"
						class="marketplace-input"
						placeholder="Any"
						min="0"
						step="1"
					>
				</div>

			</div><!-- .marketplace-form__row -->

			<div class="marketplace-form__actions">
				<button type="submit" id="marketplace-submit" class="rewriter-btn rewriter-btn--primary">
					<span class="rewriter-btn__text">Search Marketplace</span>
					<span class="rewriter-btn__arrow">→</span>
				</button>
			</div>

		</form><!-- #marketplace-form -->

		<div id="marketplace-loader" class="rewriter-loader" hidden>
			<div class="rewriter-loader__spinner"></div>
			<span>Scraping listings&hellip; this can take 15&ndash;30 seconds</span>
		</div>

		<div id="marketplace-error" class="rewriter-error" hidden></div>

		<div id="marketplace-meta" class="marketplace-meta" hidden>
			<span id="marketplace-count"></span>
			<span class="marketplace-meta__sep">&mdash;</span>
			<span id="marketplace-query-echo"></span>
		</div>

		<div id="marketplace-results" class="marketplace-results" aria-live="polite"></div>

	</div><!-- .marketplace-wrap -->
</section>

<?php get_footer(); ?>
