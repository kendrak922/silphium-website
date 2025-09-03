/**
 * Scroll JS functions used across all templates in the theme
 * - Class initialized in: ./assets/src/js/main.js
 *
 * @constructor
 */
class Functions__Scroll {
  constructor(context = 'main') {
    this.context = context //to call this class in multiple places with an obvious semantic context change
    this.setActiveLink = this.setActiveLink.bind(this)
  }

  // Function to update the active anchor link
  setScrollBodyClass = function (threshold = 120) {
    if (window.scrollY > threshold) {
      document.body.classList.add('scrolled')
    } else {
      document.body.classList.remove('scrolled')
    }
  }

  // Function to update the active anchor link
  setActiveLink = function () {
    const sections = document.querySelectorAll('main section') // Get all sections

    for (const section of sections) {
      const rect = section.getBoundingClientRect()

      if (rect.top >= 0 && rect.top <= window.innerHeight) {
        // Section is in the viewport
        const sectionId = section.id
        const links = document.querySelectorAll('#table_of_contents a')

        // Remove the 'active' class from all links
        links.forEach((link) =>
          link.parentElement.classList.remove('is-active')
        )

        // Add the 'active' class to the corresponding link
        // console.log(sectionId, 'sectionId')
        const activeLink = document.querySelector(
          `#table_of_contents a[href="#${sectionId}"]`
        )
        activeLink?.parentElement.classList.add('is-active')
        return
      }
    }
  }
}
