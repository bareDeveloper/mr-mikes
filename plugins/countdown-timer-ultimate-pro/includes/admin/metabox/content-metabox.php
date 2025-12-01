<?php
/**
 * Handles Content Setting metabox HTML
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some data
$content_data		= get_post_meta( $post->ID, $prefix.'content', true );
$show_title			= ! empty( $content_data['show_title'] ) 		? 1	: 0;
$content_position	= ! empty( $content_data['content_position'] ) 	? $content_data['content_position'] : 'below_timer';
$completion_text	= ! empty( $content_data['completion_text'] ) 	? $content_data['completion_text'] 	: '';
?>

<div id="wpcdt_content_sett" class="wpcdt-vtab-cnt wpcdt-content-sett wpcdt-clearfix">

	<div class="wpcdt-tab-info-wrap">
		<div class="wpcdt-tab-title"><?php esc_html_e('Content Settings', 'countdown-timer-ultimate'); ?></div>
		<span class="wpcdt-tab-desc"><?php esc_html_e('Choose Timer content settings.', 'countdown-timer-ultimate'); ?></span>
	</div>

	<table class="form-table wpcdt-tbl">
		<tbody>
			<tr>
				<th>
					<label for="wpcdt-show-title"><?php esc_html_e('Show Title', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>content[show_title]" class="wpcdt-select wpcdt-show-title" id="wpcdt-show-title">
						<option value="1" <?php selected( $show_title, 1 ); ?>><?php esc_html_e('Show', 'countdown-timer-ultimate'); ?></option>
						<option value="0" <?php selected( $show_title, 0 ); ?>><?php esc_html_e('Hide', 'countdown-timer-ultimate'); ?></option>
					</select><br/>
					<span class="description"><?php esc_html_e('Show / Hide timer title.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-content-position"><?php esc_html_e('Content Position', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>content[content_position]" class="wpcdt-select wpcdt-content-position" id="wpcdt-content-position">
						<option value="above_timer" <?php selected( $content_position, 'above_timer' ); ?>><?php esc_html_e('Above Timer', 'countdown-timer-ultimate'); ?></option>
						<option value="below_timer" <?php selected( $content_position, 'below_timer' ); ?>><?php esc_html_e('Below Timer', 'countdown-timer-ultimate'); ?></option>
					</select><br/>
					<span class="description"><?php esc_html_e('Set the timer content position.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-completion-txt"><?php esc_html_e('Completion Text', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<?php wp_editor( $completion_text, 'wpcdt-completion-txt', array('textarea_name' => $prefix.'content[completion_text]', 'textarea_rows' => 8) ); ?>
					<span class="description"><?php esc_html_e('Enter completion text which will be shown after the countdown timer is completed.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div>