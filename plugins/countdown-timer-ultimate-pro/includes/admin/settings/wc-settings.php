<?php
/**
 * WooCommerce Settings
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some data
$timer_position_data	= wpcdt_pro_wc_timer_pos_opts();
$timer_type_data		= wpcdt_pro_timer_type_opts();
$wc_enable				= wpcdt_pro_get_option('wc_enable');
$wc_timer_type			= wpcdt_pro_get_option('wc_timer_type', 'timer');
$wc_timer_id			= wpcdt_pro_get_option('wc_timer_id');
$wc_timer_shrt			= wpcdt_pro_get_option('wc_timer_shrt');
$wc_single				= wpcdt_pro_get_option('wc_single');
$wc_single_pos			= wpcdt_pro_get_option('wc_single_pos');
$wc_shop				= wpcdt_pro_get_option('wc_shop');
$wc_shop_pos			= wpcdt_pro_get_option('wc_shop_pos');
$timer_post_data		= wpcdt_pro_get_timer_posts( $wc_timer_id );
?>

<div class="postbox">

	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'WooCommerce Settings', 'countdown-timer-ultimate' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<th>
						<label for="wpcdt-wc-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[wc_enable]" id="wpcdt-wc-enable" class="wpcdt-checkbox wpcdt-wc-enable" value="1" <?php checked( $wc_enable, 1 ); ?> /><br />
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for WooCommerce products. This will enable countdown timer settings within WooCommerce product settings.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr>
					<th>
						<label for="wpcdt-wc-timer-type"><?php esc_html_e('Timer Type', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[wc_timer_type]" class="wpcdt-select wpcdt-show-hide wpcdt-wc-timer-type" id="wpcdt-wc-timer-type" data-prefix="wc">
							<?php if( ! empty( $timer_type_data ) ) {
								foreach( $timer_type_data as $type_key => $type_val ) { ?>
									<option value="<?php echo esc_attr( $type_key ); ?>" <?php selected( $type_key, $wc_timer_type ); ?>><?php echo esc_html( $type_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer type.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr class="wpcdt-show-hide-row-wc wpcdt-show-if-wc-timer" style="<?php if( $wc_timer_type != 'timer' ) { echo 'display: none;'; } ?>">
					<th>
						<label for="wpcdt-wc-timer"><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[wc_timer_id]" class="wpcdt-select wpcdt-wc-timer" id="wpcdt-wc-timer">
							<?php if( ! empty( $timer_post_data ) ) { ?>
								<option value=""><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></option>
								<?php echo $timer_post_data; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
							<?php } ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer for all products. This is general setting. You can choose the different timer from the individual product as well.', 'countdown-timer-ultimate'); ?></span><br/>
						<span class="description"><?php esc_html_e('Note : `Simple Timer Type` countdown is the best option to choose because of compact layout.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<tr class="wpcdt-show-hide-row-wc wpcdt-show-if-wc-shortcode" style="<?php if( $wc_timer_type != 'shortcode' ) { echo 'display: none;'; } ?>">
					<th>
						<label for="wpcdt-timer-shrt"><?php esc_html_e('Timer Shortcode', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<textarea name="wpcdt_pro_options[wc_timer_shrt]" class="large-text wpcdt-timer-shrt" id="wpcdt-timer-shrt" rows="2" cols="20"><?php echo esc_textarea( $wc_timer_shrt ); ?></textarea><br/>
						<span class="description"><?php esc_html_e('Enter countdown timer shortcode for all the products. e.g', 'countdown-timer-ultimate'); ?> <span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt_timer timer_id="123" start_date="2021-01-24 23:59:59" end_date="2021-01-26 23:59:59"]</span></span><br/>
						<span class="description"><?php echo sprintf( __('Please take a look at the plugin %sdocumentation%s for all parameters.', 'countdown-timer-ultimate'), '<a href="https://docs.essentialplugin.com/countdown-timer-ultimate-pro/" target="_blank">', '</a>' ); ?></span>
					</td>
				</tr>

				<!-- Start - WooCommerce Single Page Settings -->
				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Single Product Page Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-wc-single-pdt-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[wc_single]" id="wpcdt-wc-single-pdt-enable" class="wpcdt-checkbox wpcdt-wc-single-pdt-enable" value="1" <?php checked( $wc_single, 1 ); ?> /><br />
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for single product page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-wc-single-pdt-timer-pos"><?php esc_html_e('Timer Position', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[wc_single_pos]" class="wpcdt-select wpcdt-wc-single-pdt-timer-pos" id="wpcdt-wc-single-pdt-timer-pos">
							<?php if( ! empty( $timer_position_data ) ) {
								foreach( $timer_position_data as $position_key => $position_val ) { ?>
									<option value="<?php echo esc_attr( $position_key ); ?>" <?php selected( $position_key, $wc_single_pos ); ?>><?php echo esc_html( $position_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer position for single product page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<!-- End - WooCommerce Single Page Settings -->

				<!-- Start - WooCommerce Shop Page Settings -->
				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Shop Page Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-wc-shop-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="wpcdt_pro_options[wc_shop]" id="wpcdt-wc-shop-enable" class="wpcdt-checkbox wpcdt-wc-shop-enable" value="1" <?php checked( $wc_shop, 1 ); ?> /><br />
						<span class="description"><?php esc_html_e('Check this box to enable countdown timer for shop page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-wc-shop-timer-pos"><?php esc_html_e('Timer Position', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="wpcdt_pro_options[wc_shop_pos]" class="wpcdt-select wpcdt-wc-shop-timer-pos" id="wpcdt-wc-shop-timer-pos">
							<?php if( ! empty( $timer_position_data ) ) {
								foreach( $timer_position_data as $position_key => $position_val ) { ?>
									<option value="<?php echo esc_attr( $position_key ); ?>" <?php selected($position_key, $wc_shop_pos); ?>><?php echo esc_html( $position_val ); ?></option>
								<?php }
							} ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer position on shop page.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<!-- End - WooCommerce Shop Page Settings -->

				<tr>
					<td colspan="2">
						<input type="submit" name="wpcdt_pro_sett_submit" class="button button-primary right wpcdt-wc-sett-submit" value="<?php esc_html_e('Save Changes', 'countdown-timer-ultimate'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->