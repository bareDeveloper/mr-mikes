<?php
function wpsites_add_tracking_code() {
    if ( is_page(11) ) {
        echo '<script src="https://acuityplatform.com/Adserver/pxlj/7818027462613647998?" type="text/javascript" async></script>';
    } 
}
add_action( 'wp_head', 'wpsites_add_tracking_code' );