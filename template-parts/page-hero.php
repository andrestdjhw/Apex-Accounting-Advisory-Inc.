<?php
/**
 * Inner page hero with visible breadcrumbs + BreadcrumbList schema.
 *
 * $args: title (H1), intro, eyebrow (optional)
 */
$title   = $args['title'] ?? get_the_title();
$intro   = $args['intro'] ?? '';
$eyebrow = $args['eyebrow'] ?? 'Apex Accounting & Advisory';

$crumbs = array(
  array('name' => 'Home', 'url' => home_url('/')),
  array('name' => $title, 'url' => get_permalink()),
);
$schema = array(
  '@context'        => 'https://schema.org',
  '@type'           => 'BreadcrumbList',
  'itemListElement' => array_map(function ($crumb, $i) {
    return array('@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb['name'], 'item' => $crumb['url']);
  }, $crumbs, array_keys($crumbs)),
);
?>
<section class="relative isolate overflow-hidden bg-apex-night text-white" data-replay>
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,var(--color-apex-blue)_0%,transparent_55%)] opacity-60" aria-hidden="true"></div>
  <div class="absolute -right-24 -bottom-40 -z-10 size-[28rem] rounded-full border border-white/5" aria-hidden="true"></div>
  <div class="absolute -right-8 -bottom-24 -z-10 size-[20rem] rounded-full border border-white/5" aria-hidden="true"></div>

  <div class="apex-container py-16 sm:py-20 lg:py-24">
    <nav aria-label="Breadcrumb" class="motion-safe-only animate-rise">
      <ol class="flex flex-wrap items-center gap-2 text-sm text-white/60">
        <?php foreach ($crumbs as $i => $crumb) : ?>
          <li class="flex items-center gap-2">
            <?php if ($i > 0) : ?><span aria-hidden="true">/</span><?php endif; ?>
            <?php if ($i < count($crumbs) - 1) : ?>
              <a href="<?php echo esc_url($crumb['url']); ?>" class="transition-colors hover:text-white"><?php echo esc_html($crumb['name']); ?></a>
            <?php else : ?>
              <span aria-current="page" class="text-white/90"><?php echo esc_html($crumb['name']); ?></span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    </nav>

    <p class="motion-safe-only animate-rise [animation-delay:80ms] apex-eyebrow mt-10 text-apex-mist"><?php echo esc_html($eyebrow); ?></p>
    <h1 class="motion-safe-only animate-rise [animation-delay:160ms] mt-4 max-w-3xl text-4xl leading-[1.08] font-light tracking-tight sm:text-5xl lg:text-6xl">
      <?php echo esc_html($title); ?>
    </h1>
    <?php if ($intro) : ?>
      <p class="motion-safe-only animate-rise [animation-delay:240ms] mt-6 max-w-2xl text-lg leading-relaxed text-white/80"><?php echo esc_html($intro); ?></p>
    <?php endif; ?>
  </div>
  <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
</section>
