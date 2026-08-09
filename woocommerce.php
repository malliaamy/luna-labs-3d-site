<?php
get_header();

if (function_exists('is_shop') && (is_shop() || is_product_taxonomy())) {
    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
    $products = wc_get_products([
        'status' => 'publish',
        'limit' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ]);
    $products = array_values(array_filter($products, static fn($item) => $item instanceof WC_Product && $item->is_visible()));
?>
<div class="shop-page" data-shop-catalogue>
  <section class="shop-page-hero">
    <div class="page-kicker"><span>Catalogue 03</span><span>Physical and digital objects</span></div>
    <h1>Shop the<span>strange.</span></h1>
    <div class="page-hero-bottom">
      <p>Tabletop pieces, printed objects, stationery and downloadable sculpts—small runs, hand-finished where it matters.</p>
      <a href="#products">Browse the catalogue <span class="ui-arrow ui-arrow-down" aria-hidden="true"></span></a>
    </div>
    <nav class="shop-customer-links" aria-label="Customer account">
      <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><span>Cart</span><small>Review saved objects</small><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
      <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><span>Account</span><small>Orders and account access</small><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
    </nav>
  </section>
  <section class="shop-category-index">
    <nav class="shop-category-nav" aria-label="Filter shop categories">
      <button type="button" class="active" data-product-filter="all"><span>00</span><strong>All products</strong><small>View the complete catalogue</small><i><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></i></button>
      <?php if (!is_wp_error($categories)): foreach ($categories as $index => $category): ?>
        <button type="button" data-product-filter="<?php echo esc_attr($category->slug); ?>"><span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span><strong><?php echo esc_html($category->name); ?></strong><small><?php echo esc_html($category->description ?: 'Objects from the Luna Labs catalogue'); ?></small><i><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></i></button>
      <?php endforeach; endif; ?>
    </nav>
  </section>
  <section class="product-section" id="products">
    <div class="page-kicker"><span>Current objects</span><span data-product-count><?php echo esc_html(count($products)); ?> objects</span></div>
    <div class="product-grid">
      <?php foreach ($products as $index => $product):
          $product_id = $product->get_id();
          $image = wp_get_attachment_image_url($product->get_image_id(), 'large');
          $slugs = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'slugs']);
          $names = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']);
          $category_name = !is_wp_error($names) && $names ? $names[0] : 'Studio object';
          $status = $product->get_slug() === 'dragon-dice-tower' ? 'Made to order' : ($product->is_in_stock() ? 'Available' : 'Sold out');
      ?>
        <a class="product-card" href="<?php echo esc_url(get_permalink($product_id)); ?>" data-product-categories="<?php echo esc_attr(is_wp_error($slugs) ? '' : implode(' ', $slugs)); ?>">
          <div class="product-card-image">
            <?php if ($image): ?><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy"><?php endif; ?>
            <span><?php echo esc_html($status); ?></span>
          </div>
          <div class="product-card-copy">
            <span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <div><small><?php echo esc_html($category_name); ?></small><h2><?php echo esc_html($product->get_name()); ?></h2></div>
            <strong><?php echo wp_kses_post($product->get_price_html()); ?></strong>
            <i aria-hidden="true"><span class="ui-arrow ui-arrow-ne"></span></i>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</div>
<?php
} elseif (function_exists('is_product') && is_product() && get_post_field('post_name', get_queried_object_id()) === 'dragon-dice-tower') {
    get_template_part('woocommerce/single-product', 'dragon');
} else {
?>
  <section class="ll-shop-shell"><?php woocommerce_content(); ?></section>
<?php
}
get_footer();
