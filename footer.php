<?php
/**
 * Site footer — v5 editorial.
 * Animations (loader, reveals, page transitions) live in assets/js/ae-motion.js.
 *
 * @package AE_Softwares
 */
?>

</main><!-- .site-main -->

<footer class="site-footer v5-footer ae-rule">
  <div class="v5-footer__intro">
    <p class="v5-label" data-reveal>[ Say hello ]</p>
    <p class="v5-footer__big" data-split>
      Got a workflow that needs untangling, or a website that should be pulling its weight? <a class="v5-link" href="mailto:anthony@aesoftwaresolutions.com">Tell us about it.</a>
    </p>
  </div>

  <nav class="v5-footer__col" aria-label="Footer">
    <p class="v5-label" data-reveal>[ Pages ]</p>
    <ul data-reveal>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">Work</a></li>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/websites/' ) ); ?>">Website design</a></li>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/online-presence/' ) ); ?>">Online presence</a></li>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">Pricing</a></li>
      <li><a class="v5-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
    </ul>
  </nav>

  <div class="v5-footer__col">
    <p class="v5-label" data-reveal>[ Contact ]</p>
    <ul data-reveal>
      <li><a class="v5-link" href="mailto:anthony@aesoftwaresolutions.com">anthony@aesoftwaresolutions.com</a></li>
      <li>Mon&ndash;Fri, 9am&ndash;5pm ET</li>
    </ul>
  </div>

  <p class="v5-wordmark" aria-hidden="true"><span>AE Software</span><span>Solutions</span></p>

  <div class="v5-footer__legal ae-rule">
    <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> AE Software Solutions LLC</span>
    <a class="v5-link" href="#main-content" data-no-transition>Back to top &uarr;</a>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
