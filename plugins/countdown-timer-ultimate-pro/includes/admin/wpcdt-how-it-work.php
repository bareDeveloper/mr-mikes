<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Register plugin design page in admin menu
 * 
 * @since 1.0.0
 */
function wpcdt_pro_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WPCDT_PRO_POST_TYPE, __('Getting Started - Countdown Timer Ultimate Pro', 'countdown-timer-ultimate'), __('Getting Started', 'countdown-timer-ultimate'), 'edit_posts', 'wpcdt-designs', 'wpcdt_pro_designs_page' );
}

// Action to add menu
add_action( 'admin_menu', 'wpcdt_pro_register_design_page', 15 );

/**
 * Function to display plugin design HTML
 * 
 * @since 1.0.0
 */
function wpcdt_pro_designs_page() {

	$wpos_feed_tabs	= wpcdt_pro_help_tabs();
	$active_tab		= isset( $_GET['tab'] ) ? wpcdt_pro_clean( $_GET['tab'] ) : 'how-it-work';
?>

	<div class="wrap wpcdt-wrap">

		<h2 class="nav-tab-wrapper">
			<?php foreach( $wpos_feed_tabs as $tab_key => $tab_val ) {
				$tab_name	= $tab_val['name'];
				$active_cls	= ( $tab_key == $active_tab ) ? 'nav-tab-active' : '';
				$tab_link	= add_query_arg( array( 'post_type' => WPCDT_PRO_POST_TYPE, 'page' => 'wpcdt-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo esc_attr( $active_cls ); ?>" href="<?php echo esc_url( $tab_link ); ?>"><?php echo esc_html( $tab_name ); ?></a>

			<?php } ?>
		</h2>

		<div class="wpcdt-tab-cnt-wrp">
		<?php
			if( isset( $active_tab ) && 'how-it-work' == $active_tab ) {
				wpcdt_pro_howitwork_page();
			}
		?>
		</div><!-- end .wpcdt-tab-cnt-wrp -->

	</div><!-- end .wpcdt-wrap -->

<?php
}

/**
 * Function to get plugin feed tabs
 *
 * @since 1.0.0
 */
function wpcdt_pro_help_tabs() {

	$wpos_feed_tabs = array(
						'how-it-work' => array(
											'name' => __('How It Works', 'countdown-timer-ultimate'),
										),
					);

	return $wpos_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @since 1.0.0
 */
function wpcdt_pro_howitwork_page() { ?>

	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.wpcdt-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpcdt-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
	</style>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<!--How it workd HTML -->
			<div id="post-body-content">
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h3 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'countdown-timer-ultimate' ); ?></span>
							</h3>
						</div>

						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('Getting Started', 'countdown-timer-ultimate'); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1: This plugin creates a Countdown Timer Ultimate Pro tab in WordPress menu section', 'countdown-timer-ultimate'); ?></li>
												<li><?php esc_html_e('Step-2: Add Timer.', 'countdown-timer-ultimate'); ?></li>
												<li><?php esc_html_e('Step-3: Display timer on any Post OR Page of your website.', 'countdown-timer-ultimate'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('Plugin Shortcodes', 'countdown-timer-ultimate'); ?></label>
										</th>
										<td>
											<span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt-countdown id="XX"]</span> – <?php esc_html_e('Countdown Timer', 'countdown-timer-ultimate'); ?> <br/>
											<span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt_timer timer_id="XX" end_date="2021-01-26 23:59:59"]</span> – <?php esc_html_e('Simple Countdown Timer', 'countdown-timer-ultimate'); ?> <br/>
											<span class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt_pre_text]Write Something[/wpcdt_pre_text]</span> – <?php esc_html_e('Countdown Timer Pretext', 'countdown-timer-ultimate'); ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->

					<div class="postbox">
						<div class="postbox-header">
							<h3 class="hndle">
								<span><?php esc_html_e( 'Help to improve this plugin!', 'countdown-timer-ultimate' ); ?></span>
							</h3>
						</div>
						<div class="inside">
							<p><?php esc_html_e('Enjoyed this plugin? You can help by rate this plugin', 'countdown-timer-ultimate'); ?> <a href="https://www.essentialplugin.com/your-review/?utm_source=Countdown-Timer&event=review" target="_blank"><?php esc_html_e('5 stars!', 'countdown-timer-ultimate'); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #postbox -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-body-content -->

			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">

						<h3 class="hndle">
							<span><?php esc_html_e( 'Need Support?', 'countdown-timer-ultimate' ); ?></span>
						</h3>
						<div class="inside">
							<p><?php esc_html_e('Check plugin document for shortcode parameters and demo for designs.', 'countdown-timer-ultimate'); ?></p> <br/>
							<a class="button button-primary wpos-button-full" href="https://docs.essentialplugin.com/countdown-timer-ultimate-pro/?utm_source=Countdown-Timer&event=doc" target="_blank"><?php esc_html_e('Documentation', 'countdown-timer-ultimate'); ?></a>
							<p><a class="button button-primary wpos-button-full" href="https://demo.essentialplugin.com/prodemo/countdown-timer-ultimate-pro/?utm_source=Countdown-Timer&event=demo" target="_blank"><?php esc_html_e('View PRO Demo ', 'countdown-timer-ultimate'); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #postbox -->

					<div class="postbox wpos-pro-box">
						<h3 class="hndle">
							<span><?php esc_html_e('Need PRO Support?', 'countdown-timer-ultimate'); ?></span>
						</h3>
						<div class="inside">
							<p><?php esc_html_e('Hire our experts for any WordPress task.', 'countdown-timer-ultimate'); ?></p>
							<p><a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wordpress-services/?utm_source=Countdown-Timer&event=projobs" target="_blank"><?php esc_html_e('Know More', 'countdown-timer-ultimate'); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #postbox -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-container-1 -->
		</div><!-- #post-body -->
	</div><!-- #poststuff -->
<?php }