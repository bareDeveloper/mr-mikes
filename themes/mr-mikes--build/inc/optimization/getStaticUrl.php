<?php 
function get_static_url($static_relative_path) {
    return get_stylesheet_directory_uri() . "/static/" . $static_relative_path;
}

function static_url($static_relative_path) {
    echo get_static_url($static_relative_path);
}


function static_bg($static_relative_path) {
    $static_url = get_static_url($static_relative_path);
    echo "style='background-image: url($static_url);'";
}
?>