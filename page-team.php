<?php
/**
 * Team: partners row (formal portraits, credentials, bios) + full directory.
 * Titles, credentials and bios pending verification by 828 / Laura; Person
 * schema only includes confirmed titles (inc/seo.php).
 */

get_header();

$staff = array_values(array_filter(apex_team(), function ($m) {
  return empty($m['leadership']);
}));

get_template_part('template-parts/page-hero', null, array(
  'title'   => apex_pages()['team']['heading'],
  'crumb'   => 'Team',
  'eyebrow' => 'Our people',
  'intro'   => 'Tax, accounting, advisory and client service professionals working together on every engagement.',
));
?>

<section class="py-20 lg:py-24" aria-labelledby="partners-title">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Leadership</p>
      <h2 id="partners-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Partners</h2>
    </div>
    <div class="mt-10">
      <?php get_template_part('template-parts/partners'); ?>
    </div>
  </div>
</section>

<section class="border-t border-apex-line bg-apex-cloud py-20 lg:py-24" aria-labelledby="team-title">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <p class="apex-eyebrow text-apex-sky">The team</p>
      <h2 id="team-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Tax, accounting and client service</h2>
    </div>
    <ul class="mt-10 grid grid-cols-2 gap-x-6 gap-y-12 border-t border-apex-ink pt-10 md:grid-cols-3 lg:grid-cols-4" data-reveal-stagger="60" data-rule>
      <?php foreach ($staff as $member) : ?>
        <?php get_template_part('template-parts/team-member', null, array('member' => $member)); ?>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
