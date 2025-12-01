<?php
/**
 * Template for Countdown Timer Design 8
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/design-8.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock wpcdt-clock-design-8 wpcdt-clearfix">
	<?php if( $is_days ) { ?>
	<div class="wpcdt-col wpcdt-days-wrap">
		<div class="ce-days wpcdt-digits">
			<div class="wpcdt-flip-wrap">
				<div class="wpcdt-flip-front wpcdt-hflip-inr"></div>
				<div class="wpcdt-flip-back wpcdt-hflip-inr"></div>
			</div>
		</div>
		<span class="ce-days-label wpcdt-lbl"><?php echo wp_kses_post( $day_text ); ?></span>
	</div>
	<?php }

	if( $is_hours ) { ?>
	<div class="wpcdt-col wpcdt-hours-wrap">
		<div class="ce-hours wpcdt-digits">
			<div class="wpcdt-flip-wrap">
				<div class="wpcdt-flip-front wpcdt-hflip-inr"></div>
				<div class="wpcdt-flip-back wpcdt-hflip-inr"></div>
			</div>
		</div>
		<span class="ce-hours-label wpcdt-lbl"><?php echo wp_kses_post( $hour_text ); ?></span>
	</div>
	<?php }

	if( $is_minutes ) { ?>
	<div class="wpcdt-col wpcdt-minutes-wrap">
		<div class="ce-minutes wpcdt-digits">
			<div class="wpcdt-flip-wrap">
				<div class="wpcdt-flip-front wpcdt-hflip-inr"></div>
				<div class="wpcdt-flip-back wpcdt-hflip-inr"></div>
			</div>
		</div>
		<span class="ce-minutes-label wpcdt-lbl"><?php echo wp_kses_post( $minute_text ); ?></span>
	</div>
	<?php }

	if( $is_seconds ) { ?>
	<div class="wpcdt-col wpcdt-seconds-wrap">
		<div class="ce-seconds wpcdt-digits">
			<div class="wpcdt-flip-wrap">
				<div class="wpcdt-flip-front wpcdt-hflip-inr"></div>
				<div class="wpcdt-flip-back wpcdt-hflip-inr"></div>
			</div>
		</div>
		<span class="ce-seconds-label wpcdt-lbl"><?php echo wp_kses_post( $second_text ); ?></span>
	</div>
	<?php } ?>
</div>