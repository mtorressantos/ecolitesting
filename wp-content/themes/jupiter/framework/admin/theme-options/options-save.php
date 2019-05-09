<?php
/**
 * This class is responsible store all theme options data into database
 *
 * @author       Bob Ulusoy & Reza Marandi <ross@artbees.net>
 * @copyright    Artbees LTD (c)
 *
 * @link         http://artbees.net
 */
class MK_Theme_Options_Save {

	private $queue_size;
	private $revision_name;
	function __construct() {
		add_action( 'wp_ajax_mk_theme_save', array( &$this, 'decision_maker' ) );
		add_action( 'wp_ajax_mk_restore_theme_option_revision', array( &$this, 'restore_theme_options_revision' ) );
		add_action( 'wp_ajax_mk_list_theme_option_revision', array( &$this, 'list_theme_options_revision' ) );

		// Theme option revision's queue size
		$this->queue_size    = 10;
		$this->revision_name = THEME_OPTIONS . '_revision_';
	}
	/**
	 * This method is responsible to decide wether user wants to save theme option or reset or import.
	 *
	 * @author       Bob Ulusoy & Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 */
	public function decision_maker() {
		check_ajax_referer( 'mk-ajax-theme-options', 'security' );

		$data           = $_POST;
		$button_clicked = $data['button_clicked'];
		if ( $button_clicked ) {
			Mk_Static_Files::update_global_assets_timestamp();
		}
		switch ( $button_clicked ) {
			case 'save_theme_options_top':
				$this->save_theme_options( $data );
				break;
			case 'save_theme_options_bottom':
				$this->save_theme_options( $data );
				break;
			case 'reset_theme_options':
				$this->reset_theme_options( $data );
				break;
			case 'import_theme_options':
				$this->import_theme_options( $data );
				break;
			default:
				// code...
				break;
		}
	}
	/**
	 * This method is responsible to store theme option's data into database
	 *
	 * @author       Bob Ulusoy & Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 *
	 * @param array   $theme_options_data Data that will be pass by $_POST to system will hand to this method
	 * @param boolean $save_revision If we are recovering revision in system we must not create another revision
	 *
	 * @return array array of message that will be created through $this->message() method
	 */
	public function save_theme_options( $theme_options_data, $save_revision = true ) {
		// Remove unnecessary data
		unset( $theme_options_data['security'], $theme_options_data['action'] );
		$theme_options_data = is_array( $theme_options_data ) ? array_map( 'stripslashes_deep', $theme_options_data ) : stripslashes( $theme_options_data );

		if ( empty( $theme_options_data ) ) {
			$response = [
				'msg' => 'Not saved',
				'status' => false,
				'data' => [
					'element' => 'mk-not-saved',
				],
			];
		}

		if ( update_option( THEME_OPTIONS, $theme_options_data ) && update_option( 'mk_jupiter_flush_rules', 1 ) ) {
			if ( $save_revision ) {
				$this->save_theme_options_revision( $theme_options_data );
			}
			update_option( THEME_OPTIONS . '_backup', $theme_options_data ); // This line must be remove because of adding revision feature
			update_option( THEME_OPTIONS_BUILD, uniqid() );
			$response = [
				'msg' => 'Successfull',
				'status' => true,
				'data' => [
					'element' => 'mk-success-save',
					'theme_export_options' => base64_encode( serialize( $theme_options_data ) ),
				],
			];
		} else {
			$response = [
				'msg' => 'You have already saved these settings.',
				'status' => false,
				'data' => [
					'element' => 'mk-already-saved',
				],
			];
		}

		$static = new Mk_Static_Files( false );
		$static->DeleteThemeOptionStyles( true );
		$this->message( $response['msg'], $response['status'] , $response['data'] );

	}

	/**
	 * Reset theme options to default state.
	 *
	 * @since 5.9.5 Add action to delete HB Activation Warning log.
	 */
	public function reset_theme_options() {
		delete_option( THEME_OPTIONS );
		delete_option( 'jupiter_hb_activation_warning' );
		update_option( THEME_OPTIONS_BUILD, uniqid() );
		$static = new Mk_Static_Files( false );
		$static->DeleteThemeOptionStyles( true );
		$this->message(
			'Successfull' , false , [
				'element' => 'mk-success-reset',
				'reload' => true,
			]
		);
	}

	public function import_theme_options( $theme_options_data ) {
		$import_data             = $_POST['theme_import_options'];
		$import_data             = base64_decode( $import_data );
		$import_data_unserilized = $import_data ? unserialize( $import_data ) : false;

		if ( is_array( $import_data_unserilized ) && empty( $import_data_unserilized ) ) {
			$this->message(
				'Successfull' , false , [
					'element' => 'mk-fail-import',
					'modal' => true,
				]
			);
		}
		if ( update_option( THEME_OPTIONS, $import_data_unserilized ) ) {
			update_option( THEME_OPTIONS_BUILD, uniqid() );
			$static = new Mk_Static_Files( false );
			$static->DeleteThemeOptionStyles( true );
			$this->message(
				'Successfull' , true , [
					'element' => 'mk-success-import',
					'reload' => true,
				]
			);
		} else {
			$this->message(
				'Successfull' , false , [
					'element' => 'mk-fail-import',
					'modal' => true,
				]
			);
		}
	}
	/**
	 * This method is responsible to store one copy of theme options data as revision into database
	 *
	 * @author       Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 *
	 * @param array $theme_options_data Data that will be pass by $_POST to system will hand to this method
	 *
	 * @return array array of message that will be created through $this->message() method
	 */
	public function save_theme_options_revision( $theme_options_data ) {
		$theme_options_data = base64_encode( serialize( $theme_options_data ) );
		if ( ! $this->check_revision_storage() ) {
			$this->message( 'Revision storage have unpredictable problem , Please contact our support team.', false );
		}
		$revision_name = $this->revision_name . time();
		update_option( $revision_name, $theme_options_data );
	}
	/**
	 * This method will return list of revision that already exist in database to front-end
	 *
	 * @author       Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 *
	 * @return array array of message that will be created through $this->message() method
	 */
	public function list_theme_options_revision() {
		global $wpdb;
		$query       = "SELECT * FROM $wpdb->options WHERE option_name like '$this->revision_name%'";
		$db_response = $wpdb->get_results( $query );
		$response    = [];
		array_walk(
			$db_response, function ( $val ) use ( &$response ) {
				$val        = explode( '_', $val->option_name );
				$val        = date( 'Y-m-d H:i:s', end( $val ) );
				$response[] = $val;
			}
		);
		$this->message( 'Successfull', true, $response );
		return true;
	}

	/**
	 * This method is responsible to restore specific version of backed up theme option's data .
	 *
	 * @author       Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 *
	 * @return array array of message that will be created through $this->message() method
	 */
	public function restore_theme_options_revision() {
		global $wpdb;
		if ( ! isset( $_POST['revision_name'] ) || empty( $_POST['revision_name'] ) ) {
			$this->message( 'Revision name is empty', false );
			return false;
		}
		$revision_name = $this->revision_name . strtotime( $_POST['revision_name'] );
		$query         = "SELECT option_value as val FROM $wpdb->options WHERE option_name = '$revision_name'";
		$db_response   = $wpdb->get_row( $query );
		if ( ! is_object( $db_response ) || count( $db_response ) != 1 ) {
			$this->message( 'Can not find this revision in database', false );
			return false;
		}

		$theme_option_data = unserialize( base64_decode( $db_response->val ) );
		if ( empty( $theme_option_data ) ) {
			$this->message( 'Option`s structure is invalid', false );
			return false;
		}

		$this->save_theme_options( $theme_option_data, false );
	}
	/**
	 * This method will check if revision queue have empty space or not , if storage is full will delete the first revisions
	 *
	 * @author       Reza Marandi <ross@artbees.net>
	 * @copyright    Artbees LTD (c)
	 * @link         http://artbees.net
	 *
	 * @return boolean true if its alright to save another and false if queue is not ready because of a reason
	 */
	public function check_revision_storage() {
		global $wpdb;
		$query       = "SELECT COUNT(*) as count FROM $wpdb->options WHERE option_name like '$this->revision_name%'";
		$db_response = $wpdb->get_row( $query );
		if ( $db_response->count < $this->queue_size ) {
			// Queue have empty space
			return true;
		}

		// Queue is full and it need to delete first revisions that come to queue
		// It will remove exceeded records plus one more space for new backup
		$how_many_records = ( (int) $db_response->count - $this->queue_size) + 1;
		$query            = "SELECT option_id as id FROM $wpdb->options WHERE option_name like '$this->revision_name%' ORDER BY option_name ASC LIMIT $how_many_records";
		$db_response      = $wpdb->get_results( $query );
		$remove_records   = implode(
			',', array_map(
				function ( $val ) {
					return $val->id;
				}, $db_response
			)
		);

		// Removing first elements that come to queue
		$response = $wpdb->query( "DELETE FROM $wpdb->options WHERE option_id IN ($remove_records)" );
		if ( false === $response ) {
			return false;
		}

		return true;
	}

	/**
	 * this method is resposible to manage all the classes messages and act different on ajax mode or test mode
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str   $message for example ("Successfull")
	 * @param bool  $status true or false
	 * @param mixed $data its for when ever you want to result back an array of data or anything else
	 */
	public function message( $message, $status = true, $data = null ) {
		$response = array(
			'message'       => mk_logic_message_helper( 'theme-options', $message ),
			'status'        => $status,
			// Its a patch for wp_ajax object to define wether action was successfull or not
			'success'       => $status,
			'data'          => $data,
		);
		header( 'Content-Type: application/json' );
		wp_die( json_encode( $response ) );
		return true;
	}

}
global $abb_phpunit;
if ( empty( $abb_phpunit ) || $abb_phpunit == false ) {
	new MK_Theme_Options_Save();
}
