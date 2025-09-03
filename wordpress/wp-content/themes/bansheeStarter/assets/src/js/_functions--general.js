/**
 * General JS functions used across all templates in the theme
 * - Class initialized in: ./assets/src/js/main.js
 *
 * @constructor
 */
class Functions__General {
  constructor(context = 'main') {
    this.context = context //to call this class in multiple places with an obvious semantic context change
    this.leaving_site__check = this.leaving_site__check.bind(this)
    this.addNoOpener = this.addNoOpener.bind(this)
    this.addNewTabMessage = this.addNewTabMessage.bind(this)
  }
  /**
   * Calculate global values
   * - Any dynamic functions should be calculated here.
   * - Fire this function on other events (resize, etc) to keep vars central
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.update_global_vals();
   *
   * // Within Constructor
   * this.update_global_vals();
   */
  update_global_vals() {
    // General
    global.general.window_height = window.innerHeight
    global.general.window_width = window.innerWidth
    global.general.header_height =
      global.general.window_width > global.breakpoints.mobile ? 70 : 80
    global.general.wp_adminBar_height =
      global.general.window_width > global.breakpoints.mobile ? 32 : 46
  }

  /**
   * Detect if the user is on mobile
   * - Adds status class to <body>
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.detect__mobile();
   *
   * // Within Constructor
   * this.detect__mobile();
   */
  detect__mobile() {
    // device detection
    if (
      /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
        navigator.userAgent
      ) ||
      /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
        navigator.userAgent.substr(0, 4)
      )
    ) {
      global.general.isMobile = true
    }
    if (global.general.isMobile) {
      document.body.classList.add('mobile')
    } else {
      document.body.classList.add('desktop')
    }
  }

  /**
   * Detect Browser/Device/OS
   * - Adds status class to <body>
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.detect__device();
   *
   * // Within Constructor
   * this.detect__device();
   */
  detect__device() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera
    global.general.device = 'unknown'

    // Windows Phone must come first because its UA also contains "Android"
    if (/android/i.test(userAgent) || /Android/i.test(userAgent)) {
      global.general.device = 'android'
    }
    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      global.general.device = 'ios'
    }
    if (/windows phone/i.test(userAgent) || /Windows Phone/i.test(userAgent)) {
      global.general.device = 'windows-phone'
    }
    if (/blackberry/i.test(userAgent) || /Blackberry/i.test(userAgent)) {
      global.general.device = 'blackberry'
    }

    // Detect IE
    let ua = window.navigator.userAgent
    const msie = ua.indexOf('MSIE ')
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
      // If Internet Explorer, return version number
      if (global.general.device === 'unknown') {
        global.general.device = '' // remove unknown setting, only add on to known devices/browsers
      }
      global.general.device += ' ie'
    }
    // Add device class to body
    document.body.classList.add(global.general.device)
  }

  /**
   * Toggles window scroll locking and sets scroll position
   * - https://stackoverflow.com/a/3656618
   * - REQUIRED: Add `.scroll--lock{ overflow:hidden; }` to CSS
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.scroll_lock_toggle();
   *
   * // Within Constructor
   * this.scroll_lock_toggle();
   */
  scroll_lock_toggle() {
    let scrollPosition = [
      self.pageXOffset ||
        document.documentElement.scrollLeft ||
        document.body.scrollLeft,
      self.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop,
    ]
    const html = document.querySelector('html')

    // UNLOCK SCROLL
    if (html.classList.contains('scroll--lock')) {
      html.classList.remove('scroll--lock')
      window.scrollTo(scrollPosition[0], scrollPosition[1])

      // LOCK SCROLL
    } else {
      html.classList.add('scroll--lock')
      window.scrollTo(scrollPosition[0], scrollPosition[1])
    }
  }


// swiper (){
// const swiper = new Swiper(...);
// }



  /**
   * Page easing to anchor links
   * - Grabs the "href" of the 'clicked' param to scroll to
   *
   * @param {event} evt - pass event handler 'event' into function
   * @param {string} clicked - "this" element that triggered function
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.anchor_easing(evt, this);
   *
   * // Within Constructor
   * this.anchor_easing(evt, this);
   */
  anchor_easing(evt, clicked) {
    evt = typeof evt !== 'undefined' ? evt : false
    clicked = typeof clicked !== 'undefined' ? clicked : false
    if (evt && clicked) {
      evt.preventDefault()
      let anchor = jQuery(clicked).attr('href')
      let headerHeight = global.general.header_height + 25
      jQuery('html, body')
        .stop()
        .animate(
          {
            scrollTop: jQuery(anchor).offset().top - headerHeight,
          },
          1200,
          () => {
            // -- CALLBACK --
            // ADA Focus - for keyboard clicks only
            if (evt.which == 13) {
              jQuery(anchor)
                .attr('tabindex', '0')
                .focus()
                .removeattr('tabindex')
            }
          }
        )
    } else {
      console.log('ERROR - fn__general.anchor_easing();')
    }
  }

  /**
   * Get a query parameter from the URL query string
   *
   * @param {string} parameter - The name of the query string parameter you want to retreive
   * @return {string} the value of the defined "parameter" from the query string will be returned
   * @example
   * // Sample query param: ?query_param=customVal
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * var queryParamVal = fn__general.getQueryParam('query_param');
   *
   * // Within Constructor
   * var queryParamVal = $this.getQueryParam('query_param');
   */
  getQueryParam(parameter) {
    let qs = window.location.search
    qs = '&' + qs.replace(/%20/gi, ' ')
    const p = escape(unescape(parameter))
    const regex = new RegExp('[?&]' + p + '=(?:([^&]*))?', 'i')

    let match = regex.exec(qs)
    let value = ''
    if (match != null) {
      value = match[1]
    }
    return value
  }

  /**
   * Set a JS cookie in the browser
   * - https://www.w3schools.com/js/js_cookies.asp
   *
   * @param {string} cname - Name of the cookie you'd like to set
   * @param {*} cvalue - Value you'd like to store in a cookie
   * @param {int} exdays - Number of days you'd like the cookie to stay alive
   *
   * @example
   * // Set the "cookie_name" for 7 days with the value of "sample value"
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.cookie__set('cookie_name', 'sample value', 7);
   *
   * // Within Constructor
   * this.cookie__set('cookie_name', 'sample value', 7);
   */
  cookie__set(cname, cvalue, exdays) {
    let d = new Date()
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000)
    let expires = 'expires=' + d.toUTCString()
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'
  }

  /**
   * Delete a JS cookie in the browser
   * - https://www.w3schools.com/js/js_cookies.asp
   *
   * @param {string} cname - Name of the cookie you'd like to set
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.init();
   *
   * // Within Constructor
   * $this.init();
   *
   * @example
   * // Delete the cookie from the user's browser by name
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.cookie__delete('cookie_name');
   *
   * // Within Constructor
   * this.cookie__delete('cookie_name');
   */
  cookie__delete(cname) {
    document.cookie =
      cname + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
  }

  /**
   * Get a cookie from the browser in JS
   * - https://www.w3schools.com/js/js_cookies.asp
   *
   * @param {string} cname - Name of the cookie to retrieve
   * @return {*} Value from cookie
   *
   * @example
   * // Save cookie value into JS variable for future use
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.cookie__get('cookie_name');
   *
   * // Within Constructor
   * this.cookie__get('cookie_name');
   */
  cookie__get(cname) {
    const name = cname + '='
    let decodedCookie = decodeURIComponent(document.cookie)
    const ca = decodedCookie.split(';')
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i]
      while (c.charAt(0) == ' ') {
        c = c.substring(1)
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length)
      }
    }
    return ''
  }

  /**
   * Check and console log a cookie from the browser
   * - https://www.w3schools.com/js/js_cookies.asp
   *
   * @param {string} cname - Name of the cookie you want to check
   * @param {boolean} isJSON - Flag to JSON parse cookie if expected value is JSON
   *
   * @example
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.cookie__check('cookie_name');
   * // Within Constructor
   * this.cookie__check('cookie_name');
   *
   * // If expected cookie value is JSON
   * fn__general.checkCookie('cookie_name', true);
   * // If expected cookie value is NOT JSON
   * fn__general.checkCookie('cookie_name');
   */
  cookie__check(cname, isJSON) {
    isJSON = typeof isJSON !== 'undefined' ? isJSON : false
    const cookie = this.cookie__get(cname)
    if (isJSON) {
      cookie = JSON.parse(cookie)
    }
    if (cookie !== '') {
      console.log(cname + ' cookie set: ', cookie)
    } else {
      console.log(cname + ' cookie is NOT set!')
    }
  }

  /**
   * You are leaving the site modal
   * */
  leaving_site__check() {
    document.querySelectorAll('a:not(.modaal)').forEach((link) => {
      var hostRegExp = new RegExp(window.location.host)

      if (!hostRegExp.test(link.href)) {
        // link.addEventListener('click', (event) => {
        //   alert('LEAVING??')
        //   event.preventDefault()
        //   event.stopPropagation()
        //   window.open(link.href, '_blank')
        // })
        jQuery(link).modaal({
          type: 'confirm',
          overlay_opacity: '.9',
          confirm_button_text: 'Confirm',
          confirm_cancel_button_text: 'Cancel',
          confirm_title: 'You are now leaving this site',
          confirm_content: '<p>Would you like to leave this site?</p>',
          confirm_callback: function () {
            // alert('you have confirmed this action')
            window.open(link.href, '_blank')
          },
          confirm_cancel_callback: function () {
            // alert('you have cancelled this action')
          },
        })
        this.addNoOpener(link)
        this.addNewTabMessage(link)
      }
    })
  }

  addNoOpener(link) {
    let linkTypes = (link.getAttribute('rel') || '').split(' ')
    if (!linkTypes.includes('noopener')) {
      linkTypes.push('noopener')
    }
    link.setAttribute('rel', linkTypes.join(' ').trim())
  }

  addNewTabMessage(link) {
    if (!link.querySelector('.sr-only')) {
      const isChildOfNav = this.isDescendantOfNav(link)
      if (isChildOfNav) {
        link.insertAdjacentHTML(
          'beforeend',
          `<svg role="presentation" class="icon__new-tab sr-only" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 6H14V14" stroke="#1A1818" stroke-width="1.5"></path>
          <path d="M14 6L6 14" stroke="#1A1818" stroke-width="1.5"></path>
          <rect x="0.25" y="0.25" width="19.5" height="19.5" rx="1.75" stroke="#1A1818" stroke-width="0.5"></rect>
          </svg>`
        )
      }

      link.insertAdjacentHTML(
        'beforeend',
        '<span class="sr-only">(opens in a new tab)</span>'
      )
    }
  }

  isDescendantOfNav(element) {
    if (!element) {
      return false // Element not found, stop recursion
    }
    if (element.tagName.toLowerCase() === 'nav') {
      return true // Found a <nav> ancestor
    }
    return this.isDescendantOfNav(element.parentElement) // Recursively check the parent
  }

  /**
   * Card Event Listers
   *
   * @example
   * // Init the toggle cards so whole card is clickable
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.card_listeners();
   *
   * // Within Constructor
   * this.card_listeners();
   */
  card_listeners = function (parentElement) {
    let cardTrigger = document.querySelectorAll('.card--clickable')
    if (parentElement) {
      cardTrigger = parentElement.querySelectorAll('.card--clickable')
    }
    if (cardTrigger.length) {
      /***** CARD EVENT *****/
      cardTrigger.forEach((trigger) => {
        trigger.addEventListener('keyup', function (event) {
          event.preventDefault()
          if (event.keyCode === 13) {
            trigger.click()
          }
        })
      })
    }
  }

    /**
   * Load Masonry Layout
   *
   * @example
   * // Init the toggle cards so whole card is clickable
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.masonry_layout();
   *
   * // Within Constructor
   * this.masonry_layout();
   */
    masonry_layout = function (parentElement) {
  
    }

  /**
   * Form Event Listers
   *
   * @example
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.form_listeners();
   *
   * // Within Constructor
   * this.form_listeners();
   */


  /**
   * Toggle Event Listers
   *
   * @example
   * // Init the toggle events for future use
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.toggle_listeners();
   *
   * // Within Constructor
   * this.toggle_listeners();
   */
  toggle_listeners = function () {
    const toggleTrigger = document.querySelectorAll('.toggle')
    if (toggleTrigger.length) {
      /***** TOGGLE EVENT *****/
      toggleTrigger.forEach((trigger) => {
        trigger.addEventListener('click', () => {
          let is_open = trigger.classList.contains('is-active')
          if (is_open) {
            // CLOSE TOGGLE
            trigger.classList.remove('is-active')
            // fn__ada.adaCloseToggle(trigger);
          } else {
            // OPEN TOGGLE
            trigger.classList.add('is-active')
            // fn__ada.adaOpenToggle(trigger);
          }
        })
      })
    }
  }

  /**
   * JS Slide down/up transition
   *
   * @example
   * // Init the toggle events for future use
   *
   * // Outside Constructor
   * var fn__general = new Functions__General();
   * fn__general.slide_in_out();
   *
   * // Within Constructor
   * this.slide_in_out();
   *
   * * note, this only works if the el has the following css rules:
   * el { transition: height 0.3s ease-in-out; overflow: hidden;} exact rules can change but this is necessary to animate
   * el:not(.is-active) {display: none;}
   */
  slide_in_out = function (el) {
    if (!el.classList.contains('is-active')) {
      el.classList.add('is-active')
      el.style.height = 'auto'

      let h = el.clientHeight + 'px'

      el.style.height = '0px'

      setTimeout(() => {
        el.style.height = h
      }, 0)
    } else {
      el.style.height = '0px'

      el.addEventListener(
        'transitionend',
        () => {
          el.classList.remove('is-active')
        },
        { once: true }
      )
    }
  }

  /**
   * Alter Form - alter the DOM of the form
   *
   * @example
   * this.alterform();
   */
  alterform = function () {
    // adding span for styling checkboxes/radios
    let $inputs = jQuery(
      '.frm_forms input[type="radio"], .frm_forms input[type="checkbox"]'
    )
    if ($inputs) {
      $inputs.each(function () {
        jQuery(this).after('<span></span>')
      })
    }
  }
}
