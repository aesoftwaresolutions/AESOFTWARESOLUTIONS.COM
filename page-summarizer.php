<?php /* Template Name: Summarizer */ get_header(); ?>

<div class="page-hero rewriter-hero">
	<div class="page-hero__eyebrow"><a href="<?php echo esc_url( home_url( '/free-tools/' ) ); ?>" class="hero-back-link">&larr; Free Tools</a></div>
	<h1 class="page-hero__headline">AI Text<br><em>Summarizer</em></h1>
	<p class="page-hero__subtitle">Paste any article, report, or document and get a clean summary in the length that suits you.</p>
</div>

<section class="rewriter-section">
	<div class="rewriter-wrap">

		<div class="rewriter-modes">
			<span class="rewriter-modes__label">Summary length</span>
			<div class="rewriter-modes__btns">
				<button class="rewriter-mode-btn active" data-mode="brief">Brief (bullets)</button>
				<button class="rewriter-mode-btn" data-mode="standard">Standard</button>
				<button class="rewriter-mode-btn" data-mode="detailed">Detailed</button>
			</div>
		</div>

		<div class="rewriter-panels">

			<div class="rewriter-panel rewriter-panel--input">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">Text to summarise</span>
					<span class="rewriter-panel__count"><span id="rewriter-input-count">0</span> words</span>
				</div>
				<textarea
					id="rewriter-input"
					class="rewriter-textarea"
					placeholder="Paste your article, report, or any long text here, or use Try an example."
					rows="14"
					spellcheck="false"
				></textarea>
				<div class="rewriter-panel__footer">
					<button id="rewriter-clear" class="rewriter-btn rewriter-btn--ghost">Clear</button>
					<button id="rewriter-submit" class="rewriter-btn rewriter-btn--primary">
						<span class="rewriter-btn__text">Summarise</span>
						<span class="rewriter-btn__arrow">→</span>
					</button>
				</div>
			</div>

			<div class="rewriter-panel rewriter-panel--output" id="rewriter-output-panel">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">Summary</span>
					<span class="rewriter-panel__count"><span id="rewriter-output-count">0</span> words</span>
				</div>
				<div
					id="rewriter-output"
					class="rewriter-output rewriter-output--pre"
					aria-live="polite"
				><span class="rewriter-output__placeholder">Your summary will appear here.</span></div>
				<div id="rewriter-loader" class="rewriter-loader" hidden>
					<div class="rewriter-loader__spinner"></div>
					<span>Summarising…</span>
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
		<h2 class="section-title">Less reading.<br>Same insight.</h2>
		<div class="rewriter-how__grid">
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">01</div>
				<h3 class="rewriter-how__title">Brief (bullets)</h3>
				<p class="rewriter-how__desc">3 to 5 bullet points covering the most important information. Perfect for quick scanning or sharing a headline overview with your team.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">02</div>
				<h3 class="rewriter-how__title">Standard</h3>
				<p class="rewriter-how__desc">A single concise paragraph that captures the core argument or narrative, useful for executive summaries, briefing notes, or research triage.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">03</div>
				<h3 class="rewriter-how__title">Detailed</h3>
				<p class="rewriter-how__desc">A structured breakdown with key points, supporting details, and main conclusions, ideal for long reports, academic papers, or complex documents.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
