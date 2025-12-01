<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Pro_Script {

	function __construct() {

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wpcdt_pro_admin_style_script') );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpcdt_pro_front_style_script') );

		// Action to add custom css in head
		add_action( 'wp_head', array($this, 'wpcdt_pro_custom_css'), 20 );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_register_admin_assets() {

		global $wp_version;

		/* Styles */
		// Registring admin css
		wp_register_style( 'wpcdt-admin-css', WPCDT_PRO_URL.'assets/css/wpcdt-admin.css', array(), WPCDT_PRO_VERSION );


		/* Scripts */
		// Registring admin script
		wp_register_script( 'wpcdt-admin-js', WPCDT_PRO_URL.'assets/js/wpcdt-admin.js', array('jquery'), WPCDT_PRO_VERSION, true );
		wp_localize_script( 'wpcdt-admin-js', 'WpcdtProAdmin', array(
																'code_editor'			=> ( version_compare( $wp_version, '4.9' ) >= 0 )				? 1 : 0,
																'syntax_highlighting'	=> ( 'false' === wp_get_current_user()->syntax_highlighting )	? 0 : 1,
																'confirm_msg'			=> esc_js( __('Are you sure you want to do this?', 'countdown-timer-ultimate') ),
															));
	}

	/**
	 * Enqueue admin styles
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_admin_style_script( $hook ) {

		global $post_type, $wp_version;

		$wpcdt_post_types = array('product', 'download');

		$this->wpcdt_pro_register_admin_assets();

		/***** Registering Styles *****/
		// Time Picker style
		wp_register_style( 'wpcdt-jquery-ui-css', WPCDT_PRO_URL.'assets/css/wpcdt-time-picker.css', array(), WPCDT_PRO_VERSION );


		/***** Registering Scripts *****/
		// TimePicker script
		wp_register_script( 'wpcdt-ui-timepicker-addon-js', WPCDT_PRO_URL.'assets/js/jquery-ui-timepicker-addon.min.js', array('jquery'), WPCDT_PRO_VERSION, true );

		// Color Picker Alpha
		wp_register_script( 'wp-color-picker-alpha', WPCDT_PRO_URL.'assets/js/wp-color-picker-alpha.js', array('wp-color-picker'), WPCDT_PRO_VERSION, true );

		// If page is Post page then enqueue script
		if( $post_type == WPCDT_PRO_POST_TYPE ) {

			/***** Enqueue Styles *****/
			wp_enqueue_style( 'wp-color-picker' );		// ColorPicker Style
			wp_enqueue_style( 'wpcdt-jquery-ui-css' );	// TimePicker Style
			wp_enqueue_style( 'wpcdt-admin-css' );		// Admin Style

			/* Enqueue Scripts */
			wp_enqueue_script( 'wp-color-picker' );					// ColorPicker Script
			wp_enqueue_script( 'wp-color-picker-alpha' );			// Color Picker Alpha
			wp_enqueue_script( 'jquery-ui-datepicker' );			// Date Picker Script
			wp_enqueue_script( 'jquery-ui-slider' );				// jQuery UI Slider Script
			wp_enqueue_script( 'wpcdt-ui-timepicker-addon-js' );	// TimerPicker Addon Script
			wp_enqueue_script( 'wpcdt-admin-js' );					// Admin Script
		}

		// Enqueue Required Script and Style for "WooCommerce" & "EDD(Easy Digital Download)"
		if( in_array( $post_type, $wpcdt_post_types ) && ( $hook == 'post.php' || $hook == 'post-new.php' ) ) {

			// Style
			wp_enqueue_style('wpcdt-admin-css');	// Admin style

			// Script
			wp_enqueue_script('wpcdt-admin-js');	// Admin script
		}

		// Setting page & getting started page
		if( $hook == WPCDT_PRO_POST_TYPE.'_page_wpcdt-designs' || $hook == WPCDT_PRO_POST_TYPE.'_page_wpcdt-pro-settings' ) {

			wp_enqueue_style( 'wpcdt-admin-css' );	// Admin Style
			wp_enqueue_script( 'wpcdt-admin-js' );	// Admin Script
		}

		// If Setting page is there and check WordPress version then initialize code editor
		if( $hook == WPCDT_PRO_POST_TYPE.'_page_wpcdt-pro-settings' && isset( $_GET['tab'] ) && 'custom_css' == $_GET['tab'] && version_compare( $wp_version, '4.9' ) >= 0 ) {

			// WP CSS Code Editor
			wp_enqueue_code_editor( array(
				'type'			=> 'text/css',
				'codemirror'	=> array(
									'indentUnit'	=> 2,
									'tabSize'		=> 2,
									'lint'			=> false,
								),
			));
		}

		// VC Page Builder Frontend
		if( function_exists('vc_is_inline') && vc_is_inline() ) {
			wp_register_script( 'wpcdt-vc', WPCDT_PRO_URL . 'assets/js/vc/wpcdt-vc.js', array(), WPCDT_PRO_VERSION, true );
			wp_enqueue_script( 'wpcdt-vc' );
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_front_style_script() {

		global $post;

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '_' : '.min';

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/***** Registring Styles *****/
		// Public style
		wp_register_style( 'wpcdt-public-css', WPCDT_PRO_URL."assets/css/wpcdt-public{$suffix}.css", null, WPCDT_PRO_VERSION );

		/***** Registering Scripts *****/
		// Timer script
		wp_register_script( 'wpcdt-timecircle-js', WPCDT_PRO_URL.'assets/js/wpcdt-timecircles.js', array('jquery'), WPCDT_PRO_VERSION, true );
		wp_register_script( 'wpcdt-countereverest-js', WPCDT_PRO_URL.'assets/js/jquery.counteverest.min.js', array('jquery'), WPCDT_PRO_VERSION, true );

		// Register Elementor script
		wp_register_script( 'wpcdt-elementor-js', WPCDT_PRO_URL.'assets/js/elementor/wpcdt-elementor.js', array('jquery'), WPCDT_PRO_VERSION, true );

		// Public script
		wp_register_script( 'wpcdt-public-js', WPCDT_PRO_URL."assets/js/wpcdt-public{$suffix}.js", array('jquery'), WPCDT_PRO_VERSION, true );
		wp_localize_script( 'wpcdt-public-js', 'WpCdtPro', array(
															'elementor_preview' => $elementor_preview,
															'timezone'			=> get_option( 'gmt_offset' ),
															'ajax_url'			=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
															'loading_text'		=> esc_js( __('Loading...', 'countdown-timer-ultimate') ),
															'recuring_prefix'	=> wpcdt_pro_get_option( 'recuring_prefix', 'wpcdt_' ),
														));

		/* Enqueue Styles */
		wp_enqueue_style( 'wpcdt-public-css' );	// Public style

		// Enqueue Script for Elementor Preview
		if ( $elementor_preview ) {

			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-countereverest-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
			wp_enqueue_script( 'wpcdt-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wpcdt_pro_register_admin_assets();

			// Admin Style & Script
			wp_enqueue_style( 'wpcdt-admin-css');
			wp_enqueue_script( 'wpcdt-admin-js' );

			// Public Scripts
			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-countereverest-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wpcdt_pro_register_admin_assets();

			wp_enqueue_style( 'wpcdt-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wpcdt_pro_register_admin_assets();

			wp_enqueue_style( 'wpcdt-admin-css');
		}

		// VC Page Builder Frontend
		if( function_exists('vc_is_inline') && vc_is_inline() ) {
			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-countereverest-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
		}
	}

	/**
	 * Add custom css to head
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_custom_css() {

		$custom_css = wpcdt_pro_get_option( 'custom_css' );

		if( ! empty( $custom_css ) ) {
			echo '<style type="text/css">' . "\n" .
					wp_strip_all_tags( $custom_css )
				 . "\n" . '</style>' . "\n";
		}
	}
}

$wpcdt_pro_script = new Wpcdt_Pro_Script();