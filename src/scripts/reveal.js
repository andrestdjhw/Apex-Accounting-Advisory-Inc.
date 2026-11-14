// Scroll animations (no dependencies). Everything replays when it comes back into view.
//  - [data-reveal="up|fade|left|right|zoom|line|curtain"]  enters when in view, resets once fully out
//  - [data-rule]                 its top rule draws itself in (element with border-t)
//  - [data-count="120"]          counts up from 0 each time it enters (data-suffix="+" optional)
//  - [data-reveal-stagger="90"]  on a parent: staggers its [data-reveal] children (ms)
//  - [data-replay]               restarts CSS animations inside it when it comes back into view

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)")

let enterObserver = null // reveals once the element is meaningfully on screen
let exitObserver = null // resets only when it is completely off screen (no flicker at the edges)

function applyStagger(root) {
  root.querySelectorAll("[data-reveal-stagger]").forEach(parent => {
    const step = parseInt(parent.dataset.revealStagger, 10) || 90
    parent.querySelectorAll("[data-reveal]").forEach((el, i) => {
      if (!el.style.getPropertyValue("--reveal-delay")) {
        el.style.setProperty("--reveal-delay", `${i * step}ms`)
      }
    })
  })
}

export function scanReveal(root = document) {
  applyStagger(root)
  const items = root.querySelectorAll("[data-reveal], [data-rule], [data-count]")

  if (!enterObserver || reduceMotion.matches) {
    items.forEach(el => el.classList.add("is-visible"))
    return
  }
  items.forEach(el => {
    enterObserver.observe(el)
    exitObserver.observe(el)
  })
}

// Figures: 0 → value with an ease-out, tabular digits keep the width steady.
function countUp(el) {
  const end = parseFloat(el.dataset.count) || 0
  const suffix = el.dataset.suffix || ""
  const duration = 1600
  const start = performance.now()
  const tick = now => {
    if (!el.classList.contains("is-visible")) return
    const t = Math.min(1, (now - start) / duration)
    const eased = 1 - Math.pow(1 - t, 3)
    el.textContent = Math.round(end * eased).toLocaleString("en-US") + suffix
    if (t < 1) requestAnimationFrame(tick)
  }
  requestAnimationFrame(tick)
}

function restartAnimations(el) {
  if (typeof el.getAnimations !== "function") return
  el.getAnimations({ subtree: true }).forEach(animation => {
    if (animation instanceof CSSAnimation) {
      animation.cancel()
      animation.play()
    }
  })
}

function initReplay() {
  const items = document.querySelectorAll("[data-replay]")
  if (!items.length || reduceMotion.matches || !("IntersectionObserver" in window)) return

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const el = entry.target
      if (!entry.isIntersecting) {
        el.dataset.replayPending = "true"
      } else if (el.dataset.replayPending) {
        delete el.dataset.replayPending
        restartAnimations(el)
      }
    })
  })
  items.forEach(el => observer.observe(el))
}

export function initReveal() {
  document.documentElement.classList.add("reveal-ready")

  if ("IntersectionObserver" in window && !reduceMotion.matches) {
    enterObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting && !entry.target.classList.contains("is-visible")) {
            entry.target.classList.add("is-visible")
            if (entry.target.dataset.count) countUp(entry.target)
          }
        })
      },
      { rootMargin: "0px 0px -8% 0px", threshold: 0 }
    )

    exitObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) {
            entry.target.classList.remove("is-visible")
            if (entry.target.dataset.count) entry.target.textContent = "0" + (entry.target.dataset.suffix || "")
          }
        })
      },
      { threshold: 0 }
    )
  }

  scanReveal()
  initReplay()
}
