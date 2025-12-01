<?php
function add_favicon()
{
    $file = get_stylesheet_directory() . '/assets/favicons/index.html';

    if (file_exists($file)) {
        echo file_get_contents($file);
    }
}
add_action('wp_head', 'add_favicon');

?>