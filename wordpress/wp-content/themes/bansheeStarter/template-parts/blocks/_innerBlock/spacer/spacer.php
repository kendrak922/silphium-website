<?php
/**
 * Block: Spacer
 * - Slug: spacer
 */

use Lean\Load;

// Global Variables
global $templateData;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : uniqid($block['id']));
$blockData = [
    'spacer_height_large' => get_field('spacer_height_large') ? get_field('spacer_height_large') : 'auto',
    'spacer_height_medium' => get_field('spacer_height_medium') ? get_field('spacer_height_medium') : 'auto',
    'spacer_height_small' => get_field('spacer_height_small') ? get_field('spacer_height_small') : 'auto',
];

// BLOCK :: CLASSES
if (isset($block["className"])) {
    $blockData['classes'][] =  $block["className"];
}


// BLOCK :: RENDER     
?>

<div id="<?php echo $blockID; ?>" class="spacer">
    <style scoped>
    #<?php echo $blockID ?>  {
            height: <?php echo $blockData['spacer_height_large'] ?>;
        }
        @media(max-width: 767px) {
        #<?php echo $blockID ?> {
                height: <?php echo $blockData['spacer_height_medium']; ?>;
            }
        }
        @media(max-width: 400px) {
        #<?php echo $blockID ?> {
                    height: <?php echo $blockData['spacer_height_small']; ?>;
            }
        }
    </style>
</div>
