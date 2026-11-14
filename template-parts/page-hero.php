<?php
/**
 * Inner page header with visible breadcrumbs + BreadcrumbList schema.
 *
 * $args: title (H1), crumb (short breadcrumb label), eyebrow, intro,
 *        parent (optional array('name' => …, 'url' => …)), actions (bool: show CTA + phone)
 */
$title   = $args['title'] ?? get_the_title();
$crumb   = $args['crumb'] ?? $title;
$intro   = $args['intro'] ?? '';
$eyebrow = $args['eyebrow'] ?? '';
$parent  = $args['parent'] ?? null;
$actions = $args['actions'] ?? false;
$contact = apex_contact();

$crumbs = array(array('name' => 'Home', 'url' => home_url('/')));
if ($parent) {
  $crumbs[] = $parent;
}
$crumbs[] = array('name' => $crumb, 'url' => get_permalink());

$schema = array(
  '@context'        => 'https://schema.org',
  '@type'           => 'BreadcrumbList',
  'itemListElement' => array_map(function ($c, $i) {
    return array('@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['url']);
  }, $crumbs, array_keys($crumbs)),
);
?>
<section class="bg-apex-night text-white" data-replay>
  <div class="apex-container py-14 sm:py-16 lg:py-20">
    <nav aria-label="Breadcrumb" class="motion-safe-only animate-rise">
      <ol class="flex flex-wrap items-center gap-2 text-sm text-white/60">
        <?php foreach ($crumbs as $i => $c) : ?>
          <li class="flex items-center gap-2">
            <?php if ($i > 0) : ?><span aria-hidden="true">/</span><?php endif; ?>
            <?php if ($i < count($crumbs) - 1) : ?>
              <a href="<?php echo esc_url($c['url']); ?>" class="underline-offset-4 hover:text-white hover:underline"><?php echo esc_html($c['name']); ?></a>
            <?php else : ?>
              <span aria-current="page" class="text-white/90"><?php echo esc_html($c['name']); ?></span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    </nav>

    <div class="mt-10 grid gap-8 border-t border-white/15 pt-10 lg:grid-cols-12" style="--rule-color:rgb(255 255 255 / 0.15)" data-rule>
      <div class="lg:col-span-8">
        <?php if ($eyebrow) : ?>
          <p class="motion-safe-only animate-rise [animation-delay:100ms] apex-eyebrow text-apex-gold"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <h1 class="motion-safe-only animate-rise [animation-delay:180ms] mt-4 text-4xl leading-[1.1] font-semibold tracking-tight sm:text-5xl"><?php echo esc_html($title); ?></h1>
      </div>
      <?php if ($intro || $actions) : ?>
        <div class="motion-safe-only animate-rise [animation-delay:300ms] lg:col-span-4 lg:self-end">
          <?php if ($intro) : ?>
            <p class="text-lg leading-relaxed text-white/80"><?php echo esc_html($intro); ?></p>
          <?php endif; ?>
          <?php if ($actions) : ?>
            <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
              <a href="<?php echo esc_url($actions === true ? $contact['strategyUrl'] : $actions); ?>" class="apex-cta apex-btn bg-white text-apex-navy" data-track="cta_strategy_session"><span class="apex-cta__label"><span class="apex-cta__text">Schedule a Strategy Session</span></span></a>
              <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="text-sm font-semibold text-white underline-offset-4 hover:underline" data-track="click_to_call"><?php echo esc_html($contact['phone']); ?></a>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
</section>
