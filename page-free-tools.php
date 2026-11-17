<?php /* Template Name: Free Tools */ get_header(); ?>

<div class="page-hero free-tools-hero">
	<div class="page-hero__eyebrow">Free tools</div>
	<h1 class="page-hero__headline">Free AI tools,<br><em>no signup.</em></h1>
	<p class="page-hero__subtitle">Simple AI tools I built for everyday writing and listening work. Try them here for free.</p>
</div>

<section class="free-tools-section">
	<div class="free-tools-grid">

		<a href="<?php echo esc_url( home_url( '/email-drafter/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">01</span>
				<span class="tool-card__tag">Email</span>
			</div>
			<h2 class="tool-card__title">AI Email Drafter</h2>
			<p class="tool-card__desc">Describe what you need to say and who you're saying it to, and get a complete email draft to review and send. Professional, friendly, persuasive, and follow up tones available.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/summarizer/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">02</span>
				<span class="tool-card__tag">Research</span>
			</div>
			<h2 class="tool-card__title">AI Text Summarizer</h2>
			<p class="tool-card__desc">Drop in any long article, report, or document and get a clean summary in three lengths: a quick bullet point brief, a paragraph overview, or a detailed breakdown with key takeaways.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/transcriber/' ) ); ?>" class="tool-card">
			<div class="tool-card__header">
				<span class="tool-card__num">03</span>
				<span class="tool-card__tag">Audio</span>
			</div>
			<h2 class="tool-card__title">AI Audio Transcriber</h2>
			<p class="tool-card__desc">Record from your microphone or upload an audio file and get a text transcript. No account needed.</p>
			<span class="tool-card__cta">Try it free &rarr;</span>
		</a>

	</div>
</section>

<section class="free-tools-note">
	<div class="free-tools-note__inner">
		<div class="free-tools-note__block">
			<h3>Why are these free?</h3>
			<p>I built these tools to show what is possible with AI, and they are free to use. Please do not paste confidential or sensitive information into them.</p>
		</div>
		<div class="free-tools-note__block">
			<h3>Want this for your business?</h3>
			<p>I can build something like these for your business, branded and connected to your systems. <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get in touch</a> to talk about what I can build.</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
