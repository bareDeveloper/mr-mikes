<?php
/**
 * Setting Class
 *
 * Handles the Admin side setting options functionality of module
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get settings tab
 * 
 * @since 1.0
 */
function wpcdt_pro_settings_tab() {

	// Plugin settings tab
	$sett_tabs = array(
					'general' => __( 'General', 'countdown-timer-ultimate' ),
				);

	if( class_exists('WooCommerce') ) {
		$sett_tabs['wc'] = __( 'WooCommerce', 'countdown-timer-ultimate' );
	}

	if( class_exists('Easy_Digital_Downloads') ) {
		$sett_tabs['edd'] = __( 'Easy Digital Download', 'countdown-timer-ultimate' );
	}

	$sett_tabs['custom_css'] = __( 'Custom CSS', 'countdown-timer-ultimate' );

	return apply_filters( 'wpcdt_pro_settings_tab', (array)$sett_tabs );
}

/**
 * Function to register plugin settings
 * 
 * @since 1.0
 */
function wpcdt_pro_register_settings() {

	// Reset plugin settings
	if( ! empty( $_POST['wpcdt_reset_settings'] ) && check_admin_referer( 'wpcdt_reset_setting', 'wpcdt_reset_sett_nonce' ) ) {
		wpcdt_pro_default_settings();
	}

	register_setting( 'wpcdt_pro_plugin_options', 'wpcdt_pro_options', 'wpcdt_pro_validate_options' );
}

// Action to register plugin settings
add_action( 'admin_init', 'wpcdt_pro_register_settings' );

/**
 * Validate Settings Options
 * 
 * @since 1.0
 */
function wpcdt_pro_validate_options( $input ) {

	global $wpcdt_pro_options;

	$input = $input ? $input : array();

	parse_str( $_POST['_wp_http_referer'], $referrer ); // Pull out the tab and section
	$tab = isset( $referrer['tab'] ) ? wpcdt_pro_clean( $referrer['tab'] ) : 'product';

	// Run a sanitization for the tab for special fields
	$input = apply_filters( 'wpcdt_pro_sett_sanitize_'.$tab, $input );

	// Run a sanitization for the custom created tab
	$input = apply_filters( 'wpcdt_pro_sett_sanitize', $input, $tab );

	// Making merge of old and new input values
	$input = array_merge( $wpcdt_pro_options, $input );

	return $input;
}

/**
 * Filter to validate General settings
 * 
 * @since 1.1
 */
function wpcdt_pro_sanitize_general_sett( $input ) {

	$input['recuring_prefix']	= ! empty( $input['recuring_prefix'] )		? sanitize_title( $input['recuring_prefix'] ) : 'wpcdt_';
	$input['post_guten_editor']	= ! empty( $input['post_guten_editor'] )	? 1	: 0;

	return $input;
}
add_filter( 'wpcdt_pro_sett_sanitize_general', 'wpcdt_pro_sanitize_general_sett' );

/**
 * Filter to validate WooCommerce settings
 * 
 * @since 1.1
 */
function wpcdt_pro_sanitize_wc_sett( $input ) {

	$input['wc_timer_type']	= ! empty( $input['wc_timer_type'] )	? wpcdt_pro_clean( $input['wc_timer_type'] )			: 'timer';
	$input['wc_shop_pos']	= ! empty( $input['wc_shop_pos'] )		? wpcdt_pro_clean( $input['wc_shop_pos'] )				: 'before_title';
	$input['wc_single_pos']	= ! empty( $input['wc_single_pos'] )	? wpcdt_pro_clean( $input['wc_single_pos'] )			: 'before_title';
	$input['wc_timer_id']	= ! empty( $input['wc_timer_id'] )		? wpcdt_pro_clean_number( $input['wc_timer_id'] )		: 0;
	$input['wc_timer_shrt']	= ! empty( $input['wc_timer_shrt'] )	? sanitize_textarea_field( $input['wc_timer_shrt'] )	: '';
	$input['wc_enable']		= isset( $input['wc_enable'] )			? 1 : 0;
	$input['wc_single']		= isset( $input['wc_single'] )			? 1	: 0;
	$input['wc_shop']		= isset( $input['wc_shop'] )			? 1	: 0;

	return $input;
}
add_filter( 'wpcdt_pro_sett_sanitize_wc', 'wpcdt_pro_sanitize_wc_sett' );

/**
 * Filter to validate EDD(Easy Digital Download) settings
 * 
 * @since 1.1
 */
function wpcdt_pro_sanitize_edd_sett( $input ) {

	$input['edd_timer_type']	= ! empty( $input['edd_timer_type'] )	? wpcdt_pro_clean( $input['edd_timer_type'] )			: 'timer';
	$input['edd_shop_pos']		= ! empty( $input['edd_shop_pos'] )		? wpcdt_pro_clean( $input['edd_shop_pos'] )				: 'before_title';
	$input['edd_single_pos']	= ! empty( $input['edd_single_pos'] )	? wpcdt_pro_clean( $input['edd_single_pos'] )			: 'before_title';
	$input['edd_timer_id']		= ! empty( $input['edd_timer_id'] )		? wpcdt_pro_clean_number( $input['edd_timer_id'] )		: 0;
	$input['edd_timer_shrt']	= ! empty( $input['edd_timer_shrt'] )	? sanitize_textarea_field( $input['edd_timer_shrt'] )	: '';
	$input['edd_enable']		= isset( $input['edd_enable'] )			? 1 : 0;
	$input['edd_single']		= isset( $input['edd_single'] )			? 1	: 0;
	$input['edd_shop']			= isset( $input['edd_shop'] )			? 1	: 0;

	return $input;
}
add_filter( 'wpcdt_pro_sett_sanitize_edd', 'wpcdt_pro_sanitize_edd_sett' );

/**
 * Filter to validate Custom CSS settings
 * 
 * @since 1.0
 */
function wpcdt_pro_sanitize_custom_css_sett( $input ) {

	$input['custom_css'] = isset( $input['custom_css'] ) ? sanitize_textarea_field( $input['custom_css'] ) : '';

	return $input;
}
add_filter( 'wpcdt_pro_sett_sanitize_custom_css', 'wpcdt_pro_sanitize_custom_css_sett' );