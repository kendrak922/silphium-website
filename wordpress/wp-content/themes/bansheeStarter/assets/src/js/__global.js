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