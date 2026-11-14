<?php
/**
 * Home — executive direction (828 review, Priority 2; proposal approved).
 *
 * Skeleton: hero (no form) → proof strip → services index → industries →
 * client results → process → partners → reviews → associations & portal →
 * strategy session form → FAQ.
 *
 * Content that depends on Laura / 828 (figures, photos, credentials, bios,
 * industries, results, reviews, associations) renders as marked placeholders
 * from inc/site-data.php. Never add example numbers.
 */

get_header();

$contact      = apex_contact();
$proof        = apex_proof();
$industries   = apex_industries();
$results      = apex_results();
$reviews      = apex_reviews();
$associations = apex_associations();
?>

<!-- Hero: full-bleed office photo on the right, fading into the night band -->
<!-- Hero + proof strip fill the first screen (minus topbar 40px and navbar 80px; topbar hidden below md) -->
<div class="flex min-h-[calc(100svh-5rem)] flex-col md:min-h-[calc(100svh-7.5rem)]">
<section class="relative isolate flex flex-1 items-center overflow-hidden bg-apex-night text-white" data-replay>
  <div class="absolute inset-y-0 right-0 -z-20 w-full overflow-hidden lg:w-[62%]" aria-hidden="true">
    <?php
    echo apex_media_img('hero', 'full', array(
      'alt'           => '',
      'sizes'         => '(min-width: 1024px) 62vw, 100vw',
      'loading'       => false,
      'decoding'      => 'async',
      'fetchpriority' => 'high',
      'class'         => 'motion-safe-only size-full animate-hero-zoom object-cover object-[30%_center]',
    ));
    ?>
  </div>
  <div class="absolute inset-0 -z-10 bg-apex-night/85 lg:bg-transparent lg:bg-linear-to-r lg:from-apex-night lg:from-38% lg:via-apex-night/75 lg:via-55% lg:to-apex-night/10" aria-hidden="true"></div>

  <div class="apex-container grid w-full items-center gap-12 py-12 sm:py-14 lg:grid-cols-12 lg:gap-10 xl:gap-16">
    <div class="max-w-2xl lg:col-span-7">
      <p class="motion-safe-only animate-rise apex-eyebrow text-apex-gold">Accounting · Tax · Advisory — <?php echo esc_html($contact['city'] . ', ' . $contact['region']); ?></p>
      <h1 class="motion-safe-only animate-rise [animation-delay:120ms] mt-5 text-4xl leading-[1.08] font-semibold tracking-tight sm:text-5xl lg:text-[3.5rem]">
        Tax planning, accounting and advisory for business owners in <?php echo esc_html($contact['city']); ?>, <?php echo esc_html($contact['region']); ?>
      </h1>
      <span class="motion-safe-only animate-draw [animation-delay:350ms] mt-8 block h-px w-20 origin-left bg-apex-gold" aria-hidden="true"></span>
      <p class="motion-safe-only animate-rise [animation-delay:260ms] mt-8 max-w-xl text-lg leading-relaxed text-white/80">
        We help business owners make confident financial decisions through strategic tax planning, accounting, compliance, and business advisory.
      </p>
      <div class="motion-safe-only animate-rise [animation-delay:400ms] mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
        <a href="#strategy-session" class="apex-cta apex-btn bg-white text-apex-navy" data-track="cta_strategy_session">
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
    </div>

    <!-- Hero form: appears after the text with the "magic hat" entrance (src/index.css) -->
    <div class="relative lg:col-span-5">
      <div class="motion-safe-only apex-magic relative border border-white/20 bg-white/10 p-6 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-8">
        <div id="apex-hero-form">
          <p class="apex-eyebrow text-apex-gold">Private &amp; no obligation</p>
          <h2 class="mt-2 font-display text-2xl font-semibold">Request a Strategy Session</h2>
          <p class="mt-3 text-[15px] leading-relaxed text-white/75">Tell us a little about your business and our team will reach out to schedule a time.</p>
          <a href="#strategy-session" class="apex-cta apex-btn mt-6 w-full bg-white text-apex-navy" data-track="cta_strategy_session"><span class="apex-cta__label"><span class="apex-cta__text">Open the request form</span></span></a>
        </div>
      </div>
      <span class="motion-safe-only apex-magic-brim pointer-events-none absolute inset-x-6 -bottom-3 h-px bg-apex-gold shadow-[0_0_18px_4px_rgb(201_165_108/0.55)]" aria-hidden="true"></span>
    </div>
  </div>
</section>

<!-- Proof strip -->
<section aria-label="APEX at a glance" class="border-b border-apex-ink/80">
  <div class="apex-container">
    <dl class="grid grid-cols-2 lg:grid-cols-4" data-reveal-stagger="80">
      <?php foreach ($proof as $i => $item) : ?>
        <div class="flex flex-col gap-2 border-apex-line py-8 <?php echo $i % 2 ? 'pl-6 border-l' : 'pr-6'; ?> <?php echo $i > 1 ? 'border-t lg:border-t-0' : ''; ?> <?php echo $i === 2 ? 'lg:border-l lg:pl-6' : ''; ?>" data-reveal>
          <dt class="apex-eyebrow text-apex-ink/60"><?php echo esc_html($item['label']); ?></dt>
          <dd class="apex-figure order-first text-4xl text-apex-navy">
            <?php if ($item['value'] === null) : ?>
              <?php echo apex_pending('Pending'); ?>
            <?php elseif (is_numeric($item['value'])) : ?>
              <span data-count="<?php echo esc_attr($item['value']); ?>" data-suffix="<?php echo esc_attr($item['suffix'] ?? ''); ?>"><?php echo esc_html($item['value'] . ($item['suffix'] ?? '')); ?></span>
            <?php else : ?>
              <?php echo esc_html($item['value']); ?>
            <?php endif; ?>
          </dd>
        </div>
      <?php endforeach; ?>
    </dl>
  </div>
</section>
</div>

<!-- Services -->
<section class="py-20 lg:py-24" aria-labelledby="services-title">
  <div class="apex-container">
    <div class="grid gap-6 lg:grid-cols-12 lg:items-end" data-reveal>
      <div class="lg:col-span-7">
        <p class="apex-eyebrow text-apex-sky">Services</p>
        <h2 id="services-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Eight services, one financial structure</h2>
      </div>
      <p class="leading-relaxed text-apex-ink/70 lg:col-span-5">Accounting, tax and advisory work that fits together, from day-to-day records to long-term planning.</p>
    </div>
    <div class="mt-10">
      <?php get_template_part('template-parts/services-showcase'); ?>
    </div>
  </div>
</section>

<!-- Industries -->
<section class="border-t border-apex-line bg-apex-cloud py-20 lg:py-24" aria-labelledby="industries-title">
  <div class="apex-container grid gap-10 lg:grid-cols-12">
    <div class="lg:col-span-4" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Industries</p>
      <h2 id="industries-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink">Industries we serve</h2>
    </div>
    <div class="lg:col-span-8" data-reveal>
      <?php if ($industries) : ?>
        <ul class="grid border-t border-apex-ink sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="60" data-rule>
          <?php foreach ($industries as $industry) : ?>
            <li class="border-b border-apex-line py-4 pr-4 text-lg text-apex-ink" data-reveal><?php echo esc_html($industry); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        <div class="border-t border-apex-ink pt-6" data-rule><?php echo apex_pending('List of industries served — pending from APEX'); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Client results -->
<section class="py-20 lg:py-24" aria-labelledby="results-title">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Client results</p>
      <h2 id="results-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">What working with APEX looks like</h2>
      <p class="mt-4 text-apex-ink/70">Anonymized examples, shared with permission.</p>
    </div>
    <div class="mt-10 overflow-x-auto" data-reveal>
      <table class="w-full min-w-[640px] border-collapse text-left">
        <thead>
          <tr class="border-y border-apex-ink">
            <th scope="col" class="apex-eyebrow w-1/3 py-3 pr-6 font-semibold text-apex-ink/60">Situation</th>
            <th scope="col" class="apex-eyebrow w-1/3 py-3 pr-6 font-semibold text-apex-ink/60">What we did</th>
            <th scope="col" class="apex-eyebrow w-1/3 py-3 font-semibold text-apex-ink/60">Outcome</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($results) : ?>
            <?php foreach ($results as $result) : ?>
              <tr class="border-b border-apex-line align-top" data-reveal>
                <td class="py-5 pr-6"><?php echo esc_html($result['situation']); ?></td>
                <td class="py-5 pr-6"><?php echo esc_html($result['work']); ?></td>
                <td class="py-5 font-semibold text-apex-navy"><?php echo esc_html($result['outcome']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr class="border-b border-apex-line">
              <td colspan="3" class="py-6"><?php echo apex_pending('2–3 anonymized client results — pending confirmation from APEX'); ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php get_template_part('template-parts/how-we-work'); ?>

<!-- Partners -->
<section class="py-20 lg:py-24" aria-labelledby="partners-title">
  <div class="apex-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between" data-reveal>
      <div class="max-w-2xl">
        <p class="apex-eyebrow text-apex-sky">Leadership</p>
        <h2 id="partners-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">The partners leading APEX</h2>
      </div>
      <a href="<?php echo esc_url(home_url('/team/')); ?>" class="apex-btn-outline shrink-0 self-start md:self-auto">
        Meet the full team
        <?php echo apex_icon('arrow-right', 'size-5'); ?>
      </a>
    </div>
    <div class="mt-10">
      <?php get_template_part('template-parts/partners'); ?>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="border-t border-apex-line bg-apex-cloud py-20 lg:py-24" aria-labelledby="reviews-title">
  <div class="apex-container">
    <div class="max-w-2xl" data-reveal>
      <p class="apex-eyebrow text-apex-sky">Reviews</p>
      <h2 id="reviews-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">What clients say</h2>
    </div>
    <?php if ($reviews) : ?>
      <ul class="mt-10 grid border-t border-apex-ink md:grid-cols-3" data-reveal-stagger="90" data-rule>
        <?php foreach ($reviews as $i => $review) : ?>
          <li class="border-b border-apex-line py-8 md:border-b-0 <?php echo $i ? 'md:border-l md:pl-8' : ''; ?> md:pr-8" data-reveal>
            <blockquote class="font-display text-xl leading-snug text-apex-ink">“<?php echo esc_html($review['quote']); ?>”</blockquote>
            <p class="mt-5 text-sm text-apex-ink/70"><?php echo esc_html($review['author']); ?> · <?php echo esc_html($review['source']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <div class="mt-10 border-t border-apex-ink pt-6" data-reveal data-rule><?php echo apex_pending('3 real client reviews with their source — pending from APEX'); ?></div>
    <?php endif; ?>
  </div>
</section>

<!-- Associations + Client Portal -->
<section class="py-20 lg:py-24">
  <div class="apex-container grid gap-12 lg:grid-cols-2 lg:gap-16">
    <div data-reveal="left">
      <p class="apex-eyebrow text-apex-sky">Associations</p>
      <h2 class="mt-4 text-2xl font-semibold text-apex-ink sm:text-3xl">Professional memberships</h2>
      <div class="mt-6 border-t border-apex-ink pt-6" data-rule>
        <?php if ($associations) : ?>
          <ul class="grid gap-3">
            <?php foreach ($associations as $association) : ?>
              <li><a href="<?php echo esc_url($association['url']); ?>" class="text-apex-navy underline-offset-4 hover:underline" target="_blank" rel="noopener"><?php echo esc_html($association['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php else : ?>
          <?php echo apex_pending('Associations and memberships, if any — pending from APEX'); ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="border border-apex-ink p-8 lg:p-10" data-reveal="right" style="--reveal-delay:120ms">
      <p class="apex-eyebrow text-apex-sky">For existing clients</p>
      <h2 class="mt-4 text-2xl font-semibold text-apex-ink sm:text-3xl">The APEX Client Portal</h2>
      <p class="mt-4 leading-relaxed text-apex-ink/75">Current clients use the portal to access their account with APEX online.</p>
      <p class="mt-3"><?php echo apex_pending('What clients can do in the portal — pending from APEX'); ?></p>
      <a href="<?php echo esc_url($contact['portalUrl']); ?>" target="_blank" rel="noopener noreferrer" class="apex-btn-outline mt-8" data-track="portal_click">
        <?php echo apex_icon('lock', 'size-5'); ?>
        Go to the Client Portal
        <span class="sr-only">(opens in a new tab)</span>
      </a>
    </div>
  </div>
</section>

<!-- Strategy session (form moved out of the hero) -->
<section id="strategy-session" class="scroll-mt-24 border-t border-apex-ink bg-apex-cloud py-20 lg:py-24" aria-labelledby="session-title" data-rule>
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-4" data-reveal="left">
      <p class="apex-eyebrow text-apex-sky">Private &amp; no obligation</p>
      <h2 id="session-title" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Schedule a strategy session</h2>
      <p class="mt-5 leading-relaxed text-apex-ink/75">Tell us about your business and when you’d like to begin. A member of our team will follow up to schedule a time.</p>
      <dl class="mt-10 divide-y divide-apex-line border-y border-apex-line">
        <div class="py-4">
          <dt class="apex-eyebrow text-apex-ink/55">Phone</dt>
          <dd class="mt-1"><a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="text-lg font-semibold text-apex-navy" data-track="click_to_call"><?php echo esc_html($contact['phone']); ?></a></dd>
        </div>
        <div class="py-4">
          <dt class="apex-eyebrow text-apex-ink/55">Email</dt>
          <dd class="mt-1"><a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="break-all text-apex-navy" data-track="email_click"><?php echo esc_html($contact['email']); ?></a></dd>
        </div>
        <div class="py-4">
          <dt class="apex-eyebrow text-apex-ink/55">Office</dt>
          <dd class="mt-1">
            <a href="<?php echo esc_url($contact['mapsUrl']); ?>" target="_blank" rel="noopener noreferrer" class="text-apex-navy" data-track="directions_click">
              <?php echo esc_html($contact['street']); ?><br><?php echo esc_html($contact['city'] . ', ' . $contact['region'] . ' ' . $contact['postalCode']); ?>
              <span class="sr-only">(opens Google Maps in a new tab)</span>
            </a>
          </dd>
        </div>
      </dl>
    </div>

    <div class="border border-apex-line bg-white p-6 sm:p-10 lg:col-span-8" data-reveal="right" style="--reveal-delay:150ms">
      <div id="apex-contact-form">
        <noscript>
          <p>Our form requires JavaScript. Call <a class="font-semibold text-apex-navy underline" href="<?php echo esc_attr($contact['phoneHref']); ?>"><?php echo esc_html($contact['phone']); ?></a> or email <a class="font-semibold text-apex-navy underline" href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a>.</p>
        </noscript>
      </div>
    </div>
  </div>
</section>

<!-- FAQs -->
<section class="py-20 lg:py-24" aria-labelledby="faq-heading">
  <div class="apex-container grid gap-12 lg:grid-cols-12 lg:gap-16">
    <div class="lg:col-span-4">
      <div class="lg:sticky lg:top-32" data-reveal>
        <p class="apex-eyebrow text-apex-sky">FAQs</p>
        <h2 id="faq-heading" class="mt-4 text-3xl leading-tight font-semibold text-apex-ink sm:text-4xl">Frequently asked questions</h2>
        <p class="mt-5 leading-relaxed text-apex-ink/70">Don’t see your question? Call us at <a href="<?php echo esc_attr($contact['phoneHref']); ?>" class="font-semibold text-apex-navy" data-track="click_to_call"><?php echo esc_html($contact['phone']); ?></a>.</p>
      </div>
    </div>

    <div class="border-t border-apex-ink lg:col-span-8" data-rule>
      <?php foreach (apex_home_faqs() as $i => $faq) : ?>
        <details class="apex-faq group border-b border-apex-line" name="home-faq" <?php echo $i === 0 ? 'open' : ''; ?> data-reveal style="--reveal-delay:<?php echo (int) ($i * 70); ?>ms">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 font-display text-xl font-semibold text-apex-ink hover:text-apex-navy [&::-webkit-details-marker]:hidden">
            <?php echo esc_html($faq['q']); ?>
            <span class="shrink-0 text-apex-sky transition-transform duration-300 group-open:rotate-45" aria-hidden="true"><?php echo apex_icon('x', 'size-5 rotate-45'); ?></span>
          </summary>
          <p class="max-w-3xl pr-12 pb-6 leading-relaxed text-apex-ink/75"><?php echo esc_html($faq['a']); ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_footer();
