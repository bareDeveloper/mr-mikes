<?php
/**
 * Timer Shortcode `wpcdt_timer`
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
function wpcdt_pro_render_simple_timer( $atts, $content ) {

	// Taking some globals
	global $post, $wpcdt_timer_status;

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Simple Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt_timer
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Simple Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt_timer
				</div>';
	}

	$atts = shortcode_atts( array(
		'timer_id'		=> '',			// A Unique ID required for recurring timer
		'start_date'	=> '',			// Y-m-d H:i:s Format
		'end_date'		=> '',			// Y-m-d H:i:s Format
		'timer_mode'	=> 'default',	// default, evergreen, recurring
		'recur_mode'	=> 'daily',		// daily, weekly, custom
		'recuring_time'	=> '',			// Any Numeric Value
		'recuring_type'	=> 'minute',	// minute, hour, day
		'start_time'	=> '00:00:00',	// H:i:s Format
		'end_time'		=> '23:59:59',	// H:i:s Format
		'recur_on'		=> '',			// Comma separate Values of days number
		'week_start'	=> 1,			// Mon - 1, Tue - 2, Wed - 3, Thu - 4, Fri - 5, Sat - 6, Sun - 0
		'week_end'		=> 0,			// Mon - 1, Tue - 2, Wed - 3, Thu - 4, Fri - 5, Sat - 6, Sun - 0
		'is_days'		=> 'true',
		'is_hours'		=> 'true',
		'is_minutes'	=> 'true',
		'is_seconds'	=> 'true',
		'day_text'		=> '',
		'hour_text'		=> '',
		'minute_text'	=> '',
		'second_text'	=> '',
		'extra_class'	=> '',
		'is_caching'	=> 'false',
	), $atts, 'wpcdt_timer' );

	$atts['unique']			= wpcdt_pro_get_unique();
	$atts['extra_class']	= wpcdt_pro_sanitize_html_classes( $atts['extra_class'] );
	$atts['timer_id']		= ! empty( $atts['timer_id'] )		? $atts['timer_id']			: 0;
	$atts['start_date']		= isset( $atts['start_date'] )		? $atts['start_date']		: '';
	$atts['end_date']		= ! empty( $atts['end_date'] )		? $atts['end_date']			: date_i18n( 'Y-m-d H:i:s', strtotime("+1 day", current_time('timestamp')) );
	$atts['recuring_time']	= ! empty( $atts['recuring_time'] )	? $atts['recuring_time']	: '';
	$atts['recuring_type']	= ! empty( $atts['recuring_type'] )	? $atts['recuring_type']	: 'minute';
	$atts['day_text']		= ! empty( $atts['day_text'] )		? $atts['day_text']			: esc_html__('Days', 'countdown-timer-ultimate');
	$atts['hour_text']		= ! empty( $atts['hour_text'] )		? $atts['hour_text']		: esc_html__('Hours', 'countdown-timer-ultimate');
	$atts['minute_text']	= ! empty( $atts['minute_text'] )	? $atts['minute_text']		: esc_html__('Minutes', 'countdown-timer-ultimate');
	$atts['second_text']	= ! empty( $atts['second_text'] )	? $atts['second_text']		: esc_html__('Seconds', 'countdown-timer-ultimate');
	$atts['start_time']		= ! empty( $atts['start_time'] )	? $atts['start_time']		: '00:00:00';
	$atts['end_time']		= ! empty( $atts['end_time'] )		? $atts['end_time']			: '23:59:59';
	$atts['timer_mode']		= ! empty( $atts['timer_mode'] )	? $atts['timer_mode']		: 'default';
	$atts['recur_mode']		= ! empty( $atts['recur_mode'] )	? $atts['recur_mode']		: 'daily';
	$atts['week_start']		= isset( $atts['week_start'] )		? $atts['week_start']		: 1;
	$atts['week_end']		= isset( $atts['week_end'] )		? $atts['week_end']			: 0;
	$atts['recur_on']		= ! empty( $atts['recur_on'] )		? $atts['recur_on']			: '';
	$atts['is_days']		= ( $atts['is_days'] == 'true' )	? 1	: 0;
	$atts['is_hours']		= ( $atts['is_hours'] == 'true' )	? 1	: 0;
	$atts['is_minutes']		= ( $atts['is_minutes'] == 'true' )	? 1	: 0;
	$atts['is_seconds']		= ( $atts['is_seconds'] == 'true' )	? 1	: 0;
	$atts['is_caching']		= ( $atts['is_caching'] == 'true' )	? 1	: 0;

	extract( $atts );

	// Recurring Data Array
	$recurring_data = array(
		'recur_mode'	=> $recur_mode,
		'start_time'	=> $start_time,
		'end_time'		=> $end_time,
		'week_start'	=> $week_start,
		'week_end'		=> $week_end,
		'recur_on'		=> explode(',', $recur_on),
	);

	// Taking some variables
	$current_time		= current_time('timestamp');
	$recurring_dates	= wpcdt_pro_recurring_dates( $timer_mode, $recurring_data );

	// Check with master start date
	if( ! empty( $recurring_dates['start_date'] ) && ( strtotime( $start_date ) <= strtotime( $recurring_dates['start_date'] ) ) ) {
		$start_date = $recurring_dates['start_date'];
	}

	// Check with master end date
	if( ! empty( $recurring_dates['timer_date'] ) && ( strtotime( $end_date ) >= strtotime( $recurring_dates['timer_date'] ) ) ) {
		$end_date = $recurring_dates['timer_date'];
	}

	/* Return Timer ID is not there
	 * Return Timer End date is not there
	 */
	if( empty( $end_date ) || empty( $timer_id ) ) {
		return $content;
	}

	// Apply filter for change timer `Start Date` & `End Date`
	$start_date	= apply_filters( 'wpcdt_timer_start_date', $start_date, $timer_id );
	$end_date	= apply_filters( 'wpcdt_timer_end_date', $end_date, $timer_id );

	// Taking some variable
	$start_date	= strtotime( $start_date );
	$end_date	= strtotime( $end_date );

	// Set Timer Status
	if( $start_date && $start_date > $current_time ) {
		$wpcdt_timer_status = 'schedule';
	} else if ( $end_date >= $current_time ) {
		$wpcdt_timer_status = 'active';
	} else {
		$wpcdt_timer_status = 'finish';
	}

	// If timer is not active then simply return
	if( $wpcdt_timer_status != 'active' ) {
		return $content;
	}

	// If `Timer Mode` is `Evergreen`
	if( $timer_mode == 'evergreen' ) {

		if( $recuring_time ) {

			if( $recuring_type == 'day' ) {
				$type_time = 86400;
			} else if( $recuring_type == 'hour' ) {
				$type_time = 3600;
			} else {
				$type_time = 60;
			}

			$atts['recuring_date'] = $current_time + ( $recuring_time * $type_time );

			// Check with master end date
			if( $atts['recuring_date'] >= $end_date ) {
				$atts['recuring_date'] = $end_date;
			}

			// Recuring time diff
			$atts['recuring_diff']['year']		= (int)date( 'Y', $atts['recuring_date'] );
			$atts['recuring_diff']['month']		= (int)date( 'm', $atts['recuring_date'] );
			$atts['recuring_diff']['day']		= (int)date( 'd', $atts['recuring_date'] );
			$atts['recuring_diff']['hour']		= (int)date( 'H', $atts['recuring_date'] );
			$atts['recuring_diff']['minute']	= (int)date( 'i', $atts['recuring_date'] );
			$atts['recuring_diff']['second']	= (int)date( 's', $atts['recuring_date'] );
		}
	}

	// Date difference
	$atts['date_diff']['year']		= (int)date( 'Y', $end_date );
	$atts['date_diff']['month']		= (int)date( 'm', $end_date );
	$atts['date_diff']['day']		= (int)date( 'd', $end_date );
	$atts['date_diff']['hour']		= (int)date( 'H', $end_date );
	$atts['date_diff']['minute']	= (int)date( 'i', $end_date );
	$atts['date_diff']['second']	= (int)date( 's', $end_date );

	// Taking atts data
	$atts['date_c']			= date('c');
	$atts['timer_type']		= 'simple';
	$atts['timer_style']	= 'normal';
	$atts['current_time']	= $current_time;
	$atts['timer_status']	= $wpcdt_timer_status;
	$atts['classes']		= "wpcdt-timer-{$timer_id} wpcdt-smpl-timer-design-1 {$extra_class}";

	// If caching is there class will be added
	if( $is_caching == 1 && $atts['timer_status'] == 'active' ) {
		$atts['classes'] .= ' wpcdt-timer-ajax';
	}

	// Dequeue Timer JS
	wp_dequeue_script( 'wpcdt-public-js' );

	// Enqueue Scripts
	wp_enqueue_script( 'wpcdt-countereverest-js' );
	wp_enqueue_script( 'wpcdt-public-js' );

	ob_start();

	wpcdt_pro_get_template( 'simple/loop-start.php', $atts ); // Loop Start File

	if( empty( $is_caching ) ) {

		// Design HTML File
		wpcdt_pro_get_template( "simple/design-1.php", $atts );
	}

	wpcdt_pro_get_template( 'simple/loop-end.php', $atts ); // Loop End File

	// Reset Global Variable
	$wpcdt_timer_status	= '';

	$content .= ob_get_clean();
	return $content;
}

// Simple Timer Shortcode
add_shortcode( 'wpcdt_timer', 'wpcdt_pro_render_simple_timer' );