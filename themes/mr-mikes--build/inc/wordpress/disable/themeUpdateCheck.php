<?php
// Deactivate Theme Update Check
function deactivateThemeUpdateCheck($r, $url)
{
    if (0 !== strpos($url, 'http://api.wordpress.org/themes/update-check')) {
        return $r;
    } // Not a theme update request. Bail immediately.

    $themes = unserialize($r['body']['themes']);
    unset($themes[get_option('template')], $themes[get_option('stylesheet')]);

    $r['body']['themes'] = serialize($themes);
    return $r;
}

add_filter('http_request_args', 'deactivateThemeUpdateCheck', 5, 2);

?>