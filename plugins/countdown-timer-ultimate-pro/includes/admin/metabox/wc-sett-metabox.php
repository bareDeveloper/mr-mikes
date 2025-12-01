<?php
/**
 * Handles WooCommerce Product Setting metabox HTML
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Taking some data
$timer_title		= array();
$prefix				= WPCDT_PRO_META_PREFIX;
$type_opts			= wpcdt_pro_timer_type_opts();
$enable_opts		= wpcdt_pro_timer_show_hide();
$wc_enable			= get_post_meta( $post->ID, $prefix.'wc_enable', true );
$wc_timer_id		= get_post_meta( $post->ID, $prefix.'wc_timer_id', true );
$wc_timer_shrt		= get_post_meta( $post->ID, $prefix.'wc_timer_shrt', true );
$wc_timer_type		= get_post_meta( $post->ID, $prefix.'wc_timer_type', true );
$wc_timer_type		= ! empty( $wc_timer_type ) ? $wc_timer_type : 'timer';
$timer_post_data	= wpcdt_pro_get_timer_posts( $wc_timer_id, 'posts' );
$timer_title['']	= __( 'Select Timer', 'countdown-timer-ultimate' );

if( ! empty( $timer_post_data ) ) {
	
	foreach( $timer_post_data as $timer_post_key => $timer_post_val ) {

		$timer_type = get_post_meta( $timer_post_val->ID, $prefix.'timer_type', true );
		$timer_type = ( $timer_type == 'simple' ) ? __('Simple Timer', 'countdown-timer-ultimate') : __('Content Timer', 'countdown-timer-ultimate');
		
		$timer_title[$timer_post_val->ID] = $timer_post_val->post_title. ' - #' . $timer_post_val->ID . ' ['. $timer_type . ']';
	}
}
?>

<div id="wpcdt_product_data" class="wpcdt_product_data panel woocommerce_options_panel hidden">

	<div class="options_group">
		<?php woocommerce_wp_select( array( 'id' => esc_attr( $prefix ).'wc_enable', 'value' => esc_attr( $wc_enable ), 'label' => __( 'Enable', 'countdown-timer-ultimate' ), 'options' => $enable_opts, 'desc_tip' => 'true', 'description' => esc_html__('Enable / Disable countdown timer or choose from global setting.', 'countdown-timer-ultimate'), 'class' => 'wpcdt-select' ) ); ?>
	</div>

	<div class="options_group">
		<?php woocommerce_wp_select( array( 'id' => esc_attr( $prefix ).'wc_timer_type', 'value' => esc_attr( $wc_timer_type ), 'label' => __( 'Timer Type', 'countdown-timer-ultimate' ), 'options' => $type_opts, 'class' => 'wpcdt-select wpcdt-show-hide', 'desc_tip' => 'true', 'description' => esc_html__('Select countdown timer type.', 'countdown-timer-ultimate') ) ); ?>
	</div>

	<div class="wpcdt-show-hide-row wpcdt-show-if-timer options_group" style="<?php if( $wc_timer_type != 'timer' ) { echo 'display: none;'; } ?>">
		<?php woocommerce_wp_select( array( 'id' => esc_attr( $prefix ).'wc_timer_id', 'value' => esc_attr( $wc_timer_id ), 'label' => __( 'Select Timer', 'countdown-timer-ultimate' ), 'options' => $timer_title, 'class' => 'wpcdt-select', 'desc_tip' => 'true', 'description' => esc_html__('Select countdown timer. Note : `Simple Timer Type` countdown is the best option to choose because of compact layout.', 'countdown-timer-ultimate') ) ); ?>
	</div>

	<div class="wpcdt-show-hide-row wpcdt-show-if-shortcode options_group" style="<?php if( $wc_timer_type != 'shortcode' ) { echo 'display: none;'; } ?>">
		<?php woocommerce_wp_textarea_input( array( 'id' => esc_attr( $prefix ).'wc_timer_shrt', 'value' => esc_attr( $wc_timer_shrt ), 'label' => __( 'Shortcode', 'countdown-timer-ultimate' ), 'class' => 'wpcdt-wtcs-shortcode', 'desc_tip' => 'true', 'description' => esc_html__('Enter countdown timer shortcode for all the products. e.g. [wpcdt_timer timer_id="123" start_date="2021-01-24 23:59:59" end_date="2021-01-26 23:59:59"] Please take a look at the plugin documentation for all parameters.', 'countdown-timer-ultimate') ) ); ?>
	</div>

</div><!-- end .wpcdt_product_data -->