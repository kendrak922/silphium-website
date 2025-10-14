<?php
/**
 * ACF Field Groups for Understory Template
 */

// Add ACF fields for understory page settings
add_action('acf/init', 'understory_acf_fields');

function understory_acf_fields() {
    
    // Check if ACF is active
    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'group_understory_settings',
            'title' => 'Understory Page Settings',
            'fields' => array(
                array(
                    'key' => 'field_understory_subtitle',
                    'label' => 'Understory Subtitle',
                    'name' => 'understory_subtitle',
                    'type' => 'text',
                    'instructions' => 'Optional subtitle for the understory page',
                ),
                array(
                    'key' => 'field_understory_hero_color',
                    'label' => 'Hero Background Color',
                    'name' => 'understory_hero_color',
                    'type' => 'color_picker',
                    'instructions' => 'Choose the hero section background color',
                    'default_value' => '#1B4332',
                ),
                array(
                    'key' => 'field_understory_text_color',
                    'label' => 'Text Color',
                    'name' => 'understory_text_color',
                    'type' => 'color_picker',
                    'instructions' => 'Choose the main text color',
                    'default_value' => '#FDF6EC',
                ),
                array(
                    'key' => 'field_understory_accent_color',
                    'label' => 'Accent Color',
                    'name' => 'understory_accent_color',
                    'type' => 'color_picker',
                    'instructions' => 'Choose the accent color for highlights',
                    'default_value' => '#E9C46A',
                ),
                array(
                    'key' => 'field_understory_enable_parallax',
                    'label' => 'Enable Parallax Effects',
                    'name' => 'enable_parallax',
                    'type' => 'true_false',
                    'instructions' => 'Enable parallax scrolling effects',
                    'default_value' => 1,
                ),
                array(
                    'key' => 'field_understory_forest_overlay',
                    'label' => 'Enable Forest Overlay',
                    'name' => 'forest_overlay',
                    'type' => 'true_false',
                    'instructions' => 'Enable forest background overlay',
                    'default_value' => 1,
                ),
                array(
                    'key' => 'field_understory_ambient_sounds',
                    'label' => 'Enable Ambient Sounds',
                    'name' => 'ambient_sounds',
                    'type' => 'true_false',
                    'instructions' => 'Enable ambient forest sounds',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_understory_scroll_effects',
                    'label' => 'Enable Scroll Effects',
                    'name' => 'scroll_effects',
                    'type' => 'true_false',
                    'instructions' => 'Enable scroll-triggered animations',
                    'default_value' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-understory.php',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => 'Settings specifically for understory-themed pages',
        ));

    endif;
}