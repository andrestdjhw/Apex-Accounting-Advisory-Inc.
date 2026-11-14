<?php
/**
 * Services as an index table: service / what it is / link to its own page.
 * Used on the home page and on /services/.
 */
$services = apex_services();
?>
<div class="border-t border-apex-ink" data-rule>
  <div class="hidden grid-cols-12 gap-6 border-b border-apex-line py-3 md:grid" aria-hidden="true">
    <span class="apex-eyebrow col-span-4 text-apex-ink/55">Service</span>
    <span class="apex-eyebrow col-span-6 text-apex-ink/55">What it covers</span>
    <span class="col-span-2"></span>
  </div>
  <ul data-reveal-stagger="60">
  <?php foreach ($services as $service) : ?>
    <li data-reveal="left">
    <a
      href="<?php echo esc_url($service['url']); ?>"
      class="group grid gap-2 border-b border-apex-line py-5 transition-colors hover:bg-apex-cloud md:grid-cols-12 md:items-baseline md:gap-6 md:px-0"
    >
      <span class="font-display text-xl font-semibold text-apex-navy md:col-span-4"><?php echo esc_html($service['title']); ?></span>
      <span class="text-apex-ink/70 md:col-span-6"><?php echo esc_html($service['summary']); ?></span>
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-apex-navy md:col-span-2 md:justify-end md:pr-3">
        View service
        <?php echo apex_icon('arrow-right', 'size-4 transition-transform group-hover:translate-x-1'); ?>
      </span>
    </a>
    </li>
  <?php endforeach; ?>
  </ul>
</div>
