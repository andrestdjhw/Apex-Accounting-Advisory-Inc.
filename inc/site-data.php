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
    // Keyless Google Maps embed; validate the pin matches the Google Business Profile (brief §5.5).
    'mapsEmbed'    => 'https://www.google.com/maps?q=' . rawurlencode('Apex Accounting Inc, 6 Venture Suite 310, Irvine, CA 92618') . '&z=15&output=embed',
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
 * The eight existing service categories (brief §5.3), in the published order.
 * Each one has its own section/anchor on /services/ (brief template: benefit →
 * who it's for → what's included → CTA → related services).
 *
 * PENDING CLIENT VALIDATION: "includes" describes typical scope in general terms;
 * APEX must confirm deliverables, entity/return types and jurisdictions. No
 * guarantees, savings promises or IRS representation claims (brief §5.3).
 */
function apex_services() {
  $services = array(
    array(
      'slug'     => 'strategic-tax-planning',
      'title'    => 'Strategic Tax Planning',
      'icon'     => 'target',
      'summary'  => 'Proactive planning that aligns your tax position with your business goals.',
      'intro'    => 'Planning that looks ahead of filing season, so tax considerations become part of your business decisions instead of an afterthought.',
      'audience' => 'Business owners who want to understand the tax implications of their decisions before they make them.',
      'includes' => array(
        'Review of your current tax position and business activity',
        'Planning conversations around upcoming decisions and their timing',
        'Coordination with your accounting and tax preparation',
        'Periodic check-ins as your business changes',
      ),
      'related'  => array('corporate-tax-preparation', 'business-structure'),
    ),
    array(
      'slug'     => 'executive-accounting',
      'title'    => 'Executive Accounting',
      'icon'     => 'calculator',
      'summary'  => 'Organized records and financial visibility to support leadership decisions.',
      'intro'    => 'Organized, accurate financial records that give leadership a reliable view of how the business is doing.',
      'audience' => 'Companies that need dependable books and clearer financial visibility for decision-making.',
      'includes' => array(
        'Bookkeeping and account reconciliation',
        'Organized, up-to-date financial records',
        'Regular review of financial activity with your team',
        'Support for decisions that depend on accurate numbers',
      ),
      'related'  => array('financial-reporting', 'payroll-services'),
    ),
    array(
      'slug'     => 'business-advisory',
      'title'    => 'Business Advisory',
      'icon'     => 'messages',
      'summary'  => 'Guidance on the financial questions that come with running and growing a company.',
      'intro'    => 'Guidance on the financial side of running and growing a company, from day-to-day questions to longer-term plans.',
      'audience' => 'Owners and leaders facing decisions about growth, operations, profitability or change.',
      'includes' => array(
        'Conversations about your goals and current challenges',
        'Review of the financial information behind your decisions',
        'Recommendations with clear priorities and next steps',
        'Ongoing advisory as your needs evolve',
      ),
      'related'  => array('strategic-tax-planning', 'financial-reporting'),
    ),
    array(
      'slug'     => 'financial-reporting',
      'title'    => 'Financial Reporting',
      'icon'     => 'bar-chart',
      'summary'  => 'Clear reports that help you understand performance and plan ahead.',
      'intro'    => 'Clear financial reports that show how the business is performing and help you plan what comes next.',
      'audience' => 'Owners who want to read their numbers with confidence, and companies that need reports for planning, partners or lenders.',
      'includes' => array(
        'Preparation of periodic financial statements',
        'Reports focused on the information you actually use',
        'Walk-throughs that explain what the numbers show',
        'Support connecting reports to your planning',
      ),
      'related'  => array('executive-accounting', 'business-advisory'),
    ),
    array(
      'slug'     => 'payroll-services',
      'title'    => 'Payroll Services',
      'icon'     => 'banknote',
      'summary'  => 'Payroll processing support that keeps your team paid on schedule.',
      'intro'    => 'Payroll processing support that helps your team get paid accurately and on schedule.',
      'audience' => 'Businesses with employees that want payroll handled consistently and in step with their accounting.',
      'includes' => array(
        'Payroll processing on an agreed schedule',
        'Payroll records coordinated with your accounting',
        'Support with payroll-related filings within the agreed scope',
        'A point of contact for payroll questions',
      ),
      'related'  => array('executive-accounting', 'irs-compliance'),
    ),
    array(
      'slug'     => 'irs-compliance',
      'title'    => 'IRS Compliance',
      'icon'     => 'shield-check',
      'summary'  => 'Support meeting your filing and compliance obligations.',
      'intro'    => 'Support understanding and keeping up with your tax filing and compliance obligations.',
      'audience' => 'Businesses that want help staying organized and current with their federal tax obligations.',
      'includes' => array(
        'Review of your filing obligations and deadlines',
        'Organization of the records compliance depends on',
        'Guidance on next steps when questions come up',
        'Coordination with your tax preparation and planning',
      ),
      'related'  => array('corporate-tax-preparation', 'strategic-tax-planning'),
    ),
    array(
      'slug'     => 'corporate-tax-preparation',
      'title'    => 'Corporate Tax Preparation',
      'icon'     => 'file-text',
      'summary'  => 'Preparation of tax returns for your business entity.',
      'intro'    => 'Preparation of tax returns for your business entity, built on organized records and coordinated with your year-round planning.',
      'audience' => 'Corporations and other business entities that need their returns prepared carefully and on time.',
      'includes' => array(
        'Gathering and review of the information your return requires',
        'Preparation of your business tax returns',
        'A review of the return with you before filing',
        'Coordination with planning for the year ahead',
      ),
      'related'  => array('strategic-tax-planning', 'irs-compliance'),
    ),
    array(
      'slug'     => 'business-structure',
      'title'    => 'Business Structure',
      'icon'     => 'layers',
      'summary'  => 'Evaluating entity structure options with your long-term goals in mind.',
      'intro'    => 'Evaluating how your business is organized, so its structure fits your goals, operations and growth plans.',
      'audience' => 'New ventures choosing an entity, and established companies wondering whether their current structure still fits.',
      'includes' => array(
        'Review of your current or planned structure',
        'Discussion of entity options and their general tax and accounting considerations',
        'Coordination with your legal advisors where needed',
        'Support with the accounting side of any change',
      ),
      'related'  => array('strategic-tax-planning', 'business-advisory'),
    ),
  );

  foreach ($services as &$service) {
    $service['url'] = home_url('/services/#' . $service['slug']);
  }
  return $services;
}

function apex_service($slug) {
  foreach (apex_services() as $service) {
    if ($service['slug'] === $slug) {
      return $service;
    }
  }
  return null;
}

/**
 * Value pillars shown under the home hero.
 */
function apex_pillars() {
  return array(
    array('icon' => 'compass', 'title' => 'Plan Strategically', 'text' => 'Optimize your tax position and financial structure.'),
    array('icon' => 'clipboard-check', 'title' => 'Stay Compliant', 'text' => 'Meet your accounting and IRS obligations with confidence.'),
    array('icon' => 'trending-up', 'title' => 'Gain Clarity', 'text' => 'Understand your numbers and make informed decisions.'),
    array('icon' => 'users', 'title' => 'Grow with a Partner', 'text' => 'Ongoing guidance for today and what’s next.'),
  );
}

/**
 * PENDING CLIENT CONFIRMATION (brief §5.1): example process, replace with APEX's real one.
 */
function apex_steps() {
  return array(
    array('icon' => 'search', 'title' => 'Discovery', 'text' => 'We learn about your business, your goals and the questions you need answered.'),
    array('icon' => 'clipboard-check', 'title' => 'Review & Assessment', 'text' => 'We review your current accounting, tax position and business structure.'),
    array('icon' => 'target', 'title' => 'Strategy', 'text' => 'We outline a plan with clear priorities and next steps.'),
    array('icon' => 'users', 'title' => 'Ongoing Support', 'text' => 'We stay involved as your business and its obligations evolve.'),
  );
}

/**
 * About Us values (published copy) + what each means for the client (brief §5.2).
 */
function apex_values() {
  return array(
    array(
      'icon'   => 'compass',
      'title'  => 'Strategic Thinking',
      'text'   => 'Creating proactive financial strategies that support sustainable business success and long-term growth.',
      'client' => 'Conversations focus on what’s ahead, not only on what already happened.',
    ),
    array(
      'icon'   => 'shield-check',
      'title'  => 'Integrity',
      'text'   => 'Building lasting client relationships through honesty, transparency, accountability, and professional financial guidance.',
      'client' => 'Clear explanations of what we recommend, why, and what it involves.',
    ),
    array(
      'icon'   => 'target',
      'title'  => 'Excellence',
      'text'   => 'Delivering accurate financial solutions with precision, consistency, expertise, and exceptional client service.',
      'client' => 'Careful, consistent work you can rely on when decisions depend on it.',
    ),
    array(
      'icon'   => 'users',
      'title'  => 'Client Partnership',
      'text'   => 'Working alongside clients to achieve confident decisions, measurable growth, and lasting financial success.',
      'client' => 'A team that stays involved as your business changes.',
    ),
  );
}

/**
 * Team as published on /team/ (Nov 9, 2026). Titles pending APEX confirmation.
 * 'photo' is null when no approved photo exists.
 */
function apex_team() {
  return array(
    array('name' => 'Laura Elena Martinez', 'title' => 'Business Advisor Founder', 'photo' => 'laura-elena-martinez.webp', 'leadership' => true),
    array('name' => 'Karina Nava', 'title' => 'Enrolled Agent & Tax Expert', 'photo' => 'karina-nava.webp'),
    array('name' => 'Javier Bustamante', 'title' => 'Tax Manager', 'photo' => 'javier.webp'),
    array('name' => 'Andrea Sanchez', 'title' => 'Senior C.P.A. Partner', 'photo' => 'andrea-sanchez.webp', 'leadership' => true),
    array('name' => 'Susie Mena', 'title' => 'Accountant & Tax Expert', 'photo' => 'susie-mena.webp'),
    array('name' => 'Sam Rohani', 'title' => 'Controller', 'photo' => 'sam-rohani.webp'),
    array('name' => 'Ana Valdivia', 'title' => 'Accountant & Tax Expert', 'photo' => 'ana-valdivia.webp'),
    array('name' => 'Sergio García', 'title' => 'General Manager', 'photo' => 'sergio.webp', 'leadership' => true),
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
    'form'     => array(
      'endpoint'   => rest_url('apex/v1/strategy-session'),
      'timeframes' => apex_form_options()['timeframes'],
      'services'   => apex_form_options()['services'],
      'privacyUrl' => home_url('/privacy-policy/'),
      'termsUrl'   => home_url('/terms-and-conditions/'),
    ),
    'legal'    => array(
      array('label' => 'Privacy Policy', 'url' => home_url('/privacy-policy/')),
      array('label' => 'Terms & Conditions', 'url' => home_url('/terms-and-conditions/')),
    ),
  );
}

/**
 * Section eyebrow with the animated rule ("OUR SERVICES ———").
 */
function apex_eyebrow($text, $tone = 'light') {
  $color = $tone === 'dark' ? 'text-apex-mist' : 'text-apex-sky';
  $line  = $tone === 'dark' ? 'bg-apex-mist' : 'bg-apex-sky';
  return sprintf(
    '<p class="apex-eyebrow flex items-center gap-4 %1$s">%2$s <span class="h-px w-16 %3$s" data-reveal="line" style="--reveal-delay:300ms" aria-hidden="true"></span></p>',
    $color,
    esc_html($text),
    $line
  );
}
