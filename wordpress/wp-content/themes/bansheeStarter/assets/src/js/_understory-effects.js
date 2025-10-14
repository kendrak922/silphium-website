/**
 * Understory Page Effects
 * Handles parallax, scroll effects, and ambient sounds
 */

(function($) {
    'use strict';
    
    const UnderstoryEffects = {
        init: function() {
            if (!$('.understory-page').length) return;
            
            this.initParallax();
            this.initScrollEffects();
            this.initAmbientSounds();
        },
        
        initParallax: function() {
            if (!$('.understory-parallax').length) return;
            
            $(window).on('scroll', this.throttle(() => {
                const scrolled = $(window).scrollTop();
                const parallaxSpeed = 0.5;
                
                $('.forest-layer--1').css('transform', `translateX(${scrolled * parallaxSpeed * 0.1}px)`);
                $('.forest-layer--2').css('transform', `translateX(${scrolled * parallaxSpeed * 0.2}px)`);
                $('.forest-layer--3').css('transform', `translateX(${scrolled * parallaxSpeed * 0.3}px)`);
                $('.forest-layer--4').css('transform', `translateX(${scrolled * parallaxSpeed * 0.4}px)`);
            }, 16));
        },
        
        initScrollEffects: function() {
            if (!$('.understory-page[data-scroll-effects="true"]').length) return;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            document.querySelectorAll('.understory-page .block').forEach(block => {
                observer.observe(block);
            });
        },
        
        initAmbientSounds: function() {
            if (!$('.understory-page[data-ambient-sounds="true"]').length) return;
            
            let audioContext = null;
            let isPlaying = false;
            
            $('.sound-toggle').on('click', function() {
                if (!audioContext) {
                    // Initialize audio context on first user interaction
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                
                if (isPlaying) {
                    this.stopAmbientSounds();
                    $(this).removeClass('playing');
                    isPlaying = false;
                } else {
                    this.playAmbientSounds();
                    $(this).addClass('playing');
                    isPlaying = true;
                }
            }.bind(this));
        },
        
        playAmbientSounds: function() {
            // Placeholder for ambient sound implementation
            console.log('Playing ambient forest sounds...');
        },
        
        stopAmbientSounds: function() {
            // Placeholder for stopping ambient sounds
            console.log('Stopping ambient forest sounds...');
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
        UnderstoryEffects.init();
    });
    
})(jQuery);