<?php

/**
 * Plugin Name: Gulp Sitemap Generator
 * Plugin URI: https://gladdy.uk/blog/2014/04/13/using-uncss-and-grunt-uncss-with-wordpress/
 * Description: Generate a JSON list of every page on a site so it can be used with Gulp and uncss.
 * Author: Liam Gladdy
 * Author URI: http://gladdy.co.uk
 * Version: 1.0
 * THIS FILE IS EDITED FROM THE ORIGINAL
 */

add_action('template_redirect', 'show_sitemap');

function show_sitemap() {
	if (isset($_GET['show_sitemap'])) {

		// Set up an array for all the URLs
		$urls = array();

		// Get all the pages, posts (including CPTs)
		$the_query = new WP_Query(array(
      'post_type' => 'any',
      'posts_per_page' => '-1',
      'post_status' => 'publish'
    ));

		while ($the_query->have_posts()) {
			$urls[] = array(
				'id'	=> 'id--' . get_the_ID(),
				'url' 	=> get_the_permalink()
			);
		  $the_query->the_post();
		}

    // get custom post type archive pages
    $args = array(
      "public" => true,
      "publicly_queryable" => true,
      "_builtin" => false
    );
    $custom_post_type_archives = get_post_types( $args );

    foreach ($custom_post_type_archives as $archive) {
      $urls[] = array(
        'id' => 'post-type--' . $archive,
        'url' => get_post_type_archive_link($archive)
      );
    }

		// Add in a search result page for '.'
		$main_url = get_site_url();
		$urls[] = array(
      'id' => 'default-page--search',
      'url' => $main_url . '/?s=.'
    );

		// Force a search with no results
		$urls[] = array(
      'id' => 'default-page--search-noresults',
      'url' => $main_url . '/?s=asdfasdfasdfasdf'
    );

		// Force a 404 page
		$urls[] = array(
			'id'	=> 'default-page--404',
			'url' 	=> $main_url . '/asdfasdfasdfasdf'
		);


		// Return all of the urls captured above and encode to json for UnCSS

		die(json_encode($urls));


    // maybe add some of this later

    // // User page
    // $urls[] = array(
    //   'id' => 'user-page',
    //   'url' => get_author_posts_url(0)
    // );

    // Every term imaginable, even the empty ones (categories, custom taxonomies, tags, etc.)
    // $args = array(
    // 	'public' => true,
    // );
    //
    // $taxonomies = get_taxonomies($args, 'names');
    //
    // $args = array('hide_empty=0');
    //
    // $terms = get_terms($taxonomies, $args);
    //
    // foreach ($terms as $term) {
    // 	$urls[] = get_term_link( $term ); 
    // }

    // Getting a list of Archive URLs seems like it should be easier...probably missing something on the Codex, but this works.

    // $args = array(
    // 	'type'            => 'monthly',
    // 	'format'          => 'custom',
    // 	'before'          => '',
    // 	'after'           => '',
    // 	'show_post_count' => false,
    // 	'echo'            => 0
    // );
    //
    // $archive_links_raw = wp_get_archives($args);
    //
    // $archive_links_pattern = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";
    //
    // preg_match_all($archive_links_pattern, $archive_links_raw, $cleaned_archive_links);
    //
    // $cleaned_archive_links = $cleaned_archive_links[1];
    //
    // foreach ($cleaned_archive_links as $cleaned_archive_link) {
    // 	$urls[] = array(
    //     'id' => url_to_postid($cleaned_archive_link),
    //     'url' => $cleaned_archive_link
    //   );
    // }

  }

}
?>