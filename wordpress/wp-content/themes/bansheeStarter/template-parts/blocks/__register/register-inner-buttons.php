<?php 
// Register Block: Buttons
acf_register_block_type(array(
    'name'              => 'buttons',
    'title'             => __('Buttons'),
    'description'       => __('Button for inner content'),
    'render_template'   => 'template-parts/blocks/_innerBlock/buttons/buttons.php',
    'category'          => 'core',
    'icon'              => 'align-wide',
    'keywords'          => array( 'buttons', 'button', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'customClassName' => true,
        'anchor' => true, 
        'align' => true, 
        'align_text' => false, 
        'align_content' => false 
    ),
));
