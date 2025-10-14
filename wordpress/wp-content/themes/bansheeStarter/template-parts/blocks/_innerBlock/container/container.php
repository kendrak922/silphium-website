<?php
/**
 * Block: Container
 * - Slug: container
 * - Docs: https://www.billerickson.net/innerblocks-with-acf-blocks/
 */

// Block Variables
$blockID = (!empty($block['anchor']) ? $block['anchor'] : uniqid($block['id']));

$blocks_template = array(
    // array('core/heading', array()),
    // array('core/paragraph', array()),
    // array('acf/buttons', array()),
);

// BLOCK :: DATA
$blockData = array(
    'width' => get_field('width') ?? 'default',
    'vertical-align' => get_field('formatting_vertical_align') ?? 'top',
    'horizontal-align' => get_field('formatting_horizontal_align') ?? 'left',
    'border_color' => get_field('add_border') ? get_field('border_color')['theme_colors'] : '',
);
debug_to_console(get_field('theme_colors'));
// BLOCK :: CLASSES
$classes = [ 'inner-block--container' ];
$classes[] = 'container';
$classes[] = 'container--'.$blockData['width'];


// BLOCK :: STYLES
$style = [];



if($block['align']) {
    $classes[] = 'container--'.$block['align'];
}

if($blockData['border_color']) {
    $classes[] = 'u-borderColor'.$blockData['border_color'];
}

if($block['align']) {
    $classes[] = 'container--'.$block['align'];
}

if (! empty($block['className']) ) {
    $classes = array_merge($classes, explode(' ', $block['className']));
}

// BLOCK :: RENDER
?>

<div id="<?php echo $blockID; ?>" class="inner-block <?php echo join(' ', $classes) ?>" data-align-x="<?php echo $blockData['horizontal-align']; ?>" data-align-y="<?php echo $blockData['vertical-align']; ?>" >

    <InnerBlocks 
        template="<?php echo esc_attr(wp_json_encode($blocks_template)); ?>" 
    />
</div>
