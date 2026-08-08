<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$work_url = get_post_type_archive_link('ll_work') ?: home_url('/work/');
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$items = [
    ['Work', $work_url, is_post_type_archive('ll_work') || is_singular('ll_work')],
    ['Services', home_url('/services/'), is_page('services')],
    ['Shop', $shop_url, function_exists('is_woocommerce') && is_woocommerce()],
    ['About', home_url('/about/'), is_page('about')],
];
?>
<header class="site-header">
  <nav class="nav" aria-label="Main navigation">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Luna Labs home">
      <span class="brand-mark" aria-hidden="true"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/luna-labs-mark.png'); ?>" alt=""></span>
      <span class="brand-type"><strong>LUNA LABS</strong><small>SCULPT · PRINT · PAINT</small></span>
    </a>
    <div class="nav-links">
      <?php foreach ($items as [$label, $url, $active]): ?>
        <?php if ($label === 'Shop' && function_exists('wc_get_cart_url')): ?>
          <div class="nav-shop-menu">
            <a class="nav-shop-trigger <?php echo $active || is_cart() || is_account_page() ? 'is-active' : ''; ?>" href="<?php echo esc_url($shop_url); ?>">Shop <?php luna_header_cart_count('nav-cart-count'); ?><i aria-hidden="true"></i></a>
            <div class="nav-shop-dropdown" id="shop-navigation" aria-hidden="true">
              <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><strong>Cart</strong><small>Review selected objects</small><?php luna_header_cart_count(); ?></a>
              <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><strong>Account</strong><small>Orders and account access</small><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
            </div>
          </div>
        <?php else: ?>
          <a class="<?php echo $active ? 'is-active' : ''; ?>" href="<?php echo esc_url($url); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html($label); ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="nav-actions">
      <a class="nav-cta <?php echo is_page('services') ? 'is-active' : ''; ?>" href="<?php echo esc_url(home_url('/services/')); ?>">Start a project <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
      <div class="mobile-menu">
        <button class="mobile-menu-trigger" type="button" aria-expanded="false" aria-controls="mobile-navigation"><span>Menu</span><?php luna_header_cart_count('mobile-menu-cart-count'); ?><span class="menu-glyph" aria-hidden="true"><i></i><i></i></span></button>
        <div class="mobile-menu-panel" id="mobile-navigation" aria-hidden="true">
          <?php foreach ($items as $index => [$label, $url, $active]): ?>
            <?php if ($label === 'Shop' && function_exists('wc_get_cart_url')): ?>
              <div class="mobile-shop-menu">
                <div class="mobile-shop-heading <?php echo $active || is_cart() || is_account_page() ? 'is-active' : ''; ?>">
                  <a href="<?php echo esc_url($shop_url); ?>" tabindex="-1"><span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span><strong>Shop</strong></a>
                  <button class="mobile-shop-toggle" type="button" aria-expanded="false" aria-label="Show cart and account links" tabindex="-1"><i aria-hidden="true">+</i></button>
                  <?php luna_header_cart_count('nav-cart-count'); ?>
                </div>
                <div class="mobile-shop-links" hidden>
                  <a href="<?php echo esc_url(wc_get_cart_url()); ?>" tabindex="-1">Cart <?php luna_header_cart_count(); ?></a>
                  <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" tabindex="-1">Account <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
                </div>
              </div>
            <?php else: ?>
              <a class="<?php echo $active ? 'is-active' : ''; ?>" href="<?php echo esc_url($url); ?>" tabindex="-1"><span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span><strong><?php echo esc_html($label); ?></strong></a>
            <?php endif; ?>
          <?php endforeach; ?>
          <a class="is-cta" href="<?php echo esc_url(home_url('/services/')); ?>" tabindex="-1"><span>05</span><strong>Start a project</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
        </div>
      </div>
    </div>
  </nav>
</header>
<main id="content">
