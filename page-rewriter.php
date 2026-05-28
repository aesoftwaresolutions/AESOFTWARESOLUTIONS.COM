<?php /* Template Name: Rewriter */ get_header(); ?>

<div class="page-hero rewriter-hero">
	<div class="page-hero__eyebrow">Free Tool</div>
	<h1 class="page-hero__headline">AI Content<br><em>Rewriter</em></h1>
	<p class="page-hero__subtitle">Paste any text and get a rewritten version that reads naturally — built to pass AI detection tools like CopyLeaks.</p>
</div>

<section class="rewriter-section">
	<div class="rewriter-wrap">

		<div class="rewriter-modes">
			<span class="rewriter-modes__label">Rewrite mode</span>
			<div class="rewriter-modes__btns">
				<button class="rewriter-mode-btn active" data-mode="standard">Standard</button>
				<button class="rewriter-mode-btn" data-mode="academic">Academic</button>
				<button class="rewriter-mode-btn" data-mode="casual">Casual</button>
			</div>
		</div>

		<div class="rewriter-panels">

			<div class="rewriter-panel rewriter-panel--input">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">Original Text</span>
					<span class="rewriter-panel__count"><span id="rewriter-input-count">0</span> words</span>
				</div>
				<textarea
					id="rewriter-input"
					class="rewriter-textarea"
					placeholder="Paste your AI-generated or existing text here…"
					rows="14"
					spellcheck="false"
				></textarea>
				<div class="rewriter-panel__footer">
					<button id="rewriter-clear" class="rewriter-btn rewriter-btn--ghost">Clear</button>
					<button id="rewriter-submit" class="rewriter-btn rewriter-btn--primary">
						<span class="rewriter-btn__text">Rewrite</span>
						<span class="rewriter-btn__arrow">→</span>
					</button>
				</div>
			</div>

			<div class="rewriter-panel rewriter-panel--output" id="rewriter-output-panel">
				<div class="rewriter-panel__header">
					<span class="rewriter-panel__label">Rewritten Text</span>
					<span class="rewriter-panel__count"><span id="rewriter-output-count">0</span> words</span>
				</div>
				<div
					id="rewriter-output"
					class="rewriter-output"
					aria-live="polite"
				><span class="rewriter-output__placeholder">Your rewritten text will appear here.</span></div>
				<div id="rewriter-loader" class="rewriter-loader" hidden>
					<div class="rewriter-loader__spinner"></div>
					<span>Rewriting…</span>
				</div>
				<div id="rewriter-error" class="rewriter-error" hidden></div>
				<div class="rewriter-panel__footer">
					<button id="rewriter-copy" class="rewriter-btn rewriter-btn--ghost" disabled>Copy</button>
					<button id="rewriter-check-score" class="rewriter-btn rewriter-btn--ghost" disabled>Check Score</button>
				</div>
			</div>

		</div>

		<div id="rewriter-score-panel" class="rewriter-score-panel" hidden>
			<div class="rewriter-score-panel__header">
				<div class="rewriter-score-panel__overall">
					<span class="rewriter-score-panel__label">Human Score</span>
					<span id="rewriter-score-value" class="rewriter-score-panel__value">—</span>
					<span class="rewriter-score-panel__max">/100</span>
				</div>
				<span id="rewriter-score-verdict" class="rewriter-score-panel__verdict"></span>
			</div>
			<ul id="rewriter-score-signals" class="rewriter-score-signals"></ul>
			<ul id="rewriter-score-flags" class="rewriter-score-flags" hidden></ul>
		</div>

	</div>
</section>

<section class="rewriter-how">
	<div class="rewriter-how__inner">
		<span class="section-eyebrow">How it works</span>
		<h2 class="section-title">Built to sound human.</h2>
		<div class="rewriter-how__grid">
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">01</div>
				<h3 class="rewriter-how__title">Natural phrasing</h3>
				<p class="rewriter-how__desc">Contractions, hedging language, and varied vocabulary replace the overly polished patterns AI detectors flag.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">02</div>
				<h3 class="rewriter-how__title">Sentence variation</h3>
				<p class="rewriter-how__desc">Short punchy sentences mixed with longer flowing ones break the uniform rhythm that gives AI writing away.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">03</div>
				<h3 class="rewriter-how__title">Passes detection</h3>
				<p class="rewriter-how__desc">Engineered specifically to clear tools like CopyLeaks, GPTZero, and Originality.ai while keeping your original meaning intact.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
