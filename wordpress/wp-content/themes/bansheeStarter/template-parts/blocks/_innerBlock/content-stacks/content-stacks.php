 <?php
 /**
  * Block: Content Stack    
  * - Slug: content-stack
  */

 // Block Variables
 $blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
 

 use Lean\Load;

 if (!defined('ABSPATH')) {
     exit; // Exit if accessed directly.
 }

 if (!have_rows('content_stack')) {
     return; // return early if there is no title, text, and actions to display
 }
 $blockData = [
    'color' => get_field('theme_colors') ? get_field('theme_colors') : '',
 ];

    ?>
<section class="section">
<div class="content-stacks">
    <?php if (have_rows('content_stack')) : ?>
        <?php while(have_rows('content_stack')) :
            the_row();
            $image = get_sub_field('image');
            $title = get_sub_field('title');
            $text = get_sub_field('text');
            $button = get_sub_field('button');
            ?>
                <div class="content-stack__single u-bgColor<?php echo $blockData['color']; ?>">
                    <?php if($image) : ?>
                        <div class="content-stack__single-image">
                        <?php if ($button) : ?>
                            <a aria-label="go to <?php echo $title; ?>" href="<?php echo $button['button_link']['url']; ?>">
                        <?php endif ; ?>
                            <img alt="<?php $image['alt']; ?>" src="<?php echo $image['url']; ?>"  />
                        <?php if ($button) : ?>
                            </a>
                        <?php endif; ?>
                        </div>
                    <?php endif; ?>
                        <?php if ($title) : ?>
                            <div class="content-stack__single-text">
                            <?php         // heading
                                    Load::atom(
                                        'text/heading',
                                        [
                                            'heading'         =>  $title,
                                            'heading_level'   => 'h3',
                                            'heading_style'   => 'h4'
                                        ]
                                    );
                            ?>
                        <?php endif;?>
                   <?php if($text) : ?>
                        <p><?php echo $text; ?> </p>
                        </div>
                   <?php endif; ?>
                    <?php if ($button) {
                        Load::atom(
                            'button/button',
                            [
                                'button'         => get_sub_field('button'),
                            ]
                        );
                    }?>
                </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
</section>
