<?php
/**
 * General Settings
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$recuring_prefix	= wpcdt_pro_get_option( 'recuring_prefix', 'wpcdt_' );
$post_guten_editor	= wpcdt_pro_get_option( 'post_guten_editor' );
?>

<div class="postbox">

	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'General Settings', 'countdown-timer-ultimate' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<th>
						<label for="wpcdt-recuring-prefix"><?php esc_html_e('Recurring Prefix', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="wpcdt_pro_options[recuring_prefix]" value="<?php echo esc_attr( $recuring_prefix ); ?>" class="wpcdt-recuring-prefix" id="wpcdt-recuring-prefix" /><br/>
						<span class="description"><?php esc_html_e('Enter recurring timer storage prefix. Changing the value will restart the recurring countdown timer again for all users. Default recurring prefix is "wpcdt_".', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="wpcdt-gutenberg-editor"><?php esc_html_e('Enable Gutenberg Editor', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="checkbox" id="wpcdt-gutenberg-editor" name="wpcdt_pro_options[post_guten_editor]" value="1" <?php checked( $post_guten_editor, 1 ); ?> /><br />
						<span class="description"><?php esc_html_e( 'Check this box to enable Gutenberg editor for countdown timer post type.', 'countdown-timer-ultimate' ); ?></span><br />
						<span class="description"><?php esc_html_e( 'Note: This will only work for WordPress 5.0 and more.', 'countdown-timer-ultimate' ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="wpcdt_settings_submit" class="button button-primary right" value="<?php esc_html_e('Save Changes', 'countdown-timer-ultimate'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>