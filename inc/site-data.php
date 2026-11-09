<?php
/**
 * Single source of truth for site-wide content used by both the PHP templates
 * and the React components (passed to JS as window.apexSite).
 *
 * PENDING CLIENT VALIDATION (brief §11): business name, team titles/credentials,
 * service order and descriptions. Do not add claims here without APEX approval.
 */

if (!defined('ABSPATH')) {
  exit;
}

function apex_asset($path) {
  return get_theme_file_uri('/assets/' . ltrim($path, '/'));
}

/**
 * Inline SVG icon (stroke icons, 24x24 grid). Shared with React via inc/icons.json.
 */
function apex_icon($name, $class = 'size-6') {
  static $icons = null;
  if ($icons === null) {
    $icons = json_decode(file_get_contents(get_theme_file_path('/inc/icons.json')), true) ?: array();
  }
  if (empty($icons[$name])) {
    return '';
  }
  return sprintf(
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="%s" aria-hidden="true" focusable="false">%s</svg>',
    esc_attr($class),
    $icons[$name]
  );
}

function apex_contact() {
  return array(
    'name'         => 'Apex Accounting Inc.',
    'phone'        => '(949) 889-2739',
    'phoneHref'    => 'tel:+19498892739',
    'email'        => 'info@apexaccountinginc.com',
    'street'       => '6 Venture, Suite 310',
    'city'         => 'Irvine',
    'region'       => 'CA',
    'postalCode'   => '92618',
    'mapsUrl'      => 'https://maps.app.goo.gl/9snE5XRyEGkoukd18',
    // Do not change without client authorization (brief §5.1 / §7).
    'portalUrl'    => 'https://apexaccounting-advisory.client.myfirm360.com',
    'strategyUrl'  => home_url('/contact-us/#strategy-session'),
  );
}

function apex_nav() {
  return array(
    array('label' => 'Home', 'url' => home_url('/')),
    array('label' => 'About Us', 'url' => home_url('/about-us/')),
    array('label' => 'Services', 'url' => home_url('/services/'), 'children' => 'services'),
    array('label' => 'Team', 'url' => home_url('/team/')),
    array('label' => 'Contact', 'url' => home_url('/contact-us/')),
  );
}

/**
 * The eight existing service categories (brief §5.3). Each links to its own
 * anchor on /services/ until individual service pages (P2) are approved.
 */
function apex_services() {
  $services = array(
    array('slug' => 'strategic-tax-planning', 'title' => 'Strategic Tax Planning', 'icon' => 'target',
      'summary' => 'Proactive planning that aligns your tax position with your business goals.'),
    array('slug' => 'executive-accounting', 'title' => 'Executive Accounting', 'icon' => 'calculator',
      'summary' => 'Organized records and financial visibility to support leadership decisions.'),
    array('slug' => 'business-advisory', 'title' => 'Business Advisory', 'icon' => 'messages',
      'summary' => 'Guidance on the financial questions that come with running and growing a company.'),
    array('slug' => 'financial-reporting', 'title' => 'Financial Reporting', 'icon' => 'bar-chart',
      'summary' => 'Clear reports that help you understand performance and plan ahead.'),
    array('slug' => 'payroll-services', 'title' => 'Payroll Services', 'icon' => 'banknote',
      'summary' => 'Payroll processing support that keeps your team paid on schedule.'),
    array('slug' => 'irs-compliance', 'title' => 'IRS Compliance', 'icon' => 'shield-check',
      'summary' => 'Support meeting your filing and compliance obligations.'),
    array('slug' => 'corporate-tax-preparation', 'title' => 'Corporate Tax Preparation', 'icon' => 'file-text',
      'summary' => 'Preparation of tax returns for your business entity.'),
    array('slug' => 'business-structure', 'title' => 'Business Structure', 'icon' => 'layers',
      'summary' => 'Evaluating entity structure options with your long-term goals in mind.'),
  );

  foreach ($services as &$service) {
    $service['url'] = home_url('/services/#' . $service['slug']);
  }
  return $services;
}

/**
 * Team as published on /team/ (Nov 9, 2026). Titles pending APEX confirmation.
 * 'photo' is null when no approved photo exists.
 */
function apex_team() {
  return array(
    array('name' => 'Laura Elena Martinez', 'title' => 'Business Advisor Founder', 'photo' => 'laura-elena-martinez.webp'),
    array('name' => 'Karina Nava', 'title' => 'Enrolled Agent & Tax Expert', 'photo' => 'karina-nava.webp'),
    array('name' => 'Javier Bustamante', 'title' => 'Tax Manager', 'photo' => 'javier.webp'),
    array('name' => 'Andrea Sanchez', 'title' => 'Senior C.P.A. Partner', 'photo' => 'andrea-sanchez.webp'),
    array('name' => 'Susie Mena', 'title' => 'Accountant & Tax Expert', 'photo' => 'susie-mena.webp'),
    array('name' => 'Sam Rohani', 'title' => 'Controller', 'photo' => 'sam-rohani.webp'),
    array('name' => 'Ana Valdivia', 'title' => 'Accountant & Tax Expert', 'photo' => 'ana-valdivia.webp'),
    array('name' => 'Sergio García', 'title' => 'General Manager', 'photo' => 'sergio.webp'),
    array('name' => 'Elizabeth Rodriguez', 'title' => 'Tax Department Coordinator', 'photo' => 'elizabeth-rodriguez.webp'),
    array('name' => 'Ipzayana Monroy', 'title' => 'Service Department', 'photo' => 'ipzayana-monroy.webp'),
    array('name' => 'Wassim Attia', 'title' => 'Bookkeeper', 'photo' => null),
  );
}

/**
 * Home FAQs (brief §5.1 / §6 AEO).
 * PENDING CLIENT APPROVAL: answers only restate information already published on
 * the site. Flip APEX_FAQS_APPROVED to true once APEX signs off to also output
 * FAQPage structured data (brief: "FAQPage solo si procede").
 */
if (!defined('APEX_FAQS_APPROVED')) {
  define('APEX_FAQS_APPROVED', false);
}

function apex_home_faqs() {
  $c = apex_contact();
  return array(
    array(
      'q' => 'What services does Apex Accounting Inc. offer?',
      'a' => 'We offer eight core services: Strategic Tax Planning, Executive Accounting, Business Advisory, Financial Reporting, Payroll Services, IRS Compliance, Corporate Tax Preparation and Business Structure. Each engagement is scoped to the needs of your business.',
    ),
    array(
      'q' => 'Do you only prepare taxes?',
      'a' => 'No. Tax preparation is one part of what we do. We also help with tax planning, accounting, financial reporting, payroll, compliance and business advisory, so your financial decisions are connected to a clear structure rather than handled once a year.',
    ),
    array(
      'q' => 'Who do you work with?',
      'a' => 'We work with business owners, established entrepreneurs and the people responsible for running companies who want financial order and professional guidance as their business grows.',
    ),
    array(
      'q' => 'What is a Strategy Session?',
      'a' => 'A Strategy Session is a private conversation with our team to understand your business, how it is currently structured and where you want it to go, so we can identify how we may be able to help.',
    ),
    array(
      'q' => 'How do I get started?',
      'a' => sprintf(
        'Schedule a Strategy Session through our contact page or call us at %s. You can also email %s. Please do not send Social Security numbers, bank details or tax documents through the website form.',
        $c['phone'],
        $c['email']
      ),
    ),
    array(
      'q' => 'Where is your office located?',
      'a' => sprintf('Our office is located at %s, %s, %s %s.', $c['street'], $c['city'], $c['region'], $c['postalCode']),
    ),
    array(
      'q' => 'What is the Client Portal?',
      'a' => 'The Client Portal is the online access point for existing APEX clients. If you are a client and need help accessing it, please contact our office.',
    ),
  );
}

function apex_faq_schema() {
  if (!APEX_FAQS_APPROVED || !is_front_page()) {
    return;
  }
  $schema = array(
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(function ($faq) {
      return array(
        '@type'          => 'Question',
        'name'           => $faq['q'],
        'acceptedAnswer' => array('@type' => 'Answer', 'text' => $faq['a']),
      );
    }, apex_home_faqs()),
  );
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'apex_faq_schema');

/**
 * Data handed to React components.
 */
function apex_js_data() {
  return array(
    'homeUrl'  => home_url('/'),
    'logo'     => array(
      'dark'  => apex_media_url('logo-black'),
      'light' => apex_media_url('logo-white'),
    ),
    'contact'  => apex_contact(),
    'nav'      => apex_nav(),
    'services' => array_map(function ($s) {
      return array('title' => $s['title'], 'url' => $s['url'], 'icon' => $s['icon']);
    }, apex_services()),
    'legal'    => array(
      array('label' => 'Privacy Policy', 'url' => home_url('/privacy-policy/')),
      array('label' => 'Terms & Conditions', 'url' => home_url('/terms-and-conditions/')),
    ),
  );
}
