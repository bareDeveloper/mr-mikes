<?php
add_filter('the_content', function( $content ){
    global $post;
    if ( function_exists( 'has_blocks' ) && has_blocks( $post->ID )) {
    } else {
    //--Remove all inline styles--
    $content = preg_replace('/ style=("|\')(.*?)("|\')/','',$content);
    }
    return $content;
}, 20);
?>