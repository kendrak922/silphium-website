<?php
/**
 * The template for displaying the understory header
 * Custom header for understory-themed pages with forest atmosphere
 */

use Lean\Load;

// Declare global variables
global $themeGlobals;

$darkmode = get_field('dark_mode') == 'true' ? 'u-darkMode' : '';


// Understory-specific settings
$understory_header_settings = [
    'show_navigation' => get_field('show_navigation') ? true : true,
    'header_style' => get_field('header_style') ?: 'minimal',
    'forest_overlay' => get_field('forest_overlay') ? true : false,
];

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <meta name="format-detection" content="telephone=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <meta name="statuscake" /><!-- or -->
    <!-- add additional scripts and stylesheets to my_add_theme_scripts() in functions.php -->
    <?php if (is_singular() && get_option('thread_comments')) { wp_enqueue_script('comment-reply');
    } ?>
    <?php wp_head(); ?>
    <!-- START: FAVICON -->
    <link rel="apple-touch-icon" href="<?php echo $themeGlobals['theme_url']; ?>/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="512x512"  href="<?php echo $themeGlobals['theme_url']; ?>/favicons/android-chrome-512x512.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $themeGlobals['theme_url']; ?>/favicons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $themeGlobals['theme_url']; ?>/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $themeGlobals['theme_url']; ?>/favicons/favicon-16x16.png">
        <link rel="manifest" href="<?php echo $themeGlobals['theme_url']; ?>/favicons/site.webmanifest">
    <!-- END: FAVICON -->
</head>

<body <?php body_class(); ?> >
    <div class="wrapper understory-wrapper <?php echo $darkmode;?> ">
        <!-- Understory Header -->
        <header id="header" class="header header--understory">
            <div class="container container--wide">
                
                <!-- Minimal Understory Navigation -->
                <?php if ($understory_header_settings['show_navigation']): ?>
                <nav class="nav nav--understory">

                    
                    <!-- Understory Menu Toggle -->
                    <button class="nav__toggle understory-nav-toggle" aria-label="Toggle navigation">
                        <span class="nav__toggle-line"></span>
                        <span class="nav__toggle-line"></span>
                        <span class="nav__toggle-line"></span>
                    </button>
                    
                    <!-- Understory Navigation Menu -->
                    <div class="nav__menu nav__menu--understory">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'menu_class' => 'menu menu--understory',
                            'walker' => new bansheeStarter_nav_walker()
                        ));
                        ?>
                    </div>
                </nav>
                <?php endif; ?>
                
                <!-- Understory Skip Link -->
                <a class="screen-reader-text skip-link" href="#main">Skip to content</a>
            </div>
        </header>

        <!-- Understory Main Content Wrapper -->
        <main id="main" class="main main--understory">
