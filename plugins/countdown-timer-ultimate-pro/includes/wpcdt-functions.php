<?php
/**
 * Plugin generic functions file
 *
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Update default settings
 * 
 * @since 1.0.0
 */
function wpcdt_pro_default_settings() {

	global $wpcdt_pro_options;

	$wpcdt_pro_options = array(
		'recuring_prefix'	=> 'wpcdt_',
		'post_guten_editor'	=> 0,
		'custom_css'		=> '',
		'wc_enable'			=> 0,
		'wc_timer_type'		=> '',
		'wc_timer_id'		=> '',
		'wc_timer_shrt'		=> '',
		'wc_single'			=> 0,
		'wc_single_pos'		=> '',
		'wc_shop'			=> 0,
		'wc_shop_pos'		=> '',
		'edd_enable'		=> 0,
		'edd_timer_type'	=> '',
		'edd_timer_id'		=> '',
		'edd_timer_shrt'	=> '',
		'edd_single'		=> 0,
		'edd_single_pos'	=> '',
		'edd_shop'			=> 0,
		'edd_shop_pos'		=> '',
	);

	$default_options = apply_filters( 'wpcdt_options_default_values', $wpcdt_pro_options );

	// Update default options
	update_option( 'wpcdt_pro_options', $default_options );

	// Overwrite global variable when option is update
	$wpcdt_pro_options = wpcdt_pro_get_settings();
}

/**
 * Get Settings From Option Page
 * Handles to return all settings value
 * 
 * @since 1.0.0
 */
function wpcdt_pro_get_settings() {

	$options	= get_option( 'wpcdt_pro_options' );
	$settings	= is_array( $options ) ? $options : array();

	return $settings;
}

/**
 * Get an option
 * Looks to see if the specified setting exists, returns default if not
 * 
 * @since 1.0.0
 */
function wpcdt_pro_get_option( $key = '', $default = false ) {
	
	global $wpcdt_pro_options;

	$value	= ! empty( $wpcdt_pro_options[ $key ] ) ? $wpcdt_pro_options[ $key ] : $default;
	$value	= apply_filters( 'wpcdt_pro_get_option', $value, $key, $default );

	return apply_filters( 'wpcdt_pro_get_option_' . $key, $value, $key, $default );
}

/**
 * Function to unique number value
 * 
 * @since 1.0.0
 */
function wpcdt_pro_get_unique() {
	static $unique = 0;
	$unique++;

	// For Elementor, Beaver Builder & VC Page Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) )
	|| ( function_exists('vc_is_inline') && vc_is_inline() ) 
	|| ( isset( $_POST['action'] ) && $_POST['action'] == 'wpcdt_pro_end_timer' )
	|| ( is_admin() && (defined( 'DOING_AJAX' ) && DOING_AJAX) ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0
 */
function wpcdt_pro_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wpcdt_pro_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0.0
 */
function wpcdt_pro_clean_number( $var, $fallback = null, $type = 'int' ) {

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else {
		$data = absint( $var );
	}

	return ( empty($data) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize color value and return fallback value if it is blank
 * 
 * @since 1.0.0
 */
function wpcdt_pro_clean_color( $color, $fallback = null ) {

	if ( false === strpos( $color, 'rgba' ) ) {
		
		$data = sanitize_hex_color( $color );

	} else {

		$red	= 0;
		$green	= 0;
		$blue	= 0;
		$alpha	= 0.5;

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$data = 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

	return ( empty($data) && $fallback ) ? $fallback : $data;
}

/**
 * Allow Valid Html Tags
 * It will sanitize HTML (strip script and style tags)
 *
 * @since 1.0
 */
function wpcdt_pro_clean_html( $data = array() ) {

	if ( is_array( $data ) ) {

		$data = array_map( 'wpcdt_pro_clean_html', $data );

	} elseif ( is_string( $data ) ) {
		$data = trim( $data );
		$data = wp_filter_post_kses( $data );
	}

	return $data;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @since 1.2.1
 */
function wpcdt_pro_sanitize_html_classes( $classes, $sep = " " ) {

	$return = "";

	if( ! is_array( $classes ) ) {
		$classes = explode( $sep, $classes );
	}

	if( ! empty( $classes ) ) {
		foreach( $classes as $class ) {
			$return .= sanitize_html_class($class) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to add array after specific key
 * 
 * @since 1.0.0
 */
function wpcdt_pro_add_array( &$array, $value, $index, $from_last = false ) {

	if( is_array( $array ) && is_array( $value ) ) {

		if( $from_last ) {
			$total_count	= count( $array );
			$index			= ( ! empty( $total_count ) && ( $total_count > $index ) ) ? ( $total_count - $index ): $index;
		}

		$split_arr	= array_splice( $array, max( 0, $index ) );
		$array		= array_merge( $array, $value, $split_arr );
	}

	return $array;
}

/**
* Function to add array after specific key
*
* @since 1.0.0
*/
function wpcdt_pro_designs() {

	$design_arr = array(
			'circle'    => __( 'Circle Style 1', 'countdown-timer-ultimate' ),
			'design-3'  => __( 'Circle Style 2', 'countdown-timer-ultimate' ),
			'design-1'  => __( 'Circle Style 3', 'countdown-timer-ultimate' ),
			'design-6'  => __( 'Simple Clock 1', 'countdown-timer-ultimate' ),
			'design-7'  => __( 'Simple Clock 2', 'countdown-timer-ultimate' ),
			'design-12' => __( 'Simple Clock 3', 'countdown-timer-ultimate' ),
			'design-5'  => __( 'Simple Clock 4', 'countdown-timer-ultimate' ),
			'design-8'  => __( 'Horizontal Flip', 'countdown-timer-ultimate' ),
			'design-2'  => __( 'Vertical Flip', 'countdown-timer-ultimate' ),
			'design-9'  => __( 'Modern Clock', 'countdown-timer-ultimate' ),
			'design-11' => __( 'Shadow Clock', 'countdown-timer-ultimate' ),
			'design-4'  => __( 'Bars Clock', 'countdown-timer-ultimate' ),
		);

	return apply_filters( 'wpcdt_pro_designs', $design_arr );
}

/**
 * Function to get time options
 * 
 * @since 1.4
 */
function wpcdt_pro_recuring_opts() {

	$recuring_opts = array(	
					'minutes'	=> __( 'Minutes', 'countdown-timer-ultimate' ),
					'hour'		=> __( 'Hours', 'countdown-timer-ultimate' ),
					'day'		=> __( 'Days', 'countdown-timer-ultimate' ),
				);

	return apply_filters( 'wpcdt_pro_recuring_opts', $recuring_opts );
}

/**
 * Function to get week days
 * 
 * @since 1.4
 */
function wpcdt_pro_week_days() {

	$day_opts = array(	
					'0'	=> __( 'Sunday', 'countdown-timer-ultimate' ),
					'1'	=> __( 'Monday', 'countdown-timer-ultimate' ),
					'2'	=> __( 'Tuesday', 'countdown-timer-ultimate' ),
					'3'	=> __( 'Wednesday', 'countdown-timer-ultimate' ),
					'4'	=> __( 'Thursday', 'countdown-timer-ultimate' ),
					'5'	=> __( 'Friday', 'countdown-timer-ultimate' ),
					'6'	=> __( 'Saturday', 'countdown-timer-ultimate' ),
				);

	return apply_filters( 'wpcdt_pro_week_days', $day_opts );
}

/**
 * Function to check week days
 * 
 * @since 1.4
 */
function wpcdt_pro_check_week_days() {

	$day_opts = array(	
					'0'	=> 'sunday',
					'1'	=> 'monday',
					'2'	=> 'tuesday',
					'3'	=> 'wednesday',
					'4'	=> 'thursday',
					'5'	=> 'friday',
					'6'	=> 'saturday',
				);

	return apply_filters( 'wpcdt_pro_check_week_days', $day_opts );
}

/**
 * Function to get post featured image
 * 
 * @since 1.0
 */
function wpcdt_pro_get_featured_image( $post_id = '', $size = 'full', $default_img = false ) {

	$size   = ! empty( $size ) ? $size : 'full';
	$image  = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

	if( ! empty( $image ) ) {
		$image = isset( $image[0] ) ? $image[0] : '';
	}

	// Getting default image
	if( $default_img && empty( $image ) ) {
		return $default_img;
	}

	return $image;
}

/**
 * Function to display message, norice etc
 * 
 * @since 1.0
 */
function wpcdt_pro_display_message( $type = 'update', $msg = '', $echo = 1 ) {

	switch ( $type ) {
		case 'reset':
			$msg = ! empty( $msg ) ? $msg : __( 'All settings reset successfully.', 'countdown-timer-ultimate');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		case 'error':
			$msg = ! empty( $msg ) ? $msg : __( 'Sorry, Something happened wrong.', 'countdown-timer-ultimate');
			$msg_html = '<div id="message" class="error notice is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		default:
			$msg = ! empty( $msg ) ? $msg : __( 'Your changes saved successfully.', 'countdown-timer-ultimate');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>'. $msg .'</strong></p>
						</div>';
			break;
	}

	if( $echo ) {
		echo wp_kses_post( $msg_html );
	} else {
		return wp_kses_post( $msg_html );
	}
}

/**
 * Function to render content.
 * An alternate solution of apply_filter('the_content')
 *
 * Prioritize the function in a same order apply_filter('the_content') wp-includes/default-filters.php
 * 
 * @since 2.2
 */
function wpcdt_pro_render_content( $content = '' ) {

	if ( empty( $content ) ) {
		return false;
	}

	global $wp_embed;

	$content	= $wp_embed->run_shortcode( $content );
	$content	= $wp_embed->autoembed( $content );
	$content	= wptexturize( $content );
	$content	= wpautop( $content );
	$content	= shortcode_unautop( $content );

	// Since Version 5.5.0
	if ( function_exists('wp_filter_content_tags') ) {
		$content = wp_filter_content_tags( $content );
	}

	// Since Version 5.7.0
	if ( function_exists( 'wp_replace_insecure_home_url' ) ) {
		$content = wp_replace_insecure_home_url( $content );
	}

	$content	= do_shortcode( $content );
	$content	= convert_smilies( $content );
	$content	= str_replace( ']]>', ']]&gt;', $content );

	return apply_filters( 'wpcdt_pro_render_content', $content );
}

/**
 * Function to get post type posts by meta
 * 
 * @since 1.4
 */
function wpcdt_pro_get_timer_posts( $selected_id = '', $display = 'html' ) {

	$post_args = array(
		'post_type' 		=> WPCDT_PRO_POST_TYPE,
		'post_status' 		=> array('publish'),
		'posts_per_page' 	=> -1,
		'order'				=> 'DESC',
		'orderby' 			=> 'date',
	);

	if( $display == 'posts' ) {

		$wpcdt_posts = get_posts( $post_args );

		return apply_filters( 'wpcdt_pro_get_timer_posts', $wpcdt_posts, $post_args );

	} else {

		// Get content timer posts
		$post_args['meta_query'] = array(
										array(
											'key' 		=> WPCDT_PRO_META_PREFIX . 'timer_type',
											'value'		=> 'content',
											'compare'	=> '=',
										)
									);

		$content_posts 	= get_posts( $post_args );

		// Get simple timer posts 
		$post_args['meta_query'] = array(
										array (
										 	'key' 		=> WPCDT_PRO_META_PREFIX . 'timer_type',
											'value'		=> 'simple',
											'compare'	=> '=',
										)
									);

		$simple_posts = get_posts( $post_args );

		$content = ob_start(); 

		if( ! empty( $content_posts ) ) { ?>

			<optgroup label="<?php esc_html_e( 'Content Timer', 'countdown-timer-ultimate'); ?>">
				<?php foreach ( $content_posts as $content_post_key => $content_post_val ) { ?>
					<option value="<?php echo esc_attr( $content_post_val->ID ); ?>" <?php selected( $content_post_val->ID, $selected_id ); ?>><?php echo esc_html( $content_post_val->post_title .' - #'. $content_post_val->ID ); ?></option>
				<?php } ?>
			</optgroup>

		<?php } 

		if( ! empty( $simple_posts ) ) { ?>

			<optgroup label="<?php esc_html_e( 'Simple Timer', 'countdown-timer-ultimate'); ?>">
				<?php foreach ( $simple_posts as $simple_post_key => $simple_post_val ) { ?>
					<option value="<?php echo esc_attr( $simple_post_val->ID ); ?>" <?php selected( $simple_post_val->ID, $selected_id ); ?>><?php echo esc_html( $simple_post_val->post_title .' - #'. $simple_post_val->ID ); ?> </option>
				<?php } ?>
			</optgroup>
			<?php
		}

		$content .= ob_get_clean();

		return $content;
	}
}

/**
* Function to get timer enable/disable options
*
* @since 1.4
*/
function wpcdt_pro_timer_show_hide() {

	$show_hide_arr = array(
			0	=> __( 'Global Setting', 'countdown-timer-ultimate' ),
			1	=> __( 'Enable', 'countdown-timer-ultimate' ),
			2	=> __( 'Disable', 'countdown-timer-ultimate' ),
		);

	return apply_filters( 'wpcdt_pro_timer_show_hide', $show_hide_arr );
}

/**
* Function to get timer types for products
*
* @since 1.4
*/
function wpcdt_pro_timer_type_opts() {

	$type_arr = array(
			'timer'		=> __( 'Countdown Timer', 'countdown-timer-ultimate' ),
			'shortcode'	=> __( 'Countdown Timer Shortcode', 'countdown-timer-ultimate' ),
		);

	return apply_filters( 'wpcdt_pro_timer_type_opts', $type_arr );
}

/**
* Function to get timer position for woocommerce
*
* @since 1.4
*/
function wpcdt_pro_wc_timer_pos_opts() {

	$pos_arr = array(
			'before_cart'	=> __( 'Before Add to Cart', 'countdown-timer-ultimate' ),
			'after_cart'	=> __( 'After Add to Cart', 'countdown-timer-ultimate' ),
		);

	return apply_filters( 'wpcdt_pro_wc_timer_pos_opts', $pos_arr );
}

/**
* Function to get timer position for EDD(Easy Digital Download)
*
* @since 1.4
*/
function wpcdt_pro_edd_timer_pos_opts() {

	$position_arr = array(
			'before_purchase'	=> __( 'Before Purchase Button', 'countdown-timer-ultimate' ),
			'after_purchase'	=> __( 'After Purchase Button', 'countdown-timer-ultimate' ),
		);

	return apply_filters( 'wpcdt_pro_edd_timer_pos_opts', $position_arr );
}

/**
 * Function to render simple timer for WooCommerce products
 * 
 * @since 1.4
 */
function wpcdt_pro_render_wc_product_timer() {

	global $post;

	// Taking some data
	$prefix		= WPCDT_PRO_META_PREFIX;
	$post_id	= isset( $post->ID ) ? $post->ID : 0;
	$wc_enable	= wpcdt_pro_get_option( 'wc_enable' );
	$wc_enable	= apply_filters( 'wpcdt_pro_render_wc_product_timer', $wc_enable, $post );
	$timer_data	= array(
					'single_position'	=> '',
					'shop_position'		=> '',
					'shortcode'			=> '',
				);

	// If timer enable is not there
	if( ! $wc_enable || empty( $post_id ) ) {
		return false;
	}

	// Taking some data
	$wc_single		= wpcdt_pro_get_option( 'wc_single' );
	$wc_single_pos	= wpcdt_pro_get_option( 'wc_single_pos' );
	$wc_shop		= wpcdt_pro_get_option( 'wc_shop' );
	$wc_shop_pos	= wpcdt_pro_get_option( 'wc_shop_pos' );
	$wc_enable		= get_post_meta( $post_id, $prefix.'wc_enable', true );

	// If product timer is `disable`
	if( $wc_enable == 2 || ( ! $wc_single && is_singular('product') || ( ! $wc_shop && is_shop() ) ) ) {
		return $timer_data;
	}

	// Check woocommerce product timer data else Global
	if( $wc_enable == 1 ) {

		$wc_timer_type	= get_post_meta( $post_id, $prefix.'wc_timer_type', true );
		$wc_timer_id	= get_post_meta( $post_id, $prefix.'wc_timer_id', true );
		$wc_timer_shrt	= get_post_meta( $post_id, $prefix.'wc_timer_shrt', true );

	} else {

		$wc_timer_type	= wpcdt_pro_get_option('wc_timer_type');
		$wc_timer_id	= wpcdt_pro_get_option('wc_timer_id');
		$wc_timer_shrt	= wpcdt_pro_get_option('wc_timer_shrt');
	}

	if( $wc_timer_type == 'timer' ) {

		$timer_data['single_position']	= $wc_single_pos;
		$timer_data['shop_position']	= $wc_shop_pos;
		$timer_data['shortcode']		= do_shortcode('[wpcdt-countdown id="'. $wc_timer_id .'"]');

	} else {

		$timer_data['single_position']	= $wc_single_pos;
		$timer_data['shop_position']	= $wc_shop_pos;
		$timer_data['shortcode']		= do_shortcode( nl2br( $wc_timer_shrt ) );
	}

	return $timer_data;
}

/**
 * Function to render simple timer for EDD(Easy Digital Download) products
 * 
 * @since 1.4
 */
function wpcdt_pro_render_edd_product_timer() {

	global $post;

	// Taking some data
	$prefix		= WPCDT_PRO_META_PREFIX;
	$post_id	= isset( $post->ID ) ? $post->ID : 0;
	$edd_enable	= wpcdt_pro_get_option('edd_enable');
	$edd_enable	= apply_filters( 'wpcdt_pro_render_edd_product_timer', $edd_enable, $post );
	$timer_data	= array(
					'single_position'	=> '',
					'shop_position'		=> '',
					'shortcode'			=> '',
				);

	// If timer enable is not there
	if( ! $edd_enable || empty( $post_id ) ) {
		return false;
	}

	// Taking some data
	$edd_single		= wpcdt_pro_get_option( 'edd_single' );
	$edd_single_pos	= wpcdt_pro_get_option( 'edd_single_pos' );
	$edd_shop		= wpcdt_pro_get_option( 'edd_shop' );
	$edd_shop_pos	= wpcdt_pro_get_option( 'edd_shop_pos' );
	$edd_enable		= get_post_meta( $post_id, $prefix.'edd_enable', true );

	// If product timer is `disable`
	if( $edd_enable == 2 || ( ! $edd_single && is_singular('download') ) ) {
		return $timer_data;
	}

	// Check EDD product timer data else Global
	if( $edd_enable == 1 ) {

		$edd_timer_type	= get_post_meta( $post_id, $prefix.'edd_timer_type', true );
		$edd_timer_id	= get_post_meta( $post_id, $prefix.'edd_timer_id', true );
		$edd_timer_shrt	= get_post_meta( $post_id, $prefix.'edd_timer_shrt', true );

	} else {

		$edd_timer_type	= wpcdt_pro_get_option( 'edd_timer_type' );
		$edd_timer_id	= wpcdt_pro_get_option( 'edd_timer_id' );
		$edd_timer_shrt	= wpcdt_pro_get_option( 'edd_timer_shrt' );
	}

	if( $edd_timer_type == 'timer' ) {

		$timer_data['single_position']	= $edd_single_pos;
		$timer_data['shop_position']	= $edd_shop_pos;
		$timer_data['shortcode']		= do_shortcode('[wpcdt-countdown id="'. $edd_timer_id .'"]');

	} else {

		$timer_data['single_position']	= $edd_single_pos;
		$timer_data['shop_position']	= $edd_shop_pos;
		$timer_data['shortcode']		= do_shortcode( nl2br( $edd_timer_shrt ) );
	}

	return $timer_data;
}

/**
 * Function to generate timer style
 * 
 * @since 1.4
 */
function wpcdt_pro_generate_style( $post_id = 0, $timer_type = '', $design_style = '', $design_data = array(), $echo = true ) {

	// Taking some variable
	$style		= '';
	$prefix		= WPCDT_PRO_META_PREFIX;
	$bg_clr		= ! empty( $design_data['background_pref'] ) 	? $design_data['background_pref'] 	: '';
	$font_clr	= ! empty( $design_data['font_clr'] ) 			? $design_data['font_clr'] 			: '';
	$lbl_clr	= ! empty( $design_data['timertext_color'] ) 	? $design_data['timertext_color'] 	: '';
	$digit_clr	= ! empty( $design_data['timerdigit_color'] ) 	? $design_data['timerdigit_color'] 	: '';

	// Background Color
	if( $bg_clr ) {
		$style .= ".wpcdt-timer-{$post_id} {background-color: {$bg_clr};}";
	}

	// If Timer type is `Simple Timer(Only Timer)`
	if( $timer_type == 'simple' ) {

		if( $lbl_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-smpl-lbl{color: {$lbl_clr};}";
		}
		if( $digit_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-smpl-digits span{color: {$digit_clr};}";
		}
		if( $font_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-smpl-col{color: {$font_clr};}";
		}
	} else { // Else Timer type is `Content Timer`

		// Taking some variable
		$bg_img	= ! empty( $design_data['bg_img'] ) ? $design_data['bg_img'] : '';

		// Circle Style 1
		$timer_width = ! empty( $design_data['timer_width'] ) ? $design_data['timer_width'] : '';

		// Bar Background Colors
		$timerbackground_color			= ! empty( $design_data['timerbackground_color'] ) 			? $design_data['timerbackground_color'] 		: '';
		$timerdaysbackground_color		= ! empty( $design_data['timerdaysbackground_color'] ) 		? $design_data['timerdaysbackground_color'] 	: '';
		$timerhoursbackground_color		= ! empty( $design_data['timerhoursbackground_color'] ) 	? $design_data['timerhoursbackground_color'] 	: '';
		$timerminutesbackground_color	= ! empty( $design_data['timerminutesbackground_color'] ) 	? $design_data['timerminutesbackground_color'] 	: '';
		$timersecondsbackground_color	= ! empty( $design_data['timersecondsbackground_color'] ) 	? $design_data['timersecondsbackground_color'] 	: '';

		// Theme color
		$theme_clr = ! empty( $design_data['theme_clr'] ) ? $design_data['theme_clr'] : '';

		if( $bg_img ) {
			$style .= ".wpcdt-timer-{$post_id} {background: url({$bg_img}); background-repeat: no-repeat; background-position: center center; background-size: cover;}";
		}

		// If background image and background color is there
		if( $bg_img && $bg_clr ) {
			$style .= ".wpcdt-timer-{$post_id}:before {background-color: {$bg_clr};}";
		}
		if( $font_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-title, .wpcdt-timer-{$post_id} .wpcdt-desc, .wpcdt-timer-{$post_id} .wpcdt-desc p{color: {$font_clr};}";
		}
		if( $lbl_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock-circle h4,
						.wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-lbl{color: {$lbl_clr};}";
		}
		if( $digit_clr ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock-circle span,
						.wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-digits,
						.wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-digits span{color: {$digit_clr};}";
		}

		if( $timer_width ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock{max-width: {$timer_width}px;}";
		}

		// Circle Style 3
		if( $design_style == 'design-1' ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-digits span{border-color: {$theme_clr};}";
		}

		// Simple Clock 3
		if( $design_style == 'design-7' ) {
			$style .= ".wpcdt-timer-{$post_id}.wpcdt-timer-design-7 .wpcdt-digits::before{color: {$theme_clr};}";
		}

		// Simple Clock 5
		if( $design_style == 'design-5' ) {
			$style .= ".wpcdt-timer-{$post_id}.wpcdt-timer-design-5 .wpcdt-col+.wpcdt-col{border-color: {$theme_clr};}";
		}

		// Horizontal Flip
		if( $design_style == 'design-8' ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-flip-wrap .wpcdt-hflip-inr{background-color: {$theme_clr};}";
		}

		// Vertical Flip
		if( $design_style == 'design-2' ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-digits, .wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-flip-wrap .inn{background-color: {$theme_clr}; color: {$digit_clr};}";
		}

		// Modern Clock
		if( $design_style == 'design-9' ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-digits:before{color: {$theme_clr};}";
		}

		// Shadow Clock
		if( $design_style == 'design-11' ) {
			$style .= ".wpcdt-timer-{$post_id}.wpcdt-timer-design-11 .wpcdt-clock .wpcdt-digits span{text-shadow: -0.02em 0.05em 0 {$theme_clr}, 0.08em 0.08em 0 {$theme_clr};}";
		}

		if( $design_style == 'design-4' ) {
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .wpcdt-bar{background-color: {$timerbackground_color};}";
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .ce-bar-days .wpcdt-fill{background-color: {$timerdaysbackground_color};}";
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .ce-bar-hours .wpcdt-fill{background-color: {$timerhoursbackground_color};}";
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .ce-bar-minutes .wpcdt-fill{background-color: {$timerminutesbackground_color};}";
			$style .= ".wpcdt-timer-{$post_id} .wpcdt-clock .ce-bar-seconds .wpcdt-fill{background-color: {$timersecondsbackground_color};}";
		}
	}

	$style = apply_filters('wpcdt_pro_generate_timer_style', $style, $post_id, $design_style, $design_data );

	if( $echo ) {
		echo "<style type='text/css'>". wp_strip_all_tags( $style ) ."</style>";
	} else {
		return $style;
	}
}

/**
 * Function to return `Start Date` & `End Date` for Recurring Timer
 * 
 * @since 1.7
 */
function wpcdt_pro_recurring_dates( $timer_mode = '', $recurring_data = array() ) {

	// Taking some variable
	$results	= array(
					'start_date'	=> '',
					'timer_date'	=> '',
				);

	// Return If `Timer Mode` is not `Recurring Timer`
	if( $timer_mode != 'recurring' ) {
		return $results;
	}

	// Taking some variable
	$recur_mode		= ! empty( $recurring_data['recur_mode'] )	? $recurring_data['recur_mode'] : 'daily';
	$start_time		= ! empty( $recurring_data['start_time'] )	? $recurring_data['start_time']	: '00:00:00';
	$end_time		= ! empty( $recurring_data['end_time'] )	? $recurring_data['end_time']	: '23:59:59';
	$start_time		= strtotime( $start_time );
	$end_time		= strtotime( $end_time );
	$current_time	= current_time( 'timestamp' );

	// If `Recurring Mode` is `Daily`
	if( $recur_mode == 'daily' ) {

		$results['start_date']	= date_i18n( 'Y-m-d H:i:s', $start_time );
		$results['timer_date']	= date_i18n( 'Y-m-d H:i:s', $end_time );

	} else if( $recur_mode == 'weekly' ) { // If `Recurring Mode` is `Weekly`

		// Taking some variable
		$week_days	= wpcdt_pro_check_week_days();
		$week_start	= isset( $recurring_data['week_start'] )	? $recurring_data['week_start']	: 1;
		$week_end	= isset( $recurring_data['week_end'] )		? $recurring_data['week_end']	: 0;

		$curr_day_of_week	= strtolower( current_time( 'l' ) ); // Current Day Name
		$curr_day_number	= date_i18n( "w", strtotime( $curr_day_of_week ) ); // Current Day Number

		$rang_arr	= array();
		$num		= $week_start;

		for ($i = 0; $i <= 6; $i++) {

			// If check number not in rang array
			if( ! in_array( $num, $rang_arr ) ) {
				$rang_arr[] = $num;
			}

			// Check If `Number` is equal to `Week End` number and `Week Start` is not equal to `Week End`
			if( $num == $week_end && $week_start != $week_end ) {
				break;
			}

			$num++; // Number increment

			// If check `Number` is greater then `6`
			if( $num > 6 ) {
				$num = 0;
			}
		}

		// If check current day is there in `rang` array
		if( in_array( $curr_day_number, $rang_arr ) ) {

			// Get start date time
			$get_sday_name		= $week_days[ $week_start ];
			$last_start_day		= strtotime( 'last '.$get_sday_name, $current_time );
			$week_start_day		= ( $curr_day_of_week != $get_sday_name ) ? $last_start_day : $current_time;
			$week_start_date	= date_i18n( 'Y-m-d', $week_start_day );
			$week_start_time	= date_i18n( 'H:i:s', $start_time );

			// Get end date time
			$get_eday_name	= $week_days[ $week_end ];
			$next_end_day	= strtotime( 'next '.$get_eday_name );
			$week_end_day	= ( $curr_day_of_week != $get_eday_name ) ? $next_end_day : $current_time;
			$week_end_date	= date_i18n( 'Y-m-d', $week_end_day );
			$week_end_time	= date_i18n( 'H:i:s', $end_time );

			// If `Week Start` is equal to `Week End` and `Current Day` is equal to `Week End`
			if( $week_start == $week_end && $week_start == $curr_day_number ) {

				if( $start_time >= $current_time ) {

					$week_start_date	= date_i18n( 'Y-m-d', $last_start_day );
					$week_end_date		= date_i18n( 'Y-m-d', $current_time );

				} elseif( $start_time <= $current_time ) {

					$week_start_date	= date_i18n( 'Y-m-d', $current_time );
					$week_end_date		= date_i18n( 'Y-m-d', $next_end_day );
				}
			}

			$results['start_date']	= $week_start_date.' '.$week_start_time;
			$results['timer_date']	= $week_end_date.' '.$week_end_time;

		} else {

			$results['timer_date'] = date_i18n( 'Y-m-d H:i:s', strtotime(' -1 day') );
		}

	} else if( $recur_mode == 'custom' ) { // If `Recurring Mode` is `Custom`

		// Taking some variable
		$recur_on			= ! empty( $recurring_data['recur_on'] ) ? $recurring_data['recur_on'] : array();
		$curr_day_of_week	= strtolower( current_time( 'l' ) );
		$curr_day_number	= date("w", strtotime( $curr_day_of_week ));

		// If check current day is there in `recur_on` array
		if( in_array( $curr_day_number, $recur_on ) ) {

			$results['start_date']	= date_i18n( 'Y-m-d H:i:s', $start_time );
			$results['timer_date']	= date_i18n( 'Y-m-d H:i:s', $end_time );

		} else { // If timer day is not there then expire it

			$results['timer_date'] = date_i18n( 'Y-m-d H:i:s', strtotime(' -1 day') );
		}
	}

	return apply_filters( 'wpcdt_pro_recurring_dates', $results, $timer_mode, $recurring_data );
}