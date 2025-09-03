<?php
//  Register Block: Marquee
acf_register_block_type(
    array(
    'name'              => 'marquee',
    'title'             => __('Marquee'),
    'description'       => __('Marquee'),
    'render_template'   => 'template-parts/blocks/_innerBlock/marquee/marquee.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array('marquee', 'image', 'animation', 'movement', 'text', 'content','list' ),
    'supports'          => array(
        'customClassName' => true,
        'anchor' => true,
        'align' => false,
        'align_text' => false,
        'align_content' => false
    ),
    )
);
