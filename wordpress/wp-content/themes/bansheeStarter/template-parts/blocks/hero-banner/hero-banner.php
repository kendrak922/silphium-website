<?php
/**
 * Block: Hero Banner 
 * - Slug: hero-banner 
 */

use Lean\Load;

// BLOCK :: TEMPLATE
$blocks_allowed = array(
    'acf/buttons',
    'acf/divider',
    'acf/share',
    'core/columns',
    'core/heading',
    'core/post-title',
    'core/paragraph',
    'yoast-seo/breadcrumbs',
    'acf/image',
    'acf/spacer'
);
$blocks_template = array(
    array('core/heading', array()),
    array('acf/image', array()),
);


// Global Variables
global $templateData;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
$blockData = [
    'classes' => [],
    'content_position' => get_field('content_position'),
    'accent_image' => get_field('accent_image'),
    'background_image' => get_field('background_image'),
    'image_position' => get_field('image_position'),
    'gap' => get_field('gap'),
    'width' => get_field('container_width') ?? 'default'
];
// $imageID = $blockData['image'] ? $blockData['image']['ID'] : '';
$gap = $blockData['gap'] ? $blockData['gap'].'px;' : '';

// BLOCK :: CLASSES
if (isset($block["className"])) {
    $blockData['classes'][] =  $block["className"];
}
$style = [];
if (isset($blockData['background_image'])) {
    $bgImage = $blockData['background_image']['url'];
    $style[] =  "background-image:url($bgImage);";
    $style[] = "background-position:".$blockData['image_position'].";";
}
$style = implode(' ', $style);
$blockData['classes'][] = 'u-bgMedia';
$blockData['classes'][] = 'container--layout container--'.$blockData['width'];
// BLOCK :: RENDER
?>

<section id="<?php echo $blockID; ?>" class="block block--hero-banner hero-banner <?php echo implode(' ', $blockData['classes']); ?>" style ="<?php  print_r($style); ?>">
    <div class="container container--ultra-wide grid" style="gap:<?php echo $gap ?>;" data-content-align-x="<?php echo $blockData['content_position'];?>">
        <div class="hero-banner__content">
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr(wp_json_encode($blocks_allowed)); ?>" 
                template="<?php echo esc_attr(wp_json_encode($blocks_template)); ?>" 
            />
            <?php if($blockData['accent_image']) : ?>
                <img alt="" loading="eager" class="skip-lazy" src="<?php echo $blockData['accent_image']['url']; ?>" />
            <?php endif; ?>
        </div>
    </div>
</section>
