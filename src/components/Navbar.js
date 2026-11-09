import React, { useEffect, useId, useRef, useState } from "react"
import Icon from "./Icon"

const normalize = url => {
  try {
    return new URL(url, window.location.origin).pathname.replace(/\/+$/, "") || "/"
  } catch {
    return url
  }
}

function isActive(item, currentPath) {
  const itemPath = normalize(item.url)
  if (itemPath === "/") return currentPath === "/"
  return currentPath === itemPath || currentPath.startsWith(itemPath + "/")
}

function ServicesMenu({ item, services, active }) {
  const [open, setOpen] = useState(false)
  const wrapperRef = useRef(null)
  const closeTimer = useRef(null)
  const menuId = useId()

  useEffect(() => {
    if (!open) return
    const onKey = e => e.key === "Escape" && setOpen(false)
    const onClick = e => wrapperRef.current && !wrapperRef.current.contains(e.target) && setOpen(false)
    document.addEventListener("keydown", onKey)
    document.addEventListener("mousedown", onClick)
    return () => {
      document.removeEventListener("keydown", onKey)
      document.removeEventListener("mousedown", onClick)
    }
  }, [open])

  const show = () => {
    clearTimeout(closeTimer.current)
    setOpen(true)
  }
  const hide = () => {
    closeTimer.current = setTimeout(() => setOpen(false), 120)
  }

  return (
    <li
      ref={wrapperRef}
      className="relative flex items-center"
      onMouseEnter={show}
      onMouseLeave={hide}
      onBlur={e => !e.currentTarget.contains(e.relatedTarget) && setOpen(false)}
    >
      <a href={item.url} className={linkClass(active)} aria-current={active ? "page" : undefined}>
        {item.label}
      </a>
      <button
        type="button"
        className="ml-1 rounded p-1 text-apex-ink/70 hover:text-apex-navy"
        aria-expanded={open}
        aria-controls={menuId}
        aria-label="Show services"
        onClick={() => setOpen(o => !o)}
      >
        <Icon name="chevron-down" className={`size-4 transition-transform ${open ? "rotate-180" : ""}`} strokeWidth={2} />
      </button>

      <div
        id={menuId}
        className={`absolute left-1/2 top-full z-50 w-[560px] -translate-x-1/2 pt-4 ${open ? "block" : "hidden"}`}
      >
        <div className="rounded-lg border border-apex-line bg-white p-3 shadow-xl shadow-apex-night/10">
          <ul className="grid grid-cols-2 gap-1">
            {services.map(service => (
              <li key={service.url}>
                <a
                  href={service.url}
                  className="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-apex-ink/80 transition-colors hover:bg-apex-cloud hover:text-apex-navy"
                >
                  <Icon name={service.icon} className="size-5 shrink-0 text-apex-sky" />
                  {service.title}
                </a>
              </li>
            ))}
          </ul>
          <a
            href={item.url}
            className="mt-2 flex items-center justify-between rounded-md bg-apex-cloud px-4 py-3 font-display text-sm font-medium text-apex-navy hover:bg-apex-mist"
          >
            Explore all services
            <Icon name="arrow-right" className="size-4" strokeWidth={2} />
          </a>
        </div>
      </div>
    </li>
  )
}

const linkClass = active =>
  `relative py-2 font-display text-[15px] transition-colors after:absolute after:inset-x-0 after:-bottom-0.5 after:h-0.5 after:rounded-full after:bg-apex-navy after:transition-transform ${
    active ? "font-medium text-apex-navy after:scale-x-100" : "text-apex-ink/75 hover:text-apex-navy after:scale-x-0"
  }`

function MobileMenu({ site, currentPath, onClose }) {
  const [servicesOpen, setServicesOpen] = useState(false)
  const { nav, services, contact } = site

  return (
    <div id="apex-mobile-menu" className="max-h-[calc(100dvh-5rem)] overflow-y-auto border-t border-apex-line bg-white xl:hidden">
      <nav aria-label="Mobile" className="apex-container py-4">
        <ul className="divide-y divide-apex-line">
          {nav.map(item => {
            const active = isActive(item, currentPath)
            if (item.children === "services") {
              return (
                <li key={item.url}>
                  <div className="flex items-center justify-between">
                    <a href={item.url} className={`block py-3.5 font-display text-base ${active ? "font-medium text-apex-navy" : "text-apex-ink"}`}>
                      {item.label}
                    </a>
                    <button
                      type="button"
                      className="-mr-2 p-3 text-apex-ink/70"
                      aria-expanded={servicesOpen}
                      aria-label="Show services"
                      onClick={() => setServicesOpen(o => !o)}
                    >
                      <Icon name="chevron-down" className={`size-5 transition-transform ${servicesOpen ? "rotate-180" : ""}`} strokeWidth={2} />
                    </button>
                  </div>
                  {servicesOpen && (
                    <ul className="grid gap-1 pb-3 sm:grid-cols-2">
                      {services.map(service => (
                        <li key={service.url}>
                          <a href={service.url} onClick={onClose} className="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-apex-ink/80 hover:bg-apex-cloud">
                            <Icon name={service.icon} className="size-5 shrink-0 text-apex-sky" />
                            {service.title}
                          </a>
                        </li>
                      ))}
                    </ul>
                  )}
                </li>
              )
            }
            return (
              <li key={item.url}>
                <a
                  href={item.url}
                  aria-current={active ? "page" : undefined}
                  className={`block py-3.5 font-display text-base ${active ? "font-medium text-apex-navy" : "text-apex-ink"}`}
                >
                  {item.label}
                </a>
              </li>
            )
          })}
        </ul>

        <div className="mt-4 grid gap-3 border-t border-apex-line pt-5">
          <a href={contact.strategyUrl} className="apex-btn-primary w-full" data-track="cta_strategy_session">
            <Icon name="calendar" className="size-5" />
            Schedule a Strategy Session
          </a>
          <a
            href={contact.portalUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="apex-btn-outline w-full"
            data-track="portal_click"
          >
            <Icon name="user" className="size-5" />
            Client Portal
          </a>
          <a href={contact.phoneHref} className="py-2 text-center text-sm text-apex-ink/70" data-track="click_to_call">
            Call us: <span className="font-medium text-apex-navy">{contact.phone}</span>
          </a>
        </div>
      </nav>
    </div>
  )
}

export default function Navbar({ site }) {
  const [mobileOpen, setMobileOpen] = useState(false)
  const [scrolled, setScrolled] = useState(false)
  const currentPath = normalize(window.location.href)
  const { nav, services, contact, logo, homeUrl } = site

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8)
    onScroll()
    window.addEventListener("scroll", onScroll, { passive: true })
    return () => window.removeEventListener("scroll", onScroll)
  }, [])

  useEffect(() => {
    if (!mobileOpen) return
    const onKey = e => e.key === "Escape" && setMobileOpen(false)
    const onResize = () => window.innerWidth >= 1280 && setMobileOpen(false)
    document.addEventListener("keydown", onKey)
    window.addEventListener("resize", onResize)
    document.body.style.overflow = "hidden"
    return () => {
      document.removeEventListener("keydown", onKey)
      window.removeEventListener("resize", onResize)
      document.body.style.overflow = ""
    }
  }, [mobileOpen])

  return (
    <header className={`bg-white/95 backdrop-blur transition-shadow ${scrolled || mobileOpen ? "shadow-md shadow-apex-night/5" : ""}`}>
      <div className="apex-container flex h-20 items-center justify-between gap-6">
        <a href={homeUrl} className="shrink-0" aria-label="Apex Accounting Inc. — Home">
          <img src={logo.dark} alt="Apex Accounting & Advisory" width="1024" height="163" className="h-7 w-auto sm:h-8" />
        </a>

        <nav aria-label="Main" className="hidden xl:block">
          <ul className="flex items-center gap-8">
            {nav.map(item =>
              item.children === "services" ? (
                <ServicesMenu key={item.url} item={item} services={services} active={isActive(item, currentPath)} />
              ) : (
                <li key={item.url}>
                  <a href={item.url} className={linkClass(isActive(item, currentPath))} aria-current={isActive(item, currentPath) ? "page" : undefined}>
                    {item.label}
                  </a>
                </li>
              )
            )}
          </ul>
        </nav>

        <div className="flex items-center gap-3 xl:gap-6">
          <span className="hidden h-6 w-px bg-apex-ink/20 xl:block" aria-hidden="true" />
          <a
            href={contact.portalUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="hidden items-center gap-2 font-display text-[15px] text-apex-ink/75 transition-colors hover:text-apex-navy xl:flex"
            data-track="portal_click"
          >
            <Icon name="user" className="size-5" />
            Client Portal
            <span className="sr-only">(opens in a new tab)</span>
          </a>
          <a href={contact.strategyUrl} className="apex-btn-primary hidden md:inline-flex" data-track="cta_strategy_session">
            <Icon name="calendar" className="size-5" />
            Schedule a Strategy Session
          </a>
          <button
            type="button"
            className="-mr-2 rounded-md p-2 text-apex-ink xl:hidden"
            aria-expanded={mobileOpen}
            aria-controls="apex-mobile-menu"
            aria-label={mobileOpen ? "Close menu" : "Open menu"}
            onClick={() => setMobileOpen(o => !o)}
          >
            <Icon name={mobileOpen ? "x" : "menu"} className="size-7" />
          </button>
        </div>
      </div>

      {mobileOpen && <MobileMenu site={site} currentPath={currentPath} onClose={() => setMobileOpen(false)} />}
    </header>
  )
}
