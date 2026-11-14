<?php
/**
 * Team directory entry: rectangular 4:5 portrait (or initials), name and title.
 * $args['member']
 */
$member   = $args['member'];
$initials = implode('', array_map(function ($part) {
  return mb_substr($part, 0, 1);
}, array_slice(explode(' ', $member['name']), 0, 2)));
?>
<li data-reveal>
  <div data-reveal="curtain">
  <div class="flex aspect-[4/5] items-center justify-center overflow-hidden border border-apex-line bg-white">
    <?php if (!empty($member['photo'])) : ?>
      <?php
      echo apex_media_img(apex_team_media_key($member), 'medium', array(
        'sizes' => '(min-width: 1024px) 22vw, (min-width: 640px) 30vw, 45vw',
        'class' => !empty($member['portrait_formal']) ? 'size-full object-cover object-top' : 'aspect-square w-[85%] object-cover',
        'alt'   => 'Portrait of ' . $member['name'],
      ));
      ?>
    <?php else : ?>
      <span class="flex size-full items-center justify-center font-display text-4xl text-apex-navy/60" aria-hidden="true"><?php echo esc_html($initials); ?></span>
    <?php endif; ?>
  </div>
  </div>
  <h3 class="mt-4 text-lg font-semibold text-apex-ink"><?php echo esc_html($member['name']); ?></h3>
  <p class="text-[15px] text-apex-ink/70"><?php echo esc_html($member['title']); ?></p>
  <?php if (!empty($member['title_pending'])) : ?>
    <p class="mt-2"><?php echo apex_pending($member['title_pending']); ?></p>
  <?php endif; ?>
</li>
