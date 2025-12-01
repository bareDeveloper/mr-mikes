<?php
/**
 * Template for Countdown Timer Loop - Start
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/loop-start.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wpcdt-wrap wpcdt-timer-wrap wpcdt-clearfix <?php echo esc_attr( $classes ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $args ) ); ?>">
	<div class="wpcdt-timer-inr wpcdt-timer wpcdt-timer-js" id="wpcdt-timer-<?php echo esc_attr( $unique ); ?>" data-id="<?php echo esc_attr( $timer_id ); ?>">
		<?php if( $show_title && ! empty( $timer_title ) ) { ?>
			<div class="wpcdt-title"><?php echo wp_kses_post( $timer_title ); ?></div>
		<?php }

		if( ( ( $timer_status == 'schedule' || $timer_status == 'active' ) || ( $timer_status == 'finish' && ! empty( $content_after_complete ) ) ) && ( get_the_content() && $content_position == 'above_timer' ) ) { ?>
			<div class="wpcdt-desc"><?php the_content(); ?></div>
		<?php }

		if( $content_position == 'above_timer' && $is_caching == 1 ) { ?>
			<div class="wpcdt-ajax-clock"></div>
		<?php } ?>