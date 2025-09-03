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
);

// BLOCK :: CLASSES
$classes = [ 'inner-block--container' ];
$classes[] = 'container';
$classes[] = 'container--'.$blockData['width'];

if($block['align']){
    $classes[] = 'container--'.$block['align'];
}

if($block['align']){
    $classes[] = 'container--'.$block['align'];
}

if ( ! empty( $block['className'] ) ) {
	$classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}

// BLOCK :: RENDER
?>

<div id="<?php echo $blockID; ?>" class="inner-block <?php echo join( ' ', $classes ) ?>" data-align-x="<?php echo $blockData['horizontal-align']; ?>" data-align-y="<?php echo $blockData['vertical-align']; ?>" >

    <InnerBlocks 
        template="<?php echo esc_attr(wp_json_encode($blocks_template)); ?>" 
    />
</div>
