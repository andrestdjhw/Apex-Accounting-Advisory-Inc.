<?php

get_header(); ?>

<div class="apex-container py-16 lg:py-24">
  <?php while (have_posts()) {
    the_post(); ?>
    <article class="prose max-w-3xl">
      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </article>
  <?php } ?>
</div>

<?php get_footer();
