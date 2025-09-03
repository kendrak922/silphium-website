<?php

use Lean\Load;

// Load::atom(
//     'text/text',
//     [
//         'base'            => ['block-heading'],
//         'content'         => 'this is content',
//     ]
// );

$base = $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$content = $args['content'] ?? null;

if (!$content) {
    //exit atom
    return;
}
$base_classes = preg_filter('/$/', '__content', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

?>

<div data-atom="content" class="<?php echo $classes; ?> u-wysiwyg">
    <?php echo $content; ?>
</div>