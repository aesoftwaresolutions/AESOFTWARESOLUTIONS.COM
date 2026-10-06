<?php
/**
 * The template for displaying the footer — v4.0 Matthieu Givelet Inspired
 *
 * @package AE_Softwares
 */
?>

</main><!-- .site-main -->

<footer class="site-footer">
  <div class="footer-col">
    <h4>[ Information ]</h4>
    <div class="footer-big-text">
      I'm always looking to meet small business owners, collaborate on new systems, and solve real workflow bottlenecks. <a href="mailto:anthony@aesoftwaresolutions.com">Feel free to say hello.</a>
    </div>
  </div>

  <div class="footer-col">
    <h4>[ Navigation ]</h4>
    <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;font-size:0.95rem;">
      <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" style="color:var(--text);text-decoration:none;">Services & Consulting</a></li>
      <li><a href="<?php echo esc_url( home_url( '/work/' ) ); ?>" style="color:var(--text);text-decoration:none;">Selected Works</a></li>
      <li><a href="<?php echo esc_url( home_url( '/websites/' ) ); ?>" style="color:var(--text);text-decoration:none;">Custom Websites</a></li>
      <li><a href="<?php echo esc_url( home_url( '/business-starter/' ) ); ?>" style="color:var(--text);text-decoration:none;">Pricing ($50/mo)</a></li>
      <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="color:var(--text);text-decoration:none;">About AE Software</a></li>
    </ul>
  </div>

  <div class="footer-col">
    <h4>[ Direct Contact ]</h4>
    <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;font-size:0.9rem;color:var(--text-secondary);">
      <li><strong style="color:var(--text);">Email:</strong> anthony@aesoftwaresolutions.com</li>
      <li><strong style="color:var(--text);">Location:</strong> Chambersburg, PA</li>
      <li><strong style="color:var(--text);">Hours:</strong> Mon – Fri, 9am – 5pm ET</li>
    </ul>
  </div>

  <div style="grid-column:1/-1;display:flex;justify-content:space-between;align-items:center;padding-top:40px;margin-top:30px;border-top:1px solid var(--border);font-family:var(--font-mono);font-size:0.75rem;color:var(--text-tertiary);">
    <div>&copy; <?php echo esc_html( date( 'Y' ) ); ?> AE Software Solutions LLC. All rights reserved.</div>
    <div>[ Practical systems. Real business outcomes. ]</div>
  </div>
</footer>

<script>
  // Seamless Counter loader matching Matthieu Givelet's entrance
  (function() {
    var count = 0;
    var num = document.getElementById('ae-loader-num');
    var loader = document.getElementById('ae-loader');
    if (num && loader) {
      var timer = setInterval(function() {
        count += Math.floor(Math.random() * 16) + 8;
        if (count >= 100) {
          count = 100;
          num.textContent = '100%';
          clearInterval(timer);
          setTimeout(function() {
            loader.style.transform = 'translateY(-100%)';
          }, 250);
        } else {
          num.textContent = count + '%';
        }
      }, 35);
    }

    // Scroll-triggered header compression
    var header = document.querySelector('header.site-header');
    if (header) {
      window.addEventListener('scroll', function() {
        header.classList.toggle('aec--scrolled', window.scrollY > 40);
      }, { passive: true });
    }
  })();
</script>

<?php wp_footer(); ?>

</body>
</html>
