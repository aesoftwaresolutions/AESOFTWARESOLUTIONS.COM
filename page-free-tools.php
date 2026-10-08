<?php /* Template Name: Free Tools */ get_header(); ?>

<div class="page-hero free-tools-hero">
	<div class="page-hero__eyebrow">Powered by our VPS AI agent</div>
	<h1 class="page-hero__headline">Free AI tools,<br><em>no sign-up.</em></h1>
	<p class="page-hero__subtitle">Real AI utilities built on the same infrastructure we use for client projects — running on our own model, not a rate-limited API trial.</p>
</div>

<section class="free-tools-section">
	<div class="free-tools-grid">

		<a href="<?php echo esc_url( home_url( '/email-drafter/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">01</span>
				<span class="tool-card__tag">Email</span>
			</div>
			<h2 class="tool-card__title">AI Email Drafter</h2>
			<p class="tool-card__desc">Describe what you need to say and who you're saying it to — get a complete, ready-to-send email in seconds. Professional, friendly, persuasive, and follow-up tones available.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/summarizer/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">02</span>
				<span class="tool-card__tag">Research</span>
			</div>
			<h2 class="tool-card__title">AI Text Summarizer</h2>
			<p class="tool-card__desc">Drop in any long article, report, or document and get a clean summary in three lengths: a quick bullet-point brief, a paragraph overview, or a detailed breakdown with key takeaways.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/transcriber/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">03</span>
				<span class="tool-card__tag">Audio</span>
			</div>
			<h2 class="tool-card__title">AI Audio Transcriber</h2>
			<p class="tool-card__desc">Record from your microphone or upload an audio file and get an accurate text transcript in seconds — powered by Whisper running on our own server. No file size limits, no account needed.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

	</div>
</section>

<section class="free-tools-note">
	<div class="free-tools-note__inner">
		<div class="free-tools-note__block">
			<h3>Why are these free?</h3>
			<p>We run these tools on our own infrastructure to demonstrate what's possible with local AI models. No rate limits, no hidden costs, no data stored. Your text is processed and discarded immediately.</p>
		</div>
		<div class="free-tools-note__block">
			<h3>Want this for your business?</h3>
			<p>Every tool here can be customised, branded, and embedded into your product or website — trained on your data, connected to your systems. <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get in touch</a> to talk about what we can build.</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
