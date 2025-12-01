<?php
/**
 * Handles shortcode preview metabox HTML
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
?>

<p><?php esc_html_e('To display shortcode, add the following shortcode to your page or post.', 'countdown-timer-ultimate'); ?></p>
<div class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt-countdown id="<?php echo esc_attr( $post->ID ); ?>"]</div>

<p><?php esc_html_e('If adding the shortcode to your theme files, add the following template code.', 'countdown-timer-ultimate'); ?></p>
<div class="wpos-copy-clipboard wpcdt-shortcode-preview">&lt;?php echo do_shortcode('[wpcdt-countdown id="<?php echo esc_attr( $post->ID ); ?>"]'); ?&gt;</div>