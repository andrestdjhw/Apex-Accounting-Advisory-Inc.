<?php
/**
 * "How we work": the four-step process as a four-column table with square
 * corners and plain step labels (no outline numbers, no stacked cards).
 *
 * $args['tone'] 'dark' (navy band, default) | 'light'
 */
$dark  = ($args['tone'] ?? 'dark') === 'dark';
$steps = apex_steps();
?>
<section class="<?php echo $dark ? 'bg-apex-navy text-white' : 'bg-white text-apex-ink'; ?> py-20 lg:py-24" aria-labelledby="process-title">
  <div class="apex-container">
    <div class="grid gap-6 lg:grid-cols-12 lg:items-end" data-reveal>
      <div class="lg:col-span-7">
        <p class="apex-eyebrow <?php echo $dark ? 'text-apex-gold' : 'text-apex-sky'; ?>">How we work</p>
        <h2 id="process-title" class="mt-4 text-3xl leading-tight font-semibold sm:text-4xl">A clear process from first conversation to ongoing support</h2>
      </div>
      <p class="leading-relaxed lg:col-span-5 <?php echo $dark ? 'text-white/75' : 'text-apex-ink/70'; ?>">Every engagement follows the same four steps, so you always know where things stand and what comes next.</p>
    </div>

    <ol class="mt-12 grid border-t sm:grid-cols-2 lg:grid-cols-4 <?php echo $dark ? 'border-white/40' : 'border-apex-ink'; ?>" style="--rule-color:<?php echo $dark ? 'rgb(255 255 255 / 0.4)' : 'var(--color-apex-ink)'; ?>" data-reveal-stagger="120" data-rule>
      <?php foreach ($steps as $i => $step) : ?>
        <li class="border-b py-7 sm:pr-8 lg:border-b-0 <?php echo $dark ? 'border-white/15' : 'border-apex-line'; ?> <?php echo $i > 0 ? ($dark ? 'lg:border-l lg:border-l-white/15 lg:pl-8' : 'lg:border-l lg:border-l-apex-line lg:pl-8') : ''; ?>" data-reveal>
          <div class="mb-6 overflow-hidden" data-reveal="curtain">
            <div class="aspect-[4/3] overflow-hidden lg:aspect-auto lg:h-48 xl:h-52 <?php echo $dark ? 'bg-white/10' : 'bg-apex-cloud'; ?>">
              <?php
              echo apex_media_img('step-' . sanitize_title(str_replace('&', '', $step['title'])), 'large', array(
                'sizes' => '(min-width: 1024px) 22vw, (min-width: 640px) 45vw, 100vw',
                'class' => 'size-full object-cover transition-transform duration-700 ease-apex hover:scale-105',
              ));
              ?>
            </div>
          </div>
          <p class="apex-eyebrow <?php echo $dark ? 'text-white/60' : 'text-apex-ink/55'; ?>">Step <?php echo (int) ($i + 1); ?> of <?php echo count($steps); ?></p>
          <h3 class="mt-3 text-xl font-semibold"><?php echo esc_html($step['title']); ?></h3>
          <p class="mt-2 leading-relaxed <?php echo $dark ? 'text-white/75' : 'text-apex-ink/70'; ?>"><?php echo esc_html($step['text']); ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
