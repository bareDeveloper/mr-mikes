<?php
/**
 * Template for Countdown Simple Timer Loop - Start
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/simple/loop-start.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-wrap wpcdt-smpl-timer-wrap wpcdt-timer-wrap wpcdt-clearfix <?php echo esc_attr( $classes ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $args ) ); ?>">
	<div class="wpcdt-timer wpcdt-timer-inr wpcdt-timer-js" id="wpcdt-smpl-timer-<?php echo esc_attr( $unique ); ?>">