<?php
/**
 * Handles EDD(Easy Digital Download) Setting metabox HTML
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Taking some data
$prefix				= WPCDT_PRO_META_PREFIX;
$timer_type_data	= wpcdt_pro_timer_type_opts();
$enable_opts		= wpcdt_pro_timer_show_hide();
$edd_enable			= get_post_meta( $post->ID, $prefix.'edd_enable', true );
$edd_timer_type		= get_post_meta( $post->ID, $prefix.'edd_timer_type', true );
$edd_timer_id		= get_post_meta( $post->ID, $prefix.'edd_timer_id', true );
$edd_timer_shrt		= get_post_meta( $post->ID, $prefix.'edd_timer_shrt', true );
$edd_timer_type		= ! empty( $edd_timer_type ) ? $edd_timer_type : 'timer';
$timer_post_data	= wpcdt_pro_get_timer_posts( $edd_timer_id );
?>

<table class="form-table wpcdt-edd-sett-tbl wpcdt-tbl">
	<tbody>
		<tr>
			<th>
				<label for="wpcdt-edd-enable"><?php esc_html_e( 'Enable', 'countdown-timer-ultimate' ); ?></label>
			</th>
			<td>
				<select name="<?php echo esc_attr( $prefix ); ?>edd_enable" class="wpcdt-select wpcdt-edd-enable" id="wpcdt-edd-enable">
					<?php if( ! empty( $enable_opts ) ) {
						foreach( $enable_opts as $enable_key => $enable_val ) { ?>
							<option value="<?php echo esc_attr( $enable_key ); ?>" <?php selected( $enable_key, $edd_enable ); ?>><?php echo esc_html( $enable_val ); ?></option>
						<?php }
					} ?>
				</select><br/>
				<span class="description"><?php esc_html_e('Enable / Disable countdown timer or choose from global setting.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="wpcdt-edd-timer-type"><?php esc_html_e('Timer Type', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<select name="<?php echo esc_attr( $prefix ); ?>edd_timer_type" class="wpcdt-select wpcdt-show-hide wpcdt-edd-timer-type" id="wpcdt-edd-timer-type" data-prefix="edd">
					<?php if( ! empty( $timer_type_data ) ) {
						foreach ($timer_type_data as $type_key => $type_val) { ?>
							<option value="<?php echo esc_attr( $type_key ); ?>" <?php selected($type_key, $edd_timer_type); ?>><?php echo esc_html( $type_val ); ?></option>
						<?php }
					} ?>
				</select><br/>
				<span class="description"><?php esc_html_e('Select countdown timer type.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr class="wpcdt-show-hide-row-edd wpcdt-show-if-edd-timer" style="<?php if( $edd_timer_type != 'timer' ) { echo 'display: none;'; } ?>">
			<th>
				<label for="wpcdt-edd-timer"><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<select name="<?php echo esc_attr( $prefix ); ?>edd_timer_id" class="wpcdt-select wpcdt-edd-timer" id="wpcdt-edd-timer">
					<?php if( ! empty( $timer_post_data ) ) { ?>
						<option value=""><?php esc_html_e('Select Timer', 'countdown-timer-ultimate'); ?></option>
						<?php echo $timer_post_data; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
					<?php } ?>
				</select><br/>
				<span class="description"><?php esc_html_e('Select countdown timer. Note : `Simple Timer Type` countdown is the best option to choose because of compact layout.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr class="wpcdt-show-hide-row-edd wpcdt-show-if-edd-shortcode" style="<?php if( $edd_timer_type != 'shortcode' ) { echo 'display: none;'; } ?>">
			<th>
				<label for="wpcdt-timer-shrt"><?php esc_html_e('Timer Shortcode', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<textarea name="<?php echo esc_attr( $prefix ); ?>edd_timer_shrt" class="large-text wpcdt-timer-shrt" id="wpcdt-timer-shrt" rows="2" cols="20"><?php echo esc_textarea( $edd_timer_shrt ); ?></textarea><br/>
				<span class="description"><?php esc_html_e('Enter countdown timer shortcode for all the products. e.g.', 'countdown-timer-ultimate'); ?></span><br/>
				<span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt_timer timer_id="123" start_date="2022-01-24 23:59:59" end_date="2022-01-26 23:59:59"]</span><br/>
				<span class="description"><?php echo sprintf( __('Please take a look at the plugin %sdocumentation%s for all parameters.', 'countdown-timer-ultimate'), '<a href="https://docs.essentialplugin.com/countdown-timer-ultimate-pro/" target="_blank">', '</a>' ); ?></span>
			</td>
		</tr>
	</tbody>
</table>