<?php

/*************************************************************
    CUSTOM POST TYPES
 *************************************************************/

// make parent nav item highlighted
add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2);
function add_current_nav_class($classes, $item)
{
    // Getting the current post details
    global $post;
    // Getting the post type of the current post
    $current_post_type = get_post_type_object(get_post_type($post->ID));
    $current_post_type_slug = $current_post_type->rewrite['slug'];
    // Getting the URL of the menu item
    $menu_slug = strtolower(trim($item->url));
    // If the menu item URL contains the current post types slug add the current-menu-item class
    if (strpos($menu_slug, $current_post_type_slug) !== false) {
        $classes[] = 'current-menu-item';
    }
    // Return the corrected set of classes to be added to the menu item
    return $classes;
}



/*************************************************************
    EXCERPTS
 *************************************************************/

// change the excerpt "more" tag
function new_excerpt_more($more)
{
    global $post;
    return '&hellip; </br> <a class="moretag" href="' . get_permalink($post->ID) . '">Read more</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// CUSTOM FIELD EXCERPT
function custom_field_excerpt($fieldName)
{
    global $post;
    $text = get_field($fieldName);
    if ('' != $text) {
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $excerpt_length = 20; // set the number of words here
        $excerpt_more = '...';
        $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    }
    $text = apply_filters('the_excerpt', $text);
    echo $text;
}

// create a shorter/longer excerpt
function get_my_excerpt()
{
    $text = get_the_content();
    $text = strip_shortcodes($text);
    $text = apply_filters('the_content', $text);
    $text = strip_tags($text);
    $excerpt_length = 30; // set the number of words here
    $excerpt_more = '...';
    $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    return $text;
}

/*************************************************************
    Disable Gutenberg Blocks
 *************************************************************/
/**
 *   disable Gutenberg for posts
 * */
add_filter('use_block_editor_for_post', '__return_false', 10);

/**
 *   disable Gutenberg for post types
 * */
add_filter('use_block_editor_for_post_type', '__return_false', 10);


/*************************************************************
    ACF - COLOR PICKER: Custom Theme Color Swatches
 *************************************************************/
function acf_colorPicker_swatches()
{
    ?>
    <script type="text/javascript">
        (function($) {
            acf.add_filter('color_picker_args', function(args, $field) {
                // add the hexadecimal codes here for the colors you want to appear as swatches
                args.palettes = [
                    '#F4E9DC',
					'#211B20',
					'#99D5C9',
					'#F7CCC4',
					'#FF8058',
					'#E8D94D',
					'#BAAE11',
                    '#54357B',
                    '#3AC1A7',
                    '#B997EF',
                    '#F8C218',
                ];
                // return colors
                return args;
            });
        })(jQuery);
    </script>
    <?php
}
add_action('acf/input/admin_footer', 'acf_colorPicker_swatches');
