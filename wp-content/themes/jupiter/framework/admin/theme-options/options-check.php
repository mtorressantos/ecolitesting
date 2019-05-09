<?php

/**
 * Check current options and compare with the latest option from previous session. Only runs for
 * Ajax request.
 *
 * @author      Ayub Adiputra <ayub@artbees.net>
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.6
 * @package     artbees
 */

class Mk_Check_Theme_Options {

	/**
	 * Constructor function to add Ajax action for check current options and comparasion.
	 */
	function __construct() {
		// Ajax action.
		add_action( 'wp_ajax_mk_current_theme_options', array( &$this, 'current_theme_options' ) );
		add_action( 'wp_ajax_mk_compare_theme_options', array( &$this, 'compare_theme_options' ) );
		add_action( 'wp_ajax_mk_lock_theme_options', array( &$this, 'lock_theme_options' ) );

				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );
	}

		/**
		 * Enqueue sweet alert plugin style and script
		 *
		 * @param string $hook  Hook suffix for the current admin page.
		 */
	function enqueue( $hook ) {
		// Load only on Theme Options page
		if ( $hook != 'jupiter_page_theme_options' ) {
			return;
		}

				wp_enqueue_style( 'control-panel-modal-plugin', THEME_CONTROL_PANEL_ASSETS . '/css/sweetalert.css' );
		wp_enqueue_script( 'control-panel-sweet-alert', THEME_CONTROL_PANEL_ASSETS . '/js/sweetalert.min.js', array( 'jquery' ) );
	}

		/**
		 * Check current theme options when Jupiter Theme Options page loaded.
		 */
	public function current_theme_options() {
		$current_options = get_option( THEME_OPTIONS, array() );
		$this->message( 'Initialize theme options data.', true, $current_options );
	}

	/**
	 * Compare current theme options with the latest theme options from the previous session.
	 *
	 * @return bool False, to stop function execution.
	 */
	public function compare_theme_options() {
		try {
			$latest_options  = get_option( THEME_OPTIONS, array() );
			$current_options = ( isset( $_POST['options'] ) ? json_decode( stripslashes( $_POST['options'] ), true ) : array() );

			// Check if the options are in array format.
			if ( ! is_array( $latest_options ) || ! is_array( $current_options ) ) {
				throw new Exception( 'Both of the options should be in array format.' );
			}

			// Check if the latest_options is not empty.
			if ( empty( $latest_options ) && ! empty( $current_options ) ) {
				$this->message(
					'Theme options have been reset.', true, array(
						'reload' => true,
					)
				);
			} elseif ( ! empty( $latest_options ) && empty( $current_options ) ) {
				$this->message(
					'Theme options have been set.', true, array(
						'reload' => true,
					)
				);
			} elseif ( empty( $latest_options ) ) {
				throw new Exception( 'Theme options are empty.' );
			}

			// Check the differences between latest and previous options.
			$latest_ser  = array_map( 'serialize', $latest_options );
			$current_ser = array_map( 'serialize', $current_options );
			$result      = array_map( 'unserialize', array_diff_assoc( $latest_ser, $current_ser ) );

			if ( empty( $result ) ) {
				throw new Exception( 'Theme options are up to date.' );
			}

			// Get all changed fields.
			$fields = $this->fields_theme_options( $result );

			if ( empty( $fields ) ) {
				throw new Exception( "Can't get all the fields or null." );
			}

			$data = array(
				'fields'  => $fields,
				'result'  => $result,
				'options' => $latest_options,
			);

			$this->message( 'Get latest theme options.', true, $data );

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/**
	 * Get theme options fields such as type and value.
	 *
	 * @param  array $result The changed theme options.
	 * @return array         The changed theme options type and value.
	 */
	private function fields_theme_options( $result = array() ) {

		if ( ! is_array( $result ) ) {
			return null;
		}

		// Collect the theme options id.
		$field_id = implode( '|', array_keys( $result ) );

		// Get all theme options settings in string.
		$page     = include_once THEME_ADMIN . '/theme-options/masterkey.php';
		$page_opt = ( ! empty( $page['options'] ) ) ? $page['options'] : array();
		$json_opt = json_encode( $page_opt );

		// Find the patterns.
		$preg_pro = preg_match_all( '/\{[^\}\[]+id\"\:\"(' . $field_id . ')\b(.*?)[^\{|^\]]+((\}(?=\,\{))|(\}(?!\,\")))/s', $json_opt, $results );
		$patterns = ( ! empty( $results[0] ) ) ? $results[0] : null;

		if ( empty( $patterns ) ) {
			return null;
		}

		$field_options = array();
		foreach ( $patterns as $key => $value ) {
			$value = json_decode( $value, true );
			if ( ! empty( $value['id'] ) && ! empty( $value['type'] ) ) {
				$field_options[ $value['id'] ]['type']  = $value['type'];
				$field_options[ $value['id'] ]['value'] = $result[ $value['id'] ];
			}
		}

		return $field_options;
	}

		/**
		 * Update and refresh Theme Options lock.
		 *
		 * @return json Contains different data for Theme Options lock.
		 */
	public function lock_theme_options() {

		check_ajax_referer( 'mk_admin', 'security' );

		$refresh                    = $_POST['refresh'];
		$expiration                 = apply_filters( 'mk_theme_options_lock_expiration', 60 ); // Between 15 to 60 seconds
		$current_user               = wp_get_current_user();
		$current_user_login         = $current_user->user_login;
		$mk_theme_opt_lock      = get_option( '_mk_theme_options_lock' );
		$mk_theme_opt_lock_t = ( ! empty( $mk_theme_opt_lock['time'] ) ) ? $mk_theme_opt_lock['time'] : '';
		$mk_theme_opt_lock_u = ( ! empty( $mk_theme_opt_lock['user'] ) ) ? $mk_theme_opt_lock['user'] : '';

				// Refresh lock if current user is same as lock user or it's a refresh request.
		if ( $refresh === true || $current_user_login == $mk_theme_opt_lock_u ) {
			update_option(
				'_mk_theme_options_lock', array(
					'time' => time(),
					'user' => $current_user_login,
				), true
			);

						$this->message(
							'Theme Options lock is resfreshed.', false, array(
								'expiration' => $expiration,
							)
						);
		}

				// Check if Theme Options has been updated since the expiration time.
		if ( $mk_theme_opt_lock_t > ( time() - $expiration ) ) {
			$this->message(
				'Theme Options is locked.', true, array(
					'title'       => __( 'Theme Options is currently locked.', 'mk_framework' ),
					'text'        => sprintf( __( '<strong>%1$s</strong> (another user) is currently configuring Theme Options therefore you are locked to prevent conflicts. To unlock, ask <strong>%2$s</strong> to close all open Theme Options pages then reload the page after %3$s seconds.', 'mk_framework' ), $mk_theme_opt_lock_u, $mk_theme_opt_lock_u, $expiration ),
					'button_text' => __( 'Reload', 'mk_framework' ),
				)
			);
		}

				// Update lock for the 1st time or after the expiration time.
		update_option(
			'_mk_theme_options_lock', array(
				'time' => time(),
				'user' => $current_user_login,
			), true
		);

				$this->message(
					'Theme Options is unlocked.', false, array(
						'expiration' => $expiration,
					)
				);
	}

	/**
	 * Return response contains debug message, status, and data.
	 *
	 * @param  string $message Message for debugging.
	 * @param  bool   $status  Process status.
	 * @param  mixed  $data    Return data from process.
	 * @return json            Contain message, status, and returned data.
	 */
	public function message( $message, $status, $data = null ) {
		$response = array(
			'message' => $message,
			'status'  => $status,
			'data'    => $data,
		);
		header( 'Content-Type: application/json' );
		wp_die( json_encode( $response ) );
	}

}

new Mk_Check_Theme_Options();
