<?php
/**
 * Settings Page
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Plugin settings tab
$sett_tab	= wpcdt_pro_settings_tab();
$tab		= isset( $_GET['tab'] )	? wpcdt_pro_clean( $_GET['tab'] ) : 'general';

// If no valid tab is there
if( ! isset( $sett_tab[ $tab ] ) ) {
	wpcdt_pro_display_message( 'error' );
	return;
} ?>

<div class="wrap">
	<h2><?php esc_html_e( 'Countdown Timer Settings', 'countdown-timer-ultimate' ); ?></h2>

	<?php
	// Reset message
	if( ! empty( $_POST['wpcdt_reset_settings'] ) ) {
		wpcdt_pro_display_message( 'reset' );
	}

	// Success message
	if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		wpcdt_pro_display_message( 'update' );
	}

	settings_errors( 'wpcdt_sett_error' );
	?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $sett_tab as $tab_key => $tab_val ) {
			$tab_url		= add_query_arg( array( 'post_type' => WPCDT_PRO_POST_TYPE, 'page' => 'wpcdt-pro-settings', 'tab' => $tab_key ), admin_url('edit.php') );
			$active_tab_cls	= ( $tab == $tab_key ) ? 'nav-tab-active' : '';
		?>
			<a class="nav-tab <?php echo esc_attr( $active_tab_cls ); ?>" href="<?php echo esc_url( $tab_url ); ?>"><?php echo esc_html( $tab_val ); ?></a>
		<?php } ?>
	</h2>

	<div class="wpcdt-sett-wrap wpcdt-settings wpcdt-pad-top-20">

		<!-- Plugin reset settings form -->
		<form action="" method="post" id="wpcdt-reset-sett-form" class="wpcdt-right wpcdt-reset-sett-form">
			<input type="submit" class="button button-primary wpcdt-reset-sett wpcdt-resett-sett-btn wpcdt-confirm" name="wpcdt_reset_settings" id="wpcdt-reset-sett" value="<?php esc_attr_e( 'Reset All Settings', 'countdown-timer-ultimate' ); ?>" data-msg="<?php esc_attr_e('Click OK to reset all options. All settings will be reset!', 'countdown-timer-ultimate'); ?>" />
			<?php wp_nonce_field( 'wpcdt_reset_setting', 'wpcdt_reset_sett_nonce' ); ?>
		</form>

		<form action="options.php" method="POST" id="wpcdt-settings-form" class="wpcdt-settings-form">

			<?php settings_fields( 'wpcdt_pro_plugin_options' ); ?>

			<div class="textright wpcdt-clearfix">
				<input type="submit" name="wpcdt_settings_submit" class="button button-primary right wpcdt-sett-submit" value="<?php esc_html_e('Save Changes', 'countdown-timer-ultimate'); ?>" />
			</div>

			<div class="metabox-holder">
				<div class="post-box-container">
					<div class="meta-box-sortables ui-sortable">

						<?php
						// Setting files
						switch ( $tab ) {
							case 'general':
								include_once( WPCDT_PRO_DIR . '/includes/admin/settings/general-settings.php' );
								break;

							case 'wc':
								include_once( WPCDT_PRO_DIR . '/includes/admin/settings/wc-settings.php' );
								break;

							case 'edd':
								include_once( WPCDT_PRO_DIR . '/includes/admin/settings/edd-settings.php' );
								break;

							case 'custom_css':
								include_once( WPCDT_PRO_DIR . '/includes/admin/settings/custom-css-settings.php' );
								break;

							default:
								do_action( 'wpcdt_sett_panel_' . $tab );
								do_action( 'wpcdt_sett_panel', $tab );
								break;
						} ?>

					</div><!-- end .meta-box-sortables -->
				</div><!-- end .post-box-container -->
			</div><!-- end .metabox-holder -->
		</form><!-- end .wpcdt-settings-form -->
	</div><!-- end .wpcdt-sett-wrap -->
</div>