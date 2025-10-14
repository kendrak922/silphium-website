/**
 * Understory Header Navigation
 * Handles mobile navigation and forest effects for understory pages
 */

(function($) {
    'use strict';
    
    const UnderstoryHeader = {
        init: function() {
            if (!$('.header--understory').length) return;
            
            this.initMobileNav();
            this.initScrollEffects();
            this.initForestLayers();
        },
        
        initMobileNav: function() {
            const $toggle = $('.understory-nav-toggle');
            const $menu = $('.nav__menu--understory');
            
            $toggle.on('click', function() {
                $(this).toggleClass('active');
                $menu.toggleClass('active');
                $('body').toggleClass('nav-open');
            });
            
            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.nav--understory').length) {
                    $toggle.removeClass('active');
                    $menu.removeClass('active');
                    $('body').removeClass('nav-open');
                }
            });
            
            // Close menu when clicking on links
            $menu.find('a').on('click', function() {
                $toggle.removeClass('active');
                $menu.removeClass('active');
                $('body').removeClass('nav-open');
            });
        },
        
        initScrollEffects: function() {
            let lastScrollTop = 0;
            const $header = $('.header--understory');
            
            $(window).on('scroll', this.throttle(() => {
                const scrollTop = $(window).scrollTop();
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    $header.addClass('header--hidden');
                } else {
                    // Scrolling up
                    $header.removeClass('header--hidden');
                }
                
                lastScrollTop = scrollTop;
            }, 16));
        },
        
        initForestLayers: function() {
            if (!$('.understory-forest-bg').length) return;
            
            $(window).on('scroll', this.throttle(() => {
                const scrolled = $(window).scrollTop();
                const parallaxSpeed = 0.5;
                
                $('.forest-layer--1').css('transform', `translateX(${scrolled * parallaxSpeed * 0.1}px)`);
                $('.forest-layer--2').css('transform', `translateX(${scrolled * parallaxSpeed * 0.2}px)`);
                $('.forest-layer--3').css('transform', `translateX(${scrolled * parallaxSpeed * 0.3}px)`);
                $('.forest-layer--4').css('transform', `translateX(${scrolled * parallaxSpeed * 0.4}px)`);
            }, 16));
        },
        
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        UnderstoryHeader.init();
    });
    
})(jQuery);
