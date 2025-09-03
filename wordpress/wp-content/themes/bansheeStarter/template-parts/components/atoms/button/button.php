<?php

/**
 * Atom: Button
 */

use Lean\Load;

$base =  $args['base'] ?? [];
$classes = $args['classes'] ?? [];
$button = $args['button'] ?? [];

if (!$button) {
    //exit atom
    return;
}

$href = '';
$label = '';
$target = '';
$aria = '';

// Default classes to empty string
if (!isset($button['classes'])) {
    $button['classes'] = '';
}

// Define button style via classes
if (isset($button['button_style'])) {
    $button['classes'] .= ' btn--' . $button['button_style'] . ' ';
    if ($button['button_style'] === 'solid') :
        $button['classes'] .= ' btn--primary ';
    elseif ($button['button_style'] === 'seconday') :
            $button['classes'] .= ' btn--solid ';
    elseif ($button['button_style'] === 'border') :
        $button['classes'] .= ' btn--outline ';
    endif;
}

// $button['button_icon'] = 'utility-login';  //test icon
if (isset($button['button_icon'])) :
    $button['classes'] .= ' btn--icon';
endif;

// Button size variants
if (isset($button['button_size'])) {
    $button['classes'] .= ' btn--' . $button['button_size'] . ' ';
}

// Ensure the button has a link value
if (array_key_exists('button_type', $button)) :
    if ($button['button_type'] == 'link' && $button['button_link']) :
        $href = $button['button_link']['url'];
        $label = $button['button_link']['title'];
        $target = $button['button_link']['target'] ?? '';
        // Build ADA aria-label - provide link destination context based on external/internal
        $aria = $button['button_aria_label'] ?? $button['button_link']['title'] . ' - Navigate to' . ($target === '_blank' ? ' offsite link: ' : ': ') . $href;

    elseif ($button['button_type'] == 'anchor' && $button['button_anchor']) :
        $button['classes'] .= ' btn--anchor ';
        $href = $button['button_anchor'];
        $label = $button['button_label'];
        // Build ADA aria-label - provide destination context based on anchor
        $aria = $button['button_aria_label'] ? $button['button_aria_label'] : $button['button_label'] . ' - Jump to' . $href;

    elseif ($button['button_type'] == 'file') :
        if ($button['button_file']) :
            $href = $button['button_file']['url'];
            $label = $button['button_label'];
            $aria = (strpos($button['button_label'], "Download") !== false) ? $button['button_label'] : 'Download ' . $button['button_file']['filename'];
        endif;
    endif;
endif;

if (!$label) {
    //exit atom
    return;
}

$aria = str_replace('"', "'", $aria);

?>

<?php /**********
       * BUILD BUTTON HTML 
       **********/

?>
<a data-atom="button" class="btn <?php echo $button['classes']; ?>" href="<?php echo $href; ?>" target="<?php echo $target; ?>" <?php if($aria) :?>aria-label="<?php echo $aria; ?>"<?php 
endif;?> <?php echo isset($button['button_file']) && $button['button_file'] ? 'download' : ''; ?>>
<?php if ($button['button_style'] == 'arrow') :?>
        <span>
            <?php echo $label; ?>
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
    <?php else:
        if (isset($button['button_icon'])) :
            Load::atom(
                'icon/icon',
                [
                    'icon'     => [
                        'type'  => 'inline',
                        'svg_icon' => $button['button_icon'], // type: svg
                    ]
                ]
            );
        endif; ?>
        
        <span>
            <?php echo $label; ?>
        </span>

        <?php // download arrow
        if($button['button_type'] == 'file') :?>
            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
                <path d="M1 5.5L6 10.5L11 5.5" stroke="#1A1818" stroke-width="1.5"/>
                <path d="M6 0.5V10.5" stroke="#1A1818" stroke-width="1.5"/>
                <path d="M0 13.5H12" stroke="#1A1818" stroke-width="0.5"/>
            </svg>
        <?php endif?>
    <?php endif;?>
</a>