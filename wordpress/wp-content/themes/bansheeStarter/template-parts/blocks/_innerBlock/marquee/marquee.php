<?php
 /**
  * Block: Marquee  
  * - Slug: marquee
  */

 // Block Variables
 $blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!have_rows('marquee')) {
    return; // return early if there is no title, text, and actions to display
};


?>
    
<section class="section marquee">
<div class="marquee__container">
    <?php if (have_rows('marquee')) : ?>
        <?php while(have_rows('marquee')) :
            the_row();
            $image = get_sub_field('image');
            $text = get_sub_field('text');;
            ?>
                <div class="marquee__item">
                    <?php if($image) : ?>
                        <div class="marquee__item-image">
                            <img alt="" height="45px" width="45px" class="skip-lazy" src="<?php echo $image['url']; ?>"  />
                        </div>
                    <?php endif; ?>
                    <div class="marquee__item-text">
                       <span class="text-xl text u-textColorBlack"> <?php 
                        if ($text) :
                            echo $text; 
                        endif;
                        ?> </span>
                    </div>
                </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</section>
