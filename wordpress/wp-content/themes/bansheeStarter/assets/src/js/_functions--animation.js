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