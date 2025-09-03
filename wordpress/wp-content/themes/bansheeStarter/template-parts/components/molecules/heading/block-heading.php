<?php

use Lean\Load;

$base = $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$block_heading = $args['block-heading'];

if (!$block_heading) {
    //exit molecule
    return;
}

$base_classes = preg_filter('/$/', '__block-heading', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

$base[] = 'block-heading';
?>


<div data-molecule="block-heading" class="block-heading <?php echo $classes; ?>">

    <?php
    // Atom: Eyebrow
    if (isset($block_heading['eyebrow']) && $block_heading['eyebrow']) :
        Load::atom(
            'text/eyebrow',
            [
                'base'            => $base,
                'eyebrow'         => $block_heading['eyebrow'],
                'eyebrow_level'   => $block_heading['eyebrow_level'],
            ]
        );
    endif; ?>

    <?php
    // Atom: Heading
    if (isset($block_heading['title']) && $block_heading['title']) :
        Load::atom(
            'text/heading',
            [
                'base'            => $base,
                'heading'         => $block_heading['title'],
                'heading_level'   => $block_heading['title_level'],
                'heading_style'   => $block_heading['title_style'] ?? 'h1 u-textStyleItalic'
            ]
        );
    endif; ?>

    <?php
    // Atom: Caption
    if (isset($block_heading['caption']) && $block_heading['caption']) :
        Load::atom(
            'text/caption',
            [
                'base'            => $base,
                'caption'         => $block_heading['caption'],
                'classes'         => ['h--subheading']
            ]
        );
    endif; ?>

    <?php
    // Atom: Content
    if (isset($block_heading['content']) && $block_heading['content']) :
        Load::atom(
            'text/content',
            [
                'base'            => $base,
                'content'         => $block_heading['content'],
                'classes'         => ['intro-copy']
            ]
        );
    endif; ?>

    <?php
    // Molecule: Button Group
    if (isset($block_heading['buttons']) && $block_heading['buttons']) :
        Load::molecule(
            'buttons/button-group',
            [
                'base'            => $base,
                'classes'         => ['u-marginTop6gu  grid--align-center'],
                'buttons'         => $block_heading['buttons'],
            ]
        ); ?>
    <?php endif; ?>
</div>