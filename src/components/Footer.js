import React, { useEffect, useRef } from "react"
import Icon from "./Icon"
import { scanReveal } from "../scripts/reveal"

function FooterHeading({ children }) {
  return <h2 className="apex-eyebrow mb-5 text-apex-gold">{children}</h2>
}

const linkClass = "text-[15px] text-white/75 transition-colors hover:text-white"

export default function Footer({ site }) {
  const { nav, services, contact, logo, homeUrl, legal, network = [] } = site
  const year = new Date().getFullYear()
  const ref = useRef(null)

  // Rendered after the initial page scan, so register our reveal items once mounted.
  useEffect(() => {
    if (ref.current) scanReveal(ref.current)
  }, [])

  return (
    <footer ref={ref} className="bg-apex-night text-white">
      <div className="apex-container grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-12 lg:gap-8" data-reveal-stagger="100">
        <div className="lg:col-span-4" data-reveal>
          <a href={homeUrl} aria-label={`${contact.name} — Home`} className="inline-block">
            <img src={logo.light} alt="Apex Accounting & Advisory" width="1024" height="163" loading="lazy" className="h-8 w-auto" />
          </a>
          <p className="mt-6 max-w-sm text-sm leading-relaxed text-white/70">
            Strategic tax planning, accounting, compliance and business advisory for business owners who want a clear view of
            their financial structure.
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <a href={contact.strategyUrl} className="apex-cta apex-btn bg-white text-apex-navy" data-track="cta_strategy_session">
              <span className="apex-cta__label"><span className="apex-cta__text">
                <Icon name="calendar" className="size-5" />
                Schedule a Strategy Session
              </span></span>
            </a>
            <a
              href={contact.portalUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="apex-btn-outline-light"
              data-track="portal_click"
            >
              <Icon name="user" className="size-5" />
              Client Portal
              <span className="sr-only">(opens in a new tab)</span>
            </a>
          </div>
        </div>

        <nav aria-label="Footer" className="lg:col-span-2" data-reveal>
          <FooterHeading>Company</FooterHeading>
          <ul className="space-y-3">
            {nav.map(item => (
              <li key={item.url}>
                <a href={item.url} className={linkClass}>
                  {item.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        <nav aria-label="Services" className="lg:col-span-3" data-reveal>
          <FooterHeading>Services</FooterHeading>
          <ul className="space-y-3">
            {services.map(service => (
              <li key={service.url}>
                <a href={service.url} className={linkClass}>
                  {service.title}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        <div className="lg:col-span-3" data-reveal>
          <FooterHeading>Contact</FooterHeading>
          <address className="space-y-4 not-italic">
            <a href={contact.phoneHref} className={`flex items-start gap-3 ${linkClass}`} data-track="click_to_call">
              <Icon name="phone" className="mt-0.5 size-5 shrink-0 text-apex-mist" />
              {contact.phone}
            </a>
            <a href={`mailto:${contact.email}`} className={`flex items-start gap-3 break-all ${linkClass}`} data-track="email_click">
              <Icon name="mail" className="mt-0.5 size-5 shrink-0 text-apex-mist" />
              {contact.email}
            </a>
            <a href={contact.mapsUrl} target="_blank" rel="noopener noreferrer" className={`flex items-start gap-3 ${linkClass}`}>
              <Icon name="map-pin" className="mt-0.5 size-5 shrink-0 text-apex-mist" />
              <span>
                {contact.street}
                <br />
                {contact.city}, {contact.region} {contact.postalCode}
                <span className="sr-only"> (opens Google Maps in a new tab)</span>
              </span>
            </a>
          </address>
        </div>
      </div>

      {network.length > 0 && (
        <div className="border-t border-white/10">
          <nav aria-label="Related firms" className="apex-container flex flex-wrap items-center gap-x-6 gap-y-2 py-5 text-sm text-white/60">
            <span className="text-white/45">Also from our network:</span>
            {network.map(site => (
              <a key={site.url} href={site.url} target="_blank" rel="noopener" className="inline-flex items-center gap-1.5 transition-colors hover:text-white">
                {site.label}
                <Icon name="external-link" className="size-3.5" />
                <span className="sr-only">(opens in a new tab)</span>
              </a>
            ))}
          </nav>
        </div>
      )}

      <div className="border-t border-white/10">
        <div className="apex-container flex flex-col gap-3 py-6 text-sm text-white/60 sm:flex-row sm:items-center sm:justify-between">
          <p>
            © {year} {contact.name} All rights reserved.
          </p>
          <ul className="flex flex-wrap gap-x-6 gap-y-2">
            {legal.map(item => (
              <li key={item.url}>
                <a href={item.url} className="transition-colors hover:text-white">
                  {item.label}
                </a>
              </li>
            ))}
            <li>
              <button type="button" onClick={() => window.dispatchEvent(new CustomEvent("apex:cookie-settings"))} className="transition-colors hover:text-white">
                Cookie Settings
              </button>
            </li>
          </ul>
        </div>
      </div>
    </footer>
  )
}
