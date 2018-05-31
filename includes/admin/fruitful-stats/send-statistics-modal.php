<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 31/05/18
 * Time: 13:00
 */


if ( class_exists( 'ReduxFramework' ) ) {
	/**
	 * Enqueue scripts for all admin pages
	 */
	add_action( 'admin_enqueue_scripts', 'zoner_add_admin_scripts' );
	function zoner_add_admin_scripts() {
		wp_enqueue_script( 'fruitful-stats-modal', get_template_directory_uri() . '/includes/admin/fruitful-stats/assets/js/admin_scripts.js', array( 'jquery' ) );
		wp_enqueue_style( 'fruitful-stats-modal-styles', get_template_directory_uri() . '/includes/admin/fruitful-stats/assets/styles/admin_styles.css' );
	}

	function zoner_shortcodes_admin_notice() {
		global $zoner_config;
		$options = $zoner_config;

		if ( $options['ffc_subscribe'] === '0' && empty( $options['ffc_is_hide_subscribe_notification'] ) ) {
			require get_template_directory(). '/includes/admin/fruitful-stats/view/send-statistics-modal-view.php';
		}
	}

	add_action( 'admin_footer', 'zoner_shortcodes_admin_notice' );


	add_action( 'wp_ajax_zoner_submit_modal', 'zoner_submit_modal' );
	function zoner_submit_modal() {

		global $zoner_config;
		$request_data = $_POST['data'];

		$response = array(
			'status'            => 'failed',
			'title'             => __( 'Uh oh!', 'zoner-lite' ),
			'error_message'     => __( 'Sorry, something went wrong, and we failed to receive the shared data from you.', 'zoner-lite' ),
			'error_description' => __( 'No worries; go to the theme option to enter the required data manually and save changes.', 'zoner-lite' ),
			'stat_msg'          => '',
			'subscr_msg'        => ''
		);


		if ( ! empty( $request_data ) ) {
			foreach ( $request_data as $option => $value ) {
				if ( isset( $zoner_config[ $option ] ) ) {
					Redux::setOption( 'zoner_config', $option, $value );
				}
			}
			Redux::setOption( 'zoner_config', 'ffc_is_hide_subscribe_notification', '1' );

			if ( $request_data['ffc_statistic'] === '1' || $request_data['ffc_subscribe'] === '1' ) {
				$response = array(
					'status'            => 'success',
					'title'             => __( 'Thank you!', 'zoner-lite' ),
					'error_message'     => '',
					'error_description' => '',
					'stat_msg'          => __( 'Thank you for being supportive, we appreciate your understanding and assistance!', 'zoner-lite' ),
					'subscr_msg'        => $request_data['ffc_subscribe'] === '1' ? __( "Don't forget to check your inbox for our latest letter - you’d like that!", 'zoner-lite' ) : ''
				);
			} else {
				$response = array(
					'status'            => 'success',
					'title'             => __( 'What a pity!', 'zoner-lite' ),
					'error_message'     => '',
					'error_description' => '',
					'stat_msg'          => __( 'We wish you could have shared your site statistic and joined our community.', 'zoner-lite' ),
					'subscr_msg'        => __( 'But if you ever change your mind, you can always do that in the theme options.', 'zoner-lite' )
				);
			}
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_zoner_dismiss_subscribe_notification', 'zoner_dismiss_subscribe_notification' );
	function zoner_dismiss_subscribe_notification() {
		Redux::setOption( 'zoner_config', 'ffc_is_hide_subscribe_notification', '1' );

		wp_send_json( 'success' );
	}
}
