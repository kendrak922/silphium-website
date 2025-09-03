<?php 

// Register Block: Hero Banner
acf_register_block_type(array(
    'name'              => 'hero-banner',
    'title'             => __('Hero Banner'),
    'description'       => __('Full width banner for a page\'s hero.'),
    'render_template'   => 'template-parts/blocks/hero-banner/hero-banner.php',
    'category'          => $themeGlobals['guten_category'],
    'icon'              => 'format-image',
    'keywords'          => array( 'hero', 'banner', 'full', 'width', 'media', 'content', 'image', 'bleed', 'flexible', 'page', 'build' ) ,
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