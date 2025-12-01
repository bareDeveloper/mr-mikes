<?php
/**
 * Template for Countdown Timer Design 2
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/design-2.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-design-2 wpcdt-clearfix">
	<?php if( $is_days ) { ?>
		<div class="wpcdt-unit-wrap wpcdt-days-wrap">
			<div class="days wpcdt-digits-wrap wpcdt-clearfix" data-unit="days">
				<div class="wpcdt-digits">0</div>
				<div class="wpcdt-digits">0</div>
			</div>
			<span class="ce-days-label wpcdt-lbl"><?php echo wp_kses_post( $day_text ); ?></span>
		</div>
	<?php }

	if( $is_hours ) { ?>
		<div class="wpcdt-unit-wrap wpcdt-hours-wrap">
			<div class="hours wpcdt-digits-wrap wpcdt-clearfix" data-unit="hours">
				<div class="wpcdt-digits">0</div>
				<div class="wpcdt-digits">0</div>
			</div>
			<span class="ce-hours-label wpcdt-lbl"><?php echo wp_kses_post( $hour_text ); ?></span>
		</div>
	<?php }

	if( $is_minutes ) { ?>
		<div class="wpcdt-unit-wrap wpcdt-minutes-wrap">
			<div class="minutes wpcdt-digits-wrap wpcdt-clearfix" data-unit="minutes">
				<div class="wpcdt-digits">0</div>
				<div class="wpcdt-digits">0</div>
			</div>
			<span class="ce-minutes-label wpcdt-lbl"><?php echo wp_kses_post( $minute_text ); ?></span>
		</div>
	<?php }

	if( $is_seconds ) { ?>
		<div class="wpcdt-unit-wrap wpcdt-seconds-wrap">
			<div class="seconds wpcdt-digits-wrap wpcdt-clearfix" data-unit="seconds">
				<div class="wpcdt-digits">0</div>
				<div class="wpcdt-digits">0</div>
			</div>
			<span class="ce-seconds-label wpcdt-lbl"><?php echo wp_kses_post( $second_text ); ?></span>
		</div>
	<?php } ?>
</div>