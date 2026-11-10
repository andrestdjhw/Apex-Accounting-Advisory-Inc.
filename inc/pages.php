<?php
/**
 * Site pages: definitions, setup and per-page SEO meta.
 *
 * Every URL the theme links to must exist. `wp apex setup-pages` (also run on
 * theme activation) creates any missing page, publishes it and fills legal
 * pages with the client's published text. Idempotent and safe to re-run:
 * existing pages are never overwritten.
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * slug => title, meta description, optional seed content file (inc/content/).
 * Templates: page-{slug}.php when it exists, page.php otherwise.
 */
function apex_pages() {
  return array(
    'about-us' => array(
      'title'       => 'About Us',
      'description' => 'Learn how Apex Accounting & Advisory helps businesses make confident financial decisions through proactive planning, accounting and advisory services.',
    ),
    'services' => array(
      'title'       => 'Services',
      'description' => 'Strategic tax planning, executive accounting, business advisory, financial reporting, payroll, IRS compliance, corporate tax preparation and business structure in Irvine, CA.',
    ),
    'team' => array(
      'title'       => 'Team',
      'description' => 'Meet the Apex Accounting & Advisory team guiding clients toward greater clarity, confidence, and financial success.',
    ),
    'contact-us' => array(
      'title'       => 'Contact Us',
      'description' => 'Schedule a private strategy session with Apex Accounting & Advisory. Call (949) 889-2739 or visit us at 6 Venture, Suite 310, Irvine, CA 92618.',
    ),
    // PENDING LEGAL REVIEW (brief §5.5): text copied verbatim from the live site.
    'privacy-policy' => array(
      'title'       => 'Privacy Policy',
      'description' => 'How Apex Accounting Inc. collects, uses and protects your information.',
      'content'     => 'privacy-policy.html',
    ),
    'terms-and-conditions' => array(
      'title'       => 'Terms and Conditions',
      'description' => 'Terms and conditions for using the Apex Accounting Inc. website.',
      'content'     => 'terms-and-conditions.html',
    ),
  );
}

function apex_setup_pages() {
  $report = array();

  foreach (apex_pages() as $slug => $page) {
    $content = '';
    if (!empty($page['content'])) {
      $content = (string) file_get_contents(get_theme_file_path('/inc/content/' . $page['content']));
    }

    $existing = get_page_by_path($slug, OBJECT, 'page');

    if ($existing && $existing->post_status === 'publish') {
      $report[$slug] = 'exists #' . $existing->ID;
      continue;
    }

    if ($existing) {
      // e.g. WordPress' default draft "Privacy Policy": publish it with the client's text.
      $id = wp_update_post(array(
        'ID'           => $existing->ID,
        'post_title'   => $page['title'],
        'post_status'  => 'publish',
        'post_content' => $content !== '' ? $content : $existing->post_content,
      ), true);
      $report[$slug] = is_wp_error($id) ? 'error: ' . $id->get_error_message() : 'published #' . $id;
      continue;
    }

    $id = wp_insert_post(array(
      'post_type'    => 'page',
      'post_name'    => $slug,
      'post_title'   => $page['title'],
      'post_status'  => 'publish',
      'post_content' => $content,
    ), true);
    $report[$slug] = is_wp_error($id) ? 'error: ' . $id->get_error_message() : 'created #' . $id;
  }

  $privacy = get_page_by_path('privacy-policy', OBJECT, 'page');
  if ($privacy) {
    update_option('wp_page_for_privacy_policy', $privacy->ID);
  }

  if (get_option('permalink_structure') !== '/%postname%/') {
    update_option('permalink_structure', '/%postname%/');
    $report['permalinks'] = 'set to /%postname%/';
  }
  flush_rewrite_rules(false);

  return $report;
}

add_action('after_switch_theme', 'apex_setup_pages');

if (defined('WP_CLI') && WP_CLI) {
  /**
   * Creates/publishes every page the theme links to (never overwrites existing ones).
   */
  WP_CLI::add_command('apex setup-pages', function () {
    foreach (apex_setup_pages() as $slug => $status) {
      WP_CLI::log(str_pad($slug, 24) . $status);
    }
    WP_CLI::success('Pages ready.');
  });
}

/**
 * Meta description for the current view (skipped if an SEO plugin is active).
 */
function apex_meta_description() {
  if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
    return;
  }

  $description = '';
  if (is_front_page()) {
    $description = 'Apex Accounting Inc. helps business owners in Irvine, CA make confident financial decisions through strategic tax planning, accounting, compliance and business advisory.';
  } elseif (is_page()) {
    $pages = apex_pages();
    $slug  = get_post_field('post_name', get_queried_object_id());
    $description = $pages[$slug]['description'] ?? '';
  }

  if ($description) {
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
  }
}
add_action('wp_head', 'apex_meta_description', 1);

/**
 * Service schema on /services/ (only content that is visible on the page).
 */
function apex_services_schema() {
  if (!is_page('services')) {
    return;
  }
  $c = apex_contact();
  $schema = array(
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Services',
    'itemListElement' => array_map(function ($service, $i) use ($c) {
      return array(
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => array(
          '@type'       => 'Service',
          'name'        => $service['title'],
          'description' => $service['intro'],
          'url'         => $service['url'],
          'provider'    => array('@type' => 'AccountingService', 'name' => $c['name'], 'url' => home_url('/')),
        ),
      );
    }, apex_services(), array_keys(apex_services())),
  );
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'apex_services_schema');
