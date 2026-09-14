<?php
if (!defined('ABSPATH')) exit;

define('LUNA_THEME_VERSION', '1.8.0');

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



// Security headers for all WordPress responses.
add_action('send_headers', static function (): void {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header_remove('X-Powered-By');
});

// Consistent favicon and social previews across WordPress and the embedded homepage.
add_action('wp_head', static function (): void {
    $social_image = get_template_directory_uri() . '/lab-home/assets/luna-labs-mark.png';
    echo '<link rel="icon" href="' . esc_url($social_image) . '" type="image/png">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url($social_image) . '">' . "\n";
    if (is_front_page()) {
        echo '<meta name="description" content="Luna Labs 3D is a Malta-based studio creating custom 3D prints, sculpture, painted collectibles and downloadable STL models.">' . "\n";
        echo '<meta property="og:description" content="Custom 3D printing, sculpture, painted collectibles and downloadable STL models from Malta.">' . "\n";
        echo '<meta name="twitter:description" content="Custom 3D printing, sculpture, painted collectibles and downloadable STL models from Malta.">' . "\n";
    }
    echo '<meta property="og:image" content="' . esc_url($social_image) . '">' . "\n";
    echo '<meta property="og:image:alt" content="Luna Labs 3D — Sculpt, Print and Paint">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($social_image) . '">' . "\n";
}, 1);

// Launch-ready SEO fallbacks for pages whose generated metadata is otherwise empty.
function luna_launch_seo_post_id($args = null): int {
    if (is_array($args) && !empty($args['id'])) return (int) $args['id'];
    return (int) get_queried_object_id();
}

function luna_launch_seo_description($args = null): string {
    $post_id = luna_launch_seo_post_id($args);
    if (!$post_id) return '';

    $post = get_post($post_id);
    if (!$post instanceof WP_Post) return '';

    $descriptions = [
        'page:cookie-policy-eu' => 'Learn how Luna Labs 3D uses cookies and similar technologies, and review or change your consent preferences.',
        'll_work:ak-wedding-statue' => 'A personalised wedding statue created by Luna Labs 3D in Malta, from digital sculpting and 3D printing through to the finished keepsake.',
        'll_work:mines-diorama' => 'A commissioned scale model of the Mines building, 3D printed and hand-finished as a personalised display for the client\'s model cars.',
    ];
    $key = $post->post_type . ':' . $post->post_name;
    return $descriptions[$key] ?? '';
}

add_filter('the_seo_framework_description_excerpt', static function ($excerpt, $args) {
    return luna_launch_seo_description($args) ?: $excerpt;
}, 20, 2);

add_filter('the_seo_framework_title_from_generation', static function ($title, $args) {
    $post_id = luna_launch_seo_post_id($args);
    return $post_id && get_post_type($post_id) === 'll_work' && get_post_field('post_name', $post_id) === 'dragon-dice-tower'
        ? 'Dragon Dice Tower Project'
        : $title;
}, 20, 2);

// Preserve the same title and descriptions if the SEO plugin is disabled later.
add_filter('document_title_parts', static function (array $parts): array {
    $post_id = get_queried_object_id();
    if ($post_id && get_post_type($post_id) === 'll_work' && get_post_field('post_name', $post_id) === 'dragon-dice-tower') {
        $parts['title'] = 'Dragon Dice Tower Project';
    }
    return $parts;
}, 20);

add_action('wp_head', static function (): void {
    if (function_exists('tsf')) return;
    $description = luna_launch_seo_description();
    if (!$description) return;
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
}, 2);

// Validate and rate-limit the public quote calculator before its existing REST callback runs.
add_filter('rest_pre_dispatch', static function ($result, WP_REST_Server $server, WP_REST_Request $request) {
    if ($request->get_route() !== '/luna-labs/v1/quote' || $request->get_method() !== 'POST') return $result;
    $data = (array) $request->get_json_params();
    if (!empty($data['website'])) return new WP_Error('luna_spam', 'Unable to process this request.', ['status' => 400]);
    $description = trim(sanitize_textarea_field((string) ($data['description'] ?? '')));
    $method = sanitize_key((string) ($data['contactMethod'] ?? ''));
    $email = sanitize_email((string) ($data['contactEmail'] ?? ''));
    $phone = preg_replace('/[^0-9+() .-]/', '', (string) ($data['contactPhone'] ?? ''));
    if (mb_strlen($description) < 10 || mb_strlen($description) > 2000) return new WP_Error('luna_invalid_description', 'Please provide a description between 10 and 2,000 characters.', ['status' => 400]);
    if (!in_array($method, ['email', 'whatsapp'], true)) return new WP_Error('luna_invalid_contact', 'Please choose email or WhatsApp.', ['status' => 400]);
    if ($method === 'email' && !is_email($email)) return new WP_Error('luna_invalid_email', 'Please enter a valid email address.', ['status' => 400]);
    if ($method === 'whatsapp' && strlen(preg_replace('/\D/', '', $phone)) < 7) return new WP_Error('luna_invalid_phone', 'Please enter a valid WhatsApp number.', ['status' => 400]);
    $rate_key = 'luna_quote_' . substr(wp_hash((string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown')), 0, 24);
    $attempts = (int) get_transient($rate_key);
    if ($attempts >= 20) return new WP_Error('luna_rate_limited', 'Too many requests. Please wait a few minutes and try again.', ['status' => 429]);
    set_transient($rate_key, $attempts + 1, 5 * MINUTE_IN_SECONDS);
    $request->set_param('description', $description);
    $request->set_param('contactMethod', $method);
    $request->set_param('contactEmail', $email);
    $request->set_param('contactPhone', $phone);
    return $result;
}, 9, 3);

// Luna Labs vacation mode.
// Adds WooCommerce > Vacation Mode and closes purchasing while the banner is active.

function luna_vacation_defaults(): array {
    return [
        'enabled'     => 0,
        'message'     => 'We\'re taking a short break. The online shop is temporarily closed, but you\'re welcome to browse.',
        'reopen_date' => '',
    ];
}

function luna_vacation_settings(): array {
    $saved = get_option('luna_vacation_mode', []);
    return wp_parse_args(is_array($saved) ? $saved : [], luna_vacation_defaults());
}

function luna_vacation_is_active(): bool {
    $settings = luna_vacation_settings();
    if (empty($settings['enabled'])) return false;

    $reopen_date = (string) $settings['reopen_date'];
    if ($reopen_date !== '' && current_time('Y-m-d') >= $reopen_date) return false;

    return true;
}

function luna_vacation_message(): string {
    $settings = luna_vacation_settings();
    $message = trim((string) $settings['message']);
    return $message !== '' ? $message : luna_vacation_defaults()['message'];
}

function luna_vacation_sanitize($input): array {
    $defaults = luna_vacation_defaults();
    $input = is_array($input) ? $input : [];
    $date = sanitize_text_field((string) ($input['reopen_date'] ?? ''));

    if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = '';
        add_settings_error('luna_vacation_mode', 'luna_vacation_date', 'Please enter a valid reopen date.');
    }

    return [
        'enabled'     => empty($input['enabled']) ? 0 : 1,
        'message'     => sanitize_textarea_field((string) ($input['message'] ?? $defaults['message'])),
        'reopen_date' => $date,
    ];
}

add_action('admin_init', static function (): void {
    register_setting('luna_vacation_mode', 'luna_vacation_mode', [
        'type'              => 'array',
        'sanitize_callback' => 'luna_vacation_sanitize',
        'default'           => luna_vacation_defaults(),
    ]);
});

add_action('admin_menu', static function (): void {
    add_submenu_page(
        'woocommerce',
        'Vacation Mode',
        'Vacation Mode',
        'manage_woocommerce',
        'luna-vacation-mode',
        'luna_vacation_admin_page'
    );
});

function luna_vacation_admin_page(): void {
    if (!current_user_can('manage_woocommerce')) return;
    $settings = luna_vacation_settings();
    $active = luna_vacation_is_active();
    ?>
    <div class="wrap">
        <h1>Vacation Mode</h1>
        <p>Temporarily close checkout while keeping the catalogue available to browse.</p>
        <?php settings_errors('luna_vacation_mode'); ?>
        <div style="max-width:760px;padding:24px;margin-top:20px;background:#fff;border:1px solid #dcdcde;border-left:5px solid <?php echo $active ? '#7c3aed' : '#8c8f94'; ?>;box-shadow:0 1px 2px rgba(0,0,0,.04)">
            <p style="margin-top:0"><strong>Status:</strong> <?php echo $active ? 'Shop closed — banner visible' : 'Shop open — banner hidden'; ?></p>
            <form action="options.php" method="post">
                <?php settings_fields('luna_vacation_mode'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">Close the shop</th>
                        <td>
                            <label>
                                <input type="checkbox" name="luna_vacation_mode[enabled]" value="1" <?php checked(!empty($settings['enabled'])); ?>>
                                Turn on vacation mode
                            </label>
                            <p class="description">Customers can browse products, but they cannot add items or check out.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="luna-vacation-message">Banner message</label></th>
                        <td>
                            <textarea id="luna-vacation-message" name="luna_vacation_mode[message]" class="large-text" rows="4"><?php echo esc_textarea((string) $settings['message']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="luna-vacation-date">Automatically reopen</label></th>
                        <td>
                            <input id="luna-vacation-date" type="date" name="luna_vacation_mode[reopen_date]" value="<?php echo esc_attr((string) $settings['reopen_date']); ?>">
                            <p class="description">Optional. The shop reopens at midnight on this date, using the website timezone.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save vacation mode'); ?>
            </form>
        </div>
    </div>
    <?php
}

add_action('admin_bar_menu', static function (WP_Admin_Bar $bar): void {
    if (!current_user_can('manage_woocommerce')) return;
    $active = luna_vacation_is_active();
    $bar->add_node([
        'id'    => 'luna-vacation-mode',
        'title' => $active ? 'Vacation mode: ON' : 'Vacation mode: off',
        'href'  => admin_url('admin.php?page=luna-vacation-mode'),
        'meta'  => ['class' => $active ? 'luna-vacation-on' : 'luna-vacation-off'],
    ]);
}, 100);

function luna_vacation_banner(): void {
    static $rendered = false;
    if ($rendered || !luna_vacation_is_active()) return;
    $rendered = true;
    $settings = luna_vacation_settings();
    $is_home = is_front_page();
    ?>
    <aside class="luna-vacation-banner<?php echo $is_home ? ' is-home' : ''; ?>" aria-label="Shop notice">
        <span class="luna-vacation-spark" aria-hidden="true">&#10022;</span>
        <strong>VACATION MODE</strong>
        <span><?php echo esc_html(luna_vacation_message()); ?></span>
        <?php if (!empty($settings['reopen_date'])): ?>
            <span class="luna-vacation-date">Reopening <?php echo esc_html(wp_date(get_option('date_format'), strtotime((string) $settings['reopen_date']))); ?></span>
        <?php endif; ?>
        <span class="luna-vacation-spark" aria-hidden="true">&#10022;</span>
    </aside>
    <?php if ($is_home): ?><script>document.body.classList.add('luna-vacation-home');</script><?php endif; ?>
    <?php
}
add_action('wp_body_open', 'luna_vacation_banner', 1);
add_action('wp_footer', 'luna_vacation_banner', 1);

add_action('wp_head', static function (): void {
    if (!luna_vacation_is_active()) return;
    ?>
    <style id="luna-vacation-mode-css">
        .luna-vacation-banner{position:sticky;top:0;z-index:999999;display:flex;align-items:center;justify-content:center;gap:12px;min-height:48px;padding:10px 24px;box-sizing:border-box;background:#c8ff3d;border-bottom:1px solid rgba(8,9,6,.55);color:#080906;font:700 14px/1.35 system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;letter-spacing:.01em;text-align:center}
        .luna-vacation-banner strong{color:#080906;font-size:12px;letter-spacing:.16em;white-space:nowrap}
        .luna-vacation-spark{color:#080906}
        .luna-vacation-date{padding-left:12px;border-left:1px solid rgba(8,9,6,.35);white-space:nowrap}
        body:not(.luna-vacation-home) .site-header{top:48px}
        body.admin-bar .luna-vacation-banner:not(.is-home){top:32px}
        body.admin-bar:not(.luna-vacation-home) .site-header{top:80px}
        .luna-vacation-banner.is-home{position:fixed;left:0;right:0;top:0}
        body.admin-bar .luna-vacation-banner.is-home{top:32px}
        body.luna-vacation-home #luna-home{height:calc(100% - 48px);margin-top:48px}
        @media(max-width:782px){body.admin-bar .luna-vacation-banner{top:46px}body.admin-bar:not(.luna-vacation-home) .site-header{top:94px}}
        @media(max-width:700px){.luna-vacation-banner{flex-wrap:wrap;gap:4px 8px;min-height:58px;padding:9px 14px;font-size:12px}.luna-vacation-banner strong{width:100%;font-size:10px}.luna-vacation-date{padding-left:8px}.luna-vacation-spark{display:none}body:not(.luna-vacation-home) .site-header{top:58px}body.admin-bar:not(.luna-vacation-home) .site-header{top:104px}body.luna-vacation-home #luna-home{height:calc(100% - 58px);margin-top:58px}}
    </style>
    <?php
}, 30);

add_filter('woocommerce_is_purchasable', static function ($purchasable) {
    return luna_vacation_is_active() ? false : $purchasable;
}, 100);
add_filter('woocommerce_variation_is_purchasable', static function ($purchasable) {
    return luna_vacation_is_active() ? false : $purchasable;
}, 100);

add_filter('woocommerce_add_to_cart_validation', static function ($valid) {
    if (!luna_vacation_is_active()) return $valid;
    wc_add_notice(luna_vacation_message(), 'notice');
    return false;
}, 100);

add_action('woocommerce_checkout_process', static function (): void {
    if (luna_vacation_is_active()) wc_add_notice(luna_vacation_message(), 'error');
}, 1);

add_filter('woocommerce_order_button_html', static function ($html) {
    if (!luna_vacation_is_active()) return $html;
    return '<div class="woocommerce-info">' . esc_html(luna_vacation_message()) . '</div>';
}, 100);

add_action('template_redirect', static function (): void {
    if (!luna_vacation_is_active() || is_admin() || wp_doing_ajax()) return;
    if (function_exists('is_checkout') && is_checkout() && !is_wc_endpoint_url('order-received')) {
        wc_add_notice(luna_vacation_message(), 'notice');
        wp_safe_redirect(wc_get_page_permalink('shop'));
        exit;
    }
}, 1);
