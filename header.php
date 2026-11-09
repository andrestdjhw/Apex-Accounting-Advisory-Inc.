<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
      // Enables scroll-reveal styles. If the main bundle hasn't started within 3s, show everything.
      document.documentElement.classList.add('js');
      setTimeout(function () {
        var d = document.documentElement;
        if (!d.classList.contains('reveal-ready')) d.classList.remove('js');
      }, 3000);
    </script>
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-60 focus:rounded-md focus:bg-apex-navy focus:px-4 focus:py-3 focus:text-white">Skip to main content</a>

    <?php
    // React (src/components/Navbar.js) replaces this markup. The fallback keeps the
    // header height (no layout shift) and gives crawlers / no-JS users real links.
    ?>
    <div id="apex-navbar" class="sticky top-0 z-50 min-h-20 bg-white">
      <div class="apex-container flex h-20 items-center justify-between gap-6">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0" aria-label="Apex Accounting Inc. — Home">
          <img src="<?php echo esc_url(apex_media_url('logo-black')); ?>" alt="Apex Accounting &amp; Advisory" width="1024" height="163" class="h-7 w-auto sm:h-8">
        </a>
        <nav aria-label="Main" class="hidden xl:block">
          <ul class="flex items-center gap-8 font-display text-[15px] text-apex-ink/75">
            <?php foreach (apex_nav() as $item) : ?>
              <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </div>

    <main id="main">
