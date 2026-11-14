<?php
/**
 * Technical SEO (828 review, Nov 11 2026 — Priority 4) and Priority 1 llms.txt.
 *
 *  - One brand name everywhere (titles, schema, footer).
 *  - <title> with service + city on the home page; concrete titles per page.
 *  - Meta description, canonical (home) and OpenGraph / Twitter on every view.
 *  - Person schema for the partners.
 *  - /llms.txt generated from the real, published pages.
 *  - 301 map from the current apexaccountinginc.com URLs (`wp apex redirects` checks it).
 *
 * Head output is skipped when Yoast, Rank Math or AIOSEO is active, so tags never duplicate.
 */

if (!defined('ABSPATH')) {
  exit;
}

/*
 * PENDING 828 CONFIRMATION: today the old site uses "Apex Accounting Inc." and the
 * header "Apex Accounting & Advisory Inc.". Every visible/structured mention reads
 * from this single constant, so the confirmed name is a one-line change.
 */
if (!defined('APEX_BRAND_NAME')) {
  define('APEX_BRAND_NAME', 'Apex Accounting & Advisory');
}

function apex_brand_name() {
  return APEX_BRAND_NAME;
}

function apex_seo_plugin_active() {
  return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION');
}

/**
 * Title, description, canonical URL and share image for the current view.
 */
function apex_seo_context() {
  $brand = apex_brand_name();
  $ctx   = array(
    'title'       => '',
    'description' => '',
    'url'         => '',
    'image'       => apex_asset('images/og-default.jpg'),
    'image_alt'   => $brand . ' office in Irvine, CA',
    'type'        => 'website',
  );

  if (is_front_page()) {
    $ctx['title']       = 'Tax Planning & Accounting in Irvine, CA | ' . $brand;
    $ctx['description'] = $brand . ' helps business owners in Irvine, CA make confident financial decisions through strategic tax planning, accounting, compliance and business advisory.';
    $ctx['url']         = home_url('/');
  } elseif ($service = apex_current_service()) {
    $ctx['title']       = $service['title'] . ' in Irvine, CA | ' . $brand;
    $ctx['description'] = $service['summary'] . ' ' . $service['intro'];
    $ctx['url']         = $service['url'];
  } elseif (is_page()) {
    $slug  = get_post_field('post_name', get_queried_object_id());
    $pages = apex_pages();
    $page  = $pages[$slug] ?? array();
    $ctx['title']       = ($page['seo_title'] ?? get_the_title()) . ' | ' . $brand;
    $ctx['description'] = $page['description'] ?? '';
    $ctx['url']         = get_permalink();
  } elseif (is_singular()) {
    $ctx['title'] = get_the_title() . ' | ' . $brand;
    $ctx['url']   = get_permalink();
    $ctx['type']  = 'article';
  }

  return $ctx;
}

function apex_document_title($title) {
  if (apex_seo_plugin_active()) {
    return $title;
  }
  $ctx = apex_seo_context();
  return $ctx['title'] ? esc_html($ctx['title']) : $title;
}
add_filter('pre_get_document_title', 'apex_document_title');

// 404 / search / archives fall back to WordPress; keep the same brand name there.
function apex_document_title_parts($parts) {
  $parts['site'] = apex_brand_name();
  return $parts;
}
add_filter('document_title_parts', 'apex_document_title_parts');

function apex_seo_head() {
  if (apex_seo_plugin_active() || is_404()) {
    return;
  }
  $ctx   = apex_seo_context();
  $title = $ctx['title'] ?: wp_get_document_title();
  $tags  = array();

  if ($ctx['description']) {
    $tags[] = sprintf('<meta name="description" content="%s">', esc_attr($ctx['description']));
  }
  // WordPress prints rel=canonical on singular views only; the home page (latest-posts mode) needs its own.
  if (is_front_page() && !is_singular()) {
    $tags[] = sprintf('<link rel="canonical" href="%s">', esc_url($ctx['url']));
  }

  $og = array(
    'og:locale'      => 'en_US',
    'og:type'        => $ctx['type'],
    'og:site_name'   => apex_brand_name(),
    'og:title'       => $title,
    'og:description' => $ctx['description'],
    'og:url'         => $ctx['url'],
    'og:image'       => $ctx['image'],
    'og:image:width' => '1200',
    'og:image:height'=> '630',
    'og:image:alt'   => $ctx['image_alt'],
  );
  foreach ($og as $property => $content) {
    if ($content !== '') {
      $tags[] = sprintf('<meta property="%s" content="%s">', esc_attr($property), esc_attr($content));
    }
  }

  $twitter = array(
    'twitter:card'        => 'summary_large_image',
    'twitter:title'       => $title,
    'twitter:description' => $ctx['description'],
    'twitter:image'       => $ctx['image'],
    'twitter:image:alt'   => $ctx['image_alt'],
  );
  foreach ($twitter as $name => $content) {
    if ($content !== '') {
      $tags[] = sprintf('<meta name="%s" content="%s">', esc_attr($name), esc_attr($content));
    }
  }

  echo implode("\n", $tags) . "\n";
}
add_action('wp_head', 'apex_seo_head', 1);

/**
 * Person schema for the partners (About + Team). jobTitle is only included once
 * a title is confirmed by 828/Laura ('title_confirmed' => true in apex_team()).
 */
function apex_person_schema() {
  if (!is_page(array('about-us', 'team'))) {
    return;
  }
  $people = array();
  foreach (apex_team() as $member) {
    if (empty($member['leadership'])) {
      continue;
    }
    $person = array(
      '@type'    => 'Person',
      'name'     => $member['name'],
      'worksFor' => array('@id' => home_url('/#organization')),
    );
    if (!empty($member['title_confirmed'])) {
      $person['jobTitle'] = $member['title'];
    }
    if (!empty($member['photo'])) {
      $person['image'] = apex_media_url(apex_team_media_key($member));
    }
    $people[] = $person;
  }
  if ($people) {
    $schema = array('@context' => 'https://schema.org', '@graph' => $people);
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
  }
}
add_action('wp_head', 'apex_person_schema');

/* -------------------------------------------------------------------------- */
/* llms.txt                                                                    */
/* -------------------------------------------------------------------------- */

// On Hostinger, turn off the "LLMs.txt" option of the Hostinger Tools plugin
// (and delete the static /llms.txt it wrote), otherwise the server serves that file first.
function apex_llms_rewrite() {
  add_rewrite_rule('^llms\.txt$', 'index.php?apex_llms=1', 'top');
}
add_action('init', 'apex_llms_rewrite');

function apex_llms_query_var($vars) {
  $vars[] = 'apex_llms';
  return $vars;
}
add_filter('query_vars', 'apex_llms_query_var');

function apex_llms_txt() {
  $c     = apex_contact();
  $lines = array(
    '# ' . apex_brand_name(),
    '',
    '> Accounting, tax and business advisory firm in ' . $c['city'] . ', ' . $c['region'] . '. Strategic tax planning, executive accounting, business advisory, financial reporting, payroll, IRS compliance, corporate tax preparation and business structure for business owners.',
    '',
    sprintf('Address: %s, %s, %s %s. Phone: %s. Email: %s.', $c['street'], $c['city'], $c['region'], $c['postalCode'], $c['phone'], $c['email']),
    '',
    '## Pages',
    '',
    sprintf('- [Home](%s): Overview of the firm, services, process and team.', home_url('/')),
  );

  $legal = array();
  foreach (apex_pages() as $slug => $page) {
    $post = get_page_by_path($slug, OBJECT, 'page');
    if (!$post || $post->post_status !== 'publish') {
      continue;
    }
    $line = sprintf('- [%s](%s): %s', $page['title'], get_permalink($post), $page['description']);
    if (!empty($page['content'])) {
      $legal[] = $line;
    } else {
      $lines[] = $line;
    }
  }

  $lines[] = '';
  $lines[] = '## Services';
  $lines[] = '';
  foreach (apex_services() as $service) {
    $lines[] = sprintf('- [%s](%s): %s', $service['title'], $service['url'], $service['summary']);
  }

  if ($legal) {
    $lines[] = '';
    $lines[] = '## Legal';
    $lines[] = '';
    $lines   = array_merge($lines, $legal);
  }

  return implode("\n", $lines) . "\n";
}

function apex_llms_serve() {
  if (!get_query_var('apex_llms')) {
    return;
  }
  status_header(200);
  header('Content-Type: text/plain; charset=utf-8');
  header('X-Robots-Tag: noindex');
  echo apex_llms_txt();
  exit;
}
add_action('template_redirect', 'apex_llms_serve', 0);

/* -------------------------------------------------------------------------- */
/* 301 map: apexaccountinginc.com (current site) → new site                    */
/* -------------------------------------------------------------------------- */

/**
 * Old path => new URL. Built from the live sitemap (Nov 13, 2026). Pages that keep
 * their path (/about-us/, /team/, /contact-us/, /services/, legal) need no rule.
 * Service URLs follow apex_services(), so they move automatically to the individual
 * service pages once those exist.
 */
function apex_redirect_map() {
  $map = array(
    '/maintenance/' => home_url('/'),
  );
  foreach (apex_services() as $service) {
    $map['/service/' . $service['slug'] . '/'] = $service['url'];
  }
  $map['/service/'] = home_url('/services/');
  return $map;
}

function apex_legacy_redirects() {
  if (!is_404()) {
    return; // never shadow a real page
  }
  $path = '/' . trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/') . '/';
  $map  = apex_redirect_map();
  if (isset($map[$path])) {
    wp_safe_redirect($map[$path], 301, 'APEX theme');
    exit;
  }
}
add_action('template_redirect', 'apex_legacy_redirects', 1);

if (defined('WP_CLI') && WP_CLI) {
  /**
   * Prints the 301 map and checks every source redirects to a target that answers 200.
   *
   * ## EXAMPLES
   *     wp apex redirects
   */
  WP_CLI::add_command('apex redirects', function () {
    $ok = true;
    foreach (apex_redirect_map() as $from => $to) {
      $res    = wp_remote_head(home_url($from), array('redirection' => 0, 'sslverify' => false));
      $code   = wp_remote_retrieve_response_code($res);
      $target = wp_remote_retrieve_header($res, 'location');
      $final  = wp_remote_head(strtok($to, '#'), array('sslverify' => false));
      $status = wp_remote_retrieve_response_code($final);
      $good   = $code === 301 && $target === $to && $status === 200;
      $ok     = $ok && $good;
      WP_CLI::log(sprintf('%s %-40s → %s (%s → %s)', $good ? '✔' : '✘', $from, $to, $code ?: 'n/a', $status ?: 'n/a'));
    }
    $ok ? WP_CLI::success('All legacy URLs redirect to live pages.') : WP_CLI::error('Some redirects need attention.');
  });
}
