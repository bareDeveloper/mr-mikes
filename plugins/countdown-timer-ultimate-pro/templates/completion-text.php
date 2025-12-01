<?php
/**
 * Template for Countdown Timer Loop - End
 *
 * This template can be overridden by copying it to yourtheme/countdown-timer-ultimate-pro/completion-text.php
 *
 * @package Countdown Timer Ultimate Pro
 * @version 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpcdt_timer_completion_text;

if( $timer_status != 'finish' || empty( $wpcdt_timer_completion_text ) ) {
	return;
}
?>
<div class="wpcdt-completion-wrap"><?php echo wpcdt_pro_render_content( $wpcdt_timer_completion_text ); // WPCS: XSS ok. ?></div>