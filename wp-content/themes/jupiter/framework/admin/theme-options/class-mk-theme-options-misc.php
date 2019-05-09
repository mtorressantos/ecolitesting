<?php
/**
 * Theme Options: MK_Theme_Options_Misc class.
 *
 * @package MK_Theme_Options
 * @subpackage MK_Theme_Options_Misc
 * @since 5.9.5
 */

/**
 * This class contain all additional actions on Theme Options.
 *
 * @since 5.9.5
 */
class MK_Theme_Options_Misc {

	/**
	 * Load all required actions.
	 */
	function __construct() {
		add_action( 'wp_ajax_mk_get_site_info', array( &$this, 'get_site_info' ) );
		add_action( 'wp_ajax_mk_save_user_activate_hb', array( &$this, 'save_user_activate_hb' ) );
	}

	/**
	 * Get all necessary info from current site.
	 *
	 * @since 5.9.5
	 *
	 * $return boolean The status of return function.
	 */
	public function get_site_info() {
		// Is user activated the HB.
		$is_activated = $this->is_user_activated_hb();
		$data['is_user_activated_hb'] = ( ! empty( $is_activated['status'] ) ) ? $is_activated['status'] : false;
		$data['user_activated_data'] = ( ! empty( $is_activated['user'] ) ) ? $is_activated['user'] : null;

		// Get site current site URL.
		$data['site_url'] = home_url();

		// Return all the data.
		$this->message( 'Site info for current site.', true, $data );
		return true;
	}

	/**
	 * Get the status is current has activated the HB before.
	 *
	 * @since 5.9.5
	 *
	 * @return array Current user status and data.
	 */
	public function is_user_activated_hb() {
		$data = array(
			'status' => false,
			'user' => null,
		);

		// Get all listed users and current user data.
		$users = get_option( 'jupiter_hb_activation_warning', array() );
		$curr_user = wp_get_current_user();

		// If user data is not empty.
		if ( ! empty( $curr_user->ID ) ) {
			// Check if the user is listed. Return true and current user data.
			if ( isset( $users[ $curr_user->ID ] ) ) {
				$data = array(
					'status' => true,
					'user' => $users[ $curr_user->ID ],
				);
				return $data;
			}
		}

		// If the user data is can't be found, return status false and empty user data.
		return $data;
	}

	/**
	 * Save the user data to activated users list.
	 *
	 * @since 5.9.5
	 */
	public function save_user_activate_hb() {
		// Get all listed users.
		$users = get_option( 'jupiter_hb_activation_warning', array() );

		$data = wp_get_current_user();

		// If user data is not empty.
		if ( ! empty( $data->ID ) && ! empty( $data->data->user_email ) ) {
			// Check if the user is listed. Return true and current user data.
			if ( isset( $users[ $data->ID ] ) ) {
				$this->message( 'User already activated the HB once before.', true, $users[ $data->ID ] );
				return true;
			}

			// If the user is not listed, save it.
			$users[ $data->ID ] = $data->data->user_email;
			if ( true === update_option( 'jupiter_hb_activation_warning', $users ) ) {
				$this->message( 'User is listed successfully.', true, $users[ $data->ID ] );
				return true;
			}

			$this->message( 'Failed to store user data on the list!', false, null );
			return false;
		}

		// If the user data is can't be found, return false.
		$this->message( "Can't access current user data!", false, null );
		return false;
	}

	/**
	 * This method is resposible to manage all the classes messages and act different on ajax mode or
	 * test mode.
	 *
	 * @param string  $message For example ("Successfull").
	 * @param boolean $status  true or false.
	 * @param mixed   $data    Its for when ever you want to result back an array of data or anything else.
	 *
	 * @since 5.9.5
	 */
	public function message( $message, $status, $data ) {
		$response = array(
			'message'       => mk_logic_message_helper( 'theme-options', $message ),
			'status'        => $status,
			// Its a patch for wp_ajax object to define wether action was successfull or not.
			'success'       => $status,
			'data'          => $data,
		);
		header( 'Content-Type: application/json' );
		wp_die( json_encode( $response ) );
		return true;
	}

}

global $abb_phpunit;
if ( empty( $abb_phpunit ) || false == $abb_phpunit ) {
	new MK_Theme_Options_Misc();
}
