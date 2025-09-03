 <?php
 /**
  * Block: Testimonial    
  * - Slug: testimonial
  */

 // Block Variables
 $blockID = (!empty($block['anchor']) ? $block['anchor'] : $block['id']);
 

 use Lean\Load;

 if (!defined('ABSPATH')) {
     exit; // Exit if accessed directly.
 }

 if (!have_rows('testimonial')) {
     return; // return early if there is no title, text, and actions to display
 };


    ?>
    
<section class="swiper u-lg-hidden">
<div class="testimonial swiper-wrapper">
    <?php if (have_rows('testimonial')) : ?>
        <?php while(have_rows('testimonial')) :
            the_row();
            $image = get_sub_field('image');
            $emphasized_text = get_sub_field('emphasized_text');
            $text = get_sub_field('text');
            $author = get_sub_field('author');
            ?>
                <div class="testimonial__single swiper-slide">
                    <?php if($image) : ?>
                        <div class="testimonial__single-image">
                            <img alt="" src="<?php echo $image['url']; ?>"  />
                        </div>
                    <?php endif; ?>
                    <div class="testimonial__single-content u-bgColorLightBlue">
                        <?php 
                        if($emphasized_text) : 
                            // heading
                            Load::atom(
                                'text/heading',
                                [
                                    'heading'         =>  $emphasized_text,
                                    'heading_level'   => 'h3',
                                    'heading_style'   => 'display-quote'
                                ]
                            );
                        endif;
                        ?>
                        <?php 
                        if ($text) :
                            echo $text; 
                        endif;
                        ?>
                        <?php 
                        if ($author) :
                            echo '<p class="testimonial__single-author">— '.$author.'</p>'; 
                        endif;
                        ?> 
                    </div>
                </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<div class="swiper-pagination"></div>
</section>
<section class="section u-hidden u-lg-block">
<div class="testimonial testimonial--desktop">
    <?php if (have_rows('testimonial')) : ?>
        <?php while(have_rows('testimonial')) :
            the_row();
            $image = get_sub_field('image');
            $emphasized_text = get_sub_field('emphasized_text');
            $text = get_sub_field('text');
            $author = get_sub_field('author');
            ?>
                <div class="testimonial__single u-bgColorLightBlue">
                    <?php if($image) : ?>
                        <div class="testimonial__single-image">
                            <img class="skip-lazy" src="<?php echo $image['url']; ?>"  />
                        </div>
                    <?php endif; ?>
                    <div class="testimonial__single-content">
                        <?php 
                        if($emphasized_text) : 
                            // heading
                            Load::atom(
                                'text/heading',
                                [
                                    'heading'         =>  $emphasized_text,
                                    'heading_level'   => 'h3',
                                    'heading_style'   => 'display-quote'
                                ]
                            );
                        endif;
                        ?>
                        <?php 
                        if ($text) :
                            echo $text; 
                        endif;
                        ?>
                        <?php 
                        if ($author) :
                            echo '<p class="testimonial__single-author">— '.$author.'</p>'; 
                        endif;
                        ?> 
                    </div>
                </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</section>
