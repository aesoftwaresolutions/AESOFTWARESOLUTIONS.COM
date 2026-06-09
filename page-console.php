<?php
/**
 * Template Name: Admin Console
 *
 * Admin-only console hosting both internal platforms:
 *   - aeCRM (iframed from configurable URL or /crm/ subdirectory)
 *   - Cash Flow Intelligence (inline <ae-cashflow> web component)
 *
 * Gated to users with `manage_options`. Anyone else is redirected to login.
 */

// ── Access gate ──────────────────────────────────────────────────────────
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( wp_login_url( get_permalink() ) );
	exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

// CRM iframe URL — configurable via WP option, defaults to /crm/
$crm_url = get_option( 'aesoftwares_crm_url', home_url( '/crm/' ) );

get_header();
?>

<div class="console-page">

	<div class="console-hero">
		<div class="console-hero__inner">
			<div class="console-hero__row">
				<div>
					<span class="console-hero__eyebrow">Admin Console</span>
					<h1 class="console-hero__headline">Internal platforms.</h1>
					<p class="console-hero__sub">Both internal tools, hosted on one page. Switch tabs to flip between the CRM and the Cash Flow analyzer.</p>
				</div>
				<div class="console-user">
					<?php echo get_avatar( get_current_user_id(), 40, '', '', [ 'class' => 'console-user__avatar' ] ); ?>
					<div>
						<div class="console-user__name"><?php echo esc_html( wp_get_current_user()->display_name ); ?></div>
						<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="console-user__logout">Sign out</a>
					</div>
				</div>
			</div>

			<div class="console-tabs" role="tablist">
				<button class="console-tab is-active" role="tab" aria-selected="true" data-tab="cashflow">
					<span class="console-tab__icon" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M2 13L6 9L9 11L14 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M10 5H14V9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					Cash Flow
				</button>
				<button class="console-tab" role="tab" aria-selected="false" data-tab="crm">
					<span class="console-tab__icon" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<circle cx="8" cy="5" r="2.5" stroke="currentColor" stroke-width="1.4"/>
							<path d="M3 14C3 11.2 5.2 9 8 9C10.8 9 13 11.2 13 14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
						</svg>
					</span>
					aeCRM
				</button>
				<button class="console-tab" role="tab" aria-selected="false" data-tab="aria">
					<span class="console-tab__icon" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.4"/>
							<path d="M8 5.5V8L9.5 9.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					Aria
				</button>
				<a class="console-tab console-tab--ext" href="<?php echo esc_url( admin_url() ); ?>">
					<span class="console-tab__icon" aria-hidden="true">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
							<path d="M5 2H2V12H12V9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
							<path d="M8 2H12V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M7 7L12 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
						</svg>
					</span>
					WP Admin
				</a>
			</div>
		</div>
	</div>

	<!-- TAB: Cash Flow -->
	<section class="console-panel is-active" id="panel-cashflow" role="tabpanel">
		<div class="console-panel__inner">
			<ae-cashflow></ae-cashflow>
		</div>
	</section>

	<!-- TAB: aeCRM -->
	<section class="console-panel" id="panel-crm" role="tabpanel" hidden>
		<div class="console-panel__inner">
			<div class="console-iframe-wrap">
				<div class="console-iframe-bar">
					<span class="console-iframe-bar__label">aeCRM</span>
					<span class="console-iframe-bar__url"><?php echo esc_html( $crm_url ); ?></span>
					<a href="<?php echo esc_url( $crm_url ); ?>" target="_blank" rel="noopener" class="console-iframe-bar__open">Open in new tab &nearr;</a>
				</div>
				<iframe
					class="console-iframe"
					src="<?php echo esc_url( $crm_url ); ?>"
					title="aeCRM"
					loading="lazy"
					sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-downloads"
				></iframe>
			</div>
		</div>
	</section>

	<!-- TAB: Aria -->
	<section class="console-panel" id="panel-aria" role="tabpanel" hidden>
		<div class="console-panel__inner">
			<!-- ae-aria.js bootstraps here via #panel-aria element -->
		</div>
	</section>

</div>

<script>
/* Console tab switcher — vanilla, lives on the page so no extra enqueue needed */
(function () {
	const tabs = document.querySelectorAll('.console-tab[data-tab]');
	const panels = document.querySelectorAll('.console-panel');
	tabs.forEach(t => {
		t.addEventListener('click', () => {
			const id = t.dataset.tab;
			tabs.forEach(x => {
				const active = x === t;
				x.classList.toggle('is-active', active);
				x.setAttribute('aria-selected', active ? 'true' : 'false');
			});
			panels.forEach(p => {
				const active = p.id === 'panel-' + id;
				p.classList.toggle('is-active', active);
				p.hidden = !active;
			});
		});
	});
})();
</script>

<?php get_footer(); ?>
