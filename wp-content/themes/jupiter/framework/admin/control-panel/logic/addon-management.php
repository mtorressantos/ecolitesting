<?php
/**
 * This method will handle all actions about Artbees Add-on management.
 *
 * @author       Reza Marandi <ross@artbees.net>
 * @copyright    Artbees LTD (c)
 *
 * @link         http://artbees.net
 **/
class ValidateAddonException extends Exception {

}
class Mk_Addon_Management {

	private $validator;
	private $addon_slug;
	public function setAddonSlug( $addon_slug ) {
		$this->addon_slug = $addon_slug;
		return $this;
	}
	public function getAddonSlug() {
		return $this->addon_slug;
	}
	public function validateAddonSlug() {

	}

	private $addon_name;
	public function setAddonName( $addon_name ) {
		$this->addon_name = $addon_name;
		return $this;
	}
	public function getAddonName() {
		return $this->addon_name;
	}

	private $addon_directory;
	public function setAddonDirectory( $addon_directory ) {
		$this->addon_directory = $addon_directory;
		return $this;
	}
	public function getAddonDirectory() {
		return $this->addon_directory;
	}

	private $api_url;
	public function setApiURL( $api_url ) {
		$this->api_url = $api_url;
		return $this;
	}
	public function getApiURL() {
		return $this->api_url;
	}

	private $system_test_env;
	public function setSystemTestEnv( $system_test_env ) {
		$this->system_test_env = $system_test_env;
		return $this;
	}
	public function getSystemTestEnv() {
		return $this->system_test_env;
	}

	private $ajax_mode;
	public function setAjaxMode( $ajax_mode ) {
		$this->ajax_mode = $ajax_mode;
		return $this;
	}
	public function getAjaxMode() {
		return $this->ajax_mode;
	}

	private $message;
	public function setMessage( $message ) {
		$this->message = $message;
		return $this;
	}
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Construct.
	 * it will create/manage necessary actions on class load.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param bool $system_text_env if you want to create an instance of this method for phpunit it should be true
	 * @param bool $ajax_mode       if you need this method as ajax mode set true
	 */
	public function __construct( $system_test_env = false, $ajax_mode = true ) {

		$this->setAjaxMode( $ajax_mode )->setSystemTestEnv( $system_test_env );

		// Set Upload Directory
		$wp_upload_dir = wp_upload_dir();
		$this->setAddonDirectory( $wp_upload_dir['basedir'] . '/mk_addons/' );

		// Set API Server URL
		$this->setApiURL( V2ARTBEESAPI );

		// Create validator instance
		$this->validator = new Mk_Validator();

		// Set API Calls template
		$template = \Httpful\Request::init()
			->method( \Httpful\Http::GET )
			->withoutStrictSsl()
			->expectsJson()
			->addHeaders(
				array(
					'api-key' => get_option( 'artbees_api_key' ),
					'domain'  => $_SERVER['SERVER_NAME'],
				)
			);
		\Httpful\Request::ini( $template );
		if ( $ajax_mode === true ) {
			add_action( 'wp_ajax_abb_addon_lazy_load', array( &$this, 'abbAddonLazyLoad' ) );
			add_action( 'wp_ajax_abb_get_addons_categories', array( &$this, 'addonsCategories' ) );
			add_action( 'wp_ajax_abb_get_installed_addons', array( &$this, 'abbInstalledAddons' ) );
			add_action( 'wp_ajax_abb_activate_addon', array( &$this, 'abbActivateAddon' ) );
			add_action( 'wp_ajax_abb_deactivate_addon', array( &$this, 'abbDectivateAddon' ) );
			add_action( 'wp_ajax_abb_update_addon', array( &$this, 'abbUpdateAddon' ) );
		}

	} // END Construct

	/**
	 * method that is resposible to pass addon list to UI base on lazy load condition.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $_POST[from] from number
	 * @param str $_POST[count] how many ?
	 */
	public function abbAddonLazyLoad() {
		$this->setAddonName( isset( $_POST['addon_name'] ) ? $_POST['addon_name'] : '' );
		$from     = (isset( $_POST['from'] ) ? $_POST['from'] : null);
		$count    = (isset( $_POST['count'] ) ? $_POST['count'] : null);
		$category = (isset( $_POST['category'] ) ? $_POST['category'] : '');
		if ( is_null( $from ) || is_null( $count ) ) {
			$this->message( 'System problem , please call support', false );
			return false;
		}
		return $this->addonsList( $from, $count, $category );
	}
	public function abbInstalledAddons() {
		// update_needed
		$installed_addons = $this->getAddon();
		if ( count( $installed_addons ) < 1 ) {
			$this->message( 'Successfull', true, array() );
			return true;
		}
		$versions = $this->getAddonVersionFromAPI( array_column( $installed_addons, 'slug' ) );
		foreach ( $versions as $key => $current_version ) {
			$installed_version = $installed_addons[ $key ]['version'];
			if ( version_compare( $installed_version, $current_version, '<' ) === true ) {
				$installed_addons[ $key ]['update_needed'] = true;
				$installed_addons[ $key ]['api_version']   = $current_version;
			} else {
				$installed_addons[ $key ]['update_needed'] = false;
			}
		}

		$this->message( 'Successfull', true, $installed_addons );
		return true;
	}
	public function abbActivateAddon() {
		try {
			$addon_slug = (isset( $_POST['addon_slug'] ) ? $_POST['addon_slug'] : null);
			if ( is_null( $addon_slug ) ) {
				throw new Exception( 'System problem at installing , please call support' );
				return false;
			}

			return $this->setAddonSlug( $addon_slug )->install();
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function abbDectivateAddon() {
		try {
			$addon_slug = (isset( $_POST['addon_slug'] ) ? $_POST['addon_slug'] : null);
			if ( is_null( $addon_slug ) ) {
				throw new Exception( 'System problem at installing , please call support' );
				return false;
			}

			return $this->setAddonSlug( $addon_slug )->uninstall();
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function abbUpdateAddon() {
		try {
			$addon_slug = (isset( $_POST['addon_slug'] ) ? $_POST['addon_slug'] : null);
			if ( is_null( $addon_slug ) ) {
				throw new Exception( 'System problem at installing , please call support' );
				return false;
			}

			return $this->setAddonSlug( $addon_slug )->update();
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	/**
	 * method that is resposible to pass plugin list to UI base on lazy load condition.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $from from number
	 * @param str $count how many ?
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function install() {
		try {
			$response = $this->validator
				->setValue( $this->getAddonSlug() )
				->setFieldName( 'Add-on Slug' )
				->run( 'required:true,string:true,min_len:3' );
			if ( $response === false ) {
				throw new Exception( $this->validator->getMessage() );
			}

			Abb_Logic_Helpers::checkPermAndCreate( $this->getAddonDirectory() );
			$addon_download_link = $this->getAddOnDownloadLink( $this->getAddonSlug() );
			$addon_file_name     = $this->getAddOnFileName( $this->getAddonSlug() );
			$addon_source_file   = Abb_Logic_Helpers::uploadFromURL( $addon_download_link, $addon_file_name, $this->getAddonDirectory() );
			$addon_dir_path      = $this->getAddonDirectory() . $this->getAddonSlug() . '/';
			Abb_Logic_Helpers::unZip( $addon_source_file, $this->getAddonDirectory() );
			$this->validateAddonStructure( $addon_dir_path );
			Abb_Logic_Helpers::deleteFileNDir( $addon_source_file );

			$this->message( 'Add-on activated successfully', true );
			return true;
		} catch ( ValidateAddonException $e ) {
			Abb_Logic_Helpers::deleteFileNDir( realpath( $addon_dir_path ) );
			Abb_Logic_Helpers::deleteFileNDir( realpath( $addon_source_file ) );
			$this->message( $e->getMessage(), false );
			return false;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	public function uninstall() {
		try {
			$response = $this->validator
				->setValue( $this->getAddonSlug() )
				->setFieldName( 'Add-on Slug' )
				->run( 'required:true,string:true,min_len:3' );

			if ( $response === false ) {
				throw new Exception( $this->validator->getMessage() );
			}

			$addon_full_path = $this->getAddonFullPath( $this->getAddonSlug() );

			if ( $addon_full_path === false ) {
				throw new Exception( 'The Add-On you are looking for is not exist.' );
			}

			if ( is_writable( $addon_full_path ) == false ) {
				throw new Exception( 'The Add-On directory is not writable , Change the permission first.' );
			}

			$response = Abb_Logic_Helpers::deleteFileNDir( $addon_full_path );

			if ( $response === false ) {
				throw new Exception( 'The Add-On removal process was not successfull.' );
			}

			$this->message( 'Add-on removed successfully', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}
	public function update() {
		try {
			// Disable ajax mode if its enabled
			if ( $this->getAjaxMode() === true ) {
				$this->setAjaxMode( false );
				$ajax_mode_reverted = true;
			}

			$validator_response = $this->validator
				->setValue( $this->getAddonSlug() )
				->setFieldName( 'Add-on Slug' )
				->run( 'required:true,string:true,min_len:3' );

			if ( $validator_response === false ) {
				throw new Exception( $this->validator->getMessage() );
			}

			$api_response = $this->getAddonVersionFromAPI( array( $this->getAddonSlug() ) );
			if ( count( $api_response ) < 1 ) {
				throw new Exception( 'Add-on you are looking for is not exist in API side' );
			}

			$local_addon_data = $this->getAddon( $this->getAddonSlug() );
			$version_cmp      = version_compare( $api_response[ $this->getAddonSlug() ], $local_addon_data[ $this->getAddonSlug() ]['Version'], '>' );
			if ( $version_cmp === false ) {
				throw new Exception( 'You have latest version of this add-on.' );
			}

			$unistall_response = $this->uninstall();
			if ( $unistall_response === false ) {
				throw new Exception( $this->getMessage() );
			}

			$install_response = $this->install();
			$this->message( $install_response, false );
			if ( $install_response === false ) {
				throw new Exception( $this->getMessage() );
			}

			// Enable ajax mode if it was enabled by this method
			if ( empty( $ajax_mode_reverted ) === false && $ajax_mode_reverted === true ) {
				$this->setAjaxMode( true );
			}

			$this->message( 'Add-on updated successfully', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}
	public function addonsCategories() {
		$url      = $this->getApiURL() . 'tools/add-on-categories';
		$response = \Httpful\Request::get( $url )->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			$this->message( $response->body->data, false );
			return false;
		}

		$result = json_decode( json_encode( $response->body->data ), true );
		$this->message( 'Successfull', true, $response->body->data );
		return true;
	}
	public function addonsList( $from = 0, $count = 0, $category = '' ) {
		$exclude_addons = json_encode( array_column( $this->getAddon(), 'slug' ) );
		$exclude_addons = (empty( $exclude_addons ) == true ? array() : $exclude_addons);
		$url            = $this->getApiURL() . 'tools/add-on';
		$response       = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'from'       => $from,
					'count'      => $count,
					'category'   => $category,
					'addon-slug' => $exclude_addons,
					'addon-name' => $this->getAddonName(),
				)
			)
			->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			$this->message( $response->body->data, false );
			return false;
		}

		$result = json_decode( json_encode( $response->body->data ), true );
		$this->message( 'Successfull', true, $response->body->data );
		return true;
	}
	public function addonByName( $from = 0, $count = 0, $category = '' ) {
		$exclude_addons = json_encode( array_column( $this->getAddon(), 'slug' ) );
		$exclude_addons = (empty( $exclude_addons ) == true ? array() : $exclude_addons);
		$url            = $this->getApiURL() . 'tools/add-on/{addon-name}';
		$url            = str_replace( '{addon-name}', $this->getAddonName(), $url );
		$response       = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'from'       => $from,
					'count'      => $count,
					'category'   => $category,
					'addon-slug' => $exclude_addons,
				)
			)
			->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			$this->message( $response->body->data, false );
			return false;
		}

		$result = json_decode( json_encode( $response->body->data ), true );
		$this->message( 'Successfull', true, $response->body->data );
		return true;
	}
	// Helpers
	//
	public function getAddonVersionFromAPI( $addons = array() ) {
		$response = $this->validator
			->setValue( $addons )
			->setFieldName( 'Add-on' )
			->run( 'array:true' );
		if ( $response === false ) {
			throw new Exception( $this->validator->getMessage() );
		}

		$url      = $this->getApiURL() . 'tools/add-on-version';
		$response = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'addons-slug' => json_encode( $addons ),
				)
			)
			->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			throw new Exception( $response->body->data );
		}

		return json_decode( json_encode( $response->body->data ), true );
	}
	public function getAddon( $addon_slug = null ) {
		if ( empty( $addon_slug ) === true ) {
			$addons_list = glob( $this->getAddonDirectory() . '*/package.json' );
		} else {
			$addons_list = glob( $this->getAddonDirectory() . $addon_slug . '/package.json' );
		}
		if ( is_array( $addons_list ) == false || count( $addons_list ) == 0 ) {
			return array();
		}
		$result = array();
		foreach ( $addons_list as $key => $value ) {
			$addon_data      = @json_decode( file_get_contents( $value ), true );
			$addon_slug_name = str_replace( '/package.json', '', str_replace( $this->getAddonDirectory(), '', $value ) );
			if ( empty( $addon_data ) === true ) {
				$result[ $addon_slug_name ] = array();
			} else {
				$result[ $addon_slug_name ] = $addon_data['package_info'];
			}
		}
		return $result;
	}
	public function getAddonFullPath( $addon_slug ) {
		$response = $this->validator
			->setValue( $this->getAddonSlug() )
			->setFieldName( 'Add-on Slug' )
			->run( 'required:true,string:true,min_len:3' );
		if ( $response === false ) {
			return false;
		}
		$addons_list = glob( $this->getAddonDirectory() . $addon_slug );
		if ( is_array( $addons_list ) == false || count( $addons_list ) == 0 ) {
			return false;
		}
		return realpath( $addons_list[0] );
	}
	public function getAddOnDownloadLink( $addon_slug ) {
		$url      = $this->getApiURL() . 'tools/add-on/{addon-slug}/download';
		$url      = str_replace( '{addon-slug}', $addon_slug, $url );
		$response = \Httpful\Request::get( $url )->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			throw new Exception( $response->body->data );
		}

		return $response->body->data;
	}
	public function validateAddonStructure( $addon_path ) {
		$must_exist_files = array( 'package.json' );
		foreach ( $must_exist_files as $key => $value ) {
			if ( file_exists( $addon_path . $value ) === false ) {
				throw new ValidateAddonException( 'Add-On structure have problem , Please download it again' );
				return false;
			}
		}
		return true;
	}
	public function getAddOnFileName( $addon_slug ) {
		$url      = $this->getApiURL() . 'tools/add-on/{addon-slug}/filename';
		$url      = str_replace( '{addon-slug}', $addon_slug, $url );
		$response = \Httpful\Request::get( $url )->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			throw new Exception( $response->body->data );
		}

		return $response->body->data;
	}
	public function message( $message = '', $status = false, $data = array() ) {
		$response = array(
			'status'  => $status,
			'message' => mk_logic_message_helper( 'addon-management' , $message ),
			'data'    => $data,
		);
		if ( $this->getAjaxMode() == true ) {
			header( 'Content-Type: application/json' );
			wp_die( json_encode( $response ) );
		} else {
			$this->setMessage( $response );
		}
	}
} // END class
global $abb_phpunit;
if ( empty( $abb_phpunit ) || $abb_phpunit == false ) {
	new Mk_Addon_Management();
}
