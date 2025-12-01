<?php

// Check if it is wp function
function check_for_wp_functions(&$item, $key){
    if ($item == 'get_the_title()' OR 
        $item == 'get_the_content()' OR
        $item == 'get_permalink()' OR
        $item == 'get_the_ID()' OR
        $item == 'get_post_thumbnail_id()') {
        $item = str_replace("()", "", $item );
        $item = $item();
    }
}

function queryPosts($args){

    // Query the posts
    $query = new WP_Query($args['query']);

    // Loop through each post and call element
    if($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            // Save element props into a temporary variable
            if(props($args, 'props')): 
                $element_props = $args['props'];
            else:
                $element_props = [];
            endif;

            // Check for wp function and call them
            array_walk_recursive($element_props, 'check_for_wp_functions');

            // Call the element ie. 'atom'('button', 'props')
            element($args['element'], $element_props);
            
        endwhile;

        wp_reset_postdata();

        return true;

    else:

        return false;

    endif;

}

?>