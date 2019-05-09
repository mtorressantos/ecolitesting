<?php
// error_reporting( E_ALL );
// ini_set( 'display_errors', 1 );
/**
 * This class is responsible manage all jupiter templates
 * it will communicate with artbees API and get list of templates , install them or remove them.
 *
 * @author       Reza Marandi <ross@artbees.net>
 * @copyright    Artbees LTD (c)
 *
 * @link         http://artbees.net
 * @since        5.4
 * @since        6.0.3 Replace importThemeContent with new SSE process. Suppress warning.
 *
 * @version      1.0
 *
 * @SuppressWarnings(PHPMD)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.ExitExpression)
 * - We need exit warning to cut SSE process, if not it will continue the process.
 */

class mk_template_managememnt {

	private $layer_slider_slug = 'layerslider';
	private $logger;
	private $theme_name;

	public function setThemeName( $theme_name ) {
		$this->theme_name = $theme_name;
	}

	public function getThemeName() {
		return $this->theme_name;
	}

	private $api_url;

	public function setApiURL( $api_url ) {
		$this->api_url = $api_url;
	}

	public function getApiURL() {
		return $this->api_url;
	}

	private $template_id;

	public function setTemplateID( $template_id ) {
		$this->template_id = $template_id;
	}

	public function getTemplateID() {
		return intval( $this->template_id );
	}

	private $template_name;

	public function setTemplateName( $template_name ) {
		$this->template_name = $template_name;
	}

	public function getTemplateName() {
		return strtolower( $this->template_name );
	}

	private $template_file_name;

	public function setTemplateFileName( $template_file_name ) {
		$this->template_file_name = $template_file_name;
	}

	public function getTemplateFileName() {
		return $this->template_file_name;
	}

	private $template_remote_address;

	public function setTemplateRemoteAddress( $template_remote_address ) {
		$this->template_remote_address = $template_remote_address;
	}

	public function getTemplateRemoteAddress() {
		return $this->template_remote_address;
	}

	private $template_content_file_name;

	public function setTemplateContentFileName( $template_content_file_name ) {
		$this->template_content_file_name = $template_content_file_name;
	}

	public function getTemplateContentFileName() {
		return $this->template_content_file_name;
	}

	private $widget_file_name;

	public function setWidgetFileName( $widget_file_name ) {
		$this->widget_file_name = $widget_file_name;
	}

	public function getWidgetFileName() {
		return $this->widget_file_name;
	}

	private $options_file_name;

	public function setOptionsFileName( $options_file_name ) {
		$this->options_file_name = $options_file_name;
	}

	public function getOptionsFileName() {
		return $this->options_file_name;
	}

	/**
	 * Customizer filename.
	 *
	 * @since 6.0.3
	 * @var string
	 */
	private $customizer_file_name;

	/**
	 * Set Customizer filename.
	 *
	 * @since 6.0.3
	 * @param string $customizer_file_name Customizer filename.
	 */
	public function set_customizer_file_name( $customizer_file_name ) {
		$this->customizer_file_name = $customizer_file_name;
	}

	/**
	 * Get Customizer filename.
	 *
	 * @since 6.0.3
	 * @return string Customizer filename.
	 */
	public function get_customizer_file_name() {
		return $this->customizer_file_name;
	}

	/**
	 * Header Builder filename.
	 *
	 * @since 6.0.3
	 * @var string
	 */
	private $header_builder_file_name;

	/**
	 * Set Header Builder filename.
	 *
	 * @since 6.0.3
	 * @param string $header_builder_file_name Header Builder filename.
	 */
	public function set_header_builder_file_name( $header_builder_file_name ) {
		$this->header_builder_file_name = $header_builder_file_name;
	}

	/**
	 * Get Header Builder filename.
	 *
	 * @since 6.0.3
	 * @return string Header Builder filename.
	 */
	public function get_header_builder_file_name() {
		return $this->header_builder_file_name;
	}

	private $json_file_name;

	public function setJsonFileName( $json_file_name ) {
		$this->json_file_name = $json_file_name;
	}

	public function getJsonFileName() {
		return $this->json_file_name;
	}

	private $upload_dir;

	public function setUploadDir( $upload_dir ) {
		$this->upload_dir = $upload_dir;
	}

	public function getUploadDir() {
		return $this->upload_dir;
	}

	private $base_path;

	public function setBasePath( $base_path ) {
		$this->base_path = $base_path;
	}

	public function getBasePath() {
		return $this->base_path;
	}

	private $base_url;

	public function setBaseUrl( $base_url ) {
		$this->base_url = $base_url;
	}

	public function getBaseUrl() {
		return $this->base_url;
	}

	private $message;

	public function setMessage( $message ) {
		$this->message = $message;
	}

	public function getMessage() {
		return $this->message;
	}

	private $system_test_env;

	public function setSystemTestEnv( $system_test_env ) {
		$this->system_test_env = $system_test_env;
	}

	public function getSystemTestEnv() {
		return $this->system_test_env;
	}

	private $ajax_mode;

	public function setAjaxMode( $ajax_mode ) {
		$this->ajax_mode = $ajax_mode;
	}

	public function getAjaxMode() {
		return $this->ajax_mode;
	}
	/**
	 * Construct.
	 * it will add_actions if class created on ajax mode.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param bool $system_text_env if you want to create an instance of this method for phpunit it should be true
	 * @param bool $ajax_mode       if you need this method as ajax mode set true
	 */
	public function __construct( $system_test_env = false, $ajax_mode = true ) {

		@ini_set( 'max_execution_time', 0 );
		@ini_set( 'memory_limit', '256M' );
		@set_time_limit( 0 );

		$this->setThemeName( 'Jupiter' );
		$this->setSystemTestEnv( $system_test_env );
		$this->setAjaxMode( $ajax_mode );

		$this->logger = new Devbees\BeesLog\logger();

		$this->setApiURL( V2ARTBEESAPI );

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

		$this->setUploadDir( wp_upload_dir() );
		$this->setBasePath( $this->getUploadDir()['basedir'] . '/mk_templates/' );
		$this->setBaseUrl( $this->getUploadDir()['baseurl'] . '/mk_templates/' );

		$this->setTemplateContentFileName( 'theme_content.xml' );
		$this->setWidgetFileName( 'widget_data.wie' );
		$this->setOptionsFileName( 'options.txt' );
		$this->set_customizer_file_name( 'customizer.json' );
		$this->set_header_builder_file_name( 'header-builder.json' );
		$this->setJsonFileName( 'package.json' );

		global $wpdb;

		if ( ! defined( 'MK_LOAD_IMPORTERS' ) ) {
			define( 'MK_LOAD_IMPORTERS', true );
		}

		if ( $this->getAjaxMode() == true ) {
			add_action( 'wp_ajax_abb_template_lazy_load', array( &$this, 'loadTemplatesFromApi' ) );
			add_action( 'wp_ajax_abb_install_template_procedure', array( &$this, 'installTemplateProcedure' ) );

			// Action only for importing theme content with Server-Sent Event.
			add_action( 'wp_ajax_abb_install_template_sse', array( &$this, 'import_theme_content_sse' ) );

			add_action( 'wp_ajax_abb_get_templates_categories', array( &$this, 'getTemplateCategoryListFromApi' ) );
			add_action( 'wp_ajax_abb_restore_latest_db', array( &$this, 'restoreLatestDB' ) );
			add_action( 'wp_ajax_abb_is_restore_db', array( &$this, 'isRestoreDB' ) );

			add_action( 'wp_ajax_abb_uninstall_template', array( &$this, 'uninstallTemplate' ) );
			add_action( 'wp_ajax_abb_check_system_resource', array( &$this, 'checkSystemResource' ) );

			add_action( 'wp_ajax_abb_check_ftp_credentials', array( &$this, 'checkFtpCredentials' ) );
			add_action( 'wp_ajax_abb_get_template_psd_link', array( &$this, 'get_template_psd_link' ) );
		}
	}
	public function installTemplateProcedure() {
		$template_id = (isset( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : 0);
		$this->setTemplateID( $template_id );
		$template_name = (isset( $_POST['template_name'] ) ? $_POST['template_name'] : null);
		$import_media = (isset( $_POST['import_media'] ) ? $_POST['import_media'] : false);
		$type = (isset( $_POST['type'] ) ? $_POST['type'] : null);
		if ( is_null( $template_name ) || is_null( $type ) ) {
			$this->message( 'System problem at installing , please contact support', false );
			return false;
		}

		switch ( $type ) {
			case 'preparation':
				$this->preparation( $template_name );
				break;
			case 'backup_db':
				$this->backupDB();
				break;
			case 'backup_media_records':
				$this->backup_media_records();
				break;
			case 'restore_media_records':
				$this->restore_media_records();
				break;
			case 'reset_db':
				$this->resetDB();
				break;
			case 'upload':
				$this->uploadTemplateToServer( $template_name );
				break;
			case 'unzip':
				$this->unzipTemplateInServer( $template_name );
				break;
			case 'validate':
				$this->validateTemplateFiles( $template_name );
				break;
			case 'plugin':
				$this->installRequiredPlugins( $template_name );
				break;
			case 'theme_content':
				$this->importThemeContent( $template_name , $import_media );
				break;
			case 'menu_locations':
				$this->importMenuLocations( $template_name );
				break;
			case 'setup_pages':
				$this->setUpPages( $template_name );
				break;
			case 'theme_options':
				$this->importThemeOptions( $template_name );
				break;
			case 'customizer':
				$this->import_customizer( $template_name );
				break;
			case 'header_builder':
				$this->import_header_builder( $template_name );
				break;
			case 'theme_widget':
				$this->importThemeWidgets( $template_name );
				break;
			case 'finalize':
				$this->finalizeImporting( $template_name );
				break;
		}
	}
	public function reinitializeData( $template_name ) {
		try {
			if ( empty( $template_name ) ) {
				throw new Exception( 'Choose template first' );
			}
			$this->setTemplateName( $template_name );
			if (
				file_exists( $this->getAssetsAddress( 'template_content_path', $this->getTemplateName() ) ) == false ||
				file_exists( $this->getAssetsAddress( 'widget_path', $this->getTemplateName() ) ) == false ||
				file_exists( $this->getAssetsAddress( 'options_path', $this->getTemplateName() ) ) == false ||
				file_exists( $this->getAssetsAddress( 'json_path', $this->getTemplateName() ) ) == false
			) {
				throw new Exception( 'Template assets are not completely exist - p1, Contact support.' );
			} else {
				return true;
			}
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/**
	 * Reinitilize Template file is exist or not for SEE request.
	 *
	 * @since 6.0.3
	 *
	 * @throws Exception If template name empty.
	 * @throws Exception If template file is not exist.
	 *
	 * @param  string $template_name The template name will be imported.
	 * @param  string $template_id   The template ID will be imported.
	 * @return boolean               File status.
	 */
	public function reinitialize_data_sse( $template_name, $template_id ) {
		try {

			// Check template name and ID.
			if ( empty( $template_name ) || empty( $template_id ) ) {
				throw new Exception( 'Choose template first!' );
			}

			$this->setTemplateName( $template_name );
			$this->setTemplateID( $template_id );

			// Check template file exist or not.
			if ( false === file_exists( $this->getAssetsAddress( 'template_content_path', $this->getTemplateName() ) ) ) {
				throw new Exception( 'Template content is not exist - p1, Contact support.' );
			}

			return true;
		} catch ( Exception $e ) {
			$this->message_sse( $e->getMessage(), true );
			exit;
		}
	}

	/**
	 * Method that is resposible to pass plugin list to UI base on lazy load condition.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $_POST[from]  from number.
	 * @param str $_POST[count] how many.
	 *
	 * @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	public function loadTemplatesFromApi() {
		try {
			$from = (isset( $_POST['from'] ) ? $_POST['from'] : null);
			$count = (isset( $_POST['count'] ) ? $_POST['count'] : null);
			$template_id = (isset( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : 0);
			$template_name = (isset( $_POST['template_name'] ) ? $_POST['template_name'] : null);
			$template_category = (isset( $_POST['template_category'] ) ? $_POST['template_category'] : null);
			if ( is_null( $from ) || is_null( $count ) ) {
				throw new Exception( 'System problem , please contact support', 1001 );
				return false;
			}
			$getTemplateListArgs = [
				'pagination_start' => $from,
				'pagination_count' => $count,
				'template_category' => $template_category,
				'template_name' => $template_name,
				'template_id' => $template_id,
			];
			$list_of_templates = $this->getTemplateListFromApi( $getTemplateListArgs );

			if ( ! is_array( $list_of_templates ) ) {
				throw new Exception( 'Template list is not what we expected' );
			}

			$mk_db_management = new mk_db_management();
			$backups = $mk_db_management->is_restore_db();
			$this->message(
				'Successfull', true, array(
					'templates' => $list_of_templates,
					'backups' => $backups,
				)
			);
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function preparation( $template_name ) {
		try {
			$this->message( 'All is ready.', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function backupDB() {
		try {
			$mk_db_management = new mk_db_management();
			$dm_response = $mk_db_management->backup_db();
			if ( false == $dm_response ) {
				throw new Exception( $mk_db_management->get_error_message() );
			}

			$this->message( 'Backup created.', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function backup_media_records() {
		try {
			$mk_db_management = new mk_db_management();

			$dm_response = $mk_db_management->backup_media_records();

			if ( false == $dm_response ) {
				throw new Exception( $mk_db_management->get_error_message() );
			}
			$this->message( 'Media records backup created.', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function restore_media_records() {
		try {
			$mk_db_management = new mk_db_management();

			$dm_response = $mk_db_management->restore_media_records();

			if ( false == $dm_response ) {
				throw new Exception( $mk_db_management->get_error_message() );
			}
			$this->message( 'Media records restored successfully', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function isRestoreDB() {
		try {
			$mk_db_management = new mk_db_management();
			$result = $mk_db_management->is_restore_db();
			if ( is_array( $result ) ) {
				$this->message( 'Successfull', true, $result );
				return true;
			} else {
				throw new Exception( 'Result is not what we expected' );
			}
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function restoreLatestDB() {
		try {
			$mk_db_management = new mk_db_management();
			$return = $mk_db_management->restore_latest_db();
			if ( false == $return ) {
				throw new Exception( $mk_db_management->get_error_message() );
			}
			mk_purge_cache_actions();
			$this->message( 'Restore completed!', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function resetDB() {
		try {
			$tables = array(
				'comments',
				'commentmeta',
				'links',
				// 'options',
				'postmeta',
				'posts',
				'term_relationships',
				'termmeta',
				'terms',
				'term_taxonomy',
			);

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				$tables[] = 'icl_translations';
			}

			$this->resetWordpressDatabase( $tables, array(), false );
			$this->message( 'Database reseted', true );

			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function uploadTemplateToServer( $template_name ) {
		try {
			$this->setTemplateName( $template_name );
			$getTemplateName = $this->getTemplateName();
			if ( empty( $getTemplateName ) ) {
				throw new Exception( 'Choose one template first' );
			}
			$url = $this->getTemplateDownloadLink( $this->getTemplateName() , 'download' );
			$template_file_name = $this->getTemplateDownloadLink( $this->getTemplateName() , 'filename' );
			$this->setTemplateRemoteAddress( $url );
			if ( filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
				throw new Exception( 'Template source URL is not validate' );
			}
			Abb_Logic_Helpers::uploadFromURL( $this->getTemplateRemoteAddress(), $template_file_name ,$this->getBasePath() );
			$this->message( 'Uploaded to server', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	public function unzipTemplateInServer( $template_name ) {
		try {
			$this->setTemplateName( $template_name );
			$getTemplateName = $this->getTemplateName();
			if ( empty( $getTemplateName ) ) {
				throw new Exception( 'Choose one template first' );
			}

			$response = $this->getTemplateDownloadLink( $this->getTemplateName() , 'filename' );

			$this->setTemplateFileName( $response );

			$mkfs = new Mk_Fs(
				array(
					'context' => $this->getBasePath(),
				)
			);

			if ( $mkfs->get_error_code() ) {
				throw new Exception( $mkfs->get_error_message() );
				return false;
			}

			if ( ! $mkfs->exists( $this->getBasePath() . $this->getTemplateName() ) ) {
				Abb_Logic_Helpers::unZip( $this->getBasePath() . $this->getTemplateFileName(), $this->getBasePath() );
			} else {
				if ( $mkfs->rmdir( $this->getBasePath() . $this->getTemplateName(), true ) ) {
					Abb_Logic_Helpers::unZip( $this->getBasePath() . $this->getTemplateFileName(), $this->getBasePath() );
				}
			}

			@$mkfs->delete( $this->getBasePath() . $this->getTemplateFileName() );

			$this->message( 'Completed', true );

			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function validateTemplateFiles( $template_name ) {
		try {
			if ( empty( $template_name ) ) {
				throw new Exception( 'Choose template first' );
			}

			$mkfs = new Mk_Fs(
				array(
					'context' => $this->getBasePath(),
				)
			);

			if ( $mkfs->get_error_code() ) {
				throw new Exception( $mkfs->get_error_message() );
				return false;
			}

			$this->setTemplateName( $template_name );
			if (
				$mkfs->exists( $this->getAssetsAddress( 'template_content_path', $this->getTemplateName() ) ) == false ||
				$mkfs->exists( $this->getAssetsAddress( 'widget_path', $this->getTemplateName() ) ) == false ||
				$mkfs->exists( $this->getAssetsAddress( 'options_path', $this->getTemplateName() ) ) == false ||
				$mkfs->exists( $this->getAssetsAddress( 'json_path', $this->getTemplateName() ) ) == false
			) {
				throw new Exception( 'Template assets are not completely exist - p2, Contact support.' );
			} else {
				$this->message( 'Completed', true );
				return true;
			}
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function installRequiredPlugins( $template_name ) {
		$this->reinitializeData( $template_name );
		try {
			$json_url = $this->getAssetsAddress( 'json_url', $this->getTemplateName() );
			$json_path = $this->getAssetsAddress( 'json_path', $this->getTemplateName() );
			$response = Abb_Logic_Helpers::getFileBody( $json_url, $json_path );
			$plugins = json_decode( $response, true );

			if ( is_array( $plugins['required_plugins'] ) == false || count( $plugins['required_plugins'] ) <= 0 ) {
				throw new Exception( 'Plugin set have wrong structure' );
			}
			$mk_plugin_management = new mk_plugin_management( false, false );
			$pm_response = $mk_plugin_management->install_batch( $plugins['required_plugins'] );
			if ( false == $pm_response ) {
				$pm_response = $mk_plugin_management->get_response_message();
				throw new Exception( $pm_response );
			}

			$message = '{param} plugins are installed.' ;
			$this->message( array( $message, count( $plugins['required_plugins'] ) ), true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/**
	 * Import theme content via Server-Sent Events request.
	 *
	 * @since 6.0.3
	 *
	 * @throws Exception If template data is empty.
	 * @throws Exception If preliminary data is empty.
	 */
	public function import_theme_content_sse() {
		try {
			/*
			 * Filter data input from GET method. Eventsource doesn't allow us to use
			 * POST method.
			 */
			$template_name = '';
			if ( ! empty( $_GET['template_name'] ) ) { // WPCS: XSS ok, CSRF ok.
				$template_name = sanitize_text_field( $_GET['template_name'] );
			}

			$template_id = '';
			if ( ! empty( $_GET['template_id'] ) ) { // WPCS: XSS ok, CSRF ok.
				$template_id = sanitize_text_field( $_GET['template_id'] );
			}

			$fetch_attachments = 'false';
			if ( ! empty( $_GET['fetch_attachments'] ) ) { // WPCS: XSS ok, CSRF ok.
				$fetch_attachments = sanitize_text_field( $_GET['fetch_attachments'] );
			}

			// Include wordpress-importer class.
			Abb_Logic_Helpers::include_wordpress_importer();
			$this->reinitialize_data_sse( $template_name, $template_id );

			// Set importer options as an array.
			$options = array(
				'fetch_attachments' => filter_var( $fetch_attachments, FILTER_VALIDATE_BOOLEAN ),
				'default_author'    => get_current_user_id(),
			);

			// Create new instance for Importer.
			$importer = new WXR_Importer( $options );
			$logger   = new WP_Importer_Logger_ServerSentEvents();
			$importer->set_logger( $logger );

			// Get preliminary information.
			$file     = $this->getAssetsAddress( 'template_content_path', $this->getTemplateName() );
			$pre_data = $importer->get_preliminary_information( $file );
			if ( is_wp_error( $pre_data ) ) {
				throw new Exception( $pre_data->get_error_message() );
			}

			// @codingStandardsIgnoreStart
			// Turn off PHP output compression, allow us to print the log.
			$previous = error_reporting( error_reporting() ^ E_WARNING );
			ini_set( 'output_buffering', 'off' );
			ini_set( 'zlib.output_compression', false );
			error_reporting( $previous );
			// @codingStandardsIgnoreEnd

			if ( $GLOBALS['is_nginx'] ) {
				// Setting this header instructs Nginx to disable fastcgi_buffering
				// and disable gzip for this request.
				header( 'X-Accel-Buffering: no' );
				header( 'Content-Encoding: none' );
			}

			// Start the event stream here to record all the logs.
			header( 'Content-Type: text/event-stream' );
			header( 'Cache-Control: no-cache' );

			// Time to run the import!
			set_time_limit( 0 );

			// Ensure we're not buffered.
			wp_ob_end_flush_all();
			flush();

			// Run import process.
			$process = $importer->import( $file );

			// Setup complete response.
			$complete = array(
				'status'  => true,  // The process is complete no matter success or not.
				'error'   => false, // Message error if any.
				'data'    => null,  // Compatibility with current Ajax.
				'message' => 'Template contents were imported.',
			);

			// Check if the request is error, then set the message.
			if ( is_wp_error( $process ) ) {
				$complete['error'] = $process->get_error_message();
			}

			$this->message_sse( $complete );
			exit;

		} catch ( Exception $e ) {
			$this->message_sse( $e->getMessage(), true );
			exit;
		}
	}

	/**
	 * Get package.json data.
	 *
	 * @since 6.1.2
	 */
	public function getPackageJsonData( $template_name ) {

		$this->setTemplateName( $template_name );
		$json_url  = $this->getAssetsAddress( 'json_url', $this->getTemplateName() );
		$json_path = $this->getAssetsAddress( 'json_path', $this->getTemplateName() );
		$response  = Abb_Logic_Helpers::getFileBody( $json_url, $json_path );

		return json_decode( $response, true );
	}

	/**
	 * Send a Server-Sent Events message.
	 *
	 * @since 6.0.3
	 *
	 * @param mixed   $message     Data to be JSON-encoded and sent in the message.
	 * @param boolean $need_header Send response along with the header.
	 */
	public function message_sse( $message, $need_header = false ) {
		// Add header to start event stream only if needed.
		if ( $need_header ) {
			// Start the event stream.
			header( 'Content-Type: text/event-stream' );
			header( 'Cache-Control: no-cache' );
		}

		// Convert any message data as an array.
		if ( ! is_array( $message ) ) {
			$message = array(
				'message' => $message,
			);
		}

		// Set message event and pass the data.
		echo "event: message\n";
		echo 'data: ' . wp_json_encode( $message ) . "\n\n";

		flush();
	}

	public function importThemeContent( $template_name, $fetch_attachments = false ) {
		try {

			// Include wordpress-importer class.
			Abb_Logic_Helpers::include_wordpress_importer();
			$this->reinitializeData( $template_name );

			// Set importer options as an array.
			$options = array(
				'fetch_attachments' => filter_var( $fetch_attachments, FILTER_VALIDATE_BOOLEAN ),
				'default_author'    => get_current_user_id(),
			);

			// Create new instance for Importer.
			$importer = new WXR_Importer( $options );
			$logger   = new WP_Importer_Logger_ServerSentEvents();
			$importer->set_logger( $logger );

			// Get preliminary information.
			$file = $this->getAssetsAddress( 'template_content_path', $this->getTemplateName() );
			$data = $importer->get_preliminary_information( $file );
			if ( is_wp_error( $data ) ) {
				$this->message( 'Error in parsing theme_content.xml!', false );
				return false;
			}

			// Run import process.
			ob_start();
			$importer->import( $file );
			ob_end_clean();

			$this->message( 'Template contents were imported.', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	function importMenuLocations( $template_name ) {
		try {

			$set_menus = $this->getPackageJsonData( $template_name );

			if ( ! empty( $set_menus['menu_locations'] ) && is_array( $set_menus['menu_locations'] ) && count( $set_menus['menu_locations'] ) > 0 ) {

				foreach ( $set_menus['menu_locations'][0] as $location => $menu_name ) {
					$locations = get_theme_mod( 'nav_menu_locations' );
					$menus     = wp_get_nav_menus();
					if ( $menus ) {
						foreach ( $menus as $menu ) {
							if ( $menu->name == $menu_name ) {
								$locations[ $location ] = $menu->term_id;
							}
							set_theme_mod( 'nav_menu_locations', $locations );
						}
					}
				}
			} else {

				$locations = get_theme_mod( 'nav_menu_locations' );
				$menus     = wp_get_nav_menus();
				if ( $menus ) {
					foreach ( $menus as $menu ) {
						if (
							'Main Navigation' == $menu->name ||
							'main navigation' == $menu->name ||
							'Main' == $menu->name ||
							'Main Menu' == $menu->name ||
							'main menu' == $menu->name ||
							'main' == $menu->name
						) {
							$locations['primary-menu'] = $menu->term_id;
						}

						set_theme_mod( 'nav_menu_locations', $locations );
					}
				}
			}

			$this->message( 'Navigation locations is configured.', true );

			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}// End try().
	}


	public function setUpPages( $template_name ) {
		try {
			$package_data = $this->getPackageJsonData( $template_name );

			if ( ! empty( $package_data['homepage_title'] ) ) {
				$homepage = get_page_by_title( $package_data['homepage_title'] );
			} else {
				$homepage = get_page_by_title( 'Homepage' );
				if ( empty( $homepage->ID ) ) {
					$homepage = get_page_by_title( 'home' );
				}
			}

			if ( ! empty( $homepage->ID ) ) {
				update_option( 'page_on_front', $homepage->ID );
				update_option( 'show_on_front', 'page' );
				$home_page_response = true;
			}

			if ( ! empty( $package_data['shop_title'] ) ) {
				$shop_page = get_page_by_title( $package_data['shop_title'] );
			} else {
				$shop_page = get_page_by_title( 'Shop' );
			}
			if ( ! empty( $shop_page->ID ) ) {
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
				$shop_page_response = true;
			}

			// Set Cart page.
			$cart_page = get_page_by_title( 'Cart' );
			if ( ! empty( $cart_page->ID ) ) {
				update_option( 'woocommerce_cart_page_id', $cart_page->ID );
			}

			// Set Checkout page.
			$checkout_page = get_page_by_title( 'Checkout' );
			if ( ! empty( $checkout_page->ID ) ) {
				update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
			}

			// 'Default homepage is configured.'; 'Shop Page is configured.';
			if ( isset( $home_page_response ) && isset( $shop_page_response ) ) {
				$response = 'Default homepage and Shop Page is configured.';
			} elseif ( ! isset( $home_page_response ) && isset( $shop_page_response ) ) {
				$response = 'Shop Page is configured.';
			} elseif ( isset( $home_page_response ) && ! isset( $shop_page_response ) ) {
				$response = 'Default homepage is configured.';
			} else {
				$response = 'Setup pages completed.';
			}
			$this->message( $response, true );

			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}// End try().
	}
	public function importThemeOptions( $template_name ) {
		try {
			$this->reinitializeData( $template_name );
			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->getAssetsAddress( 'options_url', $this->getTemplateName() ),
				$this->getAssetsAddress( 'options_path', $this->getTemplateName() )
			);
			$data = unserialize( base64_decode( $import_data ) );
			if ( empty( $data ) == false ) {
				unset( $data['custom_js'], $data['twitter_consumer_key'], $data['google_maps_api_key'], $data['twitter_consumer_secret'], $data['twitter_access_token'], $data['twitter_access_token_secret'], $data['typekit_id'], $data['analytics'], $data['quick_contact_email'] );
				update_option( THEME_OPTIONS, $data );

				$this->message( 'Theme options are imported.', true );
				return true;
			} else {
				throw new Exception( 'Template options is empty' );
				return false;
			}
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	/**
	 * Import Customizer options.
	 *
	 * @since  6.0.3
	 * @param  string $template_name Name of template.
	 * @return mixed
	 * @throws Exception When Customizer file is empty.
	 */
	public function import_customizer( $template_name ) {
		try {
			$this->reinitializeData( $template_name );
			if ( ! file_exists( $this->getAssetsAddress( 'customizer_path', $this->getTemplateName() ) ) ) {
				$this->message( 'Customizer file is not found.', true );
				return true;
			}
			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->getAssetsAddress( 'customizer_url', $this->getTemplateName() ),
				$this->getAssetsAddress( 'customizer_path', $this->getTemplateName() )
			);
			$data = json_decode( $import_data, true );
			if ( false === empty( $data ) ) {
				update_option( 'mk_cz', $data );

				$this->message( 'Customizer is imported.', true );
				return true;
			}
			throw new Exception( 'Customizer is empty.' );
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	/**
	 * Import Header Builder option.
	 *
	 * @since  6.0.3
	 * @param  [type] $template_name Name of template.
	 * @return mixed
	 * @throws Exception When Header Builder file is empty.
	 */
	public function import_header_builder( $template_name ) {
		try {
			$this->reinitializeData( $template_name );
			if ( ! file_exists( $this->getAssetsAddress( 'header_builder_path', $this->getTemplateName() ) ) ) {
				$this->message( 'Header builder file is not found.', true );
				return true;
			}
			$import_data = Abb_Logic_Helpers::getFileBody(
				$this->getAssetsAddress( 'header_builder_url', $this->getTemplateName() ),
				$this->getAssetsAddress( 'header_builder_path', $this->getTemplateName() )
			);
			$data = json_decode( $import_data, true );
			if ( false === empty( $data ) ) {
				update_option( 'mkhb_global_header', $data );

				$this->message( 'Header Builder is imported.', true );
				return true;
			}
			throw new Exception( 'Header Builder is empty' );
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function importThemeWidgets( $template_name ) {
		$this->reinitializeData( $template_name );
		try {
			$data = Abb_Logic_Helpers::getFileBody(
				$this->getAssetsAddress( 'widget_url', $this->getTemplateName() ),
				$this->getAssetsAddress( 'widget_path', $this->getTemplateName() )
			);
			$data = json_decode( $data );
			$this->import_widget_data( $data );

			$this->message( 'Widgets are imported.', true );

			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function finalizeImporting( $template_name ) {
		$this->reinitializeData( $template_name );
		$template_name = sanitize_title( $template_name );
		// Check if it had something to import.
		try {
			$json_url = $this->getAssetsAddress( 'json_url', $this->getTemplateName() );
			$json_path = $this->getAssetsAddress( 'json_path', $this->getTemplateName() );
			$response = Abb_Logic_Helpers::getFileBody( $json_url, $json_path );
			$plugins = json_decode( $response, true );
			if (
				empty( $plugins['importing_data'] ) == false &&
				is_array( $plugins['importing_data'] ) &&
				count( $plugins['importing_data'] ) > 0 ) {
				foreach ( $plugins['importing_data'] as $key => $value ) {
					switch ( $value['name'] ) {
						case 'layer-slider':
							$ls_content_path = $this->getBasePath() . $template_name . '/' . $value['content_path'];
							if ( file_exists( $ls_content_path ) ) {
								$this->importLayerSliderContent( $ls_content_path );
							}
							break;
					}
				}
			}

			if ( ! $this->cleanInstallFiles( $template_name ) ) {
				throw new Exception( 'Can not remove installation source files' );
				return false;
			}

			update_option( 'jupiter_template_installed', $this->getTemplateName() );
			update_option( 'jupiter_template_installed_id', $this->getTemplateID() );
			update_option( THEME_OPTIONS . '_imported', 'true' );

			$this->message( 'Data imported successfully', true );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}// End try().
	}

	/**
	 * Clean install files
	 *
	 * @param $template_name
	 * @author Reza Marandi
	 * @return bool
	 */
	private function cleanInstallFiles( $template_name ) {
		$mkfs = new Mk_Fs(
			array(
				'context' => $this->getBasePath(),
			)
		);

		// Deleting Template Source Folder.
		$template_path = $this->getBasePath() . sanitize_title( $template_name );
		if ( $mkfs->exists( $template_path ) && $mkfs->is_dir( $template_path ) && ! $mkfs->delete( $template_path, true ) ) {
			return false;
		}

		// Deleting Template Source Zip file.
		$template_zip = $template_path . '.zip';
		if ( $mkfs->exists( $template_zip ) && $mkfs->is_file( $template_zip ) && ! $mkfs->delete( $template_zip ) ) {
			return false;
		}

		return true;
	}
	public function uninstallTemplate() {
		try {
			$tables = array(
				'comments',
				'commentmeta',
				'links',
				'options',
				'postmeta',
				'posts',
				'term_relationships',
				'termmeta',
				'terms',
				'term_taxonomy',
			);

			$reset = $this->resetWordpressDatabase( $tables, array(), true );

			if ( ! $reset ) {
				throw new Exception( 'Failed to uninstall template. Please try again.' );
			}

			$this->message( 'Template uninstall success.', true );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );

			return false;
		}
	}
	public function availableWidgets() {
		global $wp_registered_widget_controls;
		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();
		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name'] = $widget['name'];
			}
		}

		return apply_filters( 'available_widgets', $available_widgets );
	}

	/**
	 * Import widgets' data.
	 *
	 * @throws Exception If can not read widget data.
	 *
	 * @since 5.7.0
	 *        6.0.4 Make it public.
	 * @param  array $data Widgets' data.
	 * @return boolean
	 */
	public function import_widget_data( $data ) {
		global $wp_registered_sidebars;

		$available_widgets = $this->availableWidgets();
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}
		if ( empty( $data ) || ! is_object( $data ) ) {
			throw new Exception( 'Widget data could not be read. Please try a different file.' );
		}
		$results = array();
		foreach ( $data as $sidebar_id => $widgets ) {
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}
			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message = '';
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets';
				$sidebar_message_type = 'error';
				$sidebar_message = 'Sidebar does not exist in theme (using Inactive)';
			}
			$results[ $sidebar_id ]['name'] = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
			$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
			$results[ $sidebar_id ]['message'] = $sidebar_message;
			$results[ $sidebar_id ]['widgets'] = array();
			foreach ( $widgets as $widget_instance_id => $widget ) {
				$fail = false;
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );
				if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
					$fail = true;
					$widget_message_type = 'error';
					$widget_message = 'Site does not support widget';
				}
				$widget = apply_filters( 'mk_widget_settings', $widget );
				if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array();
					$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
							$fail = true;
							$widget_message_type = 'warning';
							$widget_message = 'Widget already exists';
							break;
						}
					}
				}
				if ( ! $fail ) {
					$single_widget_instances = get_option( 'widget_' . $id_base );
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
						'_multiwidget' => 1,
					);
					$single_widget_instances[] = (array) $widget;
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}
					update_option( 'widget_' . $id_base, $single_widget_instances );
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$new_instance_id = $id_base . '-' . $new_instance_id_number;
					$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;
					update_option( 'sidebars_widgets', $sidebars_widgets );
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = 'Imported';
					} else {
						$widget_message_type = 'warning';
						$widget_message = 'Imported to Inactive';
					}
				}
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name'] = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base;
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title'] = ! empty( $widget->title ) ? $widget->title : '';
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message'] = $widget_message;
			}// End foreach().
		}// End foreach().

		return true;
	}
	/**
	 * It will empty all or custom database tables of WordPress and install WordPress again if needed.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param array $table          which table need to be empty ? example : array('user' , 'usermeta')
	 *                              table names should be without any prefix
	 * @param bool  $install_needed if WordPress need to be installed after reseting database
	 *                              it should be false or true
	 *
	 * @return bool return if everything looks good and throwing errors on problems
	 */
	public function resetWordpressDatabase( $tables = array(), $exclude_tables = array(), $install_needed = false ) {
		global $wpdb, $reactivate_wp_reset_additional, $current_user;

		if ( $install_needed ) {

			require_once ABSPATH . '/wp-admin/includes/upgrade.php';

			$new_options = array();

			$old_options = array(
				'artbees_api_key',
				'jupiter-data-tracking',
			);

			$blogname = get_option( 'blogname' );
			$blog_public = get_option( 'blog_public' );
			$site_url = site_url();
			$current_theme = wp_get_theme();

			foreach ( $old_options as $old_option_key ) {
				$new_options[ $old_option_key ] = get_option( $old_option_key );
			}

			if ( 'admin' != $current_user->user_login ) {
				$user = get_user_by( 'login', 'admin' );
			}

			if ( empty( $user->user_level ) || $user->user_level < 10 ) {
				$user = $current_user;
				$session_tokens = get_user_meta( $user->ID, 'session_tokens', true );
			}

			// Check if we need all the tables or specific table.
			if ( is_array( $tables ) && count( $tables ) > 0 ) {
				array_walk(
					$tables, function ( &$value, $key ) use ( $wpdb ) {
						$value = $wpdb->prefix . $value;
					}
				);
			} else {
				$prefix = str_replace( '_', '\_', $wpdb->prefix );
				$tables = $wpdb->get_col( "SHOW TABLES LIKE '{$prefix}%'" );
			}

			// exclude table if its valued.
			if ( is_array( $exclude_tables ) && count( $exclude_tables ) > 0 ) {
				array_walk(
					$exclude_tables, function ( &$ex_value, $key ) use ( $wpdb ) {
						$ex_value = $wpdb->prefix . $ex_value;
					}
				);
				$tables = array_diff( $tables, $exclude_tables );
			}
			// Removing data from WordPress tables.
			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE $table" );
			}

			$result = wp_install( $blogname, $user->user_login, $user->user_email, $blog_public );
			switch_theme( $current_theme->get_stylesheet() );

			/* GoDaddy Patch => GD have a problem of cleaning siteurl option value after reseting database */
			if ( site_url() == '' ) {
				$this->logger->debug( 'Fresh Installed WP' , 'Sounds like GDServers : ' . $site_url );
				$wpdb->update(
					$wpdb->options, array(
						'option_value' => $site_url,
					),array(
						'option_name' => 'siteurl',
					)
				);
				$this->logger->debug( 'Update Query' , $wpdb->last_query );
				$this->logger->debug( 'After Executing GD Patch' , site_url() );
			}
			extract( $result, EXTR_SKIP );

			$query = $wpdb->prepare( "UPDATE $wpdb->users SET user_pass = %s, user_activation_key = '' WHERE ID = %d", $user->user_pass, $user_id );
			$wpdb->query( $query );

			$get_user_meta = function_exists( 'get_user_meta' ) ? 'get_user_meta' : 'get_usermeta';
			$update_user_meta = function_exists( 'update_user_meta' ) ? 'update_user_meta' : 'update_usermeta';

			if ( $get_user_meta($user_id, 'default_password_nag') ) {
				$update_user_meta($user_id, 'default_password_nag', false);
			}

			if ( $get_user_meta($user_id, $wpdb->prefix . 'default_password_nag') ) {
				$update_user_meta($user_id, $wpdb->prefix . 'default_password_nag', false);
			}

			wp_clear_auth_cookie();
			wp_set_current_user( $user_id, $user->user_login );
			if ( $session_tokens ) {
				delete_user_meta( $user->ID, 'session_tokens' );
				update_user_meta( $user->ID, 'session_tokens', $session_tokens );
			}

			wp_set_auth_cookie( $user_id, true );
			do_action( 'wp_login', $user->user_login, $user );

			if ( $new_options ) {
				foreach ( $new_options as $key => $value ) {
					update_option( $key, $value );
				}
			}
			return true;
		} else {

			$jupiter_temp_installed = get_option( 'jupiter_template_installed' );

			if ( $jupiter_temp_installed ) {

				// Delete plugins.
				$json_url = $this->getAssetsAddress( 'json_url', $jupiter_temp_installed );
				$json_path = $this->getAssetsAddress( 'json_path', $jupiter_temp_installed );
				$response = Abb_Logic_Helpers::getFileBody( $json_url, $json_path );
				$plugins = json_decode( $response, true );
				if ( $plugins ) {
					$mk_plugin_management = new mk_plugin_management( false, false );
					foreach ( $plugins['required_plugins'] as $required_plugin ) {
						if ( ! $mk_plugin_management->deactivate_plugin( $required_plugin, true ) ) {
							throw new Exception( sprintf( 'Failed to deactivate plugin: %s', $required_plugin ) );
							return false;
						}
					}
				}

				// Delete option data for page_on_front.
				if ( get_option( 'page_on_front' ) ) {
					delete_option( 'page_on_front' );
				}

				// Delete option data for show_on_front.
				if ( get_option( 'show_on_front' ) ) {
					delete_option( 'show_on_front' );
				}

				// Delete option data for woocommerce_shop_page_id.
				if ( get_option( 'woocommerce_shop_page_id' ) ) {
					delete_option( 'woocommerce_shop_page_id' );
				}

				// Delete widgets
				$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%widget%';" );

			}// End if().

			// truncate tables.
			foreach ( $tables as $table ) {
				$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}{$table}" );
			}

			return true;
		}// End if().
	}

	private function setResponseForApiTemplateList( $url, $configs ) {

		$response = \Httpful\Request::get( $url )
			->addHeaders(
				array(
					'theme-name' => $this->getThemeName(),
					'pagination-start' => isset( $configs['pagination_start'] ) ? $configs['pagination_start'] : 0,
					'pagination-count' => isset( $configs['pagination_count'] ) ? $configs['pagination_count'] : 1,
				)
			);
		if ( isset( $configs['template_id'] ) && is_null( $configs['template_id'] ) == false ) {
			$response->addHeaders(
				array(
					'template-id' => $configs['template_id'],
				)
			);
		}
		if ( isset( $configs['template_name'] ) && is_null( $configs['template_name'] ) == false ) {
			$response->addHeaders(
				array(
					'template-name' => $configs['template_name'],
				)
			);
		}
		if ( isset( $configs['template_category'] ) && is_null( $configs['template_category'] ) == false ) {
			$response->addHeaders(
				array(
					'template-category' => $configs['template_category'],
				)
			);
		}
		return $response;
	}
	/**
	 * This method is resposible to get template list from api and create download link if template need to extract from WordPress repo.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $template_name if template name is valued it will return array of information about the this template
	 *                           but if template is valued as false it will return all templates information
	 *
	 * @return array will return array of templates
	 */
	public function getTemplateListFromApi( $configs ) {
		if ( ! is_array( $configs ) ) {
			$configs = array();
		}
		$url = $this->getApiURL() . 'theme/templates';
		$response = $this->setResponseForApiTemplateList( $url, $configs );
		$response = $response->send();
		if ( false == isset( $response->body->bool ) || false == $response->body->bool ) {
			throw new Exception( $response->body->message );
		}
		return $response->body->data;
	}
	public function getTemplateDownloadLink( $template_name = '', $type = 'download' ) {
		$url = $this->getApiURL() . 'theme/download-template';
		$response = \Httpful\Request::get( $url )
		->addHeaders(
			array(
				'template-name' => $template_name,
				'type' => $type,
			)
		)
		->send();
		if ( false == isset( $response->body->bool ) || false == $response->body->bool ) {
			throw new Exception( $response->body->message );
		}

		/**
		 * Filters the template download url.
		 *
		 * @since 6.0.3
		 * @param string $response->body->data Download url.
		 */
		return apply_filters( 'mk_template_download_url', $response->body->data );
	}

	/**
	 * Gets psd file download link.
	 *
	 * @return mixed
	 * @since 5.7.0
	 * @author Ugur Mirza Zeyrek
	 */
	public function get_template_psd_link() {
		$template_name = $_POST['template_name'];
		try {
			$response = $this->getTemplateDownloadLink( $template_name,'download-psd' );
			$this->message(
				'Successfull', true, array(
					'psd_link' => $response,
				)
			);
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}

	/**
	 * This method is resposible to get templates categories list from api
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $template_name if template name is valued it will return array of information about the this template.
	 * but if template is valued as false it will return all templates information.
	 *
	 * @return array will return array of plugins.
	 */
	public function getTemplateCategoryListFromApi() {
		try {
			$url = $this->getApiURL() . 'theme/template-categories';
			$response = \Httpful\Request::get( $url )->send();
			if ( false == isset( $response->body->bool ) || false == $response->body->bool ) {
				throw new Exception( $response->body->message );
			}
			$this->message( 'Successfull', true, $response->body->data );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}
	/**
	 * We need to make assets addresses dynamic and fully proccess.
	 * in one method for future development
	 * it will get the type of address and will return full address in string
	 * example :
	 * for (options_url) type , it will return something like this
	 * (http://localhost/jupiter/wp-content/uploads/mk_templates/dia/options.txt).
	 *
	 * For (options_path) type , it will return something like this.
	 * (/usr/apache/www/wp-content/uploads/mk_templates/dia/options.txt)
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $which_one     Which address do you need.
	 * @param str $template_name such as.
	 */
	public function getAssetsAddress( $which_one, $template_name ) {
		$template_name = sanitize_title( $template_name );
		switch ( $which_one ) {
			case 'template_content_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->getTemplateContentFileName();
			break;
			case 'template_content_path':
				return $this->getBasePath() . $template_name . '/' . $this->getTemplateContentFileName();
			break;
			case 'widget_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->getWidgetFileName();
			break;
			case 'widget_path':
				return $this->getBasePath() . $template_name . '/' . $this->getWidgetFileName();
			break;
			case 'options_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->getOptionsFileName();
			break;
			case 'options_path':
				return $this->getBasePath() . $template_name . '/' . $this->getOptionsFileName();
			break;
			case 'customizer_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->get_customizer_file_name();
			break;
			case 'customizer_path':
				return $this->getBasePath() . $template_name . '/' . $this->get_customizer_file_name();
			break;
			case 'header_builder_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->get_header_builder_file_name();
			break;
			case 'header_builder_path':
				return $this->getBasePath() . $template_name . '/' . $this->get_header_builder_file_name();
			break;
			case 'json_url':
				return $this->getBaseUrl() . $template_name . '/' . $this->getJsonFileName();
			break;
			case 'json_path':
				return $this->getBasePath() . $template_name . '/' . $this->getJsonFileName();
			break;
			default:
				throw new Exception( 'File name you are looking for is not introduced.' );

			return false;
			break;
		}
	}
	public function importLayerSliderContent( $content_path ) {
		global $wpdb;
		$plugin = new mk_plugin_management();
		$ls_path = $plugin->find_plugin_path( $this->layer_slider_slug );

		if ( $ls_path == false ) {
			throw new Exception( 'LayerSlider is not installed , install it first' );
			return false;
		}
		if ( $plugin->check_active_plugin( $this->layer_slider_slug ) == false ) {
			throw new Exception( 'LayerSlider is installed but not activated , activate it first' );
			return false;
		}
		// Empty layerslider table first.
		$table = $wpdb->prefix . 'layerslider';
		$wpdb->query( "TRUNCATE TABLE $table" );

		// Try to import configs.
		$ls_plugin_root_path = pathinfo( $plugin->get_plugins_dir() . $ls_path );
		include $ls_plugin_root_path['dirname'] . '/classes/class.ls.importutil.php';
		new LS_ImportUtil( $content_path );
		return true;
	}

	/**
	 * Check system resource based on max_execution_time and memory_limit.
	 * All response will be sent to message function.
	 *
	 * @return boolean True if successfull, false if error happens.
	 *
	 * @author Ayub Adiputra <ayub@artbees.net>
	 * @since 5.6
	 * @version 5.6
	 */
	public function checkSystemResource() {
		try {
			// Collect all parameters.
			$resources = array();
			$resources['max_execution_time'] = ini_get( 'max_execution_time' );
			$resources['memory_limit']       = ini_get( 'memory_limit' );

			// Check max_execution_time is less than 3000 s.
			if ( (int) $resources['max_execution_time'] > 0 && (int) $resources['max_execution_time'] < 3000 ) {
				throw new Exception( sprintf( 'Not enough max_execution_time (%s).' , $resources['max_execution_time'] ) );
			}

			// Check minimum memory_limit is 256 MB.
			$cur_memory_limit = $this->letToNum( $resources['memory_limit'] );
			$min_memory_limit = '268435456';
			if ( $cur_memory_limit < $min_memory_limit ) {
				throw new Exception( 'Not enough memory_limit.' );
			}

			$this->message( 'Successfull', true, $resources );
			return true;
		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/**
	 * Check FTP credentials
	 *
	 * @return boolean True if successfull, false if error happens.
	 *
	 * @author Sofyan Sitorus <sofyan@artbees.net>
	 * @since 5.8
	 * @version 5.8
	 */
	public function checkFtpCredentials() {

		try {

			$url = wp_nonce_url( 'admin.php?page=theme-templates','mk-check-ftp-credentials' );

			$upload_dir = wp_upload_dir();

			$context = trailingslashit( $upload_dir['basedir'] ) . 'mk-fs-creds/';

			$mkfs = new Mk_Fs(
				array(
					'form_post' => $url,
					'context' => $context,
					'extra_fields' => array(
						'mk_check_ftp_credentials' => 1,
					),
				), true
			);

			if ( $mkfs->get_error_code() ) {
				throw new Exception( $mkfs->get_error_message() );
				return false;
			}

			$mkfs->mkdir( $context );

			$config_file = $context . 'index.php';

			// Delete existing config file.
			if ( $mkfs->exists( $config_file ) ) {
				$mkfs->delete( $config_file );
			}

			$includes = array(
				'hostname',
				'username',
				'password',
				'public_key',
				'private_key',
				'connection_type',
			);

			// Create existing config file.
			$creds_data = [];
			foreach ( $_POST as $key => $value ) {
				if ( in_array( $key, $includes ) ) {
					$creds_data[ $key ] = Abb_Logic_Helpers::encrypt_decrypt( $value );
				}
			}

			$config_file_content = '<?php' . "\n" . '$mkfs_config = \'' . json_encode( $creds_data ) . '\';' . "\n";

			$mkfs->put_contents( $config_file, $config_file_content );

			$this->message( 'Successfull', true );

			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}

	/*====================== Helpers ============================*/
	/**
	 * This method is resposible to manage all the classes messages and act different on ajax mode or test mode.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str   $message for example ("Successfull").
	 * @param bool  $status  true or false.
	 * @param mixed $data    its for when ever you want to result back an array of data or anything else.
	 */
	public function message( $message, $status, $data = null ) {
		$response = array(
			'status' => $status,
			'message' => mk_logic_message_helper( 'template-management' , $message ),
			'data' => $data,
		);
		if ( $this->getAjaxMode() == true ) {
			header( 'Content-Type: application/json' );
			wp_die( json_encode( $response ) );
		} else {
			$this->setMessage( $response );
		}
	}

	/**
	 * Convert memory data to number. Copy from let_to_num in mk_control_panel class.
	 *
	 * @param  string $size Memory size in string format.
	 * @return int          Memory size in integer format.
	 * @see framework/admin/control-panel/logic/functions.php
	 *
	 * @since 5.6
	 * @version 5.6
	 */
	private function letToNum( $size ) {
		$let   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );

		switch ( strtoupper( $let ) ) {
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
}
/* Disable woocommerce redirection */
add_action( 'admin_init', 'disable_woocommerce', 5 );
/**
 * Disable Woocommerce redirect for template install
 *
 * @since 5.6
 * @version 5.6
 */
function disable_woocommerce() {
	delete_transient( '_wc_activation_redirect' );
	add_filter(
		'woocommerce_prevent_automatic_wizard_redirect', function() {
			return true;
		}
	);
}

add_filter(
	'pre_transient__wc_activation_redirect', function() {
		return 0;
	}
);

add_filter(
	'pre_transient__vc_page_welcome_redirect', function() {
		return 0;
	}
);

global $abb_phpunit;
if ( empty( $abb_phpunit ) || $abb_phpunit == false ) {
	new mk_template_managememnt();
}
