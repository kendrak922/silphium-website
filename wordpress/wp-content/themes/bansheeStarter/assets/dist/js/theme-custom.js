/**
 * Global JS variables set for use across JS files
 * Called first in Main.js, making the variables globally available
 */

class Global {
  constructor() {
    // Breakpoints
    this.breakpoints = {
      mobile: 768,
      tablet: 1024,
      laptop: 1200,
      desktop: 1400
    }
    // General
    this.general = {
      isMobile: false,
      window_height: 0,
      window_width: 0,
      device: '',
      header_height: this.window_width > this.breakpoints.mobile ? 70 : 80, //make sure this value matches the matching sass variable
      wp_adminBar_height: this.window_width > this.breakpoints.mobile ? 32 : 46,
      slick_default: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          variableWidth: false,
          focusOnSelect: true,
          draggable: true,
          responsive: [
            {
              breakpoint: this.breakpoints.laptop,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: this.breakpoints.mobile,
              settings: {
                slidesToShow: 1,
                arrows: false
              }
            }
          ]
      },
    }
    // Active Class Vars
    this.actives = {
      search: 'active--search',
      mobile_menu: 'active--mobileMenu'
    }
    // AJAX Vars
    this.ajax = {
      isAjax: false,
      activeRequests: 0,
      overlay: '<div class="ajax__overlay"><div class="ajax__overlay--icon"></div></div>',
      loadmore: false
    }
  }
}
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

/**
 * General JS functions used across all templates in the theme
 * - Class initialized in: ./assets/src/js/__global.js
 * 
 * @constructor
 */
class Functions__Ajax { 
    constructor(context = "main") {
        this.context = context; //to call this class in multiple places with an obvious semantic context change
        this.php_vars = php_vars;
    }

    /**
     * Toggle the AJAX overlay and global status flag of AJAX
     * 
     * @param {boolean} status - is AJAX active?
     * @example 
     * // Outside Constructor 
     * var fn__ajax = new Functions__Ajax();
     * fn__ajax.ajax_status(true);
     * 
     * // Within Constructor
     * this.ajax_success(true);
     */
    ajax_status(status){
        // set ajax status
        global.ajax.isAjax = status;
        // update DOM to illustrate status
        const body = document.querySelector('body');
        if(global.ajax.isAjax){
            // // if overlay does not exist, add it
            if( !document.querySelector('.ajax__overlay') ){
                let overlay = document.createElement('div');
                overlay.innerHTML = global.ajax.overlay;
                body.prepend(overlay);
            }

            body.classList.add('ajax--active');
        }else{
            body.classList.remove('ajax--active');
        }
    };


    /**
     * AJAX GLOBAL
     * General success function for ajax handler
     *
     * @param {object} ajaxdata - AJAX response data
     * @example 
     * // Outside Constructor 
     * var fn__ajax = new Functions__Ajax();
     * fn__ajax.ajax_success(ajaxdata);
     * 
     * // Within Constructor
     * this.ajax_success(ajaxdata);
     */
    ajax_success(ajaxdata){
        console.log('');
        console.log('AJAX SUCCESS: ', JSON.parse(ajaxdata) );

    };


    /**
     * AJAX GLOBAL
     * General error function for ajax handler
     * 
     * @param {object} ajaxdata - AJAX response data
     * @param {*} textStatus - Text status of error
     * @param {*} errorThrown - error code thrown
     * @example 
     * // Outside Constructor 
     * var fn__ajax = new Functions__Ajax();
     * fn__ajax.ajax_error(ajaxdata, textStatus, errorThrown);
     * 
     * // Within Constructor
     * this.ajax_error(ajaxdata, textStatus, errorThrown);
     */
    ajax_error(ajaxdata, textStatus, errorThrown){
        console.log('');
        console.log('xxx AJAX ERROR xxx');
        console.log('ajaxdata: ', ajaxdata);
        console.log('textStatus: ', textStatus);
        console.log('errorThrown: ', errorThrown);
    };


    /**
     * AJAX GLOBAL
     * General complete function for ajax handler
     * - Ends the ajax_status method
     * 
     * @param {object} ajaxdata - AJAX response data
     * @param {*} textStatus 
     * @example 
     * // Outside Constructor 
     * var fn__ajax = new Functions__Ajax();
     * fn__ajax.ajax_complete(ajaxdata, textStatus);
     * 
     * // Within Constructor
     * this.ajax_complete(ajaxdata, textStatus);
     */
    ajax_complete(ajaxdata, textStatus){
        console.log('AJAX COMPLETE: ', ajaxdata);
        console.log('textStatus: ', textStatus);

        this.ajax_status(false);
    };


    /**
     * AJAX FUNCTION
     * Dynamic function to pass any parameter for ajax settings
     *
     * @param {object} settings - object - has parameters to fill out and complete and AJAX request
     * @param {object} fn - an object with pre-named functions for specific responses to the ajax request
     * @param {boolean} multipleReq - allow multiple AJAX requests to be called at the same time
     * @example 
     * var fn__ajax = new Functions__Ajax();
     * var ajax_settings = {
     *     data: {
	 *         action: 'name_of_callback_function',
	 *         custom_data: {
     *           // custom data to pass to functions.php function
     *         } 
	 *     }
	 * };
     * // Define callback functions
     * var fn = {};
     * fn.success = function(ajaxdata){
     *     ajaxdata = JSON.parse( ajaxdata );
     *     // Custom SUCCESS handler for this specific ajax call
     * };
     * fn.error = function(ajaxdata){
     *     ajaxdata = JSON.parse( ajaxdata );
     *     // Custom ERROR handler for this specific ajax call
     * };
     * fn.complete = function(ajaxdata){
     *     ajaxdata = JSON.parse( ajaxdata );
     *     // Custom COMPLETE handler for this specific ajax call
     * };
     * // Fire AJAX call
     * fn__ajax.ajax_wp(ajax_settings, fn, true);
     */
    ajax_wp(settings, fn, multipleReq){
        // default URL to global plugin AJAX URL
        fn = typeof fn !== 'undefined' ? fn : {};
        multipleReq = typeof multipleReq !== 'undefined' ? multipleReq : false;
        settings.url = (settings.url ? settings.url : this.php_vars.ajax_url);

        // console.log('Esteem Plugin AJAX - settings: ',settings);
        // Check if AJAX is already active
        if( !global.ajax.isAjax || multipleReq ) {
            this.ajax_status(true);

            // IF 'before_start' fn passed through, fire before running AJAX
            if( "before_start" in fn ){
                fn.before_start();
            }

            // Start AJAX
            jQuery.ajax({
                url: settings.url,
                _ajax_nonce: settings.url,
                data: (settings.data) ? settings.data : null,
                method: (settings.method) ? settings.method : 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', this.php_vars.rest_nonce);
                },
                success: function (ajaxdata) {
                    // Global Success function
                    this.ajax_success(ajaxdata);
                    // IF 'success' fn passed through
                    if( "success" in fn ){
                        fn.success(ajaxdata);
                    }
                }, /* close: ajax success */

                error: function (ajaxdata, textStatus, errorThrown) {
                    // Global Error function
                    this.ajax_error(ajaxdata, textStatus, errorThrown);
                    // IF 'error' fn passed through
                    if( "error" in fn ){
                        fn.error(ajaxdata);
                    }
                }, /* close: ajax error */

                complete: function (ajaxdata, textStatus) {
                    // Global Complete function
                    this.ajax_complete(ajaxdata, textStatus);
                    // IF 'complete' fn passed through
                    if( "complete" in fn ){
                        fn.complete(ajaxdata);
                    }
                }/* close: ajax complete */

            }); /* close: AJAX */
        } /* close: active ajax flag check */
    };
}
/* Animation related functions
 * - Class initialized in: ./assets/src/js/main.js
 * 
 * @constructor
 */
class Functions__Animation { 

    constructor(context = "main") {
        this.context = context; //to call this class in multiple places with an obvious semantic context change
        this.animateoffset = 100;
    }
    
    /*========================================================================
        Animation on scroll
    ========================================================================*/
    init() {
        this.event_listeners();

        //trigger scroll event
        if (window.scrollY) {
            window.scrollTo(window.scrollX, window.scrollY - 1);
        } else {
            window.scrollTo(window.scrollX, window.scrollY + 1);
        }
    } 

    /* HANDLERS */
    event_listeners(){
        window.addEventListener('load', this.active_animation_check());
        window.addEventListener('scroll', (event) => this.active_animation_check());
    }

    /* Check Position */
    active_animation_check(){
        this.elems = document.querySelectorAll('.u-animation:not(.u-animation--active)');
        for (var i = 0; i < this.elems.length; i++) {
            var posFromTop = this.elems[i].getBoundingClientRect().top
            if (posFromTop - global.general.window_height + this.animateoffset <= 0) {
                this.elems[i].classList.add('u-animation--active')
            }
        }
    }

}
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

/**
 * General JS functions used across all templates in the theme
 * - Class initialized in: ./assets/src/js/__global.js
 * HTML: header.php
 * SCSS: assets/src/sass/global/_header.scss
 *
 * @constructor
 */
class Functions__Header {
  constructor(context = 'main') {
    this.context = context //to call this class in multiple places with an obvious semantic context change

    const header = document.querySelector('header.header')
    if (header) {
      var bound_header = header.getBoundingClientRect()
      const headerFixed = header.querySelector('.header--fixed')
      var bound_headerfixed = header.getBoundingClientRect()
      const alertBanner = header.querySelector('.alert-banner')
      var bound_alert = alertBanner ? alertBanner.getBoundingClientRect() : null

      this.alertBannerHeight =
        alertBanner !== null
          ? bound_alert.height > 0
            ? bound_alert.height
            : 60
          : 0
      this.headerHeight = bound_headerfixed.height
      this.headerOffset = bound_header.top + window.scrollY
      this.totalHeight =
        this.headerHeight + this.headerOffset + this.alertBannerHeight
    }

      /// change z-index when there is a hero banner
      const homeHeroImage = document.querySelector('.hero-banner');
      const homeHero = document.querySelector('.hero-banner.bgMedia');
      const pageHero = document.querySelector('.page-banner');
      const body = document.querySelector('body');
        if(homeHero){
          body.classList.add('has-hero-banner');
        }

        if(homeHeroImage){
          body.classList.add('has-hero-banner__image');
        }

        if(pageHero){
          body.classList.add('has-hero-banner');
        }
  
  }

  /**
   * Header event listeners
   *
   * @example
   * // Outside Constructor
   * var fn__header = new Functions__Header();
   * fn__header.event_listeners();
   *
   * // Within Constructor
   * $this.event_listeners();
   */
  event_listeners() {


    const toggleBtn = document.querySelector('.menu-toggle');

    if(toggleBtn){
      toggleBtn.addEventListener('click', (e) => {
        if(toggleBtn.classList.contains('menu-open')){
          toggleBtn.classList.remove('menu-open');
          toggleBtn.ariaExpanded = false;
          fn__ada.setFocusContext(false);
          document.body.classList.remove(global.actives.mobile_menu)
        }else {
          toggleBtn.classList.add('menu-open');
          document.body.classList.add(global.actives.mobile_menu)
          toggleBtn.ariaExpanded = true;
          fn__ada.setFocusContext(true);
        }
      })
    }



    const close_triggers = document.querySelectorAll('.menu-trigger--close')
    const open_triggers = document.querySelectorAll('.menu-trigger--open')
    const subnav_triggers = document.querySelectorAll('[data-btn="toggle"]')
    const header__nav = document.querySelector('.menu-wrapper--main')
    const header__el = document.querySelector('.header')
    const back_triggers = document.querySelectorAll('.sub-menu__back')

    


    // Mobile menu - open
    open_triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        if (document.body.classList.contains(global.actives.mobile_menu)) {
          // CLOSE MENU
          trigger.classList.remove('is-active')
          document.body.classList.remove(global.actives.mobile_menu)
          trigger.ariaExpanded = 'false'
          header__nav.ariaHidden = 'true'
          // fn__ada.setFocusContext(false)
        } else {
          // OPEN MENU
          trigger.classList.add('is-active')
          document.body.classList.add(global.actives.mobile_menu)
          trigger.ariaExpanded = 'true'
          header__nav.ariaHidden = 'false'
          // fn__ada.setFocusContext(header__el)
        }

        // Toggle scroll lock
        fn__general.scroll_lock_toggle()
      })
    })

    // Mobile menu - close
    close_triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        // CLOSE MENU
        trigger.classList.remove('is-active')
        document.body.classList.remove(global.actives.mobile_menu)
        header__nav.ariaHidden = 'true'
        fn__ada.setFocusContext(false)

        // Toggle scroll lock
        fn__general.scroll_lock_toggle()

        // Auto focus menu open button - only desktop for keyboard nav
        open_triggers.forEach((trigger) => {
          trigger.classList.remove('is-active')
          trigger.ariaExpanded = 'false'
          if (trigger.classList.contains('main-open')) {
            trigger.focus()
          }
        })
      })
    })

    // Main menu - dropdowns
    subnav_triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        let menuItem = trigger.parentElement.parentElement,
          subNav = menuItem.querySelector('.sub-menu'),
          toggle = menuItem.querySelector('.menu-item__subnav-toggle')

        menuItem.classList.toggle('is-active')

        if (menuItem.classList.contains('is-active')) {
          subNav.ariaHidden = 'false'
          toggle.ariaExpanded = 'true'
          var menuItems = subNav.querySelector('.sub-menu__back')
          setTimeout(function () {
            menuItems.focus()
          }, 300)
        } else {
          subNav.ariaHidden = 'true'
          toggle.ariaExpanded = 'false'
          toggle.focus()
        }
      })
    })

    // Main menu - dropdowns close
    back_triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        let menuItem = trigger.parentElement.parentElement.parentElement,
          subNav = menuItem.querySelector('.sub-menu'),
          toggle = menuItem.querySelector('.menu-item__subnav-toggle')

        menuItem.classList.toggle('is-active')

        if (menuItem.classList.contains('is-active')) {
          subNav.ariaHidden = 'false'
          toggle.ariaExpanded = 'true'
        } else {
          subNav.ariaHidden = 'true'
          toggle.ariaExpanded = 'false'
          toggle.focus()
        }
      })
    })

    // Search Dropdown - open/close trigger
    const searchTrigger = document.querySelector('.header__icon--search')
    const searchField = document.querySelector(
      '.header .searchform input[type="text"]'
    )
    if (searchTrigger) {
      searchTrigger.addEventListener('click', () => {
        let is_open = document.body.classList.contains(global.actives.search)
        if (is_open) {
          // CLOSE
          document.body.classList.remove(global.actives.search)
        } else {
          // OPEN
          document.body.classList.add(global.actives.search)
          // focus the search after css animation delay
          setTimeout(() => {
            searchField.focus()
          }, 625)
        }
      })
    }

    // Alert Banner - open/close
    const alertTrigger = document.getElementById('alertBtn')
    if (alertTrigger) {
      alertTrigger.addEventListener('click', () => {
        let msg = document.getElementById('alertMsg').textContent

        document.getElementById('alertBanner').classList.remove('is-active')
        document.body.classList.remove('has-alert')

        // set cookie to remember this message. If message changes we want to show it again
        fn__general.cookie__set('alertMessage', JSON.stringify(msg), 365)
        // set cookie to hide the alert bar for 30 days
        fn__general.cookie__set('alertActive', false, 30)
      })
    }

    // Scroll Event Listener
    // scroll after first XXpx -> shrink header
    // scrolling down, slide out of sight
    // scrolling up, slide back into sight.

    const header = document.querySelector('.header--fixed')
    let lastKnownScrollPosition =
        window.pageYOffset || document.documentElement.scrollTop,
      threshold = 10

    // document.addEventListener('scroll', (e) => {
    //     let st = window.pageYOffset || document.documentElement.scrollTop

    //     // if we're scrolling down, hide the header
    //     if (st + this.headerOffset > lastKnownScrollPosition) {
    //         // Scrolling down
    //         console.log("down")
    //         if(st < this.totalHeight * 1.75 ) {
    //             //stay in place
    //             header.classList.remove('animate');
    //             header.style.top = - st + this.headerOffset + 'px'
    //         } else if (st > lastKnownScrollPosition + threshold) {
    //             //must scroll ENOUGH to hide nav if showing
    //             header.classList.add('animate')
    //             header.style.top = '-' + this.totalHeight + 'px'
    //         } else {
    //             header.classList.add('animate')
    //         }
    //     } else{
    //         // Scrolling up
    //         if (!header.classList.contains("animate")) {
    //             header.style.top = - st + this.headerOffset + 'px';
    //         }else{
    //             header.classList.add('animate');
    //             header.style.top = this.headerOffset + 'px';
    //         }
    //     }

    //     // shrink the header if it's past the initial height
    //     if(st > this.headerHeight * 1.75) {
    //         header.classList.add('header--shrink')
    //     } else {
    //         header.classList.remove('header--shrink')
    //     }

    //     lastKnownScrollPosition = st <= 0 ? this.headerOffset : st + this.headerOffset; // For Mobile or negative scrolling

    // }, false)
  }

  /**
   * Alert banner initation
   *
   * @example
   * // Outside Constructor
   * var fn__header = new Functions__Header();
   * fn__header.alert_banner();
   *
   * // Within Constructor
   * $this.alert_banner();
   */
  alert_banner = function () {
    const alertBanner = document.getElementById('alertBanner'),
      currentMessage = document.getElementById('alertMsg')
        ? document.getElementById('alertMsg').textContent
        : ''

    // todo : remove these functions - this is for testing only
    // fn__general.cookie__check('alertActive')
    // fn__general.cookie__check('alertMessage')

    // check to see if there's a new message, if so show the banner and delete the cookie
    if (
      fn__general.cookie__get('alertMessage') !== JSON.stringify(currentMessage)
    ) {
      fn__general.cookie__delete('alertActive')
    }

    // if the banner is active, slide it down.
    if (alertBanner && fn__general.cookie__get('alertActive') !== 'false') {
      alertBanner.classList.add('is-active')
      document.body.classList.add('has-alert')
    } else {
      if (alertBanner) alertBanner.remove()
      this.alertBannerHeight = 0
      this.totalHeight = this.headerHeight + this.headerOffset
    }
  }




}

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

const global = new Global()
const fn__general = new Functions__General()
const fn__header = new Functions__Header()
const fn__ada = new Functions__ADA()
const fn__ajax = new Functions__Ajax()
const fn__animation = new Functions__Animation()
const fn__scroll = new Functions__Scroll()

/* ========================================================================
        DOCUMENT READY
========================================================================*/
/* For more info on this event listener and other updated JS functions, see this snippets post: https://snippets.l05.project-qa.com/2022/01/10/es6-in-the-wp-starter-theme/ */
document.addEventListener('DOMContentLoaded', () => {
  /*========================================================================
                    GENERAL FUNCTIONS
     * html:    various files throughout theme
     * js:      /assets/src/js/_functions--general.js
     * scss:    various files and classes throughout theme
     ========================================================================*/
  // Detect device/browser/mobile - add class to body
  fn__general.detect__mobile()
  fn__general.detect__device()
  // Calculate initial values on doc ready
  fn__general.update_global_vals()
  fn__general.toggle_listeners()
  fn__general.leaving_site__check()
  fn__general.card_listeners()
  fn__general.alterform()

  /*========================================================================
                    HEADER FUNCTIONS
     * html:    header.php
     * js:      /assets/src/js/_functions--header.js
     * scss:    /assets/src/sass/global/_header.scss
     ========================================================================*/
  fn__header.event_listeners()

  /*========================================================================
                    _____ FUNCTIONS
     * html:    file location...
     * js:      /assets/src/js/_functions-- .js
     * scss:    /assets/src/sass/ .scss
     ========================================================================*/
  //  fn__myCustomClass.init();
  fn__animation.init()

  jQuery('.modaal-video').modaal({
    type: 'video',
    overlay_opacity: '.9',
  })

  jQuery('.image--modaal').modaal({
    type: 'image',
    overlay_opacity: '.9',
  })
})

/*========================================================================
        WINDOW LOAD
========================================================================*/
window.addEventListener('load', () => {
  // ADA
  fn__ada.iframes()
  fn__ada.footnotes()
}) // end: Window Load - no-conflict

/*========================================================================
        WINDOW RESIZE
========================================================================*/
window.addEventListener('resize', (e) => {
  /***** GLOBAL UPDATES *****/
  fn__general.update_global_vals()

  // ADA
  fn__ada.iframes()
}) // end: window resize

// Attach the setActiveLink function to the scroll event
window.addEventListener('scroll', (e) => {
  fn__scroll.setActiveLink()
  fn__scroll.setScrollBodyClass()
})
