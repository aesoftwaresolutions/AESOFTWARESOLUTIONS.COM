<?php /* Template Name: Transcriber */ get_header(); ?>

<div class="page-hero transcriber-hero">
	<div class="page-hero__eyebrow"><a href="<?php echo esc_url( home_url( '/free-tools/' ) ); ?>" class="hero-back-link">&larr; Free Tools</a></div>
	<h1 class="page-hero__headline">AI Audio<br><em>Transcriber</em></h1>
	<p class="page-hero__subtitle">Record from your microphone or upload an audio file and get a text transcript in seconds — powered by Whisper running on-site.</p>
</div>

<section class="transcriber-section">
	<div class="transcriber-wrap">

		<div class="transcriber-panels">

			<div class="transcriber-panel transcriber-panel--input">
				<div class="transcriber-panel__header">
					<span class="transcriber-panel__label">Audio Source</span>
				</div>

				<div class="transcriber-record-area" id="transcriber-record-area">
					<button id="transcriber-record-btn" class="transcriber-record-btn" type="button" aria-label="Start recording">
						<span class="transcriber-record-btn__icon"></span>
					</button>
					<div class="transcriber-record-status" id="transcriber-record-status">Press to record</div>
					<div class="transcriber-record-timer" id="transcriber-record-timer" hidden>0:00</div>
				</div>

				<div class="transcriber-divider"><span>or</span></div>

				<div class="transcriber-upload-area">
					<label for="transcriber-file-input" class="transcriber-upload-label">
						<span class="transcriber-upload-label__icon">↑</span>
						<span class="transcriber-upload-label__text">Upload audio / video file</span>
						<span class="transcriber-upload-label__hint">MP3, M4A, WAV, WebM, MP4 — max 25 MB</span>
					</label>
					<input
						type="file"
						id="transcriber-file-input"
						class="transcriber-file-input"
						accept="audio/*,video/*"
					>
					<div id="transcriber-file-name" class="transcriber-file-name" hidden></div>
				</div>

				<div class="transcriber-panel__footer">
					<button id="transcriber-submit" class="rewriter-btn rewriter-btn--primary" disabled>
						<span class="rewriter-btn__text">Transcribe</span>
						<span class="rewriter-btn__arrow">→</span>
					</button>
				</div>
			</div>

			<div class="transcriber-panel transcriber-panel--output">
				<div class="transcriber-panel__header">
					<span class="transcriber-panel__label">Transcript</span>
					<span class="transcriber-panel__count"><span id="transcriber-output-count">0</span> words</span>
				</div>
				<div
					id="transcriber-output"
					class="transcriber-output"
					aria-live="polite"
				><span class="transcriber-output__placeholder">Your transcript will appear here.</span></div>
				<div id="transcriber-loader" class="rewriter-loader" hidden>
					<div class="rewriter-loader__spinner"></div>
					<span>Transcribing…</span>
				</div>
				<div id="transcriber-error" class="rewriter-error" hidden></div>
				<div class="transcriber-panel__footer">
					<button id="transcriber-copy" class="rewriter-btn rewriter-btn--ghost" disabled>Copy</button>
					<button id="transcriber-clear-output" class="rewriter-btn rewriter-btn--ghost" disabled>Clear</button>
				</div>
			</div>

		</div>

	</div>
</section>

<section class="rewriter-how transcriber-how">
	<div class="rewriter-how__inner">
		<span class="section-eyebrow">How it works</span>
		<h2 class="section-title">Local. Fast. Private.</h2>
		<div class="rewriter-how__grid">
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">01</div>
				<h3 class="rewriter-how__title">On-site Whisper</h3>
				<p class="rewriter-how__desc">Audio is transcribed by OpenAI Whisper running on our own server — nothing is sent to third-party APIs.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">02</div>
				<h3 class="rewriter-how__title">Any format</h3>
				<p class="rewriter-how__desc">Record live from your microphone or upload existing audio and video files in any common format.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">03</div>
				<h3 class="rewriter-how__title">Copy &amp; use</h3>
				<p class="rewriter-how__desc">The transcript lands in plain text, ready to paste anywhere — into the rewriter, a document, or your CMS.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
