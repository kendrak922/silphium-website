<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();

$block_content = '<!-- wp:acf/spacer {"name":"acf/spacer","data":{"spacer_height_large":"7.5rem","_spacer_height_large":"field_67eaf3dd13fa4","spacer_height_medium":"5.5rem","_spacer_height_medium":"field_67eaf48513fa6","spacer_height_small":"4.5rem","_spacer_height_small":"field_67eaf48213fa5"},"mode":"preview"} /--><!-- wp:acf/page-banner {"name":"acf/page-banner","data":{"field_688a672cab2de":{"field_688a672cab2de_field_5f3f4d7639983":"Black"},"field_688a670e7de72":"390","field_688a670e7dfd8":{"field_688a670e7dfd8_field_678808b642d89":"center right"},"field_688a670e7e0c1":{"field_688a670e7e0c1_field_67f81ebaf7a3e":"full"},"field_688a670e7e13b":"20","field_688a675f718fe":"Half"},"mode":"preview"} -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:acf/spacer {"name":"acf/spacer","data":{"spacer_height_large":"1rem","_spacer_height_large":"field_67eaf3dd13fa4","spacer_height_medium":"1rem","_spacer_height_medium":"field_67eaf48513fa6","spacer_height_small":"1rem","_spacer_height_small":"field_67eaf48213fa5"},"mode":"preview"} /-->

<!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|White"}}},"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"textColor":"White"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">Events &amp; Activities</h1>
<!-- /wp:heading -->

<!-- wp:acf/spacer {"name":"acf/spacer","data":{"spacer_height_large":"1rem","_spacer_height_large":"field_67eaf3dd13fa4","spacer_height_medium":"1rem","_spacer_height_medium":"field_67eaf48513fa6","spacer_height_small":"1rem","_spacer_height_small":"field_67eaf48213fa5"},"mode":"preview"} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:acf/page-banner -->';
    
echo do_blocks($block_content);
echo tribe(Template_Bootstrap::class)->get_view_html();

get_footer();
