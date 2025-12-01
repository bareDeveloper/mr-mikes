<?php
/**
 * Template for Countdown Timer Design 4
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/design-4.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-design-4">
	<?php if( $is_days ) { ?>
		<div class="wpcdt-bar-wrap wpcdt-days-wrap wpcdt-clearfix">
			<div class="wpcdt-bar ce-bar-days">
				<div class="wpcdt-fill"></div>
			</div>
			<span class="ce-days wpcdt-digits">00</span>
			<span class="ce-days-label wpcdt-lbl"><?php echo wp_kses_post( $day_text ); ?></span>
		</div>
	<?php }

	if( $is_hours ) { ?>
		<div class="wpcdt-bar-wrap wpcdt-hours-wrap wpcdt-clearfix">
			<div class="wpcdt-bar ce-bar-hours">
				<div class="wpcdt-fill"></div>
			</div>
			<span class="ce-hours wpcdt-digits">00</span>
			<span class="ce-hours-label wpcdt-lbl"><?php echo wp_kses_post( $hour_text ); ?></span>
		</div>
	<?php }

	if( $is_minutes ) { ?>
		<div class="wpcdt-bar-wrap wpcdt-minutes-wrap wpcdt-clearfix">
			<div class="wpcdt-bar ce-bar-minutes">
				<div class="wpcdt-fill"></div>
			</div>
			<span class="ce-minutes wpcdt-digits">00</span>
			<span class="ce-minutes-label wpcdt-lbl"><?php echo wp_kses_post( $minute_text ); ?></span>
		</div>
	<?php }

	if( $is_seconds ) { ?>
		<div class="wpcdt-bar-wrap wpcdt-seconds-wrap wpcdt-clearfix">
			<div class="wpcdt-bar ce-bar-seconds">
				<div class="wpcdt-fill"></div>
			</div>
			<span class="ce-seconds wpcdt-digits">00</span>
			<span class="ce-seconds-label wpcdt-lbl"><?php echo wp_kses_post( $second_text ); ?></span>
		</div>
	<?php } ?>
</div>