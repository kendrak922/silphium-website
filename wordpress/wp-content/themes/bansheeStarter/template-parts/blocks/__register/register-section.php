<?php 

// Register Block: Inner Block Wrapper
acf_register_block_type(array(
    'name'              => 'section',
    'title'             => __('Section'),
    'description'       => __('Section to group inner blocks of content'),
    'render_template'   => 'template-parts/blocks/section/section.php',
    'category'          => 'core',
    'icon'              => 'align-wide',
    'keywords'          => array( 'section', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'jsx' => true, // allow inner blocks
        'anchor' => true, 
        'align' => false, 
        'align_text' => true, 
        'align_content' => false,
        'color' => true,
    ),
));
