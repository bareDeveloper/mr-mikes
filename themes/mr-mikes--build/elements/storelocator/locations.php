<?php

define('WP_USE_THEMES', false);  
require_once('../../../../../wp-load.php');

$locations = array();

$args = [
    'post_type' => 'restaurant',
    'posts_per_page' => -1
];

$query = new WP_Query($args);

if($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

        $post_id = $post->ID;

        $address = get_field('address', $post_id);

        $hours = get_field('opening_hours', $post_id);
        $hours_options = get_field('opening_hours', 'options');

        if($hours['mo_from']) :  $mo_from = $hours['mo_from'];   else :  $mo_from = $hours_options['mo_from'];   endif;
        if($hours['mo_to']) :    $mo_to = $hours['mo_to'];       else :  $mo_to = $hours_options['mo_to'];       endif;

        if($hours['tu_from']) :  $tu_from = $hours['tu_from'];   else :  $tu_from = $hours_options['tu_from'];   endif;
        if($hours['tu_to']) :    $tu_to = $hours['tu_to'];       else :  $tu_to = $hours_options['tu_to'];       endif;

        if($hours['we_from']) :  $we_from = $hours['we_from'];   else :  $we_from = $hours_options['we_from'];   endif;
        if($hours['we_to']) :    $we_to = $hours['we_to'];       else :  $we_to = $hours_options['we_to'];       endif;

        if($hours['th_from']) :  $th_from = $hours['th_from'];   else :  $th_from = $hours_options['th_from'];   endif;
        if($hours['th_to']) :    $th_to = $hours['th_to'];       else :  $th_to = $hours_options['th_to'];       endif;

        if($hours['fr_from']) :  $fr_from = $hours['fr_from'];   else :  $fr_from = $hours_options['fr_from'];   endif;
        if($hours['fr_to']) :    $fr_to = $hours['fr_to'];       else :  $fr_to = $hours_options['fr_to'];       endif;

        if($hours['sa_from']) :  $sa_from = $hours['sa_from'];   else :  $sa_from = $hours_options['sa_from'];   endif;
        if($hours['sa_to']) :    $sa_to = $hours['sa_to'];       else :  $sa_to = $hours_options['sa_to'];       endif;

        if($hours['su_from']) :  $su_from = $hours['su_from'];   else :  $su_from = $hours_options['su_from'];   endif;
        if($hours['su_to']) :    $su_to = $hours['su_to'];       else :  $su_to = $hours_options['su_to'];       endif;


        if(isset($address["lat"]) && isset($address["lng"]) && isset($address["address"])){

            $data = [
                "id"        => $post_id,
                "name"      => $post->post_title,
                "lat"       => $address["lat"],
                "lng"       => $address["lng"],
                "address"   => $address["address"],
                "email"     => get_field('email'),
                "phone"     => get_field('phone'),
                "web"       => get_permalink(),
                "open_table" => get_field('open_table_id'),
                "hours1"    => "Mo from " . $mo_from . " to " . $mo_to,
                "hours2"    => "Tu from " . $tu_from . " to " . $tu_to,
                "hours3"    => "We from " . $we_from . " to " . $we_to,
                "hours4"    => "Th from " . $th_from . " to " . $th_to,
                "hours5"    => "Fr from " . $fr_from . " to " . $fr_to,
                "hours6"    => "Sa from " . $sa_from . " to " . $sa_to,
                "hours7"    => "Su from " . $su_from . " to " . $su_to
            ];
        
            array_push($locations, $data);
        }
        
    endwhile;

    wp_reset_postdata();

endif;

echo json_encode($locations);

?>