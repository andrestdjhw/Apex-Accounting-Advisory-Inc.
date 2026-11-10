<?php
/**
 * "Why APEX?" statement + 4-step process. $args['why'] (bool, default true).
 */
$show_why = $args['why'] ?? true;
?>
<section class="bg-apex-navy py-20 text-white lg:py-28">
  <div class="apex-container">
    <?php if ($show_why) : ?>
      <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
        <div class="lg:col-span-7" data-reveal>
          <p class="apex-eyebrow text-apex-mist">Why APEX?</p>
          <h2 class="mt-5 text-3xl leading-tight font-light sm:text-4xl lg:text-5xl">
            We don’t just prepare taxes.
            <span class="block font-semibold">We build the financial structure that protects everything you’ve built.</span>
          </h2>
        </div>
        <p class="leading-relaxed text-white/75 lg:col-span-5" data-reveal style="--reveal-delay:150ms">
          Planning, visibility, compliance and advisory work together, so the decisions you make today support where you want your business to be tomorrow.
        </p>
      </div>
    <?php else : ?>
      <div class="max-w-2xl" data-reveal>
        <p class="apex-eyebrow text-apex-mist">How we work</p>
        <h2 class="mt-5 text-3xl leading-tight font-light sm:text-4xl">
          A clear process, <span class="font-semibold">from first conversation to ongoing support.</span>
        </h2>
      </div>
    <?php endif; ?>

    <div class="relative mt-16 pt-12">
      <span class="absolute inset-x-0 top-0 h-px bg-white/15" data-reveal="line" aria-hidden="true"></span>
      <?php if ($show_why) : ?>
        <h3 class="apex-eyebrow text-apex-mist" data-reveal="fade">How we work</h3>
      <?php endif; ?>
      <ol data-reveal-stagger="140" class="<?php echo $show_why ? 'mt-8 ' : ''; ?>grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
        <?php foreach (apex_steps() as $i => $step) : ?>
          <li data-reveal>
            <span class="font-display text-4xl font-light text-apex-mist/70"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
            <h4 class="mt-4 text-lg font-medium"><?php echo esc_html($step['title']); ?></h4>
            <p class="mt-2 text-sm leading-relaxed text-white/70"><?php echo esc_html($step['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>
