<?php
/**
 * Partners row: formal 4:5 portrait, name, title, credentials (EA / CPA / CFP)
 * and a 2–3 line bio. Credentials, bios and founder titles are marked
 * placeholders until 828 / Laura confirm them (any "CPA" must be verifiable).
 */
$partners = array_values(array_filter(apex_team(), function ($m) {
  return !empty($m['leadership']);
}));
?>
<ul class="grid gap-x-8 gap-y-12 border-t border-apex-ink pt-10 md:grid-cols-3" data-reveal-stagger="100" data-rule>
  <?php foreach ($partners as $member) : ?>
    <li class="flex flex-col" data-reveal>
      <?php
      // Current photos come pre-cropped as circles from the old site. Until the formal
      // 4:5 portraits arrive ('portrait_formal' => true), show them centred in the frame.
      $formal = !empty($member['portrait_formal']);
      ?>
      <div class="overflow-hidden" data-reveal="curtain">
      <div class="relative flex aspect-[4/5] items-center justify-center overflow-hidden border border-apex-line bg-apex-cloud">
        <?php if (!empty($member['photo'])) : ?>
          <?php
          echo apex_media_img(apex_team_media_key($member), 'medium_large', array(
            'sizes' => '(min-width: 768px) 30vw, 100vw',
            'class' => $formal ? 'size-full object-cover object-top' : 'aspect-square w-4/5 object-cover',
            'alt'   => 'Portrait of ' . $member['name'],
          ));
          ?>
        <?php endif; ?>
        <?php if (!$formal) : ?>
          <span class="absolute right-3 bottom-3"><?php echo apex_pending('Formal portrait — pending'); ?></span>
        <?php endif; ?>
      </div>
      </div>
      <h3 class="mt-6 text-2xl font-semibold text-apex-ink"><?php echo esc_html($member['name']); ?></h3>
      <p class="mt-1 text-apex-ink/70"><?php echo esc_html($member['title']); ?></p>
      <div class="mt-3 flex flex-wrap gap-2">
        <?php if (!empty($member['title_pending'])) : ?>
          <?php echo apex_pending($member['title_pending']); ?>
        <?php endif; ?>
        <?php if (!empty($member['credentials'])) : ?>
          <p class="apex-eyebrow text-apex-sky"><?php echo esc_html($member['credentials']); ?></p>
        <?php else : ?>
          <?php echo apex_pending('Credentials (EA / CPA / CFP) — pending verification'); ?>
        <?php endif; ?>
      </div>
      <div class="mt-4 border-t border-apex-line pt-4 text-apex-ink/75">
        <?php if (!empty($member['bio'])) : ?>
          <p class="leading-relaxed"><?php echo esc_html($member['bio']); ?></p>
        <?php else : ?>
          <?php echo apex_pending('2–3 line bio — pending from APEX', 'p'); ?>
        <?php endif; ?>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
