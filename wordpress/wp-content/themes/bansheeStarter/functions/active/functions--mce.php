<?php
function bansheeStarter_add_style_select_buttons($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'bansheeStarter_add_style_select_buttons');

//add custom styles to the WordPress editor
function bansheeStarter_my_custom_styles($init_array)
{
    // text formats
    $block_formats = array(
        'Paragraph=p',
        'Heading 1=h1',
        'Heading 2=h2',
        'Heading 3=h3',
        'Heading 4=h4',
        'Heading 5=h5',
        'Heading 6=h6',
        'Cite=cite',
        'Preformatted=pre',
    );
    $init_array['block_formats'] = implode(';', $block_formats);

    // text styles
    $style_formats = array(
        array(
            'title' => 'Primary Button',
            'selector' => 'a',
            'classes' => 'btn--solid'
        ),
        array(
            'title' => 'Secondary Button',
            'selector' => 'a',
            'classes' => 'btn--secondary'
        ),
        array(
            'title' => 'Outline Button',
            'selector' => 'a',
            'classes' => 'btn--outline'
        ),
        array(
            'title' => 'Button Group',
            'selector' => 'p',
            'classes' => 'btn__container grid grid--gutters-narrow u-marginBottom6gu u-md-marginBottom12gu',
            'block' => 'div',
        ),
        array(
            'title' => 'Citation',
            'selector' => 'p',
            'block' => 'cite',
        ),
    );
    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;
}
// Attach callback to 'tiny_mce_before_init' 
add_filter('tiny_mce_before_init', 'bansheeStarter_my_custom_styles');
