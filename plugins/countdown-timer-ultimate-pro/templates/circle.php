<?php
/**
 * Template for Countdown Timer Circle
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/circle.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-circle" data-timer="<?php echo esc_attr( $totalseconds ); ?>"></div>