<?php

/**
 * Admin Block Name Heading
 * - Print at the top of a block to display the blocks label in the admin
 * 
 * 
 * @param array $block - pass the blocks reserved $block variable
 * @example
 * echo bansheeStarter_blockAdminHead();
 */

if (!function_exists('bansheeStarter_blockAdminHead')) {
    function bansheeStarter_blockAdminHead($block)
    {
        $html = '';

        if (is_admin()) :
            // ONLY DISPLAYS IN THE WP ADMIN
            $html .= '<div class="admin--block-head">';
            $html .= '<h4 class="admin-block-title"><span class="dashicons dashicons-' . $block['icon'] . '"></span> <span>' . $block['title'] . '</span></h4>';
            if ($block['description']) :
                $html .= '<p class="admin-block-desc">' . $block['description'] . '</p>';
            endif;
            $html .= '</div>';
        // END ADMIN CONTENT
        endif;

        return $html;
    }
}
