<?php
//convert to story-listing array
function relatedFromQuery($postTypes, $posts_per_page = 3, $tags=[], $audience=[], $filterImpact=[], $filterNews=[], $filterResource=[], $filterResearch=[]){
    $result = [];
    $args = array(
        'post_type' => $postTypes,
        'tax_query' => array(),
        'posts_per_page' => $posts_per_page,
        'post__not_in' => [get_the_ID()]
    );
    if($tags){
        $args['tax_query'][] = array(
            'taxonomy' => 'post_tag', 
            'field'    => 'term_id',     
            'terms'    => $tags,  
            'operator'=> 'IN'  
        );
    }
    if($audience){
        $args['tax_query'][] = array(
            'taxonomy' => 'target-audience', 
            'field'    => 'term_id',     
            'terms'    => $audience,  
            'operator'=> 'IN'  
        );
    }

    //post type filters
    if($filterImpact){
        $args['tax_query'][] = array(
            'taxonomy' => 'impact-category', 
            'field'    => 'term_id',     
            'terms'    => $filterImpact,  
            'operator'=> 'IN'  
        );
    }
    if($filterNews){
        $args['tax_query'][] = array(
            'taxonomy' => 'news-category', 
            'field'    => 'term_id',     
            'terms'    => $filterNews,  
            'operator'=> 'IN'  
        );
    }
    if($filterResource){
        $args['tax_query'][] = array(
            'taxonomy' => 'resources-category', 
            'field'    => 'term_id',     
            'terms'    => $filterResource,  
            'operator'=> 'IN'  
        );
    }
    if($filterResearch){
        $args['tax_query'][] = array(
            'taxonomy' => 'research-category', 
            'field'    => 'term_id',     
            'terms'    => $filterResearch,  
            'operator'=> 'IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            switch(get_post_type($id)){
                case 'impact': 
                    $result[] = cardFromImpactID($id); break;
                case 'news': 
                    $result[] = cardFromNewsID($id); break;
                case 'resource': 
                    $result[] = cardFromResourceID($id); break;
                case 'research': 
                    $result[] = cardFromPostID($id); break;
                default:
                    
            }
        endwhile;
    endif;

    return $result;
}

//convert impact to card
function cardFromImpactID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'impact-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_field('caption',$id) ?? get_the_excerpt($id),
        'url' => get_the_permalink($id),
        'eyebrow' => get_the_date('F Y').'<span>|</span>'.join(', ', wp_list_pluck($term_obj_list, 'name')),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}

//convert news to card
function cardFromNewsID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'news-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => get_the_date('F Y').'<span>|</span>'.join(', ', wp_list_pluck($term_obj_list, 'name')),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}

//convert resource to card
function cardFromResourceID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'resources-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => get_the_date('F Y').'<span>|</span>'.join(', ', wp_list_pluck($term_obj_list, 'name')),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}

//convert research to card
function cardFromResearchID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'research-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => get_the_date('F Y').'<span>|</span>'.join(', ', wp_list_pluck($term_obj_list, 'name')),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}

//convert id to card
function cardFromPostID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'research-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => '', //get_the_date('F Y'),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}