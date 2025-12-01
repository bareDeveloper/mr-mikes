<?php
/**
 * Plugin Name: Countdown Timer Ultimate Pro
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/countdown-timer-ultimate/
 * Description: Easy to add and display Countdown Timer. Works with WordPress timezone. No dependency on client machine.
 * Author: Essential Plugin
 * Text Domain: countdown-timer-ultimate
 * Domain Path: /languages/
 * Version: 2.2
 * Author URI: https://www.essentialplugin.com
 *
 * @package Countdown Timer Ultimate Pro
 * @author Essential Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Basic plugin definitions
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */
if( ! defined( 'WPCDT_PRO_VERSION' ) ) {
	define( 'WPCDT_PRO_VERSION', '2.2' ); // Version of plugin
}

if( ! defined( 'WPCDT_PRO_DIR' ) ) {
	define( 'WPCDT_PRO_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WPCDT_PRO_URL' ) ) {
	define( 'WPCDT_PRO_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WPCDT_PRO_PLUGIN_BASENAME' ) ) {
	define( 'WPCDT_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // plugin base name
}

if( ! defined( 'WPCDT_PRO_POST_TYPE' ) ) {
	define( 'WPCDT_PRO_POST_TYPE', 'wpcdt_countdown' ); // Plugin post type
}

if( ! defined( 'WPCDT_PRO_META_PREFIX' ) ) {
	define( 'WPCDT_PRO_META_PREFIX', '_wpcdt_' ); // Plugin metabox prefix
}

if( ! defined( 'WPOS_TEMPLATE_DEBUG_MODE' ) ) {
	define( 'WPOS_TEMPLATE_DEBUG_MODE', false ); // Debug Mode
}

if( ! defined( 'WPOS_HIDE_LICENSE' ) ) {
	define( 'WPOS_HIDE_LICENSE', 'info' ); // Template Debug Mode
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wpcdt_pro_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wpcdt_pro_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wpcdt_pro_lang_dir = apply_filters( 'wpcdt_pro_languages_directory', $wpcdt_pro_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'countdown-timer-ultimate' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'countdown-timer-ultimate', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WPCDT_PRO_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'countdown-timer-ultimate', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'countdown-timer-ultimate', false, $wpcdt_pro_lang_dir );
	}
}
add_action( 'plugins_loaded', 'wpcdt_pro_load_textdomain' );

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpcdt_pro_install' );

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpcdt_pro_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @since 1.0.0
 */
function wpcdt_pro_install() {

	// Get settings for the plugin
	$wpcdt_pro_options = get_option( 'wpcdt_pro_options' );

	if( empty( $wpcdt_pro_options ) ) { // Check plugin version option

		// Set default settings
		wpcdt_pro_default_settings();

		// Update plugin version to option
		update_option( 'wpcdt_plugin_version', '1.1' );
	}

	// Register Post Type
	wpcdt_pro_register_post_type();

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	if( is_plugin_active('countdown-timer-ultimate/countdown-timer.php') ) {
		add_action('update_option_active_plugins', 'wpcdt_pro_deactivate_free_version');
	}
}

/**
 * Plugin Deactivation
 * Delete plugin options and etc.
 * 
 * @since 1.0.0
 */
function wpcdt_pro_uninstall() {
	// Uninstall functionality
}

/**
 * Deactivate free plugin
 * 
 * @since 1.0.0
 */
function wpcdt_pro_deactivate_free_version() {
	deactivate_plugins('countdown-timer-ultimate/countdown-timer.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0.0
 */
function wpcdt_pro_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = WP_PLUGIN_DIR . '/countdown-timer-ultimate/countdown-timer.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link		= add_query_arg( array('message' => 'wpcdt-pro-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'wpcdt_pro_install_notice' );

	// If PRO plugin is active and free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( esc_html__('Thank you for activating %s', 'countdown-timer-ultimate'), 'Countdown Timer Ultimate Pro').'</strong>.<br/>
					'.sprintf( esc_html__('It looks like you had Free version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'countdown-timer-ultimate'), '<strong><em>Countdown Timer Ultimate</em></strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}

// Action to display notice
add_action( 'admin_notices', 'wpcdt_pro_admin_notice');

/***** Updater Code Starts *****/
define( 'EDD_WPCDT_PRO_STORE_URL', 'https://www.wponlinesupport.com' );
define( 'EDD_WPCDT_PRO_ITEM_NAME', 'Countdown Timer Ultimate Pro' );

// Plugin Updator Class 
if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {    
	include( WPCDT_PRO_DIR . '/EDD_SL_Plugin_Updater.php' );
}

/**
 * Updater Function
 * 
 * @since 1.0.0
 */
function wpcdt_pro_plugin_updater() {

	$license_key = trim( get_option( 'wpcdt_pro_plugin_license_key' ) );

	$edd_updater = new EDD_SL_Plugin_Updater( EDD_WPCDT_PRO_STORE_URL, __FILE__, array(
				'version'	=> WPCDT_PRO_VERSION,			// current version number
				'item_name'	=> EDD_WPCDT_PRO_ITEM_NAME,		// name of this plugin
				'license'	=> $license_key,				// license key (used get_option above to retrieve from DB)
				'author'	=> 'WP Online Support'			// author of this plugin
			));
}
add_action( 'admin_init', 'wpcdt_pro_plugin_updater', 0 );
/***** Updater Code Ends *****/

// Global variables
global $wpcdt_pro_options;

// Plugin Settings
require_once( WPCDT_PRO_DIR . '/includes/admin/settings/register-settings.php' );

// Functions file
require_once( WPCDT_PRO_DIR . '/includes/wpcdt-functions.php' );
$wpcdt_pro_options = wpcdt_pro_get_settings();

// Plugin Post Type File
require_once( WPCDT_PRO_DIR . '/includes/wpcdt-post-types.php' );

// Script Class File
require_once( WPCDT_PRO_DIR . '/includes/class-wpcdt-script.php' );

// Template file
require_once( WPCDT_PRO_DIR . '/includes/wpcdt-template-functions.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

	// How it Work Page
	require_once( WPCDT_PRO_DIR . '/includes/admin/wpcdt-how-it-work.php' );

	// Database Upgrade File for post data migration
	$plugin_version = get_option( 'wpcdt_plugin_version' );
	
	if( version_compare( $plugin_version, '1.1' ) < 0 ) {
		require_once( WPCDT_PRO_DIR . '/includes/admin/wpcdt-db-upgrade.php' );
	}

	// Plugin updater file
	if( ! defined( 'WPOS_HIDE_LICENSE' ) || ( defined( 'WPOS_HIDE_LICENSE' ) && WPOS_HIDE_LICENSE != 'page' ) ) {
		require_once( WPCDT_PRO_DIR . '/wpcdt-plugin-updater.php' );
	}
}

/**
 * Load the plugin after the main plugin is loaded.
 * 
 * @since 1.0.0
 */
function wpcdt_pro_load_plugin() {

	// Admin Class File
	require_once( WPCDT_PRO_DIR . '/includes/admin/class-wpcdt-admin.php' );

	// Public Class File
	require_once( WPCDT_PRO_DIR . '/includes/class-wpcdt-public.php' );

	// Shortcode File
	require_once( WPCDT_PRO_DIR . '/includes/shortcode/wpcdt-shortcode.php' );
	require_once( WPCDT_PRO_DIR . '/includes/shortcode/wpcdt-timer.php' );
	require_once( WPCDT_PRO_DIR . '/includes/shortcode/wpcdt-pre-text.php' );
}

// Action to load plugin after the main plugin is loaded
add_action( 'plugins_loaded', 'wpcdt_pro_load_plugin', 15 );