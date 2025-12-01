<?php
/*
 * Database Upgarade File
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Database Update Notice
 * 
 * @since 1.4
 */
function wpcdt_pro_db_update_notice() {

	global $current_screen;

	// Get Some Variables
	$plugin_version = get_option( 'wpcdt_plugin_version' );
	$screen_id		= isset( $current_screen->id ) ? $current_screen->id : '';

	if( version_compare( $plugin_version, '1.1' ) < 0 && $screen_id != 'wpcdt_countdown_page_wpcdt-pro-db-update' ) {

		// Taking metabox prefix
		$prefix = WPCDT_PRO_META_PREFIX;

		// WP Query Parameters
		$args = array (
			'posts_per_page'		=> 1,
			'post_type'				=> WPCDT_PRO_POST_TYPE,
			'post_status'			=> array( 'publish' ),
			'fields'				=> 'ids',
			'no_found_rows'			=> true,
			'ignore_sticky_posts'	=> true,
			'meta_query'			=> array(
												array(
													'key'		=> $prefix.'design',
													'value'		=> '',
													'compare'	=> 'NOT EXISTS',
												),
											)
		);

		// WP Query
		$timer_post = get_posts( $args );

		// If Old Timer Post Found
		if( ! empty( $timer_post ) ) {

			$update_url = add_query_arg( array( 'post_type' => WPCDT_PRO_POST_TYPE, 'page' => 'wpcdt-pro-db-update' ), admin_url( 'edit.php' ) );

			echo '<div class="notice notice-error">
					<p><strong>'. esc_html__('Countdown Timer Ultimate Pro database update required.', 'countdown-timer-ultimate'). '</strong></p>
					<p><strong>'. esc_html__('Countdown Timer Ultimate Pro has been updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'countdown-timer-ultimate'). '</strong></p>
					<p><a class="button button-primary" href="'.esc_url( $update_url ).'">'.esc_html__('Update Database', 'countdown-timer-ultimate').'</a></p>
				</div>';
		}
	}
}

// Action to display DB update notice
add_action( 'admin_notices', 'wpcdt_pro_db_update_notice');

/**
 * Function to register database upgrade page
 * 
 * @since 1.4
 */
function wpcdt_pro_db_update_page() {

	// Registring Database Update Page
	add_submenu_page( 'edit.php?post_type='.WPCDT_PRO_POST_TYPE, __('Update Database - Countdown Timer Ultimate', 'countdown-timer-ultimate'), "<span style='color:#FCB214;'>".__('Update Database', 'countdown-timer-ultimate')."</span>", 'edit_posts', 'wpcdt-pro-db-update', 'wpcdt_pro_db_update_page_html' );
}
add_action( 'admin_menu', 'wpcdt_pro_db_update_page', 35 );

/**
 * Function to handle database update process
 * 
 * @since 1.4
 */
function wpcdt_pro_db_update_page_html() { ?>

	<div class="wrap">
		<h2>
			<?php esc_html_e( 'Update Database - Countdown Timer Ultimate', 'countdown-timer-ultimate' ); ?>
		</h2>

		<div class="wpcdt-db-update-result-wrp">
			<p><?php esc_html_e('Countdown Timer Ultimate Pro has been updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'countdown-timer-ultimate'); ?></p>
			<p><?php esc_html_e('Database update process has been started.', 'countdown-timer-ultimate'); ?></p>
			<p><?php esc_html_e('Kindly do not refresh the page or close the browser.', 'countdown-timer-ultimate'); ?></p>
		</div>
		<div class="wpcdt-db-update-result-percent"></div>
	</div>

	<script type="text/javascript">
		
		/* DB upgrade function */
		function wpcdt_pro_process_db_update( data ) {

			if( ! data ) {
				var data = {
					action			: 'wpcdt_pro_data_migrate',
					page			: 1,
					count			: 0,
					nonce			: "<?php echo wp_create_nonce( 'wpcdt-db-update' ); ?>",
				};
			}

			jQuery.post( ajaxurl, data, function( response ) {
				
				if( response.status == 0 ) {

					jQuery('.wpcdt-db-update-result-wrp').append( response.message );

				} else {

					jQuery('.wpcdt-db-update-result-wrp').append( response.result_message );
					jQuery('.wpcdt-db-update-result-percent').html( response.percentage_msg );

					/* If data is there then process again */
					if( response.data_process != 0 && ( response.data_process < response.total_count ) ) {
						data['page']            = response.page;
						data['total_count']     = response.total_count;
						data['data_process']    = response.data_process;

						wpcdt_pro_process_db_update( data );
					}

					/* If process is done */
					if( response.data_process >= response.total_count && response.url ) {
						window.location = response.url;
					}
				}
			});
		}

		wpcdt_pro_process_db_update();
	</script>
	<?php
}

/**
 * Function to get countdown timer count
 * 
 * @since 1.4
 */
function wpcdt_pro_data_migrate() {

	// Taking some defaults
	$result = array(
						'status'			=> 0,
						'result_message'	=> '',
						'message'			=> __('Sorry, Something happened wrong.', 'countdown-timer-ultimate'),
					);

	$limit			= 10;
	$count			= 0;
	$prefix			= WPCDT_PRO_META_PREFIX;
	$page			= ! empty( $_POST['page'] )			? wpcdt_pro_clean_number( $_POST['page'] )			: 1;
	$data_process	= ! empty( $_POST['data_process'] )	? wpcdt_pro_clean_number( $_POST['data_process'] )	: 0;
	$nonce			= isset( $_POST['nonce'] )			? wpcdt_pro_clean( $_POST['nonce'] )				: '';

	// Verify Nonce
	if( wp_verify_nonce( $nonce, 'wpcdt-db-update' ) ) {

		$args = array(
			'post_type' 		=> WPCDT_PRO_POST_TYPE,
			'post_status'		=> array( 'publish' ),
			'fields'			=> 'ids',
			'order' 			=> 'DESC',
			'orderby' 			=> 'date',
			'paged'				=> $page,
			'posts_per_page' 	=> $limit,
			'meta_query'		=> array(
											array(
												'key'		=> $prefix.'design',
												'value'		=> '',
												'compare'	=> 'NOT EXISTS',
											),
										)
		);
		$timer_query	= new WP_Query( $args );
		$total_count	= $timer_query->found_posts;

		if( $page < 2 ) {
			$result['result_message'] .= '<p>'.sprintf( __( 'Total %d Countdown Timer Post Found.', 'countdown-timer-ultimate' ), $total_count ).'</p>';
		}

		if( ! empty( $timer_query->posts ) ) {
			foreach ($timer_query->posts as $timer_post_key => $timer_id) {

				$count++;
				$content_meta 	= get_post_meta( $timer_id, $prefix.'content', true );
				$design_meta 	= get_post_meta( $timer_id, $prefix.'design', true );

				// Content Meta
				if( empty( $content_meta ) ) {

					$content['tab'] 				= '#wpcdt_content_sett';
					$content['content_position'] 	= 'above_timer';
					$content['timer_day_text'] 		= get_post_meta( $timer_id, $prefix.'timer_day_text', true );
					$content['timer_hour_text'] 	= get_post_meta( $timer_id, $prefix.'timer_hour_text', true );
					$content['timer_minute_text'] 	= get_post_meta( $timer_id, $prefix.'timer_minute_text', true );
					$content['timer_second_text'] 	= get_post_meta( $timer_id, $prefix.'timer_second_text', true );
					$content['completion_text'] 	= get_post_meta( $timer_id, $prefix.'completion_text', true );
					$content['show_title'] 			= get_post_meta( $timer_id, $prefix.'show_title', true );
					$content['is_timerdays'] 		= get_post_meta( $timer_id, $prefix.'is_timerdays', true );
					$content['is_timerhours'] 		= get_post_meta( $timer_id, $prefix.'is_timerhours', true );
					$content['is_timerminutes'] 	= get_post_meta( $timer_id, $prefix.'is_timerminutes', true );
					$content['is_timerseconds'] 	= get_post_meta( $timer_id, $prefix.'is_timerseconds', true );

					update_post_meta( $timer_id, $prefix.'content', $content );
				}

				// Design Meta
				if( empty( $design_meta ) ) {

					$design['background_pref'] 	= get_post_meta( $timer_id, $prefix.'background_pref', true );
					$design['font_clr'] 		= get_post_meta( $timer_id, $prefix.'font_clr', true );
					$design['timertext_color'] 	= get_post_meta( $timer_id, $prefix.'timertext_color', true );
					$design['timerdigit_color'] = get_post_meta( $timer_id, $prefix.'timerdigit_color', true );

					// Circle Style 1 Meta
					$design['timercircle_animation']		= get_post_meta( $timer_id, $prefix.'timercircle_animation', true );
					$design['timer_width'] 					= get_post_meta( $timer_id, $prefix.'timer_width', true );
					$design['timercircle_width'] 			= get_post_meta( $timer_id, $prefix.'timercircle_width', true );
					
					// Update Circle Style 2 Meta
					$design['timercircle2_width'] 			= get_post_meta( $timer_id, $prefix.'timercircle2_width', true );

					// Clock Background Colors
					$design['timerbackground_width'] 		= get_post_meta( $timer_id, $prefix.'timerbackground_width', true );
					$design['timerbackground_color'] 		= get_post_meta( $timer_id, $prefix.'timerbackground_color', true );
					$design['timerdaysbackground_color']	= get_post_meta( $timer_id, $prefix.'timerdaysbackground_color', true );
					$design['timerhoursbackground_color']	= get_post_meta( $timer_id, $prefix.'timerhoursbackground_color', true );
					$design['timerminutesbackground_color']	= get_post_meta( $timer_id, $prefix.'timerminutesbackground_color', true );
					$design['timersecondsbackground_color']	= get_post_meta( $timer_id, $prefix.'timersecondsbackground_color', true );					
					
					update_post_meta( $timer_id, $prefix.'design', $design );
				}

				// Timer Type
				update_post_meta( $timer_id, $prefix.'timer_type', 'content' );
			}

			// Record total process data
			$data_process = ( $data_process + $count );

			// Calculate percentage
			$percentage = 100;

			if( $total_count > 0 ) {
				$percentage = ( ( $limit * $page ) / $total_count ) * 100;
			}

			if( $percentage > 100 ) {
				$percentage = 100;
			}

			/* If process is done */
			if( $data_process >= $total_count ) {

				// Update plugin db version to latest
				update_option( 'wpcdt_plugin_version', '1.1' );

				$result['url'] = add_query_arg( array( 'post_type' => WPCDT_PRO_POST_TYPE, 'message' => 'wpcdt-db-update' ), admin_url('edit.php') );
			}

			$result['status']			= 1;
			$result['total_count'] 		= $total_count;
			$result['data_process']		= $data_process;
			$result['percentage'] 		= $percentage;
			$result['page']				= ( $page + 1 );
			$result['percentage_msg'] 	= sprintf( __('Percentage Completed : %d', 'countdown-timer-ultimate'), $percentage );

		} else {

			$result['message'] = __('All looks good. No old records found.', 'countdown-timer-ultimate');
		}
	}

	wp_send_json( $result );
}

// Database Upgrade Action
add_action( 'wp_ajax_wpcdt_pro_data_migrate', 'wpcdt_pro_data_migrate' );