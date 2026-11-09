import React from "react"
import ReactDOM from "react-dom/client"
import Navbar from "./components/Navbar"
import Footer from "./components/Footer"
import { initReveal } from "./scripts/reveal"

const site = window.apexSite

function mount(id, Component) {
  const el = document.getElementById(id)
  if (el && site) {
    ReactDOM.createRoot(el).render(<Component site={site} />)
  }
}

mount("apex-navbar", Navbar)
mount("apex-footer", Footer)
initReveal()
