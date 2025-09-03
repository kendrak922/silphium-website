<?php

use Lean\Load;

// Load::atom(
//     'text/seperator',
//     [
//         'base'            => ['content-seperator'],
//         'heading'         => 'seperator Title',
//         'heading_level'   => 'h3',
//         'heading_style'   => 'h--seperator',
//     ]
// );

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$heading = $args['heading'] ?? null;
$heading_level = $heading ? $args['heading_level'] ?? 'h3' : 'div';
$heading_style = $args['heading_style'] ? $args['heading_style'] : $args['heading_style'];
$text_color = get_field('theme_colors') ? 'Neutral' : get_field('theme_colors');

$base_classes = preg_filter('/$/', '__seperator', $base);
$classes = array_merge($classes, $base_classes);
$classes[] = $heading_style;
if($heading) :
    $classes[] = 'has-text u-textWeightBold u-marginBottom0gu u-textPrimary';
endif;

if($text_color) :
    $classes[] = 'u-textColor'.$text_color;
endif;

$classes = $classes ? implode(' ', $classes) : '';
?>

<<?php echo $heading_level; ?>  data-atom="seperator" class="content-seperator <?php echo $classes; ?>">
        <?php    
        Load::atom(
                'icon/icon',
                [
                    'icon'     => [
                        'type'  => 'inline',
                        'svg_icon' => 'icon-starburst', // type: svg
                    ]
                ]
            ); ?>
   <span> <?php echo $heading; ?> </span>
</<?php echo $heading_level; ?>>