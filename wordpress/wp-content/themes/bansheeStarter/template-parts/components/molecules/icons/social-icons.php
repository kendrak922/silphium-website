<?php

use Lean\Load;

// Load::molecule(
//     'icons/social-icons',
//     [
//         'social_icons'      => [], // Repeater of icons
//         'inline'            => true || false, // display vert or inline
//         'classes'           => ['class_1', 'class_2'],
//     ]   
// );

$classes = $args['class'] ?? [];
$inline = $args['inline'] ?? false;
$social_icons = $args['social_icons'] ?? [];
if (!$social_icons) {
    //exit molecule
    return;
}

$classes = $classes ? implode(' ', $classes) : '';
?>

<ul data-molecule="social-icons" class="social__icons <?php echo $inline ? 'social__icons--inline' : ''; ?> <?php echo $classes; ?>">
    <?php
    foreach ($social_icons as $social_icon) :
    ?>
        <li>
            <?php
            Load::atom(
                'icon/social-icon',
                [
                    'social-icon'   => $social_icon
                ]
            );
            ?>
        </li>
    <?php endforeach; ?>
</ul>