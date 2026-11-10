<?php
/**
 * Home page (brief §5.1).
 */

get_header();

$contact  = apex_contact();
$services = apex_services();
$team     = array_slice(apex_team(), 0, 4);

$pillars  = apex_pillars();
?>

<!-- Hero -->
<section class="relative isolate overflow-hidden bg-apex-night text-white" data-replay>
  <div class="absolute inset-y-0 right-0 -z-20 w-full overflow-hidden lg:w-[62%]" aria-hidden="true">
  <div class="absolute -inset-y-16 inset-x-0" data-parallax="0.1">
  <?php
  echo apex_media_img('hero', 'full', array(
    'alt'           => '',
    'sizes'         => '(min-width: 1024px) 62vw, 100vw',
    'loading'       => false,
    'decoding'      => 'async',
    'fetchpriority' => 'high',
    'class'         => 'motion-safe-only size-full animate-hero-zoom object-cover object-[30%_center]',
  ));
  ?>
  </div>
  </div>
  <div class="absolute inset-0 -z-10 bg-apex-night/85 lg:bg-transparent lg:bg-linear-to-r lg:from-apex-night lg:from-38% lg:via-apex-night/75 lg:via-55% lg:to-apex-night/10" aria-hidden="true"></div>

  <div class="apex-container py-16 sm:py-20 lg:py-24">
    <div class="max-w-3xl">
      <p class="motion-safe-only animate-rise apex-eyebrow flex flex-wrap gap-x-3 gap-y-1 tracking-[0.2em] text-white/80 sm:gap-x-4 sm:tracking-[0.3em]">
        <span>Strategy</span><span aria-hidden="true">/</span>
        <span>Accounting</span><span aria-hidden="true">/</span>
        <span>Tax</span><span aria-hidden="true">/</span>
        <span>Advisory</span>
      </p>

      <h1 class="motion-safe-only animate-rise [animation-delay:120ms] mt-6 text-4xl leading-[1.08] font-light tracking-tight sm:text-5xl lg:max-w-2xl lg:text-6xl">
        The structure you don’t see… is what determines your
        <span class="font-semibold text-apex-mist">company’s future.</span>
      </h1>

      <p class="motion-safe-only animate-rise [animation-delay:240ms] mt-6 max-w-xl text-lg leading-relaxed text-white/85">
        We help business owners make confident financial decisions through strategic tax planning, accounting, compliance, and expert business advisory.
      </p>

      <div class="motion-safe-only animate-rise [animation-delay:360ms] mt-10 flex flex-col gap-4 sm:flex-row">
        <a href="<?php echo esc_url($contact['strategyUrl']); ?>" class="apex-btn border border-white/40 bg-apex-navy text-white hover:bg-apex-sky" data-track="cta_strategy_session">
          <?php echo apex_icon('calendar', 'size-5'); ?>
          Schedule a Strategy Session
        </a>
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="apex-btn-outline-light">
          Our Services
          <?php echo apex_icon('arrow-right', 'size-5'); ?>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Value pillars -->
<section aria-label="Why business owners work with APEX" class="border-b border-apex-line bg-apex-cloud">
  <div class="apex-container">
    <ul data-reveal-stagger="110" class="grid gap-y-8 py-10 sm:grid-cols-2 lg:grid-cols-4 lg:divide-x lg:divide-apex-ink/10">
      <?php foreach ($pillars as $pillar) : ?>
        <li data-reveal class="group flex items-start gap-4 lg:px-8 lg:first:pl-0 lg:last:pr-0">
          <span class="text-apex-sky transition-transform duration-500 ease-apex group-hover:-translate-y-1"><?php echo apex_icon($pillar['icon'], 'size-10'); ?></span>
          <div>
            <h2 class="text-lg font-medium text-apex-navy"><?php echo esc_html($pillar['title']); ?></h2>
            <p class="mt-1 text-sm leading-relaxed text-apex-ink/70"><?php echo esc_html($pillar['text']); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- Services preview -->
<section class="py-20 lg:py-28">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:items-center">
    <div class="lg:col-span-4" data-reveal="left">
      <?php echo apex_eyebrow('Our Services'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl">
        Comprehensive Financial Solutions for Your Business
      </h2>
      <p class="mt-5 leading-relaxed text-apex-ink/70">
        A full range of accounting, tax, and advisory services designed to bring structure, compliance, and long-term growth to your business.
      </p>
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="apex-btn-primary mt-8">
        Explore All Services
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
    </div>

    <ul data-reveal-stagger="70" class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 lg:col-span-8">
      <?php foreach ($services as $service) : ?>
        <li data-reveal="zoom">
          <a
            href="<?php echo esc_url($service['url']); ?>"
            class="group flex h-full flex-col items-center justify-center gap-4 rounded-lg border border-apex-line bg-apex-cloud/60 px-4 py-8 text-center transition duration-200 hover:-translate-y-0.5 hover:border-apex-mist hover:bg-white hover:shadow-lg hover:shadow-apex-night/5"
          >
            <span class="text-apex-sky transition duration-300 group-hover:scale-110 group-hover:text-apex-navy"><?php echo apex_icon($service['icon'], 'size-10'); ?></span>
            <span class="font-display leading-snug font-medium text-apex-navy"><?php echo esc_html($service['title']); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- Why APEX + How we work -->
<?php get_template_part('template-parts/how-we-work'); ?>

<!-- Team preview -->
<section class="py-20 lg:py-28">
  <div class="apex-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
      <div class="max-w-2xl" data-reveal>
        <?php echo apex_eyebrow('Our Team'); ?>
        <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl">Meet the people behind APEX</h2>
        <p class="mt-4 leading-relaxed text-apex-ink/70">
          An experienced team guiding clients toward greater clarity, confidence, and financial success.
        </p>
      </div>
      <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline shrink-0 self-start md:self-auto" data-reveal="fade" style="--reveal-delay:200ms">
        Meet the Team
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
    </div>

    <ul data-reveal-stagger="120" class="mt-14 grid grid-cols-2 gap-x-6 gap-y-12 lg:grid-cols-4">
      <?php foreach ($team as $member) : ?>
        <?php get_template_part('template-parts/team-member', null, array('member' => $member)); ?>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- FAQs -->
<section class="border-t border-apex-line py-20 lg:py-28" aria-labelledby="faq-heading">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-4">
      <div class="lg:sticky lg:top-32" data-reveal="left">
        <?php echo apex_eyebrow('FAQs'); ?>
        <h2 id="faq-heading" class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl">Frequently Asked Questions</h2>
        <p class="mt-5 leading-relaxed text-apex-ink/70">
          Answers to common questions about working with APEX. Don’t see yours? Our team is happy to help.
        </p>
        <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="mt-8 inline-flex items-center gap-2 font-display font-medium text-apex-navy transition-colors hover:text-apex-sky">
          Contact our team
          <?php echo apex_icon('arrow-right', 'size-5'); ?>
        </a>
      </div>
    </div>

    <div class="divide-y divide-apex-line border-y border-apex-line lg:col-span-8" data-reveal-stagger="80">
      <?php foreach (apex_home_faqs() as $i => $faq) : ?>
        <details class="apex-faq group" name="home-faq" data-reveal <?php echo $i === 0 ? 'open' : ''; ?>>
          <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 font-display text-lg font-medium text-apex-navy transition-colors hover:text-apex-sky [&::-webkit-details-marker]:hidden">
            <?php echo esc_html($faq['q']); ?>
            <span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-apex-line text-apex-sky transition duration-300 ease-apex group-open:rotate-180 group-open:border-apex-navy group-open:bg-apex-navy group-open:text-white">
              <?php echo apex_icon('chevron-down', 'size-5'); ?>
            </span>
          </summary>
          <p class="max-w-3xl pb-6 pr-14 leading-relaxed text-apex-ink/70"><?php echo esc_html($faq['a']); ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
