<?php

use Lean\Load;

/**
 * The template for displaying the understory footer
 * Custom footer for understory-themed pages with forest atmosphere
 */
global $themeGlobals;

// Understory footer settings
$understory_footer_settings = [
    'show_footer' => get_field('show_footer') ? true : true,
    'footer_style' => get_field('footer_style') ?: 'minimal',
    'forest_overlay' => get_field('forest_overlay') ? true : false,
    'ambient_sounds' => get_field('ambient_sounds') ? true : false,
];

// organism variables


$button = get_field('button', 'options');
$footer_link = get_field("footer_link", 'option');
?>

<?php /*****
       * END: MAIN CONTENT 
       ******/ ?>
</main>
<?php // Opened in header.php 
?>

<footer id="footer" class="footer footer--understory">
    
    <?php /*****
           * FOOTER MAIN 
           ******/ ?>
        <section class="footer__main footer__main--understory">
            <div class="container container--wide">
                
                <!-- Understory Footer Content -->
            </div>
        </section>
        
        <?php /*****
             * FOOTER BOTTOM 
             ******/ ?>
        <section class="footer__bottom--understory">
            <div class="container container--wide">
                <div class="footer__bottom-content">
                    <div class="footer__copyright">
                        <p>&copy; <?php echo date('Y'); ?> <?php echo bloginfo('name'); ?>. All rights reserved.</p>
                    </div>
                    
                    <?php if ($footer_link): ?>
                        <div class="footer__bottom-links">
                            <a href="<?php echo $footer_link['url']; ?>" class="footer__bottom-link">
                                <?php echo $footer_link['title']; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>          
    
    
    <!-- Understory Ambient Sound Controls -->
    <?php if ($understory_footer_settings['ambient_sounds']): ?>
        <div class="understory-sound-controls">
            <button class="sound-toggle" aria-label="Toggle ambient forest sounds">
                <span class="sound-icon">🌲</span>
                <span class="sound-text">Forest Sounds</span>
            </button>
        </div>
    <?php endif; ?>
    
</footer>

<!-- Understory Forest Shadows -->
<div class="understory-footer-shadows">
    <div class="forest-shadows"></div>
</div>

</div>
<!-- END: WRAPPER -->

<?php wp_footer(); ?>

</body>
</html>
