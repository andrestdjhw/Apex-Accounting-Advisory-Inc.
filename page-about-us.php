<?php
/**
 * About Us (brief §5.2). Keeps the published concepts: Strategic Financial
 * Guidance, Building Long-Term Success, Mission, Vision and the four values.
 * No founding story, licenses or track record is added (not provided by APEX).
 */

get_header();

$leaders = array_values(array_filter(apex_team(), function ($member) {
  return !empty($member['leadership']);
}));

get_template_part('template-parts/page-hero', null, array(
  'title'   => 'About Us',
  'eyebrow' => 'Who we are',
  'intro'   => 'Discover the strategy, expertise, and values driving stronger financial futures for every client.',
));
?>

<!-- Strategic Financial Guidance -->
<section class="py-20 lg:py-28">
  <div class="apex-container grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-20">
    <div data-reveal="left">
      <?php echo apex_eyebrow('Our approach'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl" data-split>Strategic Financial Guidance</h2>
      <div class="mt-6 space-y-5 text-lg leading-relaxed text-apex-ink/75">
        <p>APEX Accounting &amp; Advisory helps businesses and individuals make confident financial decisions through proactive planning, expert accounting, and personalized advisory services.</p>
        <p>Rather than simply managing numbers, the firm develops financial strategies designed around each client’s goals, risks and opportunities for sustainable growth.</p>
      </div>
      <div class="mt-8 flex flex-col gap-4 sm:flex-row">
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="apex-btn-primary">
          Explore Our Services
          <?php echo apex_icon('arrow-right', 'size-5'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline">Meet the Team</a>
      </div>
    </div>

    <div class="relative" data-reveal="right">
      <div data-reveal="curtain" style="--reveal-delay:150ms">
      <div class="overflow-hidden rounded-2xl">
        <?php
        echo apex_media_img('hero', 'large', array(
          'alt'   => 'APEX Accounting & Advisory signage on the firm’s office building',
          'sizes' => '(min-width: 1024px) 50vw, 100vw',
          'class' => 'aspect-[4/3] w-full object-cover object-[30%_center] transition duration-700 ease-apex hover:scale-105',
        ));
        ?>
      </div>
      </div>
      <div class="absolute -bottom-6 -left-6 -z-10 hidden size-40 rounded-2xl bg-apex-cloud lg:block" aria-hidden="true"></div>
    </div>
  </div>
</section>

<!-- Building Long-Term Success -->
<section class="bg-apex-cloud py-20 lg:py-28">
  <div class="apex-container grid gap-12 lg:grid-cols-12">
    <div class="lg:col-span-5" data-reveal>
      <?php echo apex_eyebrow('Long-term partnership'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl" data-split>Building Long-Term Success</h2>
    </div>
    <div class="space-y-5 text-xl leading-relaxed text-apex-navy lg:col-span-7" data-scrub>
      <p>With a commitment to precision, integrity, and lasting partnerships, APEX works alongside clients to navigate complex financial challenges with confidence.</p>
      <p>From tax planning and financial reporting to business advisory and compliance, the firm delivers services tailored to each client’s needs.</p>
    </div>
  </div>

  <div class="apex-container mt-16 grid gap-6 md:grid-cols-2" data-reveal-stagger="150">
    <article class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-apex-line lg:p-10" data-reveal>
      <span class="flex size-12 items-center justify-center rounded-full bg-apex-cloud text-apex-sky"><?php echo apex_icon('target', 'size-6'); ?></span>
      <h3 class="mt-6 text-2xl font-medium text-apex-navy">Our Mission</h3>
      <p class="mt-4 leading-relaxed text-apex-ink/75">Empower businesses through strategic financial guidance, proactive planning, and trusted expertise that builds lasting success, confidence, profitability, and growth.</p>
    </article>
    <article class="rounded-2xl bg-apex-night p-8 text-white shadow-sm lg:p-10" data-reveal>
      <span class="flex size-12 items-center justify-center rounded-full bg-white/10 text-apex-mist"><?php echo apex_icon('compass', 'size-6'); ?></span>
      <h3 class="mt-6 text-2xl font-medium">Our Vision</h3>
      <p class="mt-4 leading-relaxed text-white/75">Become the trusted financial advisory firm inspiring stronger businesses through innovative strategies, sustainable growth, lasting partnerships, and exceptional client success.</p>
    </article>
  </div>
</section>

<!-- Values -->
<section class="relative isolate overflow-hidden bg-apex-night py-20 text-white lg:py-28">
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_left,var(--color-apex-blue)_0%,transparent_50%)] opacity-50" aria-hidden="true"></div>
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <?php echo apex_eyebrow('Our values', 'dark'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium sm:text-4xl" data-split>The Principles Behind Every Financial Strategy</h2>
      <p class="mt-5 leading-relaxed text-white/75">The values guiding every recommendation, relationship, and financial strategy we deliver.</p>
    </div>

    <ul class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger="110">
      <?php foreach (apex_values() as $value) : ?>
        <li class="flex" data-reveal>
          <div class="apex-tilt group flex flex-1 flex-col rounded-2xl bg-white p-7 text-apex-ink shadow-xl shadow-black/20 hover:shadow-2xl hover:shadow-black/30" data-tilt="8">
          <span class="flex size-14 items-center justify-center rounded-xl bg-apex-cloud text-apex-sky transition duration-500 ease-apex group-hover:-translate-y-1 group-hover:bg-apex-navy group-hover:text-white"><?php echo apex_icon($value['icon'], 'size-7'); ?></span>
          <h3 class="mt-6 text-xl font-medium text-apex-navy"><?php echo esc_html($value['title']); ?></h3>
          <p class="mt-3 text-sm leading-relaxed text-apex-ink/70"><?php echo esc_html($value['text']); ?></p>
          <div class="mt-auto pt-6">
            <div class="border-t border-apex-line pt-5 text-sm leading-relaxed text-apex-navy">
              <span class="block font-display text-xs font-medium tracking-[0.2em] text-apex-sky uppercase">What it means for you</span>
              <span class="mt-2 block"><?php echo esc_html($value['client']); ?></span>
            </div>
          </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- Leadership -->
<section class="py-20 lg:py-28">
  <div class="apex-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
      <div class="max-w-2xl" data-reveal>
        <?php echo apex_eyebrow('Leadership'); ?>
        <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl" data-split>The people leading APEX</h2>
      </div>
      <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline shrink-0 self-start md:self-auto" data-reveal="fade" style="--reveal-delay:200ms">
        Meet the Full Team
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
    </div>

    <ul class="mt-14 grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-3" data-reveal-stagger="120">
      <?php foreach ($leaders as $member) : ?>
        <?php get_template_part('template-parts/team-member', null, array('member' => $member, 'size' => 'lg')); ?>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
