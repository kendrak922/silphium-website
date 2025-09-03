<?php

use Lean\Load;

/**
 * The default page template file
 */

get_header();

// Declare global variables
global $templateData;
?>

<div <?php post_class('page__wrapper'); ?>>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			
			<?php 
			if (get_post_format() == false) {
				Load::molecule(
					'cards/card',
					[
						'post_id' => get_the_ID(),
						'template' => 'card'
					]
				);
			} else {
				Load::molecule(
					'cards/card',
					[
						'post_id' => get_the_ID(),
						'template' => 'card--'.get_post_format()
					]
				);
			}
			?>

		<?php endwhile; ?>
	<?php endif; ?>

</div>

<?php get_footer(); ?>