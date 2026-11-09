// Lightweight scroll animations (no dependencies).
//  - [data-reveal="up|fade|left|right|zoom|line"]  animates every time it enters the viewport
//                                                   and resets once it has fully left it
//  - [data-reveal-stagger="90"] on a parent          staggers its [data-reveal] children (ms)
//  - [data-replay]                                   restarts the CSS animations inside it
//                                                   (e.g. the hero entrance) when it comes back into view
//  - [data-parallax="0.15"]                          subtle vertical parallax (transform only)

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)")

let enterObserver = null // reveals once the element is meaningfully on screen
let exitObserver = null // resets only when it is completely off screen (avoids flicker at the edges)

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
  const items = root.querySelectorAll("[data-reveal]")

  if (!enterObserver || reduceMotion.matches) {
    items.forEach(el => el.classList.add("is-visible"))
    return
  }
  items.forEach(el => {
    enterObserver.observe(el)
    exitObserver.observe(el)
  })
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

function initParallax() {
  const items = [...document.querySelectorAll("[data-parallax]")]
  if (!items.length || reduceMotion.matches) return

  let ticking = false
  const update = () => {
    items.forEach(el => {
      const speed = parseFloat(el.dataset.parallax) || 0.15
      const rect = el.parentElement.getBoundingClientRect()
      if (rect.bottom < 0 || rect.top > window.innerHeight) return
      el.style.transform = `translate3d(0, ${(-rect.top * speed).toFixed(1)}px, 0)`
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
  update()
}

export function initReveal() {
  document.documentElement.classList.add("reveal-ready")

  if ("IntersectionObserver" in window && !reduceMotion.matches) {
    enterObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) entry.target.classList.add("is-visible")
        })
      },
      { rootMargin: "0px 0px -10% 0px", threshold: 0.12 }
    )

    exitObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) entry.target.classList.remove("is-visible")
        })
      },
      { threshold: 0 }
    )
  }

  scanReveal()
  initReplay()
  initParallax()
}
