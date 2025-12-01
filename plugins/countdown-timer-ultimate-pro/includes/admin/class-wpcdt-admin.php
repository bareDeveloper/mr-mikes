<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Pro_Admin {

	function __construct() {

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wpcdt_pro_post_sett_metabox' ) );

		// Action to save metabox
		add_action( 'save_post_'.WPCDT_PRO_POST_TYPE, array( $this, 'wpcdt_pro_save_metabox_value' ) );

		// Action to add custom column to Timer listing
		add_filter( 'manage_'.WPCDT_PRO_POST_TYPE.'_posts_columns', array( $this, 'wpcdt_pro_posts_columns' ) );

		// Action to add custom column data to Timer listing
		add_action('manage_'.WPCDT_PRO_POST_TYPE.'_posts_custom_column', array( $this, 'wpcdt_pro_post_columns_data' ), 10, 2);

		// Action to register admin menu
		add_action( 'admin_menu', array( $this, 'wpcdt_pro_register_menu' ) );

		// Action to register plugin settings
		add_action ( 'admin_init', array( $this, 'wpcdt_pro_admin_processes' ) );

		// Filter to add plugin links
		add_filter( 'plugin_row_meta', array( $this, 'wpcdt_pro_plugin_row_meta' ), 10, 2 );


		// Taking some global settings for WooCommerce & EDD
		$wc_enable	= wpcdt_pro_get_option( 'wc_enable' );
		$edd_enable	= wpcdt_pro_get_option( 'edd_enable' );

		// If WooCommerce is there or enable metabox
		if( class_exists( 'WooCommerce' ) && $wc_enable ) {

			// WooCommerce Product Data Tabs - Admin
			add_filter( 'woocommerce_product_data_tabs', array($this, 'wpcdt_pro_woo_product_data_tabs') );

			// WooCommerce Product Tabs HTML - Admin
			add_action( 'woocommerce_product_data_panels', array($this, 'wpcdt_pro_woo_product_mb_content') );

			// Action to save metabox
			add_action( 'save_post_product', array($this, 'wpcdt_pro_save_woo_metabox_value') );
		}

		// EDD(Easy Digital Download) is there or enable metabox
		if( class_exists( 'Easy_Digital_Downloads' ) && $edd_enable ) {

			// Action to save metabox
			add_action( 'save_post_download', array($this, 'wpcdt_pro_save_edd_metabox_value') );
		}

		// WP Editor Filter to remove some unnecessary buttons
		add_filter( 'mce_buttons', array( $this, 'wpcdt_pro_editor_buttons' ), 10, 2 );

		// Action to admin notice
		add_action( 'admin_notices', array( $this, 'wpcdt_pro_admin_notices') );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_post_sett_metabox() {

		// Taking some variable
		$edd_enable	= wpcdt_pro_get_option('edd_enable');

		// Settings Metabox
		add_meta_box( 'wpcdt-post-sett', __( 'Countdown Timer Ultimate Pro - Settings', 'countdown-timer-ultimate' ), array($this, 'wpcdt_pro_post_sett_mb_content'), WPCDT_PRO_POST_TYPE, 'normal', 'high' );

		// Quick - Side Meta Box
		add_meta_box( 'wpcdt-shrt-sett', __( 'How to Use', 'countdown-timer-ultimate' ), array( $this, 'wpcdt_pro_shrt_prev_mb_content'), WPCDT_PRO_POST_TYPE, 'side', 'low' );

		// If EDD(Easy Digital Download) plugin is there
		if( class_exists( 'Easy_Digital_Downloads' ) && $edd_enable ) {

			// EDD Metabox
			add_meta_box( 'wpcdt-edd-sett', __( 'Countdown Timer Ultimate', 'countdown-timer-ultimate' ), array($this, 'wpcdt_pro_edd_sett_mb_content'), 'download', 'normal', 'high' );
		}		
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_post_sett_mb_content() {
		include_once( WPCDT_PRO_DIR .'/includes/admin/metabox/post-sett-metabox.php');
	}

	/**
	 * Function to handle the edd html
	 *
	 * @since 1.0
	 */
	function wpcdt_pro_edd_sett_mb_content() {
		include_once( WPCDT_PRO_DIR .'/includes/admin/metabox/edd-sett-metabox.php');
	}

	/**
	 * Quick Post Settings Metabox HTML
	 * 
	 * @since 1.2.1
	 */
	function wpcdt_pro_shrt_prev_mb_content() {
		include_once( WPCDT_PRO_DIR .'/includes/admin/metabox/wpcdt-shrt-prev.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_save_metabox_value( $post_id ) {

		global $post_type;

		// Taking metabox prefix
		$data	= array();
		$prefix	= WPCDT_PRO_META_PREFIX;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )							// Check Autosave
		|| ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id )	// Check Revision
		|| ( $post_type !=  WPCDT_PRO_POST_TYPE ) )										// Check if current post type is supported.
		{
			return $post_id;
		}

		// General Settings Meta
		$data['timer_type']		= isset( $_POST[$prefix.'timer_type'] )		? wpcdt_pro_clean( $_POST[$prefix.'timer_type'] )	: '';
		$data['start_date']		= isset( $_POST[$prefix.'start_date'] )		? wpcdt_pro_clean( $_POST[$prefix.'start_date'] )	: '';
		$data['timer_date']		= ! empty( $_POST[$prefix.'timer_date'] )	? wpcdt_pro_clean( $_POST[$prefix.'timer_date'] )	: date_i18n( 'Y-m-d H:i:s', strtotime( "+1 day", current_time('timestamp') ) );
		$data['design_style']	= isset( $_POST[$prefix.'design_style'] )	? wpcdt_pro_clean( $_POST[$prefix.'design_style'] )	: '';
		$data['timer_mode']		= isset( $_POST[$prefix.'timer_mode'] )		? wpcdt_pro_clean( $_POST[$prefix.'timer_mode'] )	: '';

		// Recurring Data
		$data['recurring']['time']			= ! empty( $_POST[$prefix.'recurring']['time'] )		? wpcdt_pro_clean_number( $_POST[$prefix.'recurring']['time'], '', 'abs' )	: '';
		$data['recurring']['type']			= isset( $_POST[$prefix.'recurring']['type'] )			? wpcdt_pro_clean( $_POST[$prefix.'recurring']['type'] )					: '';
		$data['recurring']['timer_mode']	= isset( $_POST[$prefix.'recurring']['timer_mode'] )	? wpcdt_pro_clean( $_POST[$prefix.'recurring']['timer_mode'] )				: '';
		$data['recurring']['recur_mode']	= isset( $_POST[$prefix.'recurring']['recur_mode'] )	? wpcdt_pro_clean( $_POST[$prefix.'recurring']['recur_mode'] )				: '';
		$data['recurring']['start_time']	= isset( $_POST[$prefix.'recurring']['start_time'] )	? wpcdt_pro_clean( $_POST[$prefix.'recurring']['start_time'] )				: '';
		$data['recurring']['end_time']		= isset( $_POST[$prefix.'recurring']['end_time'] )		? wpcdt_pro_clean( $_POST[$prefix.'recurring']['end_time'] )				: '';
		$data['recurring']['recur_on']		= isset( $_POST[$prefix.'recurring']['recur_on'] )		? wpcdt_pro_clean( $_POST[$prefix.'recurring']['recur_on'] )				: array();
		$data['recurring']['week_start']	= isset( $_POST[$prefix.'recurring']['week_start'] )	? wpcdt_pro_clean_number( $_POST[$prefix.'recurring']['week_start'] )		: 1;
		$data['recurring']['week_end']		= isset( $_POST[$prefix.'recurring']['week_end'] )		? wpcdt_pro_clean_number( $_POST[$prefix.'recurring']['week_end'] )			: 0;

		// Content Tab Data
		$data['content']['tab']					= isset( $_POST[$prefix.'content']['tab'] )					? wpcdt_pro_clean( $_POST[$prefix.'content']['tab'] )							: '';
		$data['content']['content_position']	= isset( $_POST[$prefix.'content']['content_position'] )	? wpcdt_pro_clean( $_POST[$prefix.'content']['content_position'] )				: '';
		$data['content']['timer_day_text']		= ! empty( $_POST[$prefix.'content']['timer_day_text'] )	? wpcdt_pro_clean( $_POST[$prefix.'content']['timer_day_text'] )				: __('Days', 'countdown-timer-ultimate');
		$data['content']['timer_hour_text']		= ! empty( $_POST[$prefix.'content']['timer_hour_text'] )	? wpcdt_pro_clean( $_POST[$prefix.'content']['timer_hour_text'] )				: __('Hours', 'countdown-timer-ultimate');
		$data['content']['timer_minute_text']	= ! empty( $_POST[$prefix.'content']['timer_minute_text'] )	? wpcdt_pro_clean( $_POST[$prefix.'content']['timer_minute_text'] )				: __('Minutes', 'countdown-timer-ultimate');
		$data['content']['timer_second_text']	= ! empty( $_POST[$prefix.'content']['timer_second_text'] )	? wpcdt_pro_clean( $_POST[$prefix.'content']['timer_second_text'] )				: __('Seconds', 'countdown-timer-ultimate');
		$data['content']['completion_text']		= isset( $_POST[$prefix.'content']['completion_text'] )		? sanitize_post_field( 'post_content', $_POST[$prefix.'content']['completion_text'], $post_id, 'db' )	: '';
		$data['content']['show_title']			= ! empty( $_POST[$prefix.'content']['show_title'] )		? 1	: 0;
		$data['content']['is_timerdays']		= ! empty( $_POST[$prefix.'content']['is_timerdays'] )		? 1 : 0;
		$data['content']['is_timerhours']		= ! empty( $_POST[$prefix.'content']['is_timerhours'] )		? 1 : 0;
		$data['content']['is_timerminutes']		= ! empty( $_POST[$prefix.'content']['is_timerminutes'] )	? 1 : 0;
		$data['content']['is_timerseconds']		= ! empty( $_POST[$prefix.'content']['is_timerseconds'] )	? 1 : 0;

		// If all labels are unchecked then make them checked
		if( ! $data['content']['is_timerdays'] && ! $data['content']['is_timerhours'] && ! $data['content']['is_timerminutes'] && ! $data['content']['is_timerseconds'] ) {

			$data['content']['is_timerdays']	= 1;
			$data['content']['is_timerhours']	= 1;
			$data['content']['is_timerminutes']	= 1;
			$data['content']['is_timerseconds']	= 1;
		}

		// Design Settings Meta
		$data['design']['background_pref']	= isset( $_POST[$prefix.'design']['background_pref'] )		? wpcdt_pro_clean_color( $_POST[$prefix.'design']['background_pref'] )	: '';
		$data['design']['font_clr']			= isset( $_POST[$prefix.'design']['font_clr'] )				? wpcdt_pro_clean_color( $_POST[$prefix.'design']['font_clr'] )			: '';
		$data['design']['timertext_color']	= ! empty( $_POST[$prefix.'design']['timertext_color'] )	? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timertext_color'] )	: '#a8a8a8';
		$data['design']['timerdigit_color']	= ! empty( $_POST[$prefix.'design']['timerdigit_color'] )	? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timerdigit_color'] )	: '#000000';
		$data['design']['timer_width']		= isset( $_POST[$prefix.'design']['timer_width'] )			? wpcdt_pro_clean_number( $_POST[$prefix.'design']['timer_width'], '' )	: '';

		// Circle Style 1 Meta
		$data['design']['timercircle_animation']	= isset( $_POST[$prefix.'design']['timercircle_animation'] )	? wpcdt_pro_clean( $_POST[$prefix.'design']['timercircle_animation'] )					: '';
		$data['design']['timercircle_width']		= ! empty( $_POST[$prefix.'design']['timercircle_width'] )		? wpcdt_pro_clean_number( $_POST[$prefix.'design']['timercircle_width'], null, 'abs' )	: 0.1;

		// Circle Style 2 Meta
		$data['design']['timercircle2_width'] = ! empty( $_POST[$prefix.'design']['timercircle2_width'] ) ? wpcdt_pro_clean_number( $_POST[$prefix.'design']['timercircle2_width'] ) : 10;

		// Clock Background Colors
		$data['design']['timerbackground_width']		= ! empty( $_POST[$prefix.'design']['timerbackground_width'] )			? wpcdt_pro_clean_number( $_POST[$prefix.'design']['timerbackground_width'], null, 'abs' )	: 1.2;
		$data['design']['timerbackground_color']		= ! empty( $_POST[$prefix.'design']['timerbackground_color'] )			? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timerbackground_color'] )				: '#313332';
		$data['design']['timerdaysbackground_color']	= ! empty( $_POST[$prefix.'design']['timerdaysbackground_color'] )		? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timerdaysbackground_color'] )			: '#e3be32';
		$data['design']['timerhoursbackground_color']	= ! empty( $_POST[$prefix.'design']['timerhoursbackground_color'] )		? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timerhoursbackground_color'] )			: '#36b0e3';
		$data['design']['timerminutesbackground_color']	= ! empty( $_POST[$prefix.'design']['timerminutesbackground_color'] )	? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timerminutesbackground_color'] )			: '#75bf44';
		$data['design']['timersecondsbackground_color']	= ! empty( $_POST[$prefix.'design']['timersecondsbackground_color'] )	? wpcdt_pro_clean_color( $_POST[$prefix.'design']['timersecondsbackground_color'] )			: '#66c5af';

		// Theme Color
		$data['design']['theme_clr'] = ! empty( $_POST[$prefix.'design']['theme_clr'] )	? wpcdt_pro_clean_color( $_POST[$prefix.'design']['theme_clr'] ) : '#ff9900';

		// Update Meta
		update_post_meta( $post_id, $prefix.'timer_type', $data['timer_type'] );
		update_post_meta( $post_id, $prefix.'timer_mode', $data['timer_mode'] );
		update_post_meta( $post_id, $prefix.'design_style', $data['design_style'] );
		update_post_meta( $post_id, $prefix.'start_date', $data['start_date'] );
		update_post_meta( $post_id, $prefix.'timer_date', $data['timer_date'] );
		update_post_meta( $post_id, $prefix.'recurring', $data['recurring'] );
		update_post_meta( $post_id, $prefix.'content', $data['content'] );
		update_post_meta( $post_id, $prefix.'design', $data['design'] );
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_posts_columns( $columns ) {

		$new_columns['wpcdt_timer_type']	= esc_html__('Timer Type', 'countdown-timer-ultimate');
		$new_columns['wpcdt_timer_mode']	= esc_html__('Timer Mode', 'countdown-timer-ultimate');
		$new_columns['wpcdt_start_date']	= esc_html__('Start Date', 'countdown-timer-ultimate');
		$new_columns['wpcdt_end_date']		= esc_html__('End Date', 'countdown-timer-ultimate');
		$new_columns['wpcdt_shortcode']		= esc_html__('Shortcode', 'countdown-timer-ultimate');

		$columns = wpcdt_pro_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_post_columns_data( $column, $post_id ) {

		// Taking some variables
		$prefix = WPCDT_PRO_META_PREFIX;

		switch ( $column ) {

			case 'wpcdt_timer_type':

				$timer_type = get_post_meta( $post_id, $prefix.'timer_type', true );

				if ( $timer_type == 'simple' ) {
					esc_html_e('Simple Timer', 'countdown-timer-ultimate');
				} else {
					esc_html_e('Content Timer', 'countdown-timer-ultimate');
				}

				break;

			case 'wpcdt_timer_mode':

				$timer_mode = get_post_meta( $post_id, $prefix.'timer_mode', true );

				if ( $timer_mode == 'evergreen' ) {
					esc_html_e('Evergreen Timer', 'countdown-timer-ultimate');
				} else if( $timer_mode == 'recurring' ) {
					esc_html_e('Recurring Timer', 'countdown-timer-ultimate');
				} else {
					esc_html_e('Default Timer', 'countdown-timer-ultimate');
				}

				break;

			case 'wpcdt_start_date':

				$start_date = get_post_meta( $post_id, $prefix.'start_date', true );

				if( $start_date ) {
					echo esc_attr( $start_date );
				} else {
					echo "&mdash;";
				}
				break;

			case 'wpcdt_end_date':

				$end_date = get_post_meta( $post_id, $prefix.'timer_date', true );
				
				echo esc_attr( $end_date );
				break;

			case 'wpcdt_shortcode':

				echo '<div class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt-countdown id="'.esc_attr( $post_id ).'"]</div>';
				break;
		}
	}

	/**
	 * Function to register admin menus
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_register_menu() {

		// Register Setting page
		add_submenu_page( 'edit.php?post_type='.WPCDT_PRO_POST_TYPE, __('Settings - Countdown Timer Ultimate', 'countdown-timer-ultimate'), __('Settings', 'countdown-timer-ultimate'), 'manage_options', 'wpcdt-pro-settings', array($this, 'wpcdt_pro_settings_page') );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_admin_processes() {

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'wpcdt-pro-plugin-notice' == $_GET['message'] ) {
			set_transient( 'wpcdt_pro_install_notice', true, 604800 );
		}

		// If plugin notice is dismissed
		if( isset($_GET['message']) && 'wpcdt-pro-license-exp-notice' == $_GET['message'] ) {
			set_transient( 'wpcdt_pro_license_exp_notice', true, 864000 );
		}
	}

	/**
	 * Function to handle the setting page html
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_settings_page() {
		include_once( WPCDT_PRO_DIR . '/includes/admin/settings/settings.php' );
	}

	/**
	 * Function to unique number value
	 * 
	 * @since 1.0.0
	 */
	function wpcdt_pro_plugin_row_meta( $links, $file ) {

		if ( $file == WPCDT_PRO_PLUGIN_BASENAME ) {

			$row_meta = array(
				'docs' 		=> '<a href="' . esc_url('https://docs.essentialplugin.com/countdown-timer-ultimate-pro/?utm_source=hp&event=doc') . '" title="' . esc_attr__( 'View Documentation', 'countdown-timer-ultimate' ) . '" target="_blank">' . esc_html__( 'Document', 'countdown-timer-ultimate' ) . '</a>',
				'support' 	=> '<a href="' . esc_url('https://www.wponlinesupport.com/wordpress-services/?utm_source=hp&event=projobs') . '" title="' . esc_attr__( 'Premium Support - For any Customization', 'countdown-timer-ultimate' ) . '" target="_blank">' . esc_html__( 'Premium Support', 'countdown-timer-ultimate' ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}
		return (array)$links;
	}

	/**
	 * Add `Countdown Timer` tab to Woocommerce Product Page at admin side
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_woo_product_data_tabs( $data_tabs ) {

		$data_tabs['wpcdt_data'] = array(
									'label'		=> __( 'Countdown Timer', 'countdown-timer-ultimate' ),
									'target'	=> 'wpcdt_product_data',
									'class'		=> array( 'wpcdt-data' ),
								);
		return $data_tabs;
	}

	/**
	 * WooCommerce Countdown Timer tab section HTML at product meta
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_woo_product_mb_content() {
		include_once( WPCDT_PRO_DIR . '/includes/admin/metabox/wc-sett-metabox.php' );
	}

	/**
	 * Function to save WooCommerce metabox values
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_save_woo_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                			// Check Autosave
		|| ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) != $post_id )	// Check Revision
		|| ( 'product' != $post_type ) )			              						// Check if current post type is supported.
		{
		  return $post_id;
		}

		// Taking variables
		$prefix			= WPCDT_PRO_META_PREFIX;
		$wc_timer_type	= isset( $_POST[$prefix.'wc_timer_type'] )	? wpcdt_pro_clean( $_POST[$prefix.'wc_timer_type'] )			: '';
		$wc_enable		= isset( $_POST[$prefix.'wc_enable'] )		? wpcdt_pro_clean_number( $_POST[$prefix.'wc_enable'] )			: 0;
		$wc_timer_id	= isset( $_POST[$prefix.'wc_timer_id'] )	? wpcdt_pro_clean_number( $_POST[$prefix.'wc_timer_id'] )		: 0;
		$wc_timer_shrt	= isset( $_POST[$prefix.'wc_timer_shrt'] )	? sanitize_textarea_field( $_POST[$prefix.'wc_timer_shrt'] )	: '';

		update_post_meta( $post_id, $prefix.'wc_enable', $wc_enable );
		update_post_meta( $post_id, $prefix.'wc_timer_type', $wc_timer_type );
		update_post_meta( $post_id, $prefix.'wc_timer_id', $wc_timer_id );
		update_post_meta( $post_id, $prefix.'wc_timer_shrt', $wc_timer_shrt );
	}

	/**
	 * Function to save EDD(Easy Digital Download) metabox values
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_save_edd_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                			// Check Autosave
		|| ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) != $post_id )	// Check Revision
		|| ( 'download' != $post_type ) )			              						// Check if current post type is supported.
		{
		  return $post_id;
		}

		// Taking variables
		$prefix			= WPCDT_PRO_META_PREFIX;
		$edd_enable		= isset( $_POST[$prefix.'edd_enable'] )		? wpcdt_pro_clean( $_POST[$prefix.'edd_enable'] )				: '';
		$edd_timer_type	= isset( $_POST[$prefix.'edd_timer_type'] )	? wpcdt_pro_clean( $_POST[$prefix.'edd_timer_type'] )			: '';
		$edd_timer_shrt	= isset( $_POST[$prefix.'edd_timer_shrt'] )	? sanitize_textarea_field( $_POST[$prefix.'edd_timer_shrt'] )	: '';
		$edd_timer_id	= isset( $_POST[$prefix.'edd_timer_id'] )	? wpcdt_pro_clean_number( $_POST[$prefix.'edd_timer_id'] )		: 0;

		update_post_meta( $post_id, $prefix.'edd_enable', $edd_enable );
		update_post_meta( $post_id, $prefix.'edd_timer_type', $edd_timer_type );
		update_post_meta( $post_id, $prefix.'edd_timer_id', $edd_timer_id );
		update_post_meta( $post_id, $prefix.'edd_timer_shrt', $edd_timer_shrt );
	}

	/**
	 * Remove some unnecessary WP Editor Buttons
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_editor_buttons( $mce_buttons, $editor_id ) {

		// If admin screen and plugin editor is there
		if( is_admin() && is_array( $mce_buttons ) && $editor_id == 'wpcdt-completion-txt' ) {

			$remove_buttons = apply_filters( 'wpcdt_pro_remove_editor_buttons', array('wp_more') );

			// Loop of buttons to remove
			if( ! empty( $remove_buttons ) ) {
				foreach( $remove_buttons as $mce_button_key => $mce_button_val ) {

					if( ( $key = array_search( $mce_button_val, $mce_buttons ) ) !== false ) {
						unset( $mce_buttons[ $key ] );
					}
				}
			}
		}

		return $mce_buttons;
	}

	/**
	 * Function to display admin notice
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_admin_notices() {

		global $typenow;

		if( WPCDT_PRO_POST_TYPE == $typenow && isset( $_GET['message'] ) && 'wpcdt-db-update' == $_GET['message'] ) {

			echo '<div class="updated notice notice-success is-dismissible">
					<p><strong>'. esc_html__('Countdown Timer Ultimate Pro database update done successfully.', 'countdown-timer-ultimate'). '</strong></p>
				</div>';
		}
	}
}

$wpcdt_pro_admin = new Wpcdt_Pro_Admin();