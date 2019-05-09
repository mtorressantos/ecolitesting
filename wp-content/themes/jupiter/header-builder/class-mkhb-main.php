<?php
/**
 * Header Builder: Main loader file
 *
 * @since 5.9.0
 * @package Header_Builder
 */

/**
 * Main class used for loading all Header Builder files.
 *
 * @since 5.9.0
 * @since 5.9.3 Refactor it as main class.
 * @since 5.9.4 Add parameters on HB_Grid declaration.
 * @since 5.9.5 Include make internal CSS inside hb_grid_style hook. Run HB_Grid after wp_loaded. Add
 *              new conditional statement to check if HB is activated from TO. Remove conditional
 *              statement to check HB is activated from HB admin page.
 * @since 5.9.8 Add conditional statements to call hb-grid only when HB is activated and called on
 *              frontend or preview only. Remove array type operand, check if the variable is array
 *              or not instead. Includes common.js.
 * @since 6.0.0 Remove unused codes.
 */
class MKHB_Main {

	/**
	 * Constructor.
	 *
	 * Call some functions to load Header Builder and load all necessary files.
	 *
	 * @since 5.9.3
	 * @since 5.9.5 Manage how to load the walkers for navigation. Join init_load and init_hooks.
	 * @since 5.9.8 Hook HB_Grid on 'wp'. Previously, it's called on 'wp_loaded'.
	 * @since 6.0.0 Load all the shortcode files on the frontend. Remove unused files.
	 * @since 6.0.3 Load SimpleCssMinifier for compressing internal CSS.
	 */
	public function __construct() {
		// Load the constants, helpers, etc.
		require_once dirname( __FILE__ ) . '/mkhb-config.php';
		require_once HB_INCLUDES_DIR . '/helpers/general.php';
		require_once HB_INCLUDES_DIR . '/link-template.php';
		require_once HB_INCLUDES_DIR . '/helpers/array.php';
		require_once dirname( __FILE__ ) . '/class-mkhb-migration.php';

		// Load the nav walkers. Should be loaded here because the class will be used in admin panel too.
		require_once HB_INCLUDES_DIR . '/helpers/walkers/class-mkhb-walker-nav-responsive.php';
		require_once HB_INCLUDES_DIR . '/helpers/walkers/class-mkhb-walker-nav-burger.php';

		// Load main HB files.
		require_once HB_INCLUDES_DIR . '/class-mkhb-post-type.php';
		require_once HB_INCLUDES_DIR . '/class-mkhb-hooks.php';
		require_once HB_INCLUDES_DIR . '/revision.php';

		if ( is_user_logged_in() ) {
			require_once HB_ADMIN_DIR . '/includes/post.php';
		}

		require_once HB_INCLUDES_DIR . '/class-mkhb-render.php';

		if ( is_admin() ) {
			require_once HB_ADMIN_DIR . '/class-mkhb-model.php';
			require_once HB_ADMIN_DIR . '/class-mkhb-screen.php';
		}

		// Call hooks.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'submenu_file', array( $this, 'return_query_tag' ) );
		add_filter( 'template_include', array( $this, 'preview_template' ), 99 );
		add_filter( 'query_vars', array( $this, 'query_vars_filter' ) );
		add_filter( 'get_header_style', array( $this, 'header_style' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'mk_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'mk_enqueue_styles_minified', array( $this, 'enqueue_styles' ) );
		add_action( 'wp', array( $this, 'hb_grid' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Load shortcode files on the frontend only.
		if ( ! is_admin() ) {
			// CSS Minifier lib, used by Jupiter.
			require_once THEME_INCLUDES . '/minify/src/SimpleCssMinifier.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-row.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-column.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-logo.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-shopping-icon.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-textbox.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-search.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-navigation.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-icon.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-social.php';
			require_once HB_INCLUDES_DIR . '/shortcodes/mkhb-button.php';
		}
	}

	/**
	 * Add's "Header Builder" to Jupiter WordPress menu.
	 *
	 * @since 5.9.3
	 */
	public function admin_menu() {
		add_submenu_page( THEME_NAME, __( 'Header Builder', 'mk_framework' ), __( 'Header Builder <span class="mk-beta-badge">Beta</span>', 'mk_framework' ), 'edit_theme_options', 'header-builder', '__return_null' );
	}

	/**
	 * Add the current page URL as the "return" parameter to our "Jupiter" > Header Builder" submenu.
	 *
	 * @since 5.9.3
	 */
	public function return_query_tag() {
		global $submenu;

		if ( array_key_exists( 'Jupiter', $submenu ) ) {
			return;
		}

		$current_url        = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$header_builder_url = add_query_arg( 'return', rawurlencode( $current_url ), 'admin.php?page=header-builder' );

		if ( ! array_key_exists( 'Jupiter', $submenu ) ) {
			return;
		}

		// The following position needs update if the header builder submenu location changes.
		foreach ( $submenu['Jupiter'] as $submenu_key => $submenu_array ) {
			if ( 'header-builder' === $submenu_array[2] ) {
				break;
			}
		}

		// @todo WordPress not allowed to override global $submenu. Need to find better way.
		$submenu['Jupiter'][ $submenu_key ][2] = $header_builder_url; // WPCS: override ok.
	}

	/**
	 * Render the "Preview" template when the URL loaded is "?header-builder=preview"
	 *
	 * @since 5.9.3
	 *
	 * @param string $template The path of the template to include.
	 */
	public function preview_template( $template ) {
		if ( 'navigation-preview' === get_query_var( 'header-builder' ) ) {
			return HB_INCLUDES_DIR . '/templates/navigation-preview.php';
		}

		return $template;
	}

	/**
	 * Add header-builder to query vars. This is used for the preview functionality.
	 *
	 * @since 5.9.3
	 *
	 * @param array $public_query_vars The array of whitelisted query variables.
	 */
	public function query_vars_filter( $public_query_vars ) {
		$public_query_vars[] = 'header-builder-preview-id';
		$public_query_vars[] = 'header-builder-preview';
		$public_query_vars[] = 'header-builder-preview-nonce';
		$public_query_vars[] = 'header-builder-id';
		return $public_query_vars;
	}

	/**
	 * Override default header style from theme-options.
	 *
	 * @since 5.9.3
	 * @since 5.9.5 Add conditional statement to check if HB is activated from TO. Remove conditional
	 *              statement to check HB is activated from HB admin page.
	 *
	 * @param string $style The Theme Options style to override.
	 */
	public function header_style( $style ) {
		// Is HB active from Theme Options.
		if ( mkhb_is_to_active() ) {
			return 'custom';
		}

		// Is user open HB in preview mode.
		$is_previewing = (bool) get_query_var( 'header-builder-preview' );

		if ( $is_previewing ) {
			return 'custom';
		}

		return $style;
	}

	/**
	 * Add new class 'mkhb-jupiter' to body.
	 *
	 * @since 5.9.5
	 * @since 5.9.8 Add conditional statement to add hb-jupiter class only if HB is active or
	 *              user open Preview page.
	 * @since 5.9.5 Rename hb-jupiter into mkhb-jupiter.
	 *
	 * @param  string $classes Current body class list.
	 * @return array  $classes Latest body class list with additional mkhb-jupiter class.
	 */
	public function body_class( $classes ) {
		// Is user open HB in preview mode.
		if ( mkhb_is_to_active() || (bool) get_query_var( 'header-builder-preview' ) ) {
			$classes[] = 'mkhb-jupiter';
		}

		return $classes;
	}

	/**
	 * Load our styles when mk_enqueue_styles() is called.
	 *
	 * @since 5.9.3
	 * @since 5.9.4 Add parameters on HB_Grid declaration.
	 * @since 5.9.5 Include make internal CSS inside hb_grid_style hook.
	 * @since 5.9.8 Include common.js as common HB Javscript file.
	 * @since 6.0.0 Remove unused enqueue files. Remove grid style hook.
	 * @since 6.0.2 Add row and column assets because both of them always used in the FE.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'mkhb-render', HB_ASSETS_URI . 'css/mkhb-render.css', array(), THEME_VERSION );
		wp_enqueue_style( 'mkhb-row', HB_ASSETS_URI . 'css/mkhb-row.css', array(), THEME_VERSION );
		wp_enqueue_style( 'mkhb-column', HB_ASSETS_URI . 'css/mkhb-column.css', array(), THEME_VERSION );
		wp_enqueue_script( 'mkhb-render', HB_ASSETS_URI . 'js/mkhb-render.js', array( 'jquery' ), THEME_VERSION, true );
		wp_enqueue_script( 'mkhb-column', HB_ASSETS_URI . 'js/mkhb-column.js', array( 'jquery' ), THEME_VERSION, true );
	}

	/**
	 * Create global JS variable on admin init.
	 *
	 * @since 5.9.5
	 */
	public function admin_enqueue_scripts() {
		?>
		<script type="text/javascript">
			window.headerBuilderEnabledInThemeOptions = <?php echo (int) mkhb_is_to_active(); ?>;
		</script>
		<?php
	}

	/**
	 * Load and run HB_Grid in Front End on wp_loaded action. Front-End: init -> widgets_init -> wp_loaded.
	 *
	 * @since 5.9.5
	 * @since 5.9.8 Add conditional statement to add mkhb-jupiter class only if HB is active and it's
	 *              frontend page or user open Preview page.
	 * @since 6.0.0 Run additonal hooks for HB Shopping Icon. Add HB_Render initialize to render all
	 *              shortcodes based on the devices and workspaces.
	 * @since 6.1.2 Add mkhb_is_po_active in conditional statement based on template
	 *              setting of Page Options.
	 */
	public function hb_grid() {
		if ( ! is_admin() && ( mkhb_is_to_active() || (bool) get_query_var( 'header-builder-preview' ) ) && mkhb_is_po_active() ) {
			new MKHB_Render();
		}

	}

}

new MKHB_Main();
