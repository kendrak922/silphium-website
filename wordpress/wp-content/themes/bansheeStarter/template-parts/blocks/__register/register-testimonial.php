<?php
//  Register Block: Testimonial
acf_register_block_type(
    array(
    'name'              => 'testimonial',
    'title'             => __('Testimonial'),
    'description'       => __('Testimonial'),
    'render_template'   => 'template-parts/blocks/_innerBlock/testimonial/testimonial.php',
    'category'          => 'design',
    'icon'              => 'align-wide',
    'keywords'          => array('testimonial', 'image', 'stack', 'column', 'text', 'copy', 'body', 'content', 'page', 'build'),
    'supports'          => array(
        'customClassName' => true,
        'anchor' => true,
        'align' => false,
        'align_text' => false,
        'align_content' => false
    ),
    )
);
