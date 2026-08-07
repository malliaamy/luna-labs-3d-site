<?php
get_header();

if (function_exists('is_cart') && is_cart() && WC()->cart && WC()->cart->is_empty()):
?>
<article class="luna-empty-cart">
  <div class="luna-empty-cart-copy">
    <small>Cart · 00 objects</small>
    <h1>Nothing in the lab <span>just yet.</span></h1>
    <p>Your selected objects and custom palettes will appear here. Return to the catalogue when you’re ready to add something strange.</p>
  </div>
  <div class="luna-empty-cart-route">
    <div class="luna-empty-orbit" aria-hidden="true"></div>
    <a class="product-buy-button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Return to the shop <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
  </div>
</article>
<?php
elseif (function_exists('is_account_page') && is_account_page()):
    while (have_posts()): the_post();
?>
<article class="luna-account-page">
  <header class="luna-account-hero">
    <div>
      <small>Customer account</small>
      <h1>Your place in <span>the lab.</span></h1>
    </div>
    <p>Sign in or create an account to keep orders, delivery details, saved payment methods and account settings together.</p>
  </header>
  <div class="luna-account-content"><?php the_content(); ?></div>
</article>
<?php
    endwhile;
elseif (have_posts()):
    while (have_posts()): the_post();
?>
<article class="ll-standard-page"><h1><?php the_title(); ?></h1><?php the_content(); ?></article>
<?php
    endwhile;
endif;

get_footer();
?>
