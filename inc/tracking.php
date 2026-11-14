<?php
/**
 * Measurement tags (828 review — Carlos: GTM, GA4, Search Console, Clarity).
 *
 * IDs live in Settings → APEX Tracking (or as constants in wp-config.php, which win):
 *   APEX_GTM_ID         GTM-XXXXXXX   GA4 is configured inside this GTM container.
 *   APEX_CLARITY_ID     abcdefghij    Microsoft Clarity project ID.
 *   APEX_GSC_TOKEN      …             Search Console "HTML tag" verification token.
 *
 * Nothing is printed while an ID is empty. Consent: header.php sets Google Consent
 * Mode v2 defaults before GTM loads; Clarity waits for analytics consent from the
 * cookie banner (src/components/CookieConsent.js → "apex:consent" event).
 */

if (!defined('ABSPATH')) {
  exit;
}

function apex_tracking_settings() {
  return array(
    'gtm'     => array('label' => 'Google Tag Manager container ID', 'constant' => 'APEX_GTM_ID', 'pattern' => '/^GTM-[A-Z0-9]+$/', 'placeholder' => 'GTM-XXXXXXX', 'help' => 'Configure GA4 inside this container.'),
    'clarity' => array('label' => 'Microsoft Clarity project ID', 'constant' => 'APEX_CLARITY_ID', 'pattern' => '/^[a-z0-9]{6,20}$/', 'placeholder' => 'abcdefghij', 'help' => 'Loads only after the visitor accepts analytics cookies.'),
    'gsc'     => array('label' => 'Search Console verification token', 'constant' => 'APEX_GSC_TOKEN', 'pattern' => '/^[A-Za-z0-9_-]{20,100}$/', 'placeholder' => 'content value of the google-site-verification tag', 'help' => 'Only needed for the "HTML tag" verification method.'),
  );
}

function apex_tracking_id($key) {
  $settings = apex_tracking_settings();
  $constant = $settings[$key]['constant'];
  $value    = defined($constant) ? constant($constant) : get_option('apex_tracking_' . $key, '');
  return preg_match($settings[$key]['pattern'], (string) $value) ? $value : '';
}

/* ---------- Front end ---------- */

function apex_tracking_head() {
  if ($token = apex_tracking_id('gsc')) {
    printf('<meta name="google-site-verification" content="%s">' . "\n", esc_attr($token));
  }

  if ($gtm = apex_tracking_id('gtm')) {
    // Runs after the Consent Mode defaults in header.php.
    printf(
      "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','%s');</script>\n",
      esc_js($gtm)
    );
  }

  if ($clarity = apex_tracking_id('clarity')) {
    printf(
      "<script>(function(){var id='%s';function load(){if(window.clarity)return;(function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window,document,'clarity','script',id);}var c=null;try{c=JSON.parse(localStorage.getItem('apex_cookie_consent'));}catch(e){}if(c&&c.analytics&&Date.now()-c.date<31536000000)load();window.addEventListener('apex:consent',function(e){if(e.detail&&e.detail.analytics)load();});})();</script>\n",
      esc_js($clarity)
    );
  }
}
add_action('wp_head', 'apex_tracking_head', 2);

function apex_tracking_body() {
  if ($gtm = apex_tracking_id('gtm')) {
    printf(
      '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>' . "\n",
      esc_attr($gtm)
    );
  }
}
add_action('wp_body_open', 'apex_tracking_body');

/* ---------- Settings → APEX Tracking ---------- */

function apex_tracking_register() {
  foreach (apex_tracking_settings() as $key => $field) {
    register_setting('apex_tracking', 'apex_tracking_' . $key, array(
      'type'              => 'string',
      'default'           => '',
      'sanitize_callback' => function ($value) use ($field, $key) {
        $value = trim((string) $value);
        if ($value !== '' && !preg_match($field['pattern'], $value)) {
          add_settings_error('apex_tracking', 'apex_tracking_' . $key, sprintf('“%s” doesn’t look valid and was not saved.', $field['label']));
          return get_option('apex_tracking_' . $key, '');
        }
        return $value;
      },
    ));
  }
}
add_action('admin_init', 'apex_tracking_register');

function apex_tracking_menu() {
  add_options_page('APEX Tracking', 'APEX Tracking', 'manage_options', 'apex-tracking', 'apex_tracking_page');
}
add_action('admin_menu', 'apex_tracking_menu');

function apex_tracking_page() {
  ?>
  <div class="wrap">
    <h1>APEX Tracking</h1>
    <p>Measurement tags for this site. Leave a field empty to disable that tag. Values defined as constants in <code>wp-config.php</code> take priority and can’t be edited here.</p>
    <form method="post" action="options.php">
      <?php settings_fields('apex_tracking'); ?>
      <table class="form-table" role="presentation">
        <?php foreach (apex_tracking_settings() as $key => $field) : ?>
          <?php $locked = defined($field['constant']); ?>
          <tr>
            <th scope="row"><label for="apex_tracking_<?php echo esc_attr($key); ?>"><?php echo esc_html($field['label']); ?></label></th>
            <td>
              <input type="text" class="regular-text code" id="apex_tracking_<?php echo esc_attr($key); ?>" name="apex_tracking_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($locked ? constant($field['constant']) : get_option('apex_tracking_' . $key, '')); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php disabled($locked); ?>>
              <p class="description"><?php echo esc_html($field['help']); ?><?php echo $locked ? ' ' . esc_html(sprintf('Set by the %s constant.', $field['constant'])) : ''; ?></p>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}
