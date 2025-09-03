<?php
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

$hasSidebar = is_front_page() || get_field('hide_sidebar') ? false : true;
$toc = get_field('table_of_contents');
?>

<?php if($hasSidebar):?>
<a class="screen-reader-text skip-link" href="#page_content">Skip to content</a>
<?php endif;?>

<div <?php post_class($hasSidebar?'page__wrapper--sidebar':'page__wrapper'); ?>>
	<?php if($hasSidebar):?>
        <div id="sidebar" class="sidebar">
            <?php if($toc): ?>
                <aside class="aside--toc u-bgColorWhite">
                    <div class="sidebar__container">
                        <div class="toc__container">
                            <div class="toc__title h--smNav">
                                Table of Contents
                            </div>
                            <nav id="table_of_contents" class="sidebar__menu" aria-label="Inner Jump Navigation">
                                <ul class="menu menu--toc">
                                    <?php foreach($toc as $toc_item):?>
                                        <li class="menu-item">
                                            <a href="<?php echo $toc_item['section_id'];?>">
                                                <?php echo $toc_item['title'];?> <span class="sr-only">(jump to section)</span>
                                            </a>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </aside>
            <?php else: ?>
                <aside class="u-bgColorOatmeal">
                    <div class="sidebar__container">
                        <div></div>
                        <div class="sidebar__footer">
                            <?php echo get_field("sidebar_text",'option');?>
                        </div>
                    </div>
                </aside>
            <?php endif;?>
        </div>
	<?php endif; ?>

	<?php if (have_posts()) : ?>
		<div id="page_content" class="content container--full">
			<?php while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

</div>

<?php get_footer('default',array('hasSidebar'=> $hasSidebar)); ?>