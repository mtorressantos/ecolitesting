<?php
/**
 * Custom customizer loader
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Class to initiate the theme customizer
 * Prefixes: sh -> shop, pl -> product-list, pp -> product-page
 *
 * @version 1.0.0
 * @author  Artbees Team
 *
 * @since 5.9.4
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod) // For now
 */
class MK_Customizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_controls();
		$this->add_hooks();
		$this->enqueue_dynamic_styles();
	}

	/**
	 * Add actions hooks
	 */
	public function add_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_controls' ) );
		add_action( 'customize_preview_init', array( $this, 'enqueue_preview' ) );
		add_action( 'customize_save_after', array( $this, 'customize_save' ) );
		add_filter( 'customize_partial_render', array( $this, 'prepend_inline_css' ) );
		add_action( 'wp_ajax_mk_customizer_reset', array( $this, 'reset' ) );

		// Hook to modify part of the pages.
		if ( $this->is_shop_enabled() ) {
			$hooks = glob( THEME_CUSTOMIZER_DIR . '/woocommerce/hooks/*.php' );

			foreach ( $hooks as $hook ) {
				require_once $hook;
			}
		}
	}

	/**
	 * Add 'Shop Customizer' submenu under 'Jupiter' menu.
	 * Thanks to https://goo.gl/Za6SDH and https://goo.gl/4R264q
	 *
	 * @since 6.1.2 Remove beta tag from Shop Customizer menu.
	 */
	public function admin_menu() {
		if ( ! $this->is_section_enabled( 'shop_customizer' ) ) {
			return;
		}

		// @codingStandardsIgnoreStart
		global $submenu;
		$query['autofocus[section]'] = 'mk_shop';
		$link = add_query_arg( $query, admin_url( 'customize.php' ) );

		$submenu['Jupiter'][] = array(
			__( 'Shop Customizer', 'mk_framework' ),
			'manage_options',
			esc_url( $link ),
		);
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue_controls() {
		$mk_cz_ctrl_css_file = '/assets/css/customizer-controls.' . THEME_VERSION . '.css';

		wp_enqueue_style(
			'mk-cz-controls',
			THEME_CUSTOMIZER_URI . $mk_cz_ctrl_css_file,
			array(),
			filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_ctrl_css_file )
		);

		$mk_cz_ctrl_js_file = '/assets/js/customizer-controls.' . THEME_VERSION . '.js';

		wp_enqueue_script(
			'mk-cz-controls',
			THEME_CUSTOMIZER_URI . $mk_cz_ctrl_js_file,
			array( 'jquery-ui-tabs', 'jquery-ui-dialog', 'jquery', 'customize-controls' ),
			filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_ctrl_js_file ),
			true
		);

		wp_enqueue_script(
			'mk-webfontloader',
			THEME_DIR_URI . '/assets/js/plugins/wp-enqueue/webfontloader.js',
			array(),
			THEME_VERSION,
			true
		);

		wp_localize_script( 'mk-cz-controls', 'mk_cz', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'mk_cz' ),
		));
	}

	/**
	 * Enqueue control related scripts/styles for live preview
	 */
	public function enqueue_preview() {
		$mk_cz_prev_css_file = '/assets/css/customizer-preview.' . THEME_VERSION . '.css';

		wp_enqueue_style(
			'mk-customizer-preview',
			THEME_CUSTOMIZER_URI . $mk_cz_prev_css_file,
			array(),
			filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_prev_css_file )
		);

		$mk_cz_prev_js_file = '/assets/js/customizer-preview.' . THEME_VERSION . '.js';

		wp_enqueue_script(
			'mk-customizer-preview',
			THEME_CUSTOMIZER_URI . $mk_cz_prev_js_file,
			array( 'customize-preview' ),
			filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_prev_js_file ),
			true
		);
	}

	/**
	 * Load controls files dependencies
	 */
	public function register_controls() {
		if ( ! class_exists( 'WP_Customize_Control' ) ) {
			return;
		}

		// Require main custom control class.
		require_once THEME_CUSTOMIZER_DIR . '/controls/class-mk-control.php';

		// Require all the custom controls.
		$controls = glob( THEME_CUSTOMIZER_DIR . '/controls/**/class-mk-*.php' );

		foreach ( $controls as $control ) {
			require_once $control;
		}
	}

	/**
	 * Register custom settings
	 *
	 * @param object $wp_customize WordPress built-in custmizer object.
	 */
	public function register_settings( $wp_customize ) {
		if ( ! $wp_customize ) {
			return;
		}

		// Load active callback functions.
		require_once THEME_CUSTOMIZER_DIR . '/settings/active-callbacks.php';

		// Load dialog - custom section.
		require_once THEME_CUSTOMIZER_DIR . '/settings/class-mk-dialog.php';

		// Register dialog - custom section.
		$wp_customize->register_section_type( 'MK_Dialog' );

		// Load all the Shop settings.
		if ( $this->is_section_enabled( 'shop_customizer' ) ) {
			require_once THEME_CUSTOMIZER_DIR . '/settings/shop/sections.php';
		}

		// Load all the Widgets settings.
		require_once THEME_CUSTOMIZER_DIR . '/settings/widgets/sections.php';
	}

	/**
	 * Get all dynamic styles files.
	 */
	private function get_dynamic_styles_files() {
		$directory = THEME_CUSTOMIZER_DIR . '/dynamic-styles';
		$filter = array();

		// Include helper functions first.
		require_once THEME_CUSTOMIZER_DIR . '/dynamic-styles/helpers/helpers.php';

		// Filter the helpers folder.
		$filter[] = 'helpers';

		// Filter the dynamic styles files by folder name.
		if ( ! $this->is_shop_enabled() ) {
			$filter[] = 'shop';
		}

		$files = new RecursiveIteratorIterator(
					new RecursiveCallbackFilterIterator(
						new RecursiveDirectoryIterator(
							$directory,
							RecursiveDirectoryIterator::SKIP_DOTS
						),
						function ( $file ) use ( $filter ) {
							return $file->isFile() || ! in_array( $file->getBaseName(), $filter, true );
						}
					)
				);

		return $files;
	}

	/**
	 * Enqueue dynamic styles.
	 */
	protected function enqueue_dynamic_styles() {
		$files = $this->get_dynamic_styles_files();
		$static = new Mk_Static_Files( false );
		foreach ( $files as $file ) {
			$css = include( $file );
			if ( ! empty( $css ) ) {
				$static->addGlobalStyle( $css );
			}
		}
	}

	/**
	 * Check if a panel of sections and settings is enabled.
	 *
	 * @param string $section Slug of the section.
	 * @return boolean Returns true if enabled.
	 */
	private function is_section_enabled( $section ) {
		$mk_options = get_option( THEME_OPTIONS );

		if ( ! empty( $mk_options[ $section ] ) && 'true' === $mk_options[ $section ] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if Shop section should be enabled.
	 *
	 * @return boolean Returns true if enabled.
	 */
	private function is_shop_enabled() {
		if ( $this->is_section_enabled( 'shop_customizer' ) && class_exists( 'WooCommerce' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Run some actions after customize saves.
	 *
	 * @since 6.0.3
	 */
	public function customize_save() {
		$this->clear_theme_cache();
	}

	/**
	 * Clear theme cache.
	 *
	 * @since 5.9.4
	 * @since 6.0.3 Change to private.
	 */
	private function clear_theme_cache() {
		$static = new Mk_Static_Files( false );
		$static->DeleteThemeOptionStyles( true );
	}

	/**
	 * Prepend inline CSS for selective refresh output.
	 *
	 * @param string|array|false $ouput The rendered partial as a string, raw data array (for client-side JS template).
	 */
	public function prepend_inline_css( $ouput ) {
		$inline_css = '';
		$files = $this->get_dynamic_styles_files();
		foreach ( $files as $file ) {
			$inline_css .= include( $file );
		}
		return '<style id=\'customizer-inline-styles\' type=\'text/css\'>' . $inline_css . '</style>' . $ouput;
	}

	/**
	 * Reset partial data of the customizer.
	 *
	 * @since 5.9.4
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public function reset() {
		check_ajax_referer( 'mk_cz', 'nonce' );

		$reset = $_POST['reset'];

		if ( empty( $reset ) ) {
			wp_send_json_error();
		}

		$mk_cz = get_option( 'mk_cz' );

		if ( $mk_cz ) {
			foreach ( $mk_cz as $key => $value ) {
				if ( strpos( $key, $reset ) === 0 ) {
					unset( $mk_cz[ $key ] );
				}
			}

			update_option( 'mk_cz', $mk_cz );

			$this->clear_theme_cache();
			wp_send_json_success();
		}

		wp_send_json_error();
	}

}

new MK_Customizer();
