// Conversion events (brief §7): pushed to window.dataLayer so GA4 / GTM can pick
// them up once installed. Nothing personal is ever sent — only event names and URLs.
//
//  - Any element with data-track="event_name" fires that event on click
//    (cta_strategy_session, portal_click, click_to_call, email_click, directions_click…)
//  - The contact form calls track("form_start") / track("form_submit")

export function track(event, params = {}) {
  window.dataLayer = window.dataLayer || []
  window.dataLayer.push({ event, ...params })
}

export function initTracking() {
  document.addEventListener("click", e => {
    const el = e.target.closest("[data-track]")
    if (!el) return
    track(el.dataset.track, {
      link_url: el.getAttribute("href") || undefined,
      link_text: (el.textContent || "").trim().replace(/\s+/g, " ").slice(0, 80),
      page_path: window.location.pathname,
    })
  })
}
