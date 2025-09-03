<?php 

// Register Block: Resource Listing
acf_register_block_type(array(
    'name'              => 'story-listing',
    'title'             => __('Our Story Listing'),
    'description'       => __('Select stories to display manually, or by tag'),
    'render_template'   => 'template-parts/blocks/_innerBlock/story-listing/story-listing.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array( 'story listing', 'inner', 'blocks', 'content', 'page', 'build' ) ,
    'supports'          => array(
        'anchor' => true, 
        'align' => false, 
        'align_text' => false, 
        'align_content' => false,
    ),
));
