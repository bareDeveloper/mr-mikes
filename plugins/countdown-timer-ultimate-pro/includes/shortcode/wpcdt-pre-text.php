<?php
/**
 * Timer Pre-Text Shortcode `wpcdt_pre_text`
 * Display Text before the timer starts
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to handle timer shortcode
 *
 * @since 1.4
 */
function wpcdt_pro_render_timer_pre_text( $atts, $content ) {

	// Taking some globals
	global $wpcdt_timer_status;

	if( $wpcdt_timer_status != 'schedule' ) {
		return false;
	}

	ob_start();

	$content = do_shortcode( wpautop( $content ) );

	$content .= ob_get_clean();
	return $content;
}

// Countdown Timer Pre Text Shortcode
add_shortcode( 'wpcdt_pre_text', 'wpcdt_pro_render_timer_pre_text' );