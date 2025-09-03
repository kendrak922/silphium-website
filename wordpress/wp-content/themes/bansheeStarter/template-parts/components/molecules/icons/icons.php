<?php

use Lean\Load;

// Load::molecule(
//     'icons/icons',
//     [
//         'icons'      => [], // array of icons
//         'classes'    => ['u-marginTop6gu'],
//     ]   
// );

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$icons = $args['icons'] ?? [];
$icons_classes = $args['icons_classes'] ?? [];

if (!$icons) {
    //exit molecule
    return;
}

$base_classes = preg_filter('/$/', '__icons', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

$base[] = 'icons';
?>

<div data-molecule="icons" class="icons <?php echo $classes; ?>">
    <?php
    foreach ($icons as $icon) :
        Load::atom(
            'icon/icon',
            [
                'icon'   => $icon,
                'classes' => $icons_classes
            ]
        );
    endforeach; ?>
</div>