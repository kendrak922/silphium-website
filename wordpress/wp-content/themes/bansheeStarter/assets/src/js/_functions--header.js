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
