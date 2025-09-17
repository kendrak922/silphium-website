<?php

//convert to post
function postFromID($id){
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'imageID' =>  get_post_thumbnail_id($id),
        'url' => get_the_permalink($id),
        'description' => get_the_excerpt($id),
    );
}



function postsFromQuery()
{
    $result = [];
    $args = array(
        'post_type' => 'post',
        'orderby' => array(
            'date' => 'DESC', 
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] =  postFromID($id);
        endwhile;
    endif;
    return $result;
    
}