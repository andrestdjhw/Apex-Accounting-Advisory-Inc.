<?php
/**
 * Services hub: index of the eight services, each with its own page
 * (/services/{slug}/, template page-service.php).
 */

get_header();

get_template_part('template-parts/page-hero', null, array(
  'title'   => apex_pages()['services']['heading'],
  'crumb'   => 'Services',
  'eyebrow' => 'Services',
  'intro'   => 'Accounting, tax and advisory services designed to bring structure, clarity and confidence to the financial side of your business.',
  'actions' => true,
));
?>

<section class="py-20 lg:py-24" aria-label="All services">
  <div class="apex-container">
    <?php get_template_part('template-parts/services-index'); ?>
  </div>
</section>

<?php get_template_part('template-parts/how-we-work'); ?>

<section class="py-10">
  <div class="apex-container">
    <p class="max-w-4xl text-sm leading-relaxed text-apex-ink/60">
      The information on this site is a general description of our services and is not tax, legal or financial advice for your specific situation. Services, scope and deliverables are defined individually with each client.
    </p>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
