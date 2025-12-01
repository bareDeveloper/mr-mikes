<?php
/**
 * Template for Simple Timer Design 1
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/simple/design-1.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-clock-simple wpcdt-clearfix">
	<?php if( $is_days ) { ?>
	<div class="wpcdt-smpl-col wpcdt-days-wrap">
		<div class="ce-days wpcdt-smpl-digits">00</div>
		<span class="ce-days-label wpcdt-smpl-lbl"><?php echo wp_kses_post( $day_text ); ?></span>
	</div>
	<?php }

	if( $is_hours ) { ?>
	<div class="wpcdt-smpl-col wpcdt-hours-wrap">
		<div class="ce-hours wpcdt-smpl-digits">00</div>
		<span class="ce-hours-label wpcdt-smpl-lbl"><?php echo wp_kses_post( $hour_text ); ?></span>
	</div>
	<?php }

	if( $is_minutes ) { ?>
	<div class="wpcdt-smpl-col wpcdt-minutes-wrap">
		<div class="ce-minutes wpcdt-smpl-digits">00</div>
		<span class="ce-minutes-label wpcdt-smpl-lbl"><?php echo wp_kses_post( $minute_text ); ?></span>
	</div>
	<?php }

	if( $is_seconds ) { ?>
	<div class="wpcdt-smpl-col wpcdt-seconds-wrap">
		<div class="ce-seconds wpcdt-smpl-digits">00</div>
		<span class="ce-seconds-label wpcdt-smpl-lbl"><?php echo wp_kses_post( $second_text ); ?></span>
	</div>
	<?php } ?>
</div>