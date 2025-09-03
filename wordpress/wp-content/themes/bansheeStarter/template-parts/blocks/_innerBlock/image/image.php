<?php
/**
 * Block: Cover Image
 * - Slug: cover-image 
 */

use Lean\Load;

// Global Variables
global $templateData;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : uniqid($block['id']));
$blockData = [
    'classes' => [],
    'image' => get_field('image'),
    'aspect_ratio' => get_field('aspect_ratio') ?? 'default',
    'resolution' => get_field('resolution') ?? 'large',
    'horizontal_align' => get_field('formatting_horizontal_align') ?? 'center',
    'rounded' => get_field('rounded') ? 'u-rounded' : '',
    'max_width' => get_field('max_width') ? get_field('max_width') : 'auto',
    'mobile_max_width' => get_field('max_width_mobile') ? get_field('max_width_mobile') : 'auto',
];

$mediaId = $blockData['image']['ID'];
$image = get_post($mediaId);
$image_caption = $image->post_excerpt?$image->post_excerpt:'';
$image_attribution = get_field('attribution', $mediaId)? '<i>'.get_field('attribution', $mediaId).'</i>':'';

// BLOCK :: CLASSES
if (isset($block["className"])) {
    $blockData['classes'][] =  $block["className"];
}
$blockData['classes'][] = 'u-bgMedia';


// BLOCK :: RENDER     
?>

<figure id="<?php echo $blockID; ?>" class="figure--<?php echo $blockData['horizontal_align'] ?> <?php echo $blockData['rounded'] ?>">
<style scoped>
   #<?php echo $blockID ?> img {
        max-width: <?php echo $blockData['max_width'] ?>;
    }
    @media(max-width: 767px) {
       #<?php echo $blockID ?> img {
                max-width: <?php echo $blockData['mobile_max_width']; ?>;
        }
    }
</style>
    <?php echo wp_get_attachment_image($mediaId, $blockData['resolution'], '', array('class' => 'lazyload','style' => 'aspect-ratio: '.$blockData['aspect_ratio']) ); ?>
    <figcaption class="h--caption"><?php echo $image_caption .' '.$image_attribution;?></figcaption>
</figure>
