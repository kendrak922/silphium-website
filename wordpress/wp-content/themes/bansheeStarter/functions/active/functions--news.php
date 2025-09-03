<?php

use Lean\Load;

/**********************
 * Fix News Permalinks
 **********************/
/* add impact-and-news before news links (post type: Post)*/
// function add_rewrite_rules($wp_rewrite)
// {
//     $new_rules = array(
//         'news/([0-9]{4})/([0-9]{2})/(.+?)/?$' => 'index.php?post_type=post&name=' . $wp_rewrite->preg_index(3),
//     );
//     $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
// }
// add_action('generate_rewrite_rules', 'add_rewrite_rules');

// function change_blog_links($post_link, $id = 0)
// {
//     $post = get_post($id);
//     $year =  date("Y", strtotime($post->post_date));
//     $month = date("m", strtotime($post->post_date));
//     if (is_object($post) && $post->post_type == 'post') {
//         return home_url('/news/' . $year . '/' . $month . '/' . $post->post_name . '/');
//     }
//     return $post_link;
// }
// add_filter('post_link', 'change_blog_links', 1, 3);



/**********************
 * Get Featured News
 **********************/
function bansheeStarter_get_news_featured($category = null, $tags = null)
{

    /**********************
     * QUERY POSTS
     ***********************/
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4
    );

    //filter category
    if ($category) :
        $args['cat'] = $category;
    endif;

    //filter tags
    if ($tags) :
        foreach ($tags as $tag) :
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'terms' => $tag,
                'field'     => 'term_id',
            );
        endforeach;
    endif;

    //filter options
    $tax_query = array();
    $tax_query[] = array(
        'taxonomy' => 'option',
        'terms' => 'featured',
        'field'     => 'slug',
    );

    $args['tax_query'] = $tax_query;
    $featuredPosts = new WP_Query($args);

    return $featuredPosts;
}

/**********************
 * Get Related News
 **********************/
function bansheeStarter_get_news_related($post_id)
{
    /**********************
     * QUERY POSTS
     ***********************/
    $post_categories = wp_get_post_categories($post_id);
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'category__in' => $post_categories,
    );
    $relatedPosts = new WP_Query($args);

    return $relatedPosts;
}

/**********************
 * Get News
 **********************/
add_action('wp_ajax_nopriv_ajax_posts', 'bansheeStarter_get_news');
add_action('wp_ajax_ajax_posts', 'bansheeStarter_get_news');
function bansheeStarter_get_news($posts_per_page = 12, $template = 'card', $classes = [])
{

    if (isset($_POST['query_vars'])) :
        $query_vars = $_POST['query_vars'];
        $paged = $query_vars['paged'];
        $posts_per_page = $query_vars['posts_per_page'];
    else :
        $query_vars = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'paged' => 1,
            'order' => 'DESC',
            // 'orderby' => 'post-date'
        );
        $paged = 1;
    endif;

    if (isset($query_vars['cat']) && $query_vars['cat']) :

        /**** PRIMARY CATEGORY FIRST - THEN BY DATE */

        //primary first
        $query1_vars = $query_vars;
        $query1_vars['fields'] = 'ids';
        $query1_vars['posts_per_page'] = -1;
        $query1_vars['paged'] = null;
        $query1_vars['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => '_yoast_wpseo_primary_category',
                'value' => $query_vars['cat'],
            ],
        ];
        $query1 = new WP_Query($query1_vars);

        //not primary 2nd
        $query2_vars = $query_vars;
        $query2_vars['fields'] = 'ids';
        $query2_vars['posts_per_page'] = -1;
        $query2_vars['paged'] = null;
        $query2_vars['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => '_yoast_wpseo_primary_category',
                'value' => $query_vars['cat'],
                'compare' => '!='
            ],
        ];
        $query2 = new WP_Query($query2_vars);

        //now you got post IDs in $query->posts
        $allTheIDs = array_merge($query1->posts, $query2->posts);

        $query_vars['post__in'] = $allTheIDs;
        $query_vars['orderby'] = 'post__in';
        $results = new WP_Query($query_vars);

    else :
        /**** RESULTS ORDERED BY POST DATE */
        $results = new WP_Query($query_vars);
    endif;

    $cards = [];
    if ($results->have_posts()) :
        while ($results->have_posts()) :
            $results->the_post();

            if (isset($_POST['query_vars'])) :
                //dont print out card, send via json
                ob_start();
                Load::molecule(
                    'cards/card',
                    [
                        'classes' => $classes,
                        'template' => $template,
                        'post_id' => get_the_ID(),
                    ]
                );
                $card = ob_get_contents();
                ob_end_clean();
                $cards[] = $card;
            else :
                // load correct card based on $template
                Load::molecule(
                    'cards/card',
                    [
                        'classes' => $classes,
                        'template' => $template,
                        'post_id' => get_the_ID(),
                    ]
                );
            endif;
        endwhile;
    endif;

    $total = $results->found_posts;
    $showing = $posts_per_page * $paged;

    $response = array();
    $response['cards'] = $cards;
    $response['more'] = $showing >= $total ? false : true;
    $response['total'] = $total;
    $response['showing'] = $showing;
    $response['paged'] = $paged;

    if (isset($_POST['query_vars'])) :
        // json response
        echo json_encode($response);
    else :
        // return as array
        return $response;
    endif;
    die();
}
