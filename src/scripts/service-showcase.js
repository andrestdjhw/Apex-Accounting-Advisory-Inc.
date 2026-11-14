// Home services showcase (template-parts/services-showcase.php).
// Hover / focus on a service shows its preview. While the section is in view and
// nobody is interacting, it advances every few seconds; a brass line on the active
// row shows the progress. Restarts from the first service when the section is revisited.

const INTERVAL = 5000
const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)")
const desktop = window.matchMedia("(min-width: 1024px)")

export function initServiceShowcase() {
  document.querySelectorAll("[data-showcase]").forEach(root => {
    const items = [...root.querySelectorAll("[data-showcase-item]")]
    const panels = [...root.querySelectorAll("[data-showcase-panel]")]
    if (!items.length || items.length !== panels.length) return

    let active = 0
    let timer = 0
    let inView = false
    let paused = false

    const progress = i => items[i].querySelector("[data-showcase-progress]")

    const resetProgress = () => {
      items.forEach((_, i) => {
        const bar = progress(i)
        if (!bar) return
        bar.style.transition = "none"
        bar.style.transform = "scaleX(0)"
      })
    }

    const runProgress = () => {
      const bar = progress(active)
      if (!bar || reduceMotion.matches) return
      void bar.offsetWidth // restart the transition
      bar.style.transition = `transform ${INTERVAL}ms linear`
      bar.style.transform = "scaleX(1)"
    }

    const show = i => {
      active = i
      items.forEach((item, n) => item.toggleAttribute("data-active", n === i))
      panels.forEach((panel, n) => {
        panel.toggleAttribute("data-active", n === i)
        panel.toggleAttribute("inert", n !== i)
      })
    }

    const stop = () => {
      clearTimeout(timer)
      resetProgress()
    }

    const schedule = () => {
      stop()
      if (!inView || paused || reduceMotion.matches || !desktop.matches) return
      runProgress()
      timer = setTimeout(() => {
        show((active + 1) % items.length)
        schedule()
      }, INTERVAL)
    }

    items.forEach((item, i) => {
      item.addEventListener("mouseenter", () => {
        paused = true
        show(i)
        stop()
      })
      item.addEventListener("focus", () => {
        paused = true
        show(i)
        stop()
      })
    })
    root.addEventListener("mouseleave", () => {
      paused = false
      schedule()
    })
    root.addEventListener("focusout", e => {
      if (!root.contains(e.relatedTarget)) {
        paused = false
        schedule()
      }
    })

    if ("IntersectionObserver" in window) {
      let left = false // fully off screen since the last visit → start over on return
      new IntersectionObserver(
        ([entry]) => {
          if (entry.intersectionRatio === 0) {
            left = true
            inView = false
            stop()
            return
          }
          const nowInView = entry.intersectionRatio >= 0.35
          if (nowInView && !inView) {
            if (left) show(0) // replay from the first service on every visit
            left = false
            inView = true
            schedule()
          } else if (!nowInView && inView) {
            inView = false
            stop()
          }
        },
        { threshold: [0, 0.35] }
      ).observe(root)
    }
    desktop.addEventListener("change", schedule)
  })
}
