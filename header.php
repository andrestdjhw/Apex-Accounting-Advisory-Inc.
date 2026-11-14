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
    <script>
      // Google Consent Mode v2 defaults — must run before GA4 / GTM / ad tags.
      // Non-essential storage stays denied until the visitor chooses in the cookie
      // banner (src/components/CookieConsent.js), which sends the update.
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      (function () {
        var c = null;
        try { c = JSON.parse(localStorage.getItem('apex_cookie_consent')); } catch (e) {}
        var fresh = c && c.v === 1 && Date.now() - c.date < 31536000000;
        var a = fresh && c.analytics ? 'granted' : 'denied';
        var m = fresh && c.marketing ? 'granted' : 'denied';
        gtag('consent', 'default', {
          analytics_storage: a, ad_storage: m, ad_user_data: m, ad_personalization: m,
          functionality_storage: 'granted', security_storage: 'granted', wait_for_update: 500
        });
      })();
    </script>
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-60 focus:rounded-md focus:bg-apex-navy focus:px-4 focus:py-3 focus:text-white">Skip to main content</a>

    <?php $contact = apex_contact(); ?>
    <div class="hidden bg-apex-night text-white/80 md:block">
      <div class="apex-container flex h-10 items-center justify-between gap-6 text-[13px]">
        <div class="flex items-center gap-6">
          <a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="flex items-center gap-2 transition-colors hover:text-white" data-track="email_click">
            <?php echo apex_icon('mail', 'size-4 text-apex-mist'); ?>
            <?php echo esc_html($contact['email']); ?>
          </a>
          <span class="h-4 w-px bg-white/20" aria-hidden="true"></span>
          <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="flex items-center gap-2 transition-colors hover:text-white" data-track="click_to_call">
            <?php echo apex_icon('phone', 'size-4 text-apex-mist'); ?>
            <?php echo esc_html($contact['phone']); ?>
          </a>
        </div>
        <a href="<?php echo esc_url($contact['mapsUrl']); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 transition-colors hover:text-white" data-track="directions_click">
          <?php echo apex_icon('map-pin', 'size-4 text-apex-mist'); ?>
          <?php echo esc_html($contact['street'] . ', ' . $contact['city'] . ', ' . $contact['region'] . ' ' . $contact['postalCode']); ?>
          <span class="sr-only">(opens Google Maps in a new tab)</span>
        </a>
      </div>
    </div>

    <?php
    // React (src/components/Navbar.js) replaces this markup. The fallback keeps the
    // header height (no layout shift) and gives crawlers / no-JS users real links.
    ?>
    <div id="apex-navbar" class="sticky top-0 z-50 min-h-20 bg-white">
      <div class="apex-container flex h-20 items-center justify-between gap-6">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0" aria-label="<?php echo esc_attr(apex_brand_name()); ?> — Home">
          <img src="<?php echo esc_url(apex_media_url('logo-black')); ?>" alt="Apex Accounting &amp; Advisory" width="1024" height="163" class="h-8 w-auto sm:h-[37px]">
        </a>
        <nav aria-label="Main" class="hidden xl:block">
          <ul class="flex items-center gap-8 font-display text-[17px] text-apex-ink/75">
            <?php foreach (apex_nav() as $item) : ?>
              <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </div>

    <main id="main">
