<?php
/**
 * Template for Countdown Timer
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/timer.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-timer wpcdt-clearfix">
	<?php if( $is_days ) { ?>
		<div class="wpcdt-col wpcdt-days-wrap">
			<span class="ce-days wpcdt-digits">
				<span class="ce-days-digit">0</span><span class="ce-days-digit">0</span>
			</span>
			<span class="ce-days-label wpcdt-lbl"><?php echo wp_kses_post( $day_text ); ?></span>
		</div>
	<?php }

	if( $is_hours ) { ?>
		<div class="wpcdt-col wpcdt-hours-wrap">
			<span class="ce-hours wpcdt-digits">
				<span class="ce-days-digit">0</span><span class="ce-days-digit">0</span>
			</span>
			<span class="ce-hours-label wpcdt-lbl"><?php echo wp_kses_post( $hour_text ); ?></span>
		</div>
	<?php }

	if( $is_minutes ) { ?>
		<div class="wpcdt-col wpcdt-minutes-wrap">
			<span class="ce-minutes wpcdt-digits">
				<span class="ce-days-digit">0</span><span class="ce-days-digit">0</span>
			</span>
			<span class="ce-minutes-label wpcdt-lbl"><?php echo wp_kses_post( $minute_text ); ?></span>
		</div>
	<?php }

	if( $is_seconds ) { ?>
		<div class="wpcdt-col wpcdt-seconds-wrap">
			<span class="ce-seconds wpcdt-digits">
				<span class="ce-days-digit">0</span><span class="ce-days-digit">0</span>
			</span>
			<span class="ce-seconds-label wpcdt-lbl"><?php echo wp_kses_post( $second_text ); ?></span>
		</div>
	<?php } ?>
</div>