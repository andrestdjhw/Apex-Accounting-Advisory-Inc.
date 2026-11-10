<?php
/**
 * Services (brief §5.3). One section per service with its own anchor
 * (/services/#slug), following the brief's template: benefit → who it's for →
 * what's included → how APEX works → CTA → related services.
 * Individual service URLs are P2 (subject to approval).
 */

get_header();

$services = apex_services();
$contact  = apex_contact();

get_template_part('template-parts/page-hero', null, array(
  'title'   => 'Services',
  'eyebrow' => 'Strategy / Accounting / Tax / Advisory',
  'intro'   => 'Accounting, tax and advisory services designed to bring structure, clarity and confidence to the financial side of your business.',
));
?>

<!-- Service index (sticky) -->
<nav aria-label="Services on this page" class="sticky top-20 z-40 border-b border-apex-line bg-white/95 backdrop-blur">
  <div class="apex-container">
    <ul class="-mx-1 flex gap-1 overflow-x-auto py-3 [scrollbar-width:none]" data-service-nav>
      <?php foreach ($services as $service) : ?>
        <li class="shrink-0">
          <a href="#<?php echo esc_attr($service['slug']); ?>" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm whitespace-nowrap text-apex-ink/70 transition-colors hover:bg-apex-cloud hover:text-apex-navy aria-[current=true]:bg-apex-navy aria-[current=true]:text-white">
            <?php echo apex_icon($service['icon'], 'size-4'); ?>
            <?php echo esc_html($service['title']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>

<!-- Overview grid -->
<section class="py-20 lg:py-24">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <?php echo apex_eyebrow('What we do'); ?>
      <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl" data-split>Eight ways we support your business</h2>
      <p class="mt-5 leading-relaxed text-apex-ink/70">Each engagement is scoped to your needs. Many clients combine services, so planning, accounting and compliance work from the same information.</p>
    </div>

    <ul class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger="70">
      <?php foreach ($services as $service) : ?>
        <li data-reveal="zoom">
          <a href="#<?php echo esc_attr($service['slug']); ?>" class="group flex h-full flex-col rounded-xl border border-apex-line bg-apex-cloud/60 p-6 transition duration-200 hover:-translate-y-0.5 hover:border-apex-mist hover:bg-white hover:shadow-lg hover:shadow-apex-night/5">
            <span class="text-apex-sky transition duration-300 group-hover:scale-110 group-hover:text-apex-navy"><?php echo apex_icon($service['icon'], 'size-9'); ?></span>
            <span class="mt-5 font-display text-lg font-medium text-apex-navy"><?php echo esc_html($service['title']); ?></span>
            <span class="mt-2 text-sm leading-relaxed text-apex-ink/70"><?php echo esc_html($service['summary']); ?></span>
            <span class="mt-auto flex items-center gap-2 pt-5 font-display text-sm font-medium text-apex-sky">
              Learn more <?php echo apex_icon('arrow-right', 'size-4 transition-transform group-hover:translate-x-1'); ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- Service details -->
<?php foreach ($services as $i => $service) : ?>
  <?php $alt = $i % 2 === 0; ?>
  <section id="<?php echo esc_attr($service['slug']); ?>" class="scroll-mt-40 border-t border-apex-line py-20 lg:py-24 <?php echo $alt ? 'bg-apex-cloud/50' : ''; ?>" aria-labelledby="<?php echo esc_attr($service['slug']); ?>-title" data-service-section>
    <div class="apex-container grid gap-12 lg:grid-cols-12 lg:items-center lg:gap-16">
      <div class="lg:col-span-7" data-reveal="<?php echo $alt ? 'left' : 'right'; ?>">
        <div class="flex items-center gap-4">
          <span class="flex size-14 items-center justify-center rounded-xl bg-apex-navy text-white"><?php echo apex_icon($service['icon'], 'size-7'); ?></span>
          <span class="font-display text-sm text-apex-ink/50"><?php echo esc_html(sprintf('%02d / %02d', $i + 1, count($services))); ?></span>
        </div>
        <h2 id="<?php echo esc_attr($service['slug']); ?>-title" class="mt-6 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl" data-split><?php echo esc_html($service['title']); ?></h2>
        <p class="mt-5 text-lg leading-relaxed text-apex-ink/75"><?php echo esc_html($service['intro']); ?></p>

        <h3 class="mt-10 font-display text-xs font-medium tracking-[0.25em] text-apex-sky uppercase">Who it’s for</h3>
        <p class="mt-3 leading-relaxed text-apex-ink/75"><?php echo esc_html($service['audience']); ?></p>

        <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
          <a href="<?php echo esc_url(add_query_arg('service', $service['slug'], home_url('/contact-us/')) . '#strategy-session'); ?>" class="apex-btn-primary" data-track="cta_strategy_session">
            <?php echo apex_icon('calendar', 'size-5'); ?>
            Discuss <?php echo esc_html($service['title']); ?>
          </a>
          <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="inline-flex items-center gap-2 font-display text-sm font-medium text-apex-navy hover:text-apex-sky" data-track="click_to_call">
            <?php echo apex_icon('phone', 'size-4'); ?>
            or call <?php echo esc_html($contact['phone']); ?>
          </a>
        </div>
      </div>

      <div class="lg:col-span-5 <?php echo $alt ? '' : 'lg:order-first'; ?>" data-reveal="<?php echo $alt ? 'right' : 'left'; ?>" style="--reveal-delay:150ms">
        <figure class="group relative" data-reveal="curtain" style="--reveal-delay:250ms">
          <div class="relative overflow-hidden rounded-2xl bg-apex-cloud">
            <?php
            echo apex_media_img('service-' . $service['slug'], 'large', array(
              'sizes'   => '(min-width: 1024px) 40vw, 100vw',
              'loading' => 'lazy',
              'class'   => 'aspect-[4/3] w-full object-cover transition duration-700 ease-apex group-hover:scale-105',
            ));
            ?>
            <div class="pointer-events-none absolute inset-0 bg-linear-to-t from-apex-night/55 via-apex-night/10 to-transparent" aria-hidden="true"></div>
            <span class="absolute top-4 left-4 flex size-12 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/25 backdrop-blur-md" aria-hidden="true"><?php echo apex_icon($service['icon'], 'size-6'); ?></span>
          </div>
        </figure>

        <div class="relative z-10 mx-3 -mt-16 rounded-2xl bg-white p-7 shadow-xl shadow-apex-night/10 ring-1 ring-apex-line sm:mx-6 lg:p-9">
          <h3 class="font-display text-lg font-medium text-apex-navy">What’s typically included</h3>
          <ul class="mt-5 space-y-4">
            <?php foreach ($service['includes'] as $item) : ?>
              <li class="flex gap-3 text-apex-ink/75">
                <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-apex-cloud text-apex-sky"><?php echo apex_icon('check', 'size-4'); ?></span>
                <span class="leading-relaxed"><?php echo esc_html($item); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="mt-6 border-t border-apex-line pt-5 text-xs leading-relaxed text-apex-ink/60">Scope is confirmed with you before any engagement begins.</p>
        </div>

        <div class="mt-6">
          <h3 class="font-display text-xs font-medium tracking-[0.25em] text-apex-ink/50 uppercase">Related services</h3>
          <ul class="mt-3 flex flex-wrap gap-2">
            <?php foreach ($service['related'] as $related_slug) : ?>
              <?php $related = apex_service($related_slug); ?>
              <?php if ($related) : ?>
                <li>
                  <a href="#<?php echo esc_attr($related['slug']); ?>" class="inline-flex items-center gap-2 rounded-full border border-apex-line bg-white px-4 py-2 text-sm text-apex-navy transition-colors hover:border-apex-navy">
                    <?php echo apex_icon($related['icon'], 'size-4 text-apex-sky'); ?>
                    <?php echo esc_html($related['title']); ?>
                  </a>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>
<?php endforeach; ?>

<?php get_template_part('template-parts/how-we-work', null, array('why' => false)); ?>

<section class="py-10">
  <div class="apex-container">
    <p class="max-w-4xl text-xs leading-relaxed text-apex-ink/55">
      The information on this page is a general description of our services and is not tax, legal or financial advice for your specific situation. Services, scope and deliverables are defined individually with each client.
    </p>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
