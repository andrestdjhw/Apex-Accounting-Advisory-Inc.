<?php

get_header(); ?>

<div class="apex-container py-16 lg:py-24">
  <div class="prose max-w-3xl">
    <?php if (have_posts()) {
      while (have_posts()) {
        the_post(); ?>
        <article>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php the_excerpt(); ?>
        </article>
      <?php }
    } ?>
  </div>
</div>

<?php get_footer();
