</main>
<footer>
  <div class="footer-statement">
    <span>Independent 3D studio</span>
    <p>Sculpt. <strong>Print.</strong> Paint.</p>
    <a href="<?php echo esc_url(home_url('/services/')); ?>">Build something with us <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  </div>
  <div class="footer-main">
    <div class="footer-brand">
      <span class="footer-logo"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/luna-labs-mark.png'); ?>" alt=""></span>
      <div><strong>LUNA LABS</strong><p>Sculpture, print and paint from a small island studio.</p></div>
    </div>
    <div class="footer-links">
      <div><span>Explore</span><a href="<?php echo esc_url(home_url('/work/')); ?>">Work</a><a href="<?php echo esc_url(home_url('/services/')); ?>">Services</a><a href="<?php echo esc_url(home_url('/shop/')); ?>">Shop</a><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></div>
      <div><span>Customer</span><a href="<?php echo esc_url(home_url('/cart/')); ?>">Cart</a><a href="<?php echo esc_url(home_url('/my-account/')); ?>">Account</a><a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms</a><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a><a href="<?php echo esc_url(home_url('/product-safety/')); ?>">Product safety</a></div>
      <div><span>Contact</span><a href="mailto:<?php echo esc_attr(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?>"><?php echo esc_html(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?></a><a href="tel:<?php echo esc_attr(get_theme_mod('luna_phone', '+356 7771 8303')); ?>"><?php echo esc_html(get_theme_mod('luna_phone', '+356 7771 8303')); ?></a></div>
    </div>
  </div>
  <div class="footer-socials" aria-label="Luna Labs social media">
    <span>Follow the studio</span>
    <a href="<?php echo esc_url(get_theme_mod('luna_instagram')); ?>" target="_blank" rel="noreferrer"><span>Instagram</span><strong>@lunalabs3d</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
    <a href="<?php echo esc_url(get_theme_mod('luna_tiktok')); ?>" target="_blank" rel="noreferrer"><span>TikTok</span><strong>@lunalabs3d</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  </div>
  <div class="footer-bottom"><span>© <?php echo esc_html(date('Y')); ?> Luna Labs</span><span>SCULPT · PRINT · PAINT · MALTA</span><a href="#top">Back to top <span class="ui-arrow ui-arrow-up" aria-hidden="true"></span></a></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
