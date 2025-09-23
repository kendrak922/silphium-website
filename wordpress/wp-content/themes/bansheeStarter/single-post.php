<?php

/**
 * The default page template file
 */

use Lean\Load;

get_header();

// Declare global variables
global $templateData;

// Set Page Data
$templateData = [
	'post' => get_post(),
	'blocks' => '',
	'show_menu' =>  get_field('show_menu') ? true : false,
	'has_sidebar' => get_field('hide_sidebar') ? false : true
];

// Parse page blocks
if (has_blocks($templateData['post']->post_content)) {
	$templateData['blocks'] = parse_blocks($templateData['post']->post_content);
}

// DEBUG
// debug_to_console($templateData, 'Single $templateData');

?>

<div class='page__wrapper single-post'>
	<?php if (have_posts()) : ?>
		<div id="page_content" class="content container--narrow">
			<?php while (have_posts()) : the_post(); ?>
				<h1><?php echo the_title(); ?></h1>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

</div>
