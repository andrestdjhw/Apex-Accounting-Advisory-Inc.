import React from "react"
import ReactDOM from "react-dom/client"
import Navbar from "./components/Navbar"
import Footer from "./components/Footer"
import ContactForm from "./components/ContactForm"
import HeroForm from "./components/HeroForm"
import CookieConsent from "./components/CookieConsent"
import { initReveal } from "./scripts/reveal"
import { initTracking } from "./scripts/tracking"
import { initServiceNav } from "./scripts/service-nav"
import { initMotion } from "./scripts/motion"

const site = window.apexSite

function mount(id, Component) {
  const el = document.getElementById(id)
  if (el && site) {
    ReactDOM.createRoot(el).render(<Component site={site} />)
  }
}

mount("apex-navbar", Navbar)
mount("apex-footer", Footer)
mount("apex-contact-form", ContactForm)
mount("apex-hero-form", HeroForm)
mount("apex-cookie-consent", CookieConsent)
initMotion()
initReveal()
initTracking()
initServiceNav()
