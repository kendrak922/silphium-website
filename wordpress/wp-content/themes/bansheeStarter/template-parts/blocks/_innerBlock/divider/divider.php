<?php
/**
 * Block: Divider
 * - Slug: divider
 */

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
$blockData = array(
    'color' => get_field('theme_colors') ?? 'green'
);


// BLOCK :: RENDER
?>

<div class="inner-block--divider u-bgColor<?php echo ucfirst($blockData['color']);?>">
    <!-- <span class="u-bgColor<?php echo ucfirst($blockData['color']);?>"></span> -->
</div>