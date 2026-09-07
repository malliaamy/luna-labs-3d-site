<?php
defined('ABSPATH') || exit;
global $product;
if (!$product instanceof WC_Product) {
    $product = wc_get_product(get_queried_object_id());
}
$parts = [
    'dice_tray' => ['01', 'Dice tray', 'The lower catch tray', 'Charcoal'],
    'stone_tower' => ['02', 'Stone tower', 'The central dice route', 'Granite'],
    'dragon_body' => ['03', 'Dragon body', 'The main creature form', 'Crimson'],
    'dragon_head' => ['04', 'Dragon head', 'Face, horns and neck', 'Crimson'],
    'dragon_tail' => ['05', 'Dragon tail', 'The lower wrapped tail', 'Crimson'],
    'front_wing' => ['06', 'Front wing', 'The forward-facing wing', 'Ochre'],
    'back_wing' => ['07', 'Back wing', 'The rear wing', 'Ruby'],
];
$colours = [
    'Charcoal' => '#272c30', 'Granite' => '#5b6168', 'Pewter' => '#8d9399', 'Bone' => '#d8c9aa',
    'Crimson' => '#ad2d2d', 'Ruby' => '#c23c3c', 'Wine' => '#721d2e', 'Ochre' => '#ba7c26',
    'Gold' => '#d4a72c', 'Forest' => '#365742', 'Moss' => '#5d7047', 'Violet' => '#6d4ca8', 'Azure' => '#3d7ca6',
];
?>
<article class="product-page dragon-product-page" data-dragon-configurator data-model-url="<?php echo esc_url(get_template_directory_uri() . '/lab-home/assets/dragon-tower-web-v2.glb?v=' . LUNA_THEME_VERSION); ?>" data-fallback-url="<?php echo esc_url(get_template_directory_uri() . '/lab-home/assets/dragon-tower-02.jpg'); ?>">
  <div class="product-back-row">
    <a class="product-back-link" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">← Back to shop</a>
    <span>DnD / Dragon Dice Tower</span>
  </div>

  <section class="tower-configurator">
    <div class="tower-configurator-heading">
      <div class="page-kicker"><span>Customizable tabletop object</span><span>Live colour preview</span></div>
      <h1>Build your Dragon Dice Tower.</h1>
      <p>Choose a colour for each printed component, rotate the assembled tower, and add your finished palette to the order.</p>
    </div>

    <div class="tower-configurator-layout">
      <div class="tower-preview">
        <div class="tower-preview-canvas" data-dragon-canvas aria-label="Interactive assembled Dragon Dice Tower colour preview. Drag or swipe sideways to rotate.">
          <div class="model-status" data-dragon-status><span></span>Preparing the sculpt</div>
        </div>
        <div class="tower-preview-toolbar"><span>Drag or swipe to rotate</span><button type="button" data-dragon-reset>Reset view</button></div>
      </div>

      <form class="tower-controls cart" method="post" enctype="multipart/form-data">
        <div class="tower-controls-intro">
          <span>Step 01 / Choose what to colour</span>
          <div class="tower-selection-summary">
            <i data-selected-swatch style="background:#272c30"></i>
            <div><small>Currently editing</small><strong data-selected-label>Dice tray</strong><p data-selected-description>The lower catch tray</p></div>
          </div>
        </div>
        <div class="tower-part-list">
          <?php foreach ($parts as $key => [$number, $label, $description, $default]): ?>
            <button type="button" class="<?php echo $key === 'dice_tray' ? 'active' : ''; ?>" data-dragon-part="<?php echo esc_attr($key); ?>">
              <span><?php echo esc_html($number); ?></span><span class="tower-part-name"><strong><?php echo esc_html($label); ?></strong><small><?php echo esc_html($description); ?></small></span>
              <i data-part-swatch="<?php echo esc_attr($key); ?>" style="background:<?php echo esc_attr($colours[$default]); ?>"></i>
            </button>
            <input type="hidden" name="luna_dragon_colours[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($default); ?>" data-dragon-input="<?php echo esc_attr($key); ?>">
          <?php endforeach; ?>
        </div>
        <fieldset class="tower-colours">
          <legend>Step 02 / Pick a colour</legend>
          <div class="tower-palette-heading"><div><span>Studio palette</span><strong data-colour-name>Charcoal</strong></div><i data-colour-swatch style="background:#272c30"></i></div>
          <div class="tower-palette-groups">
            <div class="tower-palette-group"><span>Foundations</span><div>
              <?php foreach (array_slice($colours, 0, 4, true) as $name => $hex): ?><button type="button" data-dragon-colour="<?php echo esc_attr($name); ?>" data-colour-hex="<?php echo esc_attr($hex); ?>" aria-label="<?php echo esc_attr($name); ?>" style="--swatch:<?php echo esc_attr($hex); ?>"><i></i><small><?php echo esc_html($name); ?></small></button><?php endforeach; ?>
            </div></div>
            <div class="tower-palette-group"><span>Dragon tones</span><div>
              <?php foreach (array_slice($colours, 4, 5, true) as $name => $hex): ?><button type="button" data-dragon-colour="<?php echo esc_attr($name); ?>" data-colour-hex="<?php echo esc_attr($hex); ?>" aria-label="<?php echo esc_attr($name); ?>" style="--swatch:<?php echo esc_attr($hex); ?>"><i></i><small><?php echo esc_html($name); ?></small></button><?php endforeach; ?>
            </div></div>
            <div class="tower-palette-group"><span>Deep colours</span><div>
              <?php foreach (array_slice($colours, 9, null, true) as $name => $hex): ?><button type="button" data-dragon-colour="<?php echo esc_attr($name); ?>" data-colour-hex="<?php echo esc_attr($hex); ?>" aria-label="<?php echo esc_attr($name); ?>" style="--swatch:<?php echo esc_attr($hex); ?>"><i></i><small><?php echo esc_html($name); ?></small></button><?php endforeach; ?>
            </div></div>
          </div>
        </fieldset>
        <div class="tower-order">
          <div><span>Made to order · estimated studio time 1–2 weeks</span><strong><?php echo wp_kses_post($product->get_price_html()); ?></strong></div>
          <button class="product-buy-button" type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">Add to cart <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></button>
          <small>Every component colour is saved by name in the cart, order, and order email so the studio receives your exact palette.</small>
        </div>
      </form>
    </div>
  </section>

  <section class="product-story">
    <div class="product-story-kicker"><span>Object notes</span></div>
    <div class="product-story-grid">
      <h2>Built to roll. Painted to your palette.</h2>
      <div class="product-description">
        <p>Hand-crafted, made-to-order dice tower designed and produced in-house. The model is digitally sculpted, then 3D-printed at high resolution, carefully aligned and bonded, sanded for a smooth internal chute, primed, hand-painted, and sealed for durability. Stands at approximately 26 cm tall.</p>
        <p>Production is by preorder: please allow 1–2 weeks for printing, assembly, and painting before delivery. Includes one dice tower; dice are not included. Minor variations are normal with handcrafted finishes. Local delivery is available.</p>
      </div>
    </div>
  </section>

  <?php $gallery_ids = $product instanceof WC_Product ? $product->get_gallery_image_ids() : array(); ?>
  <?php if ( ! empty( $gallery_ids ) ) : ?>
  <section class="product-gallery">
    <div class="product-story-kicker"><span>Gallery</span></div>
    <div class="product-gallery-grid">
      <?php foreach ( $gallery_ids as $gallery_image_id ) : ?>
        <figure class="product-gallery-item"><?php echo wp_get_attachment_image( $gallery_image_id, 'large' ); ?></figure>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
</article>
<script type="module" src="<?php echo esc_url(get_template_directory_uri() . '/assets/js/dragon-customizer.js?ver=' . LUNA_THEME_VERSION); ?>"></script>
