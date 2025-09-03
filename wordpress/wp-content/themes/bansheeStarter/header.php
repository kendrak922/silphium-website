<?php

/**
 * The template for displaying the header
 */

use Lean\Load;

// Declare global variables
global $themeGlobals;

$darkmode = get_field('dark_mode') == 'true' ? 'u-darkMode' : '';

$header_button_link = get_field('header_button_link', 'options');
$header_button = [];
if ($header_button_link) {
    $header_button = array(
    'button_style' => 'solid',
    'button_link' => $header_button_link,
    'button_type' => 'link',
    );
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <meta name="format-detection" content="telephone=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.typekit.net/lnq6vyb.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gwendolyn:wght@400;700&family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&family=Petit+Formal+Script&display=swap" rel="stylesheet">
    <title><?php wp_title(''); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
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
    <div class="wrapper <?php echo $darkmode;?> ">

        <?php /*****
               * HEADER
               ******/ ?>
        <header class="header" aria-label="Global header navigation">
            <div class="header--fixed">

                <a class="screen-reader-text skip-link" href="#main">Skip to main</a>

                <?php /*****
                       * START: HEADER BAR 
                       ******/ ?>

                <div data-molecule="header-bar" class="header-bar header-bar--main">
                    <div class="container container--ultra-wide">
                        <div class="header-bar__wrapper grid grid--justify-between grid--no-wrap grid--align-center grid-lg--justify-center">
                            <div class="header-bar__item u-hidden u-lg-flex">
                            <nav class="menu-wrapper__content"  aria-label="Site Navigation">
                                <?php
                                            $header_nav_two = [
                                            'theme_location'    => 'main-menu--two',
                                            'menu_class'        => 'menu menu--main',
                                            'walker'            => new bansheeStarter_nav_walker()
                                            ];
                                            wp_nav_menu($header_nav_two); ?>
                            </nav>
                            </div>

                            <div class="header-bar__item">
                                <?php /*****
                                       * SITE LOGO 
                                       ******/ 
                                    $logo = get_field('global_imagery', 'options') ? get_field('global_imagery', 'options')['header_logo'] : "";
                                    $darkmodeLogo = get_field('global_imagery', 'options') ? get_field('global_imagery', 'options')['header_logo_dark_mode'] : "";
                                    ?>
                                <a href="<?php bloginfo('url'); ?>" class="logo header__logo" aria-label="Link to homepage">
                                    <?php if ($logo) : ?>
                                        <img height="100px" width="140px" class="lightModeLogo" src="<?php echo $logo['url']; ?>" alt="Cafe Marguerite">
                                            <?php if ($darkmodeLogo) : ?>
                                                <img class="darkModeLogo" src="<?php echo $darkmodeLogo['url']; ?>" alt="Cafe Marguerite">
                                            <?php endif; ?>
                                    <?php elseif (file_exists($themeGlobals['theme_rel'] . '/assets/dist/imgs/logo-header.png')) : ?>
                                        <img src="<?php echo $themeGlobals['theme_url']; ?>/assets/dist/imgs/logo-header.png" alt="Cafe Marguerite" class="u-hidden u-lg-block" />                                    <?php else : ?>
                                        <strong><?php echo bloginfo('title'); ?></strong>
                                       <?php endif; ?>
                                </a>
                            </div>

                            <div class=" header-bar__item">

                                <span class="u-lg-hidden u-marginLeft6gu">
                                    <button id="menu-toggle" class="menu-toggle" aria-label="Open the Menu" aria-expanded="false" aria-controls="menu" tabindex="0">
                                        <span class="menu-toggle__icon"></span>
                                        <span class="menu-toggle__icon"></span>
                                        <span class="menu-toggle__icon"></span>
                                    </button>
                                </span>

                                <div id="menu_container" class="menu-wrapper menu-wrapper--main" aria-hidden="false">
                        
                                    <nav class="menu-wrapper__content"  aria-label="Site Navigation" >
                                        <?php /*****
                                               * Menu 
                                               *****/ ?>
                                        <?php
                                        $header_nav = [
                                        'theme_location'    => 'main-menu',
                                        'menu_class'        => 'menu menu--main',
                                        'walker'            => new bansheeStarter_nav_walker()
                                        ];
                                        wp_nav_menu($header_nav); ?>
                                           <?php
                                            $header_nav_two = [
                                            'theme_location'    => 'main-menu--two',
                                            'menu_class'        => 'menu menu--main u-lg-hidden',
                                            'walker'            => new bansheeStarter_nav_walker()
                                            ];
                                            wp_nav_menu($header_nav_two); ?>
                                    </nav>
                                </div>
                            </div>
                            <?php  if($header_button) : ?>
                                <div class=" header-bar__item">
                                    <?php Load::atom(
                                        'button/button',
                                        [
                                        'button'   => $header_button,
                                        ]
                                    ); ?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <!--.header--fixed-->

            <div class=" overlay header__overlay menu-trigger--close"></div>
        </header>

        <?php /*****
               * START: MAIN CONTENT 
               ******/ ?>
        <main id="main" class="main" aria-label="Primary page content"> <?php // Closed in footer.php 
        ?>
