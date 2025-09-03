<?php

use Lean\Load;

$base =  $args['base'] ?? [];
$classes =  $args['classes'] ?? [];
$buttons = $args['buttons'] ?? [];
if (!$buttons) {
    //exit molecule
    return;
}

$base_classes = preg_filter('/$/', '__buttons', $base);
$classes = array_merge($classes, $base_classes);

//stack
$classes[] = 'grid--column';
// $classes[] = 'grid--align-center';

$classes = $classes ? implode(' ', $classes) : '';
?>

<div data-molecule="button-group" class="<?php echo $classes; ?> btn__container grid grid--gutters-narrow">
    <?php
    foreach ($buttons as $button) :

        Load::atom(
            'button/button',
            [
                'button'         => $button['button'],
            ]
        );
    endforeach;
    ?>
</div>