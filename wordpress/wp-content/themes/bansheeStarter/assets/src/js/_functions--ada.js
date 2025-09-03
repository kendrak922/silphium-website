/**
 * ADA related functions used to meet compliance
 * - Class initialized in: ./assets/src/js/__global.js
 *
 * @constructor
 */
class Functions__ADA {
  constructor(context = 'main') {
    this.context = context //to call this class in multiple places with an obvious semantic context change
  }

  //Toggle Handlers
  adaOpenToggle(trigger) {
    trigger.setAttribute('aria-expanded', true)
    trigger.nextElementSibling.setAttribute('aria-hidden', false)
    trigger.nextElementSibling.querySelectorAll('button').forEach((node) => {
      node.setAttribute('tabindex', 0)
    })
  }
  adaCloseToggle(trigger) {
    trigger.setAttribute('aria-expanded', false)
    trigger.nextElementSibling.setAttribute('aria-hidden', true)
    trigger.nextElementSibling.querySelectorAll('button').forEach((node) => {
      node.setAttribute('tabindex', -1)
    })
  }

  /**
   * iFrame Cleanup
   *
   * @example
   * // Outside Constructor
   * var fn__ada = new Functions__ADA();
   * fn__ada.iframes();
   *
   * // Within Constructor
   * this.iframes();
   */
  iframes() {
    if (document.querySelector('iframe')) {
      let iframes = document.querySelectorAll('iframe')
      iframes.forEach((iframe) => {
        // Remove HTML formatting
        iframe.removeAttribute('style')
        iframe.removeAttribute('width')
        iframe.removeAttribute('height')
        iframe.removeAttribute('frameborder')
        iframe.removeAttribute('border')
        iframe.removeAttribute('scrolling')
      })
    }
  }

  /**
   * Slick Slider ADA additions
   *
   * @example
   * // Outside Constructor
   * var fn__ada = new Functions__ADA();
   * fn__ada.slickSlider_dotsADA();
   *
   * // Within Constructor
   * this.slickSlider_dotsADA();
   */
  slickSlider_dotsADA = function () {
    if ($('.slick-slider.slick-dotted').length) {
      var sliders = $('.slick-slider.slick-dotted').toArray()
      $.each(sliders, function (i, slider) {
        var dotCount = $(slider).find('.slick-dots').children().length
        if (dotCount && dotCount < 2) {
          $(slider).find('.slick-slide').removeAttr('aria-describedby')
        }
      })
    }
  }

  /**
   * Footnotes ADA additions
   */
  footnotes = function () {
    // Check if there are elements with class 'fn'
    var fnElements = document.querySelectorAll('sup.fn')
    if (fnElements.length) {
      fnElements.forEach(function (fnElement) {
        // Find the first 'a' element inside the 'sup' element
        var anchorElement = fnElement.querySelector('a')
        if (anchorElement) {
          var footnoteID = anchorElement.textContent
          anchorElement.setAttribute(
            'aria-label',
            'Go to Footnote ' + footnoteID
          )
          anchorElement.setAttribute('aria-describedby', 'wp-block-footnotes')
          anchorElement.setAttribute('role', 'doc-noteref')
        }
      })
    }

    // Check if there are elements with class 'wp-block-footnotes'
    var fnBackElements = document.querySelectorAll('.wp-block-footnotes li')
    if (fnBackElements.length) {
      fnBackElements.forEach(function (fnElement, index) {
        var anchorElement = fnElement.querySelector('a:last-of-type')
        if (anchorElement) {
          anchorElement.setAttribute(
            'aria-label',
            'Back to reference ' + (index + 1)
          )
          anchorElement.setAttribute('role', 'doc-backlink')
          // anchorElement.textContent = '[' + (index + 1) + ']'
        }
      })
    }
  }

  //FOCUS TRAP
  //https://kahoot.com/tech-blog/focus-on-accessibility-accessible-menus-and-modals/
  setFocusContext = (focusContext) => {
    // focusable elements within document
    var focusableElements = document.querySelectorAll(`
        a, 
        input,
        button, 
        select, 
        textarea,
        [tabindex]
        `)

    var parentElements = document.querySelectorAll(`
        body > div, 
        body > header,
        body > aside, 
        body > section, 
        body > main,
        body > footer,
        body > form,
        body > article
        `)

    const formElements = ['BUTTON', 'INPUT', 'SELECT', 'TEXTAREA']

    if (focusContext) {
      focusableElements.forEach(function (el) {
        if (!focusContext.contains(el)) {
          // disable focusable elements outside our context
          el.setAttribute('data-focus-context', false)
          el.setAttribute('aria-hidden', true)
          if (el.hasAttribute('tabindex')) {
            el.setAttribute(
              'data-context-inert-tabindex',
              el.getAttribute('tabindex')
            )
            el.removeAttribute('tabindex')
          }
          if (el.hasAttribute('href')) {
            el.setAttribute('data-context-inert-href', el.getAttribute('href'))
            el.removeAttribute('href')
          }
          if (formElements.indexOf(el.tagName) != -1) {
            if (el.hasAttribute('disabled')) {
              el.setAttribute(
                'data-context-inert-disabled',
                el.getAttribute('disabled')
              )
            }
            el.setAttribute('disabled', true)
          }
        }
      })

      focusContext.focus()

      // set parents in dom inert
      parentElements.forEach((parentEl) => {
        if (!parentEl.contains(focusContext)) {
          parentEl.setAttribute('inert', ' ')
          parentEl.setAttribute('aria-hidden', true)
        }
      })
    } else {
      var inertElements = document.querySelectorAll(
        '[data-focus-context=false]'
      )
      // restore
      inertElements.forEach((el) => {
        if (el.hasAttribute('data-context-inert-tabindex')) {
          el.setAttribute(
            'tabindex',
            el.getAttribute('data-context-inert-tabindex')
          )
          el.removeAttribute('data-context-inert-tabindex')
        }
        if (el.hasAttribute('data-context-inert-href')) {
          el.setAttribute('href', el.getAttribute('data-context-inert-href'))
          el.removeAttribute('data-context-inert-href')
        }
        if (formElements.indexOf(el.tagName) != -1) {
          el.removeAttribute('disabled', true)
          if (el.hasAttribute('data-context-inert-disabled')) {
            el.setAttribute(
              'disabled',
              el.getAttribute('data-context-inert-disabled')
            )
            el.removeAttribute('data-context-inert-disabled')
          }
        }
        el.removeAttribute('data-context')
      })
      // restore parents in dom
      parentElements.forEach((parentEl) => {
        parentEl.removeAttribute('inert')
        parentEl.removeAttribute('aria-hidden')
      })
    }
  }
}
