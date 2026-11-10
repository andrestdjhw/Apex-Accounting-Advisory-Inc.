<?php

require_once get_theme_file_path('/inc/site-data.php');
require_once get_theme_file_path('/inc/media.php');
require_once get_theme_file_path('/inc/pages.php');
require_once get_theme_file_path('/inc/contact-form.php');

function apex_load_assets() {
  $asset = include get_theme_file_path('/build/index.asset.php');

  wp_enqueue_style(
    'apex-fonts',
    'https://fonts.googleapis.com/css2?family=Geologica:wght@300;400;500;600;700&family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap',
    array(),
    null
  );
  wp_enqueue_style('apex-main', get_theme_file_uri('/build/index.css'), array('apex-fonts'), filemtime(get_theme_file_path('/build/index.css')));

  wp_enqueue_script('apex-main', get_theme_file_uri('/build/index.js'), $asset['dependencies'], $asset['version'], true);
  wp_add_inline_script('apex-main', 'window.apexSite = ' . wp_json_encode(apex_js_data()) . ';', 'before');
}
add_action('wp_enqueue_scripts', 'apex_load_assets');

function apex_resource_hints($urls, $relation_type) {
  if ($relation_type === 'preconnect') {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin');
  }
  return $urls;
}
add_filter('wp_resource_hints', 'apex_resource_hints', 10, 2);

function apex_theme_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'apex_theme_support');

function apex_site_icon() {
  if (!has_site_icon()) {
    echo '<link rel="icon" href="' . esc_url(apex_media_url('logo-icon')) . '" type="image/webp">' . "\n";
  }
}
add_action('wp_head', 'apex_site_icon');


/**
 * Structured data (brief §6): AccountingService with NAP matching the visible content.
 */
function apex_schema() {
  if (!is_front_page()) {
    return;
  }
  $c = apex_contact();
  $schema = array(
    '@context'  => 'https://schema.org',
    '@type'     => 'AccountingService',
    'name'      => $c['name'],
    'url'       => home_url('/'),
    'logo'      => apex_media_url('logo-black'),
    'image'     => apex_media_url('hero'),
    'telephone' => '+1-949-889-2739',
    'email'     => $c['email'],
    'address'   => array(
      '@type'           => 'PostalAddress',
      'streetAddress'   => $c['street'],
      'addressLocality' => $c['city'],
      'addressRegion'   => $c['region'],
      'postalCode'      => $c['postalCode'],
      'addressCountry'  => 'US',
    ),
    'hasOfferCatalog' => array(
      '@type'           => 'OfferCatalog',
      'name'            => 'Accounting, tax and advisory services',
      'itemListElement' => array_map(function ($s) {
        return array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => $s['title'], 'url' => $s['url']));
      }, apex_services()),
    ),
  );
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'apex_schema');
