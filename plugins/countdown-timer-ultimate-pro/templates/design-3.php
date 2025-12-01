<?php
/**
 * Template for Countdown Timer Design 3
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/design-3.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-design-3 wpcdt-clearfix">
	<?php if( $is_days ) { ?>
	<div class="wpcdt-circle-wrap wpcdt-days-wrap">
		<canvas id="ce-days-<?php echo esc_attr( $unique ); ?>" class="wpcdt-ce-days" width="400" height="400"></canvas>
		<div class="wpcdt-circle-inr">
			<span class="wpcdt-digits ce-days">00</span>
			<span class="wpcdt-lbl ce-days-label"><?php echo wp_kses_post( $day_text ); ?></span>
		</div>
	</div>
	<?php }

	if( $is_hours ) { ?>
	<div class="wpcdt-circle-wrap wpcdt-hours-wrap">
		<canvas id="ce-hours-<?php echo esc_attr( $unique ); ?>" class="wpcdt-ce-hours" width="400" height="400"></canvas>
		<div class="wpcdt-circle-inr">
			<span class="wpcdt-digits ce-hours">00</span>
			<span class="wpcdt-lbl ce-hours-label"><?php echo wp_kses_post( $hour_text ); ?></span>
		</div>
	</div>
	<?php }

	if( $is_minutes ) { ?>
	<div class="wpcdt-circle-wrap wpcdt-minutes-wrap">
		<canvas id="ce-minutes-<?php echo esc_attr( $unique ); ?>" class="wpcdt-ce-minutes" width="400" height="400"></canvas>
		<div class="wpcdt-circle-inr">
			<span class="wpcdt-digits ce-minutes">00</span>
			<span class="wpcdt-lbl ce-minutes-label"><?php echo wp_kses_post( $minute_text ); ?></span>
		</div>
	</div>
	<?php }

	if( $is_seconds ) { ?>
	<div class="wpcdt-circle-wrap wpcdt-seconds-wrap">
		<canvas id="ce-seconds-<?php echo esc_attr( $unique ); ?>" class="wpcdt-ce-seconds" width="400" height="400"></canvas>
		<div class="wpcdt-circle-inr">
			<span class="wpcdt-digits ce-seconds">00</span>
			<span class="wpcdt-lbl ce-seconds-label"><?php echo wp_kses_post( $second_text ); ?></span>
		</div>
	</div>
	<?php } ?>
</div>