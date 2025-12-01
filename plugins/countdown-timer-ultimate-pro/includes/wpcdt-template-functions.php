<?php
/**
 * Template Functions
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Returns the path to the plugin templates directory
 *
 * @since 1.3
 */
function wpcdt_pro_get_templates_dir() {
	return apply_filters( 'wpcdt_pro_template_dir', WPCDT_PRO_DIR . '/templates' );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *	yourtheme/$template_path/$template_name
 *	yourtheme/$template_name
 *	$default_path/$template_name
 * 
 * @since 1.3
 * 
 */
function wpcdt_pro_locate_template( $template_name, $template_path = '', $default_path = '', $default_template = '' ) {

	if ( ! $template_path ) {
		$template_path = trailingslashit( 'countdown-timer-ultimate-pro' );
	}

	if ( ! $default_path ) {
		$default_path = trailingslashit( wpcdt_pro_get_templates_dir() );
	}

	// Look within passed path within the theme - this is priority.
	$template_lookup = array(
							trailingslashit( $template_path ) . $template_name,
						);

	// Adding default path to check
	if( ! empty( $default_template ) ) {
		$template_lookup[] = trailingslashit( $template_path ) . $default_template;
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template( $template_lookup );

	// Look within plugin template folder
	if ( ! $template || WPOS_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}

	// If template does not exist then load passed $default_template
	if ( ! empty( $default_path ) && ! file_exists( $template ) ) {
		$template = $default_path . $default_template;
	}

	// Return what we found
	return apply_filters( 'wpcdt_pro_locate_template', $template, $template_name, $template_path );
}

/**
 * Get other templates (e.g. attributes) passing attributes and including the file.
 *
 * @since 1.3
 */
function wpcdt_pro_get_template( $template, $args = array(), $template_path = '', $default_path = '', $default_template = '' ) {

	$located = wpcdt_pro_locate_template( $template, $template_path, $default_path, $default_template );

	if ( ! file_exists( $located ) ) {
		return;
	}

	if ( $args && is_array($args) ) {
		extract( $args );
	}

	do_action( 'wpcdt_pro_before_template_part', $template, $template_path, $located, $args );

	include( $located );

	do_action( 'wpcdt_pro_after_template_part', $template, $template_path, $located, $args );
}

/**
 * Like wpcdt_pro_get_template, but returns the HTML instead of outputting.
 * 
 * @since 1.3
 */
function wpcdt_pro_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '', $default_template = '' ) {
	ob_start();

	wpcdt_pro_get_template( $template_name, $args, $template_path, $default_path, $default_template );

	return ob_get_clean();
}