<?php
/**
 * Block: Buttons
 * - Slug: buttons
 * - Docs: https://www.billerickson.net/innerblocks-with-acf-blocks/
 */

 use Lean\Load;
 
// Block Variables
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
$blockData = array(
    'buttons' => get_field('buttons'),
    'horizontal-align' => get_field('formatting_horizontal_align') ?? 'left'
);

/***** ADMIN LABEL *****/
// echo bansheeStarter_blockAdminHead($block);
?>

<div class="inner-block inner-block--buttons grid grid--gutters-narrow u-marginVert6gu"  data-align-x="<?php echo $blockData['horizontal-align']; ?>">
    <?php
    //BUTTONS
    if (isset($blockData['buttons']) && $blockData['buttons']) :
        
        // Loop through the buttons array and remove "cta_" from the keys
        foreach ($blockData['buttons'] as $button) {
            if (isset($button['button']) && is_array($button['button'])) {
                $button['button'] = array_reduce(array_keys($button['button']), function ($result, $key) use ($button) {
                    $newKey = preg_replace('/^cta_/', '', $key);
                    if($newKey == 'button_link_type'){ $newKey = 'button_type';}
                    $result[$newKey] = $button['button'][$key];
                    return $result;
                }, []);
            }
        }

        Load::molecule(
            'buttons/button-group',
            [
                'buttons'   => $blockData['buttons'],
            ]
        );
    endif;
    ?>
</div>