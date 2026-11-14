<?php
/**
 * Individual service page (/services/{slug}/), routed by apex_service_template()
 * in inc/pages.php. Structure from the 828 review: who it's for, what's included,
 * what you receive, FAQs and a call to action.
 *
 * Deliverables and FAQs are marked placeholders until 828 / APEX confirm them.
 * No guarantees, savings promises or IRS representation claims.
 */

get_header();

$service = apex_current_service();
$contact = apex_contact();
$cta_url = add_query_arg('service', $service['slug'], home_url('/contact-us/')) . '#strategy-session';

get_template_part('template-parts/page-hero', null, array(
  'title'   => $service['title'] . ' in ' . $contact['city'] . ', ' . $contact['region'],
  'crumb'   => $service['title'],
  'eyebrow' => 'Service',
  'intro'   => $service['summary'],
  'parent'  => array('name' => 'Services', 'url' => home_url('/services/')),
  'actions' => $cta_url,
));
?>

<!-- Overview + who it's for -->
<section class="py-20 lg:py-24">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-7" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Overview</p>
      <p class="mt-5 font-display text-2xl leading-snug text-apex-ink sm:text-3xl"><?php echo esc_html($service['intro']); ?></p>

      <div class="mt-12 border-t border-apex-ink pt-6" data-rule>
        <h2 class="apex-eyebrow text-apex-ink/60">Who it’s for</h2>
        <p class="mt-3 text-lg leading-relaxed text-apex-ink/80"><?php echo esc_html($service['audience']); ?></p>
      </div>
    </div>

    <div class="lg:col-span-5" data-reveal="curtain" style="--reveal-delay:150ms">
      <div class="aspect-[4/3] overflow-hidden bg-apex-cloud">
        <?php
        echo apex_media_img('service-' . $service['slug'], 'large', array(
          'sizes' => '(min-width: 1024px) 38vw, 100vw',
          'class' => 'size-full object-cover',
        ));
        ?>
      </div>
    </div>
  </div>
</section>

<!-- Included + deliverables -->
<section class="border-t border-apex-line bg-apex-cloud py-20 lg:py-24">
  <div class="apex-container grid gap-12 lg:grid-cols-2 lg:gap-16">
    <div data-reveal>
      <h2 class="text-2xl font-semibold text-apex-ink sm:text-3xl">What’s typically included</h2>
      <ul class="mt-6 border-t border-apex-ink" data-rule>
        <?php foreach ($service['includes'] as $item) : ?>
          <li class="flex gap-4 border-b border-apex-line py-4 text-apex-ink/80" data-reveal="left">
            <span class="mt-1 text-apex-sky"><?php echo apex_icon('check', 'size-5'); ?></span>
            <span class="leading-relaxed"><?php echo esc_html($item); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="mt-4 text-sm text-apex-ink/60">Scope is confirmed with you before any engagement begins.</p>
    </div>

    <div data-reveal>
      <h2 class="text-2xl font-semibold text-apex-ink sm:text-3xl">What you receive</h2>
      <?php if ($service['deliverables']) : ?>
        <ul class="mt-6 border-t border-apex-ink" data-rule>
          <?php foreach ($service['deliverables'] as $item) : ?>
            <li class="border-b border-apex-line py-4 leading-relaxed text-apex-ink/80"><?php echo esc_html($item); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        <div class="mt-6 border-t border-apex-ink pt-6" data-rule><?php echo apex_pending('Concrete deliverables for this service — pending from APEX'); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/how-we-work', null, array('tone' => 'light')); ?>

<!-- FAQs -->
<section class="border-t border-apex-line py-20 lg:py-24" aria-labelledby="service-faq">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-4" data-reveal>
      <p class="apex-eyebrow text-apex-sky">FAQs</p>
      <h2 id="service-faq" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink">Questions about <?php echo esc_html($service['title']); ?></h2>
    </div>
    <div class="border-t border-apex-ink lg:col-span-8" data-reveal data-rule>
      <?php if ($service['faqs']) : ?>
        <?php foreach ($service['faqs'] as $faq) : ?>
          <details class="apex-faq group border-b border-apex-line">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 font-display text-xl font-semibold text-apex-ink [&::-webkit-details-marker]:hidden">
              <?php echo esc_html($faq['q']); ?>
              <span class="shrink-0 text-apex-sky transition-transform duration-300 group-open:rotate-45" aria-hidden="true"><?php echo apex_icon('x', 'size-5 rotate-45'); ?></span>
            </summary>
            <p class="max-w-3xl pr-12 pb-6 leading-relaxed text-apex-ink/75"><?php echo esc_html($faq['a']); ?></p>
          </details>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="pt-6"><?php echo apex_pending('3–4 questions and answers for this service — pending from APEX'); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Related services -->
<section class="border-t border-apex-line py-16">
  <div class="apex-container">
    <h2 class="apex-eyebrow text-apex-ink/60">Related services</h2>
    <ul class="mt-6 grid border-t border-apex-ink sm:grid-cols-2" data-rule>
      <?php foreach ($service['related'] as $i => $related_slug) : ?>
        <?php $related = apex_service($related_slug); ?>
        <?php if ($related) : ?>
          <li class="border-b border-apex-line <?php echo $i ? 'sm:border-l sm:pl-8' : 'sm:pr-8'; ?>">
            <a href="<?php echo esc_url($related['url']); ?>" class="group flex items-baseline justify-between gap-4 py-6">
              <span>
                <span class="block font-display text-xl font-semibold text-apex-navy"><?php echo esc_html($related['title']); ?></span>
                <span class="mt-1 block text-apex-ink/70"><?php echo esc_html($related['summary']); ?></span>
              </span>
              <?php echo apex_icon('arrow-right', 'size-5 shrink-0 text-apex-navy transition-transform group-hover:translate-x-1'); ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <p class="mt-6"><a href="<?php echo esc_url(home_url('/services/')); ?>" class="font-semibold text-apex-navy underline-offset-4 hover:underline">All services</a></p>
  </div>
</section>

<?php get_template_part('template-parts/final-cta'); ?>

<?php get_footer();
