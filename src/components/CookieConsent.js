import React, { useEffect, useRef, useState } from "react"
import Icon from "./Icon"

// Cookie consent banner + preferences dialog.
//
// - Choice is stored in localStorage ("apex_cookie_consent") for 12 months.
// - Google Consent Mode v2: defaults are set to "denied" in header.php before any
//   tag loads; this component sends `gtag('consent', 'update', …)` with the choice,
//   so GA4 / GTM / ad pixels only use cookies the visitor accepted.
// - The footer "Cookie Settings" link fires `apex:cookie-settings` to reopen it.

const STORAGE_KEY = "apex_cookie_consent"
const VERSION = 1
const MAX_AGE = 1000 * 60 * 60 * 24 * 365

export function readConsent() {
  try {
    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY))
    if (saved && saved.v === VERSION && Date.now() - saved.date < MAX_AGE) return saved
  } catch {
    /* storage blocked: ask again */
  }
  return null
}

function gtag() {
  window.dataLayer = window.dataLayer || []
  window.dataLayer.push(arguments)
}

function applyConsent({ analytics, marketing }) {
  gtag("consent", "update", {
    analytics_storage: analytics ? "granted" : "denied",
    ad_storage: marketing ? "granted" : "denied",
    ad_user_data: marketing ? "granted" : "denied",
    ad_personalization: marketing ? "granted" : "denied",
  })
}

const CATEGORIES = [
  {
    key: "necessary",
    title: "Strictly necessary",
    text: "Required for the website to work, such as security, form protection and remembering these choices. They can’t be turned off.",
    locked: true,
  },
  {
    key: "analytics",
    title: "Analytics",
    text: "Help us understand how visitors use the site (pages viewed, buttons clicked) so we can improve it. Data is aggregated.",
  },
  {
    key: "marketing",
    title: "Marketing",
    text: "Used to measure advertising and show relevant ads on other platforms.",
  },
]

function Toggle({ id, checked, disabled, onChange, label }) {
  return (
    <button
      type="button"
      role="switch"
      id={id}
      aria-checked={checked}
      aria-label={label}
      disabled={disabled}
      onClick={() => onChange(!checked)}
      className={`relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-apex-sky disabled:cursor-not-allowed ${
        checked ? "bg-apex-navy" : "bg-apex-ink/20"
      } ${disabled ? "opacity-60" : ""}`}
    >
      <span className={`inline-block size-5 rounded-full bg-white shadow transition-transform duration-200 ${checked ? "translate-x-6" : "translate-x-1"}`} />
    </button>
  )
}

export default function CookieConsent({ site }) {
  const privacyUrl = `${site.form.privacyUrl}#cookies`
  const [banner, setBanner] = useState(false)
  const [prefs, setPrefs] = useState({ analytics: false, marketing: false })
  const dialogRef = useRef(null)

  useEffect(() => {
    const saved = readConsent()
    if (saved) setPrefs({ analytics: saved.analytics, marketing: saved.marketing })
    else setBanner(true)

    const open = () => {
      const current = readConsent()
      if (current) setPrefs({ analytics: current.analytics, marketing: current.marketing })
      openDialog()
    }
    window.addEventListener("apex:cookie-settings", open)
    return () => window.removeEventListener("apex:cookie-settings", open)
  }, [])

  const openDialog = () => {
    const dialog = dialogRef.current
    if (dialog && !dialog.open) {
      dialog.showModal()
      window.apexLenis?.stop()
    }
  }

  const closeDialog = () => {
    dialogRef.current?.close()
  }

  const save = choice => {
    const record = { v: VERSION, date: Date.now(), analytics: !!choice.analytics, marketing: !!choice.marketing }
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(record))
    } catch {
      /* private mode: applies for this page view only */
    }
    setPrefs({ analytics: record.analytics, marketing: record.marketing })
    applyConsent(record)
    window.dataLayer.push({ event: "cookie_consent_update", consent_analytics: record.analytics, consent_marketing: record.marketing })
    setBanner(false)
    closeDialog()
  }

  const acceptAll = () => save({ analytics: true, marketing: true })
  const rejectAll = () => save({ analytics: false, marketing: false })

  return (
    <>
      {banner && (
        <section
          aria-label="Cookie consent"
          className="motion-safe-only fixed inset-x-3 bottom-3 z-80 animate-rise rounded-2xl bg-apex-night p-5 text-white shadow-2xl shadow-black/40 ring-1 ring-white/10 sm:inset-x-auto sm:bottom-6 sm:left-6 sm:max-w-md sm:p-6"
        >
          <div className="flex items-start gap-4">
            <span className="hidden size-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-apex-mist sm:flex">
              <Icon name="shield-check" className="size-6" />
            </span>
            <div>
              <h2 className="font-display text-lg font-medium">We value your privacy</h2>
              <p className="mt-2 text-sm leading-relaxed text-white/75">
                We use cookies to keep this site secure and, with your permission, to understand how it’s used and measure our marketing.{" "}
                <a href={privacyUrl} className="font-medium text-white underline hover:text-apex-mist">
                  Learn more
                </a>
              </p>
            </div>
          </div>
          <div className="mt-5 grid grid-cols-2 gap-2.5">
            <button type="button" onClick={rejectAll} className="apex-btn min-h-11 border border-white/30 px-4 text-sm text-white hover:bg-white/10">
              Necessary only
            </button>
            <button type="button" onClick={acceptAll} className="apex-btn min-h-11 bg-white px-4 text-sm text-apex-navy hover:bg-apex-mist">
              Accept all
            </button>
            <button type="button" onClick={openDialog} className="col-span-2 py-1 text-center text-sm font-medium text-white/80 underline underline-offset-4 hover:text-white">
              Customize preferences
            </button>
          </div>
        </section>
      )}

      <dialog
        ref={dialogRef}
        aria-labelledby="cookie-dialog-title"
        onClose={() => window.apexLenis?.start()}
        onClick={e => e.target === dialogRef.current && closeDialog()}
        className="m-auto w-[calc(100%-1.5rem)] max-w-xl rounded-2xl bg-white p-0 text-apex-ink shadow-2xl backdrop:bg-apex-night/60 backdrop:backdrop-blur-sm"
        data-lenis-prevent
      >
        <div className="flex max-h-[85dvh] flex-col">
          <div className="flex items-start justify-between gap-4 border-b border-apex-line px-6 py-5 sm:px-8">
            <div>
              <h2 id="cookie-dialog-title" className="font-display text-xl font-medium text-apex-navy">
                Cookie settings
              </h2>
              <p className="mt-1 text-sm text-apex-ink/65">Choose which cookies we can use. You can change this anytime from the footer.</p>
            </div>
            <button type="button" onClick={closeDialog} className="-mr-2 rounded-md p-2 text-apex-ink/60 hover:text-apex-navy" aria-label="Close cookie settings">
              <Icon name="x" className="size-5" />
            </button>
          </div>

          <ul className="divide-y divide-apex-line overflow-y-auto px-6 sm:px-8">
            {CATEGORIES.map(cat => (
              <li key={cat.key} className="flex items-start justify-between gap-6 py-5">
                <div>
                  <label htmlFor={`cookie-${cat.key}`} className="font-display font-medium text-apex-navy">
                    {cat.title}
                    {cat.locked && <span className="ml-2 rounded-full bg-apex-cloud px-2 py-0.5 text-xs font-normal text-apex-sky">Always on</span>}
                  </label>
                  <p className="mt-1 text-sm leading-relaxed text-apex-ink/65">{cat.text}</p>
                </div>
                <Toggle
                  id={`cookie-${cat.key}`}
                  label={cat.title}
                  checked={cat.locked ? true : prefs[cat.key]}
                  disabled={cat.locked}
                  onChange={value => setPrefs(p => ({ ...p, [cat.key]: value }))}
                />
              </li>
            ))}
          </ul>

          <div className="flex flex-col-reverse gap-2.5 border-t border-apex-line px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
            <a href={privacyUrl} className="text-center text-sm text-apex-ink/65 underline underline-offset-4 hover:text-apex-navy sm:text-left">
              Privacy Policy
            </a>
            <div className="flex flex-col gap-2.5 sm:flex-row">
              <button type="button" onClick={() => save(prefs)} className="apex-btn-outline min-h-11 px-5 text-sm">
                Save preferences
              </button>
              <button type="button" onClick={acceptAll} className="apex-btn-primary min-h-11 px-5 text-sm">
                Accept all
              </button>
            </div>
          </div>
        </div>
      </dialog>
    </>
  )
}
