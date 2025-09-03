<?php

use Lean\Load;

// Load::atom(
//   'icon/social-icon',
//   [
//     'social-icon'     => [
//         'fa_or_custom'  => true || false, true = FA, false = custom
//         'font_awesome_icon' => 'fa-class',
//         'custom_icon' => [media object],
//         'social_link' => 'https://twitter.com'
//     ]
//   ]
// );

/**
 * Social Icon Atom
 */


$social_icon = $args['social-icon'] ?? [];

// if there's no social icon object
if (!$social_icon) {
    //exit atom
    return;
}

if ($social_icon['social_link']) {
    $tag = 'a';
} else {
    $tag = 'div';
}
?>
<<?php echo $tag; ?> data-atom="social-icon" class="social__icon" aria-label="link to our <?php echo $social_icon['social_link']; ?>" <?php echo $social_icon['social_link'] ? 'href="' . $social_icon['social_link'] . '" target="_blank"' : ''; ?>>
  <?php // If Font Awesome and there is a font awesome class 
    ?>
  <?php if ($social_icon['fa_or_custom'] && $social_icon['font_awesome_icon']) : ?>
    <span class="fa fab fa-<?php echo $social_icon['font_awesome_icon']; ?>"></span>
        <?php // if custom, and custom icon exists 
        ?>
  <?php elseif (!$social_icon['fa_or_custom'] && $social_icon['custom_icon']) : ?>
    <img src="<?php echo $social_icon['custom_icon']['url']; ?>" alt="<?php echo $social_icon['custom_icon']['alt']; ?>">
  <?php endif; ?>

</<?php echo $tag; ?>>