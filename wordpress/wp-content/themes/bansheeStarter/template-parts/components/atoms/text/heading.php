<?php

use Lean\Load;

// Load::atom(
//     'text/heading',
//     [
//         'base'            => ['block-heading'],
//         'heading'         => 'This is a Title',
//         'heading_level'   => 'h1',
//         'heading_style'   => 'h1',
//     ]
// );

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$heading = $args['heading'] ?? null;
$heading_level = $args['heading_level'] ?? "h1";
$heading_style = $args['heading_style'] ?? $heading_level; ////force a heading style

if (!$heading) {
    //exit atom
    return;
}

$base_classes = preg_filter('/$/', '__heading', $base);
$classes = array_merge($classes, $base_classes);
$classes[] = $heading_style;
$classes = $classes ? implode(' ', $classes) : '';
?>

<<?php echo $heading_level; ?> data-atom="heading" class="<?php echo $heading_style; ?>">
    <?php echo $heading; ?>
</<?php echo $heading_level; ?>>