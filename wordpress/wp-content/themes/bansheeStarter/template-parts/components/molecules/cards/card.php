<?php

use Lean\Load;

$base = $args['base'] ?? [];
$classes =  $args['classes'] ?? [];
$template = $args['template'];

$card = $args['card'] ?? null;
$post_id = $args['post_id'];

if (!$card && !$post_id) {
    //exit molecule
    return;
}

//GENERATE A CARD
if ($post_id) :
    $type = get_post_type($post_id);
    if ($type == 'events') {
        $card = bansheeStarter_eventToCard($post_id, $template);
        $classes[] = 'card--event';
    } else {
        $card = bansheeStarter_postToCard($post_id, $template);
        $classes[] = 'card--article';
    }
endif;

//card classes
$classes[] = $template;
if (isset($card['card_url'])) :
    $classes[] = "card--clickable";
endif;

$base_classes = preg_filter('/$/', '__card', $base);
$classes = array_merge($classes, $base_classes);
$classes = $classes ? implode(' ', $classes) : '';

//add base for molecule
$base[] = 'card';
?>



<article data-molecule="card" class="card <?php echo $classes; ?>" aria-label="<?php echo $card['card_title']; ?>" <?php if (isset($card['card_url'])) : ?> tabindex="0" onclick="location.href='<?php echo $card['card_url']; ?>';" <?php endif; ?>>

    <?php if ($card['card_image']) :
        $image = $card['card_image']['url'];
        $image_alt = $card['card_image']['alt'] ? $card['card_image']['alt'] : $card['card_image']['title']; ?>
        <div class="card__media">
            <div class="card__image" style="background-image: url(<?php echo $image; ?>);"></div>
        </div>
    <?php endif; ?>

    <div class="card__content">
        <?php if ( isset($card['card_chips'])) : ?>
            <div class="grid grid--gutters-narrow u-marginBottom2gu">
                <?php
                foreach ($card['card_chips'] as $chip) :
                    // Atom: Chip
                    Load::atom(
                        'chip/chip',
                        [
                            'base'       => $base,
                            'chip'       => $chip
                        ]
                    );
                endforeach
                ?>
            </div>
        <?php endif; ?>

        <?php
        // Atom: Heading
        Load::atom(
            'text/heading',
            [
                'base'            => $base,
                'heading'         => $card['card_title'],
                'heading_level'   => 'h3',
                'heading_style'   => 'h--subheading u-textWeightBold card__heading'
            ]
        );
        ?>

        <?php if (isset($card['card_subtitle']) && $card['card_subtitle']) : ?>
            <div class="card__subheading u-textWeightSemiBold u-textColorNeutral600">
                <?php echo $card['card_subtitle']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($card['card_caption']) && $card['card_caption']) : ?>
            <div class="card__caption u-wysiwyg">
                <?php echo $card['card_caption']; ?>
            </div>
        <?php endif; ?>

        <?php
        // Molecule: Button Group
        if (isset($card['card_buttons']) && $card['card_buttons']) :
            Load::molecule(
                'buttons/button-group',
                [
                    'base'            => $base,
                    'buttons'         => $card['card_buttons'],
                ]
            );
        endif; ?>

    </div>
</article>