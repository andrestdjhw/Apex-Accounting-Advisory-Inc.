<?php
/**
 * Default page template (legal pages and any page without its own template).
 */

get_header();

while (have_posts()) :
  the_post();

  get_template_part('template-parts/page-hero', null, array(
    'title'   => get_the_title(),
    'eyebrow' => $post->post_name === 'privacy-policy' || $post->post_name === 'terms-and-conditions' ? 'Legal' : 'Apex Accounting & Advisory',
  ));
  ?>

  <article class="py-16 lg:py-24">
    <div class="apex-container">
      <div class="prose prose-lg mx-auto max-w-3xl prose-headings:font-display prose-headings:font-semibold prose-headings:text-apex-ink prose-a:text-apex-navy prose-strong:text-apex-ink" data-reveal>
        <?php the_content(); ?>
      </div>
    </div>
  </article>

<?php endwhile;

get_footer();
