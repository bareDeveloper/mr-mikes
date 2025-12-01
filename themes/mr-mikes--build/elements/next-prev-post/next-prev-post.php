<?php

global $post;

$postlist_args = [
    'post_type'         => 'post',
    'posts_per_page'    => -1,
    'orderby'           => 'menu_order title',
    'order'             => 'ASC',
];
$postlist = get_posts($postlist_args);

//var_dump($postlist);

// get ids of posts retrieved from get_posts
$ids = [];
foreach ($postlist as $thepost) {
    $ids[] = $thepost->ID;
}

// get and echo previous and next post in the same taxonomy
$thisindex = array_search($post->ID, $ids);

if ($thisindex == 0) {
    $prev_post = end($ids);
} else {
    $prev_post = $ids[$thisindex - 1];
}

if ($thisindex == (count($ids) - 1)) {
    $next_post = $ids[0];
} else {
    $next_post = $ids[$thisindex + 1];
}

$args = [
    'element'       => 'card',

    'query'         => [
        'post_type'     => 'post',
        'post__in'      => array($prev_post, $next_post)
    ],

    'props'         => [

        'thumbnail' => 'get_post_thumbnail_id()',
        
        'headline'  => [
            'id'    => '',
            'class' => '',
            'text'  => 'get_the_title()',
            'style' => 'h3'
        ],

        'content'   => 'get_the_content()',

        'button'    => [
            'id'    => '',
            'class' => '',
            'text'  => 'Feed me more…',
            'link'  => 'get_permalink()'
        ]
        
    ]
    
];

queryPosts($args);

?>