<?php

//  Image sizes
add_image_size( 'preloader', 25, 25 );
add_image_size( 'link-box', 250, 250, [ 'center', 'center' ] );
add_image_size( 'medium-2', 500, 500 );
add_image_size( 'medium-3', 700, 700 );
add_image_size( 'large-2', 1200, 1200 );
add_image_size( 'large-3', 1400, 1400 );
add_image_size( 'large-4', 1600, 1600 );
add_image_size( 'retina', 2560, 2560 );

//
// This function takes in a WordPress image ID and is for lazyloading the background image according to screen size.
// It returns a background image style with low res initial image.
// It also adds the necessary element properties for lazy loaded responsive background images with lazysizes.js
// It's required that the element this is added to also has the .lazyload class applied.
// ex. <header class='lazyload' <?php responsive_bg_img(get_field('header_background')); ? >
function scaled_image_path( $attachment_id, $size = 'thumbnail' ) {
	$file = get_attached_file( $attachment_id, true );
	if ( empty( $size ) || $size === 'full' ) {
		// for the original size get_attached_file is fine
		return realpath( $file );
	}
	if ( ! wp_attachment_is_image( $attachment_id ) ) {
		return false; // the id is not referring to a media
	}
	$info = image_get_intermediate_size( $attachment_id, $size );
	if ( ! is_array( $info ) || ! isset( $info['file'] ) ) {
		return false; // probably a bad size argument
	}

	return realpath( str_replace( wp_basename( $file ), $info['file'], $file ) );
}

function get_lazy_background( $image_id, $height = 'auto', $lqip_size = 'preloader' ) {
	// check the image ID is not blank
	if ( $image_id != '' ) {
		// set the default src image size
		$image_src = wp_get_attachment_image_url( $image_id, 'thumbnail' );

		$lqip_w    = intval( get_option( "medium_size_w" ) );
		$lqip_h    = intval( get_option( "medium_size_h" ) );
		$lqip_path = scaled_image_path( $image_id, $lqip_size );
		$lqip_path = $lqip_path ? $lqip_path : scaled_image_path( $image_id, 'thumbnail' );

		$lqip_file_contents = isset( $lqip_path ) && file_exists( $lqip_path ) ? file_get_contents( $lqip_path ) : false;
		$lqip_base64        = $lqip_file_contents ? base64_encode( $lqip_file_contents ) : '';

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset( $image_id );

		// generate the markup for the responsive image
		return "style='background-image: url(data:image/gif;base64,{$lqip_base64});height: $height;' data-sizes='auto' data-bgset='{$image_srcset}'";
	}
}

function lazy_background( $image_id, $lqip_size = 'medium' ) {
	echo get_lazy_background( $image_id, $lqip_size );
}

// This function takes in a WordPress image ID and is for lazyloading an image according to screen size.
// It returns a data source set for use with lazysizes.js
// It's required that the element this is added to also has the .lazyload class applied.
// ex. <img class='lazyload' <?php responsive_bg_img(get_sub_field('image')); ? >
function mrm_get_lazy_loaded_image_attrs( $image_id, $custom_alt_text = '' ): string {

	$lazy_loaded_image_attrs = [];

	// set the default src image size
	$image_md = wp_get_attachment_image_url( $image_id, 'medium-2' );
	if ( ! empty( $image_md ) ) {
		$lazy_loaded_image_attrs['src'] = esc_url( $image_md );
	}

	// set the srcset with various image sizes
	$image_srcset = wp_get_attachment_image_srcset( $image_id );
	if ( ! empty( $image_srcset ) ) {
		$lazy_loaded_image_attrs['data-srcset'] = esc_attr( $image_srcset );
	}

	// set the alt text
	if ( ! empty( $custom_alt_text ) ) {
		$lazy_loaded_image_attrs['alt'] = esc_attr( $custom_alt_text );
	} else {
		$image_alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		if ( ! empty( $image_alt_text ) ) {
			$lazy_loaded_image_attrs['alt'] = esc_attr( $image_alt_text );
		}
	}


	$lazy_loaded_image_attrs_string = '';
	foreach ( $lazy_loaded_image_attrs as $attr => $value ) {
		$lazy_loaded_image_attrs_string .= $attr . '="' . $value . '"' . ' ';
	}

	// generate the attrs for the responsive image
	return trim( $lazy_loaded_image_attrs_string );
}

// TODO: Remove this function and replace with the one above (it's the same)
function Img( $image_id, $classname = "", $alt = "" ) {
	$lazy_loaded_image_atts = mrm_get_lazy_loaded_image_attrs( $image_id, $alt );
	echo "<img alt='$alt' class='lazyload $classname' $lazy_loaded_image_atts />";
}

// Lazyload Converter for images and videos in ACF content and post thumbnails
function add_lazyload( $content ) {
	$content = mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' );
	$dom     = new DOMDocument();
	@$dom->loadHTML( $content );

	// Convert Images
	$images = [];

	foreach ( $dom->getElementsByTagName( 'img' ) as $node ) {
		$images[] = $node;
	}

	foreach ( $images as $node ) {
		$fallback = $node->cloneNode( true );

		$oldsrc = $node->getAttribute( 'src' );
		$node->setAttribute( 'data-src', $oldsrc );
		$newsrc = get_template_directory_uri() . '/images/placeholder.jpg';
		$node->setAttribute( 'src', $newsrc );

		$oldsrcset = $node->getAttribute( 'srcset' );
		$node->setAttribute( 'data-srcset', $oldsrcset );
		$newsrcset = '';
		$node->setAttribute( 'srcset', $newsrcset );

		$classes    = $node->getAttribute( 'class' );
		$newclasses = $classes . ' lazyload';
		$node->setAttribute( 'class', $newclasses );

		$noscript = $dom->createElement( 'noscript', '' );
		$node->parentNode->insertBefore( $noscript, $node );
		$noscript->appendChild( $fallback );
	}

	// Convert Videos
	$videos = [];

	foreach ( $dom->getElementsByTagName( 'iframe' ) as $node ) {
		$videos[] = $node;
	}

	foreach ( $videos as $node ) {
		$fallback = $node->cloneNode( true );

		$oldsrc = $node->getAttribute( 'src' );
		$node->setAttribute( 'data-src', $oldsrc );
		$newsrc = '';
		$node->setAttribute( 'src', $newsrc );

		$classes    = $node->getAttribute( 'class' );
		$newclasses = $classes . ' lazyload lazy-hidden';
		$node->setAttribute( 'class', $newclasses );

		$noscript = $dom->createElement( 'noscript', '' );
		$node->parentNode->insertBefore( $noscript, $node );
		$noscript->appendChild( $fallback );
	}

	$newHtml = preg_replace( '/^<!DOCTYPE.+?>/', '', str_replace( [ '<html>', '</html>', '<body>', '</body>' ], [
		'',
		'',
		'',
		''
	], $dom->saveHTML() ) );

	return $newHtml;
}

add_filter( 'acf_the_content', 'add_lazyload' );
add_filter( 'post_thumbnail_html', 'add_lazyload' );

?>