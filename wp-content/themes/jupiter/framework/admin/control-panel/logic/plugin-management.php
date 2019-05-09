<?php
/**
 * This class is responsible to manage all jupiters plugin.
 * it will communicate with artbees API and get list of plugins , install them or remove them
 *
 * @author       Reza Marandi <ross@artbees.net>
 * @copyright    Artbees LTD (c)
 * @link         http://artbees.net
 * @version      1.0
 * @package      jupiter
 */
class mk_plugin_management {

	private $validator;
	private $logger;

	private $plugin_slug;
	public function set_plugin_slug( $plugin_slug ) {
		$this->plugin_slug = $plugin_slug;
		return $this;
	}
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	private $plugin_name;
	public function set_plugin_name( $plugin_name ) {
		$this->plugin_name = $plugin_name;
		return $this;
	}
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	private $plugins_dir;
	public function set_plugins_dir( $plugins_dir ) {
		$this->plugins_dir = $plugins_dir;
		return $this;
	}
	public function get_plugins_dir() {
		return $this->plugins_dir;
	}


	// Example : hello-dolly/hello-dolly.php
	private $plugin_path;
	public function set_plugin_path( $plugin_path ) {
		$this->plugin_path = $plugin_path;
		return $this;
	}
	public function get_plugin_path() {
		return $this->plugin_path;
	}

	private $plugin_remote_file_name;
	public function set_plugin_remote_file_name( $plugin_remote_file_name ) {
		$this->plugin_remote_file_name = $plugin_remote_file_name;
		return $this;
	}
	public function get_plugin_remote_file_name() {
		return $this->plugin_remote_file_name;
	}

	private $plugin_remote_url;
	public function set_plugin_remote_url( $plugin_remote_url ) {
		$this->plugin_remote_url = $plugin_remote_url;
		return $this;
	}
	public function get_plugin_remote_url() {
		return $this->plugin_remote_url;
	}

	private $response;
	public function set_response( $response ) {
		$this->response = $response;
		return $this;
	}
	public function get_response() {
		return $this->response;
	}
	public function get_response_message() {
		$system_response = $this->get_response();
		if ( isset( $system_response['message'] ) ) {
			return $system_response['message'];
		}
		return null;
	}
	public function get_response_status() {
		$system_response = $this->get_response();
		if ( isset( $system_response['status'] ) ) {
			return $system_response['status'];
		}
		return null;
	}
	public function get_response_data() {
		$system_response = $this->get_response();
		if ( isset( $system_response['data'] ) ) {
			return $system_response['data'];
		}
		return null;
	}

	private $api_url;
	public function setApiURL( $api_url ) {
		$this->api_url = $api_url;
		return $this;
	}
	public function getApiURL() {
		return $this->api_url;
	}

	private $system_under_test;
	public function set_system_under_test( $system_under_test ) {
		$this->system_under_test = $system_under_test;
		return $this;
	}
	public function get_system_under_test() {
		return $this->system_under_test;
	}

	private $ajax_mode;
	public function set_ajax_mode( $ajax_mode ) {
		$this->ajax_mode = $ajax_mode;
		return $this;
	}
	public function get_ajax_mode() {
		return $this->ajax_mode;
	}

	private $lock = false;
	private $lock_id = '';
	public function set_lock( $lock_id, $lock ) {
		if ( $lock === true && $this->lock === false ) {
			// Lock is free ...
			$this->lock = $lock;
			$this->lock_id = $lock_id;
		} elseif ( $lock === false && $this->lock === true && $lock_id == $this->lock_id ) {
			// Lock will be free by owner
			$this->lock = $lock;
			$this->lock_id = '';
		}

		return $this;
	}
	public function get_lock() {
		return $this->lock;
	}

	/*====================== CONSTRUCTOR ============================*/

	/**
	 * Construct.
	 * it will add_actions if class created on ajax mode
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @param bool $system_under_test if you want to create an instance of this method for phpunit it should be true
	 * @param bool $ajax_mode if you need this method as ajax mode set true
	 *
	 * @return      void
	 */
	public function __construct( $ajax_mode = true, $system_under_test = false ) {

		$this->set_system_under_test( $system_under_test )
		->set_ajax_mode( $ajax_mode );

		// Set API Server URL
		$this->setApiURL( V2ARTBEESAPI );

		// Create validator instance
		require_once( 'validator-class.php' );

		$this->validator = new Mk_Validator();

		// Init logger to system
		$this->logger = new Devbees\BeesLog\logger();

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

		if ( $this->get_system_under_test() === false ) {
			$this->set_plugins_dir( ABSPATH . 'wp-content/plugins/' );
		}
		if ( $this->get_ajax_mode() == true ) {
			add_action( 'wp_ajax_abb_installed_plugins', array( &$this, 'list_of_installed_plugin' ) );

			add_action( 'wp_ajax_abb_lazy_load_plugin_list', array( &$this, 'mk_plugin_list_handler' ) );
			add_action( 'wp_ajax_abb_install_plugin', array( &$this, 'mk_install_handler' ) );
			add_action( 'wp_ajax_abb_update_plugin', array( &$this, 'mk_update_handler' ) );
			add_action( 'wp_ajax_abb_remove_plugin', array( &$this, 'mk_remove_handler' ) );
		}
	}

	/*====================== ACTION HANDLER ============================*/

	/**
	 * method that is resposible to get data from WordPress ajax and pass it to install() method for installing plugin.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @param str $abb_controlpanel_plugin_name should be posted to this method
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function mk_install_handler() {
		$slug_name = ( ! empty( $_POST['abb_controlpanel_plugin_slug'] ) ? $_POST['abb_controlpanel_plugin_slug'] : null);
		$this->set_plugin_slug( $slug_name );
		$this->install();
	}

	/**
	 * method that is resposible to get data from WordPress ajax and remove specific plugin.
	 * it will deactive plugin first and check if the plugin is one file stand or a directory and then will remove it
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @param str $abb_controlpanel_plugin_name plugin name that should be posted to this method ex (artbees-cap)
	 * @param str $abb_controlpanel_plugin_index_name plugin base name that should be posted to this method ex (artbees-captcha/captcha.php)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function mk_remove_handler() {
		$slug_name = ( ! empty( $_POST['abb_controlpanel_plugin_slug'] ) ? $_POST['abb_controlpanel_plugin_slug'] : null);
		$this->set_plugin_slug( $slug_name );
		$this->remove_plugin( $this->get_plugin_slug() );
	}
	/**
	 * method that is resposible to get data from WordPress ajax and update specific plugin.
	 * it will deactive plugin first and check if the plugin is one file stand or a directory and then will remove it
	 * after removing plugin it will send the plugin name to install() method to install renew plugin
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $abb_controlpanel_plugin_name plugin name that should be posted to this method ex (artbees-cap)
	 * @param str $abb_controlpanel_plugin_index_name plugin base name that should be posted to this method ex (artbees-captcha/captcha.php)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function mk_update_handler() {
		$slug_name = ( ! empty( $_POST['abb_controlpanel_plugin_slug'] ) ? $_POST['abb_controlpanel_plugin_slug'] : null);
		$this->set_plugin_slug( $slug_name );
		$this->update_plugin();
	}
	/**
	 * method that is resposible to pass plugin list to UI base on lazy load condition.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $_POST[from] from number
	 * @param str $_POST[count] how many ?
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function mk_plugin_list_handler() {
		try {
			$this->set_lock( 'mk_plugin_list_handler' , true );
			$response = $this->list_of_installed_plugin();
			$this->set_lock( 'mk_plugin_list_handler' , false );
			if ( $response == false ) {
				throw new Exception( $this->get_response_message() );
			}
			$installed_plugin = $this->get_response_data();
			$exclude_plugins = array_column( $installed_plugin, 'slug' );
			$this->plugins_list_from_api( $exclude_plugins );
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/*====================== MAIN FUNCTIONS ============================*/

	/**
	 * method that is resposible to download plugin from api and install it on WordPress then activate it on last step.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $this->getPluginName plugin name
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function install() {

		try {
			// Validate if plugin name is setted
			$response = $this->validator
				->setValue( $this->get_plugin_slug() )
				->setFieldName( 'Plugins Slug' )
				->run( 'required:true,string:true,min_len:3' );
			if ( $response === false ) {
				throw new Exception( $this->validator->getMessage() );
			}

			// Check if plugin already exist
			if ( ($plugin_path = $this->find_plugin_path( $this->get_plugin_slug() )) !== false ) {
				if ( $this->check_active_plugin( $this->get_plugin_slug() ) == true ) {
					$this->message( 'Plugin already active.', true );
					return true;
				}
				$this->activate_plugin( $this->get_plugin_slug() );
				$this->message( 'Plugin successfully activated.', true );
				return true;
			}

			// Precheck everything
			$this->precheck();

			// Get plugin url (address)
			$this->set_lock( 'install' ,  true );
			if ( $this->plugins_list_from_api( 0, 1 ) === false ) {
				throw new Exception( $this->get_response_message(), 1 );
			}
			$api_response = $this->get_response_data();
			if ( is_array( $api_response ) == false || count( $api_response ) < 1 ) {
				$message = 'The plugin ({param}) you are looking for is not exist.' ;
				$this->message( array( $message, $this->get_plugin_slug() ), false );
				return false;
			}
			$this->set_lock( 'install' , false );
			if ( filter_var( $api_response[0]['source'], FILTER_VALIDATE_URL ) === false ) {
				throw new Exception( 'Plugins source could not be found or it has invalid URL' );
			}
			$this->set_plugin_remote_url( $api_response[0]['source'] );
			$this->set_plugin_remote_file_name( basename( $this->get_plugin_remote_url() ) );

			// Upload plugin from address to WordPress upload folder
			Abb_Logic_Helpers::uploadFromURL( $this->get_plugin_remote_url(), $this->get_plugin_remote_file_name(), $this->get_plugins_dir() );

			// Unzip IT
			$zip_path = $this->get_plugins_dir() . $this->get_plugin_remote_file_name();
			Abb_Logic_Helpers::unZip( $zip_path, $this->get_plugins_dir() );

			// Find if the plugin have a directory or one stand php file and set full address of it
			$found_file = $this->find_plugin_path( $this->get_plugin_slug() );

			if ( $found_file === false ) {
				$need_to_be_replace = 'Can not find {param} plugin path.';
				$this->message( array( $need_to_be_replace, $this->get_plugin_slug() ), false );
				return false;
			}

			// Remove ZIP file
			Abb_Logic_Helpers::deleteFileNDir( $zip_path );

			// Activate Plugin
			$this->activate_plugin( $this->get_plugin_slug() );

			$this->message( 'Plugin successfully added and activated.', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}

	/**
	 * method that is resposible to update specific plugin.
	 * it will deactive plugin first and check if the plugin is one file stand or a directory and then will remove it
	 * after removing plugin it will send the plugin name to install() method to install renew plugin
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $this->get_plugin_slug() plugin slig. ex (artbees-cap)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function update_plugin() {
		try {
			$this->set_lock( 'update_plugin' , true );
			// Validate if plugin name is setted
			$response = $this->validator
				->setValue( $this->get_plugin_slug() )
				->setFieldName( 'Plugins Slug' )
				->run( 'required:true,string:true,min_len:3' );
			if ( $response === false ) {
				throw new Exception( $this->validator->getMessage() );
			}

			// Precheck everything
			$this->precheck();

			$plugin_path = $this->find_plugin_path( $this->get_plugin_slug() );
			if ( $plugin_path === false ) {
				throw new Exception( 'Can not find plugin head file name.' );
			}

			$this->set_plugin_path( $plugin_path );

			// Check if plugin is active or not
			if ( $this->check_active_plugin( $this->get_plugin_slug() ) !== false ) {
				$this->deactivate_plugin( $this->get_plugin_slug() );
			}

			$response = $this->remove_plugin( $this->get_plugin_slug() );

			$response = $this->install();
			$get_plugins = get_plugins();

			$installed_plugin_version = $get_plugins[ $plugin_path ]['Version'];

			$this->set_lock( 'update_plugin' , false )->message( $installed_plugin_version, true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}
	public function plugins_list_from_api( $exclude_plugins = array() ) {
		$exclude_plugins = json_encode( $exclude_plugins );
		$exclude_plugins = (empty( $exclude_plugins ) == true ? array() : $exclude_plugins);
		$url            = $this->getApiURL() . 'tools/plugin';
		$response       = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'from'       => 0,
					'count'      => 20,
					'exclude-plugins-slug' => $exclude_plugins,
					'plugin-name' => $this->get_plugin_name(),
					'plugin-slug' => $this->get_plugin_slug(),
				)
			)
			->send();
		if ( ! isset( $response->body->bool ) || ! $response->body->bool ) {
			$this->message( $response->body->message, false );
			return false;
		}
		if ( empty( $response->body->data ) ) {
			$this->message( 'Successfull', true, array() );
			return true;
		}
		$result = json_decode( json_encode( $response->body->data ), true );
		foreach ( $result as $key => $value ) {
				$fetch_data = [];
			if ( 'wp-repo' === $value['source'] ) {
				$fetch_data['download_link'] = 'source';
			}
			if ( 'wp-repo' === $value['version'] ) {
				$fetch_data['version'] = 'version';
			}
			if ( 'wp-repo' === $value['desc'] ) {
				$fetch_data['short_description'] = 'desc';
			}
			if ( is_array( $fetch_data ) && count( $fetch_data ) > 0 ) {
				$response = $this->get_plugin_info_from_wp_repo( $value['slug'], $fetch_data );
				if ( false !== $response ) {
					$result[ $key ] = array_replace( $result[ $key ], $response );
				}
				if ( $this->find_plugin_path( $value['slug'] ) ) {
					$result[ $key ]['version'] = $this->get_plugin_data( $value['slug'], 'Version' );
					$result[ $key ]['desc'] = $this->get_plugin_data( $value['slug'], 'Description' );
				}
			}
		}
		$this->message( 'Successfull', true, $result );
		return true;
	}
	public function plugin_version_from_api( $plugins = array() ) {
		$response = $this->validator
			->setValue( $plugins )
			->setFieldName( 'Plugins' )
			->run( 'array:true' );
		if ( $response === false ) {
			throw new Exception( $this->validator->getMessage() );
		}
		$url      = $this->getApiURL() . 'tools/plugin-version';
		$response = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'plugins-slug' => json_encode( $plugins ),
				)
			)
			->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			throw new Exception( $response->body->message );
		}

		return json_decode( json_encode( $response->body->data ), true );
	}
	public function list_of_installed_plugin() {
		try {
			$list_of_plugins = $this->plugins_custom_api( 0 , 0 , array( 'slug', 'basename', 'version', 'name', 'desc', 'img_url' ) );

			if ( is_array( $list_of_plugins ) && count( $list_of_plugins ) > 0 ) {
				foreach ( $list_of_plugins as $key => $plugin_info ) {
					if ( is_plugin_active( $plugin_info['basename'] ) ) {
						if ( ($current_plugin_version = $this->get_plugin_version( $plugin_info['slug'] )) != false ) {
							if ( version_compare( $current_plugin_version, $plugin_info['version'], '<' ) ) {
								$list_of_plugins[ $key ]['installed']   = true;
								$list_of_plugins[ $key ]['update_needed'] = true;
							} else {
								$list_of_plugins[ $key ]['installed']   = true;
								$list_of_plugins[ $key ]['update_needed'] = false;
							}
							$list_of_plugins[ $key ]['version'] = $current_plugin_version;
						}
					} else {
						unset( $list_of_plugins[ $key ] );
					}
				}
				$this->message( 'Successfull', true, $list_of_plugins );
				return true;
			} else {
				$this->message( 'Plugin list is empty', false );
				return false;
			}
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}
	/*====================== HELPERS ============================*/

	public function plugins_custom_api( $from = 0, $count = 1, $list_of_attr = array() ) {
		$url            = $this->getApiURL() . 'tools/plugin-custom-list';
		$response       = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'from'       => $from,
					'count'      => $count,
					'list-of-attr' => json_encode( $list_of_attr ),
				)
			)
			->send();
		if ( isset( $response->body->bool ) == false || $response->body->bool == false ) {
			throw new Exception( $response->body->message );
			return false;
		}

		$result = json_decode( json_encode( $response->body->data ), true );
		if ( is_array( $result ) && count( $result ) > 0 ) {
			foreach ( $result as $key => $value ) {
				$fetch_data = [];
				if ( isset( $value['source'] ) && $value['source'] == 'wp-repo' ) {
					$fetch_data['download_link'] = 'source';
				}
				if ( isset( $value['version'] ) && $value['version'] == 'wp-repo' ) {
					$fetch_data['version'] = 'version';
				}
				if ( isset( $value['desc'] ) && $value['desc'] == 'wp-repo' ) {
					$fetch_data['short_description'] = 'desc';
				}
				if ( is_array( $fetch_data ) && count( $fetch_data ) > 0 ) {
					$response = $this->get_plugin_info_from_wp_repo( $value['slug'], $fetch_data );
					if ( $response != false ) {
						$result[ $key ] = array_replace( $result[ $key ], $response );
					}
				}
			}
		}
		return $result;
	}
	/**
	 * method that is resposible to download plugin from api and install it on WordPress then activate it on last step.
	 * it will get an array of plugins name.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $this->getPluginName plugin name
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function install_batch( $plugins_slug_list ) {
		try {
			if ( empty( $plugins_slug_list ) || is_array( $plugins_slug_list ) == false || count( $plugins_slug_list ) == 0 ) {
				throw new Exception( 'Plugin list is not an array , use install method instead.' );
			}
			$this->set_lock( 'install_batch' , true );
			foreach ( $plugins_slug_list as $key => $plugin_slug ) {
				$this->set_plugin_slug( $plugin_slug );
				$response = $this->install();
				if ( $response == false ) {
					throw new Exception( $this->get_response_message() );
				}
			}
			$message_need_to_replace = '{param} plugins installed successfully';
			$this->set_lock( 'install_batch' , false )->message( array( $message_need_to_replace, count( $plugins_slug_list ) ) );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	/**
	 * this method is resposible to activate upladed plugin .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug for example : (js_composer_theme)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function precheck() {

		// Check Plugins directory
		$response = $this->validator
				->setValue( $this->get_plugins_dir() )
				->setFieldName( 'Plugins Directory' )
				->run( 'required:true,string:true,min_len:10' );
		if ( $response === false ) {
			throw new Exception( $this->validator->getMessage() );
			return false;
		}

		$url = add_query_arg( $_POST, admin_url( 'admin-ajax.php' ) );

		$mkfs = new Mk_Fs(
			[
				'form_post' => $url,
				'context' => $this->get_plugins_dir(),
			]
		);

		if ( $mkfs->get_error_code() ) {
			throw new Exception( $mkfs->get_error_message() );
			return false;
		}

		if ( ! $mkfs->is_writable( $this->get_plugins_dir() ) ) {
			throw new Exception( 'Plugin directory is not writable.' );
			return false;
		}

		return true;
	}
	/**
	 * this method is resposible to activate upladed plugin .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug for example : (js_composer_theme)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function activate_plugin( $plugin_slug ) {
		$current = get_option( 'active_plugins' );
		$plugin_path = $this->find_plugin_path( $plugin_slug );
		if ( $plugin_path === false ) {
			throw new Exception( 'Can not find plugin path in activate plugin func' );
			return false;
		}
		$plugin_main_filename  = plugin_basename( trim( $plugin_path ) );
		if ( ! in_array( $plugin_main_filename, $current ) ) {
			ob_start();
			$current[] = $plugin_main_filename;
			sort( $current );
			do_action( 'activate_plugin', trim( $plugin_main_filename ) , '' , false );
			update_option( 'active_plugins', $current );
			do_action( 'activate_' . trim( $plugin_main_filename ) );
			do_action( 'activated_plugin', trim( $plugin_main_filename ) );
			ob_end_clean();
			return true;
		}
		return true;
	}
	/**
	 * this method is resposible to deactivate active plugin .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug for example : (js_composer_theme)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function deactivate_plugin( $plugin_slug, $silent = false, $network_wide = null ) {
		$plugin_path = $this->find_plugin_path( $plugin_slug );
		if ( $plugin_path === false ) {
			return true;
		}
		$plugin_full_path = $this->get_plugins_dir() . $plugin_path;
		if ( is_plugin_active( $plugin_path ) == false ) {
			return true;
		}
		$response = deactivate_plugins( $plugin_full_path, $silent, $network_wide );
		if ( is_wp_error( $response ) ) {
			throw new Exception( 'deactivatePlugin , ' . $response->get_error_message(), 1 );
			return false;
		}
		return true;
	}

	/**
	 * this method is resposible to remove deactive plugin .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug plugin slug name
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function remove_plugin( $plugin_slug ) {
		// Check wether parent directory is writable or not
		try {

			$mkfs = new Mk_Fs(
				[
					'context' => $this->get_plugins_dir(),
				]
			);

			if ( $mkfs->get_error_code() ) {
				throw new Exception( $mkfs->get_error_message() );
			}
			if ( ! $mkfs->is_writable( $this->get_plugins_dir() ) ) {
				throw new Exception( 'Plugin parent directory is not writable - RPPM01x01.' );
			}
			if ( ($plugin_path = $this->find_plugin_path( $plugin_slug )) == false ) {
				throw new Exception( 'Plugin you want to remove is not exist  - RPPM01x01.' );
			}

			// Condition to check if plugin is still active or not
			// if ( $this->check_active_plugin( $plugin_slug ) === true ) {
			// throw new Exception( 'Plugin you want to remove is still activated , deactive it first  - RPPM01x01.' );
			// }
			$this->deactivate_plugin( $plugin_slug );

			$plugin_full_path = $this->get_plugins_dir() . $plugin_path;
			// Check if the plugin is one file or a directory
			$plugin_base_directory = str_replace( basename( $plugin_path ), '', $plugin_full_path );

			if ( strlen( str_replace( $this->get_plugins_dir(), '', $plugin_full_path ) ) > 2 ) {
				if ( ! $mkfs->is_writable( $plugin_base_directory ) ) {
					throw new Exception( 'Plugin directory is not writable  - RPPM01x01.' );
				}
				if ( Abb_Logic_Helpers::deleteFileNDir( $plugin_base_directory ) ) {
					$this->message( 'Plugin successfully Removed.', true );
					return true;
				} else {
					throw new Exception( 'Can not remove directory of plugin - RPPM01x01-Directory' );
				}
			} else {
				if ( Abb_Logic_Helpers::deleteFileNDir( $plugin_full_path ) ) {
					$this->message( 'Plugin successfully Removed.', true );
					return true;
				} else {
					throw new Exception( 'Can not remove directory of plugin - RPPM01x01-File' );
				}
			}

			$this->message( 'Plugin successfully Removed.', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}

	/**
	 * this method is resposible to get plugin version .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug for example : (js_composer_theme)
	 *
	 * @return bool|int will return version of plugin or false
	 */
	public function get_plugin_version( $plugin_slug ) {
		$plugin_path = $this->find_plugin_path( $plugin_slug );
		if ( $plugin_path === false ) {
			return false;
		}
		$plugin_full_path = $this->get_plugins_dir() . $plugin_path;
		if ( file_exists( $plugin_full_path ) == false ) {
			return false;
		}
		$get_plugin_data = get_plugin_data( $plugin_full_path );
		$version_response = $get_plugin_data['Version'] ;
		if ( empty( $version_response ) == false ) {
			return $version_response;
		} else {
			return false;
		}
	}

	/**
	 * this method is resposible to get plugin version .
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug for example : (js_composer_theme)
	 * @param str $which_field which field do you need from plugins data ? (Name | PluginURI | Version | Description | Author | AuthorURI | TextDomain | DomainPath | Network | Title | AuthorName)
	 *
	 * @return bool|int will return version of plugin or false
	 */
	public function get_plugin_data( $plugin_slug, $which_field ) {
		wp_clean_plugins_cache();
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_address => $plugin_data ) {
			// Extract slug from address
			if ( strlen( $plugin_address ) == basename( $plugin_address ) ) {
				$slug = strtolower( str_replace( '.php', '', $plugin_address ) );
			} else {
				$slug = strtolower( str_replace( '/' . basename( $plugin_address ), '', $plugin_address ) );
			}
			// Check if slug exists
			if ( strtolower( $plugin_slug ) == $slug ) {
				return (isset( $plugin_data[ $which_field ] ) ? $plugin_data[ $which_field ] : false);
			}
		}
		return false;
	}

	/**
	 * this method is resposible to get plugin data name field
	 * it used native WordPress functions.
	 *
	 * @author Sofyan Sitorus <sofyan@artbees.net>
	 *
	 * @param str $plugin_slug plugin slug or plugin path (js_composer_theme | js_composer_theme/js_composer.php)
	 *
	 * @return string Will return plugin name or slug if it was not found
	 */
	public function get_plugin_data_name( $plugin_slug ) {

		$plugin_data_name = $this->get_plugin_data( $plugin_slug, 'Name' );

		if ( ! $plugin_data_name ) {
			$url = $this->getApiURL() . 'tools/plugin';
			$response       = \Httpful\Request::get( $url )
				->addHeaders(
					array(
						'plugin-slug' => $plugin_slug,
					)
				)
				->send();
			if ( isset( $response->body->data[0]->name ) ) {
				$plugin_data_name = $response->body->data[0]->name;
			}
		}

		return empty( $plugin_data_name ) ? $plugin_slug : $plugin_data_name;
	}

	/**
	 * this method is resposible to check if input plugin name is active or not.
	 * it used native WordPress functions.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug plugin slug or plugin path (js_composer_theme | js_composer_theme/js_composer.php)
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function check_active_plugin( $plugin_slug ) {
		$active_plugins = get_option( 'active_plugins' );
		if ( is_array( $active_plugins ) == false || count( $active_plugins ) < 1 ) {
			return false;
		}
		foreach ( $active_plugins as $index => $string ) {
			if ( strpos( $string, $plugin_slug ) !== false ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * this method is resposible to find plugin head file and return full path of it.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $plugin_slug plugins slug for example (js_composer_theme).
	 *
	 * @return string|bool will return plugin path : (js_composer_theme/js_composer.php) or false if plugin slug not exist.
	 */
	public function find_plugin_path( $plugin_slug ) {
		wp_clean_plugins_cache();
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_address => $plugin_data ) {

			// Extract slug from address
			if ( strlen( $plugin_address ) == basename( $plugin_address ) ) {
				$slug = strtolower( str_replace( '.php', '', $plugin_address ) );
			} else {
				$slug = strtolower( str_replace( '/' . basename( $plugin_address ), '', $plugin_address ) );
			}
			// Check if slug exists
			if ( strtolower( $plugin_slug ) == $slug ) {
				return $plugin_address;
			}
		}
		return false;
	}

	/**
	 * Try to grab information from WordPress API.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param array  $info_array it should be valued if you want to extract specific data from WordPress info
	 *                           for example : array('download_link' => 'source' , 'version' => 'version')
	 *                           array key : the info name from WordPress repo
	 *                           array value : the name of info that you need to return
	 *
	 * @return object Plugins_api response object on success, WP_Error on failure.
	 */
	public function get_plugin_info_from_wp_repo( $plugin_slug, $info_array = array() ) {
		static $api = array();
		if ( ! isset( $api[ $plugin_slug ] ) ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$response   = plugins_api(
				'plugin_information', array(
					'slug' => $plugin_slug,
					'fields' => array(
						'sections' => false,
						'short_description' => true,
					),
				)
			);
			$api[ $plugin_slug ] = false;

			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
				return false;
			} else {
				$api[ $plugin_slug ] = $response;
			}
		}
		if ( is_array( $info_array ) && count( $info_array ) > 0 ) {
			$final_response = [];
			foreach ( $info_array as $key => $value ) {
				if ( empty( $api[ $plugin_slug ]->$key ) == false ) {
					$final_response[ $value ] = $api[ $plugin_slug ]->$key;
				}
			}
			return $final_response;
		} else {
			return $api[ $plugin_slug ];
		}
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
			'message' => mk_logic_message_helper( 'plugin-management' , $message ),
			'status'  => $status,
			'data'    => $data,
		);
		if ( $this->get_lock() == true && $this->get_system_under_test() == false ) {
			$this->set_response( $response );
			return true;
		} elseif ( $this->get_ajax_mode() == true && $this->get_system_under_test() == false ) {
			// Ajax response to UI
			header( 'Content-Type: application/json' );
			wp_die( json_encode( $response ) );
			return true;
		} elseif ( $this->get_ajax_mode() == false && $this->get_system_under_test() == true ) {
			// System under class integration tests
			$response['message'] = mk_logic_message_helper( 'plugin-management' , $message , 'sys_msg' );
			$this->set_response( $response );
			return true;
		} elseif ( $this->get_ajax_mode() == true && $this->get_system_under_test() == true ) {
			// System under ajax integration tests
			wp_die( json_encode( $response ) );
			return true;
		} elseif ( $this->get_ajax_mode() == false && $this->get_system_under_test() == false ) {
			// System is communicating with other classes
			$this->set_response( $response );
			return true;
		}
	}

}
global $abb_phpunit;
if ( empty( $abb_phpunit ) || $abb_phpunit == false ) {
	new mk_plugin_management();
}
