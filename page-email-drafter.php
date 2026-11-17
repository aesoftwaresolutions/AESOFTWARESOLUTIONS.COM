<?php /* Template Name: Email Drafter */ get_header(); ?>

<div class="page-hero rewriter-hero">
	<div class="page-hero__eyebrow"><a href="<?php echo esc_url( home_url( '/free-tools/' ) ); ?>" class="hero-back-link">&larr; Free Tools</a></div>
	<h1 class="page-hero__headline">AI Email<br><em>Drafter</em></h1>
	<p class="page-hero__subtitle">Describe what you need to say and get a complete email draft to review and send. Professional, friendly, persuasive, or follow up.</p>
</div>

<section class="rewriter-section">
	<div class="rewriter-wrap">

		<div class="rewriter-modes">
			<span class="rewriter-modes__label">Tone</span>
			<div class="rewriter-modes__btns">
				<button class="rewriter-mode-btn active" data-mode="professional">Professional</button>
				<button class="rewriter-mode-btn" data-mode="friendly">Friendly</button>
				<button class="rewriter-mode-btn" data-mode="persuasive">Persuasive</button>
				<button class="rewriter-mode-btn" data-mode="followup">Follow up</button>
			</div>
		</div>

		<div class="rewriter-panels">

			<div class="rewriter-panel rewriter-panel--input">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">What do you need to say?</span>
					<span class="rewriter-panel__count"><span id="rewriter-input-count">0</span> words</span>
				</div>
				<textarea
					id="rewriter-input"
					class="rewriter-textarea"
					placeholder="Describe the email you need, or use Try an example."
					rows="14"
					spellcheck="false"
				></textarea>
				<div class="rewriter-panel__footer">
					<button id="rewriter-clear" class="rewriter-btn rewriter-btn--ghost">Clear</button>
					<button id="rewriter-submit" class="rewriter-btn rewriter-btn--primary">
						<span class="rewriter-btn__text">Draft email</span>
						<span class="rewriter-btn__arrow">→</span>
					</button>
				</div>
			</div>

			<div class="rewriter-panel rewriter-panel--output" id="rewriter-output-panel">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">Your Email</span>
					<span class="rewriter-panel__count"><span id="rewriter-output-count">0</span> words</span>
				</div>
				<div
					id="rewriter-output"
					class="rewriter-output"
					aria-live="polite"
				><span class="rewriter-output__placeholder">Your drafted email will appear here.</span></div>
				<div id="rewriter-loader" class="rewriter-loader" hidden>
					<div class="rewriter-loader__spinner"></div>
					<span>Drafting…</span>
				</div>
				<div id="rewriter-error" class="rewriter-error" hidden></div>
				<div class="rewriter-panel__footer">
					<button id="rewriter-copy" class="rewriter-btn rewriter-btn--ghost" disabled>Copy</button>
				</div>
			</div>

		</div>

	</div>
</section>

<section class="rewriter-how">
	<div class="rewriter-how__inner">
		<span class="section-eyebrow">How it works</span>
		<h2 class="section-title">Say less. Send more.</h2>
		<div class="rewriter-how__grid">
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">01</div>
				<h3 class="rewriter-how__title">Describe your intent</h3>
				<p class="rewriter-how__desc">Write a quick summary of what you need to communicate, who it's to, what you're asking, and any key details. No need for full sentences.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">02</div>
				<h3 class="rewriter-how__title">Choose a tone</h3>
				<p class="rewriter-how__desc">Professional for client facing emails. Friendly for warm outreach. Persuasive for sales. Follow up for chasing a response without being annoying.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">03</div>
				<h3 class="rewriter-how__title">Copy and send</h3>
				<p class="rewriter-how__desc">Get a complete email with a subject line, greeting, body, and sign off, ready to paste into your inbox. Edit any detail you like.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
