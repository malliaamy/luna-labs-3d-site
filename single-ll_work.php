<?php
get_header();
the_post();
$kind = luna_work_meta('kind') ?: 'Selected work';
$intro = luna_work_meta('intro') ?: get_the_excerpt();
$process = luna_work_meta('process');
$outcome = luna_work_meta('outcome');
$gallery = array_filter(array_map('trim', explode("\n", luna_work_meta('gallery'))));
$image = luna_work_image('full');
$previous = get_previous_post();
$next = get_next_post();
?>
<article class="project-page project-page-lime">
  <header class="project-heading">
    <div class="page-kicker"><span><?php echo esc_html($kind); ?></span><span><?php echo esc_html(get_the_date('Y')); ?></span></div>
    <h1><?php the_title(); ?></h1>
    <p><?php echo esc_html($intro); ?></p>
  </header>
  <?php if ($image): ?><div class="project-hero-image"><img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"><span>01 / Object study</span></div><?php endif; ?>

  <?php if (get_post_field('post_name') === 'dragon-dice-tower'): ?>
    <section class="project-product-route">
      <div><span>Customizable product</span><h2>Choose every component colour in 3D.</h2></div>
      <p>Build a palette for the tray, tower, dragon, tail and both wings on the fully assembled model before continuing to checkout.</p>
      <a href="<?php echo esc_url(home_url('/product/dragon-dice-tower/')); ?>">Customize this tower <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
    </section>
  <?php endif; ?>

  <section class="project-process">
    <div class="project-process-intro"><div><span>02 / Process</span><h2>How the object took shape.</h2></div><p><?php echo wp_kses_post($outcome ? wpautop($outcome) : 'From digital sculpt to a carefully finished physical object, every stage was handled in the Luna Labs studio.'); ?></p></div>
    <ol>
      <li><span>01</span><p><?php echo wp_kses_post($process ? wpautop($process) : 'The concept, silhouette and functional requirements were developed before the final sculpt was prepared.'); ?></p></li>
      <li><span>02</span><p>The geometry was checked, split and prepared for reliable production while preserving the details that define the piece.</p></li>
      <li><span>03</span><p>The parts were printed, assembled, surface-finished and completed by hand for a durable, display-ready result.</p></li>
    </ol>
  </section>

  <?php if ($gallery): ?>
    <section class="project-gallery">
      <div class="page-kicker"><span>03 / Project views</span><span><?php echo esc_html(str_pad((string) count($gallery), 2, '0', STR_PAD_LEFT)); ?> images</span></div>
      <div><?php foreach ($gallery as $index => $gallery_image): ?><figure><img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr(get_the_title() . ' view ' . ($index + 2)); ?>"><figcaption><?php echo esc_html(str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT) . ' / ' . get_the_title()); ?></figcaption></figure><?php endforeach; ?></div>
    </section>
  <?php endif; ?>

  <?php if (trim(get_the_content())): ?><section class="work-content"><?php the_content(); ?></section><?php endif; ?>

  <nav class="project-next" aria-label="Project navigation">
    <?php if ($previous): ?><a href="<?php echo esc_url(get_permalink($previous)); ?>"><span>Previous project</span><strong><?php echo esc_html(get_the_title($previous)); ?></strong><span class="ui-arrow ui-arrow-up" aria-hidden="true"></span></a><?php else: ?><span></span><?php endif; ?>
    <a href="<?php echo esc_url(get_post_type_archive_link('ll_work')); ?>"><span>Project archive</span><strong>All work</strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a>
    <?php if ($next): ?><a href="<?php echo esc_url(get_permalink($next)); ?>"><span>Next project</span><strong><?php echo esc_html(get_the_title($next)); ?></strong><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></a><?php else: ?><span></span><?php endif; ?>
  </nav>
</article>
<?php get_footer(); ?>
