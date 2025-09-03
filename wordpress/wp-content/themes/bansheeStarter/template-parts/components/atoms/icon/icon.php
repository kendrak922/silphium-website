<?php

/**
 *  Atom: Icon
 */

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$icon = $args['icon'] ?? [];

if (!$icon) {
    //exit atom
    return;
}

// Load::atom(
//   'icon/icon',
//   [
//     'icon'     => [
//         'type'  => 'default', 
//         'icon' => [media object], // type: default
//         'svg_icon' => '[file name]', // type: svg
//         'font_awesome_icon' => 'fa-class', // type: fa
//         'link' => [
//             'url' => 'https://twitter.com',
//             'aria' => '',
//             'target' => '_blank'
//         ]
//     ]
//   ]
// );

if (isset($icon['link'])) {
    $tag = 'a';
} elseif (isset($icon['type']) && $icon['type'] == 'inline') {
    $tag = 'span';
} else {
    $tag = 'div';
}

// icon type
if (isset($icon['type']) && isset($icon['svg_icon'])) :
    if (in_array($icon['svg_icon'], ['utility-login', 'utility-file'])) :
        $classes[] = 'icon--fill';
    endif;
    if (in_array($icon['svg_icon'], ['utility-download', 'utility-shield', 'utility-link'])) :
        $classes[] = 'icon--outline';
    endif;
endif;

$base_classes = preg_filter('/$/', '__icon', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';
?>


<<?php echo $tag; ?> data-atom="icon" class="icon <?php echo $classes; ?>" <?php echo isset($icon['link']) ? 'href="' . $icon['link']['url'] . '" target="' . ($icon['link']['target'] ?? '') . '" aria-label="' . ($icon['link']['aria'] ?? '') . '" title="' . ($icon['link']['title'] ?? '') . '" ' : ''; ?>>

    <?php /* If Font Awesome and there is a font awesome class */
    if (isset($icon['type']) && isset($icon['font_awesome_icon'])) : ?>
        <span class="fa fab fa-<?php echo $icon['font_awesome_icon']; ?>"></span>

    <?php /* If file added to the 'assets/src/img/icons' folder */
    elseif (isset($icon['type']) && isset($icon['svg_icon'])) :
        echo GetIconMarkup($icon['svg_icon']);

    /* if custom, and custom icon exists */
    elseif ($icon['icon']) : ?>
        <img src="<?php echo $icon['icon']['url']; ?>" alt="<?php echo $icon['icon']['alt']; ?>">
    <?php endif; ?>

</<?php echo $tag; ?>>