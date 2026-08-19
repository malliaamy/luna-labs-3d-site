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
      <div><span>Customer</span><a href="<?php echo esc_url(home_url('/cart/')); ?>">Cart</a><a href="<?php echo esc_url(home_url('/my-account/')); ?>">Account</a><a href="<?php echo esc_url(home_url('/privacy-policy-2/')); ?>">Privacy</a><a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms</a><a href="<?php echo esc_url(home_url('/returns-and-refund-policy/')); ?>">Returns</a><a href="<?php echo esc_url(home_url('/shipping-and-delivery-policy/')); ?>">Shipping</a><a href="<?php echo esc_url(home_url('/cookie-policy-eu/')); ?>">Cookies</a></div>
      <div><span>Contact</span><a href="mailto:<?php echo esc_attr(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?>"><?php echo esc_html(get_theme_mod('luna_email', 'lunalabs3d@gmail.com')); ?></a><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', (string) get_theme_mod('luna_phone', '+356 7771 8303'))); ?>"><?php echo esc_html(get_theme_mod('luna_phone', '+356 7771 8303')); ?></a></div>
    </div>
  </div>
  <div class="footer-socials" aria-label="Luna Labs social media">
    <span>Follow the studio</span>
    <a href="<?php echo esc_url(get_theme_mod('luna_instagram', 'https://www.instagram.com/lunalabs3d/')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram — @lunalabs3d (opens in a new tab)"><span>Instagram</span><strong>@lunalabs3d</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
    <a href="<?php echo esc_url(get_theme_mod('luna_tiktok', 'https://www.tiktok.com/@lunalabs3d')); ?>" target="_blank" rel="noopener noreferrer" aria-label="TikTok — @lunalabs3d (opens in a new tab)"><span>TikTok</span><strong>@lunalabs3d</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  </div>
  <div class="footer-bottom"><span>© <?php echo esc_html(date('Y')); ?> Luna Labs</span><span>SCULPT · PRINT · PAINT · MALTA</span><a href="#top">Back to top <span class="ui-arrow ui-arrow-up" aria-hidden="true"></span></a></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
