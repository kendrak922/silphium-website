<?php
/*
 * Template Name: Landing Page
 * Description: Page without header or footer navigation
 */

use Lean\Load;

get_header();

// Declare global variables
global $templateData;

// Set Page Data
$templateData = [
	'post' => get_post(),
	'blocks' => '',
];

// Parse page blocks
if (has_blocks($templateData['post']->post_content)) {
	$templateData['blocks'] = parse_blocks($templateData['post']->post_content);
}

// DEBUG
// debug_to_console($templateData, 'Single $templateData');

?>


<div class='page__wrapper landing-page'>
	<?php if (have_posts()) : ?>
		<div id="page_content" class="content container">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

</div>

<?php get_footer(); ?>