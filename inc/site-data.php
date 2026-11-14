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
    'name'         => apex_brand_name(), // single brand name (inc/seo.php)
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
    $service['url'] = home_url('/services/' . $service['slug'] . '/');
    // PENDING 828/APEX: concrete deliverables (shown as a marked placeholder). FAQs: apex_service_faqs().
    $service['deliverables'] = $service['deliverables'] ?? array();
    $service['faqs']         = apex_service_faqs()[$service['slug']] ?? array();
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
    array('name' => 'Laura Elena Martinez', 'title' => 'Business Advisor', 'title_pending' => 'Founder / co-founder title — pending confirmation', 'credentials' => null, 'bio' => null, 'photo' => 'laura-elena-martinez.webp', 'leadership' => true),
    array('name' => 'Karina Nava', 'title' => 'Enrolled Agent & Tax Expert', 'photo' => 'karina-nava.webp'),
    array('name' => 'Javier Bustamante', 'title' => 'Tax Manager', 'photo' => 'javier.webp'),
    array('name' => 'Andrea Sanchez', 'title' => 'Senior C.P.A. Partner', 'credentials' => null, 'bio' => null, 'photo' => 'andrea-sanchez.webp', 'leadership' => true),
    array('name' => 'Susie Mena', 'title' => 'Accountant & Tax Expert', 'photo' => 'susie-mena.webp'),
    array('name' => 'Sam Rohani', 'title' => 'Controller', 'photo' => 'sam-rohani.webp'),
    array('name' => 'Ana Valdivia', 'title' => 'Accountant & Tax Expert', 'photo' => 'ana-valdivia.webp'),
    array('name' => 'Sergio García', 'title' => 'General Manager', 'credentials' => null, 'bio' => null, 'photo' => 'sergio.webp', 'leadership' => true),
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
      'q' => 'What services does ' . apex_brand_name() . ' offer?',
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
      'q' => 'What is the difference between tax preparation and tax planning?',
      'a' => 'Tax preparation looks back: it reports what already happened during the year and files the return. Tax planning looks ahead: it considers the tax side of a decision before you make it, such as its timing or how the business is set up. We offer both, so they work together.',
    ),
    array(
      'q' => 'When is the best time to start tax planning?',
      'a' => 'Before the decisions it affects, and well ahead of filing season. Once a year has closed, there are fewer choices left to make about it. If you are not sure where to begin, a Strategy Session is a good first step at any time of year.',
    ),
    array(
      'q' => 'What is a Strategy Session?',
      'a' => 'A Strategy Session is a private conversation with our team to understand your business, how it is currently structured and where you want it to go, so we can identify how we may be able to help.',
    ),
    array(
      'q' => 'What should I have ready for the first conversation?',
      'a' => 'A general picture is enough: what your business does, how it is set up, who handles your books and taxes today, and the questions on your mind. If we need documents later, we will tell you which ones and how to share them securely.',
    ),
    array(
      'q' => 'Can you work alongside my current bookkeeper or accountant?',
      'a' => 'Several of our services include coordinating with the people who already handle your books or returns. We will talk through your current setup in the Strategy Session and agree on who does what before any work begins.',
    ),
    array(
      'q' => 'I received a letter from the IRS. Can you help?',
      'a' => 'Bring the notice to a Strategy Session, and keep an eye on any response date it shows. Our IRS Compliance service helps you understand your filing obligations and the next steps a notice may call for. The scope of any help is confirmed with you individually.',
    ),
    array(
      'q' => 'How are your fees determined?',
      'a' => 'Fees depend on the services and the scope your business needs, so we confirm them with you before any engagement begins. The Strategy Session helps us understand what that scope looks like.',
    ),
    array(
      'q' => 'How do I share documents with APEX?',
      'a' => 'Existing clients share information through the APEX Client Portal, the online access point for clients. Please do not send Social Security numbers, bank details or tax documents through the website form; it is only for your contact details and a short message.',
    ),
    array(
      'q' => 'How do I get started?',
      'a' => sprintf(
        'Schedule a Strategy Session through our contact page, call us at %s or email %s. Our office is at %s, %s, %s %s.',
        $c['phone'],
        $c['email'],
        $c['street'],
        $c['city'],
        $c['region'],
        $c['postalCode']
      ),
    ),
  );
}

/**
 * Four FAQs per service page (/services/{slug}/).
 * PENDING CLIENT APPROVAL, same rules as the home FAQs: answers only restate the
 * published scope of each service. No guarantees, savings promises, specific
 * entity/return types or IRS representation claims.
 */
function apex_service_faqs() {
  return array(
    'strategic-tax-planning' => array(
      array('q' => 'How is tax planning different from tax preparation?', 'a' => 'Tax preparation reports what already happened and files the return. Tax planning looks at the tax side of your decisions before you make them, so they are made with clearer information.'),
      array('q' => 'How often do we meet?', 'a' => 'Periodic check-ins are part of the service, along with conversations before major decisions. How often depends on your business and is agreed when the engagement starts.'),
      array('q' => 'Can tax planning guarantee I will pay less tax?', 'a' => 'No one can promise a specific result. Planning helps you understand the tax implications of your options ahead of time, so you can choose with the full picture.'),
      array('q' => 'Does planning connect with my tax return?', 'a' => 'Yes. Planning is coordinated with your accounting and tax preparation, so what is decided during the year is reflected when your return is prepared.'),
    ),
    'executive-accounting' => array(
      array('q' => 'What does Executive Accounting include?', 'a' => 'Bookkeeping and account reconciliation, organized and up-to-date financial records, and regular reviews of your financial activity with your team.'),
      array('q' => 'My books are behind. Can you help?', 'a' => 'Bring your current situation to a Strategy Session. We will review where your records stand and confirm what it would take to bring them up to date before any engagement begins.'),
      array('q' => 'Do you work with the accounting software I already use?', 'a' => 'Tell us what you use today. We will confirm in the Strategy Session how we would work with your current tools and team.'),
      array('q' => 'How does this help me make decisions?', 'a' => 'Accurate, current numbers are what most business decisions depend on. Organized records give you and your advisors a reliable view of how the business is doing.'),
    ),
    'business-advisory' => array(
      array('q' => 'What kinds of questions does business advisory cover?', 'a' => 'Decisions about growth, operations, profitability or change, and the financial questions that come with running a company day to day.'),
      array('q' => 'How is advisory different from accounting?', 'a' => 'Accounting records and reports what happened. Advisory uses that information to help you decide what to do next, with recommendations, priorities and next steps.'),
      array('q' => 'Is it a one-time project or ongoing?', 'a' => 'It can be either. Ongoing advisory as your needs evolve is part of the service; the format is agreed with you at the start.'),
      array('q' => 'What do you need from me to start?', 'a' => 'Your goals, your current challenges and access to the financial information behind your decisions. We will tell you exactly what is useful during the Strategy Session.'),
    ),
    'financial-reporting' => array(
      array('q' => 'Which reports will I receive?', 'a' => 'Periodic financial statements, focused on the information you actually use. Which reports, and how often, are agreed with you.'),
      array('q' => 'Will someone explain the numbers?', 'a' => 'Yes. Walk-throughs that explain what the numbers show are part of the service, so the reports are useful and not just delivered.'),
      array('q' => 'A lender or partner asked for statements. Can you help?', 'a' => 'Tell us what they have asked for during the Strategy Session, so the scope of your reporting can take it into account.'),
      array('q' => 'How do reports connect to planning?', 'a' => 'We help you connect what the reports show to your planning, so decisions about the year ahead are based on current numbers.'),
    ),
    'payroll-services' => array(
      array('q' => 'What does payroll support include?', 'a' => 'Payroll processing on an agreed schedule, payroll records coordinated with your accounting, and a point of contact for payroll questions.'),
      array('q' => 'Do you help with payroll-related filings?', 'a' => 'Support with payroll-related filings can be part of the service, within the scope we agree with you before the engagement begins.'),
      array('q' => 'How does payroll connect with my accounting?', 'a' => 'Payroll records are coordinated with your accounting, so wages and related costs are reflected consistently in your books and reports.'),
      array('q' => 'Who do I contact with a payroll question?', 'a' => 'You have a point of contact at APEX for payroll questions, so you always know who to reach.'),
    ),
    'irs-compliance' => array(
      array('q' => 'What does IRS Compliance support include?', 'a' => 'A review of your filing obligations and deadlines, organization of the records compliance depends on, and guidance on next steps when questions come up.'),
      array('q' => 'I received an IRS notice. What should I do?', 'a' => 'Read it carefully and note any response date it shows. Then bring it to a Strategy Session, and we will help you understand what it refers to and the next steps it may call for.'),
      array('q' => 'Can you help me get organized before deadlines?', 'a' => 'Yes. Reviewing your obligations and deadlines and organizing the records they depend on is the core of this service.'),
      array('q' => 'How does compliance connect with my tax returns?', 'a' => 'Compliance work is coordinated with your tax preparation and planning, so obligations are tracked in one place rather than handled separately.'),
    ),
    'corporate-tax-preparation' => array(
      array('q' => 'What information is needed to prepare my business return?', 'a' => 'Generally, the financial records for the year and information about the business entity. We will confirm the list for your situation and how to share it securely.'),
      array('q' => 'Will I review the return before it is filed?', 'a' => 'Yes. A review of the return with you before filing is part of the service.'),
      array('q' => 'When should I start?', 'a' => 'As soon as your year closes. Starting early leaves time to gather records, answer questions and review the return without rushing.'),
      array('q' => 'My books are not up to date. Can you still prepare the return?', 'a' => 'An accurate return depends on accurate records. We will review where your books stand and discuss the options, including our Executive Accounting service.'),
    ),
    'business-structure' => array(
      array('q' => 'What does a business structure review look at?', 'a' => 'Your current or planned structure, the entity options available and their general tax and accounting considerations, in light of your long-term goals.'),
      array('q' => 'When should I review my structure?', 'a' => 'When starting a business, and again when it changes: new partners, growth, a change in ownership or new plans. A structure that fit at the start may not fit later.'),
      array('q' => 'Do you handle the legal side of an entity change?', 'a' => 'Legal documents and filings involve your legal advisors. We coordinate with them where needed and support the accounting side of any change.'),
      array('q' => 'How does structure relate to taxes?', 'a' => 'How a business is structured can affect how it is taxed and accounted for. Reviewing structure together with your tax planning helps you decide with the full picture.'),
    ),
  );
}

function apex_faq_schema() {
  if (!APEX_FAQS_APPROVED) {
    return;
  }
  $service = function_exists('apex_current_service') ? apex_current_service() : null;
  $faqs    = is_front_page() ? apex_home_faqs() : ($service ? $service['faqs'] : array());
  if (!$faqs) {
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
    }, $faqs),
  );
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'apex_faq_schema');

/**
 * Data handed to React components.
 */
/*
 * ---------------------------------------------------------------------------
 * Proof and trust content (828 review, Priority 2).
 * Everything here comes from Laura / 828, confirmed. Until it arrives each item
 * renders as a marked placeholder (apex_pending()). NEVER put example numbers here.
 * ---------------------------------------------------------------------------
 */

/** Proof strip under the hero: label => confirmed value (null = pending). */
function apex_proof() {
  return array(
    array('label' => 'Years serving clients', 'value' => null),
    array('label' => 'Businesses served', 'value' => null),
    array('label' => 'Team professionals', 'value' => null),
    array('label' => 'Credentials', 'value' => null),
  );
}

/** Industries served (strings). */
function apex_industries() {
  return array();
}

/** 2–3 anonymized client results: situation, work, outcome. */
function apex_results() {
  return array();
}

/** Real reviews: quote, author (name or initials), source. */
function apex_reviews() {
  return array();
}

/** Professional associations, if any: name, url. */
function apex_associations() {
  return array();
}

/**
 * Marked placeholder for pending content. Visible on staging on purpose.
 */
function apex_pending($text, $tag = 'span') {
  return sprintf('<%1$s class="apex-pending">[%2$s]</%1$s>', tag_escape($tag), esc_html($text));
}

/**
 * Sister sites linked from the footer (828 review, Priority 4.6).
 * PENDING: swap to the production domains at launch (lem. is still on staging).
 */
function apex_network() {
  return array(
    array('label' => 'Laura Elena Martinez', 'url' => 'https://lem.828marketingsolutions.com/'),
    array('label' => 'Remake Business Advisors', 'url' => 'https://remakeadvisors.com/'),
  );
}

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
    'network'  => apex_network(),
    'legal'    => array(
      array('label' => 'Privacy Policy', 'url' => home_url('/privacy-policy/')),
      array('label' => 'Terms & Conditions', 'url' => home_url('/terms-and-conditions/')),
    ),
  );
}
