<?php 

// Register Block: page Banner
acf_register_block_type(array(
    'name'              => 'page-banner',
    'title'             => __('Page Banner'),
    'description'       => __('Full width banner for a page\'s page.'),
    'render_template'   => 'template-parts/blocks/page-banner/page-banner.php',
    'category'          => $themeGlobals['guten_category'],
    'icon'              => 'format-image',
    'keywords'          => array( 'page', 'banner', 'full', 'width', 'media', 'content', 'image', 'bleed', 'flexible', 'page', 'build' ) ,
    'supports'          => array(
        'jsx' => true, // allow inner blocks
        'customClassName' => true,
        'anchor' => true, 
        'align' => true, 
        'align_text' => false, 
        'align_content' => false,
        'ariaLabel'=> true
    ),
));