<?php
/**
 * Home services: index + live preview (src/scripts/service-showcase.js).
 *
 * Desktop: the list on the left drives a large preview on the right (photo,
 * intro, three included items, link). Hover / focus selects a service; when
 * nobody interacts it advances on its own while in view, with a brass progress
 * line on the active row. Mobile: each service is a photo card.
 */
$services = apex_services();
?>
<div class="grid gap-10 lg:grid-cols-12 lg:gap-12" data-showcase>
  <!-- Index -->
  <ul class="border-t border-apex-ink lg:col-span-5" data-reveal-stagger="60" data-rule>
    <?php foreach ($services as $i => $service) : ?>
      <li class="border-b border-apex-line" data-reveal="left">
        <a
          href="<?php echo esc_url($service['url']); ?>"
          class="group relative flex items-center gap-5 py-5 pr-2 lg:py-6 pl-0 transition-colors duration-300 lg:pl-5 lg:data-active:bg-apex-cloud"
          data-showcase-item="<?php echo (int) $i; ?>"
          <?php echo $i === 0 ? 'data-active' : ''; ?>
        >
          <!-- active marker + autoplay progress -->
          <span class="absolute inset-y-0 left-0 hidden w-0.5 origin-top scale-y-0 bg-apex-sky transition-transform duration-500 ease-apex group-data-active:scale-y-100 lg:block" aria-hidden="true"></span>
          <span class="absolute bottom-0 left-0 hidden h-px w-full origin-left scale-x-0 bg-apex-sky lg:block" data-showcase-progress aria-hidden="true"></span>

          <span class="size-16 shrink-0 overflow-hidden bg-apex-cloud lg:hidden" aria-hidden="true">
            <?php
            echo apex_media_img('service-' . $service['slug'], 'thumbnail', array(
              'sizes' => '64px',
              'class' => 'size-full object-cover',
              'alt'   => '',
            ));
            ?>
          </span>
          <span class="hidden size-11 shrink-0 items-center justify-center border border-apex-line text-apex-navy transition-colors duration-300 group-hover:border-apex-navy group-data-active:border-apex-navy group-data-active:bg-apex-navy group-data-active:text-white lg:flex">
            <?php echo apex_icon($service['icon'], 'size-5'); ?>
          </span>
          <span class="min-w-0 flex-1">
            <span class="block font-display text-xl font-semibold text-apex-ink transition-colors group-hover:text-apex-navy lg:group-data-active:text-apex-navy sm:text-2xl"><?php echo esc_html($service['title']); ?></span>
            <span class="mt-1 block text-[15px] leading-snug text-apex-ink/65 lg:hidden"><?php echo esc_html($service['summary']); ?></span>
          </span>
          <?php echo apex_icon('arrow-right', 'size-5 shrink-0 text-apex-navy opacity-40 transition duration-300 group-hover:translate-x-1 group-hover:opacity-100 lg:group-data-active:translate-x-1 lg:group-data-active:opacity-100'); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Preview (desktop) -->
  <div class="hidden lg:col-span-7 lg:block" data-reveal="right" style="--reveal-delay:150ms">
    <div class="grid">
      <?php foreach ($services as $i => $service) : ?>
        <article
          class="col-start-1 row-start-1 flex flex-col bg-apex-night text-white opacity-0 transition-opacity duration-700 ease-apex data-active:opacity-100 data-active:z-10"
          data-showcase-panel="<?php echo (int) $i; ?>"
          <?php echo $i === 0 ? 'data-active' : 'inert'; ?>
        >
          <div class="relative aspect-[16/9] overflow-hidden">
            <?php
            echo apex_media_img('service-' . $service['slug'], 'large', array(
              'sizes'   => '(min-width: 1024px) 55vw, 1px',
              'loading' => $i === 0 ? 'eager' : 'lazy',
              'class'   => 'size-full scale-110 object-cover transition-transform duration-[1600ms] ease-apex [[data-active]_&]:scale-100',
              'alt'     => '',
            ));
            ?>
            <div class="absolute inset-0 bg-linear-to-t from-apex-night via-apex-night/30 to-transparent" aria-hidden="true"></div>
            <div class="absolute inset-x-0 bottom-0 p-8 xl:p-10">
              <p class="apex-eyebrow text-apex-gold">Service <?php echo (int) ($i + 1); ?> of <?php echo count($services); ?></p>
              <h3 class="mt-3 text-3xl font-semibold xl:text-4xl"><?php echo esc_html($service['title']); ?></h3>
            </div>
          </div>
          <div class="grid flex-1 gap-8 p-8 xl:grid-cols-5 xl:p-10">
            <p class="text-lg leading-relaxed text-white/80 xl:col-span-3"><?php echo esc_html($service['intro']); ?></p>
            <ul class="space-y-3 border-t border-white/15 pt-5 text-[15px] text-white/75 xl:col-span-2 xl:border-t-0 xl:border-l xl:pt-0 xl:pl-6">
              <?php foreach (array_slice($service['includes'], 0, 3) as $item) : ?>
                <li class="flex gap-3">
                  <span class="mt-1 shrink-0 text-apex-gold"><?php echo apex_icon('check', 'size-4'); ?></span>
                  <span><?php echo esc_html($item); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="border-t border-white/15 px-8 py-5 xl:px-10">
            <a href="<?php echo esc_url($service['url']); ?>" class="inline-flex items-center gap-2 font-semibold text-white underline-offset-4 hover:underline">
              Explore <?php echo esc_html($service['title']); ?>
              <?php echo apex_icon('arrow-right', 'size-5'); ?>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</div>
