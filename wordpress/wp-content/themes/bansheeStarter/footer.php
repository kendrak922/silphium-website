<?php

use Lean\Load;

/**
 * The template for displaying the footer
 */
global $themeGlobals;

// organism variables
$footerData = [
    'logo'        => get_field('footer_logo', 'options') ? get_field('footer_logo', 'options') : get_field('site_logo', 'options'), // media object
    'nav'        => [
        'theme_location'    => 'footer-menu',
        'depth'                => 2,
        'menu_class'        => 'menu menu--footer',
        'walker'            => new bansheeStarter_nav_walker_footer()
    ]
];

$button = get_field('button', 'options');
$socials = get_field('socials', 'options');
$footer_text = get_field("footer_text", 'option');
$footer_email = get_field("email", 'option');
$footer_phone = get_field("phone", 'option');
$footer_link = get_field("footer_link", 'option');


?>

<?php /*****
       * END: MAIN CONTENT 
       ******/ ?>
</main>
<?php // Opened in header.php 
?>

<footer id="footer" class="footer">
    <?php /*****
           * FOOTER MAIN 
           ******/ ?>
    <section class="footer__main">
        <div class="container container--wide">
        <div class="footer__logo">
                <a href="<?php bloginfo('url'); ?>"  aria-label="Link to homepage">
                    <?php if (get_field('global_imagery', 'options')['footer_logo']) : $logo = get_field('global_imagery', 'options')['footer_logo']; ?>
                            <img height="166px" width="500px" src="<?php echo $logo['url']; ?>" alt="Banshee Starter Logo">
                    <?php elseif (file_exists($themeGlobals['theme_rel'] . '/assets/dist/imgs/logo-footer.png')) : ?>
                            <img height="166px" width="500px" src="<?php echo $themeGlobals['theme_url']; ?>/assets/dist/imgs/logo-footer.png" alt="Banshee Starter Logo" class="u-lg-block" />               
                    <?php else : ?>
                            <strong><?php echo bloginfo('title'); ?></strong>
                    <?php endif; ?>
                </a>
            </div>
        <div class="footer__content">
                        <?php if($footer_email) : ?>
                            <div class="footer__contact">
                                    <h3 class="h4 contact__head">Contact</h3>
                                    <?php if($footer_phone) : 
                                        $phone_link = $footer_phone['url'];
                                        $phone_text = $footer_phone['title'];  
                                        ?>
                                        <div>
                                            <a  class="text-xs" href="mailto:<?php echo $footer_email?>" target="_blank"><?php echo $footer_email; ?></a>
                                            <a class="text-xs" href="<?php echo $phone_link; ?>"><?php echo $phone_text; ?></a>
                                        </div>
                                    <?php endif; ?>
                            </div>
                        <?php endif; ?>
                            <?php /* Footer Menu */ ?>
                            <?php if($socials) : ?>
                                        <div class="footer__social social">
                                            <h3 class="h4 social__head">Follow</h3>
                                            <div  aria-label="Social Media Menu">
                                                <?php foreach($socials as $social) : ?>
                                                        <?php 
                                                            $link = $social['social_link'];
                                                            $title = $social['social_title'];
                                                            $icon = $social['social_icon']['url'];
                                                        
                                                        ?>
                                                        <a href="<?php echo $link; ?>" target="_blank">
                                                            <img src="<?php echo $icon; ?>" height="40px" width="40px" />
                                                        </a>
                                                <?php endforeach;?>
                                            </div>
                                        </div>
                            <?php endif; ?>
                </div>
                <div class="footer__location">
                        <h3 class="h4">Silphium Collective</h3>
                </div>
                <div class="footer__bottom">
                <nav class="menu-wrapper__content"  aria-label="Site Navigation" >
                                        <?php /*****
                                               * Menu 
                                               *****/ ?>
                                        <?php
                                        $header_nav = [
                                        'theme_location'    => 'footer-menu',
                                        'menu_class'        => 'menu--footer menu',
                                        'walker'            => new bansheeStarter_nav_walker()
                                        ];
                                        wp_nav_menu($header_nav); ?>
                                    </nav>
                </div>
        </div>
    </section>

    <?php // WP Footer 
    ?>
    <?php wp_footer(); ?>

</footer>

</div> <?php // END: .wrapper - opened in header.php 
?>
</body>

</html>