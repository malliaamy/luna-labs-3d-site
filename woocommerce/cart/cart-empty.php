<?php
defined('ABSPATH') || exit;
do_action('woocommerce_before_cart_empty');
?>
<section class="luna-empty-cart">
  <div class="luna-empty-cart-copy">
    <small>Cart · 00 objects</small>
    <h1>Nothing in the lab <span>just yet.</span></h1>
    <p><?php echo wp_kses_post(apply_filters('wc_empty_cart_message', __('Your selected objects and custom palettes will appear here. Return to the catalogue when you\'re ready to add something strange.', 'luna-labs'))); ?></p>
  </div>
  <div class="luna-empty-cart-route">
    <div class="luna-empty-orbit" aria-hidden="true"></div>
    <?php do_action('woocommerce_cart_is_empty'); ?>
    <?php if (wc_get_page_id('shop') > 0): ?>
      <a class="product-buy-button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
        <?php echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to the shop', 'luna-labs'))); ?>
        <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span>
      </a>
    <?php endif; ?>
  </div>
</section>
<?php do_action('woocommerce_after_cart_empty'); ?>
