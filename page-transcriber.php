<?php
/* Template Name: Transcriber */

/* Inject TTS-section styles before the closing </head> */
add_action( 'wp_head', function () { ?>
<style>
/* ── TTS section ──────────────────────────────────────────────────── */
.transcriber-section--tts { padding-top: 0; }
.transcriber-output__placeholder { color: var(--text-muted); font-style: italic; }
.transcriber-file-name { color: var(--accent); }

.transcriber-tool-label {
	padding: 0 0 1.75rem;
	border-bottom: 1px solid var(--border);
	margin-bottom: 2rem;
}
.transcriber-tool-label__tag {
	display: inline-block;
	background: var(--accent);
	color: var(--bg);
	font-size: 0.7rem;
	font-weight: 700;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	padding: 0.25rem 0.65rem;
	border-radius: 3px;
	margin-bottom: 0.6rem;
}
.transcriber-tool-label__desc {
	margin: 0;
	font-size: 0.95rem;
	color: var(--text-muted);
	max-width: 52ch;
}
.tts-voice-row {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	padding: 0.75rem 1.25rem;
	border-top: 1px solid #2a2a2a;
}
.tts-voice-label {
	font-size: 0.8rem;
	font-weight: 600;
	color: var(--text-muted);
	white-space: nowrap;
}
.tts-voice-select {
	flex: 1;
	font-size: 0.85rem;
	padding: 0.4rem 0.6rem;
	border: 1px solid #333;
	border-radius: 6px;
	background: var(--bg-surface);
	color: var(--text);
	cursor: pointer;
	max-width: 240px;
}
.tts-voice-select:focus {
	outline: 2px solid var(--accent);
	outline-offset: 1px;
	border-color: var(--accent);
}
.tts-output-area {
	flex: 1;
	min-height: 120px;
	padding: 1.25rem;
	display: flex;
	align-items: center;
	justify-content: center;
}
.tts-output-area audio {
	display: block;
	width: 100%;
	border-radius: 6px;
}
</style>
<?php }, 20 );

get_header(); ?>

<div class="page-hero transcriber-hero">
	<div class="page-hero__eyebrow"><a href="<?php echo esc_url( home_url( '/free-tools/' ) ); ?>" class="hero-back-link">&larr; Free Tools</a></div>
	<h1 class="page-hero__headline">AI Audio<br><em>Transcriber</em></h1>
	<p class="page-hero__subtitle">Transcribe audio to text or convert text to speech &mdash; both powered by AI running on-site, nothing sent to third parties.</p>
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
						<span class="transcriber-upload-label__icon">&uarr;</span>
						<span class="transcriber-upload-label__text">Upload audio / video file</span>
						<span class="transcriber-upload-label__hint">MP3, M4A, WAV, WebM, MP4 &mdash; max 25 MB</span>
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
						<span class="rewriter-btn__arrow">&rarr;</span>
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
					<span>Transcribing&hellip;</span>
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

<section class="transcriber-section transcriber-section--tts">
	<div class="transcriber-wrap">

		<div class="transcriber-tool-label">
			<span class="transcriber-tool-label__tag">Text to Speech</span>
			<p class="transcriber-tool-label__desc">Type or paste text and generate spoken audio with a choice of voices.</p>
		</div>

		<div class="transcriber-panels">

			<div class="transcriber-panel transcriber-panel--input">
				<div class="transcriber-panel__header">
					<span class="transcriber-panel__label">Text Input</span>
					<span class="transcriber-panel__count"><span id="tts-input-count">0</span> words</span>
				</div>
				<textarea
					id="tts-input"
					class="rewriter-textarea"
					placeholder="Type or paste the text you want spoken&hellip;"
					rows="10"
					spellcheck="false"
				></textarea>
				<div class="tts-voice-row">
					<label for="tts-voice" class="tts-voice-label">Voice</label>
					<select id="tts-voice" class="tts-voice-select">
						<option value="af_bella">Bella &mdash; US Female</option>
						<option value="af_sarah">Sarah &mdash; US Female</option>
						<option value="af_sky">Sky &mdash; US Female</option>
						<option value="am_adam">Adam &mdash; US Male</option>
						<option value="am_michael">Michael &mdash; US Male</option>
						<option value="bf_emma">Emma &mdash; UK Female</option>
						<option value="bm_george">George &mdash; UK Male</option>
					</select>
				</div>
				<div class="transcriber-panel__footer">
					<button id="tts-clear" class="rewriter-btn rewriter-btn--ghost">Clear</button>
					<button id="tts-submit" class="rewriter-btn rewriter-btn--primary" disabled>
						<span class="rewriter-btn__text">Generate Speech</span>
						<span class="rewriter-btn__arrow">&rarr;</span>
					</button>
				</div>
			</div>

			<div class="transcriber-panel transcriber-panel--output">
				<div class="transcriber-panel__header">
					<span class="transcriber-panel__label">Audio Output</span>
				</div>
				<div class="tts-output-area" id="tts-output-area">
					<span class="transcriber-output__placeholder">Generated audio will appear here.</span>
				</div>
				<div id="tts-loader" class="rewriter-loader" hidden>
					<div class="rewriter-loader__spinner"></div>
					<span>Generating speech&hellip;</span>
				</div>
				<div id="tts-error" class="rewriter-error" hidden></div>
				<div class="transcriber-panel__footer">
					<button id="tts-download" class="rewriter-btn rewriter-btn--ghost" disabled>Download MP3</button>
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
				<p class="rewriter-how__desc">Audio is transcribed by OpenAI Whisper running on our own server &mdash; nothing is sent to third-party APIs.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">02</div>
				<h3 class="rewriter-how__title">Any format</h3>
				<p class="rewriter-how__desc">Record live from your microphone or upload existing audio and video files in any common format.</p>
			</div>
			<div class="rewriter-how__card">
				<div class="rewriter-how__num">03</div>
				<h3 class="rewriter-how__title">Copy &amp; use</h3>
				<p class="rewriter-how__desc">The transcript lands in plain text, ready to paste anywhere &mdash; into the rewriter, a document, or your CMS.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
