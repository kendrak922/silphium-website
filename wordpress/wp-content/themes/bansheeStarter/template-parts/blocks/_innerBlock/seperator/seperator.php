<?php
/**
 * Block: seperator
 * - Slug: seperator
 */

 use Lean\Load;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : uniqid($block['id']));
$blockData = array(
    'title' => get_field('heading_text'),
    'title_level' => get_field('heading_level') ?? 'h3',
    'title_style' => get_field('heading_style') ?? 'h3'
);


// BLOCK :: RENDER
?>

<?php 
    // seperator
    Load::atom(
        'text/seperator',
        [
            'base'            => ['seperator'],
            'heading'         => $blockData['title'],
            'heading_level'   => $blockData['title_level'],
            'heading_style'   => $blockData['title_style']
        ]
    );
?>