<?php
/**
 * Output static assets needed for theme backend end pages.
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1.2
 * @package     artbees
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

class Mk_Theme_Backend_Assets {

	var $theme_version;
	var $is_js_min;
	var $is_css_min;
	var $assets_css_path;

	function __construct() {

		// Get the current version of theme from database.
		global $mk_options;
		$this->theme_version = get_option( 'mk_jupiter_theme_current_version' );

		// Check if MK_DEV constant is set for debudding purposes.
		$this->is_js_min = false;
		 $this->is_css_min = false;

		 // TODO : fix the relative images path when css is minified
		 // $this->is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
		 // $this->is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
		 // Paths for assets.
		$this->assets_css_path = THEME_ADMIN_ASSETS_URI . '/stylesheet/css' . ($this->is_css_min ? '/min' : '');
		$this->assets_js_path = THEME_ADMIN_ASSETS_URI . '/js' . ($this->is_js_min ? '/min' : '');
		$this->assets_css_dir = THEME_ADMIN_ASSETS_DIR . '/stylesheet/css' . ($this->is_css_min ? '/min' : '');
		$this->assets_js_dir = THEME_ADMIN_ASSETS_DIR . '/js' . ($this->is_js_min ? '/min' : '');
		 $this->controlpanel_assets_css_path = THEME_CONTROL_PANEL_ASSETS . '/css';
		 $this->controlpanel_assets_js_path = THEME_CONTROL_PANEL_ASSETS . '/js';

		// Get methods hooked up to admin_enqueue_scripts.
		add_action( 'admin_enqueue_scripts' , array( &$this, 'mk_remove_plugin_notices' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'wp_libs' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'select2_assets' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'widgets_assets' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'backend_core_assets' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'local_forage' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'icon_selector' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'alpha_color_picker' ) );

		 add_action( 'admin_enqueue_scripts', array( &$this, 'ui_library' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'theme_options' ) );
		 add_action( 'admin_enqueue_scripts', array( &$this, 'ui_control_panel' ) );

		if ( NEW_UI_LIBRARY ) {
			add_action( 'admin_enqueue_scripts', array( &$this, 'ui_page_options' ) );
		}

			  add_action( 'admin_init',             array( &$this, 'wp_enqueue_media' ) );

	}




	/**
	 * Enqueue core styles and scripts that will be loaded in almost all pages including
	 * Theme options, add new post/page and editing, theme control panel pages
	 */
	function backend_core_assets() {
		wp_enqueue_style( 'theme-global-styles', $this->assets_css_path . '/global-styles.css', false, $this->theme_version );

		if ( mk_theme_is_masterkey() || mk_theme_is_post_type() ) {
			wp_enqueue_script( 'attrchange',            $this->assets_js_path . '/attrchange.js',           array( 'jquery' ), $this->theme_version, true );
			wp_enqueue_script( 'mk-options-dependency',     $this->assets_js_path . '/options-dependency.js',   array( 'jquery' ), $this->theme_version, true );
			wp_enqueue_script( 'progress-circle',       $this->assets_js_path . '/progress-circle.js',      array( 'jquery' ), $this->theme_version, true );
			wp_enqueue_script( 'attrchange',            $this->assets_js_path . '/attrchange.js',           array( 'jquery' ), $this->theme_version, true );

			$mk_backend_js_file = '/backend-scripts.js';

			wp_enqueue_script(
				'theme-backend-scripts',
				$this->assets_js_path . $mk_backend_js_file,
				array( 'jquery' ),
				filemtime( $this->assets_js_dir . $mk_backend_js_file ),
				true
			);

			$mk_backend_css_file = '/theme-backend-styles.' . THEME_VERSION . '.css';

			wp_enqueue_style(
				'theme-backend-styles',
				$this->assets_css_path . $mk_backend_css_file,
				false,
				filemtime( $this->assets_css_dir . $mk_backend_css_file )
			);

			global $mk_options;
			$loggedin_menu = isset( $mk_options['loggedin_menu'] ) ? $mk_options['loggedin_menu'] : '';
			$global_lazyload = isset( $mk_options['global_lazyload'] ) ? $mk_options['global_lazyload'] : '';
			$mk_localized_data = array(
				'security'      => wp_create_nonce( 'mk_admin' ),
				'loggedin_menu' => $loggedin_menu,
				'mk_global_lazyload' => $global_lazyload,
				'meta_main_nav_loc_warning_msg' => __( 'You have set "Main Navigation For Logged In User" in your Theme Options which overrides this option here.', 'mk_framework' ),
			);
				wp_localize_script( 'theme-backend-scripts', 'theme_backend_localized_data', $mk_localized_data );
		}

		if ( mk_theme_is_masterkey() ) {
			wp_enqueue_script( 'theme-codemirror', $this->assets_js_path . '/codemirror.js', array(), $this->theme_version, true );
			wp_enqueue_script( 'theme-codemirror-lang-javascript', $this->assets_js_path . '/codemirror-js.js', array( 'theme-codemirror' ), $this->theme_version, true );
			wp_enqueue_script( 'theme-codemirror-lang-css', $this->assets_js_path . '/codemirror-css.js', array( 'theme-codemirror' ), $this->theme_version, true );
			wp_enqueue_script( 'theme-codemirror-css-hint', $this->assets_js_path . '/codemirror-css-hint.js', array( 'theme-codemirror' ), $this->theme_version, true );
			wp_enqueue_script( 'theme-codemirror-js-hint', $this->assets_js_path . '/codemirror-js-hint.js', array( 'theme-codemirror' ), $this->theme_version, true );
			wp_enqueue_script( 'theme-codemirror-show-hint-js', $this->assets_js_path . '/codemirror-show-hint.js', array( 'theme-codemirror' ), $this->theme_version, true );

			wp_enqueue_style( 'theme-codemirror', $this->assets_css_path . '/codemirror.css', array(), $this->theme_version, 'all' );
			wp_enqueue_style( 'theme-codemirror-skin', $this->assets_css_path . '/codemirror-dracula.css', array( 'theme-codemirror' ), $this->theme_version, 'all' );
			wp_enqueue_style( 'theme-codemirror-show-hint-css', $this->assets_css_path . '/codemirror-show-hint.css', array( 'theme-codemirror' ), $this->theme_version, 'all' );

			wp_enqueue_script( 'theme-code-editors', $this->assets_js_path . '/use-codemirror.js', array( 'jquery', 'theme-codemirror' ), $this->theme_version, true );
			wp_enqueue_style( 'theme-code-editors', $this->assets_css_path . '/use-codemirror.css', array( 'theme-codemirror' ), $this->theme_version, 'all' );
		}

	}

	/**
	 * Removes bundled plugin update and register product notices. Reason : confuse theme users as if they need to buy them as well.
	 */
	function mk_remove_plugin_notices() {
		echo '<style>
		.rs-update-notice-wrap,
		#vc_license-activation-notice
		{display:none !important;}
		</style>';
	}

	/**
	 * Enqueue some of WP built-in libraries.
	 */
	function wp_libs() {
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wp-color-picker' );
		 wp_enqueue_style( 'wp-color-picker' );
	}



	/**
	 * Enqueue WordPress media assets for theme options. It will be used for upload options.
	 */
	function wp_enqueue_media() {

		if ( ! mk_theme_is_masterkey() ) {
			return false;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
	}



	/**
	 * Enqueue select2 assets to be used in multiselect dropdowns. its widely used library in the theme.
	 *
	 * @link https://select2.github.io/
	 */
	function select2_assets() {
		if ( mk_is_control_panel() ) {
			return false;
		}
		wp_enqueue_style( 'mk-select2',     $this->assets_css_path . '/select2.css', false, $this->theme_version );
		wp_enqueue_script( 'mk-select2', $this->assets_js_path . '/select2.js', array( 'jquery' ), $this->theme_version, true );

	}



	/**
	 * Enqueues assets specifically for widgets.php
	 */
	function widgets_assets() {
		// Scripts below should be enqueued in Widget and Customizer admin page.
		if ( ! mk_theme_is_widgets() && ! is_customize_preview() ) {
			return false;
		}

		wp_enqueue_script( 'mk-widget-scripts', $this->assets_js_path . '/widgets.js', array( 'jquery' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-widget-styles', $this->assets_css_path . '/widgets.css', false, $this->theme_version );
	}




	/**
	 * Enqueues localforage (Front End Database Wrapper)
	 */
	function local_forage() {
		if ( NEW_CUSTOM_ICON == true ) {
			wp_enqueue_script( 'mk-localforage', $this->assets_js_path . '/localforage.min.js', array(), $this->theme_version, true );
		}
	}


	/**
	 * Enqueues assets for Icon Picker
	 */
	function icon_selector() {
		if ( NEW_CUSTOM_ICON == true ) {
			wp_enqueue_script( 'mk-icon-selector', $this->assets_js_path . '/icon-selector.js', array( 'jquery', 'mk-localforage', 'wp-util' ), $this->theme_version, true );
			wp_enqueue_style( 'mk-icon-selector', $this->assets_css_path . '/icon-selector.css', false, $this->theme_version );
			$icon_selector_data = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'select_icon_string' => __( 'Select Icon', 'mk_framework' ),
				'replace_icon_string' => __( 'Replace Icon', 'mk_framework' ),
			);
			wp_localize_script( 'mk-icon-selector', 'icon_selector_locolized_data', $icon_selector_data );
		}

	}

	/**
	 * Enqueues assets for UI Library
	 */
	function ui_library() {
		wp_enqueue_script( 'mk-gsap', $this->controlpanel_assets_js_path . '/gsap.js', array(), $this->theme_version, true );
		wp_enqueue_script( 'mk-rangeslider', $this->controlpanel_assets_js_path . '/rangeslider.js', array( 'jquery' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-rangeslider', $this->controlpanel_assets_css_path . '/rangeslider.css', false, $this->theme_version );
		wp_enqueue_script( 'mk-modal', $this->controlpanel_assets_js_path . '/mk-modal.js', array( 'jquery', 'mk-gsap' ), $this->theme_version, true );
		wp_enqueue_script( 'mk-ui-library', $this->controlpanel_assets_js_path . '/ui-library.js', array( 'jquery', 'mk-rangeslider', 'mk-gsap', 'underscore', 'wp-color-picker' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-ui-library', $this->controlpanel_assets_css_path . '/ui-library.css', false, $this->theme_version );

	}

	/**
	 * Enqueue scripts and styles for Control Panel.
	 *
	 * @since 5.9.5
	 * @since 6.0.3
	 *        Add nonce for mk-ui-control-panel script.
	 *        Add media scripts.
	 * @return boolean
	 */
	public function ui_control_panel() {

		if ( ! mk_is_control_panel() ) {
			return false;
		}
		wp_enqueue_media();
		wp_enqueue_style( 'control-panel-modal-plugin', THEME_CONTROL_PANEL_ASSETS . '/css/sweetalert.css', false, $this->theme_version );
		wp_enqueue_script( 'control-panel-sweet-alert', THEME_CONTROL_PANEL_ASSETS . '/js/sweetalert.min.js', array( 'jquery' ), $this->theme_version );
		wp_enqueue_script( 'mk-ui-library', $this->controlpanel_assets_js_path . '/ui-library.js', array( 'jquery', 'mk-rangeslider', 'mk-gsap' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-ui-library', $this->controlpanel_assets_css_path . '/ui-library.css', false, $this->theme_version );
		wp_enqueue_style( 'mk-ui-control-panel', $this->controlpanel_assets_css_path . '/ui-control-panel.css', false, $this->theme_version );
		wp_enqueue_script( 'mk-ui-control-panel', $this->controlpanel_assets_js_path . '/ui-control-panel.js', array( 'jquery' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-ui-show-more', THEME_CONTROL_PANEL_ASSETS . '/css/ui-show-more.css', false, $this->theme_version );
		wp_enqueue_script( 'mk-show-more', $this->controlpanel_assets_js_path . '/dynamicmaxheight.min.js', array( 'jquery' ), $this->theme_version, false );
		wp_localize_script( 'mk-ui-control-panel', 'mk_cp_textdomain', mk_adminpanel_textdomain() );

		wp_localize_script(
			'mk-ui-control-panel', 'mk_control_panel', array(
				'nonce' => wp_create_nonce( 'mk_control_panel' ),
			)
		);
	}


	/**
	 * Enqueues assets for Page Options Redesign
	 */
	function ui_page_options() {
		wp_enqueue_style( 'mk-ui-page-options', $this->controlpanel_assets_css_path . '/ui-page-options.css', false, $this->theme_version );

		wp_enqueue_script( 'mk-ui-page-options', $this->controlpanel_assets_js_path . '/ui-page-options.js', array( 'jquery' ), $this->theme_version, true );

		// UI LIBRARY FILES
		wp_enqueue_script( 'mk-gsap', $this->controlpanel_assets_js_path . '/gsap.js', array(), $this->theme_version, true );
		wp_enqueue_script( 'mk-rangeslider', $this->controlpanel_assets_js_path . '/rangeslider.js', array( 'jquery' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-rangeslider', $this->controlpanel_assets_css_path . '/rangeslider.css', false, $this->theme_version );

		wp_enqueue_script( 'mk-ui-library', $this->controlpanel_assets_js_path . '/ui-library.js', array( 'jquery', 'mk-rangeslider', 'mk-gsap' ), $this->theme_version, true );
		wp_enqueue_style( 'mk-ui-library', $this->controlpanel_assets_css_path . '/ui-library.css', false, $this->theme_version );
	}

	/**
	 * Enqueue assets for UI Theme Options v2
	 */
	function theme_options() {
		if ( mk_theme_is_masterkey() ) {
			wp_enqueue_style( 'mk-ui-theme-options-v2', $this->controlpanel_assets_css_path . '/ui-theme-options-v2.css', false, $this->theme_version );
			wp_enqueue_script( 'mk-ui-theme-options-v2', $this->controlpanel_assets_js_path . '/ui-theme-options.js', array( 'jquery', 'mk-rangeslider', 'mk-gsap' ), $this->theme_version, true );
		}
	}


	function alpha_color_picker() {
		wp_enqueue_style(
			'mk-alpha-color-picker',
			THEME_DIR_URI . '/framework/admin/assets/stylesheet/css/vc-alpha-color-picker.css',
			array( 'wp-color-picker' ),
			$this->theme_version
		);
		wp_enqueue_script(
			'mk-alpha-color-picker',
			THEME_DIR_URI . '/framework/admin/assets/js/vc-alpha-color-picker.js',
			array( 'jquery', 'wp-color-picker' ),
			$this->theme_version,
			true
		);
	}

}


new Mk_Theme_Backend_Assets();
