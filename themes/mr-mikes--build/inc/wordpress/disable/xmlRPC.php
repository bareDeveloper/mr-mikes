<?php 
          function remove_x_pingback($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        }
        add_filter('wp_headers', 'remove_x_pingback');
        add_filter('xmlrpc_enabled', '__return_false');
        remove_action ('wp_head', 'rsd_link');

?>