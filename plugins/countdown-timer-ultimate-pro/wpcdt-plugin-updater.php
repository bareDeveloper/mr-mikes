<?php
/**
 * Updater Functions
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpcdt_pro_license_info;

// License page URL
if( ! defined( 'WPCDT_PRO_LICENSE_URL' ) ) {
	define( 'WPCDT_PRO_LICENSE_URL', add_query_arg(array( 'post_type' => WPCDT_PRO_POST_TYPE, 'page' => 'wpcdt-license'), admin_url('edit.php')) );
}

// Taking some data
$current_date	= current_time( 'mysql' );
$license 		= get_option( 'wpcdt_pro_plugin_license_key' );
$license_info	= get_option( 'edd_wpcdt_license_info' );

if( isset( $license_info->expires ) && $license_info->expires != 'lifetime' && $current_date > $license_info->expires ) {

	$renew_link		= add_query_arg( array('edd_license_key' => $license, 'download_id' => $license_info->payment_id), 'https://www.wponlinesupport.com/checkout/' );
	$license_msg	= sprintf(
							__( 'Your license key expired on %s. Kindly <a href="%s" target="_blank">renew</a> it for further updates and support from your <a href="%s" target="_blank">account page</a>.', 'countdown-timer-ultimate' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_info->expires, current_time( 'timestamp' ) ) ),
							$renew_link,
							'https://www.wponlinesupport.com/my-account/?tab=license-keys'
					);

	$license_info->license_status	= 'expired';
	$license_info->license_msg		= $license_msg;

} else if( isset( $license_info->license ) && $license_info->license == 'valid' && ! isset( $license_info->license_msg )  ) {
	
	$license_info->license_status = $license_info->license;
	$license_info->license_msg = __( 'License is activated successfully.', 'countdown-timer-ultimate' );

} else if( isset( $license_info->license ) ) {
	
	$license_info->license_status = $license_info->license;
}

$wpcdt_pro_license_info = $license_info; // Assign to global variable

/**
 * Updater Menu Function
 * 
 * @since 1.0
 */
function wpcdt_pro_plugin_license_menu() {

	global $wpcdt_pro_license_info;

	// Getting license status to show notification
	$license_info 	= $wpcdt_pro_license_info;
	$status 		= ! empty( $license_info->license_status ) ? $license_info->license_status : false;
	$notification 	= ( $status !== 'valid' ) ? ' <span class="update-plugins count-1"><span class="plugin-count" aria-hidden="true">1</span></span>' : '';

	add_submenu_page( 'edit.php?post_type='.WPCDT_PRO_POST_TYPE, __('Countdown Timer Ultimate Pro - Plugin License', 'countdown-timer-ultimate'), __('Plugin License', 'countdown-timer-ultimate').$notification, 'manage_options', 'wpcdt-license', 'wpcdt_pro_plugin_license_page' );
}
add_action('admin_menu', 'wpcdt_pro_plugin_license_menu', 30);

/**
 * Plugin license form HTML
 * 
 * @since 1.0.0
 */
function wpcdt_pro_plugin_license_page() {

	global $wpcdt_pro_license_info;

	$license_info 	= $wpcdt_pro_license_info;
	$license 		= get_option( 'wpcdt_pro_plugin_license_key' );
	$status 		= ! empty( $license_info->license_status )	? $license_info->license_status : false;
	$payment_id		= ! empty( $license_info->payment_id )		? $license_info->payment_id		: false;
?>

	<div class="wrap">
		<h2><?php esc_html_e('Countdown Timer Ultimate Pro - License Options', 'countdown-timer-ultimate'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('edd_wpcdt_license'); ?>

			<?php if( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) { ?>
				<div class="updated notice is-dismissible" id="message">
					<p><?php esc_html_e('Your changes saved successfully.', 'countdown-timer-ultimate'); ?></p>
				</div>
			<?php } elseif ( isset( $_GET['sl_activation'] ) && 'false' == $_GET['sl_activation'] && ! empty( $_GET['message'] ) ) { ?>
				<div class="error" id="message">
					<p><?php echo urldecode( $_GET['message'] ); ?></p>
				</div>
			<?php }

			if( $status !== false && $status == 'valid' ) { ?>
				<div class="updated notice notice-success" id="message">
					<p><?php esc_html_e('Plugin license is active.', 'countdown-timer-ultimate'); ?></p>
				</div>
			<?php } elseif( ! isset( $_GET['sl_activation'] ) ) { ?>
				<div class="error notice notice-error" id="message">
					<p><?php esc_html_e('Please activate plugin license to get automatic update of plugin.', 'countdown-timer-ultimate'); ?></p>
				</div>
			<?php } ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<label for="wpcdt-pro-license-key"><?php esc_html_e('License Key', 'countdown-timer-ultimate'); ?></label>
						</th>
						<td>
							<input name="wpcdt_pro_plugin_license_key" id="wpcdt-pro-license-key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" /><br/>
							<span class="description"><?php esc_html_e('Enter plugin license key.', 'countdown-timer-ultimate'); ?></span>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php esc_html_e('Activate License', 'countdown-timer-ultimate'); ?>
							</th>
							<td>
								<?php wp_nonce_field( 'wpcdt_pro_license_nonce', 'wpcdt_pro_license_nonce' );

								if( $status !== false && $status == 'valid' ) { ?>	
									<input type="submit" class="button-secondary" name="wpcdt_pro_license_deactivate" value="<?php esc_html_e('Deactivate License', 'countdown-timer-ultimate'); ?>"/>
									<span style="color: green; display: inline-block; margin: 4px 0px 0px;"><i class="dashicons dashicons-yes"></i><?php esc_html_e('Active', 'countdown-timer-ultimate'); ?></span>
								<?php } else { ?>
									<input type="submit" class="button button-secondary" name="wpcdt_pro_license_activate" value="<?php esc_html_e('Activate License', 'countdown-timer-ultimate'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php }

					if( $license && $license_info ) { ?>
						<tr>
							<th valign="top"><?php esc_html_e('License Information', 'countdown-timer-ultimate'); ?></th>
							<?php if( $status == 'valid' ) { ?>
							<td style="font-weight: 600; line-height: 25px;">
								<p style="color:green;"><?php echo wp_kses_post( $license_info->license_msg ); ?></p>

								<?php
								if( ! defined( 'WPOS_HIDE_LICENSE' ) || ( defined( 'WPOS_HIDE_LICENSE' ) && WPOS_HIDE_LICENSE != 'info' ) ) {

									echo esc_html__('License Limit' , 'countdown-timer-ultimate') ." : ". ( (isset($license_info->license_limit) && $license_info->license_limit == 0) ? __('Unlimited', 'countdown-timer-ultimate') : $license_info->license_limit ) ." ". esc_html__('Sites', 'countdown-timer-ultimate') . '<br/>';
									echo esc_html__('Active Site(s)' , 'countdown-timer-ultimate') ." : ". ( isset($license_info->site_count) ? $license_info->site_count : 'N/A' ) . '<br/>';
									echo esc_html__('Activation Left Site(s)' , 'countdown-timer-ultimate') ." : ". ( isset($license_info->activations_left) ? ucfirst($license_info->activations_left) : 'N/A' ) . '<br/>';
									
									if( isset( $license_info->expires ) && $license_info->expires == 'lifetime' ) {
										echo esc_html__('Valid Upto' , 'countdown-timer-ultimate') ." : ". esc_html__( 'Lifetime', 'countdown-timer-ultimate' ) . '<br/>';
									} else {
										echo esc_html__('Valid Upto' , 'countdown-timer-ultimate') ." : ". date('d M, Y', strtotime($license_info->expires)) . ' <label style="vertical-align:top;" title="'.esc_attr__('On purchase of any product 1 Year of Updates, 1 Year of Expert Support. After 1 Year, use without renewal OR renew manually at discounted price for further updates and support.', 'countdown-timer-ultimate').'">[?]</label> <br/>';
									}

									echo esc_html__('Purchase ID' , 'countdown-timer-ultimate') ." : #". $license_info->payment_id;
								}
								?>
							</td>
							<?php } else if( $status != 'valid' && isset( $license_info->license_msg ) ) { ?>
							<td style="font-weight: 600;">
								<p style="color:#dc3232;"><?php echo wp_kses_post( $license_info->license_msg ); ?></p>
							</td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

			<div class="wpo-activate-step">
				<hr/>
				<h2><?php esc_html_e('Steps to Activate the License', 'countdown-timer-ultimate'); ?></h2>
				<ol>
					<li><?php esc_html_e("Enter your license key into 'License Key' field and press 'Save Changes' button.", 'countdown-timer-ultimate'); ?></li>
					<li><?php esc_html_e("After save changes you can see an another button named 'Activate License'.", 'countdown-timer-ultimate'); ?></li>
					<li><?php esc_html_e("Press 'Activate License'. If your key is valid then you can see green 'Active' text.", 'countdown-timer-ultimate'); ?></li>
					<li><?php esc_html_e("That's it. Now you can get auto update of this plugin.", 'countdown-timer-ultimate'); ?></li>
				</ol>
				<h4 style="color:#dc3232;"><?php esc_html_e('Note: If you do not activate the license then you will not get automatic update of this plugin any more.', 'countdown-timer-ultimate'); ?></h4>
				<h4><?php esc_html_e('You will receive license key within your product purchase email. If you do not have license key then you can get it from your', 'countdown-timer-ultimate'); ?> <a href="https://www.wponlinesupport.com/my-account/" target="_blank"><?php esc_html_e('account page', 'countdown-timer-ultimate'); ?></a>.</h4>
				<h4><?php esc_html_e('Note : If your license key has expired, please renew your license from', 'countdown-timer-ultimate'); ?> <a href="https://www.wponlinesupport.com/my-account/" target="_blank"><?php esc_html_e('Account page', 'countdown-timer-ultimate'); ?></a>.</h4>
			</div><!-- end .wpo-activate-step -->
		</form>
	</div><!-- end .wrap -->
<?php
}

/**
 * Validate plugin license options
 * 
 * @since 1.0
 */
function wpcdt_pro_sanitize_license( $new ) {

	$old = get_option( 'wpcdt_pro_plugin_license_key' );

	if( $old && $old != $new ) {
		update_option( 'edd_wpcdt_license_info', '' ); // new license has been entered, so must reactivate
	}
	return $new;
}

/**
 * Register plugin license settings
 * 
 * @since 1.0.0
 */
function wpcdt_pro_process_plugin_license() {

	// Register plugin license settings
	register_setting('edd_wpcdt_license', 'wpcdt_pro_plugin_license_key', 'wpcdt_pro_sanitize_license' );

	/***** Activate Plugin License *****/
	if( isset( $_POST['wpcdt_pro_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wpcdt_pro_license_nonce', 'wpcdt_pro_license_nonce' ) ) {
			return; // get out if we didn't click the Activate button
	 	}

		// retrieve the license from the database
		$license_msg	= sprintf( __('Sorry, Something happened wrong. Please contact %ssite administrator%s.', 'countdown-timer-ultimate'), '<a href="'.esc_url( EDD_WPCDT_PRO_STORE_URL ).'">', '</a>' );
		$license		= trim( get_option( 'wpcdt_pro_plugin_license_key' ) );
		$post_license	= isset( $_POST['wpcdt_pro_plugin_license_key'] ) ? trim( $_POST['wpcdt_pro_plugin_license_key'] ) : '';

		// Update license key if user directly press active button
		if( $post_license != $license ) {
			update_option( 'wpcdt_pro_plugin_license_key', $post_license );
			$license = $post_license;
		}

		// data to send in our API request
		$api_params = array(
			'edd_action'	=> 'activate_license',
			'license' 		=> $license,
			'item_name' 	=> rawurlencode( EDD_WPCDT_PRO_ITEM_NAME ), // the name of our product in EDD
			'url'			=> home_url(),
			'environment'	=> function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post( EDD_WPCDT_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params, ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'countdown-timer-ultimate' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$renew_link	= add_query_arg( array('edd_license_key' => $license, 'download_id' => $license_data->payment_id), 'https://www.wponlinesupport.com/checkout/' );
						$message	= sprintf(
											__( 'Your license key expired on %s.', 'countdown-timer-ultimate' ),
											date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
										);

						$license_msg = $message;
						$license_msg .= '<br/>' . sprintf( __('Kindly %srenew%s it for further updates and support from your %saccount page%s.', 'countdown-timer-ultimate'), '<a href="'.esc_url( $renew_link ).'" target="_blank">', '</a>', '<a href="https://www.wponlinesupport.com/my-account/?tab=license-keys" target="_blank">', '</a>' );
						break;

					case 'revoked' :
					case 'disabled' :

						$message		= __( 'Your license key has been disabled.', 'countdown-timer-ultimate' );
						$license_msg	= sprintf( __('Your license key has been disabled. Please contact %ssite administrator%s.', 'countdown-timer-ultimate'), '<a href="'.esc_url( EDD_WPCDT_PRO_STORE_URL ).'" target="_blank">', '</a>' );
						break;

					case 'missing' :

						$message		= __( 'Plugin license is invalid. Please be sure you have entered right plugin license key.', 'countdown-timer-ultimate' );
						$license_msg	= $message;
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message		= __( 'Your license is not active for this URL.', 'countdown-timer-ultimate' );
						$license_msg	= $message;
						break;

					case 'item_name_mismatch' :

						$message		= sprintf( __( 'This appears to be an invalid license key for %s.', 'countdown-timer-ultimate' ), EDD_WPCDT_PRO_ITEM_NAME );
						$license_msg	= $message;
						break;

					case 'no_activations_left':

						$message		= __('Your license key has reached its activation limit.', 'countdown-timer-ultimate');
						$license_msg	= $message;
						$license_msg	.= '<br/>' . sprintf( __('You can manage this from your %saccount%s or Please contact %ssite administrator%s.', 'countdown-timer-ultimate'), '<a href="https://www.wponlinesupport.com/my-account/?tab=license-keys" target="_blank">', '</a>', '<a href="https://www.essentialplugin.com/" target="_blank">', '</a>' );
						break;

					default :

						$message		= __( 'An error occurred, please try again.', 'countdown-timer-ultimate' );
						$license_msg	= $message;
						break;
				}

			} else {
				$license_msg = __( 'License is activated successfully.', 'countdown-timer-ultimate' );
			}
		}

		// $license_data->license will be either "valid" or "invalid"
		if( isset( $license_data->license ) ) {
			$license_data->license_msg = $license_msg;
			update_option( 'edd_wpcdt_license_info', $license_data );
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => rawurlencode( $message ) ), WPCDT_PRO_LICENSE_URL );
			wp_safe_redirect( $redirect );
			exit();
		}

		wp_safe_redirect( WPCDT_PRO_LICENSE_URL );
		exit();
	}

	/***** Deactivate Plugin License *****/
	// listen for our activate button to be clicked
	if( isset( $_POST['wpcdt_pro_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wpcdt_pro_license_nonce', 'wpcdt_pro_license_nonce' ) ) {
			return; // get out if we didn't click the Activate button
	 	}

		// retrieve the license from the database
		$license = trim( get_option( 'wpcdt_pro_plugin_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'	=> 'deactivate_license',
			'license' 		=> $license,
			'item_name'		=> rawurlencode( EDD_WPCDT_PRO_ITEM_NAME ), // the name of our product in EDD
			'url'       	=> home_url(),
			'environment'	=> function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post( EDD_WPCDT_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'countdown-timer-ultimate' );
			}

			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => rawurlencode( $message ) ), WPCDT_PRO_LICENSE_URL );

			wp_safe_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {
			update_option( 'edd_wpcdt_license_info', '' );
		}

		wp_safe_redirect( WPCDT_PRO_LICENSE_URL );
		exit();
	}
}
add_action('admin_init', 'wpcdt_pro_process_plugin_license');

/**
 * Function to add license plugins link
 * 
 * @since 1.0
 */
function wpcdt_pro_plugin_action_links( $links ) {
	
	$links['license'] = '<a href="' . esc_url(WPCDT_PRO_LICENSE_URL) . '" title="' . esc_attr__( 'Activate Plugin License', 'countdown-timer-ultimate' ) . '">' . esc_html__( 'License', 'countdown-timer-ultimate' ) . '</a>';
	
	return $links;
}
add_filter( 'plugin_action_links_' . WPCDT_PRO_PLUGIN_BASENAME, 'wpcdt_pro_plugin_action_links' );

/**
 * Displays message inline on plugin row that the license key is missing
 *
 * @since 1.0
 */
function wpcdt_pro_plugin_row_license_missing( $plugin_data, $version_info ) {

	global $wpcdt_pro_license_info;

	$license_info 	= $wpcdt_pro_license_info;
	$license_status = ! empty( $license_info->license_status ) ? $license_info->license_status : false;

	if( ( empty( $license_info ) || $license_status !== 'valid' ) ) {
		echo '&nbsp;<strong><a href="' . esc_url( WPCDT_PRO_LICENSE_URL ) . '">' . esc_html__( 'Enter valid license key for automatic updates.', 'countdown-timer-ultimate' ) . '</a></strong>';
	}
}
add_action( 'in_plugin_update_message-' . WPCDT_PRO_PLUGIN_BASENAME, 'wpcdt_pro_plugin_row_license_missing', 10, 2 );

/**
 * Displays license expired message on plugin row
 *
 * @since 1.0
 */
function wpcdt_pro_plugin_license_msg( $file, $plugin_data ) {

	global $wpcdt_pro_license_info;

	$license 		= get_option( 'wpcdt_pro_plugin_license_key' );
	$plugin_slug    = isset( $plugin_data['slug'] )							? $plugin_data['slug']						: sanitize_title( $plugin_data['Name'] );
	$license_status = ! empty( $wpcdt_pro_license_info->license_status ) 	? $wpcdt_pro_license_info->license_status	: false;
	$license_msg	= ! empty( $wpcdt_pro_license_info->license_msg )		? $wpcdt_pro_license_info->license_msg		: '';

	if( ! isset( $plugin_data['update'] ) ) {

		// Little tweak to merge notice in same row
		$script = '<script type="text/javascript"> 
						jQuery("#'.esc_attr( $plugin_slug ).'-update").prev("tr").addClass("update");
					</script>';

		if( empty( $license ) || ( ! empty( $license ) && $license_status != 'expired' && $license_status != 'valid' ) ) {

			echo '<tr id="'.esc_attr( $plugin_slug ).'-update" class="plugin-update-tr active" data-slug="'. esc_attr( $plugin_slug ) .'" data-plugin="'.esc_attr( $file ).'">
					<td class="plugin-update colspanchange" colspan="4">
						<div class="update-message notice inline notice-error notice-alt"><p>'. sprintf( __('%sRegister%s your copy of Countdown Timer Ultimate Pro to receive access to automatic upgrades and support. Need a license key? %sPurchase one now%s.', 'countdown-timer-ultimate'), '<a href="'.esc_url( WPCDT_PRO_LICENSE_URL ).'">', '</a>', '<a href="https://www.essentialplugin.com/wordpress-plugin/countdown-timer-ultimate/?utm_source=countdown_timer_pro&utm_medium=WP&utm_campaign=Plugin-List" target="_blank">', '</a>' ) .'</a></p></div>
						'.$script.'
					</td>
				</tr>';

		} else if( $license_status == 'expired' && $license_msg ) {

			echo '<tr id="'.esc_attr( $plugin_slug ).'-update" class="plugin-update-tr active" data-slug="'. esc_attr( $plugin_slug ) .'" data-plugin="'.esc_attr( $file ).'">
					<td class="plugin-update colspanchange" colspan="4">
						<div class="update-message notice inline notice-error notice-alt"><p>'. wp_kses_post( $license_msg ) .'</p></div>
						'.$script.'
					</td>
				</tr>';
		}
	}
}
add_action( 'after_plugin_row_' . WPCDT_PRO_PLUGIN_BASENAME, 'wpcdt_pro_plugin_license_msg', 10, 2 );

/**
 * Function to add license expired notice
 * 
 * @since 1.0
 */
function wpcdt_pro_plugin_license_notice() {

	global $typenow, $wpcdt_pro_license_info;

	// If not plugin screen then return
	if( $typenow != WPCDT_PRO_POST_TYPE ) {
		return false;
	}

	$notice_transient = get_transient( 'wpcdt_pro_license_exp_notice' );
	
	// If plugin license is dismissed
	if( $notice_transient !== false ) {
		return false;
	}

	$license_info	= $wpcdt_pro_license_info;
	$license 		= get_option( 'wpcdt_pro_plugin_license_key' );
	$license_status = ! empty( $license_info->license_status )	? $license_info->license_status : false;
	$license_msg	= ! empty( $license_info->license_msg )		? $license_info->license_msg	: false;

	if( $license_status == 'expired' && $license ) {

		$notice_link = add_query_arg( array('message' => 'wpcdt-pro-license-exp-notice') );

		echo '<div class="error notice notice-error" style="position:relative;">
				<p>'. wp_kses_post( $license_msg ) .'</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}
add_action( 'admin_notices', 'wpcdt_pro_plugin_license_notice' );