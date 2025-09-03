<?php

/**
 * Block: File Grid
 * - Slug: file-grid
 */

use Lean\Load;

// BLOCK :: DATA
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
$blockData = array(
    'title' => get_field('title') ?? 'title',
    'title_level' => get_field('title_level') ?? 'h3',
    'files' => get_field('files'),
);


// BLOCK :: File Grid
?>

<div class="inner-block--file-grid">
    <div> <?php         // heading
            Load::atom(
                'text/seperator',
                [
                    'heading'         => $blockData['title'],
                    'heading_level'   => $blockData['title_level'],
                ]
            );
            ?>
    </div>
    <div class="file-grid--wrapper">
        <?php
        if ($blockData['files']) :
            foreach ($blockData['files'] as $file) :
                $fileName = $file['file']['title'] . ' ' . size_format($file['file']['filesize'], 2);
                $fileUrl = $file['file']['url']; 
                $button = [
                    'button_type' => 'file',
                    'button_style' => 'outline',
                    'button_file' => [
                        'url' => $fileUrl,
                        'filename'=> $fileName,
                    ],
                    'button_label' => $fileName,
                ];
                Load::atom(
                    'button/button',
                    [
                        'button' => $button,
                    ]
                ); ?>
        <?php
            endforeach;
        endif;
        ?>
    </div>

</div>