<?php

use Lean\Load;

// Load::atom(
//     'chip/chip',
//     [
//         'base'       => ['card'],
//         'classes'    => ['classes'],
//         'chip' => [
//           'text'       => 'Category Name',
//           'url'        => '/impact-and-news/?cat=3',
//           'color'      => 'Red',
//         ]
//     ]
// );

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$chip = $args['chip'] ?? null;

if (!$chip) {
    //exit atom
    return;
}

if (isset($chip['color'])) {
    $classes[] = 'chip' . $chip['color'];
}
$base_classes = preg_filter('/$/', '__chip', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';
?>

<?php if (isset($chip['url'])) : ?>
    <a data-atom="chip" href="<?php echo $chip['url']; ?>" class="chip chip--clickable <?php echo $classes; ?>">
        <?php echo $chip['text']; ?>
    </a>
<?php else : ?>
    <span data-atom="chip" class="chip <?php echo $classes; ?>">
        <?php echo $chip['text']; ?>
    </span>
<?php endif; ?>