// Interaction effects adapted from the 828 Marketing Solutions reference build
// (lem.828marketingsolutions.com), rewritten without GSAP so they share the
// replay-on-revisit behaviour of reveal.js:
//
//  - Smooth wheel scrolling (Lenis) + in-page anchors that respect scroll-margin
//  - Scroll progress bar            [data-scroll-progress]
//  - Headline word-by-word reveal   [data-split]  (on scroll)  /  [data-split="load"]  (hero, on load)
//  - Words that light up on scroll  [data-scrub]
//  - Magnetic buttons               [data-magnetic]
//  - 3D tilt + cursor light         [data-tilt]
//  - Hero image follows the mouse   [data-mouse-parallax]
//
// Everything is skipped with prefers-reduced-motion; pointer-only effects are
// skipped on touch devices.

import Lenis from "lenis"

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches
const finePointer = window.matchMedia("(hover: hover) and (pointer: fine)").matches

/* ---------- Split text into words (keeps <span>, <em>… and their styles) ---------- */

function splitWords(el, { mask = true } = {}) {
  if (el.dataset.splitDone) return [...el.querySelectorAll(".apex-w__i")]
  const words = []

  const walk = node => {
    ;[...node.childNodes].forEach(child => {
      if (child.nodeType === Node.TEXT_NODE) {
        const frag = document.createDocumentFragment()
        child.textContent.split(/(\s+)/).forEach(part => {
          if (!part) return
          if (/^\s+$/.test(part)) {
            frag.appendChild(document.createTextNode(" "))
            return
          }
          const inner = document.createElement("span")
          inner.className = "apex-w__i"
          inner.textContent = part
          if (mask) {
            const outer = document.createElement("span")
            outer.className = "apex-w"
            outer.appendChild(inner)
            frag.appendChild(outer)
          } else {
            frag.appendChild(inner)
          }
          words.push(inner)
        })
        child.replaceWith(frag)
      } else if (child.nodeType === Node.ELEMENT_NODE && !child.matches("svg, br, img")) {
        walk(child)
      }
    })
  }

  walk(el)
  words.forEach((word, i) => word.style.setProperty("--w", i))
  el.dataset.splitDone = "true"
  el.classList.add("is-split")
  return words
}

function initSplit() {
  document.querySelectorAll("[data-split]").forEach(el => splitWords(el))
}

/* ---------- Words light up as the block scrolls through the viewport ---------- */

function initScrub() {
  const blocks = [...document.querySelectorAll("[data-scrub]")].map(el => ({
    el,
    words: splitWords(el, { mask: false }),
  }))
  if (!blocks.length) return

  let ticking = false
  const update = () => {
    const vh = window.innerHeight
    blocks.forEach(({ el, words }) => {
      const rect = el.getBoundingClientRect()
      if (rect.bottom < -vh || rect.top > vh * 2) return
      // 0 when the top reaches 85% of the viewport, 1 when the bottom reaches 45%
      const start = vh * 0.85
      const end = vh * 0.45
      const progress = Math.min(1, Math.max(0, (start - rect.top) / (start - end + rect.height)))
      const lit = progress * words.length
      words.forEach((word, i) => {
        word.style.opacity = (0.22 + 0.78 * Math.min(1, Math.max(0, lit - i))).toFixed(3)
      })
    })
    ticking = false
  }

  window.addEventListener(
    "scroll",
    () => {
      if (!ticking) {
        ticking = true
        requestAnimationFrame(update)
      }
    },
    { passive: true }
  )
  window.addEventListener("resize", update)
  update()
}

/* ---------- Scroll progress bar ---------- */

function initProgress() {
  const bar = document.querySelector("[data-scroll-progress]")
  if (!bar) return
  let ticking = false
  const update = () => {
    const max = document.documentElement.scrollHeight - window.innerHeight
    bar.style.transform = `scaleX(${max > 0 ? Math.min(1, window.scrollY / max) : 0})`
    ticking = false
  }
  window.addEventListener(
    "scroll",
    () => {
      if (!ticking) {
        ticking = true
        requestAnimationFrame(update)
      }
    },
    { passive: true }
  )
  window.addEventListener("resize", update)
  update()
}

/* ---------- Smooth scroll ---------- */

function anchorOffset(target) {
  const margin = parseFloat(getComputedStyle(target).scrollMarginTop) || 0
  const padding = parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0
  return Math.max(margin, padding)
}

function initSmoothScroll() {
  if (!finePointer) return // touch scrolling stays native

  const lenis = new Lenis({
    duration: 1.15,
    easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
  })
  window.apexLenis = lenis

  const raf = time => {
    lenis.raf(time)
    requestAnimationFrame(raf)
  }
  requestAnimationFrame(raf)

  // In-page anchors (#strategy-session, service chips, skip link…)
  document.addEventListener("click", e => {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return
    const link = e.target.closest('a[href*="#"]')
    if (!link || link.target === "_blank") return
    const url = new URL(link.href, window.location.href)
    if (url.origin !== window.location.origin || url.pathname !== window.location.pathname || url.search !== window.location.search || !url.hash) return

    const target = document.getElementById(decodeURIComponent(url.hash.slice(1)))
    if (!target) return
    e.preventDefault()
    lenis.scrollTo(target, { offset: -anchorOffset(target), duration: 1.4 })
    history.pushState(null, "", url.hash)

    // Move keyboard focus with the scroll (accessibility)
    if (!target.matches("a, button, input, select, textarea, [tabindex]")) target.setAttribute("tabindex", "-1")
    target.focus({ preventScroll: true })
  })
}

/* ---------- Magnetic buttons ---------- */

function initMagnetic() {
  if (!finePointer) return
  document.querySelectorAll("[data-magnetic]").forEach(btn => {
    let resetTimer = 0
    btn.addEventListener("mousemove", e => {
      clearTimeout(resetTimer)
      const r = btn.getBoundingClientRect()
      const x = (e.clientX - r.left - r.width / 2) * 0.22
      const y = (e.clientY - r.top - r.height / 2) * 0.32
      btn.style.transition = "transform .25s var(--ease-apex), background-color .2s, color .2s, border-color .2s"
      btn.style.transform = `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0)`
    })
    btn.addEventListener("mouseleave", () => {
      btn.style.transition = "transform .7s var(--ease-apex), background-color .2s, color .2s, border-color .2s"
      btn.style.transform = ""
      resetTimer = setTimeout(() => (btn.style.transition = ""), 700)
    })
  })
}

/* ---------- 3D tilt + light that follows the cursor ---------- */

function initTilt() {
  if (!finePointer) return
  document.querySelectorAll("[data-tilt]").forEach(card => {
    const strength = parseFloat(card.dataset.tilt) || 6
    card.addEventListener("mousemove", e => {
      const r = card.getBoundingClientRect()
      const px = (e.clientX - r.left) / r.width
      const py = (e.clientY - r.top) / r.height
      card.style.setProperty("--tilt-y", `${((px - 0.5) * strength).toFixed(2)}deg`)
      card.style.setProperty("--tilt-x", `${((0.5 - py) * strength * 0.75).toFixed(2)}deg`)
      card.style.setProperty("--gx", `${(px * 100).toFixed(1)}%`)
      card.style.setProperty("--gy", `${(py * 100).toFixed(1)}%`)
    })
    card.addEventListener("mouseleave", () => {
      card.style.removeProperty("--tilt-x")
      card.style.removeProperty("--tilt-y")
    })
  })
}

/* ---------- Hero image drifts against the mouse ---------- */

function initMouseParallax() {
  if (!finePointer) return
  document.querySelectorAll("[data-mouse-parallax]").forEach(layer => {
    const area = layer.closest("section") || layer.parentElement
    area.addEventListener("mousemove", e => {
      const r = area.getBoundingClientRect()
      const px = (e.clientX - r.left) / r.width - 0.5
      const py = (e.clientY - r.top) / r.height - 0.5
      layer.style.transform = `translate3d(${(px * -24).toFixed(1)}px, ${(py * -16).toFixed(1)}px, 0) scale(1.04)`
    })
    area.addEventListener("mouseleave", () => {
      layer.style.transform = ""
    })
  })
}

/* ---------- Stacked sticky cards + timeline ---------- */
// Each card sticks a little lower than the previous one. As the next card
// arrives, the previous one shrinks and fades (--stack: 0 → 1). The timeline
// next to the stack highlights the current step and fills its progress line.

export function initStack() {
  document.querySelectorAll("[data-stack]").forEach(stack => {
    const cards = [...stack.querySelectorAll("[data-stack-card]")]
    const timeline = stack.id ? document.querySelector(`[data-stack-timeline="${stack.id}"]`) : null
    const steps = timeline ? [...timeline.querySelectorAll("[data-step]")] : []
    if (!cards.length) return

    let ticking = false
    const update = () => {
      const vh = window.innerHeight
      let active = 0
      let partial = 0

      cards.forEach((card, i) => {
        const stickTop = parseFloat(getComputedStyle(card).top) || 0
        // Active once stuck — or past mid-screen (the last card can't stick: its list ends)
        if (card.getBoundingClientRect().top <= Math.max(stickTop + 2, vh * 0.45)) active = i

        const next = cards[i + 1]
        if (!next) return
        const nextStick = parseFloat(getComputedStyle(next).top) || 0
        const nextTop = next.getBoundingClientRect().top
        const progress = Math.min(1, Math.max(0, (vh - nextTop) / (vh - nextStick)))
        if (!reduceMotion) card.style.setProperty("--stack", progress.toFixed(3))
        if (i === active) partial = progress
      })

      // Before the first card sticks, nothing is active yet
      if (cards[0].getBoundingClientRect().top > Math.max((parseFloat(getComputedStyle(cards[0]).top) || 0) + 2, vh * 0.45)) active = -1

      steps.forEach((step, i) => step.toggleAttribute("data-active", i <= active))
      if (timeline) {
        const fill = active < 0 ? 0 : (active + (active < cards.length - 1 ? partial : 0)) / Math.max(1, cards.length - 1)
        timeline.style.setProperty("--timeline", Math.min(1, fill).toFixed(3))
      }
      ticking = false
    }

    window.addEventListener(
      "scroll",
      () => {
        if (!ticking) {
          ticking = true
          requestAnimationFrame(update)
        }
      },
      { passive: true }
    )
    window.addEventListener("resize", update)
    update()
  })
}

export function initMotion() {
  initStack()
  if (reduceMotion) return
  initSplit()
  initScrub()
  initProgress()
  initSmoothScroll()
  initMagnetic()
  initTilt()
  initMouseParallax()
}
