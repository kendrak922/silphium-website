<?php

global $post;

/*************************************************************
    ATOMIC DESIGN SUPPORT
 *************************************************************/
// Composer autoload.
require_once __DIR__ . '/vendor/autoload.php';

// Run the theme setup.
add_filter(
    'loader_directories',
    function ($directories) {
        $directories[] = get_template_directory() . '/template-parts/components';
        return $directories;
    }
);
add_filter(
    'loader_alias',
    function ($alias) {
        $alias['atom']     = 'atoms';
        $alias['molecule'] = 'molecules';
        $alias['organism'] = 'organisms';
        $alias['template'] = 'templates';

        return $alias;
    }
);

/*************************************************************
    GLOBAL PHP VARIABLE
 *************************************************************/
// Define theme global variable
global $themeGlobals;
$themeGlobals = [
    'namespace' => 'theme-',
    'guten_category' => '',
    'text_domain' => 'theme-custom',
    'theme_url' => get_stylesheet_directory_uri(), // Absolute path to theme directory (URL)
    'theme_rel' => get_stylesheet_directory(), // Relative path to theme directory
    'ajax_url' => admin_url('admin-ajax.php'), // Localized AJAX URL
    'rest_url' => esc_url_raw(rest_url()),
];
$themeGlobals['guten_category'] = $themeGlobals['namespace'] . 'blocks'; // Using the namespace prefix used throughout the theme, create the slug used for our Gutenberg Category


/*************************************************************
    ENQUEUE SCRIPTS AND STYLES
 *************************************************************/
// for documentation and a list of scripts that are pre-registered by wordpress see https://developer.wordpress.org/reference/functions/wp_enqueue_script
// for a quick overview read this http://www.wpbeginner.com/wp-tutorials/how-to-properly-add-javascripts-and-styles-in-wordpress

function theme_enqueue_assets()
{
    // Global Vars
    global $themeGlobals;

    /**********
 * DECLARE VARS 
**********/
    // Asset Paths
    $paths = [
        'styles_vendor' => '/assets/dist/css/' . $themeGlobals['namespace'] . 'vendor.min.css',
        'styles_custom' => '/assets/dist/css/' . $themeGlobals['namespace'] . 'custom.css',
        'scripts_vendor' => '/assets/dist/js/' . $themeGlobals['namespace'] . 'vendor.min.js',
        'scripts_custom' => '/assets/dist/js/' . $themeGlobals['namespace'] . 'custom.js',
        'scripts_blocks' => '/assets/dist/js/' . $themeGlobals['namespace'] . 'custom-blocks.js',
    ];
    // WP Asset Handles / IDs
    $handles = [
        'styles_vendor' => $themeGlobals['namespace'] . 'vendor-styles',
        'styles_custom' => $themeGlobals['namespace'] . 'custom-styles',
        'scripts_vendor' => $themeGlobals['namespace'] . 'vendor-scripts',
        'scripts_custom' => $themeGlobals['namespace'] . 'custom-scripts',
        'scripts_blocks' => $themeGlobals['namespace'] . 'custom-block-scripts',
    ];
    // Enqueue Dependencies
    $dependencies = [
        'styles_vendor' => null,
        'styles_custom' => null,
        'scripts_vendor' => array('jquery'),
        'scripts_custom' => array('jquery'),
        'scripts_blocks' => array('jquery'),
    ];

    /**********
 * ENQUEUE ASSETS 
**********/

    /***
 * Vendor Styles 
***/
    // if file exists, register & enqueue
    if (file_exists($themeGlobals['theme_rel'] . $paths['styles_vendor'])) {
        // Update dependencies
        $dependencies['styles_custom'] = array($handles['styles_vendor']);
        $dependencies['styles_blocks'] = array($handles['styles_vendor']);
        // Enqueue Asset
        wp_register_style($handles['styles_vendor'], $themeGlobals['theme_url'] . $paths['styles_vendor'], $dependencies['styles_vendor'], filemtime($themeGlobals['theme_rel'] . $paths['styles_vendor']), false);
        wp_enqueue_style($handles['styles_vendor']);
    }

    /***
 * Custom Global Styles 
***/
    // if file exists, register & enqueue
    if (file_exists($themeGlobals['theme_rel'] . $paths['styles_custom'])) {
        // Enqueue Asset
        wp_register_style($handles['styles_custom'], $themeGlobals['theme_url'] . $paths['styles_custom'], $dependencies['styles_custom'], filemtime($themeGlobals['theme_rel'] . $paths['styles_custom']), false);
        wp_enqueue_style($handles['styles_custom']);
    }

    /***
 * Custom Font Styles 
***/
    wp_register_style('styles_fonts', "https://fonts.googleapis.com/css2?family=Baumans&family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&family=Parisienne&family=Petit+Formal+Script&family=Send+Flowers&display=swap");

    wp_enqueue_style('styles_fonts');

    /***
 * Vendor Scripts 
***/
    // if file exists, register & enqueue
    if (file_exists($themeGlobals['theme_rel'] . $paths['scripts_vendor'])) {
        // Update dependencies
        $dependencies['scripts_custom'] = array('jquery', $handles['scripts_vendor']);
        $dependencies['scripts_blocks'] = array('jquery', $handles['scripts_vendor']);
        // Enqueue Asset
        wp_register_script($handles['scripts_vendor'], $themeGlobals['theme_url'] . $paths['scripts_vendor'], $dependencies['scripts_vendor'], filemtime($themeGlobals['theme_rel'] . $paths['scripts_vendor']), true);
        wp_enqueue_script($handles['scripts_vendor']);
    }

    /***
 * Custom Scripts 
***/
    // if file exists, register & enqueue
    if (file_exists($themeGlobals['theme_rel'] . $paths['scripts_custom'])) {
        // Enqueue Asset
        wp_register_script($handles['scripts_custom'], $themeGlobals['theme_url'] . $paths['scripts_custom'], $dependencies['scripts_custom'], filemtime($themeGlobals['theme_rel'] . $paths['scripts_custom']), true);
        wp_enqueue_script($handles['scripts_custom']);
        // Pass variables from PHP into JS - accessible from the "php_vars" JS object: console.log('php_vars: ', php_vars)
        wp_localize_script(
            $handles['scripts_custom'], 'php_vars', array(
            'ajax_url' => $themeGlobals['ajax_url'],
            'rest_url' => $themeGlobals['rest_url'],
            'rest_nonce' => wp_create_nonce('wp_rest'),
            )
        );
    }

    /***
 * Custom Block Scripts 
***/
    // if file exists, register & enqueue
    if (file_exists($themeGlobals['theme_rel'] . $paths['scripts_blocks'])) {
        // Enqueue Asset
        wp_register_script($handles['scripts_blocks'], $themeGlobals['theme_url'] . $paths['scripts_blocks'], $dependencies['scripts_blocks'], filemtime($themeGlobals['theme_rel'] . $paths['scripts_blocks']), true);
        wp_enqueue_script($handles['scripts_blocks']);
        // Pass variables from PHP into JS - accessible from the "php_vars" JS object: console.log('php_vars: ', php_vars)
        wp_localize_script(
            $handles['scripts_blocks'], 'php_vars', array(
            'ajax_url' => $themeGlobals['ajax_url'],
            'rest_url' => $themeGlobals['rest_url'],
            'rest_nonce' => wp_create_nonce('wp_rest'),
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets');


/*****
 * ADMIN ASSETS 
 *****/
function admin_theme_assets()
{
    // Global Variables
    global $themeGlobals;

    /*****
 * Gutenberg Block Editor 
*****/
    if (is_admin()) {
        // Custom Block Styles and Scripts
        add_action('admin_enqueue_scripts', 'theme_enqueue_assets');

        /**********
 * DECLARE VARS 
**********/
        // Asset Paths
        $paths = [
            'styles_admin' => '/assets/dist/css/admin-styles.css',
        ];
        // WP Asset Handles / IDs
        $handles = [
            'styles_admin' => $themeGlobals['namespace'] . 'admin-styles',
        ];
        // Enqueue Dependencies
        $dependencies = [
            'styles_admin' => null,
        ];

        /**********
 * ENQUEUE ASSETS 
**********/

        /***
 * Gutenberg Block Editor Admin Styles 
***/
        // if file exists, register & enqueue
        if (file_exists($themeGlobals['theme_rel'] . $paths['styles_admin'])) {
            // Enqueue Asset
            wp_register_style($handles['styles_admin'], $themeGlobals['theme_url'] . $paths['styles_admin'], $dependencies['styles_admin'], filemtime($themeGlobals['theme_rel'] . $paths['styles_admin']), false);
            wp_enqueue_style($handles['styles_admin']);
        }
    }
}
add_action('enqueue_block_editor_assets', 'admin_theme_assets');


/*************************************************************
    CONSOLE LOG PHP
 *************************************************************/
/** 
 * PHP CONSOLE LOG 
 * 
 * @param $data  - PHP variable to log to JS console
 * @param $label - String label to prefix the JS console log
 */
function debug_to_console($data, $label = 'PHP Debug to Console')
{
    echo '<script type="text/javascript" data-debug="true">console.log("' . $label . ': ",' . json_encode($data) . ');</script>';
}

/**
 * Clean String
 * - https://stackoverflow.com/a/14114419
 */
function theme_clean_string($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}


/*************************************************************
    REGISTER MENUS
 *************************************************************/
function theme_register_menus()
{

    register_nav_menus(
        array(
            'main-menu' => __('Main Menu'),
            'main-menu--two' => __('Main Menu Two'), 
            // 'utility-menu' => __('Utility Menu'),
            'footer-menu' => __('Footer Menu'),
            // 'footer-menu2' => __('Footer Menu 2'),
        )
    );
}
add_action('init', 'theme_register_menus');


/*************************************************************
    REGISTER SIDEBAR
 *************************************************************/
function theme_widgets_register()
{

    register_sidebar(
        array(
        'name'          => 'Sidebar One',
        'id'            => 'sidebar_one',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
        'description'   => ''
        )
    );
}
add_action('widgets_init', 'theme_widgets_register');


/*************************************************************
    ADD THEME SUPPORT
 *************************************************************/
function theme_add_support()
{
    // Excerpts 
    add_theme_support('excerpt');    
    add_post_type_support('page', 'excerpt');
    // Featured Images
    add_theme_support('post-thumbnails');
}
add_action('init', 'theme_add_support');

// Remove Tags taxonomy
function theme_remove_tags()
{
    global $wp_taxonomies;
    $tax = 'post_tag';
    if (taxonomy_exists($tax)) {
        unset($wp_taxonomies[$tax]);
    }
}
// add_action( 'init', 'theme_remove_tags' ); 


/*************************************************************/
/*  ADVANCED CUSTOM FIELDS - THEME OPTIONS                   */
/*************************************************************/
// add options page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
        'page_title'    => 'Theme General Options',
        'menu_title'    => 'Theme Options',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'icon_url' => 'dashicons-admin-home',
        'position' => 2
        )
    );
}


/*************************************************************
                THEME ELEMENTS
 *************************************************************/
/**
 * ELEMENT Includes - all files within the sub-directory: /functions/active
 * - Create individual files for each element function
 */
$elements = scandir(__DIR__ . '/functions/active');
foreach ($elements as $item) {
    if ($item[0] != '.') {
        include __DIR__ . '/functions/active/' . $item;
    }
}


/*************************************************************
    REGISTER GUTENBERG BLOCKS
 *************************************************************/
// REGISTER CUSTOM BLOCK CATEGORY
function theme_register_block_categories($categories)
{
    global $themeGlobals;
    $category_slugs = wp_list_pluck($categories, 'slug');
    // Add category if its not already there
    return in_array($themeGlobals['guten_category'], $category_slugs, true) ? $categories : array_merge(
        $categories,
        array(
            array(
                'slug'  => $themeGlobals['guten_category'],
                'title' => __('Theme Blocks', $themeGlobals['text_domain']),
                'icon'  => 'admin-home',
            ),
        )
    );
}
add_filter('block_categories_all', 'theme_register_block_categories', 10, 2);



// REGISTER CUSTOM BLOCKS
function register_acf_block_types()
{
    global $themeGlobals;

    // Register all blocks found within the block register directory
    $blocks = scandir(__DIR__ . '/template-parts/blocks/__register');
    foreach ($blocks as $block) {
        if ($block[0] != '.') {
            include __DIR__ . '/template-parts/blocks/__register/' . $block;
        }
    }
}
// Check if function exists and hook into setup.
if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'register_acf_block_types');
}


/*************************************************************
    GUTENBERG BLOCKS: LIMIT ALLOWED BLOCKS
 *************************************************************/
// Allow Only Custom Blocks Listed Below
function my_allowed_block_types($allowed_block_types_all, $post)
{
    $allowed = [
        'core/block', // add this for reusable blocks
        'core/footnotes',
        'core/group',
        'core/columns',
        'core/heading',
        'core/list-item',
        'core/list',
        'core/media-text',
        'core/paragraph',
        'core/post-excerpt',
        'core/post-featured-image',
        'core/post-title',
        'core/html',
        'yoast-seo/breadcrumbs',
        'visual-portfolio/block' ,
        'tribe/events-list',
        'tribe/qr-code'
    ];

    $post_type = get_post_type();
    switch ($post_type):

    case 'post':
        // Post Type specific blocks - example
        // default to built in wysiwyg - allow ACF blocks too
        $allowed[] = 'core/freeform';
        foreach (acf_get_block_types() as $key => $block) {
            $allowed[] = $block['name'];
        }
        break;

        // Default blocks allowed
    default:
        foreach (acf_get_block_types() as $key => $block) {
            $allowed[] = $block['name'];
        }
        break;
    endswitch;

    return $allowed;
}
add_filter('allowed_block_types_all', 'my_allowed_block_types', 10, 2);


/*************************************************************
    GUTENBERG BLOCKS: CORE BLOCKS
 *************************************************************/
/**
 * CORE/FREEFORM
 * - source: https://www.gsarigiannidis.gr/adding-a-div-wrapper-to-gutenberg-s-classic-block/
 * - Wrap core/freeform in container div
 */
function theme_wrap_classic_block($block_content, $block)
{
    if (null === $block['blockName'] && !empty($block_content) && !ctype_space($block_content)) {
        $block_content = '<section class="block block--freeform"><div class="container">' . $block_content . '</div></section>';
    }
    return $block_content;
}
add_filter('render_block', 'theme_wrap_classic_block', 10, 2);


/*************************************************************
    MENU ORDER SUPPORT
 *************************************************************/
function my_pre_get_posts($query)
{
    // only modify queries in the admin
    if (is_admin() && !wp_doing_ajax()) {
        $query->set('orderby', array('menu_order' => 'ASC', 'date' => 'DESC'));
    }
    return $query;
}

add_action('pre_get_posts', 'my_pre_get_posts');


add_filter('frm_scroll_offset', 'frm_scroll_offset');
function frm_scroll_offset()
{
    return 170; 
}
