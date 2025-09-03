<?php
//  Register Block: Column
acf_register_block_type(
    array(
    'name'              => 'content-stacks',
    'title'             => __('Content Stack'),
    'description'       => __('Content - title, image, description, button - in a column layout'),
    'render_template'   => 'template-parts/blocks/_innerBlock/content-stacks/content-stacks.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array('button', 'image', 'stack', 'column', 'text', 'copy', 'body', 'content', 'page', 'build'),
    'supports'          => array(
        'customClassName' => true,
        'anchor' => true,
        'align' => false,
        'align_text' => false,
        'align_content' => false
    ),
    )
);
