<?php
/**
 * Theme images ↔ WordPress Media Library.
 *
 * Every image the theme uses is registered in the Media Library (so WP generates
 * responsive sizes, keeps a backup in /uploads and lets editors change the alt
 * text). Templates ask for an image by key; if the attachment doesn't exist yet
 * the bundled copy in assets/images/ is used as a fallback.
 *
 * Import (idempotent, safe to re-run):
 *   - automatically when the theme is activated
 *   - `wp apex import-media` (WP-CLI)
 */

if (!defined('ABSPATH')) {
  exit;
}

const APEX_MEDIA_META_KEY = '_apex_asset_key';

/**
 * key => file in assets/images/, title and default alt text.
 */
function apex_media_manifest() {
  $manifest = array(
    'logo-black' => array('file' => 'logo-black.webp', 'title' => 'APEX Accounting & Advisory logo (dark)', 'alt' => 'Apex Accounting & Advisory'),
    'logo-white' => array('file' => 'logo-white.webp', 'title' => 'APEX Accounting & Advisory logo (white)', 'alt' => 'Apex Accounting & Advisory'),
    'logo-icon'  => array('file' => 'logo-icon.webp', 'title' => 'APEX site icon', 'alt' => 'APEX'),
    'hero'       => array('file' => 'hero-apex.webp', 'title' => 'APEX office building signage', 'alt' => ''),
    'process-bg' => array('file' => 'process-bg.webp', 'title' => 'Glass facade background (Pexels #33643675)', 'alt' => ''),
  );

  // Service photos: free-license stock from Pexels (pexels.com/license), photo IDs noted for reference.
  $service_photos = array(
    'strategic-tax-planning'    => array('id' => 6863243, 'alt' => 'Notebook with “tax planning” handwritten on grid paper next to a percent sign'),
    'executive-accounting'      => array('id' => 8296990, 'alt' => 'Accountant reviewing documents with a calculator and laptop'),
    'business-advisory'         => array('id' => 7979447, 'alt' => 'Advisor meeting with business owners around an office table'),
    'financial-reporting'       => array('id' => 8062287, 'alt' => 'Professionals reviewing a financial report with charts'),
    'payroll-services'          => array('id' => 6929012, 'alt' => 'Calendar, clipboard with forms and a calculator on a desk'),
    'irs-compliance'            => array('id' => 15116664, 'alt' => 'Federal tax return form on a desk'),
    'corporate-tax-preparation' => array('id' => 3771097, 'alt' => 'Business executive signing documents at a desk'),
    'business-structure'        => array('id' => 8424567, 'alt' => 'Two professionals planning a business strategy on a whiteboard'),
  );
  foreach ($service_photos as $slug => $photo) {
    $manifest['service-' . $slug] = array(
      'file'  => 'services/' . $slug . '.webp',
      'title' => 'Service photo — ' . $slug . ' (Pexels #' . $photo['id'] . ')',
      'alt'   => $photo['alt'],
    );
  }

  foreach (apex_team() as $member) {
    if (!empty($member['photo'])) {
      $manifest[apex_team_media_key($member)] = array(
        'file'  => 'team/' . $member['photo'],
        'title' => $member['name'] . ' — ' . $member['title'],
        'alt'   => 'Portrait of ' . $member['name'],
      );
    }
  }

  return $manifest;
}

function apex_team_media_key($member) {
  return 'team-' . sanitize_title($member['name']);
}

/**
 * Attachment ID for a key, or 0. One query per request.
 */
function apex_media_id($key) {
  static $map = null;

  if ($map === null) {
    $map = array();
    $ids = get_posts(array(
      'post_type'      => 'attachment',
      'post_status'    => 'inherit',
      'posts_per_page' => -1,
      'fields'         => 'ids',
      'meta_key'       => APEX_MEDIA_META_KEY,
      'no_found_rows'  => true,
    ));
    foreach ($ids as $id) {
      $map[get_post_meta($id, APEX_MEDIA_META_KEY, true)] = (int) $id;
    }
  }

  return $map[$key] ?? 0;
}

/**
 * Image URL for a key (Media Library first, theme fallback).
 */
function apex_media_url($key, $size = 'full') {
  $id = apex_media_id($key);
  if ($id && ($url = wp_get_attachment_image_url($id, $size))) {
    return $url;
  }
  $manifest = apex_media_manifest();
  return isset($manifest[$key]) ? apex_asset('images/' . $manifest[$key]['file']) : '';
}

/**
 * <img> tag for a key. $attr is passed to wp_get_attachment_image() (class,
 * sizes, loading, fetchpriority, alt…); the fallback honors the same attributes.
 */
function apex_media_img($key, $size = 'full', $attr = array()) {
  $id = apex_media_id($key);
  if ($id) {
    $html = wp_get_attachment_image($id, $size, false, $attr);
    if ($html) {
      return $html;
    }
  }

  $manifest = apex_media_manifest();
  if (!isset($manifest[$key])) {
    return '';
  }

  $attr = array_merge(array('alt' => $manifest[$key]['alt']), $attr);
  $attr['src'] = apex_asset('images/' . $manifest[$key]['file']);
  $path = get_theme_file_path('/assets/images/' . $manifest[$key]['file']);
  if (empty($attr['width']) && file_exists($path) && ($dims = wp_getimagesize($path))) {
    $attr['width']  = $dims[0];
    $attr['height'] = $dims[1];
  }

  $html = '<img';
  foreach ($attr as $name => $value) {
    if ($value === false || $value === null) {
      continue;
    }
    $html .= ' ' . $name . '="' . ($name === 'src' ? esc_url($value) : esc_attr($value)) . '"';
  }
  return $html . '>';
}

/**
 * Registers every manifest image in the Media Library. Returns a report.
 */
function apex_import_media() {
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';
  require_once ABSPATH . 'wp-admin/includes/image.php';

  $report = array();

  foreach (apex_media_manifest() as $key => $item) {
    if (apex_media_id_fresh($key)) {
      $report[$key] = 'exists';
      continue;
    }

    $source = get_theme_file_path('/assets/images/' . $item['file']);
    if (!file_exists($source)) {
      $report[$key] = 'missing source file';
      continue;
    }

    // media_handle_sideload() moves the file, so work on a temp copy.
    $tmp = wp_tempnam(basename($source));
    copy($source, $tmp);

    $id = media_handle_sideload(
      array('name' => 'apex-' . str_replace('/', '-', $item['file']), 'tmp_name' => $tmp),
      0,
      $item['title']
    );

    if (is_wp_error($id)) {
      @unlink($tmp);
      $report[$key] = 'error: ' . $id->get_error_message();
      continue;
    }

    update_post_meta($id, APEX_MEDIA_META_KEY, $key);
    update_post_meta($id, '_wp_attachment_image_alt', $item['alt']);
    $report[$key] = 'imported #' . $id;
  }

  return $report;
}

/**
 * Uncached lookup used during import.
 */
function apex_media_id_fresh($key) {
  $ids = get_posts(array(
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'posts_per_page' => 1,
    'fields'         => 'ids',
    'meta_key'       => APEX_MEDIA_META_KEY,
    'meta_value'     => $key,
    'no_found_rows'  => true,
  ));
  return $ids ? (int) $ids[0] : 0;
}

add_action('after_switch_theme', 'apex_import_media');

if (defined('WP_CLI') && WP_CLI) {
  /**
   * Registers the theme images in the Media Library (skips ones already there).
   */
  WP_CLI::add_command('apex import-media', function () {
    foreach (apex_import_media() as $key => $status) {
      WP_CLI::log(str_pad($key, 32) . $status);
    }
    WP_CLI::success('Media import finished.');
  });
}
