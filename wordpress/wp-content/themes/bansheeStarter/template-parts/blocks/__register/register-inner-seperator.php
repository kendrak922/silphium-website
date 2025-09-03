<?php 
// Register Block: seperator
acf_register_block_type(array(
    'name'              => 'seperator',
    'title'             => __('Seperator Heading'),
    'description'       => __('Titles for content entries, divider with line'),
    'render_template'   => 'template-parts/blocks/_innerBlock/seperator/seperator.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'seperator', 'title', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'customClassName' => true,
        'anchor' => false, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
