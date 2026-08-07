<?php
if (!defined('ABSPATH')) exit;

define('LUNA_THEME_VERSION', '1.6.12');

function luna_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-slider');
    register_nav_menus(['primary' => __('Primary navigation', 'luna-labs')]);
}
add_action('after_setup_theme', 'luna_setup');

function luna_assets(): void {
    wp_enqueue_style('luna-prototype', get_template_directory_uri() . '/assets/css/prototype.css', [], LUNA_THEME_VERSION);
    wp_enqueue_style('luna-theme', get_template_directory_uri() . '/assets/css/theme.css', ['luna-prototype'], LUNA_THEME_VERSION);
    wp_enqueue_style('luna-footer-shared', get_template_directory_uri() . '/assets/css/footer-shared.css', ['luna-theme'], LUNA_THEME_VERSION);
    wp_enqueue_script('luna-theme', get_template_directory_uri() . '/assets/js/theme.js', [], LUNA_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'luna_assets');

// Remove SKU from all WooCommerce product displays.
add_filter('wc_product_sku_enabled', '__return_false');

// Themed back link + category label on generic (non-custom) single product pages.
function luna_shop_product_back_row(): void {
    global $product;
    if (!$product instanceof WC_Product) return;
    $terms = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
    $cat = (!is_wp_error($terms) && $terms) ? $terms[0] : 'Shop';
    echo '<div class="product-back-row"><a class="product-back-link" href="' . esc_url(wc_get_page_permalink('shop')) . '">&larr; Back to shop</a><span>' . esc_html($cat) . ' / ' . esc_html($product->get_name()) . '</span></div>';
}
add_action('woocommerce_before_single_product', 'luna_shop_product_back_row');

// Show more products in the single-product 'related' section, even if they aren't
// strictly related by category/tag, and give the section a friendlier heading.
function luna_shop_more_related_args($args) {
    $args['posts_per_page'] = 8;
    $args['columns'] = 4;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'luna_shop_more_related_args');

function luna_shop_related_heading($translated, $text, $domain) {
    if ($domain === 'woocommerce' && $text === 'Related products') {
        return 'You might also like';
    }
    return $translated;
}
add_filter('gettext', 'luna_shop_related_heading', 10, 3);

// If WooCommerce still comes up short (e.g. a lonely product category).
// pad the related list with other published products from the shop.
function luna_shop_pad_related($related_posts, $product_id, $args) {
    $needed = isset($args['posts_per_page']) ? (int) $args['posts_per_page'] : 4;
    if (count($related_posts) >= $needed) {
        return $related_posts;
    }
    $exclude = array_merge($related_posts, array($product_id));
    $fill = wc_get_products(array(
        'status'   => 'publish',
        'limit'    => $needed - count($related_posts),
        'exclude'  => $exclude,
        'orderby'  => 'rand',
        'return'   => 'ids',
        'type'     => array('simple', 'variable'),
    ));
    return array_merge($related_posts, $fill);
}
add_filter('woocommerce_related_products', 'luna_shop_pad_related', 10, 3);

add_filter('loop_shop_columns', fn() => 3);
add_filter('loop_shop_per_page', fn() => 12);
add_action('wp', function (): void {
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
});

function luna_header_cart_count(string $class = 'shop-cart-count'): void {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <span class="<?php echo esc_attr($class); ?><?php echo $count < 1 ? ' is-empty' : ''; ?>" aria-label="<?php echo esc_attr(sprintf(_n('%d item', '%d items', $count, 'luna-labs'), $count)); ?>"><?php echo esc_html($count); ?></span>
    <?php
}

add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    ob_start();
    luna_header_cart_count('shop-cart-count');
    $fragments['.shop-cart-count'] = ob_get_clean();

    ob_start();
    luna_header_cart_count('nav-cart-count');
    $fragments['.nav-cart-count'] = ob_get_clean();

    return $fragments;
});

function luna_work_meta(string $key, int $post_id = 0): string {
    return (string) get_post_meta($post_id ?: get_the_ID(), '_luna_' . $key, true);
}

function luna_work_image(string $size = 'large'): string {
    if (has_post_thumbnail()) return get_the_post_thumbnail_url(get_the_ID(), $size) ?: '';
    $slug = get_post_field('post_name', get_the_ID());
    $extensions = [
        'dragon-dice-tower' => 'jpg', 'mushroom-penholder' => 'jpg',
        'teacup-penholder' => 'png', 'primus-inter-pares' => 'jpeg',
        'the-dancer' => 'jpeg', 'noir-angel' => 'jpg',
        'amun-ra' => 'jpeg', 'chopper-figurine' => 'jpg',
        '3d-fantasy-models' => 'png', 'azri-animations' => 'png',
        'medieval-village' => 'jpg', 'keep-it-brief' => 'jpg',
    ];
    return isset($extensions[$slug])
        ? get_template_directory_uri() . '/assets/images/work/' . $slug . '.' . $extensions[$slug]
        : '';
}

function luna_customize_register(WP_Customize_Manager $customizer): void {
    $customizer->add_section('luna_contact', ['title' => 'Luna Labs contact', 'priority' => 35]);
    foreach ([
        'email' => ['Email', 'lunalabs3d@gmail.com'],
        'phone' => ['Phone', '+356 7771 8303'],
        'instagram' => ['Instagram URL', 'https://www.instagram.com/lunalabs3d/'],
        'tiktok' => ['TikTok URL', 'https://www.tiktok.com/@lunalabs3d'],
    ] as $key => [$label, $default]) {
        $customizer->add_setting('luna_' . $key, ['default' => $default, 'sanitize_callback' => 'sanitize_text_field']);
        $customizer->add_control('luna_' . $key, ['label' => $label, 'section' => 'luna_contact']);
    }
    $customizer->add_section('luna_home_model', ['title' => 'Homepage 3D model', 'priority' => 34]);
    foreach ([
        'desktop_angle' => ['Desktop default angle (degrees)', -5, -360, 360, 1],
        'mobile_angle' => ['Mobile default angle (degrees)', -50, -360, 360, 1],
        'scroll_rotation' => ['Mobile scroll rotation (degrees)', 83, -360, 360, 1],
    ] as $key => [$label, $default, $min, $max, $step]) {
        $customizer->add_setting('luna_' . $key, [
            'default' => $default,
            'sanitize_callback' => function ($value) use ($default, $min, $max) {
                if (!is_numeric($value)) return $default;
                return min($max, max($min, (float) $value));
            },
        ]);
        $customizer->add_control('luna_' . $key, [
            'label' => $label,
            'section' => 'luna_home_model',
            'type' => 'number',
            'input_attrs' => ['min' => $min, 'max' => $max, 'step' => $step],
        ]);
    }
}
add_action('customize_register', 'luna_customize_register');
