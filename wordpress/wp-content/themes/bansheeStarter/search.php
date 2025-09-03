<?php

/**
 * The template for displaying search results pages
 */

use Lean\Load;

get_header();
?>

<div class="page__wrapper template--search">
	<div class="container">
		<div class="u-textAlignCenter u-marginTop12gu u-md-marginTop20gu">
			<h1 class="h2 h2--book u-textUppercase u-marginBottom15gu">
				Search Results: <span class="u-textColorTeal"><?php echo get_search_query(); ?></span>
			</h1>
			<?php echo get_search_form(); ?>

			<?php if (get_search_query()) : ?>
				<div class="u-textColorNeutral600 u-marginTop8gu">
					<?php
					global $wp_query;
					if ($wp_query->found_posts == 1) {
						$result = "result";
					} else {
						$result = "results";
					}
					echo $wp_query->found_posts . " " . $result . " found.";

					if (function_exists('relevanssi_didyoumean')) {
						relevanssi_didyoumean(get_search_query(false), '<br/><br/>Did you mean: ', '', 5);
					}
					?>
				</div>
			<?php endif; ?>
		</div>

		<?php if (have_posts()) : ?>

			<?php if (get_search_query()) : ?>
				<div class="search__results u-marginTop20gu">
					<?php while (have_posts()) : the_post();
						$postType = get_post_type_object(get_post_type()); ?>

						<article id="post-<?php the_ID(); ?>" class="search-result u-marginBottom10gu" aria-label=" <?php echo the_title(); ?>">
							<h2 class="search-result__title h--subheading u-marginBottom3gu u-textWeightBold">
								<?php the_title(sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())), " | " . esc_html($postType->labels->singular_name) . '</a>'); ?>
							</h2>
							<div class="search-result__excerpt">
								<?php
								echo  get_the_excerpt(); ?>
							</div>
							<div class="search-result__button">
								<a href=" <?php the_permalink(); ?>" class="u-marginTop4gu btn btn--text" aria-label=" View More: <?php echo the_title(); ?>">View More</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<div class="search-pagination u-textAlignCenter u-marginVert15gu u-md-marginVert25gu">
					<?php
					$searchPages = $wp_query->max_num_pages;
					$theBigNumber = 999999999;
					$paginateSearchArgs = array(
						'base' => str_replace($theBigNumber, '%#%', esc_url(get_pagenum_link($theBigNumber))),
						'format' => '?page = %#%',
						'current' =>  max(1, get_query_var('paged')),
						'total' => $searchPages,
						'mid_size' => 1,
						'prev_text'          => __('« Prev'),
						'next_text'          => __('Next »'),
					);
					echo paginate_links($paginateSearchArgs);
					?>
				</div>
			<?php else : ?>
				<div class="search__results u-marginVert15gu u-md-marginVert25gu">
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="search__results u-marginVert15gu u-md-marginVert25gu">
			</div>
		<?php endif; ?>


	</div>
</div>

<?php
get_footer();
