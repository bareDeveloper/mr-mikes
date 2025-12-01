<?php
/**
 * Handles Countdown Timer Setting metabox HTML
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Get timezone from WP settings
$current_offset	= get_option('gmt_offset');
$curr_timezone	= get_option('timezone_string');

// Remove old Etc mappings. Fallback to gmt_offset.
if ( false !== strpos( $curr_timezone,'Etc/GMT' ) ) {
	$curr_timezone = '';
}

// Create a UTC+- zone if no timezone string exists
if ( empty( $curr_timezone ) ) {
	if ( 0 == $current_offset ) {
		$curr_timezone = 'UTC+0';
	} elseif( $current_offset < 0 ) {
		$curr_timezone = 'UTC' . $current_offset;
	} else {
		$curr_timezone = 'UTC+' . $current_offset;
	}
}

// Taking some variable
$prefix				= WPCDT_PRO_META_PREFIX; // Metabox prefix
$design_arr			= wpcdt_pro_designs();
$recuring_type_arr	= wpcdt_pro_recuring_opts();
$days_arr			= wpcdt_pro_week_days();

$timer_type			= get_post_meta( $post->ID, $prefix.'timer_type', true );
$timer_mode			= get_post_meta( $post->ID, $prefix.'timer_mode', true );
$start_date			= get_post_meta( $post->ID, $prefix.'start_date', true );
$timer_date			= get_post_meta( $post->ID, $prefix.'timer_date', true );
$content_data		= get_post_meta( $post->ID, $prefix.'content', true );
$recurring_data		= get_post_meta( $post->ID, $prefix.'recurring', true );
$design_style		= get_post_meta( $post->ID, $prefix.'design_style', true );
$design_style		= ! empty( $design_style )	? $design_style : 'circle';
$timer_type			= ! empty( $timer_type )	? $timer_type	: 'content';
$timer_mode			= ( ! empty( $recurring_data['time'] ) && empty( $timer_mode ) ) ? 'evergreen' : $timer_mode;

// Get Recurring Data
$recur_mode		= isset( $recurring_data['recur_mode'] )	? $recurring_data['recur_mode']	: 'daily';
$start_time		= isset( $recurring_data['start_time'] )	? $recurring_data['start_time']	: '';
$end_time		= isset( $recurring_data['end_time'] )		? $recurring_data['end_time']	: '';
$recur_on		= isset( $recurring_data['recur_on'] )		? $recurring_data['recur_on']	: array();

$recuring_time	= isset( $recurring_data['time'] )			? $recurring_data['time']		: '';
$recuring_type	= isset( $recurring_data['type'] )			? $recurring_data['type']		: '';
$week_start		= isset( $recurring_data['week_start'] )	? $recurring_data['week_start']	: 1;
$week_end		= isset( $recurring_data['week_end'] )		? $recurring_data['week_end']	: 0;

$selected_tab		= ! empty( $content_data['tab'] )				? $content_data['tab']					: '';
$timer_day_text		= ! empty( $content_data['timer_day_text'] )	? $content_data['timer_day_text']		: esc_html__('Days', 'countdown-timer-ultimate');
$timer_hour_text	= ! empty( $content_data['timer_hour_text'] )	? $content_data['timer_hour_text']		: esc_html__('Hours', 'countdown-timer-ultimate');
$timer_minute_text	= ! empty( $content_data['timer_minute_text'] ) ? $content_data['timer_minute_text']	: esc_html__('Minutes', 'countdown-timer-ultimate');
$timer_second_text	= ! empty( $content_data['timer_second_text'] ) ? $content_data['timer_second_text']	: esc_html__('Seconds', 'countdown-timer-ultimate');
$is_timerdays		= ! empty( $content_data['is_timerdays'] )		? 1 : 0;
$is_timerhours		= ! empty( $content_data['is_timerhours'] )		? 1 : 0;
$is_timerminutes	= ! empty( $content_data['is_timerminutes'] )	? 1 : 0;
$is_timerseconds	= ! empty( $content_data['is_timerseconds'] )	? 1 : 0;

$desing_data		= get_post_meta( $post->ID, $prefix.'design', true );
$background_pref	= ! empty( $desing_data['background_pref'] )	? $desing_data['background_pref']	: '';
$font_clr			= ! empty( $desing_data['font_clr'] )			? $desing_data['font_clr']			: '';
$timertext_color	= ! empty( $desing_data['timertext_color'] )	? $desing_data['timertext_color']	: '#a8a8a8';
$timerdigit_color	= ! empty( $desing_data['timerdigit_color'] )	? $desing_data['timerdigit_color']	: '#000000';
?>

<div class="wpcdt-sett-wrap wpcdt-clearfix">
	<div class="wpcdt-meta-sett">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<td colspan="2" class="wpcdt-no-padding">
						<div class="wpcdt-info">
							<?php echo sprintf( __('Countdown Timer Ultimate Pro works with WordPress timezone which you had set from <a href="%s" target="_blank">General Setting</a> page.', 'countdown-timer-ultimate'), admin_url('options-general.php') ); ?> <br/>
							<?php echo __('Your Current timezone is', 'countdown-timer-ultimate') .' : '. $curr_timezone; ?>
						</div>
					</td>
				</tr>

				<tr>
					<th>
						<label for="wpcdt-start-date"><?php esc_html_e('Start Date & Time', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>start_date" value="<?php echo esc_attr( $start_date ); ?>" class="wpcdt-datetime wpcdt-start-date" id="wpcdt-start-date" /><br/>
						<span class="description"><?php esc_html_e('Set countdown timer start date.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-end-date"><?php esc_html_e('Expiry Date & Time', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>timer_date" value="<?php echo esc_attr( $timer_date ); ?>" class="wpcdt-datetime wpcdt-end-date" id="wpcdt-end-date" /><br/>
						<span class="description"><?php esc_html_e('Set countdown timer expiry date.', 'countdown-timer-ultimate'); ?></span><br/>
						<span class="description"><?php esc_html_e('Note: Expiry Date & Time is compulsory. Set long future date for long running timer.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-timer-type"><?php esc_html_e('Timer Type', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $prefix ); ?>timer_type" class="wpcdt-select wpcdt-show-hide wpcdt-timer-type" id="wpcdt-timer-type" data-prefix="type">
							<option value="content" <?php selected( $timer_type, 'content' ); ?>><?php esc_html_e('Content Timer', 'countdown-timer-ultimate'); ?></option>
							<option value="simple" <?php selected( $timer_type, 'simple' ); ?>><?php esc_html_e('Simple Timer (Only Timer)', 'countdown-timer-ultimate'); ?></option>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer type.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-timer-mode"><?php esc_html_e('Timer Mode', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $prefix ); ?>timer_mode" id="wpcdt-timer-mode" class="wpcdt-select wpcdt-timer-mode wpcdt-show-hide" data-prefix="mode">
							<option value="default"><?php esc_html_e('Default', 'countdown-timer-ultimate'); ?></option>
							<option value="evergreen" <?php selected( $timer_mode, 'evergreen' ); ?>><?php esc_html_e('Evergreen Timer', 'countdown-timer-ultimate'); ?></option>
							<option value="recurring" <?php selected( $timer_mode, 'recurring' ); ?>><?php esc_html_e('Recurring Timer', 'countdown-timer-ultimate'); ?></option>
						</select><br/>
						<span class="description"><?php esc_html_e('Select countdown timer mode.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>

				<!-- Start - Evergreen Timer Settings -->
				<tr class="wpcdt-show-hide-row-mode wpcdt-show-if-mode-evergreen" style="<?php if( $timer_mode != 'evergreen' ) { echo 'display: none;'; } ?>">
					<td colspan="2" class="wpcdt-no-padding">
						<table class="form-table wpcdt-tbl">
							<tr>
								<th colspan="2">
									<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Evergreen Timer Settings', 'countdown-timer-ultimate'); ?></div>
								</th>
							</tr>

							<tr>
								<th>
									<label for="wpcdt-recurring-time"><?php esc_html_e('Recurring Time', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<input type="text" name="<?php echo esc_attr( $prefix ); ?>recurring[time]" value="<?php echo esc_attr( $recuring_time ); ?>" class="wpcdt-recurring-time" id="wpcdt-recurring-time" />
									<select name="<?php echo esc_attr( $prefix ); ?>recurring[type]" class="wpcdt-select wpcdt-recurring-type">
										<?php if( ! empty( $recuring_type_arr ) ) {
											foreach( $recuring_type_arr as $recuring_key => $recuring_value ) { ?>
												<option value="<?php echo esc_attr( $recuring_key ); ?>" <?php selected( $recuring_type, $recuring_key ); ?>><?php echo esc_html( $recuring_value ); ?></option>
											<?php }
										} ?>
									</select><br/>
									<span class="description"><?php esc_html_e('Set countdown timer recurring time.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- End - Evergreen Timer Settings -->

				<!-- Start - Recurring Timer Settings -->
				<tr class="wpcdt-show-hide-row-mode wpcdt-show-if-mode-recurring" style="<?php if( $timer_mode != 'recurring' ) { echo 'display: none;'; } ?>">
					<td colspan="2" class="wpcdt-no-padding">
						<table class="form-table wpcdt-tbl">
							<tr>
								<th colspan="2">
									<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Recurring Timer Settings', 'countdown-timer-ultimate'); ?></div>
								</th>
							</tr>

							<tr>
								<th>
									<label for="wpcdt-recurring-mode"><?php esc_html_e('Recurring Mode', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<select name="<?php echo esc_attr( $prefix ); ?>recurring[recur_mode]" id="wpcdt-recurring-mode" class="wpcdt-select wpcdt-recurring-mode wpcdt-show-hide" data-prefix="rmode">
										<option value="daily" <?php selected( $recur_mode, 'daily' ); ?>><?php esc_html_e('Daily', 'countdown-timer-ultimate'); ?></option>
										<option value="weekly" <?php selected( $recur_mode, 'weekly' ); ?>><?php esc_html_e('Weekly', 'countdown-timer-ultimate'); ?></option>
										<option value="custom" <?php selected( $recur_mode, 'custom' ); ?>><?php esc_html_e('Custom', 'countdown-timer-ultimate'); ?></option>
									</select><br/>
									<span class="description"><?php esc_html_e('Select recurring countdown timer mode.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
							<tr class="wpcdt-show-hide-row-rmode wpcdt-show-if-rmode-weekly" style="<?php if( $recur_mode != 'weekly' ) { echo 'display: none;'; } ?>">
								<th>
									<label for="wpcdt-week-start"><?php esc_html_e('Week Start On', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<select name="<?php echo esc_attr( $prefix ); ?>recurring[week_start]" id="wpcdt-week-start" class="wpcdt-select wpcdt-week-start">
										<?php foreach( $days_arr as $day_key => $day_val ) { ?>
											<option value="<?php echo esc_attr( $day_key ); ?>" <?php selected( $week_start, $day_key ); ?>><?php echo esc_html( $day_val ); ?></option>
										<?php } ?>
									</select><br/>
									<span class="description"><?php esc_html_e('Select week start day.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
							<tr class="wpcdt-show-hide-row-rmode wpcdt-show-if-rmode-weekly" style="<?php if( $recur_mode != 'weekly' ) { echo 'display: none;'; } ?>">
								<th>
									<label for="wpcdt-week-end"><?php esc_html_e('Week End On', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<select name="<?php echo esc_attr( $prefix ); ?>recurring[week_end]" id="wpcdt-week-end" class="wpcdt-select wpcdt-week-end">
										<?php foreach( $days_arr as $day_key => $day_val ) { ?>
											<option value="<?php echo esc_attr( $day_key ); ?>" <?php selected( $week_end, $day_key ); ?>><?php echo esc_html( $day_val ); ?></option>
										<?php } ?>
									</select><br/>
									<span class="description"><?php esc_html_e('Select week end day.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
							<tr class="wpcdt-show-hide-row-rmode wpcdt-show-if-rmode-custom wpcdt-hide" style="<?php if( $recur_mode == 'custom' ) { echo 'display: table-row;'; } ?>">
								<th>
									<label for="wpcdt-recurring-on"><?php esc_html_e('Recurring Days', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<select name="<?php echo esc_attr( $prefix ); ?>recurring[recur_on][]" id="wpcdt-recurring-on" class="wpcdt-select wpcdt-recurring-on" multiple="multiple">
										<?php foreach( $days_arr as $day_key => $day_val ) { ?>
											<option value="<?php echo esc_attr( $day_key ); ?>" <?php echo in_array( $day_key, $recur_on ) ? 'selected' : ''; ?>><?php echo esc_html( $day_val ); ?></option>
										<?php } ?>
									</select><br/>
									<span class="description"><?php esc_html_e('Select recurring days. Hold Ctrl key to select multiple days at a time.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="wpcdt-recur-start-time"><?php esc_html_e('Start Time', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<input type="text" name="<?php echo esc_attr( $prefix ); ?>recurring[start_time]" value="<?php echo esc_attr( $start_time ); ?>" id="wpcdt-recur-start-time" class="wpcdt-text wpcdt-time wpcdt-recur-start-time" /><br/>
									<span class="description"><?php esc_html_e('Choose recurring timer start time.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="wpcdt-recur-end-time"><?php esc_html_e('End Time', 'countdown-timer-ultimate'); ?></label>
								</th>
								<td>
									<input type="text" name="<?php echo esc_attr( $prefix ); ?>recurring[end_time]" value="<?php echo esc_attr( $end_time ); ?>" id="wpcdt-recur-end-time" class="wpcdt-text wpcdt-time wpcdt-recur-end-time" /><br/>
									<span class="description"><?php esc_html_e('Choose recurring timer end time.', 'countdown-timer-ultimate'); ?></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- End - Recurring Timer Settings -->

				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('General Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>

				<tr>
					<th>
						<label for="wpcdt-timer-opt"><?php esc_html_e('Timer Label', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td class="wpcdt-no-padding">
						<table class="form-table">
							<tbody>
								<tr>
									<td>
										<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>content[is_timerdays]" value="1" <?php checked( $is_timerdays, 1 ); ?> class="wpcdt-checkbox wpcdt-days" id="wpcdt-days" />
										<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[timer_day_text]" value="<?php echo esc_attr( $timer_day_text ); ?>" class="wpcdt-day-txt" id="wpcdt-day-txt" /><br/>
										<span class="description"><?php esc_html_e('Check this box if you want to enable days and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
									<td>
										<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>content[is_timerhours]" value="1" <?php checked( $is_timerhours, 1 ); ?> class="wpcdt-checkbox wpcdt-hours" id="wpcdt-hours" />
										<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[timer_hour_text]" value="<?php echo esc_attr( $timer_hour_text ); ?>" class="wpcdt-hour-txt" id="wpcdt-hour-txt" /><br/>
										<span class="description"><?php esc_html_e('Check this box if you want to enable hours and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
								<tr>
									<td>
										<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>content[is_timerminutes]" value="1" <?php checked( $is_timerminutes, 1 ); ?> class="wpcdt-checkbox wpcdt-minutes" id="wpcdt-minutes" />
										<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[timer_minute_text]" value="<?php echo esc_attr( $timer_minute_text ); ?>" class="wpcdt-minute-txt" id="wpcdt-minute-txt" /><br/>
										<span class="description"><?php esc_html_e('Check this box if you want to enable minutes and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
									<td>
										<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>content[is_timerseconds]" value="1" <?php checked( $is_timerseconds, 1 ); ?> class="wpcdt-checkbox wpcdt-seconds" id="wpcdt-seconds" />
										<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[timer_second_text]" value="<?php echo esc_attr( $timer_second_text ); ?>" class="wpcdt-second-txt" id="wpcdt-second-txt" /><br/>
										<span class="description"><?php esc_html_e('Check this box if you want to enable seconds and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>

				<tr>
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Design Common Settings', 'countdown-timer-ultimate'); ?></div>
					</th>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-bgclr"><?php esc_html_e('Timer Background Color', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[background_pref]" value="<?php echo esc_attr( $background_pref ); ?>" class="wpcdt-colorpicker wpcdt-bgclr" id="wpcdt-bgclr" data-alpha="true" /><br/>
						<span class="description"><?php esc_html_e('Choose timer background color.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-lblclr"><?php esc_html_e('Timer Label Color', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[timertext_color]" value="<?php echo esc_attr( $timertext_color ); ?>" class="wpcdt-colorpicker wpcdt-lblclr" id="wpcdt-lblclr" /><br/>
						<span class="description"><?php esc_html_e('Choose timer label color.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-digitclr"><?php esc_html_e('Timer Digit Color', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[timerdigit_color]" value="<?php echo esc_attr( $timerdigit_color ); ?>" class="wpcdt-colorpicker wpcdt-digitclr" id="wpcdt-digitclr" /><br/>
						<span class="description"><?php esc_html_e('Choose timer digit color.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-fontclr"><?php esc_html_e('Timer Content Color', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[font_clr]" value="<?php echo esc_attr( $font_clr ); ?>" class="wpcdt-colorpicker wpcdt-fontclr" id="wpcdt-fontclr" /><br/>
						<span class="description"><?php esc_html_e('Choose timer content color.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Tabs - Start -->
	<div class="wpcdt-vtab-wrap wpcdt-show-hide-row-type wpcdt-show-if-type-content" style="<?php if( $timer_type != 'content' ) { echo 'display: none;'; } ?>">
		<ul class="wpcdt-vtab-nav-wrap">
			<li class="wpcdt-vtab-nav">
				<a href="#wpcdt_content_sett"><i class="dashicons dashicons-text-page" aria-hidden="true"></i> <?php esc_html_e('Content', 'countdown-timer-ultimate'); ?></a>
			</li>

			<li class="wpcdt-vtab-nav">
				<a href="#wpcdt_design_sett"><i class="dashicons dashicons-admin-customizer" aria-hidden="true"></i> <?php esc_html_e('Design', 'countdown-timer-ultimate'); ?></a>
			</li>
		</ul>

		<div class="wpcdt-vtab-cnt-wrp">
			<?php
				// Content Settings
				include_once( WPCDT_PRO_DIR . '/includes/admin/metabox/content-metabox.php' );

				// Design Settings
				include_once( WPCDT_PRO_DIR . '/includes/admin/metabox/design-metabox.php' );
			?>
		</div>
		<input type="hidden" value="<?php echo esc_attr( $selected_tab ); ?>" class="wpcdt-selected-tab" name="<?php echo esc_attr( $prefix ); ?>content[tab]" />
	</div>
	<!-- Tabs - End -->
</div><!-- end .wpcdt-sett-wrap -->