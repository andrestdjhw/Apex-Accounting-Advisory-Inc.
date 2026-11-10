<?php
/**
 * 404 — keeps visitors inside the site with links to every main page.
 */

get_header();
?>

<section class="relative isolate overflow-hidden bg-apex-night text-white">
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,var(--color-apex-blue)_0%,transparent_55%)] opacity-60" aria-hidden="true"></div>
  <div class="apex-container py-24 lg:py-32">
    <p class="motion-safe-only animate-rise apex-eyebrow text-apex-mist">Error 404</p>
    <h1 class="motion-safe-only animate-rise [animation-delay:120ms] mt-5 max-w-3xl text-4xl leading-[1.08] font-light tracking-tight sm:text-5xl lg:text-6xl">
      We couldn’t find <span class="font-semibold">that page.</span>
    </h1>
    <p class="motion-safe-only animate-rise [animation-delay:240ms] mt-6 max-w-xl text-lg leading-relaxed text-white/80">
      The page may have moved or no longer exists. These links will take you where you need to go.
    </p>
    <div class="motion-safe-only animate-rise [animation-delay:360ms] mt-10 flex flex-col gap-4 sm:flex-row">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="apex-btn bg-white text-apex-navy hover:bg-apex-mist">
        <?php echo apex_icon('home', 'size-5'); ?>
        Back to Home
      </a>
      <a href="<?php echo esc_url(apex_contact()['strategyUrl']); ?>" class="apex-btn-outline-light" data-track="cta_strategy_session">
        <?php echo apex_icon('calendar', 'size-5'); ?>
        Schedule a Strategy Session
      </a>
    </div>
  </div>
</section>

<section class="py-20">
  <div class="apex-container">
    <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger="80">
      <?php foreach (array_slice(apex_nav(), 1) as $item) : ?>
        <li data-reveal>
          <a href="<?php echo esc_url($item['url']); ?>" class="group flex items-center justify-between rounded-xl border border-apex-line p-6 font-display text-lg font-medium text-apex-navy transition hover:border-apex-mist hover:bg-apex-cloud/60">
            <?php echo esc_html($item['label']); ?>
            <?php echo apex_icon('arrow-right', 'size-5 text-apex-sky transition-transform group-hover:translate-x-1'); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php get_footer();
