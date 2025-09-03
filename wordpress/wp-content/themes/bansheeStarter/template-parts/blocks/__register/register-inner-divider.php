<?php 
// Register Block: Divider
acf_register_block_type(array(
    'name'              => 'divider',
    'title'             => __('Divider'),
    'description'       => __('Divider for titles'),
    'render_template'   => 'template-parts/blocks/_innerBlock/divider/divider.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'divider', 'title', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'customClassName' => true,
        'anchor' => false, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
