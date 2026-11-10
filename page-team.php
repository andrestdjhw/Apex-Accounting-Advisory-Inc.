<?php
/**
 * Team (brief §5.4). Directory as published on /team/.
 * PENDING CLIENT VALIDATION: names, titles and credentials. Person schema is
 * intentionally not output until APEX confirms each title.
 */

get_header();

get_template_part('template-parts/page-hero', null, array(
  'title'   => 'Team',
  'eyebrow' => 'Our people',
  'intro'   => 'Learn about the experienced team guiding clients toward greater clarity, confidence, and financial success.',
));
?>

<section class="py-20 lg:py-28">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <?php echo apex_eyebrow('Meet the team'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl">The people behind your financial structure</h2>
      <p class="mt-5 leading-relaxed text-apex-ink/70">Tax, accounting, advisory and client service professionals working together on every engagement.</p>
    </div>

    <ul class="mt-16 grid grid-cols-2 gap-x-6 gap-y-14 md:grid-cols-3 lg:grid-cols-4" data-reveal-stagger="80">
      <?php foreach (apex_team() as $member) : ?>
        <?php get_template_part('template-parts/team-member', null, array('member' => $member)); ?>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="bg-apex-navy py-16 text-white lg:py-20">
  <div class="apex-container flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
    <div class="max-w-2xl" data-reveal>
      <h2 class="text-3xl leading-tight font-light sm:text-4xl">Work with a team that <span class="font-semibold">knows your business.</span></h2>
      <p class="mt-4 leading-relaxed text-white/75">See how our services fit together, or learn more about the values that guide our work.</p>
    </div>
    <div class="flex flex-col gap-4 sm:flex-row" data-reveal="right">
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="apex-btn bg-white text-apex-navy hover:bg-apex-mist">
        Our Services
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
      <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="apex-btn-outline-light">About APEX</a>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
