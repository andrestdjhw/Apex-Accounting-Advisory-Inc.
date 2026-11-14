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
      'seo_title'   => 'About Our Accounting & Advisory Firm in Irvine, CA',
      'heading'     => 'Strategic Accounting and Advisory for Business Owners',
      'description' => 'Learn how Apex Accounting & Advisory helps businesses make confident financial decisions through proactive planning, accounting and advisory services.',
    ),
    'services' => array(
      'title'       => 'Services',
      'seo_title'   => 'Accounting, Tax & Advisory Services in Irvine, CA',
      'heading'     => 'Accounting, Tax and Advisory Services',
      'description' => 'Strategic tax planning, executive accounting, business advisory, financial reporting, payroll, IRS compliance, corporate tax preparation and business structure in Irvine, CA.',
    ),
    'team' => array(
      'title'       => 'Team',
      'seo_title'   => 'Our Accounting & Tax Team in Irvine, CA',
      'heading'     => 'The Accounting and Tax Team Behind APEX',
      'description' => 'Meet the Apex Accounting & Advisory team guiding clients toward greater clarity, confidence, and financial success.',
    ),
    'contact-us' => array(
      'title'       => 'Contact Us',
      'seo_title'   => 'Contact Us — Schedule a Strategy Session',
      'heading'     => 'Schedule a Strategy Session With APEX',
      'description' => 'Schedule a private strategy session with Apex Accounting & Advisory. Call (949) 889-2739 or visit us at 6 Venture, Suite 310, Irvine, CA 92618.',
    ),
    // PENDING LEGAL REVIEW (brief §5.5): text copied verbatim from the live site.
    'privacy-policy' => array(
      'title'       => 'Privacy Policy',
      'description' => 'How ' . apex_brand_name() . ' collects, uses and protects your information.',
      'content'     => 'privacy-policy.html',
    ),
    'terms-and-conditions' => array(
      'title'       => 'Terms and Conditions',
      'description' => 'Terms and conditions for using the ' . apex_brand_name() . ' website.',
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

  // Individual service pages: /services/{slug}/ (children of Services).
  $parent = get_page_by_path('services', OBJECT, 'page');
  if ($parent) {
    foreach (apex_services() as $service) {
      $path     = 'services/' . $service['slug'];
      $existing = get_page_by_path($path, OBJECT, 'page');
      if ($existing && $existing->post_status === 'publish') {
        $report[$path] = 'exists #' . $existing->ID;
        continue;
      }
      $id = $existing
        ? wp_update_post(array('ID' => $existing->ID, 'post_status' => 'publish'), true)
        : wp_insert_post(array(
          'post_type'   => 'page',
          'post_name'   => $service['slug'],
          'post_title'  => $service['title'],
          'post_parent' => $parent->ID,
          'post_status' => 'publish',
        ), true);
      $report[$path] = is_wp_error($id) ? 'error: ' . $id->get_error_message() : ($existing ? 'published #' : 'created #') . $id;
    }
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
 * Anchor for the cookie banner's "Learn more" link (/privacy-policy/#cookies).
 */
function apex_privacy_cookie_anchor($content) {
  if (!is_page('privacy-policy') || strpos($content, 'id="cookies"') !== false) {
    return $content;
  }
  return preg_replace('/<h([2-4])>(\s*Tracking Technologies and Cookies\s*)<\/h\1>/i', '<h$1 id="cookies" class="scroll-mt-32">$2</h$1>', $content, 1);
}
add_filter('the_content', 'apex_privacy_cookie_anchor');

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

/**
 * The service array when the current page is one of the /services/{slug}/ pages.
 */
function apex_current_service() {
  if (!is_page()) {
    return null;
  }
  $post   = get_queried_object();
  $parent = $post && $post->post_parent ? get_post($post->post_parent) : null;
  if (!$parent || $parent->post_name !== 'services') {
    return null;
  }
  return apex_service($post->post_name);
}

/**
 * Service pages share one template (page-service.php).
 */
function apex_service_template($template) {
  if (apex_current_service()) {
    $custom = get_theme_file_path('/page-service.php');
    if (file_exists($custom)) {
      return $custom;
    }
  }
  return $template;
}
add_filter('template_include', 'apex_service_template');

/**
 * Service schema on each service page (only what is visible on the page).
 */
function apex_service_schema() {
  $service = apex_current_service();
  if (!$service) {
    return;
  }
  $c      = apex_contact();
  $schema = array(
    '@context'    => 'https://schema.org',
    '@type'       => 'Service',
    'name'        => $service['title'],
    'description' => $service['intro'],
    'url'         => $service['url'],
    'serviceType' => $service['title'],
    'areaServed'  => array('@type' => 'City', 'name' => $c['city'] . ', ' . $c['region']),
    'provider'    => array('@type' => 'AccountingService', '@id' => home_url('/#organization'), 'name' => $c['name'], 'url' => home_url('/')),
  );
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'apex_service_schema');
