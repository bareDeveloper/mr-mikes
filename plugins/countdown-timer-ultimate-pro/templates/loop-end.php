<?php
/**
 * Template for Countdown Timer Loop - End
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/loop-end.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

		if( $content_position == 'below_timer' && $is_caching == 1 ) { ?>
			<div class="wpcdt-ajax-clock"></div>
		<?php }

		if( ( ( $timer_status == 'schedule' || $timer_status == 'active' ) || ( $timer_status == 'finish' && ! empty( $content_after_complete ) ) ) && ( get_the_content() && $content_position == 'below_timer' ) ) { ?>
			<div class="wpcdt-desc"><?php the_content(); ?></div>
		<?php }

		// Completion Text
		wpcdt_pro_get_template( 'completion-text.php', $args );

		?>
	</div>
</div>