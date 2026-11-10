<?php
/**
 * Contact / Strategy Session (brief §5.5).
 * The form is the React ContactForm component (src/components/ContactForm.js),
 * mounted on #apex-contact-form and posting to the apex/v1 REST endpoint
 * (inc/contact-form.php).
 */

get_header();

$contact = apex_contact();

get_template_part('template-parts/page-hero', null, array(
  'title'   => 'Contact Us',
  'eyebrow' => 'Private Strategy Session',
  'intro'   => 'Schedule a strategy session and discover how APEX can strengthen your financial foundation.',
));

$cards = array(
  array('icon' => 'phone', 'label' => 'Call us at', 'value' => $contact['phone'], 'href' => $contact['phoneHref'], 'track' => 'click_to_call'),
  array('icon' => 'mail', 'label' => 'Email us at', 'value' => $contact['email'], 'href' => 'mailto:' . $contact['email'], 'track' => 'email_click'),
  array('icon' => 'map-pin', 'label' => 'Visit us at', 'value' => $contact['street'] . ', ' . $contact['city'] . ', ' . $contact['region'] . ' ' . $contact['postalCode'], 'href' => $contact['mapsUrl'], 'track' => 'directions_click', 'external' => true),
);
?>

<section class="py-20 lg:py-28">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <!-- Contact details -->
    <div class="lg:col-span-5">
      <div data-reveal="left">
        <?php echo apex_eyebrow('Get in touch'); ?>
        <h2 class="mt-5 text-3xl leading-tight font-medium text-apex-navy sm:text-4xl">Let’s talk about your business</h2>
        <p class="mt-5 leading-relaxed text-apex-ink/70">Tell us a little about your company and when you’d like to begin. A member of our team will follow up to schedule your private strategy session.</p>
      </div>

      <address class="mt-10 space-y-4 not-italic" data-reveal-stagger="100">
        <?php foreach ($cards as $card) : ?>
          <a
            href="<?php echo esc_attr($card['href']); ?>"
            class="group flex items-start gap-5 rounded-xl border border-apex-line p-5 transition duration-200 hover:border-apex-mist hover:bg-apex-cloud/60"
            data-track="<?php echo esc_attr($card['track']); ?>"
            data-reveal
            <?php if (!empty($card['external'])) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
          >
            <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-apex-navy text-white transition-transform duration-300 group-hover:scale-110"><?php echo apex_icon($card['icon'], 'size-5'); ?></span>
            <span>
              <span class="block font-display text-xs font-medium tracking-[0.2em] text-apex-sky uppercase"><?php echo esc_html($card['label']); ?></span>
              <span class="mt-1 block break-words text-lg text-apex-navy"><?php echo esc_html($card['value']); ?></span>
              <?php if (!empty($card['external'])) : ?><span class="sr-only">(opens Google Maps in a new tab)</span><?php endif; ?>
            </span>
          </a>
        <?php endforeach; ?>
      </address>

      <div class="mt-8 rounded-xl bg-apex-night p-6 text-white" data-reveal>
        <div class="flex items-start gap-4">
          <span class="text-apex-mist"><?php echo apex_icon('user', 'size-6'); ?></span>
          <div>
            <h3 class="font-display text-lg font-medium">Already a client?</h3>
            <p class="mt-1 text-sm leading-relaxed text-white/70">Access your account through the APEX Client Portal.</p>
            <a href="<?php echo esc_url($contact['portalUrl']); ?>" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-2 font-display text-sm font-medium text-white hover:text-apex-mist" data-track="portal_click">
              Go to Client Portal
              <?php echo apex_icon('external-link', 'size-4'); ?>
              <span class="sr-only">(opens in a new tab)</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Strategy Session form -->
    <div class="lg:col-span-7">
      <div id="strategy-session" class="scroll-mt-28 rounded-2xl bg-white p-6 shadow-xl shadow-apex-night/5 ring-1 ring-apex-line sm:p-10" data-reveal="right">
        <h2 class="text-2xl font-medium text-apex-navy sm:text-3xl">Schedule Your Private Strategy Session</h2>
        <p class="mt-3 text-sm leading-relaxed text-apex-ink/70">Fields marked with <span class="text-apex-sky" aria-hidden="true">*</span><span class="sr-only">an asterisk</span> are required.</p>

        <div id="apex-contact-form" class="mt-8">
          <noscript>
            <p class="rounded-lg bg-apex-cloud p-5 text-apex-ink/80">
              Our form requires JavaScript. You can reach us at
              <a class="font-medium text-apex-navy underline" href="<?php echo esc_attr($contact['phoneHref']); ?>"><?php echo esc_html($contact['phone']); ?></a> or
              <a class="font-medium text-apex-navy underline" href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a>.
            </p>
          </noscript>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Map -->
<section aria-label="Office location" class="relative h-[420px] bg-apex-navy" data-reveal="fade">
  <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-center text-white/60" aria-hidden="true">
    <?php echo apex_icon('map-pin', 'size-10 text-apex-mist'); ?>
    <span class="text-sm"><?php echo esc_html($contact['street'] . ', ' . $contact['city'] . ', ' . $contact['region']); ?></span>
  </div>
  <iframe
    src="<?php echo esc_url($contact['mapsEmbed']); ?>"
    title="<?php echo esc_attr('Map showing the ' . $contact['name'] . ' office in ' . $contact['city'] . ', ' . $contact['region']); ?>"
    class="absolute inset-0 size-full border-0 grayscale-60 transition duration-500 hover:grayscale-0"
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
    allowfullscreen
  ></iframe>
</section>

<?php get_footer();
