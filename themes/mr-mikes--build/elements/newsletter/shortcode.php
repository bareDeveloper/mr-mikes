<?php

// return newletter signup form
function newsletter_signup( $atts ) {
	$args = shortcode_atts( array(
		'img_id'          => null,
		'title'           => null,
		'title_color'     => null,
		'header_bg_color' => null,
	), $atts );

	ob_start();

	get_template_part( 'elements/newsletter/newsletter', null, $args );

	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}


add_shortcode( 'newsletter_form', 'newsletter_signup' );


// return email subsciption form header image and text
function form_header_code( $atts ) {
	$a = shortcode_atts( array(
		'img_id' => 44,
		'title'  => null,
		'height' => '300px',
	), $atts );

	$image_url = wp_get_attachment_url( $a['img_id'] );

	if ( $image_url ) {
		return "<div style='position:relative'><img class='newsletter__header lazyload'" . " " . mrm_get_lazy_loaded_image_attrs( $a['img_id'] ) . "/><h2 class='headline'>" . $a['title'] . "</h2></div>";
	}
}

add_shortcode( 'form_header', 'form_header_code' );