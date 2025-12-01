<?php
/**
 * EDD (Easy Digital Download) Settings
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some data
$timer_position_data	= wpcdt_pro_edd_timer_pos_opts();
$timer_type_data		= wpcdt_pro_timer_type_opts();
$edd_enable				= wpcdt_pro_get_option( 'edd_enable' );
$edd_timer_type			= wpcdt_pro_get_option( 'edd_timer_type', 'timer' );
$edd_timer_id			= wpcdt_pro_get_option( 'edd_timer_id' );
$edd_timer_shrt			= wpcdt_pro_get_option( 'edd_timer_shrt' );
$edd_single				= wpcdt_pro_get_option( 'edd_single' );
$edd_single_pos			= wpcdt_pro_get_option( 'edd_single_pos' );
$edd_shop				= wpcdt_pro_get_option( 'edd_shop' );
$edd_shop_pos			= wpcdt_pro_get_option( 'edd_shop_pos' );
$timer_post_data		= wpcdt_pro_get_timer_posts( $edd_timer_id );
?>

<div class="postbox">

	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'Easy Digital Download Settings', 'countdown-timer-ultimate' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<th>
						<label for="wpcdt-edd-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[edd_enable]" id="wpcdt-edd-enable" class="wpcdt-checkbox wpcdt-edd-enable" value="1" <?php checked( $edd_enable, 1 ); ?> /><br/>
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for EDD products. This will enable countdown timer settings within EDD product settings.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr>
					<th>
						<label for="wpcdt-edd-timer-type"><?php esc_html_e('Timer Type', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[edd_timer_type]" class="wpcdt-select wpcdt-show-hide wpcdt-edd-timer-type" id="wpcdt-edd-timer-type" data-prefix="wc">
							<?php if( ! empty( $timer_type_data ) ) {
								foreach ($timer_type_data as $type_key => $type_val) { ?>
									<option value="<?php echo esc_attr( $type_key ); ?>" <?php selected( $type_key, $edd_timer_type ); ?>><?php echo esc_html( $type_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer type.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr class="wpcdt-show-hide-row-wc wpcdt-show-if-wc-timer" style="<?php if( $edd_timer_type != 'timer' ) { echo 'display: none;'; } ?>">
					<th>
						<label for="wpcdt-edd-timer"><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[edd_timer_id]" class="wpcdt-select wpcdt-edd-timer" id="wpcdt-edd-timer">
							<?php if( ! empty( $timer_post_data ) ) { ?>
								<option value=""><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></option>
								<?php echo $timer_post_data; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
							<?php } ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer for all products. This is general setting. You can choose the different timer from the individual product as well.', 'countdown-timer-ultimate'); ?></span><br/>
						<span class="description"><?php esc_html_e('Note : `Simple Timer Type` countdown is the best option to choose because of compact layout.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr class="wpcdt-show-hide-row-wc wpcdt-show-if-wc-shortcode" style="<?php if( $edd_timer_type != 'shortcode' ) { echo 'display: none;'; } ?>">
					<th>
						<label for="wpcdt-timer-shrt"><?php esc_html_e('Timer Shortcode', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<textarea name="wpcdt_pro_options[edd_timer_shrt]" class="large-text wpcdt-timer-shrt" id="wpcdt-timer-shrt" rows="2" cols="20"><?php echo esc_textarea( $edd_timer_shrt ); ?></textarea><br/>
						<span class="description"><?php esc_html_e('Enter countdown timer shortcode for all the products. e.g', 'countdown-timer-ultimate'); ?> <span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt_timer timer_id="123" start_date="2021-01-24 23:59:59" end_date="2021-01-26 23:59:59"]</span></span><br/>
						<span class="description"><?php echo sprintf( __('Please take a look at the plugin %sdocumentation%s for all parameters.', 'countdown-timer-ultimate'), '<a href="https://docs.essentialplugin.com/countdown-timer-ultimate-pro/" target="_blank">', '</a>' ); ?></span>
					</td>
				</tr>

				<!-- Start - Easy Digital Download Single Page Settings -->
				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Single Product Page Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-edd-single-pdt-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[edd_single]" id="wpcdt-edd-single-pdt-enable" class="wpcdt-checkbox wpcdt-edd-single-pdt-enable" value="1" <?php checked( $edd_single, 1 ); ?> /><br/>
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for single product page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-edd-single-pdt-timer-pos"><?php esc_html_e('Timer Position', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[edd_single_pos]" class="wpcdt-select wpcdt-edd-single-pdt-timer-pos" id="wpcdt-edd-single-pdt-timer-pos">
							<?php if( ! empty( $timer_position_data ) ) {
								foreach ($timer_position_data as $position_key => $position_val) { ?>
									<option value="<?php echo esc_attr( $position_key ); ?>" <?php selected( $position_key, $edd_single_pos ); ?>><?php echo esc_html( $position_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer position for single product page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<!-- End - Easy Digital Download Single Page Settings -->

				<!-- Start - Easy Digital Download Shop Page Settings -->
				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Shop Page Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-edd-shop-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[edd_shop]" id="wpcdt-edd-shop-enable" class="wpcdt-checkbox wpcdt-edd-shop-enable" value="1" <?php checked( $edd_shop, 1 ); ?> /><br/>
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for shop page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-edd-shop-timer-pos"><?php esc_html_e('Timer Position', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[edd_shop_pos]" class="wpcdt-select wpcdt-edd-shop-timer-pos" id="wpcdt-edd-shop-timer-pos">
							<?php if( ! empty( $timer_position_data ) ) {
								foreach ($timer_position_data as $position_key => $position_val) { ?>
									<option value="<?php echo esc_attr( $position_key ); ?>" <?php selected( $position_key, $edd_shop_pos ); ?>><?php echo esc_html( $position_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer position on shop page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<!-- End - Easy Digital Download Shop Page Settings -->

				<tr>
					<td colspan="2">
						<input type="submit" name="wpcdt_sett_submit" class="button button-primary right wpcdt-edd-sett-submit" value="<?php esc_html_e('Save Changes', 'countdown-timer-ultimate'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->