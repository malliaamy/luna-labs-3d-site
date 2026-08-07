<?php get_header(); the_post(); ?>
<section class="about-hero">
  <div class="about-portrait"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/amy-mallia.png'); ?>" alt="Amy Mallia, founder of Luna Labs"></div>
  <div class="about-intro">
    <div class="page-kicker"><span>About the studio</span><span>Malta · Independent</span></div>
    <h1>Amy<span>Mallia.</span></h1>
    <p>I'm the artist behind Luna Labs. I create digital sculptures, custom 3D models and finished printed pieces with a focus on character, detail and making ideas feel tangible.</p>
  </div>
</section>
<section class="about-story">
  <p class="about-story-lead">The work sits between digital art and fabrication: sculpting the object on screen is only half the story.</p>
  <div><p>What started as an interest in design and visual storytelling grew into a personal studio where a sketch or reference can become a resolved physical piece.</p><p>A First-Class degree in Game Art &amp; Visual Design shaped the way I approach form, silhouette and visual storytelling. Each model is designed with both aesthetics and the realities of printing in mind.</p><?php the_content(); ?></div>
</section>
<section class="about-process">
  <div class="page-kicker"><span>The working method</span><span>Digital thinking · Physical result</span></div>
  <div class="about-process-grid">
    <article><span>01</span><h2>Concept &amp; planning</h2><p>References, style, shape language and purpose.</p></article>
    <article><span>02</span><h2>Digital sculpting</h2><p>Form built for character and physical production.</p></article>
    <article><span>03</span><h2>Preparing for print</h2><p>Clean geometry, sensible splits and print planning.</p></article>
    <article><span>04</span><h2>Printing &amp; finishing</h2><p>Production, refinement, acrylic paint and varnish.</p></article>
  </div>
</section>
<?php get_footer(); ?>
