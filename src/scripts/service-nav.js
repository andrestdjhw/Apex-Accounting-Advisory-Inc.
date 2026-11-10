// Services page: highlights the chip of the section currently in view and keeps
// it scrolled into view inside the horizontal sticky nav.

export function initServiceNav() {
  const nav = document.querySelector("[data-service-nav]")
  const sections = document.querySelectorAll("[data-service-section]")
  if (!nav || !sections.length || !("IntersectionObserver" in window)) return

  const links = new Map([...nav.querySelectorAll('a[href^="#"]')].map(a => [a.getAttribute("href").slice(1), a]))

  const setActive = id => {
    links.forEach((link, key) => {
      if (key === id) {
        link.setAttribute("aria-current", "true")
        const navBox = nav.getBoundingClientRect()
        const box = link.getBoundingClientRect()
        if (box.left < navBox.left || box.right > navBox.right) {
          nav.scrollBy({ left: box.left - navBox.left - 16, behavior: "smooth" })
        }
      } else {
        link.removeAttribute("aria-current")
      }
    })
  }

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => entry.isIntersecting && setActive(entry.target.id))
    },
    { rootMargin: "-45% 0px -50% 0px" }
  )
  sections.forEach(section => observer.observe(section))
}
