<?php
get_header();
$projects = get_posts([
    'post_type' => 'll_work',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
]);
$terms = get_terms(['taxonomy' => 'll_work_type', 'hide_empty' => true]);
$default_filters = [
    'finished-objects' => 'Finished objects',
    'commissions' => 'Commissions',
    'digital-sculpts' => 'Digital sculpts',
];
?>
<div class="work-page">
  <section class="portfolio-showcase" data-work-carousel>
    <div class="portfolio-intro">
      <div class="portfolio-intro-copy">
        <p class="portfolio-archive-label">
          <span>Selected work · 01—<?php echo esc_html(str_pad((string) count($projects), 2, '0', STR_PAD_LEFT)); ?></span>
          <span>Drag or use the controls</span>
        </p>
        <h1><span>Ideas</span><span>made</span><span>physical.</span></h1>
      </div>
      <p class="portfolio-intro-note">Commissions, personal studies and functional sculptures—each developed from first sketch to finished physical form.</p>
    </div>

    <div class="work-legend" aria-label="Project legend">
      <span><i data-tone="finished"></i>Finished objects</span>
      <span><i data-tone="digital"></i>3D modelling</span>
      <span><i data-tone="studio"></i>Studio object</span>
    </div>

    <div class="portfolio-toolbar">
      <div class="portfolio-filters" aria-label="Filter work">
        <button class="active" type="button" data-work-filter="all">All</button>
        <?php foreach ($default_filters as $slug => $label): ?><button type="button" data-work-filter="<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></button><?php endforeach; ?>
        <?php if (!is_wp_error($terms)): foreach ($terms as $term): ?>
          <?php if (!isset($default_filters[$term->slug])): ?><button type="button" data-work-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button><?php endif; ?>
        <?php endforeach; endif; ?>
      </div>
      <div class="portfolio-toolbar-nav">
        <button type="button" data-work-previous><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span><span>Previous project</span></button>
        <button type="button" data-work-next><span>Next project</span><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></button>
      </div>
    </div>

    <div class="portfolio-rail" tabindex="0" aria-label="Selected work carousel">
      <div class="portfolio-track">
        <?php foreach ($projects as $index => $project):
          $image = get_the_post_thumbnail_url($project, 'large');
          if (!$image) {
              $GLOBALS['post'] = $project;
              setup_postdata($project);
              $image = luna_work_image();
          }
          $project_terms = wp_get_post_terms($project->ID, 'll_work_type', ['fields' => 'slugs']);
          $kind = luna_work_meta('kind', $project->ID) ?: 'Studio object';
          $kind_lower = strtolower($kind);
          $inferred_type = strpos($kind_lower, 'commission') !== false ? 'commissions' : (strpos($kind_lower, 'finished') !== false || in_array($project->post_name, ['dragon-dice-tower', 'chopper-figurine'], true) ? 'finished-objects' : 'digital-sculpts');
          $project_types = is_wp_error($project_terms) ? [] : $project_terms;
          $project_types[] = $inferred_type;
        ?>
          <a class="portfolio-slide <?php echo $index === 0 ? 'is-active' : ''; ?>" href="<?php echo esc_url(get_permalink($project)); ?>" data-work-types="<?php echo esc_attr(implode(' ', array_unique($project_types))); ?>" data-work-kind="<?php echo esc_attr($kind); ?>" data-work-title="<?php echo esc_attr(get_the_title($project)); ?>">
            <?php if ($image): ?><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($project)); ?>" draggable="false"><?php endif; ?>
            <span class="portfolio-slide-index"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <span class="portfolio-slide-copy"><span><?php echo esc_html($kind); ?></span><strong><?php echo esc_html(get_the_title($project)); ?></strong><i>View project <span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></i></span>
          </a>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </div>

    <?php if ($projects): ?>
      <div class="portfolio-controls">
        <div class="portfolio-progress" aria-label="Choose a project">
          <?php foreach ($projects as $index => $project): ?><button class="<?php echo $index === 0 ? 'active' : ''; ?>" type="button" data-work-marker="<?php echo esc_attr((string) $index); ?>" aria-label="View <?php echo esc_attr(get_the_title($project)); ?>"><i></i></button><?php endforeach; ?>
        </div>
        <p><strong data-work-current>01</strong><span>/ <?php echo esc_html(str_pad((string) count($projects), 2, '0', STR_PAD_LEFT)); ?></span></p>
        <small>Choose a marker or use the arrows</small>
        <div class="portfolio-bottom-nav"><button type="button" data-work-previous><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span><span>Previous</span></button><button type="button" data-work-next><span>Next</span><span class="ui-arrow ui-arrow-ne" aria-hidden="true"></span></button></div>
      </div>
    <?php endif; ?>
  </section>
</div>
<?php get_footer(); ?>
