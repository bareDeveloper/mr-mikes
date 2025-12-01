<?php
/**
 * Public Class
 *
 * Handles the Public side functionality of plugin
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Pro_Public {

	function __construct() {

		// Add action to get completion text when timer is finished
		add_action( 'wp_ajax_wpcdt_pro_end_timer', array( $this, 'wpcdt_pro_end_timer' ) );
		add_action( 'wp_ajax_nopriv_wpcdt_pro_end_timer', array( $this,'wpcdt_pro_end_timer' ) );

		// Add action to get timer caching data
		add_action( 'wp_ajax_wpcdt_timer_caching_data', array( $this, 'wpcdt_timer_caching_data' ) );
		add_action( 'wp_ajax_nopriv_wpcdt_timer_caching_data', array( $this,'wpcdt_timer_caching_data' ) );

		// Add action to check WooCommerce and EDD
		add_action( 'wp', array( $this, 'wpcdt_pro_wc_edd_timer_stuff' ) );

		// Filter to a P tag for Countdown Time Post Content
		add_filter( 'the_content', array( $this, 'wpcdt_pro_filter_timer_content' ), 10 );
	}

	/**
	 * Function to get completion text when timer is finished
	 * 
	 * @since 1.0
	 */
	function wpcdt_pro_end_timer() {

		global $wpcdt_timer_completion_text;

		$prefix			= WPCDT_PRO_META_PREFIX;
		$atts			= isset( $_POST['timer_conf'] )		? $_POST['timer_conf']		: array();
		$timer_id		= isset( $atts['timer_id'] )		? $atts['timer_id']			: 0;
		$recuring_time	= ! empty( $atts['recuring_time'] )	? $atts['recuring_time']	: 0;
		$timer_mode		= ! empty( $atts['timer_mode'] )	? $atts['timer_mode']		: '';
		$result			= array(
								'success'	=> 0,
								'msg'		=> esc_js( __('Sorry, Something happened wrong.', 'countdown-timer-ultimate') ),
							);

		// If post id is not there
		if( empty( $timer_id ) ) {
			wp_send_json( $result );
		}

		// Taking some data
		$current_time		= current_time( 'timestamp' );
		$start_date			= get_post_meta( $timer_id, $prefix.'start_date', true );
		$timer_date			= get_post_meta( $timer_id, $prefix.'timer_date', true );
		$recurring_data		= get_post_meta( $timer_id, $prefix.'recurring', true );
		$recurring_data		= is_array( $recurring_data ) ? $recurring_data : (array)$recurring_data;

		$recurring_dates	= wpcdt_pro_recurring_dates( $timer_mode, $recurring_data );

		// Check with master start date
		if( ! empty( $recurring_dates['start_date'] ) && ( strtotime( $start_date ) <= strtotime( $recurring_dates['start_date'] ) ) ) {
			$start_date = $recurring_dates['start_date'];
		}

		// Check with master end date
		if( ! empty( $recurring_dates['timer_date'] ) && ( strtotime( $timer_date ) >= strtotime( $recurring_dates['timer_date'] ) ) ) {
			$timer_date = $recurring_dates['timer_date'];
		}

		// Apply filter for change timer `Start Date` & `End Date`
		$start_date	= apply_filters( 'wpcdt_timer_start_date', $start_date, $timer_id );
		$timer_date	= apply_filters( 'wpcdt_timer_end_date', $timer_date, $timer_id );

		$timer_date	= strtotime( $timer_date );
		$time_diff	= ( $timer_date - $current_time );

		// If timer is expired
		if( $time_diff <= 1 || $recuring_time ) {

			// Complete Text Data
			$atts['timer_status']			= 'finish';
			$content_data					= get_post_meta( $timer_id, $prefix.'content', true );
			$wpcdt_timer_completion_text	= isset( $content_data['completion_text'] ) ? $content_data['completion_text'] : '';

			// Completion Text
			$data	= wpcdt_pro_get_template_html( 'completion-text.php', $atts );
			$result = array(
						'success'			=> 1,
						'completion_text'	=> $data,
						'msg'				=> esc_js( __('Timer is finished.', 'countdown-timer-ultimate') ),
					);
		}

		wp_send_json( $result );
	}

	/**
	 * Function to get timer caching data
	 * 
	 * @since 1.4
	 */
	function wpcdt_timer_caching_data() {

		global $wpcdt_timer_completion_text;

		$data			= '';
		$prefix			= WPCDT_PRO_META_PREFIX;
		$current_time	= current_time( 'timestamp' );
		$atts			= isset( $_POST['timer_conf'] )	? $_POST['timer_conf']	: array();
		$timer_id		= isset( $atts['timer_id'] )	? $atts['timer_id']		: 0;
		$results		= array(
								'success'	=> 0,
								'msg'		=> esc_js( __('Sorry, Something happened wrong.', 'countdown-timer-ultimate') ),
							);

		// If post id is not there then return
		if( empty( $timer_id ) ) {
			wp_send_json( $results );
		}

		if( $atts['timer_style'] == 'advance' ) {

			// Taking some data
			$timer_date			= get_post_meta( $timer_id, $prefix.'timer_date', true );
			$start_date			= get_post_meta( $timer_id, $prefix.'start_date', true );
			$recurring_data		= get_post_meta( $timer_id, $prefix.'recurring', true );
			$recurring_data		= is_array( $recurring_data ) ? $recurring_data : (array)$recurring_data;

			$recurring_dates	= wpcdt_pro_recurring_dates( $atts['timer_mode'], $recurring_data );

		} else {

			// Taking some data
			$start_date	= isset( $atts['start_date'] )	? $atts['start_date']	: '';
			$timer_date	= isset( $atts['end_date'] )	? $atts['end_date']		: '';

			// Recurring Data Array
			$recurring_data = array(
				'recur_mode'	=> $atts['recur_mode'],
				'start_time'	=> $atts['start_time'],
				'end_time'		=> $atts['end_time'],
				'week_start'	=> $atts['week_start'],
				'week_end'		=> $atts['week_end'],
				'recur_on'		=> explode(',', $atts['recur_on']),
			);

			$recurring_dates	= wpcdt_pro_recurring_dates( $atts['timer_mode'], $recurring_data );
		}

		// Check with master start date
		if( ! empty( $recurring_dates['start_date'] ) && ( strtotime( $start_date ) <= strtotime( $recurring_dates['start_date'] ) ) ) {
			$start_date = $recurring_dates['start_date'];
		}

		// Check with master end date
		if( ! empty( $recurring_dates['timer_date'] ) && ( strtotime( $timer_date ) >= strtotime( $recurring_dates['timer_date'] ) ) ) {
			$timer_date = $recurring_dates['timer_date'];
		}

		// Apply filter for change timer `Start Date` & `End Date`
		$start_date	= apply_filters('wpcdt_timer_start_date', $start_date, $timer_id );
		$timer_date	= apply_filters('wpcdt_timer_end_date', $timer_date, $timer_id );

		// Taking some variable
		$start_date				= strtotime( $start_date );
		$timer_date				= strtotime( $timer_date );
		$atts['expiry_date']	= date_i18n( 'Y-m-d H:i:s', $timer_date );
		$atts['current_date']	= date_i18n( 'Y-m-d H:i:s', $current_time );

		if( $atts['timer_style'] = 'advance' ) {
			$atts['totalseconds'] = ( $timer_date - $current_time );
		}

		// Set Timer Status
		if( $start_date && $start_date > $current_time ) {
			$atts['timer_status'] = 'schedule';
		} else if ( $timer_date >= $current_time ) {
			$atts['timer_status'] = 'active';
		} else {
			$atts['timer_status'] = 'finish';
		}

		// If `Timer Mode` is `Evergreen`
		if( $atts['timer_mode'] == 'evergreen' ) {

			// If recuring flag is there
			if( $atts['recuring_time'] ) {

				if( $atts['recuring_type'] == 'day' ) {
					$type_time = 86400;
				} else if( $atts['recuring_type'] == 'hour' ) {
					$type_time = 3600;
				} else {
					$type_time = 60;
				}

				$atts['recuring_date']	= $current_time + ( $atts['recuring_time'] * $type_time );
				
				// Check with master end date
				if( $atts['recuring_date'] >= $timer_date ) {
					$atts['recuring_date'] = $timer_date;
				}

				$atts['totalseconds'] = ( $atts['recuring_date'] - $current_time );

				// Recuring time diff
				$atts['recuring_diff']['year']		= (int)date( 'Y', $atts['recuring_date'] );
				$atts['recuring_diff']['month']		= (int)date( 'm', $atts['recuring_date'] );
				$atts['recuring_diff']['day']		= (int)date( 'd', $atts['recuring_date'] );
				$atts['recuring_diff']['hour']		= (int)date( 'H', $atts['recuring_date'] );
				$atts['recuring_diff']['minute']	= (int)date( 'i', $atts['recuring_date'] );
				$atts['recuring_diff']['second']	= (int)date( 's', $atts['recuring_date'] );
				$atts['recur_date_time']			= date_i18n( 'Y-m-d H:i:s', $atts['recuring_date'] );
			}
		}

		// Date Diff
		$atts['date_diff']['year']		= (int)date( 'Y', $timer_date );
		$atts['date_diff']['month']		= (int)date( 'm', $timer_date );
		$atts['date_diff']['day']		= (int)date( 'd', $timer_date );
		$atts['date_diff']['hour']		= (int)date( 'H', $timer_date );
		$atts['date_diff']['minute']	= (int)date( 'i', $timer_date );
		$atts['date_diff']['second']	= (int)date( 's', $timer_date );
		$atts['date_c']					= date('c');
		$atts['current_time']			= $current_time;

		// If timer is active
		if( $atts['timer_status'] == 'active' ) {

			if( $atts['timer_type'] == 'simple' ) {

				// Design HTML File
				$data = wpcdt_pro_get_template_html( "simple/design-1.php", $atts );

			} else {

				// Design HTML File
				$data = wpcdt_pro_get_template_html( "{$atts['design']}.php", $atts, null, null, "timer.php" );
			}

		} else if ( $atts['timer_status'] == 'finish' ) { // If timer is finished when ajax is taking more time (Rare Scenario)

			$content_data					= get_post_meta( $timer_id, $prefix.'content', true );
			$wpcdt_timer_completion_text	= isset( $content_data['completion_text'] ) ? $content_data['completion_text'] : '';

			// Completion Text
			$data = wpcdt_pro_get_template_html( 'completion-text.php', $atts );
		}

		$results = array(
					'success'	=> 1,
					'data'		=> $data,
					'shrt_atts'	=> $atts,
				);

		wp_send_json( $results );
	}

	/*
	 * Function to check WooCommerce and EDD(Easy Digital Download) stuff
	 * @since 1.4
	*/
	function wpcdt_pro_wc_edd_timer_stuff() {

		global $post;

		// IF WooCommerce plugin is there
		if( class_exists( 'WooCommerce' ) ) {

			// Taking some variable
			$wc_single	= wpcdt_pro_get_option('wc_single');
			$wc_shop	= wpcdt_pro_get_option('wc_shop');

			// If WooCommerce single page is there
			if( $wc_single && is_product() ) {

				// Add action to display timer before add to cart button on single page
				add_action( 'woocommerce_before_add_to_cart_button', array($this, 'wpcdt_pro_timer_before_add_to_cart') );

				// Add action to display timer after add to cart button on single page
				add_action( 'woocommerce_after_add_to_cart_form', array($this, 'wpcdt_pro_timer_after_add_to_cart') );
			}

			// If WooCommerce shop page is there
			if( $wc_shop && is_shop() ) {

				// Add action to display timer on shop page before add to cart button
				add_action( 'woocommerce_after_shop_loop_item', array($this, 'wpcdt_pro_timer_shop_page_before_btn'), 1 );

				// Add action to display timer on shop page after add to cart button
				add_action( 'woocommerce_after_shop_loop_item', array($this, 'wpcdt_pro_timer_shop_page_after_btn') );
			}
		}

		// If EDD(Easy Digital Download) plugin is there
		if( class_exists('Easy_Digital_Downloads') ) {

			// Taking some variable
			$edd_single	= wpcdt_pro_get_option( 'edd_single' );
			$edd_shop	= wpcdt_pro_get_option( 'edd_shop' );
			$priority	= apply_filters( 'wpcdt_pro_edd_priority', 10 );

			// If EDD(Easy Digital Download) single page is there
			if( $edd_single || $edd_shop ) {

				// Add action to display timer on product single page before purchase button
				add_action( 'edd_purchase_link_top', array($this, 'wpcdt_pro_timer_before_purchase_btn'), $priority );

				// Add action to display timer on product single page after purchase button
				add_action( 'edd_purchase_link_end', array($this, 'wpcdt_pro_timer_after_purchase_btn'), $priority );
			}
		}
	}

	/**
	 * Function to set timer on woocommerce single product page before add to cart button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_before_add_to_cart() {

		$timer_data	= wpcdt_pro_render_wc_product_timer();

		if( ! empty( $timer_data ) && $timer_data['single_position'] == 'before_cart' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Function to set timer on woocommerce single product page after add to cart button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_after_add_to_cart() {

		$timer_data = wpcdt_pro_render_wc_product_timer();

		if( ! empty( $timer_data ) && $timer_data['single_position'] == 'after_cart' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Function to set timer on woocommerce shop page before add to cart button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_shop_page_before_btn() {

		$timer_data = wpcdt_pro_render_wc_product_timer();

		if( ! empty( $timer_data ) && $timer_data['shop_position'] == 'before_cart' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Function to set timer on woocommerce shop page after add to cart button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_shop_page_after_btn() {

		$timer_data = wpcdt_pro_render_wc_product_timer();

		if( ! empty( $timer_data ) && $timer_data['shop_position'] == 'after_cart' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Function to set timer on EDD(Easy Digital Download) single page before purchase button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_before_purchase_btn() {

		$timer_data = wpcdt_pro_render_edd_product_timer();

		// Single Page
		if( ! empty( $timer_data ) && is_singular('download') && $timer_data['single_position'] == 'before_purchase' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
		
		// Shop Page
		if( ! empty( $timer_data ) && ! is_singular('download') && $timer_data['shop_position'] == 'before_purchase' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}		
	}

	/**
	 * Function to set timer on EDD(Easy Digital Download) single page after purchase button
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_timer_after_purchase_btn() {

		$timer_data = wpcdt_pro_render_edd_product_timer();

		// Single Page
		if( ! empty( $timer_data ) && is_singular('download') && $timer_data['single_position'] == 'after_purchase' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		// Shop Page
		if( ! empty( $timer_data ) && ! is_singular('download') && $timer_data['shop_position'] == 'after_purchase' ) {
			echo $timer_data['shortcode']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Function add P tag for Countdowwn Timer Post
	 * WP 5.7 is not adding the P tag even with the the_content()
	 * A little tweak to make it work properly
	 * 
	 * @since 1.4
	 */
	function wpcdt_pro_filter_timer_content( $content ) {

		global $post;

		if( ! empty( $post->post_type ) && $post->post_type == WPCDT_PRO_POST_TYPE ) {
			$content = wpautop( $content );
		}

		return $content;
	}
}

$wpcdt_pro_public = new Wpcdt_Pro_Public();