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

$hasSidebar = (!is_front_page() && $templateData['has_sidebar']);
?>

<?php if($hasSidebar):?>
<a class="screen-reader-text skip-link" href="#page_content">Skip to content</a>
<?php endif;?>

<div class='page__wrapper'>
	<?php if (have_posts()) : ?>
		<div id="page_content" class="content container--full">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

</div>

<?php get_footer('default',array('hasSidebar'=> $hasSidebar)); ?>