<?php
/**
 * Custom CSS Settings
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="postbox">
	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'Custom CSS Settings', 'countdown-timer-ultimate' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<th scope="row">
						<label for="wpcdt-custom-css"><?php esc_html_e('Custom CSS', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<textarea name="wpcdt_pro_options[custom_css]" class="large-text wpcdt-custom-css wpcdt-code-editor" id="wpcdt-custom-css" rows="15"><?php echo esc_textarea( wpcdt_pro_get_option('custom_css') ); ?></textarea>
						<span class="description"><?php esc_html_e('Enter custom CSS to override plugin CSS. Sometime !important will work.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="wpcdt_settings_submit" class="button button-primary right wpcdt-btn wpcdt-post-type-sett-submit" value="<?php esc_html_e('Save Changes', 'countdown-timer-ultimate'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>