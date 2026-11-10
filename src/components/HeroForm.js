import React, { useEffect, useRef, useState } from "react"
import Icon from "./Icon"
import { EMPTY, validate } from "./ContactForm"
import { track } from "../scripts/tracking"

// Compact, translucent version of the strategy-session form for the home hero.
// Same endpoint, validation, honeypot and tracking as ContactForm — only the
// essential fields (optional ones can be shared later with the team).

const FORM_NAME = "strategy_session_hero"

const inputClass = invalid =>
  `block w-full rounded-lg border bg-white/10 px-3.5 py-2.5 text-sm text-white placeholder:text-white/45 transition-colors focus:border-white focus:bg-white/15 focus:ring-2 focus:ring-white/25 focus:outline-none ${
    invalid ? "border-red-300" : "border-white/25 hover:border-white/45"
  }`

function Field({ id, label, error, children, className = "" }) {
  return (
    <div className={className}>
      <label htmlFor={id} className="mb-1.5 block text-xs font-medium tracking-wide text-white/85">
        {label}
      </label>
      {children}
      {error && (
        <p id={`${id}-error`} className="mt-1.5 flex items-center gap-1.5 text-xs text-red-200">
          <Icon name="alert-circle" className="size-3.5 shrink-0" strokeWidth={2} />
          {error}
        </p>
      )}
    </div>
  )
}

export default function HeroForm({ site }) {
  const { form, contact } = site
  const [values, setValues] = useState({ ...EMPTY, state: "" })
  const [errors, setErrors] = useState({})
  const [status, setStatus] = useState("idle")
  const [serverMessage, setServerMessage] = useState("")
  const startedAt = useRef(Date.now())
  const started = useRef(false)
  const formRef = useRef(null)
  const successRef = useRef(null)

  useEffect(() => {
    if (status === "success" && successRef.current) successRef.current.focus()
  }, [status])

  const id = name => `hero-${name}`
  const errorProps = name => ({
    "aria-invalid": !!errors[name],
    "aria-describedby": errors[name] ? `${id(name)}-error` : undefined,
  })

  const onChange = e => {
    const { name, type, checked, value } = e.target
    setValues(v => ({ ...v, [name]: type === "checkbox" ? checked : value }))
    if (errors[name]) setErrors(({ [name]: _removed, ...rest }) => rest)
  }

  const onFocus = () => {
    if (!started.current) {
      started.current = true
      track("form_start", { form_name: FORM_NAME })
    }
  }

  const focusFirstError = errs => {
    const first = Object.keys(errs)[0]
    formRef.current?.querySelector(`[name="${first}"]`)?.focus()
  }

  const onSubmit = async e => {
    e.preventDefault()
    const clientErrors = validate(values)
    if (Object.keys(clientErrors).length) {
      setErrors(clientErrors)
      setServerMessage("Please review the highlighted fields.")
      setStatus("error")
      focusFirstError(clientErrors)
      return
    }

    setStatus("submitting")
    setServerMessage("")
    try {
      const res = await fetch(form.endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...values, elapsed: Date.now() - startedAt.current, source_url: window.location.href.split("#")[0] }),
      })
      const data = await res.json().catch(() => ({}))
      if (res.ok && data.ok) {
        setStatus("success")
        track("form_submit", { form_name: FORM_NAME, service: values.service || undefined })
        return
      }
      if (data.errors) {
        setErrors(data.errors)
        focusFirstError(data.errors)
      }
      setServerMessage(data.message || `Something went wrong. Please try again or call us at ${contact.phone}.`)
      setStatus("error")
    } catch {
      setServerMessage(`We couldn’t reach our server. Please call us at ${contact.phone}.`)
      setStatus("error")
    }
  }

  if (status === "success") {
    return (
      <div ref={successRef} tabIndex={-1} role="status" className="py-10 text-center outline-none">
        <span className="mx-auto flex size-14 items-center justify-center rounded-full bg-white text-apex-navy">
          <Icon name="check" className="size-7" strokeWidth={2} />
        </span>
        <h2 className="mt-5 font-display text-2xl font-medium">Thank you, {values.first_name}.</h2>
        <p className="mx-auto mt-3 max-w-xs text-sm leading-relaxed text-white/80">
          We’ve received your request. A member of our team will contact you to schedule your strategy session.
        </p>
        <a href={contact.phoneHref} className="mt-6 inline-flex items-center gap-2 text-sm font-medium text-white underline" data-track="click_to_call">
          <Icon name="phone" className="size-4" />
          {contact.phone}
        </a>
      </div>
    )
  }

  const submitting = status === "submitting"

  return (
    <form ref={formRef} onSubmit={onSubmit} onFocus={onFocus} noValidate aria-labelledby="hero-form-title">
      <p className="apex-eyebrow text-apex-mist">Private &amp; no obligation</p>
      <h2 id="hero-form-title" className="mt-2 font-display text-2xl font-medium">
        Request a Strategy Session
      </h2>

      <div role="alert" aria-live="assertive">
        {status === "error" && serverMessage && (
          <p className="mt-4 flex items-start gap-2 rounded-lg border border-red-300/40 bg-red-500/15 p-3 text-xs text-red-100">
            <Icon name="alert-circle" className="size-4 shrink-0" strokeWidth={2} />
            {serverMessage}
          </p>
        )}
      </div>

      <div className="mt-5 grid grid-cols-2 gap-3.5">
        <Field id={id("first_name")} label="First name *" error={errors.first_name}>
          <input id={id("first_name")} name="first_name" type="text" autoComplete="given-name" value={values.first_name} onChange={onChange} {...errorProps("first_name")} className={inputClass(errors.first_name)} />
        </Field>
        <Field id={id("last_name")} label="Last name *" error={errors.last_name}>
          <input id={id("last_name")} name="last_name" type="text" autoComplete="family-name" value={values.last_name} onChange={onChange} {...errorProps("last_name")} className={inputClass(errors.last_name)} />
        </Field>
        <Field id={id("email")} label="Email *" error={errors.email} className="col-span-2 sm:col-span-1">
          <input id={id("email")} name="email" type="email" inputMode="email" autoComplete="email" value={values.email} onChange={onChange} {...errorProps("email")} className={inputClass(errors.email)} />
        </Field>
        <Field id={id("phone")} label="Phone *" error={errors.phone} className="col-span-2 sm:col-span-1">
          <input id={id("phone")} name="phone" type="tel" inputMode="tel" autoComplete="tel" placeholder="(949) 555-0123" value={values.phone} onChange={onChange} {...errorProps("phone")} className={inputClass(errors.phone)} />
        </Field>
        <Field id={id("timeframe")} label="When would you like to begin? *" error={errors.timeframe} className="col-span-2 sm:col-span-1">
          <select id={id("timeframe")} name="timeframe" value={values.timeframe} onChange={onChange} {...errorProps("timeframe")} className={inputClass(errors.timeframe)}>
            <option value="" className="text-apex-ink">Select…</option>
            {Object.entries(form.timeframes).map(([value, label]) => (
              <option key={value} value={value} className="text-apex-ink">{label}</option>
            ))}
          </select>
        </Field>
        <Field id={id("service")} label="Service of interest" className="col-span-2 sm:col-span-1">
          <select id={id("service")} name="service" value={values.service} onChange={onChange} className={inputClass(false)}>
            <option value="" className="text-apex-ink">Not sure yet</option>
            {Object.entries(form.services).map(([value, label]) => (
              <option key={value} value={value} className="text-apex-ink">{label}</option>
            ))}
          </select>
        </Field>
      </div>

      {/* Honeypot */}
      <div className="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
        <label htmlFor="hero-website">Website</label>
        <input id="hero-website" name="website" type="text" tabIndex={-1} autoComplete="off" value={values.website} onChange={onChange} />
      </div>

      <div className="mt-4">
        <label className="flex cursor-pointer items-start gap-2.5 text-xs leading-relaxed text-white/75">
          <input type="checkbox" name="consent" checked={values.consent} onChange={onChange} {...errorProps("consent")} id={id("consent")} className="mt-0.5 size-4 shrink-0 accent-apex-sky" />
          <span>
            I agree to the{" "}
            <a href={form.privacyUrl} target="_blank" rel="noopener" className="font-medium text-white underline hover:text-apex-mist">Privacy Policy</a>{" "}
            and{" "}
            <a href={form.termsUrl} target="_blank" rel="noopener" className="font-medium text-white underline hover:text-apex-mist">Terms &amp; Conditions</a>. *
          </span>
        </label>
        {errors.consent && (
          <p id={`${id("consent")}-error`} className="mt-1.5 flex items-center gap-1.5 text-xs text-red-200">
            <Icon name="alert-circle" className="size-3.5 shrink-0" strokeWidth={2} />
            {errors.consent}
          </p>
        )}
      </div>

      <button type="submit" disabled={submitting} className="apex-btn mt-5 w-full bg-white text-apex-navy hover:bg-apex-mist disabled:cursor-wait disabled:opacity-70">
        {submitting ? (
          <>
            <span className="size-5 animate-spin rounded-full border-2 border-apex-navy/30 border-t-apex-navy" aria-hidden="true" />
            Sending…
          </>
        ) : (
          <>
            <Icon name="calendar" className="size-5" />
            Request My Strategy Session
          </>
        )}
      </button>

      <p className="mt-3 flex items-center justify-center gap-1.5 text-[11px] text-white/55">
        <Icon name="lock" className="size-3.5 shrink-0" />
        Please don’t send SSNs, bank details or tax documents here.
      </p>
    </form>
  )
}
