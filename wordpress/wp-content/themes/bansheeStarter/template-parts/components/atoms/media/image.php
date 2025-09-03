<?php

use Lean\Load;

// Load::atom(
//     'media/image',
//     [
//         'base' => [],
//         'classes' => ['modaal-video modaal'],
//         'image' => [
//             'url' => $image,
//             'alt' => $image_alt
//         ],
//         'image_link' => [
//             'url' => 'vimeo.com',
//             'aria' => 'Watch this video'
//         ]
//     ]
// );

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];

$image = $args['image'] ?? null;
$image_hover = $args['image_hover'] ?? null;
$image_link = $args['image_link'] ?? null;
$caption = $args['caption'] ?? null;

if (!$image) {
    //exit atom
    return;
}
$classes[] = 'image';
if ($image_hover) {
    $classes[] = 'image--has-hover';
}

$base_classes = preg_filter('/$/', '__image', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

$image_classes = isset($image['classes']) ? implode(' ', $image['classes']) : '';
?>

<?php if (isset($image_link) && $image_link['url']) : ?>
    <?php
    // video link
    if (isset($image_link['type']) && $image_link['type'] == 'modaal') : ?>
        <a href="<?php echo $image_link['url']; ?>" class="image--modaal <?php echo $classes; ?>" aria-label="<?php echo $image_link['aria']; ?>">
            <img data-atom="image" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="<?php echo $image_classes; ?>">
            <?php echo GetIconMarkup('icon-play'); ?>
        </a>
    <?php
    // image link
    else : ?>
        <a href="<?php echo $image_link['url']; ?>" class="image__link" target=" <?php echo $image_link['target'] ?? ''; ?>" aria-label="<?php echo $image_link['aria'] ?? ''; ?>">
            <div class="<?php echo $classes; ?>">
                <img data-atom="image" class="image__img <?php echo $image_classes; ?>" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                <?php if ($image_hover) : ?>
                    <img data-atom="image" class="image__img--hover <?php echo $image_classes; ?>" src=" <?php echo $image_hover['url']; ?>" alt="<?php echo $image_hover['alt']; ?>">
                <?php endif; ?>
            </div>
            <?php if ($caption) : ?>
                <div class="image__caption"><?php echo $caption ?></div>
            <?php endif; ?>
        </a>
    <?php endif; ?>
<?php else :
    //default image 
?>
    <div class="<?php echo $classes; ?>">
        <img data-atom="image" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="<?php echo $image_classes; ?>">
    </div>
    <?php if ($caption) : ?>
        <div class="image__caption"><?php echo $caption ?></div>
    <?php endif; ?>
<?php endif; ?>