<?php

/**
 * Post to Card
 * - converts a post_id to a card object that can be passed to a molecule for consistent styling
 * 
 * 
 * @param array $post_id - pass the post id,  $template - alters card format if necessary
 * @example
 * echo bansheeStarter_postToCard($post_id);
 */

function bansheeStarter_postToCard($post_id, $template = 'card')
{
    //image
    $size = 'medium';
    $image = get_the_post_thumbnail_url(get_the_ID(), $size);
    //set card
    $card = [
        'card_image' => [
            'url' => $image,
            'alt' => '',
            'title' => ''
        ],
        'card_title' => get_the_title($post_id),
        'card_subtitle' => get_the_date('F j, Y', $post_id),
        'card_caption' => get_the_excerpt($post_id),
        'card_buttons' => [
            [
                'button' => [
                    'button_type' => 'link',
                    'button_style' => 'solid',
                    'button_link' => [
                        'title' => 'Read More',
                        'url' => get_the_permalink($post_id),
                        'aria' => 'Read More: ' . get_the_title($post_id)
                    ]
                ]
            ]
        ],
        'card_url' => get_the_permalink($post_id)
    ];
    return $card;
}



/**
 * Event to Card
 * - converts an "Event" post_id to a card object that can be passed to a molecule for consistent styling
 * 
 * 
 * @param array $post_id - pass the post id,  $template - alters card format if necessary
 * @example
 * echo bansheeStarter_eventToCard($post_id);
 */

function bansheeStarter_eventToCard($post_id, $template = 'card')
{
    $startDate = get_field('start_date', $post_id);
    $event_date = $startDate ? get_the_date('F j, Y', $startDate) :  get_the_date('F j, Y', $post_id);

    //image
    $size = 'medium';
    $image = get_the_post_thumbnail_url(get_the_ID(), $size);

    //set card
    $card = [
        'card_image' => [
            'url' => $image,
            'alt' => '',
            'title' => ''
        ],
        'card_title' => get_the_title($post_id),
        'card_subtitle' => $event_date,
        'card_caption' => get_the_excerpt($post_id),
        'card_buttons' => [
            [
                'button' => [
                    'button_type' => 'link',
                    'button_style' => 'solid',
                    'button_link' => [
                        'title' => 'Learn More',
                        'url' => get_the_permalink($post_id),
                        'aria' => 'Learn More about the ' . get_the_title($post_id) . ' event'
                    ]
                ]
            ]

        ],
    ];
    return $card;
}
