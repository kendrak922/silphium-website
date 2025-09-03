<?php 

// Register Block: File Gird
acf_register_block_type(array(
    'name'              => 'file-grid',
    'title'             => __('File Grid'),
    'description'       => __('Create a grid of files'),
    'render_template'   => 'template-parts/blocks/_innerBlock/file-grid/file-grid.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'file', 'button', 'grid', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'anchor' => true, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
