<?php 
// Register Block: image
acf_register_block_type(array(
    'name'              => 'image',
    'title'             => __('Image (ACF)'),
    'description'       => __('Image with caption'),
    'render_template'   => 'template-parts/blocks/_innerBlock/image/image.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'image', 'title', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'customClassName' => true,
        'anchor' => false, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
