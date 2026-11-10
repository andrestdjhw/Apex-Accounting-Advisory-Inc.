<?php
/**
 * Team member card. $args['member'], $args['size'] ('md' | 'lg').
 */
$member = $args['member'];
$large  = ($args['size'] ?? 'md') === 'lg';
$photo  = $large ? 'size-40 sm:size-48' : 'size-32 sm:size-40';
$sizes  = $large ? '(min-width: 640px) 192px, 160px' : '(min-width: 640px) 160px, 128px';
$initials = implode('', array_map(function ($part) {
  return mb_substr($part, 0, 1);
}, array_slice(explode(' ', $member['name']), 0, 2)));
?>
<li class="group text-center" data-reveal>
  <?php if (!empty($member['photo'])) : ?>
    <?php
    echo apex_media_img(apex_team_media_key($member), 'medium', array(
      'sizes' => $sizes,
      'class' => 'mx-auto ' . $photo . ' rounded-full bg-apex-smoke object-cover ring-1 ring-apex-line origin-bottom transition duration-500 ease-apex group-hover:scale-120 group-hover:ring-4 group-hover:ring-apex-mist',
    ));
    ?>
  <?php else : ?>
    <span class="mx-auto flex <?php echo esc_attr($photo); ?> origin-bottom items-center justify-center rounded-full bg-apex-cloud font-display text-3xl font-light text-apex-navy ring-1 ring-apex-line transition duration-500 ease-apex group-hover:scale-120 group-hover:ring-4 group-hover:ring-apex-mist" aria-hidden="true">
      <?php echo esc_html($initials); ?>
    </span>
  <?php endif; ?>
  <h3 class="mt-5 text-lg font-medium text-apex-navy"><?php echo esc_html($member['name']); ?></h3>
  <p class="mt-1 text-sm text-apex-ink/70"><?php echo esc_html($member['title']); ?></p>
</li>
