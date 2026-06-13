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
				<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Operational Discovery</a></li>
				<li><a href="<?php echo esc_url( home_url( '/services/#tier-2' ) ); ?>">Process Intelligence</a></li>
				<li><a href="<?php echo esc_url( home_url( '/services/#tier-3' ) ); ?>">Intelligent Deployment</a></li>
				<li><a href="<?php echo esc_url( home_url( '/industries/' ) ); ?>">Industries</a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4>Company</h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
				<li><a href="<?php echo esc_url( home_url( '/software/' ) ); ?>">Software</a></li>
				<li><a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>">Pricing</a></li>
				<li><a href="<?php echo esc_url( home_url( '/work/' ) ); ?>">Case Studies</a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4>Contact</h4>
			<ul>
				<li><a href="mailto:anthony@aesoftwaresolutions.com">Email us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a></li>
				<li><a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>">Free assessment</a></li>
			</ul>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="footer-copy">
			&copy; <?php echo esc_html( date( 'Y' ) ); ?> AE Software Solutions. All rights reserved.
		</div>
		<div class="footer-social">
			<a href="https://linkedin.com/company/ae-software-solutions" class="footer-social__link" target="_blank" rel="noopener noreferrer">LinkedIn</a>
			<a href="https://instagram.com/aesoftwaresolutions" class="footer-social__link" target="_blank" rel="noopener noreferrer">Instagram</a>
		</div>
	</div>
</footer><!-- .site-footer -->

<?php wp_footer(); ?>

</body>
</html>
