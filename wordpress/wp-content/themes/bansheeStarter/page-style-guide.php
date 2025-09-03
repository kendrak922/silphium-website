<?php

/**
 * The default page template file
 */

get_header();

// Declare global variables
global $templateData;

// Set Global Template Data
$templateData = [
  'post' => get_post(),
  'blocks' => '',
];


// Parse page blocks
if (has_blocks($templateData['post']->post_content)) {
    $templateData['blocks'] = parse_blocks($templateData['post']->post_content);
}

// Debug
// debug_to_console($templateData, 'Page $templateData');
?>
<div class='page__wrapper'>
	<?php if (have_posts()) : ?>
		<div id="page_content" class="content container--full">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

  <?php
    /**
     * GENERAL
     */
    ?>

<section class="block">
    <div class="container container--narrow">
      <h2>General Best Practices:</h2>

      <?php
        /**
         * COLORS
         */
        ?>

  <section class="block">
    <div class="container container--narrow">
      <h2>Colors:</h2>
      <p>These colors are very French to me! The site overall will be simple, but some color will be fun to play with here and there.</p>
      <a href="https://coolors.co/" target="_blank" >I use this tool to make sure colors go together</a>
  <br></br>

    <?php

      $colors = [
        'Primary',
        'Secondary',
        'Tertiary',
        // Text color
        'Neutral',
        // Style Guide
        'Blue',
        'LightBlue',
        'Yellow',
        'Green',
        'LightGreen',
        'Gray',
        'Red',
        'Gold',
        'Black',
        'White',
        'Success',
        'Error',
      ];
      ?>

      <div class="container grid grid--gutters">
        <?php foreach ($colors as $key => $color) : ?>
          <div class="grid__col grid__col--12 grid__col-sm--6--spaced grid__col-md--4--spaced u-marginBottom8gu grid grid--column">
            <h5 class="u-textColorWhite u-bgColor<?php echo $color; ?> u-paddingVert8gu u-paddingHoriz4gu u-textSizePlus2 u-marginBottom2gu"><?php echo $color; ?></h5>
          </div>
          <!--.grid__col-->
        <?php endforeach; ?>
      </div>
      <!--.container-->
    </section>
    <!--.block-->
    <section class="block">
    <div class="container container--narrow">
      <h2 class="u-marginBottom8gu">Fonts:</h2>
      Having a seperate header and body font is pretty - and helps us organize content. Heading sizes are important for information architecture and for screen readers. You can learn more about accessibility concerns and headings here: <a href="https://www.w3.org/WAI/tutorials/page-structure/headings/" target="_blank">Heading Guidelines</a>
      Here are also some resources I use to pick fonts if the ones I chose aren't feeling right.
      <a href="https://design.google/library/choosing-web-fonts-beginners-guide" target="_blank" >https://design.google/library/choosing-web-fonts-beginners-guide</a>
      <br>
      <a href="https://fonts.google.com/" target="_blank" >  https://fonts.google.com/</a>
      <br>
      <a href="https://fontpair.co/" target="_blank" >https://fontpair.co/</a>
      <div>
            <h5 class="u-textPrimary u-marginBottom8gu u-marginTop16gu">Primary (body) Font</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="grid__col grid__col--12 grid__col-sm--6--spaced grid__col-md--4--spaced u-marginBottom8gu grid grid--column">
            <h5 class="u-textSecondary u-marginBottom8gu u-marginTop16gu">Secondary (Header) Font</h5>
            <h1>h1 - used for page titles</h1>
            <h2>h2 - used for section titles</h2>
            <h3>h3 - used for sections within sections</h3>
      </div>
      <div class="grid__col grid__col--12 grid__col-sm--6--spaced grid__col-md--4--spaced u-marginBottom8gu grid grid--column">
            <h2 class="u-textTertiary u-marginBottom8gu u-marginTop16gu u-textSizePlus8">Accent Font</h2>
            <p class="u-textSizePlus8 u-textTertiary">This is the font we use sparingly when we need some pizzaz </p>
          </div>
    <?php

      ?>

      <div class="container grid grid--gutters">
          
      </div>
      <!--.container-->
    </section>
  </div>

  </div>

<?php get_footer(); ?>