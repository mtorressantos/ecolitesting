<?php
class mk_control_panel {


	public function __construct() {

		$this->store_theme_current_version();
		$this->api_url = 'https://artbees.net/api/v1/';
		add_filter( 'upload_mimes', array( &$this, 'mime_types' ) );
		add_filter( 'getimagesize_mimes_to_exts', array( &$this, 'mime_to_ext' ) );
		add_action( 'wp_ajax_mka_cp_load_pane_action', array( & $this, 'mka_cp_load_pane_action' ) );
		add_action( 'wp_ajax_mka_cp_register_revoke_api_action', array( &$this, 'mka_cp_register_revoke_api_action' ) );
		add_action( 'wp_ajax_abb_save_image_sizes', array( &$this, 'abb_save_image_sizes' ) );
		add_action( 'wp_ajax_abb_get_icon_list_action', array( &$this, 'abb_get_icon_list_action' ) );
		$this->mk_verify_envato_hosted();
	}

	/**
	 * This hook will allow WordPress to accept .zip formats in media library
	 *
	 * @author      Bob Ulusoy
	 */
	public function mime_types( $mimes ) {
		$mimes['zip'] = 'application/zip';
		return $mimes;
	}

	/**
	 * Map the "image/vnd.microsoft.icon" MIME type to the ico file extension, instead of
	 * modifying the expected MIME types of WordPress in the WordPress wp_get_mime_types()
	 * function.
	 *
	 * This is work-around for a bug in WordPress when the PHP version returns MIME
	 * type of "image/vnd.microsoft.icon" instead of "image/x-icon"
	 * that WordPress expects.
	 *
	 * @author Dominique Mariano
	 *
	 * @since 5.6 Introduced.
	 */
	public function mime_to_ext( $mimes_to_text ) {
		$mimes_to_text['image/vnd.microsoft.icon'] = 'ico';
		return $mimes_to_text;
	}


	public function get_image_size_data() {

		$options = get_option( IMAGE_SIZE_OPTION );

		if ( empty( $options ) ) {
			$options = array(
				array(
					'size_w' => 500,
					'size_h' => 500,
					'size_n' => 'Image Size 500x500',
					'size_c' => 'on',
				),
			);
		}

		return $options;
	}


	public function abb_save_image_sizes() {

		check_ajax_referer( 'ajax-image-sizes-options', 'security' );

		$options = isset( $_POST['options'] ) ? $_POST['options'] : array();
		$options_array = array();
		if ( ! empty( $options ) ) {
			foreach ( $options as $sizes ) {
				parse_str( $sizes, $output );
				$options_array[] = $output;
			}
		}
		if ( update_option( IMAGE_SIZE_OPTION, $options_array ) ) {

						update_option( THEME_OPTIONS_BUILD, uniqid() );
			wp_die( 1 );
		} else {
			wp_die( 2 );
		}
	}


	public function abb_get_icon_list_action() {

		include_once( 'icons-list.php' );
		$from = isset( $_POST['from'] ) ? $_POST['from'] : 0;
		$count = isset( $_POST['count'] ) ? $_POST['count'] : 500;
		$category = isset( $_POST['category'] ) ? $_POST['category'] : false;
		$keyword = isset( $_POST['keyword'] ) ? $_POST['keyword'] : false;

		if ( ! empty( $category ) ) {
			$mk_icon_list = $this->get_icon_by_library( $mk_icon_list, $category );
		}

		if ( ! empty( $keyword ) ) {
			$mk_icon_list = $this->get_icon_by_keyword( $mk_icon_list, $keyword );
		}

		$prepare_list = array_slice( $mk_icon_list, $from, $count );
		$return_count = count( $prepare_list );

		$response = array(
			'status' => true,
			'return_count' => $return_count,
			'data' => $this->generate_icon_list( $prepare_list ),
		);
		wp_die( json_encode( $response ) );

	}

	public function generate_icon_list( $library ) {
		$icon_html = '';
		if ( ! empty( $library ) ) {
			foreach ( $library as $icon ) {
				$icon_html .= ' <div class="mka-cp-icon-item">
                                    <span class="mka-cp-icon-item-svg">' . Mk_SVG_Icons::get_svg_icon_by_class_name( false, $icon['n'], 32 ) . '</span>
                                    <textarea class="mka-cp-icon-item-class" readonly="readonly" onclick="this.focus();this.select()">' . $icon['n'] . '</textarea>
                                </div>';
			}
		}
		return $icon_html;

	}


	public function get_icon_by_library( $library, $category ) {
		if ( $category ) {
			$new_list = array();
			foreach ( $library as $icon ) {
				if ( $icon['l'] == $category ) {
					$new_list[] = $icon;
				}
			}
			return $new_list;
		}
		return $library;
	}

	public function get_icon_by_keyword( $library, $keyword ) {
		if ( $keyword ) {
			$new_list = array();
			foreach ( $library as $icon ) {
				if ( strpos( $icon['n'], $keyword ) !== false ) {
					$new_list[] = $icon;
				}
			}
			return $new_list;
		}
		return $library;
	}

	/**
	 *
	 * Artbees API Key Verifier
	 *
	 * @param string $apikey Api key.
	 * @since Updated 6.0.2 Added Envato parameter for api verification.
	 * @return array.
	 */
	public function verify_artbees_apikey( $apikey ) {
		$envato = ( defined( 'SUBSCRIPTION_CODE' ) && defined( 'ENVATO_HOSTED_SITE' ) ) ? true : false;
		$data = array(
			'timeout'     => 10,
			'httpversion' => '1.1',
			'body'        => array(
				'apikey' => $apikey,
				'domain' => $_SERVER['SERVER_NAME'],
				'envato' => $envato,
			),
		);

		$query = wp_remote_post( $this->api_url . 'verify', $data );
		if ( ! is_wp_error( $query ) ) {
			$result = json_decode( $query['body'], true );
			return $result;
		}

		return array(
			'message' => $query->get_error_message() . ' Please contact Artbees Support',
		);
	}

	/**
	 * Gets the result for api key verification.
	 *
	 * @param  string $api_key api key string.
	 * @return array|mixed|object returns false if testdrive mod is defined or disable test drive session is true.
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 6.0 Added for test drive specific sites.
	 */
	private function mka_cp_get_api_result( $api_key ) {

		$mk_testdrive = ( defined( 'MK_TESTDRIVE' ) && true === MK_TESTDRIVE );
		$mk_dis_td_session = ( isset( $_SESSION['mk_disable_testdrive'] ) && 'true' === $_SESSION['mk_disable_testdrive'] );
		$result['is_verified'] = false;

		if ( false === $mk_testdrive || true === $mk_dis_td_session ) {
			$result = $this->verify_artbees_apikey( $api_key );
		}

		return $result;
	}

	/**
	 * Verifies envato hosted users.
	 * @since 6.0.2
	 * @author Ugur Mirza Zeyrek
	 */
	private function mk_verify_envato_hosted() {
		if ( defined( 'ENVATO_HOSTED_SITE' ) && defined( 'SUBSCRIPTION_CODE' ) ) {
			$api_key = SUBSCRIPTION_CODE;
			$result = $this->mka_cp_get_api_result( $api_key );
			if ( $result['is_verified'] ) {
				update_option( 'artbees_api_key', $api_key, 'yes' );
				$message = __( 'Your product registration was successful.', 'mk_framework' );
				$status = true;
			} else {
				delete_option( 'artbees_api_key' );
				$message = __( 'Your API key could not be verified. ', 'mk_framework' ) . (isset( $result['message'] ) ? $result['message'] : __( 'An error occured', 'mk_framework' )) . '.';
				$status = false;
			}
		}
	}

	public function mka_cp_register_revoke_api_action() {

		check_ajax_referer( 'mka-cp-ajax-register-api', 'security' );
		$method = $_POST['method'];

		if ( $method == 'register' ) {
			$api_key = $_POST['api_key'];
			$result = $this->mka_cp_get_api_result( $api_key );

			if ( $result['is_verified'] ) {
				update_option( 'artbees_api_key', $api_key, 'yes' );
				$message = __( 'Your product registration was successful.', 'mk_framework' );
				$status = true;
			} else {
				delete_option( 'artbees_api_key' );
				$message = __( 'Your API key could not be verified. ', 'mk_framework' ) . ( isset( $result['message'] ) ? $result['message'] : __( 'An error occured', 'mk_framework' ) ) . '.';
				$status = false;
			}
		} elseif ( $method == 'revoke' ) {
			 delete_option( 'artbees_api_key' );
			 $message = __( 'Your API key has been successfully revoked.', 'mk_framework' );
			 $status = true;
		}

		echo json_encode(
			array(
				'message' => $message,
				'status' => $status,
			)
		);

		wp_die();

	}

	/**
	 * Stores theme current version into options table to be used in multiple instances
	 */
	public function store_theme_current_version() {

		$theme_data    = wp_get_theme( get_option( 'template' ) );
		$theme_version = $theme_data->Version;

		if ( get_option( 'mk_jupiter_theme_current_version' ) != $theme_version ) {
			update_option( 'mk_jupiter_theme_current_version', $theme_version );
		}
	}

	/**
	 *
	 *
	 * Check if Current Customer is verified
	 */
	public function is_verified_artbees_customer( $localhost = true ) {

		$result = $this->verify_artbees_apikey( get_option( 'artbees_api_key' ) );

		if ( defined( 'MK_DEV' ) ) {
			return true;
		}

		if ( self::isLocalHost() && $localhost == true ) {
			return true;
		}

		return ( isset( $result['status'] ) && 202 == $result['status'] ) ? true : false;
	}

	public function is_api_key_exists( $localhost = true ) {

		$api_key = get_option( 'artbees_api_key' );

		if ( ! empty( $api_key ) ) {
			return true;
		}

		return false;

	}

	public function mka_cp_load_pane_action() {

		$slug = esc_attr( $_POST['slug'] );
		ob_start();
		include_once( THEME_CONTROL_PANEL . "/v2/panes/{$slug}.php" );
		$pane_html = ob_get_clean();
		wp_send_json_success( $pane_html );
		wp_die();
	}


	/**
	 * Fetch Announcements from artbees themes API, store them in transients. So they get updated once a day
	 *
	 * @copyright   ArtbeesLTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.1
	 * @last_update Version 5.1
	 * @package     artbees
	 * @author      Bob Ulusoy
	 */
	public function get_announcements() {

		// set_transient('mk_artbees_themes_announcements', null);
		if ( false == get_transient( 'mk_artbees_themes_announcements' ) ) {
			global $wp_version;

			$data = array(
				'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
			);

			$raw_response = wp_remote_get( $this->api_url . 'announcements', $data );

			if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {
				$response = $raw_response['body'];
			} else {
				$response = is_wp_error( $raw_response );
			}
			// Transient will be cleared after 1 day.
			set_transient( 'mk_artbees_themes_announcements', $response, DAY_IN_SECONDS );
		}

		return unserialize( get_transient( 'mk_artbees_themes_announcements' ) );

	}

	private static function let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );

		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}

		return $ret;
	}

	public static function makeBoolStr( $var ) {
		if ( $var == false || $var == 'false' || $var == 0 || $var == '0' || $var == '' || empty( $var ) ) {
			return 'false';
		} else {
			return 'true';
		}
	}
	public static function isLocalHost() {
		return ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '::1') ? 1 : 0;
	}

	public static function isWpDebug() {
		return (defined( 'WP_DEBUG' ) && WP_DEBUG == true);
	}

	public static function compileSystemStatus( $json_output = false, $remote_checks = false ) {
		global $wpdb;

		$sysinfo = array();

		$sysinfo['home_url'] = esc_url( home_url( '/' ) );
		$sysinfo['site_url'] = esc_url( site_url( '/' ) );

		// Only is a file-write check
		$sysinfo['wp_content_url']      = WP_CONTENT_URL;
		$sysinfo['wp_ver']              = get_bloginfo( 'version' );
		$sysinfo['wp_multisite']        = is_multisite();
		$sysinfo['permalink_structure'] = get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default';
		$sysinfo['front_page_display']  = get_option( 'show_on_front' );
		if ( $sysinfo['front_page_display'] == 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
			$blog_page_id  = get_option( 'page_for_posts' );

			$sysinfo['front_page'] = $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset';
			$sysinfo['posts_page'] = $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset';
		}

		$sysinfo['wp_mem_limit']['raw']  = self::let_to_num( WP_MEMORY_LIMIT );
		$sysinfo['wp_mem_limit']['size'] = size_format( $sysinfo['wp_mem_limit']['raw'] );

		$sysinfo['db_table_prefix'] = 'Length: ' . strlen( $wpdb->prefix ) . ' - Status: ' . (strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable');

		$sysinfo['wp_debug'] = 'false';
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$sysinfo['wp_debug'] = 'true';
		}

		$sysinfo['wp_lang'] = get_locale();

		if ( ! class_exists( 'Browser' ) ) {
			$file_path = pathinfo( __FILE__ );
			require_once $file_path['dirname'] . '/browser.php';
		}

		$browser = new Browser();

		$sysinfo['browser'] = array(
			'agent'    => $browser->getUserAgent(),
			'browser'  => $browser->getBrowser(),
			'version'  => $browser->getVersion(),
			'platform' => $browser->getPlatform(),

			// 'mobile'   => $browser->isMobile() ? 'true' : 'false',
		);

		$sysinfo['server_info'] = esc_html( $_SERVER['SERVER_SOFTWARE'] );
		$sysinfo['localhost']   = self::makeBoolStr( self::isLocalHost() );
		$sysinfo['php_ver']     = function_exists( 'phpversion' ) ? esc_html( phpversion() ) : 'phpversion() function does not exist.';
		$sysinfo['abspath']     = ABSPATH;

		if ( function_exists( 'ini_get' ) ) {
			$sysinfo['php_mem_limit']      = size_format( self::let_to_num( ini_get( 'memory_limit' ) ) );
			$sysinfo['php_post_max_size']  = size_format( self::let_to_num( ini_get( 'post_max_size' ) ) );
			$sysinfo['php_time_limit']     = ini_get( 'max_execution_time' );
			$sysinfo['php_max_input_var']  = ini_get( 'max_input_vars' );
			$sysinfo['suhosin_request_max_vars']  = ini_get( 'suhosin.request.max_vars' );
			$sysinfo['suhosin_post_max_vars'] = ini_get( 'suhosin.post.max_vars' );
			$sysinfo['php_display_errors'] = self::makeBoolStr( ini_get( 'display_errors' ) );
		}

		$sysinfo['suhosin_installed'] = extension_loaded( 'suhosin' );
		$sysinfo['mysql_ver']         = $wpdb->db_version();
		$sysinfo['max_upload_size']   = size_format( wp_max_upload_size() );

		$sysinfo['def_tz_is_utc'] = 'true';
		if ( date_default_timezone_get() !== 'UTC' ) {
			$sysinfo['def_tz_is_utc'] = 'false';
		}

		$sysinfo['fsockopen_curl'] = 'false';
		if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
			$sysinfo['fsockopen_curl'] = 'true';
		}

		$sysinfo['soap_client'] = 'false';
		if ( class_exists( 'SoapClient' ) ) {
			$sysinfo['soap_client'] = 'true';
		}

		$sysinfo['dom_document'] = 'false';
		if ( class_exists( 'DOMDocument' ) ) {
			$sysinfo['dom_document'] = 'true';
		}

		$sysinfo['gzip'] = 'false';
		if ( is_callable( 'gzopen' ) ) {
			$sysinfo['gzip'] = 'true';
		}

				$sysinfo['mbstring'] = 'false';

		if ( extension_loaded( 'mbstring' ) && function_exists( 'mb_eregi' ) && function_exists( 'mb_ereg_match' ) ) {
			$sysinfo['mbstring'] = 'true';
		}

		$sysinfo['simplexml'] = 'false';

		if ( class_exists( 'SimpleXMLElement' ) && function_exists( 'simplexml_load_string' ) ) {
			$sysinfo['simplexml'] = 'true';
		}

		$sysinfo['phpxml'] = 'false';

		if ( function_exists( 'xml_parse' ) ) {
			$sysinfo['phpxml'] = 'true';
		}

		if ( $remote_checks == true ) {
			$response = wp_remote_post(
				'https://www.paypal.com/cgi-bin/webscr', array(
					'sslverify'  => false,
					'timeout'    => 60,
					'user-agent' => 'MkFramework/',
					'body'       => array(
						'cmd' => '_notify-validate',
					),
				)
			);

			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
				$sysinfo['wp_remote_post']       = 'true';
				$sysinfo['wp_remote_post_error'] = '';
			} else {
				$sysinfo['wp_remote_post'] = 'false';

				try {
					if ( is_wp_error( $response ) ) {
						$sysinfo['wp_remote_post_error'] = $response->get_error_message();
					}
				} catch ( Exception $e ) {

					$sysinfo['wp_remote_post_error'] = $e->getMessage();
				}
			}

			$response = wp_remote_get( 'http://reduxframework.com/wp-admin/admin-ajax.php?action=get_redux_extensions' );

			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
				$sysinfo['wp_remote_get']       = 'true';
				$sysinfo['wp_remote_get_error'] = '';
			} else {
				$sysinfo['wp_remote_get'] = 'false';

				try {
					if ( is_wp_error( $response ) ) {
						$sysinfo['wp_remote_get_error'] = $response->get_error_message();
					}
				} catch ( Exception $e ) {

					$sysinfo['wp_remote_get_error'] = $e->getMessage();
				}
			}
		}

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		$sysinfo['plugins'] = array();

		foreach ( $active_plugins as $plugin ) {
			$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$plugin_name = esc_html( $plugin_data['Name'] );

			$sysinfo['plugins'][ $plugin_name ] = $plugin_data;
		}

		$active_theme = wp_get_theme();

		$sysinfo['theme']['name']       = $active_theme->Name;
		$sysinfo['theme']['version']    = $active_theme->Version;
		$sysinfo['theme']['author_uri'] = $active_theme->{'Author URI'};
		$sysinfo['theme']['is_child']   = self::makeBoolStr( is_child_theme() );

		if ( is_child_theme() ) {
			$parent_theme = wp_get_theme( $active_theme->Template );

			$sysinfo['theme']['parent_name']       = $parent_theme->Name;
			$sysinfo['theme']['parent_version']    = $parent_theme->Version;
			$sysinfo['theme']['parent_author_uri'] = $parent_theme->{'Author URI'};
		}

		return $sysinfo;
	}
}

new mk_control_panel();
