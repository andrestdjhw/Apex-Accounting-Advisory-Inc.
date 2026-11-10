<?php
/**
 * "Why APEX?" statement (optional) + "How we work" process as a sticky timeline
 * with stacked cards (stack effect in src/scripts/motion.js → initStack).
 *
 * $args['why'] (bool, default true) — services page hides the statement.
 */
$show_why = $args['why'] ?? true;
$steps    = apex_steps();

// Card look per step: background, text, outline-number stroke, icon colour.
$card_styles = array(
  array('card' => 'bg-white text-apex-navy', 'stroke' => 'var(--color-apex-navy)', 'icon' => 'text-apex-sky', 'body' => 'text-apex-ink/70'),
  array('card' => 'bg-apex-cloud text-apex-navy', 'stroke' => 'var(--color-apex-navy)', 'icon' => 'text-apex-sky', 'body' => 'text-apex-ink/70'),
  array('card' => 'bg-apex-mist text-apex-navy', 'stroke' => 'var(--color-apex-navy)', 'icon' => 'text-apex-blue', 'body' => 'text-apex-ink/75'),
  array('card' => 'bg-linear-to-br from-apex-sky to-apex-navy text-white', 'stroke' => '#fff', 'icon' => 'text-white/80', 'body' => 'text-white/80'),
);
?>

<?php if ($show_why) : ?>
  <!-- Why APEX -->
  <section class="bg-apex-navy py-20 text-white lg:py-28">
    <div class="apex-container grid gap-8 lg:grid-cols-12 lg:items-end">
      <div class="lg:col-span-7" data-reveal>
        <p class="apex-eyebrow text-apex-mist">Why APEX?</p>
        <h2 class="mt-5 text-3xl leading-tight font-light sm:text-4xl lg:text-5xl" data-scrub>
          We don’t just prepare taxes.
          <span class="block font-semibold">We build the financial structure that protects everything you’ve built.</span>
        </h2>
      </div>
      <p class="leading-relaxed text-white/75 lg:col-span-5" data-reveal style="--reveal-delay:150ms">
        Planning, visibility, compliance and advisory work together, so the decisions you make today support where you want your business to be tomorrow.
      </p>
    </div>
  </section>
<?php endif; ?>

<!-- How we work: sticky timeline + stacked cards -->
<section class="relative isolate overflow-clip bg-apex-night py-20 text-white lg:py-28" aria-labelledby="process-title">
  <div class="absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
    <div class="absolute -inset-y-24 inset-x-0" data-parallax="0.12">
      <?php
      echo apex_media_img('process-bg', 'full', array(
        'alt'     => '',
        'sizes'   => '100vw',
        'loading' => 'lazy',
        'class'   => 'size-full object-cover opacity-35 mix-blend-luminosity',
      ));
      ?>
    </div>
    <div class="absolute inset-0 bg-linear-to-b from-apex-night via-apex-night/80 to-apex-night"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgb(255_255_255/0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgb(255_255_255/0.04)_1px,transparent_1px)] bg-size-[64px_64px]"></div>
  </div>

  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <!-- Left: heading + timeline (sticky on desktop) -->
    <div class="lg:col-span-5">
      <div class="lg:sticky lg:top-36">
        <div data-reveal>
          <p class="apex-eyebrow text-apex-mist">How we work</p>
          <h2 id="process-title" class="mt-5 text-3xl leading-tight font-light sm:text-4xl lg:text-5xl" data-split>
            A clear process, <span class="font-semibold">from first conversation to ongoing support.</span>
          </h2>
          <p class="mt-5 max-w-md leading-relaxed text-white/70">
            Every engagement follows the same four steps, so you always know where things stand and what comes next.
          </p>
        </div>

        <ol class="relative mt-12 hidden space-y-7 pl-10 lg:block" data-stack-timeline="process-steps" aria-hidden="true">
          <span class="absolute top-2 bottom-2 left-[11px] w-px bg-white/15"></span>
          <span class="absolute top-2 bottom-2 left-[11px] w-px origin-top bg-apex-sky transition-transform duration-300" style="transform:scaleY(var(--timeline,0))"></span>
          <?php foreach ($steps as $i => $step) : ?>
            <li class="group relative" data-step>
              <span class="absolute top-1/2 -left-10 flex size-[23px] -translate-y-1/2 items-center justify-center rounded-full border border-white/25 bg-apex-night transition duration-300 group-data-active:border-apex-sky group-data-active:bg-apex-sky">
                <span class="size-1.5 rounded-full bg-white/40 transition group-data-active:bg-white"></span>
              </span>
              <span class="font-display text-sm text-white/40 transition-colors group-data-active:text-apex-mist"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
              <span class="ml-3 font-display text-lg text-white/55 transition-colors group-data-active:font-medium group-data-active:text-white"><?php echo esc_html($step['title']); ?></span>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>

    <!-- Right: stacked cards -->
    <ol id="process-steps" class="space-y-6 lg:col-span-7" data-stack>
      <?php foreach ($steps as $i => $step) : ?>
        <?php $style = $card_styles[$i % count($card_styles)]; ?>
        <li class="sticky top-[calc(6.5rem+var(--i)*1rem)] lg:top-[calc(9rem+var(--i)*1.75rem)]" style="--i:<?php echo (int) $i; ?>" data-stack-card>
          <article class="apex-stack-card flex min-h-[19rem] flex-col justify-between rounded-3xl p-8 shadow-2xl shadow-black/40 sm:min-h-[22rem] lg:p-11 <?php echo esc_attr($style['card']); ?>">
            <div class="flex items-start justify-between gap-6">
              <span class="font-display text-7xl leading-none font-semibold text-transparent sm:text-8xl" style="-webkit-text-stroke:1.5px <?php echo esc_attr($style['stroke']); ?>" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
              <span class="<?php echo esc_attr($style['icon']); ?>" aria-hidden="true"><?php echo apex_icon($step['icon'], 'size-12 sm:size-14'); ?></span>
            </div>
            <div class="mt-10">
              <p class="sr-only">Step <?php echo (int) ($i + 1); ?> of <?php echo count($steps); ?></p>
              <h3 class="text-3xl font-semibold tracking-tight sm:text-4xl"><?php echo esc_html($step['title']); ?></h3>
              <p class="mt-3 max-w-lg text-lg leading-relaxed <?php echo esc_attr($style['body']); ?>"><?php echo esc_html($step['text']); ?></p>
            </div>
          </article>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
