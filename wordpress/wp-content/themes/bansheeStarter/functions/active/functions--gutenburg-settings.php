<?php
//by template
function restrict_blocks_by_template($allowed_blocks, $post)
{
    // Get the current page template
    $template = get_page_template_slug($post->ID);

    // Define the template for which you want to restrict blocks
    $restricted_template = 'page.php';

    if ($template === $restricted_template) {
        // List the block names that you want to allow
        $allowed_blocks = array(
            'core/paragraph',
            'core/image',
            // Add more allowed block names here
        );
    }

    return $allowed_blocks;
}
add_filter('allowed_block_types', 'restrict_blocks_by_template', 10, 2);

//by post type
// function restrict_blocks_by_post_type($allowed_blocks, $post) {
//     // Define the post types for which you want to restrict blocks
//     $restricted_post_types = array('your_custom_post_type');

//     // Check if the current post's type is in the restricted list
//     if (in_array($post->post_type, $restricted_post_types)) {
//         // List the block names that you want to allow
//         $allowed_blocks = array(
//             'core/paragraph',
//             'core/image',
//             // Add more allowed block names here
//         );
//     }

//     return $allowed_blocks;
// }
// add_filter('allowed_block_types', 'restrict_blocks_by_post_type', 10, 2);


/**
 * Remove patterns from blocks
 */
add_filter('should_load_remote_block_patterns', '__return_false');

/**
 * Remove patterns that ship with WordPress Core.
 */
function gutenberg_removals()
{
    // remove_theme_support( 'core-block-patterns' );
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('editor-font-sizes', []);
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'gutenberg_removals');

/**
 * Color Palette
 */
add_theme_support(
    'editor-color-palette', array(
    array(
        'name'  => esc_attr__('white', 'themeLangDomain'),
        'slug'  => 'White',
        'color' => '#FFFFFF',
     ),
     array(
        'name'  => esc_attr__('cream', 'themeLangDomain'),
        'slug'  => 'Cream',
        'color' => '#E6DFD4',
     ),
     array(
        'name'  => esc_attr__('black', 'themeLangDomain'),
        'slug'  => 'Black',
        'color' => '#1A1D18',
     ),
     array(
        'name'  => esc_attr__('light gray', 'themeLangDomain'),
        'slug'  => 'LightGray',
        'color' => '#8F9191',
     ),array(
        'name'  => esc_attr__('brand: blue', 'themeLangDomain'),
        'slug'  => 'Blue',
        'color' => '#1B5299',
     ),
     array(
        'name'  => esc_attr__('brand: light-blue', 'themeLangDomain'),
        'slug'  => 'LightBlue',
        'color' => '#CFE4FF',
     ),
     array(
        'name'  => esc_attr__('brand: brown', 'themeLangDomain'),
        'slug'  => 'Brown',
        'color' => '#8F6D50',
     ),
     array(
        'name'  => esc_attr__('brand: neutral', 'themeLangDomain'),
        'slug'  => 'Neutral',
        'color' => '#4A3D25',
     ),
    //  array(
    //     'name'  => esc_attr__('brand: pink', 'themeLangDomain'),
    //     'slug'  => 'Pink',
    //     'color' => '#F75590',
    //  ),
     array(
        'name'  => esc_attr__('brand: gold', 'themeLangDomain'),
        'slug'  => 'Gold',
        'color' => '#A37C40',
     ),
     array(
        'name'  => esc_attr__('brand: green', 'themeLangDomain'),
        'slug'  => 'Green',
        'color' => '#3C896D',
     ),
     array(
        'name'  => esc_attr__('brand: light-green', 'themeLangDomain'),
        'slug'  => 'LightGreen',
        'color' => '#D0EFB1',
     ) ,
     array(
       'name'  => esc_attr__('brand: red', 'themeLangDomain'),
       'slug'  => 'Red',
       'color' => '#DB3A34',
     ),
     array(
      'name'  => esc_attr__('brand: yellow', 'themeLangDomain'),
      'slug'  => 'Yellow',
      'color' => '#FCFDAF',
     )   
    ) 
);



//filter Media & Text block output to add image caption
function media_block_caption( $block_content, $block )
{
    if ($block['blockName'] === 'core/media-text' ) {
        $mediaId = $block['attrs']['mediaId'];
        if($mediaId) {
            $image = get_post($mediaId);
            $image_caption = $image->post_excerpt?$image->post_excerpt:'';
            $image_attribution = get_field('attribution', $mediaId)? '<i>'.get_field('attribution', $mediaId).'</i>':'';
            if($image_caption || $image_attribution) {
                $content = str_replace('</figure>', '<figcaption class="h--caption">' . $image_caption . ' '.$image_attribution.'</figcaption></figure>', $block_content);
                return $content;
            }
        }
    }
    if ($block['blockName'] === 'core/post-featured-image' ) {
        $mediaId =  get_post_thumbnail_id(get_the_ID());
        if($mediaId) {
            $image = get_post($mediaId);
            $image_caption = $image->post_excerpt?$image->post_excerpt:'';
            $image_attribution = get_field('attribution', $mediaId)? '<i>'.get_field('attribution', $mediaId).'</i>':'';
            if($image_caption || $image_attribution) {
                $content = str_replace('</figure>', '<figcaption class="h--caption">' . $image_caption . ' '.$image_attribution.'</figcaption></figure>', $block_content);
                return $content;
            }
        }
    }
    return $block_content;
}
add_filter('render_block', 'media_block_caption', 10, 2);


//convert classname
function alterBgColorClass( $block_content, $block )
{
    if ($block['blockName'] === 'core/quote' ) {
        $content = str_replace('has-black-background-color', 'u-bgColorBlack u-darkMode', $block_content);
        $content = str_replace('has-blue-background-color', 'u-bgColorBlue u-darkMode', $content);
        $content = str_replace('has-red-background-color', 'u-bgColorRed u-darkMode', $content);
        $content = str_replace('has-green-background-color', 'u-bgColorGreen u-darkMode', $content);
        $content = str_replace('has-white-background-color', 'u-bgColorWhite u-lightMode', $content);
        return $content;
    }
    return $block_content;
}
add_filter('render_block', 'alterBgColorClass', 10, 2);
//post date/author function
function alterPostDate( $block_content, $block )
{
    if ($block['blockName'] === 'core/post-author-name' ) {
        $authors = get_field('authors') ? get_field('authors') : array('National Endowment for the Arts');

        $content = '<div class="inner-block--author">';
        $content .='<span class="h--caption">Author(s):</span>';
        $content .='<ul>';
        if(count($authors) > 1) {
            forEach($authors as $author){
                $content .='<li class="u-textSecondary u-textWeightMedium text-xs">' . $author['author'] . '</li>';
            }
        }else{
            $content .='<li class="u-textSecondary u-textWeightMedium text-xs">' . $authors[0] . '</li>';
        }

        $content .='</ul>';
        $content .= '</div>';

        return $content;
    };

    if ($block['blockName'] === 'core/post-date' ) {
        $date = get_the_date();
        $label = 'Published';
        if($block['attrs'] && $block['attrs']['displayType'] == 'modified') {
            $date = get_the_modified_date();
            $label = 'Modified';
        };
        $content = '<div class="inner-block--date">';
        $content .='<span class="h--caption">Date ' . $label . ': </span> ';
        $content .='<span class="u-textSecondary u-textWeightMedium text-xs">'. $date .'</span>';
        $content .='</span> ';
        $content .= '</div>';

        return $content;
    };
    return $block_content;
}
add_filter('render_block', 'alterPostDate', 10, 2);
