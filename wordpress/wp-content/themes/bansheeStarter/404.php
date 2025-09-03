<?php

/**
 * The template for displaying the 404 template in the Twenty Twenty theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

use Lean\Load;

get_header();
?>

<div class="page__wrapper template--404">

	<section class="block block--404 u-textAlignCenter">
		<div class="container container--wide">

			<!-- <div class="u-marginBottom6gu u-paddingHoriz12gu">
				<img src="/wp-content/themes/bansheeStarter/assets/dist/imgs/404-graphic.png" alt="404" class="u-alignCenter" />
			</div> -->

			<?php
			// Molecule: Block Header
			$blockHeader = [
				'title' => 'We’re sorry! This page does not exist.',
				'title_level' => 'h2',
				'title_style' => 'h3',
				'eyebrow' => '404',
				'eyebrow_level' => 'h1',
				'caption' => 'The page you were looking for might have been deleted, moved, or possibly never existed.',
				'buttons' => [
					[
						'button' => [
							'button_type' => 'link',
							'button_style' => 'solid',
							'button_link' => [
								'url' => '/',
								'title' => 'Go Home'
							]
						]
					]
				]
			];
			Load::molecule(
				'heading/block-heading',
				[
					'block-heading' => $blockHeader,
					'classes' => ['u-textAlignCenter u-animation'],
				]
			);
			?>
		</div>
	</section>

</div>


<?php
get_footer();
