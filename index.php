<?php get_header(); ?>
<?php if (is_404()): ?>
<main id="primary" class="error-404">
  <span class="error-code">Error 404</span>
  <h1><?php esc_html_e('That object escaped the lab.', 'luna-labs'); ?></h1>
  <p><?php esc_html_e('The page may have moved or no longer exists. Return home, browse the shop, or start a custom project.', 'luna-labs'); ?></p>
  <div class="error-404-actions">
    <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Return home', 'luna-labs'); ?></a>
    <a class="button" href="<?php echo esc_url(home_url('/shop/')); ?>"><?php esc_html_e('Browse the shop', 'luna-labs'); ?></a>
    <a class="button" href="<?php echo esc_url(home_url('/services/#quote')); ?>"><?php esc_html_e('Start a project', 'luna-labs'); ?></a>
  </div>
</main>
<?php else: ?>
<section class="ll-standard-page"><?php if (have_posts()): while (have_posts()): the_post(); ?><article><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1><?php the_excerpt(); ?></article><?php endwhile; else: ?><h1>Nothing found.</h1><?php endif; ?></section>
<?php endif; ?>
<?php get_footer(); ?>
