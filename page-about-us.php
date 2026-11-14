<?php
/**
 * About Us. Keeps the published concepts: Strategic Financial Guidance,
 * Building Long-Term Success, Mission, Vision and the four values.
 * No founding story, licenses or track record is added (not provided by APEX).
 */

get_header();

get_template_part('template-parts/page-hero', null, array(
  'title'   => apex_pages()['about-us']['heading'],
  'crumb'   => 'About Us',
  'eyebrow' => 'About APEX',
  'intro'   => 'The strategy, expertise and values behind every engagement.',
));
?>

<!-- Approach -->
<section class="py-20 lg:py-24">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-5" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Our approach</p>
      <h2 class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Strategic financial guidance</h2>
    </div>
    <div class="space-y-5 text-lg leading-relaxed text-apex-ink/80 lg:col-span-7" data-reveal>
      <p>APEX Accounting &amp; Advisory helps businesses and individuals make confident financial decisions through proactive planning, expert accounting, and personalized advisory services.</p>
      <p>Rather than simply managing numbers, the firm develops financial strategies designed around each client’s goals, risks and opportunities for sustainable growth.</p>
      <p>With a commitment to precision, integrity, and lasting partnerships, APEX works alongside clients to navigate complex financial challenges with confidence. From tax planning and financial reporting to business advisory and compliance, the firm delivers services tailored to each client’s needs.</p>
      <div class="flex flex-wrap gap-4 pt-4">
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="apex-btn-primary">
          Explore our services
          <?php echo apex_icon('arrow-right', 'size-5'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline">Meet the team</a>
      </div>
    </div>
  </div>
</section>

<!-- Mission & vision -->
<section class="border-y border-apex-line bg-apex-cloud">
  <div class="apex-container grid md:grid-cols-2">
    <article class="border-b border-apex-line py-16 md:border-r md:border-b-0 md:pr-12" data-reveal="left">
      <p class="apex-eyebrow text-apex-sky">Our mission</p>
      <p class="mt-5 font-display text-2xl leading-snug text-apex-ink">Empower businesses through strategic financial guidance, proactive planning, and trusted expertise that builds lasting success, confidence, profitability, and growth.</p>
    </article>
    <article class="py-16 md:pl-12" data-reveal="right" style="--reveal-delay:120ms">
      <p class="apex-eyebrow text-apex-sky">Our vision</p>
      <p class="mt-5 font-display text-2xl leading-snug text-apex-ink">Become the trusted financial advisory firm inspiring stronger businesses through innovative strategies, sustainable growth, lasting partnerships, and exceptional client success.</p>
    </article>
  </div>
</section>

<!-- Values -->
<section class="py-20 lg:py-24" aria-labelledby="values-title">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Our values</p>
      <h2 id="values-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">The principles behind every recommendation</h2>
    </div>
    <?php // Rows are shared across columns (subgrid) so photo, title, text and "what it means" line up. ?>
    <dl class="mt-10 grid border-t border-apex-ink sm:grid-cols-2 lg:grid-cols-4 lg:grid-rows-[auto_auto_auto_auto]" data-reveal-stagger="90" data-rule>
      <?php foreach (apex_values() as $i => $value) : ?>
        <div class="flex flex-col border-b border-apex-line pt-8 pb-10 sm:pr-8 lg:row-span-4 lg:grid lg:grid-rows-subgrid lg:border-b-0 <?php echo $i ? 'lg:border-l lg:pl-8' : ''; ?> <?php echo $i % 2 ? 'sm:border-l sm:pl-8 lg:border-l' : ''; ?>" data-reveal>
          <div class="mb-7 overflow-hidden" data-reveal="curtain">
            <div class="aspect-[4/3] overflow-hidden bg-apex-cloud lg:aspect-auto lg:h-52 xl:h-56">
              <?php
              echo apex_media_img('value-' . sanitize_title($value['title']), 'large', array(
                'sizes' => '(min-width: 1024px) 22vw, (min-width: 640px) 45vw, 100vw',
                'class' => 'size-full object-cover transition-transform duration-700 ease-apex hover:scale-105',
              ));
              ?>
            </div>
          </div>
          <dt class="font-display text-xl font-semibold text-apex-ink"><?php echo esc_html($value['title']); ?></dt>
          <dd class="mt-3 leading-relaxed text-apex-ink/75"><?php echo esc_html($value['text']); ?></dd>
          <dd class="mt-6 border-t border-apex-line pt-5">
            <span class="apex-eyebrow block text-apex-ink/55">What it means for you</span>
            <span class="mt-2 block text-[15px] leading-relaxed text-apex-navy"><?php echo esc_html($value['client']); ?></span>
          </dd>
        </div>
      <?php endforeach; ?>
    </dl>
  </div>
</section>

<!-- Partners -->
<section class="border-t border-apex-line py-20 lg:py-24" aria-labelledby="partners-title">
  <div class="apex-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between" data-reveal>
      <div class="max-w-2xl">
        <p class="apex-eyebrow text-apex-sky">Leadership</p>
        <h2 id="partners-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">The partners leading APEX</h2>
      </div>
      <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline shrink-0 self-start md:self-auto">
        Meet the full team
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
    </div>
    <div class="mt-10">
      <?php get_template_part('template-parts/partners'); ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
