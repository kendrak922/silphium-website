<?php 
// Register Block: spacer
acf_register_block_type(array(
    'name'              => 'spacer',
    'title'             => __('Spacer'),
    'description'       => __('We got a blank space, baby'),
    'render_template'   => 'template-parts/blocks/_innerBlock/spacer/spacer.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'spacer', 'seperator', 'blank', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'customClassName' => true,
        'anchor' => false, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
