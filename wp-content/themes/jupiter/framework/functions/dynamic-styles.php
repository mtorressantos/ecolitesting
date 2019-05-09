<?php
/**
 * This file is responsible from all dynamic css and js proccess and minification.
 *
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       1.0.0
 * @since       5.9.3
 * @package     artbees
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Mk_Static_Files is responsible for dynamic styles.
 *
 * @copyright   Artbees LTD (c)
 * @since       1.0.0
 * @since       5.9.3
 * @package     artbees
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Mk_Static_Files {

	const THEME_OPTIONS_CSS = 'theme-options.css';

	/**
	 * Initialize base methods and variables.
	 *
	 * Mk_Static_Files constructor.
	 *
	 * @author      Bob Ulusoy & Ugur Mirza ZEYREK
	 * @param bool $with_actions
	 * @since       5.0.0
	 * @since       5.9.1
	 * @since       5.9.3
	 */
	public function __construct( $with_actions ) {

		global $mk_dev;
		global $theme_option;
		global $is_js_min;
		global $is_css_min;
		global $mk_lang;

		// Check if MK_DEV constant is set for debudding purposes.
		$is_js_min  = (defined( 'MK_DEV' ) ? ! constant( 'MK_DEV' ) : true);
		$is_css_min = (defined( 'MK_DEV' ) ? ! constant( 'MK_DEV' ) : true);
		// Paths for assets.
		$mk_dev       = (defined( 'MK_DEV' ) ? constant( 'MK_DEV' ) : false);
		$theme_option = get_option( THEME_OPTIONS );

		// Minify Static files.
		require_once THEME_INCLUDES . '/minify/src/Minifier.php';
		require_once THEME_INCLUDES . '/minify/src/SimpleCssMinifier.php';

		if ( $with_actions ) {
			$priority = 999;
			if ( ($mk_dev) != true ) {
				$priority = 11;
			}

			add_action( 'wp_enqueue_scripts', array( &$this, 'mk_enqueue_fonts' ), 10 );
			add_action( 'wp_head', array( &$this, 'critical_path_css' ), 1 );
			add_action( 'wp_enqueue_scripts', array( &$this, 'process_global_styles' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_default_stylesheet' ), 30 );
			if ( ! self::is_vc_editing() ) {
				add_action( 'wp_footer', array( &$this, 'move_short_styles_footer' ), 9 );
			}
			// if the theme options file isn't exists in order to access global variable we need to use different priority level.
			if ( get_option( 'mk_theme_options_css_file' . $mk_lang ) == '' ) {
				$priority = 999;
			}

			add_action( 'wp_enqueue_scripts', array( &$this, 'addThemeOptionsCSSToEnqueue' ), $priority );
			add_action( 'wp_head', array( &$this, 'dynamic_theme_options' ), 99999 );
		}
	}

	public function enqueue_default_stylesheet() {
		if ( is_child_theme() ) {
			wp_enqueue_style( 'mk-style', get_stylesheet_uri(), false, false, 'all' );
		}
	}

	public static function is_referer_admin_ajax() {
		global $pagenow;

		$result = in_array(
			$pagenow, array(
				'admin-ajax.php',
			)
		);

		if ( $result ) {
			return true;
		}
	}

	/**
	 * Updates timestamp for global assets.
	 *
	 * @author   Ugur Mirza ZEYREK
	 * @since    5.9.4
	 */
	public static function update_global_assets_timestamp() {

		$timestamp = time();
		update_option( 'global_assets_timestamp', $timestamp );

	}

	/**
	 * Takes the timestamp of the global assets. Creates if it's not yet created.
	 *
	 * @author   Ugur Mirza ZEYREK
	 * @since    5.0.5
	 * @since    5.9.3
	 * @since    5.9.4 Update timestamp moved to a new function
	 */
	public static function global_assets_timestamp() {

		$timestamp = get_option( 'global_assets_timestamp' );
		if ( ! is_numeric( $timestamp ) ) {
			self::update_global_assets_timestamp();
		}

		return $timestamp;
	}

	/**
	 * Google fonts
	 *
	 * @deprecated 5.9.3
	 */
	public function mk_enqueue_fonts() {
		return;
	}

	/**
	 * Append shortcode css files into app_dynamic_styles global variable
	 *
	 * @return mixed
	 */
	public static function shortcode_id() {

		if ( self::is_vc_editing() || self::is_referer_admin_ajax() ) {
			return uniqid();
		}

		global $mk_shortcode_order;
		$mk_shortcode_order++;
		return $mk_shortcode_order;
	}

	/**
	 * Define the global variables for dynamic shortcode styles, global and posts dynamic styles.
	 */
	public function init_globals() {
		$glob_dynamic_styles = $local_dynamic_styles = '';
		global $glob_dynamic_styles, $local_dynamic_styles;
	}

	/**
	 * Load the dynamic files. these files can be overrided in child themes if needed.
	 */
	public function include_files() {
		$styles_dir = get_template_directory() . '/dynamic-styles/*/*.php';

		$styles = glob( $styles_dir );

		foreach ( $styles as $style ) {
			include_once $style;
		}
	}

	/**
	 * Append shortcode css files into app_dynamic_styles global variable.
	 *
	 * @param    string $app_styles
	 * @param    int    $css_id
	 */
	public static function addCSS( $app_styles, $css_id ) {
		if ( self::is_vc_editing() ) {
			$minifier = new SimpleCssMinifier();
			$output = $minifier->minify( $app_styles );
			echo '<style id="mk-shortcode-style-' . $css_id . '" type="text/css">' . $output . '</style>';
			return;
		}

		global $mk_dynamic_styles;

		$mk_dynamic_styles[] = $app_styles;

		if ( self::is_referer_admin_ajax() ) {
			$minifier = new SimpleCssMinifier();
			$output   = $minifier->minify( $app_styles );
			echo '<style id="mk-shortcode-style-' . $css_id . '" type="text/css">' . $output . '</style>';
		}

	}

	/**
	 * Move dynamic shortcode styles to footer
	 *
	 * @param int $id
	 * @author      Bob Ulusoy
	 * @since       Version 5.9.3
	 */
	public function move_short_styles_footer() {
		if ( self::is_vc_editing() ) {
			return;
		}
		global $mk_dynamic_styles;

		if ( empty( $mk_dynamic_styles ) ) {
			return;
		}

		$conc_dynamic_styles = implode( '', $mk_dynamic_styles );
		$minifier                         = new SimpleCssMinifier();
		$output                           = $minifier->minify( $conc_dynamic_styles );
		echo '<style id="mk-shortcode-static-styles" type="text/css">' . $output . '</style>';
	}

	/**
	 * Appends short code names into the app_global_assets array.
	 *
	 * @param $shortcode_name
	 * @return bool
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @since       5.0.0
	 * @deprecated  5.9.3 This function does nothing but for child theme compatibility we will keep this method.
	 */
	public static function addAssets( $shortcode_name ) {
		if ( $shortcode_name ) {
			return true;
		}
		return false;
	}

	/**
	 * Stores the asset and updates the option if specified.
	 *
	 * @param $folder
	 * @param $filename
	 * @param $file_content
	 *
	 * @author      Ugur Mirza ZEYREK & Bob Ulusoy & Sheikh Danish Iqbal
	 * @since       5.0.0
	 * @since       5.8.0
	 * @since       5.9.3 Refactored.
	 */
	public static function StoreAsset( $folder, $filename, $file_content ) {
		global $mk_dev;

		$mkfs = new Mk_Fs(
			array(
				'context' => $folder,
			)
		);

		self::createPath( $folder );
		$sha1_concat_string = sha1( $file_content );
		$file_path          = path_convert( $folder . '' . $filename );
		if ( get_option( $filename . '_sha1' ) != $sha1_concat_string || ! $mkfs->exists( $file_path ) ) {
			$comment = ''; // define comment var.
			if ( $mk_dev ) {
				$comment = "\n /* $filename " . time() . " */ \n ";
			}

			$mkfs->put_contents( $file_path, $comment . $file_content );

			update_option( $filename . '_sha1', $sha1_concat_string );
		}
	}

	/**
	 * Stores the glob_dynamic_styles into the mk_assets/theme_options.css file
	 *
	 * @since 5.0.0
	 * @since 6.0.1 Add current time to filename.
	 * @param bool|true $minify
	 */
	public static function StoreThemeOptionStyles( $minify ) {
		global $glob_dynamic_styles;
		global $mk_dev;
		global $mk_options;
		global $dyn_theme_option_css;
		global $mk_lang;

		$extension = 'css';
		$time      = 'production-' . time();

		if ( $mk_dev ) {
			$time = 'dev';
		}

		$filename = str_replace( '.css', '-' . $time . $mk_lang . '.css', self::THEME_OPTIONS_CSS );
		$folder   = self::get_global_asset_upload_folder( 'directory' );
		$string   = $glob_dynamic_styles;

		if ( $minify && true != $mk_dev && 'false' != $mk_options[ 'minify-' . $extension ] ) {
			$string = self::minify_string( $glob_dynamic_styles, 'css' );
		}

		$file_path = $folder . '' . $filename;
		if ( self::deleteFile( $folder . $filename ) != true ) {
			wp_die( 'A problem occurred while trying to delete theme-options css file: ' . $file_path );
		}

		$dyn_theme_option_css = $string;

		self::StoreAsset( $folder, $filename, $string );
		update_option( 'mk_theme_options_css_file' . $mk_lang, $filename, true );
	}

	/**
	 * Deletes the the mk_assets/theme_options-***.css file
	 *
	 * @since 5.0.0
	 * @since 5.9.6 Update timestamp when clearing cache.
	 * @since 6.0.1
	 *        Fix wrong option name for filename.
	 *        Remove old .*_sha1 row from DB. Keep the latest only.
	 * @return bool
	 */
	public function DeleteThemeOptionStyles( $remove_cache_plugins ) {
		global $mk_lang;

		$filename = get_option( 'mk_theme_options_css_file' . $mk_lang );
		$folder   = $this->get_global_asset_upload_folder( 'directory' );

		if ( $remove_cache_plugins ) {
			mk_purge_cache_actions();
		}

		delete_option( $filename . '_sha1' );

		if ( true != $this->deleteFile( $folder . $filename ) && '' != $filename ) {
			wp_die( 'A problem occurred while trying to delete theme-options css file' );
		}

		self::update_global_assets_timestamp();
		update_option( 'mk_theme_options_css_file' . $mk_lang, '', true );
		return true;

	}

	/**
	 * Enqueues Theme Options CSS File
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @since       5.0.0
	 * @since       5.9.1
	 * @since       5.9.3
	 */
	public static function addThemeOptionsCSSToEnqueue() {
		global $mk_dev;
		global $dyn_theme_option_css;
		global $mk_lang;

		// Ensure "mk_theme_options_css_file" is string.
		$theme_options = get_option( 'mk_theme_options_css_file' . $mk_lang );
		if ( is_array( $theme_options ) ) {
			$theme_options = '';
		}

		if ( '' == $theme_options || $mk_dev || ! file_exists( self::get_global_asset_upload_folder( 'directory' ) . $theme_options ) ) {
			$dyn_theme_option_css = true;
		}

		if ( ('' != $theme_options && file_exists( self::get_global_asset_upload_folder( 'directory' ) . $theme_options )) ) {
			$dyn_theme_option_css = false;
			$theme_options_css         = self::get_global_asset_upload_folder( 'url' ) . $theme_options;
			if ( $theme_options_css ) {
				if ( false == $mk_dev ) {
					wp_enqueue_style( 'theme-options', $theme_options_css, array(), self::global_assets_timestamp() );
				}
			}
		}
	}

	/**
	 * Processes global styles and updates timestamp.
	 *
	 * @author   Bob Ulusoy & Reza Ardestani & Ugur Mirza Zeyrek
	 * @since    5.0.7
	 * @since    5.9.3 Added update global assets method.
	 * @since    5.9.6 Added `wp_add_inline_style()` function.
	 */
	public function process_global_styles() {
		// declaring the globals.
		global $mk_options, $local_dynamic_styles;
		$is_css_min = ( ! ( defined( 'MK_DEV' ) ? constant( 'MK_DEV' ) : true ) || 'true' === $mk_options['minify-css'] );
		$handle = 'components-full';

		$this->init_globals();
		$this->include_files();
		$output = $local_dynamic_styles;
		$output .= mk_enqueue_woocommerce_font_icons();
		$output .= $mk_options['custom_css'];

		if ( $is_css_min ) {
			$minifier = new SimpleCssMinifier();
			$output = $minifier->minify( $output );
			$handle = 'theme-styles';
		}

		wp_add_inline_style( $handle, $output );
	}

	/**
	 * Adds critical styles in the header
	 *
	 * @author  Ugur Mirza ZEYREK & Sheikh Danish Iqbal
	 * @since   5.0.7
	 * @since   5.8.0
	 * @since   5.9.3 Rename variable name.
	 */
	public function critical_path_css() {
		$critical_path_css = array();
		$crit_path_css_min = get_transient( 'mk_critical_path_css' );
		$status                 = '';
		if ( ! $crit_path_css_min ) {

			$dest_path = get_template_directory();

			$remote_get_content    = '';
			$critical_path_css['filename'] = '/assets/stylesheet/min/critical-path.css';

			$mkfs = new Mk_Fs(
				array(
					'context' => $dest_path,
				)
			);

			$critical_path_css['body'] = $mkfs->get_contents( $dest_path . $critical_path_css['filename'] );

			if ( ! $critical_path_css['body'] ) {

				$base_dir                   = mk_base_url();
				$template_uri = get_template_directory_uri();
				if ( ! is_numeric( strpos( $template_uri, $base_dir ) ) ) {
					$template_uri = $base_dir . $template_uri;
				}

				$file_url           = $template_uri . $critical_path_css['filename'];
				$wp_remote_get_file = wp_remote_get( $file_url );

				if ( is_array( $wp_remote_get_file ) && array_key_exists( 'body', $wp_remote_get_file ) ) {

					 $remote_get_content = $wp_remote_get_file['body'];

				} elseif ( is_numeric( strpos( $file_url, 'https://' ) ) ) {

					$file_url           = str_replace( 'https://', 'http://', $file_url );
					$wp_remote_get_file = wp_remote_get( $file_url );

					if ( ! is_array( $wp_remote_get_file ) || ! array_key_exists( 'body', $wp_remote_get_file ) ) {
						wp_die( 'Dynamic styling error. Code: ds-critical_path_css' );
					}

					 $remote_get_content = $wp_remote_get_file['body'];

				}
				$critical_path_css['body'] = $remote_get_content;
			}
			$status .= '/* non cached */ ';
			self::prevent_cache_plugins();

			$minifier = new SimpleCssMinifier();
			$crit_path_css_min = $minifier->minify( $critical_path_css['body'] );

			set_transient( 'mk_critical_path_css', $crit_path_css_min, 15 * 60 );
		}// End if().

		// Commented line below is the old code, don't remove it.
		// echo "<style id=\"critical-path-css\" type='text/css'>" . $status . $crit_path_css_min . '</style>';.
		?>

		<style id="critical-path-css" type="text/css">
			<?php echo $status . $crit_path_css_min; ?>
		</style>

		<?php
	}

	/**
	 * Prevents cache if page has temp assets which supposed to included into dynamic asset files.
	 *
	 * @author      Ugur Mirza ZEYREK
	 * @since       5.1.4
	 * @since       5.9.3
	 */
	public static function prevent_cache_plugins() {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
			define( 'DONOTCACHCEOBJECT', true );
		}

		if ( ! defined( 'DONOTMINIFY' ) ) {
			define( 'DONOTMINIFY', true );
		}

		if ( ! defined( 'DONOTCACHEDB' ) ) {
			define( 'DONOTCACHEDB', true );
		}

		if ( ! defined( 'DONOTCDN' ) ) {
			define( 'DONOTCDN', true );
		}

	}

	/**
	 * Sets the dynamic theme options
	 *
	 * @author      Bob Ulusoy & Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       5.0.0
	 * @since       5.9.1
	 * @since       5.9.3
	 */
	public function dynamic_theme_options() {
		global $mk_dev;
		global $dyn_theme_option_css;

		if ( true == $mk_dev || $dyn_theme_option_css ) {
			self::StoreThemeOptionStyles( true );
			self::prevent_cache_plugins();
			echo '<style id=\'dynamic-theme-options-css\' type=\'text/css\'> /*  ' . time() . ' */ ' . $dyn_theme_option_css . '</style>';
		}
	}

	public static function minify_string( $string, $extension ) {
		$minified_content = '';
		if ( 'css' == $extension ) {
			$minifier         = new SimpleCssMinifier();
			$minified_content = $minifier->minify( $string );
		} elseif ( 'js' == $extension ) {
			$minified_content = \JShrink\Minifier::minify( $string );
		} elseif ( 'js' != $extension && 'css' != $extension ) {
			wp_die( 'wrong extension for minify_string' );
		}
		return $minified_content;
	}

	/**
	 * Append global styles that set in theme options into glob_dynamic_styles global variable.
	 *
	 * @param string $styles
	 */
	public static function addGlobalStyle( $styles ) {
		global $glob_dynamic_styles;

		$glob_dynamic_styles .= $styles;
	}

	/**
	 * Append local styles that set in post meta options into local_dynamic_styles global variable.
	 *
	 * @param string $styles
	 */
	public static function addLocalStyle( $styles ) {
		global $local_dynamic_styles;

		$local_dynamic_styles .= $styles;
	}

	/**
	 * Gets the upload folder address by the specified type.
	 * Type variable should be passed as "directory" or "url"
	 *
	 * Usage Example:
	 * $type = "directory";
	 *
	 * @param $type
	 * @return string
	 *
	 * @author  Ugur Mirza ZEYREK & Bob Ulusoy
	 * @since   5.0.0
	 * @since   5.1.4
	 * @since   5.9.3
	 */
	public static function get_global_asset_upload_folder( $type ) {
		$upload_folder_name = 'mk_assets';
		$wp_upload_dir      = wp_upload_dir();
		if ( 'directory' == $type ) {
			$upload_dir = $wp_upload_dir['basedir'] . '/' . $upload_folder_name . '/';
		} elseif ( 'url' == $type ) {
			// Converts url to https even if site url is not primarily set as https.
			$baseurl    = is_ssl() ? str_replace( 'http://', 'https://', $wp_upload_dir['baseurl'] ) : $wp_upload_dir['baseurl'];
			$upload_dir = $baseurl . '/' . $upload_folder_name . '/';
		} elseif ( 'url' != $type && 'directory' != $type ) {
			return '';
		}

		return $upload_dir;
	}

	/**
	 * Deletes the mk_getimagesize transient cache.
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @since   5.0.6
	 */
	public static function delete_transient_mk_getimagesize() {

		global $wpdb;
		$sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_mk\_getimagesize\_%'
               OR option_name like '\_transient\_timeout\_mk\_getimagesize\_%'
           ";

		$wpdb->query( $sql );

	}

	/**
	 * Deletes the mk_critical_styles transient cache.
	 *
	 * @return bool
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @since   5.0.7
	 */
	public static function delete_transient_mk_critical_path_css() {

		global $wpdb;
		$sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_timeout\_mk\_critical\_path\_css%'
               OR option_name like '\_transient\_mk\_critical\_path\_css%'
           ";
		$wpdb->query( $sql );

	}

	/**
	 * Gets the global asset address by the extension and type.
	 *
	 * Usage Example:
	 * $extension = "js";
	 * $type = "directory";
	 *
	 * @param $extension
	 * @param $type
	 * @return string
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @since   5.0.0
	 */
	public static function get_global_asset_address( $extension, $type ) {
		if ( 'css' != $extension && 'js' != $extension ) {
			return '';
		}

		if ( get_option( 'global_assets_filename' ) ) {
			$upload_dir = self::get_global_asset_upload_folder( $type );
			if ( ! $upload_dir ) {
				return '';
			}

			$filename_pre = get_option( 'global_assets_filename' );
			$filename     = $upload_dir . $filename_pre . '.min.' . $extension;

			return $filename;
		}

			return '';
	}

	/**
	 * If the path is already exists returns true.
	 * If it doesn't creates the path.
	 * When something goes wrong returns false.
	 *
	 * @param $path
	 * @return bool
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @since   5.0.0
	 */
	public static function createPath( $path ) {
		$mkfs = new Mk_Fs(
			array(
				'context' => $path,
			)
		);
		if ( $mkfs->is_dir( $path ) ) {
			return true;
		}

		return $mkfs->mkdir( $path );
	}

	/**
	 * If the file is not exists or deleted successfully returns true.
	 * When something goes wrong returns false.
	 *
	 * @param $filename
	 * @return bool
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @since   5.0.0
	 * @since   5.9.1
	 * @since   5.9.3
	 */
	public static function deleteFile( $filename ) {

		$mkfs = new Mk_Fs(
			array(
				'context' => $filename,
			)
		);
		if ( ! $mkfs->exists( $filename ) ) {
			return true;
		}

		return $mkfs->delete( $filename );
	}

	/**
	 * Check if current page is in VC frontend editor.
	 *
	 * @since 6.0.3
	 * @return boolean
	 */
	private static function is_vc_editing() {
		global $mk_options;

		$move_styles_footer = isset( $mk_options['move-shortcode-css-footer'] ) ? $mk_options['move-shortcode-css-footer'] : 'true';
		if ( 'false' == $move_styles_footer ) {
			return true;
		}

		if ( function_exists( 'vc_is_page_editable' ) && vc_is_page_editable() ) {
			return true;
		}

		return false;
	}

}
new Mk_Static_Files( true );
