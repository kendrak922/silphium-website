<?php 

// Register Block: Container
acf_register_block_type(array(
    'name'              => 'container',
    'title'             => __('Container'),
    'description'       => __('Container to align widths of inner content'),
    'render_template'   => 'template-parts/blocks/_innerBlock/container/container.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'container', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'jsx' => true, // allow inner blocks
        'anchor' => true, 
        'align' => true, 
        'align_text' => false, 
        'align_content' => false,
        "spacing" => array(
            "padding" => true,
            "margin" => true
        )
    ),
));
