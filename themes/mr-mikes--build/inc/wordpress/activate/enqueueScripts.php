<?php
function enqueueScripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	// Getting the Page Header Type
	$header_type = get_field( 'header_type', get_the_ID() );
	if ( $header_type === 'lp_header' ) {
		wp_enqueue_script( 'lp-header-menu', get_stylesheet_directory_uri() . '/js/lpHeaderMobileMenu.js', [], null, true );
	}

	// Enqueuing Swiper slider Scripts and Styles
	wp_enqueue_script( 'swiper-scripts', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true );
	wp_enqueue_style( 'swiper-styles', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );

	// Testimonials Section Scripts
	wp_enqueue_script( 'testimonials-section', get_stylesheet_directory_uri() . '/js/testimonialsSection.js', [ 'swiper-scripts' ], null, true );

	// Gallery Section Scripts
	wp_enqueue_script( 'gallery-section', get_stylesheet_directory_uri() . '/js/gallerySection.js', [ 'swiper-scripts' ], null, true );

	// Accordion Section Scripts
	wp_enqueue_script( 'accordion-section', get_stylesheet_directory_uri() . '/js/accordionSection.js', [], null, true );

	// Tab Section Scripts
	wp_enqueue_script( 'tab-section', get_stylesheet_directory_uri() . '/js/tabSection.js', [], null, true );

	if ( get_post_type() == 'restaurant' ) {
		wp_enqueue_script( 'google-places', '//maps.googleapis.com/maps/api/js?key=AIzaSyDuEg9r5ucNWPXbAxX3OrE8KJmsLy_HcLY' );
	}

	// Gift card purchase
	if ( is_page( 564 ) ) {
		wp_enqueue_script( 'gift-cards', 'https://www.buyatab.com/gcp/view/js/parent.js' );
	}

	wp_register_script( 'scripts', get_template_directory_uri() . '/js/bundle.js', array( 'jquery' ), '', false );
	wp_localize_script( 'scripts', 'ajax_object',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
	);
	wp_enqueue_script( 'scripts' );
}

add_action( 'wp_enqueue_scripts', 'enqueueScripts' );

?>