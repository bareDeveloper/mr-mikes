<?php 
remove_action( 'wp_head',      'wp_oembed_add_discovery_links'         );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
?>