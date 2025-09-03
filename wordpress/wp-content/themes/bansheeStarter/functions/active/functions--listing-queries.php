<?php
//convert to publications array
function grantPublicationsFromQuery($tags=[], $audience=[], $audience_exclude=[]){
    $result = [];
    $args = array(
        'post_type' => 'publication',
        'tax_query' => array(),
        'orderby' => array(
            'title' => 'ASC', 
        ),
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
    if($audience_exclude){
        $args['tax_query'][] = array(
            'taxonomy' => 'target-audience', 
            'field'    => 'term_id',     
            'terms'    => $audience_exclude,  
            'operator'=> 'NOT IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] =  publicationFromID($id);
        endwhile;
    endif;

    return $result;
}
//convert to publication-listing item
function publicationFromID($id){
    return array(
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id),
        'author' => get_field('authors',$id) ?? 'National Endowment for the Arts',
        'abstract' => get_field('abstract',$id),
        'publication_date' => get_field('publication_date',$id),
        'publisher' => get_field('publisher',$id),
        'volume' => get_field('volume',$id),
        'population_studied' => get_field('population_studied',$id),
        'treatment' => get_field('treatment',$id),
        'study_design' => get_field('study_design',$id),
        'measures' => get_field('measures',$id),
        'url' => get_field('url',$id),
    );
}



//convert to story-listing array
function resourcesFromQuery($categories=[], $tags=[], $audience=[], $audience_exclude=[]){
    $result = [];
    $args = array(
        'post_type' => 'resource',
        'tax_query' => array(),
        'orderby' => array(
            'title' => 'ASC', 
        ),
    );
    if($categories){
        $args['tax_query'][] = array(
            'taxonomy' => 'resources-category', 
            'field'    => 'term_id',     
            'terms'    => $categories,  
            'operator'=> 'IN'  
        );
    }
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
    if($audience_exclude){
        $args['tax_query'][] = array(
            'taxonomy' => 'target-audience', 
            'field'    => 'term_id',     
            'terms'    => $audience_exclude,  
            'operator'=> 'NOT IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] = resourceFromID($id);
        endwhile;
    endif;

    return $result;
}
//convert to story-listing item
function resourceFromID($id){
    $eyebrow = '';
    $term_obj_list = get_the_terms( $id, 'resources-category' ); 
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => join(', ', wp_list_pluck($term_obj_list, 'name')),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}



//convert to news-listing array
function newsFromQuery($categories=[], $tags=[], $audience=[], $audience_exclude=[]){
    $result = [];
    $args = array(
        'post_type' => 'news',
        'tax_query' => array(),
        'orderby' => array(
            'title' => 'ASC', 
        ),
    );
    if($categories){
        $args['tax_query'][] = array(
            'taxonomy' => 'news-category', 
            'field'    => 'term_id',     
            'terms'    => $categories,  
            'operator'=> 'IN'  
        );
    }
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
    if($audience_exclude){
        $args['tax_query'][] = array(
            'taxonomy' => 'target-audience', 
            'field'    => 'term_id',     
            'terms'    => $audience_exclude,  
            'operator'=> 'NOT IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] = newsFromID($id);
        endwhile;
    endif;
    return $result;
}
//convert to news-listing item
function newsFromID($id){
    $eyebrow = get_the_date('F Y', $id);
    $term_obj_list = get_the_terms( $id, 'news-category' ); 
    if($term_obj_list):
        $eyebrow .= '<span class="u-marginHoriz2gu">|</span>'.join(', ', wp_list_pluck($term_obj_list, 'name'));
    endif;
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'description' => get_the_excerpt($id) ?? '',
        'url' => get_the_permalink($id),
        'eyebrow' => $eyebrow,
        'imageID' =>  get_post_thumbnail_id($id)
    );
}



//convert to locations array (grantees)
function locationsFromQuery($organization_type = [], $project_tags = []){
    $result = [];
    $args = array(
        'post_type' => 'organization',
        'tax_query' => array(),
        'meta_query' => array(
            'relation' => 'AND', 
            'state_clause' => array(
                'key' => 'state', 
            ),
            'city_clause' => array(
                'key' => 'city', 
            ),
        ),
        'orderby' => array(
            'state_clause' => 'ASC', 
            'city_clause' => 'ASC', 
            'title' => 'ASC', 
        ),
    );
    if($organization_type){
        $args['tax_query'][] = array(
            'taxonomy' => 'organization-type', 
            'field'    => 'slug',     
            'terms'    => $organization_type,   
            'operator'=> 'IN'  
        );
    }
    if($project_tags){
        $args['tax_query'][] = array(
            'taxonomy' => 'project-tag', 
            'field'    => 'term_id',     
            'terms'    => $project_tags,  
            'operator'=> 'IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        $currentSubTitle = '';
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $subTitle = get_field('city',$id).', '.get_field('state',$id);
            if($currentSubTitle != $subTitle){
                $result[$subTitle] = [];
                $currentSubTitle = $subTitle;
            }
            $result[$subTitle][] =  array(
                'title' => get_the_title(),
                'url' =>  get_field('url',$id),
                'title' => get_the_title(),
            );
        endwhile;
    endif;

    return $result;
}


//convert to project grants array
function projectGrantsFromQuery($project_tags){
    $result = [];
    $args = array(
        'post_type' => 'grant-project',
        'tax_query' => array(),
        'orderby' => array(
            'title' => 'ASC', 
        ),
    );
    if($project_tags){
        $args['tax_query'][] = array(
            'taxonomy' => 'project-tag', 
            'field'    => 'term_id',     
            'terms'    => $project_tags,  
            'operator'=> 'IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] = projectFromID($id);
        endwhile;
    endif;

    return $result;
}
//convert to project item
function projectFromID($id){
    $granteeId =  get_field('grantee',$id);
    $partnerIds =  get_field('partner_organizations',$id);
    $partners = [];
    foreach($partnerIds as $partnerId):
        $partners[] =  array(
            'title' => get_the_title($partnerId),
            'link' => array(
                'title' => str_replace(array("http://", "https://"), "", get_field('url',$partnerId)),
                'url' => get_field('url',$partnerId)
            )
        );
    endforeach;
    $tier_terms = get_the_terms( $id, 'grant-tier' ); 
    return array(
        'title' => get_the_title(),
        'description' => get_field('description',$id),
        'link' =>get_field('link',$id),
        'grantee' =>  array(
            'title' =>  get_the_title($granteeId),
            'city'=> get_field('city',$granteeId),
            'state'=> get_field('state',$granteeId),
            'link' => array(
                'title' => str_replace(array("http://", "https://"), "", get_field('url',$granteeId)),
                'url' => get_field('url',$granteeId)
            )
        ),
        'partner_organization' => $partners,
        'amount' =>  get_field('amount',$id),
        'tier' =>  join(', ', wp_list_pluck($tier_terms, 'name')),
        'period' =>  get_field('grant_period',$id),
    );
}



//convert to persons array
function personsFromQuery($categories = []){
    $result = [];
    $args = array(
        'post_type' => 'person',
        'tax_query' => array(),
        'meta_query' => array(
            'relation' => 'AND', 
            'fname_clause' => array(
                'key' => 'first_name', 
            ),
            'lname_clause' => array(
                'key' => 'last_name', 
            ),
        ),
        'orderby' => array(
            'lname_clause' => 'ASC', 
            'fname_clause' => 'ASC', 
            'title' => 'ASC', 
        ),
    );
    if($categories){
        $args['tax_query'][] = array(
            'taxonomy' => 'person-category', 
            'field'    => 'term_id',     
            'terms'    => $categories,  
            'operator'=> 'IN'  
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] =  personFromID($id);
        endwhile;
    endif;

    return $result;
}
//convert to person item
function personFromID($id){
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'first_name'=> get_field('first_name',$id),
        'last_name'=> get_field('last_name',$id),
        'credentials'=> get_field('credentials',$id),
        'position'=> get_field('position',$id),
        'imageID' =>  get_post_thumbnail_id($id)
    );
}


//convert to story item
function storyFromID($id){
    return array(
        'id' =>$id,
        'title' => get_the_title($id),
        'imageID' =>  get_post_thumbnail_id($id),
        'url' => get_the_permalink($id),
        'description' => get_the_excerpt($id),
        'accent_image' => get_field('accent_image', $id)
    );
}



function storiesFromQuery()
{
    $result = [];
    $args = array(
        'post_type' => 'our_work',
        'orderby' => array(
            'date' => 'DESC', 
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            $id = get_the_ID();
            $result[] =  storyFromID($id);
        endwhile;
    endif;
    return $result;
    
}