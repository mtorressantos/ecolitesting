<?php
/**
 * This file contains only mk_live_support class.
 *
 * @package jupiter/framework/admin/general
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * This class is responsible for live support of VIP users.
 * It communicates with Artbees Portal and checks if user eligible for VIP Live Support.
 *
 * @author       Ugur Mirza Zeyrek <mirza@artbees.net>
 * @copyright    Artbees LTD (c)
 *
 * @link         http://artbees.net
 * @since        5.9.5
 * @version      1.0
 */
class Mk_Live_Support {
	/**
	 * Logger for errors.
	 *
	 * @var object.
	 */
	private $logger;

	/**
	 * Time interval for checking vip user status from Artbees Portal.
	 *
	 * @var string.
	 */
	private $check_time_interval = '1';
	/*====================== MAIN SECTION ============================*/

	/**
	 * Mk_Live_Support constructor.
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 */
	public function __construct() {
		add_action( 'admin_footer', array( &$this, 'enqueue_live_support_script' ), 100 );
		$this->logger = new Devbees\BeesLog\logger();
	}

	/**
	 * Returns true if current logged-in user to the admin section eligible for live support verification.
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @since       Version 6.1 Added Envato Hosted condition.
	 * @return      bool
	 */
	private function eligible_for_live_support_check() {
		if ( defined( 'ENVATO_HOSTED_SITE' ) ) {
			return false;
		}

		if ( ! $this->get_api_key() ) {
			return false;
		}

		if ( ! $this->user_level_check() ) {
			return false;
		}

		if ( ! $this->is_live_support_active() ) {
			return false;
		}

		return true;
	}

	/**
	 * This method is responsible to check active api-key on jupiter and return it
	 *
	 * @return mixed return false if api-key is not exists and return api-key itself if its exist
	 *
	 * @author      Ross Marandi & Ugur Mirza Zeyrek
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 */
	private function get_api_key() {
		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}
		return $api_key;
	}

	/**
	 * This method is responsible to create http body and post request to ATP and evaluate response
	 *
	 * @return mixed it will returl JSON if response is okay and false if there is a problem on connection
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @since       6.1.0 Rename function and transient name.
	 * @since       6.1.2 Replace how to set theme version.
	 */
	private function request_user_info() {
		$transient_name = 'mk_request_user_info_' . $this->get_api_key();
		$return_result = get_transient( $transient_name );
		if ( $return_result ) {
			return $return_result;
		}
		$url  = 'https://themes.artbees.net/wp-admin/admin-ajax.php';

		$args = array(
			'method'     => 'POST',
			'blocking'   => true,
			'timeout'    => 45,
			'headers'    => array(
				'Content-Type'    => 'application/x-www-form-urlencoded;charset=UTF-8',
			),
			'body'       => array(
				'action'          => 'abb_get_vip_user_by_api_key',
				'api_key'         => $this->get_api_key(),
				'jupiter_version' => THEME_VERSION,
			),
		);

		$response = wp_remote_post( $url, $args );
		// Check if Artbees Portal Response has Error.
		if ( is_wp_error( $response ) ) {
			$this->logger->debug( 'Live Support - Error in response', $response->get_error_message() );
			return false;
		}

		$result = wp_remote_retrieve_body( $response );
		try {
			$return_result = json_decode( $result, true );
		} catch ( Exception $exception ) {
			// Check if response is not encoded in JSON.
			$this->logger->debug( 'Live Support - Error in decoding', $exception->getMessage() );
			return false;
		}
			$seconds = 3600;
		if ( defined( 'MK_DEV' ) && true === MK_DEV ) {
			$seconds = 1;
		}

		set_transient( $transient_name,  $return_result, $seconds * $this->check_time_interval );
		return $return_result;
	}

	/**
	 * Returns true if user not disabled live support and sets live support status if get parameter provided.
	 *
	 * @return boolean false if disabled.
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @last_update Version 5.9.5
	 */
	private function is_live_support_active() {
		wp_verify_nonce( 'set_mk_live_support', 'nonce' );
		if ( isset( $_GET['set_mk_live_support'] ) ) {
			if ( '1' === $_GET['set_mk_live_support'] ) {
				update_option( 'mk_live_support', true );
			}
			if ( '0' === $_GET['set_mk_live_support'] ) {
				update_option( 'mk_live_support', false );
			}
		}
		return get_option( 'mk_live_support', true );
	}

	/**
	 * Checks if current user has administrator privileges or not. Multi-site compatible.
	 *
	 * @return boolean true if current user is admin and false if not.
	 *
	 * @author      Ross Marandi & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @last_update Version 5.9.5
	 */
	private function user_level_check() {
		if ( current_user_can( 'administrator' ) || current_user_can( 'super_admin' ) ) {
			return true;
		}
		$user = wp_get_current_user();
		if ( $user->user_level > 9 ) {
			return true;
		}
		return false;
	}

	/**
	 * Enqueue live support script and inject variables in it. (it call by admin_footer hook )
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @since       6.1.0 Remove all name and condtions related to vip_user.
	 * @since       6.1.2 Add Jupiter version on Intercom script.
	 * @last_update Version 5.9.5
	 */
	public function enqueue_live_support_script() {
		if ( $this->eligible_for_live_support_check() ) {
			$user_info = $this->user_info();

			// Avoid cache isse with updated Jupiter version.
			wp_enqueue_script( 'live-support', THEME_ADMIN_ASSETS_URI . '/js/intercom.js', array( 'jquery' ), THEME_VERSION );

			$support_active = isset( $user_info['support_active'] ) ? $user_info['support_active'] : false;
			$user_status    = isset( $user_info['status'] ) ? $user_info['status'] : false;

			// If user VIP status is true or INTERCOM_SUPPORT_ACTIVE is enabled.
			if ( true === $user_status || true === $support_active ) {
				wp_localize_script( 'live-support', 'mk_atp_user', $user_info );
				return true;
			}

			if ( ! isset( $user_info['status'] ) ) {
				$script = 'console.log("Live Support not available : An unknown error occurred.");';
				wp_add_inline_script( 'live-support', $script );
			}
		}

		return false;
	}

	/**
	 * Returns necessary variables for vip customer.
	 *
	 * @return array
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.5
	 * @since       6.1.0 Rename function name and remove checking related to vip user.
	 */
	private function user_info() {
		$user_info = $this->request_user_info();
		if ( $user_info && isset( $user_info['status'] ) ) {
			update_option( 'mk_atp_user', true );
		}
		return $user_info;
	}

}


global $abb_phpunit;
if ( empty( $abb_phpunit ) || false === $abb_phpunit ) {
	new Mk_Live_Support();
}
