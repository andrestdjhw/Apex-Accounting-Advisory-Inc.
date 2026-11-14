<?php
/**
 * Closing call to action with contact details and the office map (inner pages).
 */
$contact = apex_contact();
?>
<section class="relative border-t border-apex-ink bg-apex-navy text-white" aria-labelledby="cta-title" data-rule>
  <div class="apex-container grid lg:grid-cols-2">
    <div class="py-16 lg:py-20 lg:pr-16" data-reveal>
      <p class="apex-eyebrow text-apex-gold">Private strategy session</p>
      <h2 id="cta-title" class="mt-4 text-3xl leading-tight font-semibold sm:text-4xl">Let’s review the structure behind your business</h2>
      <p class="mt-5 max-w-xl leading-relaxed text-white/75">Talk through where your business stands today and where you want it to go.</p>
      <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-4">
        <a href="<?php echo esc_url($contact['strategyUrl']); ?>" class="apex-cta apex-btn bg-white text-apex-navy" data-track="cta_strategy_session">
          <span class="apex-cta__label"><span class="apex-cta__text">
            Schedule a Strategy Session
            <?php echo apex_icon('arrow-right', 'size-5'); ?>
          </span></span>
        </a>
        <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="inline-flex items-center gap-2 font-semibold text-white underline-offset-4 hover:underline" data-track="click_to_call">
          <?php echo apex_icon('phone', 'size-5'); ?>
          <?php echo esc_html($contact['phone']); ?>
        </a>
      </div>
      <address class="mt-10 grid gap-6 border-t border-white/15 pt-8 text-[15px] not-italic sm:grid-cols-2">
        <div>
          <span class="apex-eyebrow block text-white/50">Email</span>
          <a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="mt-1 block break-all text-white/85 hover:text-white" data-track="email_click"><?php echo esc_html($contact['email']); ?></a>
        </div>
        <div>
          <span class="apex-eyebrow block text-white/50">Office</span>
          <a href="<?php echo esc_url($contact['mapsUrl']); ?>" target="_blank" rel="noopener noreferrer" class="mt-1 block text-white/85 hover:text-white" data-track="directions_click">
            <?php echo esc_html($contact['street']); ?><br><?php echo esc_html($contact['city'] . ', ' . $contact['region'] . ' ' . $contact['postalCode']); ?>
            <span class="sr-only">(opens Google Maps in a new tab)</span>
          </a>
        </div>
      </address>
    </div>

    <div class="relative -mx-3.5 min-h-80 bg-apex-night sm:-mx-[15px] lg:absolute lg:inset-y-0 lg:right-0 lg:left-1/2 lg:mx-0 lg:min-h-0" data-reveal="fade" style="--reveal-delay:250ms">
      <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-center text-white/60" aria-hidden="true">
        <?php echo apex_icon('map-pin', 'size-8'); ?>
        <span class="text-sm"><?php echo esc_html($contact['street'] . ', ' . $contact['city'] . ', ' . $contact['region']); ?></span>
      </div>
      <iframe
        src="<?php echo esc_url($contact['mapsEmbed']); ?>"
        title="<?php echo esc_attr('Map showing the ' . $contact['name'] . ' office in ' . $contact['city'] . ', ' . $contact['region']); ?>"
        class="absolute inset-0 size-full border-0"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen
      ></iframe>
    </div>
  </div>
</section>
