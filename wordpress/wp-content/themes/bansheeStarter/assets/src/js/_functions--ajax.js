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