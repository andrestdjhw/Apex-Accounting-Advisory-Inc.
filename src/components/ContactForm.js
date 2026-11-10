import React, { useEffect, useRef, useState } from "react"
import Icon from "./Icon"
import { track } from "../scripts/tracking"

const US_STATES = [
  "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware",
  "District of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas",
  "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi",
  "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York",
  "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island",
  "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington",
  "West Virginia", "Wisconsin", "Wyoming",
]

const EMPTY = {
  first_name: "",
  last_name: "",
  company: "",
  email: "",
  phone: "",
  city: "",
  state: "California",
  timeframe: "",
  service: "",
  message: "",
  consent: false,
  website: "", // honeypot
}

function validate(values) {
  const errors = {}
  if (!values.first_name.trim()) errors.first_name = "Please enter your first name."
  if (!values.last_name.trim()) errors.last_name = "Please enter your last name."
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(values.email.trim())) errors.email = "Please enter a valid email address."
  if (values.phone.replace(/\D/g, "").length < 10) errors.phone = "Please enter a valid phone number, including area code."
  if (!values.timeframe) errors.timeframe = "Please choose when you’d like to begin."
  if (values.message.length > 1000) errors.message = "Please keep your message under 1,000 characters."
  if (!values.consent) errors.consent = "Please accept the Privacy Policy and Terms & Conditions."
  return errors
}

const inputClass = invalid =>
  `block w-full rounded-lg border bg-white px-4 py-3 text-apex-ink placeholder:text-apex-ink/40 transition-colors focus:border-apex-sky focus:ring-2 focus:ring-apex-sky/20 focus:outline-none ${
    invalid ? "border-red-600" : "border-apex-ink/20 hover:border-apex-ink/40"
  }`

function Field({ id, label, required, error, hint, children, className = "" }) {
  return (
    <div className={className}>
      <label htmlFor={id} className="mb-2 block text-sm font-medium text-apex-navy">
        {label}
        {required ? (
          <span className="text-apex-sky" aria-hidden="true">
            {" "}
            *
          </span>
        ) : (
          <span className="font-normal text-apex-ink/50"> (optional)</span>
        )}
      </label>
      {children}
      {hint && !error && (
        <p id={`${id}-hint`} className="mt-2 text-xs text-apex-ink/55">
          {hint}
        </p>
      )}
      {error && (
        <p id={`${id}-error`} className="mt-2 flex items-center gap-1.5 text-sm text-red-700">
          <Icon name="alert-circle" className="size-4 shrink-0" strokeWidth={2} />
          {error}
        </p>
      )}
    </div>
  )
}

export default function ContactForm({ site }) {
  const { form, contact } = site
  const [values, setValues] = useState(() => {
    const service = new URLSearchParams(window.location.search).get("service")
    return { ...EMPTY, service: service && form.services[service] ? service : "" }
  })
  const [errors, setErrors] = useState({})
  const [status, setStatus] = useState("idle") // idle | submitting | success | error
  const [serverMessage, setServerMessage] = useState("")
  const startedAt = useRef(Date.now())
  const started = useRef(false)
  const formRef = useRef(null)
  const successRef = useRef(null)

  useEffect(() => {
    if (status === "success" && successRef.current) successRef.current.focus()
  }, [status])

  const describedBy = (id, hint) => [errors[id] && `${id}-error`, hint && !errors[id] && `${id}-hint`].filter(Boolean).join(" ") || undefined

  const onChange = e => {
    const { name, type, checked, value } = e.target
    setValues(v => ({ ...v, [name]: type === "checkbox" ? checked : value }))
    if (errors[name]) setErrors(({ [name]: _removed, ...rest }) => rest)
  }

  const onFocus = () => {
    if (!started.current) {
      started.current = true
      track("form_start", { form_name: "strategy_session" })
    }
  }

  const onBlur = e => {
    const { name } = e.target
    const fieldError = validate(values)[name]
    if (fieldError && values[name] !== "" && values[name] !== false) {
      setErrors(prev => ({ ...prev, [name]: fieldError }))
    }
  }

  const focusFirstError = errs => {
    const first = Object.keys(errs)[0]
    const el = first && formRef.current?.querySelector(`[name="${first}"]`)
    if (el) el.focus()
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
        body: JSON.stringify({
          ...values,
          elapsed: Date.now() - startedAt.current,
          source_url: window.location.href.split("#")[0],
        }),
      })
      const data = await res.json().catch(() => ({}))

      if (res.ok && data.ok) {
        setStatus("success")
        track("form_submit", { form_name: "strategy_session", service: values.service || undefined })
        return
      }

      if (data.errors) {
        setErrors(data.errors)
        focusFirstError(data.errors)
      }
      setServerMessage(data.message || `Something went wrong. Please try again or call us at ${contact.phone}.`)
      setStatus("error")
    } catch {
      setServerMessage(`We couldn’t reach our server. Please check your connection or call us at ${contact.phone}.`)
      setStatus("error")
    }
  }

  if (status === "success") {
    return (
      <div ref={successRef} tabIndex={-1} role="status" className="rounded-xl bg-apex-cloud p-8 text-center outline-none">
        <span className="mx-auto flex size-16 items-center justify-center rounded-full bg-apex-navy text-white">
          <Icon name="check" className="size-8" strokeWidth={2} />
        </span>
        <h3 className="mt-6 font-display text-2xl font-medium text-apex-navy">Thank you, {values.first_name}.</h3>
        <p className="mx-auto mt-3 max-w-md leading-relaxed text-apex-ink/75">
          We’ve received your request. A member of our team will contact you to schedule your private strategy session.
        </p>
        <p className="mt-6 text-sm text-apex-ink/70">
          Need to reach us sooner? Call{" "}
          <a href={contact.phoneHref} className="font-medium text-apex-navy underline" data-track="click_to_call">
            {contact.phone}
          </a>
          .
        </p>
      </div>
    )
  }

  const submitting = status === "submitting"

  return (
    <form ref={formRef} onSubmit={onSubmit} onFocus={onFocus} noValidate className="space-y-6" aria-describedby="form-status">
      <div id="form-status" role="alert" aria-live="assertive">
        {status === "error" && serverMessage && (
          <p className="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <Icon name="alert-circle" className="mt-0.5 size-5 shrink-0" strokeWidth={2} />
            {serverMessage}
          </p>
        )}
      </div>

      <div className="grid gap-6 sm:grid-cols-2">
        <Field id="first_name" label="First name" required error={errors.first_name}>
          <input id="first_name" name="first_name" type="text" autoComplete="given-name" value={values.first_name} onChange={onChange} onBlur={onBlur} aria-invalid={!!errors.first_name} aria-describedby={describedBy("first_name")} className={inputClass(errors.first_name)} />
        </Field>
        <Field id="last_name" label="Last name" required error={errors.last_name}>
          <input id="last_name" name="last_name" type="text" autoComplete="family-name" value={values.last_name} onChange={onChange} onBlur={onBlur} aria-invalid={!!errors.last_name} aria-describedby={describedBy("last_name")} className={inputClass(errors.last_name)} />
        </Field>
        <Field id="email" label="Email" required error={errors.email}>
          <input id="email" name="email" type="email" autoComplete="email" inputMode="email" value={values.email} onChange={onChange} onBlur={onBlur} aria-invalid={!!errors.email} aria-describedby={describedBy("email")} className={inputClass(errors.email)} />
        </Field>
        <Field id="phone" label="Phone" required error={errors.phone}>
          <input id="phone" name="phone" type="tel" autoComplete="tel" inputMode="tel" placeholder="(949) 555-0123" value={values.phone} onChange={onChange} onBlur={onBlur} aria-invalid={!!errors.phone} aria-describedby={describedBy("phone")} className={inputClass(errors.phone)} />
        </Field>
        <Field id="company" label="Company name" error={errors.company} className="sm:col-span-2">
          <input id="company" name="company" type="text" autoComplete="organization" value={values.company} onChange={onChange} aria-invalid={!!errors.company} aria-describedby={describedBy("company")} className={inputClass(errors.company)} />
        </Field>
        <Field id="city" label="City" error={errors.city}>
          <input id="city" name="city" type="text" autoComplete="address-level2" value={values.city} onChange={onChange} aria-invalid={!!errors.city} aria-describedby={describedBy("city")} className={inputClass(errors.city)} />
        </Field>
        <Field id="state" label="State" error={errors.state}>
          <select id="state" name="state" autoComplete="address-level1" value={values.state} onChange={onChange} className={inputClass(errors.state)}>
            <option value="">Select a state</option>
            {US_STATES.map(state => (
              <option key={state} value={state}>
                {state}
              </option>
            ))}
          </select>
        </Field>
      </div>

      <fieldset aria-describedby={describedBy("timeframe")}>
        <legend className="mb-3 block text-sm font-medium text-apex-navy">
          When would you like to begin your accounting services?
          <span className="text-apex-sky" aria-hidden="true">
            {" "}
            *
          </span>
        </legend>
        <div className="grid gap-3 sm:grid-cols-3">
          {Object.entries(form.timeframes).map(([value, label]) => (
            <label
              key={value}
              className={`flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 text-sm transition-colors has-[:checked]:border-apex-navy has-[:checked]:bg-apex-cloud has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-apex-sky ${
                errors.timeframe ? "border-red-600" : "border-apex-ink/20 hover:border-apex-ink/40"
              }`}
            >
              <input type="radio" name="timeframe" value={value} checked={values.timeframe === value} onChange={onChange} className="size-4 accent-apex-navy" />
              {label}
            </label>
          ))}
        </div>
        {errors.timeframe && (
          <p id="timeframe-error" className="mt-2 flex items-center gap-1.5 text-sm text-red-700">
            <Icon name="alert-circle" className="size-4 shrink-0" strokeWidth={2} />
            {errors.timeframe}
          </p>
        )}
      </fieldset>

      <Field id="service" label="Service of interest" error={errors.service}>
        <select id="service" name="service" value={values.service} onChange={onChange} className={inputClass(errors.service)}>
          <option value="">Not sure yet</option>
          {Object.entries(form.services).map(([value, label]) => (
            <option key={value} value={value}>
              {label}
            </option>
          ))}
        </select>
      </Field>

      <Field
        id="message"
        label="How can we help?"
        error={errors.message}
        hint="Please don’t include Social Security numbers, bank details or tax documents. We’ll request anything we need securely."
      >
        <textarea id="message" name="message" rows={4} maxLength={1000} value={values.message} onChange={onChange} onBlur={onBlur} aria-invalid={!!errors.message} aria-describedby={describedBy("message", true)} className={inputClass(errors.message)} />
      </Field>

      {/* Honeypot: hidden from people and assistive tech, bots fill it in. */}
      <div className="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
        <label htmlFor="website">Website</label>
        <input id="website" name="website" type="text" tabIndex={-1} autoComplete="off" value={values.website} onChange={onChange} />
      </div>

      <div>
        <label className="flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-apex-ink/75">
          <input
            type="checkbox"
            name="consent"
            checked={values.consent}
            onChange={onChange}
            aria-invalid={!!errors.consent}
            aria-describedby={describedBy("consent")}
            className="mt-1 size-4 shrink-0 accent-apex-navy"
          />
          <span>
            I agree to the website’s{" "}
            <a href={form.privacyUrl} target="_blank" rel="noopener" className="font-medium text-apex-navy underline hover:text-apex-sky">
              Privacy Policy
            </a>{" "}
            and{" "}
            <a href={form.termsUrl} target="_blank" rel="noopener" className="font-medium text-apex-navy underline hover:text-apex-sky">
              Terms &amp; Conditions
            </a>
            .<span className="text-apex-sky" aria-hidden="true"> *</span>
          </span>
        </label>
        {errors.consent && (
          <p id="consent-error" className="mt-2 flex items-center gap-1.5 text-sm text-red-700">
            <Icon name="alert-circle" className="size-4 shrink-0" strokeWidth={2} />
            {errors.consent}
          </p>
        )}
      </div>

      <div className="flex flex-col gap-4 border-t border-apex-line pt-6 sm:flex-row sm:items-center sm:justify-between">
        <p className="flex items-center gap-2 text-xs text-apex-ink/60">
          <Icon name="lock" className="size-4 shrink-0" />
          Your information is sent securely and only used to contact you.
        </p>
        <button type="submit" disabled={submitting} className="apex-btn-primary disabled:cursor-wait disabled:opacity-70">
          {submitting ? (
            <>
              <span className="size-5 animate-spin rounded-full border-2 border-white/30 border-t-white" aria-hidden="true" />
              Sending…
            </>
          ) : (
            <>
              <Icon name="calendar" className="size-5" />
              Request My Strategy Session
            </>
          )}
        </button>
      </div>
    </form>
  )
}
