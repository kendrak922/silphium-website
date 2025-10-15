<?php

use Lean\Load;

/**
 * The template for displaying the understory footer
 * Custom footer for understory-themed pages with forest atmosphere
 */
global $themeGlobals;
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
                    
                </div>
            </div>
        </section>          
    
    
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
