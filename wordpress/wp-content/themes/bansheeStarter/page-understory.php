<?php
/*
 * Template Name: Understory Page
 * Description: Custom page template for understory-themed content with immersive forest-like experience
 */

use Lean\Load;

get_header('understory');

// Declare global variables
global $templateData;

// Set Page Data
$templateData = [
    'post' => get_post(),
    'blocks' => '',
    'understory_settings' => [
        'enable_parallax' => get_field('enable_parallax') ? true : false,
        'forest_overlay' => get_field('forest_overlay') ? true : false,
        'ambient_sounds' => get_field('ambient_sounds') ? true : false,
        'scroll_effects' => get_field('scroll_effects') ? true : false,
    ]
];

// Parse page blocks
if (has_blocks($templateData['post']->post_content)) {
    $templateData['blocks'] = parse_blocks($templateData['post']->post_content);
}

// DEBUG
// debug_to_console($templateData, 'Understory $templateData');

$understoryClasses = ['page__wrapper', 'understory-page'];
$understoryData = '';

// Add understory-specific classes and data attributes
if ($templateData['understory_settings']['enable_parallax']) {
    $understoryClasses[] = 'understory-parallax';
}
if ($templateData['understory_settings']['forest_overlay']) {
    $understoryClasses[] = 'understory-forest-overlay';
}
if ($templateData['understory_settings']['ambient_sounds']) {
    $understoryData .= ' data-ambient-sounds="true"';
}
if ($templateData['understory_settings']['scroll_effects']) {
    $understoryData .= ' data-scroll-effects="true"';
}
?>

<!-- Understory Forest Background -->
<?php if ($templateData['understory_settings']['forest_overlay']): ?>
<div class="understory-forest-bg">
    <!-- <div class="forest-layer forest-layer--1"></div>
    <div class="forest-layer forest-layer--2"></div>
    <div class="forest-layer forest-layer--3"></div> -->
    <div class="forest-layer forest-layer--4"></div>
</div>
<?php endif; ?>

<div class="<?php echo implode(' ', $understoryClasses); ?>"<?php echo $understoryData; ?>>
    
    <!-- Understory Hero Section -->
    <section class="understory-hero">
        <div class="container container--ultra-wide">
            <div class="understory-hero__content">
                <!-- <div class="forest-canopy"></div> -->
                <div class="understory-content">
                    <h1 class="understory-title"><?php the_title(); ?></h1>
                    <?php if (get_field('understory_subtitle')): ?>
                        <p class="understory-subtitle"><?php echo get_field('understory_subtitle'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="forest-floor"></div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <?php if (have_posts()) : ?>
        <div id="page_content" class="content understory-content-area container--full">
            <?php while (have_posts()) : the_post(); ?>
                <div class="understory-content-wrapper">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Ambient Sound Controls (if enabled) -->
<?php if ($templateData['understory_settings']['ambient_sounds']): ?>
<div class="understory-sound-controls">
    <button class="sound-toggle" aria-label="Toggle ambient forest sounds">
        <span class="sound-icon">🌲</span>
        <span class="sound-text">Forest Sounds</span>
    </button>
</div>
<?php endif; ?>

<?php get_footer('understory'); ?>