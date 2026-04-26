<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AE_Softwares
 */
?>

	</main><!-- .site-main -->

<footer class="site-footer">
	<div class="footer-top">
		<div class="footer-col footer-col--brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
			<p class="footer-tagline">Building intelligence. Delivering results.</p>
		</div>

		<div class="footer-col footer-col--newsletter">
			<h3>Newsletter</h3>
			<form class="footer-newsletter" method="post" action="#">
				<input type="email" class="footer-newsletter__input" placeholder="your@email.com" required>
				<button type="submit" class="footer-newsletter__button">Subscribe</button>
			</form>
		</div>
	</div>

	<div class="footer-mid">
		<div class="footer-col">
			<h4>Services</h4>
			<ul>
				<li><a href="#services">AI Development</a></li>
				<li><a href="#services">Automation</a></li>
				<li><a href="#services">Chatbots</a></li>
				<li><a href="#services">Consulting</a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4>Company</h4>
			<ul>
				<li><a href="#about">About</a></li>
				<li><a href="#process">Process</a></li>
				<li><a href="#testimonials">Testimonials</a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4>Contact</h4>
			<ul>
				<li><a href="mailto:anthony@aesoftwaresolutions.com">Email us</a></li>
				<li><a href="#contact">Start a project</a></li>
			</ul>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="footer-copy">
			&copy; <?php echo esc_html( date( 'Y' ) ); ?> AE Software Solutions. All rights reserved.
		</div>
		<div class="footer-social">
			<a href="https://linkedin.com/company/ae-software-solutions" class="footer-social__link" target="_blank" rel="noopener noreferrer">LinkedIn</a>
			<a href="#instagram" class="footer-social__link">Instagram</a>
		</div>
	</div>
</footer><!-- .site-footer -->

<script>
(function(){
	var h = document.querySelector('.site-header');
	if(!h) return;
	window.addEventListener('scroll', function(){
		h.classList.toggle('scrolled', window.scrollY > 40);
	}, {passive:true});
})();
</script>

<?php wp_footer(); ?>

</body>
</html>
