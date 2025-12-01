<?php
/**
 * 'wpcdt-countdown' Shortcode
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpcdt_pro_countdown_timer( $atts, $content = null ) {

	// Taking some globals
	global $post, $wpcdt_timer_status, $wpcdt_timer_completion_text, $wpcdt_timer_loop;

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Countdown Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt-countdown
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Countdown Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt-countdown
				</div>';
	}

	$atts = shortcode_atts( array(
		'id'						=> '',
		'content_after_complete'	=> 'true',
		'is_caching'				=> 'false',
		'extra_class'				=> '',
	), $atts, 'wpcdt-countdown' );

	$atts['unique']					= wpcdt_pro_get_unique();
	$atts['timer_id']				= ! empty( $atts['id'] )						? $atts['id']		: 0;
	$atts['is_caching']				= ( $atts['is_caching'] == 'true' )				? 1					: 0;
	$atts['content_after_complete']	= ( $atts['content_after_complete'] == 'true' )	? 1					: 0;
	$atts['extra_class']			= wpcdt_pro_sanitize_html_classes( $atts['extra_class'] );

	extract( $atts );

	// WP Query Parameters
	$args = array (
		'posts_per_page'		=> 1,
		'post_type'				=> WPCDT_PRO_POST_TYPE,
		'post_status'			=> array( 'publish' ),
		'post__in'				=> array( $atts['id'] ),
		'no_found_rows'			=> true,
		'ignore_sticky_posts'	=> true,
	);

	// WP Query Parameters
	$query = new WP_Query( $args );

	// If timer is not there, recursive loop or no query post found
	if ( empty( $timer_id ) || ! empty( $wpcdt_timer_loop ) || ! $query->have_posts() ) {
		return $content;
	}

	// Taking variables
	$prefix			= WPCDT_PRO_META_PREFIX;
	$timer_date		= get_post_meta( $timer_id, $prefix.'timer_date', true );
	$current_time	= current_time( 'timestamp' );

	// If timer `Expiry Date & Time` is not there
	if( empty( $timer_date ) ) {
		return $content;
	}

	// Taking some variables
	$start_date			= get_post_meta( $timer_id, $prefix.'start_date', true );
	$timer_mode			= get_post_meta( $timer_id, $prefix.'timer_mode', true );
	$recurring_data		= get_post_meta( $timer_id, $prefix.'recurring', true );
	$recurring_data		= is_array( $recurring_data ) ? $recurring_data : (array)$recurring_data;
	$timer_mode			= ( ! empty( $recurring_data['time'] ) && empty( $timer_mode ) ) ? 'evergreen' : $timer_mode;

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

	// Getting General Settings
	$start_date	= strtotime( $start_date );
	$timer_date	= strtotime( $timer_date );

	// Set Timer Status
	if( $start_date && $start_date > $current_time ) {
		$wpcdt_timer_status = 'schedule';
	} else if ( $timer_date >= $current_time ) {
		$wpcdt_timer_status = 'active';
	} else {
		$wpcdt_timer_status = 'finish';
	}

	// Taking data in atts variable
	$content_data				= get_post_meta( $timer_id, $prefix.'content', true );
	$atts['timer_type']			= get_post_meta( $timer_id, $prefix.'timer_type', true );
	$atts['show_title']			= ! empty( $content_data['show_title'] )		? 1	: 0;
	$atts['content_position']	= ! empty( $content_data['content_position'] )	? $content_data['content_position']		: 'above_timer';
	$atts['day_text']			= ! empty( $content_data['timer_day_text'] )	? $content_data['timer_day_text']		: '';
	$atts['hour_text']			= ! empty( $content_data['timer_hour_text'] )	? $content_data['timer_hour_text']		: '';
	$atts['minute_text']		= ! empty( $content_data['timer_minute_text'] ) ? $content_data['timer_minute_text']	: '';
	$atts['second_text']		= ! empty( $content_data['timer_second_text'] ) ? $content_data['timer_second_text']	: '';
	$atts['is_days']			= ! empty( $content_data['is_timerdays'] )		? $content_data['is_timerdays']			: '';
	$atts['is_hours']			= ! empty( $content_data['is_timerhours'] )		? $content_data['is_timerhours']		: '';
	$atts['is_minutes']			= ! empty( $content_data['is_timerminutes'] )	? $content_data['is_timerminutes']		: '';
	$atts['is_seconds']			= ! empty( $content_data['is_timerseconds'] )	? $content_data['is_timerseconds']		: '';
	$atts['expiry_date']		= date_i18n( 'Y-m-d H:i:s', $timer_date );
	$atts['current_date']		= date_i18n( 'Y-m-d H:i:s', $current_time );
	$atts['date_c']				= date('c');
	$atts['timer_status']		= $wpcdt_timer_status;
	$atts['timer_mode']			= $timer_mode;
	$atts['totalseconds']		= ( $timer_date - $current_time );

	// Taking some variable
	$design_data			= get_post_meta( $timer_id, $prefix.'design', true );
	$bg_img					= wpcdt_pro_get_featured_image( $timer_id, 'full' );
	$bg_clr					= ! empty( $design_data['background_pref'] ) ? $design_data['background_pref'] : '';	
	$design_data['bg_img']	= $bg_img;

	// If `Timer Mode` is `Evergreen`
	if( $timer_mode == 'evergreen' ) {

		// Taking some variable
		$atts['recuring_time']	= isset( $recurring_data['time'] )	? $recurring_data['time']	: '';
		$atts['recuring_type']	= isset( $recurring_data['type'] )	? $recurring_data['type']	: '';

		// If recuring flag is there
		if( $atts['recuring_time'] ) {

			if( $atts['recuring_type'] == 'day' ) {
				$type_time = 86400;
			} else if( $atts['recuring_type'] == 'hour' ) {
				$type_time = 3600;
			} else {
				$type_time = 60;
			}

			$atts['recuring_date'] = $current_time + ( $atts['recuring_time'] * $type_time );

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

	// Timer Date Diff
	$atts['date_diff']['year']		= (int)date( 'Y', $timer_date );
	$atts['date_diff']['month']		= (int)date( 'm', $timer_date );
	$atts['date_diff']['day']		= (int)date( 'd', $timer_date );
	$atts['date_diff']['hour']		= (int)date( 'H', $timer_date );
	$atts['date_diff']['minute']	= (int)date( 'i', $timer_date );
	$atts['date_diff']['second']	= (int)date( 's', $timer_date );
	$atts['current_time']			= $current_time;
	$atts['timer_style']			= 'advance';
	$atts['classes']				= "wpcdt-timer-{$timer_id} {$extra_class}";
	$atts['classes']				.= ( $atts['content_position'] == 'above_timer' ) ? ' wpcdt-cnt-above-timer' : ' wpcdt-cnt-below-timer';

	// If caching is there class will be added
	if( $is_caching == 1 && $atts['timer_status'] == 'active' ) {
		$atts['classes'] .= ' wpcdt-timer-ajax';
	}

	// Timer type is `Simple`
	if( $atts['timer_type'] == 'simple' ) {

		// Taking some data
		$atts['design'] = 'simple';
		$atts['classes'] .= ' wpcdt-smpl-timer-design-1';

		// Enqueue Timer JS
		wp_enqueue_script( 'wpcdt-countereverest-js' );

	} else { // Timer type is `Content`

		// Taking some data
		$shortcode_designs				= wpcdt_pro_designs();
		$design							= get_post_meta( $timer_id, $prefix.'design_style', true );
		$atts['design']					= ( $design && ( array_key_exists( $design, $shortcode_designs ) ) ) ? $design : 'circle';
		$wpcdt_timer_completion_text	= isset( $content_data['completion_text'] ) ? $content_data['completion_text'] : '';

		// JS parameters for color and width
		// Getting circle style 1 settings
		$atts['timercircle_animation']	= ! empty( $design_data['timercircle_animation'] )	? $design_data['timercircle_animation'] : '';
		$atts['timercircle_width']		= ! empty( $design_data['timercircle_width'] )		? $design_data['timercircle_width']		: '';
		$atts['timer_bg_width']			= ! empty( $design_data['timerbackground_width'] )	? $design_data['timerbackground_width'] : '';
		
		// Getting circle style 2 settings
		$atts['timer2_width']			= ! empty( $design_data['timercircle2_width'] ) ? $design_data['timercircle2_width'] : '';
		
		// Getting clock background colors settings
		$atts['timer_bgclr']			= ! empty( $design_data['timerbackground_color'] )			? $design_data['timerbackground_color']			: '';
		$atts['timer_day_bgclr']		= ! empty( $design_data['timerdaysbackground_color'] )		? $design_data['timerdaysbackground_color'] 	: '';
		$atts['timer_hour_bgclr']		= ! empty( $design_data['timerhoursbackground_color'] ) 	? $design_data['timerhoursbackground_color']	: '';
		$atts['timer_minute_bgclr']		= ! empty( $design_data['timerminutesbackground_color'] )	? $design_data['timerminutesbackground_color']	: '';
		$atts['timer_second_bgclr']		= ! empty( $design_data['timersecondsbackground_color'] )	? $design_data['timersecondsbackground_color']	: '';

		$atts['classes']				.= " wpcdt-timer-{$atts['design']}";
		$atts['classes']				.= ( $bg_img || $bg_clr ) ? " wpcdt-has-bg" : '';

		// Common design class
		if( $atts['design'] == 'design-1' || $atts['design'] == 'design-5' || $atts['design'] == 'design-6' || $atts['design'] == 'design-7' || $atts['design'] == 'design-9' || $atts['design'] == 'design-11' || $atts['design'] == 'design-12' ) {
			$atts['classes'] .= ' wpcdt-timer-clock';
		}

		// If Circle Style 1 is there
		if( $atts['design'] == 'circle' ) {
			wp_enqueue_script( 'wpcdt-timecircle-js' ); // Enqueue Timer Circle JS

			// If simple timer added with completion text
			if( has_shortcode( $wpcdt_timer_completion_text, 'wpcdt_timer' ) ) {
				wp_enqueue_script( 'wpcdt-countereverest-js' );
			}

		} else {
			wp_enqueue_script( 'wpcdt-countereverest-js' ); // Enqueue Counteverest JS
		}
	}

	// Dequeue Timer JS
	wp_dequeue_script( 'wpcdt-public-js' );

	// Enqueue Timer JS
	wp_enqueue_script( 'wpcdt-public-js' );

	$original_post = $GLOBALS['post'];

	ob_start();

	// Print Inline Style
	wpcdt_pro_generate_style( $timer_id, $atts['timer_type'], $atts['design'], $design_data );

	while ( $query->have_posts() ) : $query->the_post();

		// Taking template variables
		$atts['timer_title']	= get_the_title();
		$wpcdt_timer_loop		= $timer_id;

		// Timer type is `Simple`
		if( $atts['timer_type'] == 'simple' ) {

			// If timer is active
			if( $atts['timer_status'] == 'active' ) {

				wpcdt_pro_get_template( 'simple/loop-start.php', $atts ); // Loop Start File

				if( empty( $is_caching ) ) {

					// Design HTML File
					wpcdt_pro_get_template( "simple/design-1.php", $atts );
				}

				wpcdt_pro_get_template( 'simple/loop-end.php', $atts ); // Loop End File
			}

		} else { // Timer type is `Content Timer`

			wpcdt_pro_get_template( 'loop-start.php', $atts ); // Loop Start File

			// If timer is active and caching is not there
			if( $atts['timer_status'] == 'active' && empty( $is_caching ) ) {

				// Design HTML File
				wpcdt_pro_get_template( "{$atts['design']}.php", $atts, null, null, "timer.php" );
			}

			wpcdt_pro_get_template( 'loop-end.php', $atts ); // Loop End File
		}

	endwhile;

	$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	wp_reset_postdata(); // Reset WP Query

	// Reset Global Variable
	$wpcdt_timer_status				= '';
	$wpcdt_timer_loop				= '';
	$wpcdt_timer_completion_text	= '';

	$content .= ob_get_clean();
	return $content;
}

// Countdown Timer Shortcode
add_shortcode( 'wpcdt-countdown', 'wpcdt_pro_countdown_timer' );