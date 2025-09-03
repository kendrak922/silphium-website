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
