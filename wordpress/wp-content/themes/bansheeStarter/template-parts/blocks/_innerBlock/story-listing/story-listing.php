<?php
/**
 * Block: Story Listing
 * - Slug: story-listing
 */

use Lean\Load;

// see functions--listing-queries.php for data handling

// Block Variables
$blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);

// BLOCK :: DATA
$blockData = array(
    'title' => get_field('title') ?? 'Our Work',
    'title_level' => get_field('title_level') ?? 'h2',
    'pull_type' => get_field('pull_type') ?? 'manual',
);

// BLOCK :: CLASSES
$classes = [ 'inner-block--story-listing' ];
if (! empty($block['className']) ) {
    $classes = array_merge($classes, explode(' ', $block['className']));
}

$storylisting[] = storiesFromQuery()

// BLOCK :: RENDER
?>

<div id="<?php echo $blockID; ?>" class="inner-block content-listing story-listing <?php echo join(' ', $classes) ?>">

    <div class="list__story-listing content-listing">
        <?php 
        foreach( $storylisting[0] as  $key=>$story):
            ?>
            <?php $tags = get_terms('post_tag'); ?>
            <div class="resource__overview content-entry text-sm card--clickable" onclick="location.href='<?php echo $story['url']; ?>';"> 
                <div class="content-entry__image <?php if(!$story['imageID']) :?>u-hidden u-md-block<?php 
               endif?>">
                    <?php if($story['imageID']) :?>
                        <?php echo wp_get_attachment_image($story['imageID'], 'medium', '', array('class' => 'lazyload')); ?>
                    <?php endif;?>
                </div>
                <div class="content-entry__text">
                    <a class="content-entry__title" href="<?php echo $story['url'];?>">
                        <?php 
                        Load::atom(
                            'text/heading',
                            [
                                'heading'         => $story['title'],
                                'heading_level'   => 'h3',
                                'heading_style'   => 'h2 u-marginBottom2gu',
                            ]
                        );?>
                    </a>
                    <?php if($tags) : ?>
                        <div class="content-entry__tags">
                        <?php foreach (get_the_terms(get_the_ID(), 'post_tag') as $cat): {
                                    echo '<a href="' . get_term_link($cat) . '">' . $cat->name . '</a> <span class="content-entry__seperator">|</span> ';
                            }
                        endforeach; ?>
                        </div>
                    <?php endif;?>
                </div>
            </div> 

        <?php endforeach;?>
    </div>
</div>