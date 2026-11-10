<?php
/**
 * Strategy Session form backend (brief §5.5).
 *
 * POST /wp-json/apex/v1/strategy-session
 *  - server-side validation with per-field error messages
 *  - anti-spam: honeypot field, minimum fill time, per-IP rate limit
 *  - every lead is stored as a private "Lead" in wp-admin (follow-up / audit trail)
 *  - notification email to the firm, with Reply-To set to the prospect
 *  - never asks for or stores SSN, bank details or tax documents
 */

if (!defined('ABSPATH')) {
  exit;
}

const APEX_LEAD_POST_TYPE = 'apex_lead';

/**
 * Options shown in the form. Timeframes match the published form.
 */
function apex_form_options() {
  return array(
    'timeframes' => array(
      'asap'       => 'ASAP',
      '1-3-months' => '1-3 Months',
      '3-6-months' => '3-6 Months',
    ),
    'services'   => array_combine(
      wp_list_pluck(apex_services(), 'slug'),
      wp_list_pluck(apex_services(), 'title')
    ),
  );
}

/**
 * Leads: private post type, visible only to administrators.
 */
function apex_register_lead_post_type() {
  register_post_type(APEX_LEAD_POST_TYPE, array(
    'labels'          => array(
      'name'          => 'Leads',
      'singular_name' => 'Lead',
      'menu_name'     => 'Strategy Leads',
      'all_items'     => 'All Leads',
      'edit_item'     => 'Lead',
      'search_items'  => 'Search Leads',
      'not_found'     => 'No leads yet.',
    ),
    'public'          => false,
    'show_ui'         => true,
    'show_in_rest'    => false,
    'menu_icon'       => 'dashicons-groups',
    'menu_position'   => 25,
    'supports'        => array('title'),
    'capability_type' => 'post',
    'capabilities'    => array(
      'create_posts' => 'do_not_allow',
      'edit_posts'   => 'manage_options',
      'edit_others_posts' => 'manage_options',
      'delete_posts' => 'manage_options',
      'publish_posts' => 'manage_options',
      'read_private_posts' => 'manage_options',
      'edit_private_posts' => 'manage_options',
      'edit_published_posts' => 'manage_options',
      'delete_others_posts' => 'manage_options',
      'delete_private_posts' => 'manage_options',
      'delete_published_posts' => 'manage_options',
    ),
    'map_meta_cap'    => true,
  ));
}
add_action('init', 'apex_register_lead_post_type');

function apex_lead_fields() {
  return array(
    'first_name' => 'First name',
    'last_name'  => 'Last name',
    'company'    => 'Company',
    'email'      => 'Email',
    'phone'      => 'Phone',
    'city'       => 'City',
    'state'      => 'State',
    'timeframe'  => 'Start timeframe',
    'service'    => 'Service of interest',
    'message'    => 'Message',
    'source_url' => 'Submitted from',
  );
}

/* Admin list + detail view */
add_filter('manage_' . APEX_LEAD_POST_TYPE . '_posts_columns', function ($columns) {
  return array(
    'cb'        => $columns['cb'],
    'title'     => 'Name',
    'email'     => 'Email',
    'phone'     => 'Phone',
    'timeframe' => 'Timeframe',
    'service'   => 'Service',
    'date'      => 'Received',
  );
});

add_action('manage_' . APEX_LEAD_POST_TYPE . '_posts_custom_column', function ($column, $post_id) {
  $value = get_post_meta($post_id, '_apex_' . $column, true);
  if ($column === 'email' && $value) {
    printf('<a href="mailto:%1$s">%1$s</a>', esc_html($value));
    return;
  }
  if ($column === 'timeframe') {
    $value = apex_form_options()['timeframes'][$value] ?? $value;
  }
  if ($column === 'service') {
    $value = apex_form_options()['services'][$value] ?? '—';
  }
  echo esc_html($value ?: '—');
}, 10, 2);

add_action('add_meta_boxes', function () {
  add_meta_box('apex_lead_details', 'Lead details', function ($post) {
    $options = apex_form_options();
    echo '<table class="widefat striped"><tbody>';
    foreach (apex_lead_fields() as $key => $label) {
      $value = get_post_meta($post->ID, '_apex_' . $key, true);
      if ($key === 'timeframe') {
        $value = $options['timeframes'][$value] ?? $value;
      }
      if ($key === 'service') {
        $value = $options['services'][$value] ?? $value;
      }
      printf('<tr><th style="width:180px">%s</th><td>%s</td></tr>', esc_html($label), nl2br(esc_html($value ?: '—')));
    }
    echo '</tbody></table>';
  }, APEX_LEAD_POST_TYPE, 'normal', 'high');
});

/* REST endpoint */
add_action('rest_api_init', function () {
  register_rest_route('apex/v1', '/strategy-session', array(
    'methods'             => 'POST',
    'callback'            => 'apex_handle_strategy_session',
    'permission_callback' => '__return_true',
  ));
});

function apex_client_ip_hash() {
  $ip = $_SERVER['REMOTE_ADDR'] ?? '';
  return hash_hmac('sha256', $ip, wp_salt('nonce'));
}

function apex_handle_strategy_session(WP_REST_Request $request) {
  $data = $request->get_json_params();
  if (!is_array($data)) {
    $data = $request->get_body_params();
  }

  // Honeypot: real visitors never see or fill this field. Pretend success.
  if (!empty($data['website'])) {
    return new WP_REST_Response(array('ok' => true), 200);
  }

  // Minimum time on form (bots submit instantly).
  $elapsed = isset($data['elapsed']) ? (int) $data['elapsed'] : 0;
  if ($elapsed < 3000) {
    return new WP_REST_Response(array('ok' => false, 'message' => 'Please take a moment to review your information and try again.'), 400);
  }

  // Rate limit: 5 submissions per hour per visitor.
  $rate_key = 'apex_rl_' . substr(apex_client_ip_hash(), 0, 32);
  $count    = (int) get_transient($rate_key);
  if ($count >= 5) {
    return new WP_REST_Response(array('ok' => false, 'message' => 'We’ve received several requests from your connection. Please call us at ' . apex_contact()['phone'] . '.'), 429);
  }

  $options = apex_form_options();
  $clean   = array(
    'first_name' => sanitize_text_field($data['first_name'] ?? ''),
    'last_name'  => sanitize_text_field($data['last_name'] ?? ''),
    'company'    => sanitize_text_field($data['company'] ?? ''),
    'email'      => sanitize_email($data['email'] ?? ''),
    'phone'      => sanitize_text_field($data['phone'] ?? ''),
    'city'       => sanitize_text_field($data['city'] ?? ''),
    'state'      => sanitize_text_field($data['state'] ?? ''),
    'timeframe'  => sanitize_key($data['timeframe'] ?? ''),
    'service'    => sanitize_key($data['service'] ?? ''),
    'message'    => sanitize_textarea_field($data['message'] ?? ''),
    'source_url' => esc_url_raw($data['source_url'] ?? ''),
  );
  $consent = !empty($data['consent']);

  $errors = array();
  if ($clean['first_name'] === '') $errors['first_name'] = 'Please enter your first name.';
  if ($clean['last_name'] === '')  $errors['last_name'] = 'Please enter your last name.';
  if (!is_email($clean['email']))  $errors['email'] = 'Please enter a valid email address.';
  if (strlen(preg_replace('/\D/', '', $clean['phone'])) < 10) $errors['phone'] = 'Please enter a valid phone number, including area code.';
  if (!isset($options['timeframes'][$clean['timeframe']])) $errors['timeframe'] = 'Please choose when you’d like to begin.';
  if ($clean['service'] !== '' && !isset($options['services'][$clean['service']])) $clean['service'] = '';
  if (mb_strlen($clean['message']) > 1000) $errors['message'] = 'Please keep your message under 1,000 characters.';
  if (!$consent) $errors['consent'] = 'Please accept the Privacy Policy and Terms & Conditions.';

  foreach (array('first_name', 'last_name', 'company', 'city', 'state') as $field) {
    if (mb_strlen($clean[$field]) > 120) $errors[$field] = 'This field is too long.';
  }

  if ($errors) {
    return new WP_REST_Response(array('ok' => false, 'message' => 'Please review the highlighted fields.', 'errors' => $errors), 422);
  }

  set_transient($rate_key, $count + 1, HOUR_IN_SECONDS);

  $name    = trim($clean['first_name'] . ' ' . $clean['last_name']);
  $lead_id = wp_insert_post(array(
    'post_type'   => APEX_LEAD_POST_TYPE,
    'post_status' => 'private',
    'post_title'  => $name . ($clean['company'] ? ' — ' . $clean['company'] : ''),
  ), true);

  if (!is_wp_error($lead_id)) {
    foreach ($clean as $key => $value) {
      update_post_meta($lead_id, '_apex_' . $key, $value);
    }
    update_post_meta($lead_id, '_apex_consent', current_time('mysql'));
  }

  $sent = apex_send_lead_notification($clean, is_wp_error($lead_id) ? 0 : $lead_id);

  if (is_wp_error($lead_id) && !$sent) {
    return new WP_REST_Response(array('ok' => false, 'message' => 'We couldn’t send your request. Please call us at ' . apex_contact()['phone'] . '.'), 500);
  }

  return new WP_REST_Response(array('ok' => true), 200);
}

function apex_send_lead_notification($lead, $lead_id) {
  $contact   = apex_contact();
  $options   = apex_form_options();
  $recipient = apply_filters('apex_lead_recipient', $contact['email']);

  $lines = array();
  foreach (apex_lead_fields() as $key => $label) {
    $value = $lead[$key] ?? '';
    if ($key === 'timeframe') $value = $options['timeframes'][$value] ?? $value;
    if ($key === 'service')   $value = $options['services'][$value] ?? '';
    $lines[] = $label . ': ' . ($value !== '' ? $value : '—');
  }
  if ($lead_id) {
    $lines[] = '';
    $lines[] = 'View in WordPress: ' . admin_url('post.php?post=' . $lead_id . '&action=edit');
  }

  $subject = sprintf('New Strategy Session request — %s %s', $lead['first_name'], $lead['last_name']);
  $headers = array('Reply-To: ' . $lead['first_name'] . ' ' . $lead['last_name'] . ' <' . $lead['email'] . '>');

  return wp_mail($recipient, $subject, implode("\n", $lines), $headers);
}
