<?php

use Lean\Load;

// Load::atom(
//     'text/caption',
//     [
//         'base'            => ['block-heading'],
//         'caption'         => 'this is content',
//     ]
// );

$base = $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$caption = $args['caption'] ?? null;

if (!$caption) {
    //exit atom
    return;
}
$base_classes = preg_filter('/$/', '__caption', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

?>

<div data-atom="caption" class="<?php echo $classes; ?> caption">
    <?php echo $caption; ?>
</div>