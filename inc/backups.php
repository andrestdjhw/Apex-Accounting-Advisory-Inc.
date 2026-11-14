<?php
/**
 * UpdraftPlus: keep development folders out of the Themes backup.
 *
 * The free version has no "exclude" field for Themes, but it asks
 * `updraftplus_exclude_directory` for every folder before zipping it.
 * node_modules (~880 MB of build tooling) is never needed to restore the site:
 * the theme runs from build/, and node_modules is rebuilt with `npm install`.
 */

if (!defined('ABSPATH')) {
  exit;
}

function apex_updraft_exclude_dev_dirs($exclude, $fullpath) {
  if ($exclude) {
    return $exclude;
  }
  $themes_root = wp_normalize_path(get_theme_root());
  $path        = wp_normalize_path($fullpath);

  // Only inside wp-content/themes, any theme, any depth.
  if (strpos($path, $themes_root . '/') === 0 && basename($path) === 'node_modules') {
    return true;
  }
  return $exclude;
}
add_filter('updraftplus_exclude_directory', 'apex_updraft_exclude_dev_dirs', 10, 2);
