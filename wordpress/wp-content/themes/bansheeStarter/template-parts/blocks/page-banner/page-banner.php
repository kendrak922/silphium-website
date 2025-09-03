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
);


// Global Variables
global $templateData;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
$blockData = [
    'classes' => [],
    'background_image' => get_field('background_image'),
    'background_color' => get_field('background_color'),
    'shape' => get_field('shape') ,
    'image_position' => get_field('image_position'),
    'gap' => get_field('gap'),
    'width' => get_field('container_width') ?? 'default'
];

$gap = $blockData['gap'] ? $blockData['gap'].'px' : '';

// BLOCK :: CLASSES
if (isset($block["className"])) {
    $blockData['classes'][] =  $block["className"];
}
$style = [];
if (!empty($blockData['background_image'])) {
    $bgImage = $blockData['background_image']['url'];
    $style[] =  "background-image:url($bgImage);";
    $style[] = "background-position:".$blockData['image_position'].";";
    $blockData['classes'][] = 'u-bgMedia';
}

if (!empty($blockData['shape'])&& $blockData['shape'] !== 'None') {
    $blockData['classes'][] = "u-sectionShape".$blockData['shape'];
}

if (isset($blockData['background_color'])) {
    $blockData['classes'][] = "u-bgColor".$blockData['background_color']['theme_colors']." ";
}

$style = implode(' ', $style);

$blockData['classes'][] = 'container--layout container--'.$blockData['width'];
// BLOCK :: RENDER
?>

<section id="<?php echo $blockID; ?>" class="block block--page-banner page-banner <?php echo implode(' ', $blockData['classes']); ?>" style ="<?php  print_r($style); ?>">
    <div class="container container--full">
        <div class="page-banner__content">
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr(wp_json_encode($blocks_allowed)); ?>" 
                template="<?php echo esc_attr(wp_json_encode($blocks_template)); ?>" 
            />
        </div>
    </div>
</section>
