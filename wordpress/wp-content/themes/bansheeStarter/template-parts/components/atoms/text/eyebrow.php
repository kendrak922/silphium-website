<?php

use Lean\Load;

// Load::atom(
//     'text/eyebrow',
//     [
//         'base'            => ['block-heading'],
//         'eyebrow'         => 'eyebrow',
//         'eyebrow_level'   => 'div',
//     ]
// );

$base =  $args['base'] ?? [];
$eyebrow = $args['eyebrow'] ?? null;
$eyebrow_level = $args['eyebrow_level'] ?? "div";

if (!$eyebrow) {
    //exit atom
    return;
}

$classes = preg_filter('/$/', '__eyebrow', $base);
$classes = $classes ? implode(' ', $classes) : '';
?>

<<?php echo $eyebrow_level; ?> data-atom="eyebrow" class="<?php echo $classes; ?>">
    <?php echo $eyebrow; ?>
</<?php echo $eyebrow_level; ?>>