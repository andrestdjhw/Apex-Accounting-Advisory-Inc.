<?php
/**
 * Closing CTA with contact details and Google Map (used on every page).
 */
$contact = apex_contact();
?>
<section class="bg-apex-cloud py-20 lg:py-24">
  <div class="apex-container">
    <div data-reveal="zoom" class="grid overflow-hidden rounded-2xl bg-apex-night text-white lg:grid-cols-2">
      <div class="px-6 py-12 sm:px-12 lg:px-16 lg:py-16" data-reveal style="--reveal-delay:200ms">
        <h2 class="text-3xl leading-tight font-light sm:text-4xl" data-split>
          Let’s review the structure <span class="font-semibold">behind your business.</span>
        </h2>
        <p class="mt-5 max-w-xl leading-relaxed text-white/75">
          Schedule a private strategy session with our team to talk through where your business stands today and where you want it to go.
        </p>
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:flex-wrap">
          <a href="<?php echo esc_url($contact['strategyUrl']); ?>" class="apex-btn bg-white text-apex-navy hover:bg-apex-mist" data-track="cta_strategy_session" data-magnetic>
            <?php echo apex_icon('calendar', 'size-5'); ?>
            Schedule a Strategy Session
          </a>
          <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="apex-btn-outline-light" data-track="click_to_call" data-magnetic>
            <?php echo apex_icon('phone', 'size-5'); ?>
            <?php echo esc_html($contact['phone']); ?>
          </a>
        </div>

        <address class="mt-10 grid gap-5 border-t border-white/15 pt-8 not-italic sm:grid-cols-2">
          <a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="flex items-start gap-3 text-sm text-white/80 hover:text-white" data-track="email_click">
            <span class="text-apex-mist"><?php echo apex_icon('mail', 'size-5'); ?></span>
            <span class="break-all"><?php echo esc_html($contact['email']); ?></span>
          </a>
          <a href="<?php echo esc_url($contact['mapsUrl']); ?>" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 text-sm text-white/80 hover:text-white">
            <span class="text-apex-mist"><?php echo apex_icon('map-pin', 'size-5'); ?></span>
            <span>
              <?php echo esc_html($contact['street']); ?><br>
              <?php echo esc_html($contact['city'] . ', ' . $contact['region'] . ' ' . $contact['postalCode']); ?>
              <span class="sr-only">(opens Google Maps in a new tab)</span>
            </span>
          </a>
        </address>
      </div>

      <div class="relative min-h-80 lg:min-h-full" data-reveal="curtain" style="--reveal-delay:300ms">
      <div class="group absolute inset-0 bg-apex-navy">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-center text-white/60" aria-hidden="true">
          <?php echo apex_icon('map-pin', 'size-10 text-apex-mist'); ?>
          <span class="text-sm"><?php echo esc_html($contact['street'] . ', ' . $contact['city'] . ', ' . $contact['region']); ?></span>
        </div>
        <iframe
          src="<?php echo esc_url($contact['mapsEmbed']); ?>"
          title="<?php echo esc_attr('Map showing the ' . $contact['name'] . ' office in ' . $contact['city'] . ', ' . $contact['region']); ?>"
          class="absolute inset-0 size-full border-0 grayscale-60 transition duration-500 group-hover:grayscale-0"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen
        ></iframe>
        <a
          href="<?php echo esc_url($contact['mapsUrl']); ?>"
          target="_blank"
          rel="noopener noreferrer"
          class="apex-btn absolute right-4 bottom-4 min-h-10 bg-white px-4 text-sm text-apex-navy shadow-lg shadow-apex-night/20 hover:bg-apex-mist"
          data-track="directions_click"
        >
          <?php echo apex_icon('map-pin', 'size-4'); ?>
          Get Directions
          <span class="sr-only">(opens Google Maps in a new tab)</span>
        </a>
      </div>
      </div>
    </div>
  </div>
</section>

