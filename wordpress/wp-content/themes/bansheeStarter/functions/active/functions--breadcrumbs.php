<?php

/**
 * Filter: Add breadcrumb Links
 * - Append a link to the breadcrumbs supplied by Yoast's wpseo_breadcrumb_links filter
 */

//add post type archive to breadcrumbs
add_filter('wpseo_breadcrumb_links', 'bansheeStarter_breadcrumb_append_link');
function bansheeStarter_breadcrumb_append_link($links)
{
    global $post;

    if (is_single()) :
        $posttype = get_post_type();
        $breadcrumb = [];
        //add post type to breadcrumbs
        if ( $posttype == 'resource') {
            // $breadcrumb[] = array(
            //     'url' => site_url('/resources/'),
            //     'text' => 'Resources',
            // );
            return  array(
                array(
                    'url' => site_url('/resources/'),
                    'text' => 'Back to all resources',
                ),
                array()
            );
        }
        if ( $posttype == 'news') {
            return  array(
                array(
                    'url' => site_url('/news/'),
                    'text' => 'Back to all news',
                ),
                array()
            );
        }
        array_splice($links, 1, -2, $breadcrumb);

    endif;

    //remove "HOME"
    array_splice($links, 0, 1);

    return $links;
}


//SEO OUTPUT HELPERS
/**
 * make wrapper a list
 */
add_filter( 'wpseo_breadcrumb_output_wrapper', 'ss_breadcrumb_output_wrapper', 10, 1 );
function ss_breadcrumb_output_wrapper( $wrapper ) {
    $wrapper = 'ul';
    return $wrapper;
}

/**
 * Filter the output of Yoast breadcrumbs so each item is an <li> with schema markup
 */
function doublee_filter_yoast_breadcrumb_items( $link_output, $link ) {
	$from = 'span';
	$to = 'li';
	$new_link_output = str_replace( $from, $to, $link_output );
	return $new_link_output;
}
add_filter( 'wpseo_breadcrumb_single_link', 'doublee_filter_yoast_breadcrumb_items', 10, 2 );


/**
 * Filter the output of Yoast breadcrumbs to remove <span> tags added by the plugin
 */
function doublee_filter_yoast_breadcrumb_output( $output ){
	$from = '<span>';
	$to = '</span>';
	$output = str_replace( $from, $to, $output );
	return $output;
}
add_filter( 'wpseo_breadcrumb_output', 'doublee_filter_yoast_breadcrumb_output' );

//seperator
function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
    return '';
};
add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);

//output
function ss_breadcrumb_output($output) {
    $output = '<nav aria-labelledby="breadcrumbs" >'.$output.'</nav>';
    return $output;
}
add_filter( 'wpseo_breadcrumb_output', 'ss_breadcrumb_output' );