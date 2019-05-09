<?php
/**
 * Header Builder: MKHB_Screen class.
 *
 * @package Header_Builder
 * @subpackage UI
 * @since 5.9.0
 */

/**
 * Create at isolated environment for displaying "wp-admin/admin.php?page=header-builder". Render
 * full-screen header-builder admin area.
 *
 * @author Dominique Mariano <dominique@artbees.net>
 *
 * @since 5.9.0 Introduced to replace screen.php.
 */
class MKHB_Screen {
	/**
	 * Constructor.
	 *
	 * @since 5.9.0
	 *
	 * Execute header_builder_screen() before any other hook when a user accesses the admin area.
	 * This allows us to create an isolated environment for the Header Builder, where only the assets
	 * needed for Header Builder are loaded and nothing else.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'header_builder_screen' ), 100 );
		add_filter( 'upload_mimes', array( $this, 'upload_mimes' ) );
		add_filter( 'site_option_upload_filetypes', array( $this, 'site_option_upload_filetypes' ) );
	}

	/**
	 * Allow JSON files to be uploaded in WP media.
	 *
	 * @since 5.9.8 Introduced.
	 *
	 * @param array $mimes Array of MIME types.
	 */
	public function upload_mimes( $mimes ) {
		$mimes['json'] = 'application/json';

		return $mimes;
	}

	/**
	 * Allow JSON files to be uploaded in WP media.
	 *
	 * @since 5.9.8 Introduced.
	 *
	 * @param string $file_types Space-separated string of MIME types for WP MU.
	 */
	public function site_option_upload_filetypes( $file_types ) {
		return rtrim( $file_types ) . ' json ';
	}

	/**
	 * Renders the entire Header Builder screen on the front end, with default values.
	 *
	 * @since 5.9.0
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 */
	public function header_builder_screen() {
		// @todo Sanitize GET request here.
		// If we are not on "wp-admin/admin.php?page=header-builder", then bail.
		if ( ! isset( $_GET['page'] ) || 'header-builder' !== $_GET['page'] ) { // WPCS: CSRF ok.
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'common' );
		wp_enqueue_style( 'forms' );

		// Bootstrap needs to be removed. It is causing issues on some npm modules.
		wp_enqueue_style( 'mkhb-bootstrap', get_template_directory_uri() . '/header-builder/includes/assets/css/bootstrap.min.css', array(), THEME_VERSION, 'all' );
		wp_enqueue_style( 'mkhb-font-awesome', get_template_directory_uri() . '/header-builder/admin/fonts/font-awesome.min.css', array(), THEME_VERSION, 'all' );
		wp_enqueue_style( 'mkhb', get_template_directory_uri() . '/header-builder/admin/css/screen.css', array( 'mkhb-font-awesome' ), THEME_VERSION, 'all' );
		wp_enqueue_style( 'mkhb-admin', get_template_directory_uri() . '/header-builder/admin/css/admin.css', array(), THEME_VERSION, 'all' );

		wp_register_script( 'mkhb-webfontloader', get_template_directory_uri() . '/header-builder/admin/js/webfontloader.js', array(), THEME_VERSION, true );
		wp_register_script( 'mkhb-navigation-scripts', get_template_directory_uri() . '/header-builder/includes/assets/js/mkhb-navigation-scripts.js', array( 'jquery' ), THEME_VERSION, true );
		wp_register_script( 'mkhb-burger-scripts', get_template_directory_uri() . '/header-builder/includes/assets/js/mkhb-burger-menu.js', array( 'jquery' ), THEME_VERSION, true );
		wp_register_script( 'mkhb', get_template_directory_uri() . '/header-builder/admin/js/main.js', array( 'jquery', 'mkhb-webfontloader', 'mkhb-navigation-scripts', 'mkhb-burger-scripts' ), THEME_VERSION, true );

		wp_localize_script( 'mkhb', 'ajax_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'home_url' => home_url(),
			'ajax_nonce_mkhb_set' => wp_create_nonce( 'mkhb-set-header' ),
			'ajax_nonce_mkhb_get' => wp_create_nonce( 'mkhb-get-header' ),
			'ajax_nonce_mkhb_get_s' => wp_create_nonce( 'mkhb-get-headers' ),
			'ajax_nonce_mkhb_clone' => wp_create_nonce( 'mkhb-clone-header' ),
			'ajax_nonce_mkhb_delete' => wp_create_nonce( 'mkhb-delete-header' ),
			'ajax_nonce_mkhb_set_global' => wp_create_nonce( 'mkhb-set-global-header' ),
			'ajax_nonce_mkhb_check_title' => wp_create_nonce( 'mkhb-check-title-header' ),
		) );

		ob_start();
		$this->setup_hb_admin_header();
		$this->setup_hb_admin_body();
		$this->setup_hb_admin_footer();
		// @todo We shouldn't use exit.
		exit;
	}

	/**
	 * Render the main application header and all associated metas, scripts, links, etc.
	 *
	 * Before this, we load the data from:
	 * - get_option( 'artbees_header_builder', array() );
	 *
	 * @since 5.9.0
	 *
	 * @todo Find a way to escape Javascript code here.
	 */
	public function setup_hb_admin_header() {
		global $mk_options;
		$result = $this->get_header_data();
		$post_id = $result['post_id'];
		$mk_typekit_id = ( ( $mk_options['typekit_id'] ) ? $mk_options['typekit_id'] : '' );
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php esc_html_e( 'Header Builder', 'mk_framework' ); ?></title>

			<script type="text/javascript">
				window.MK = window.MK || {};
				MK.HB = MK.HB || {};
				MK.HB.WP = MK.HB.WP || {};
				MK.HB.id = <?php echo $post_id; // WPCS: XSS OK. ?>;
				MK.HB.WP.menus = <?php echo wp_json_encode( wp_get_nav_menus() ); ?>;
				MK.HB.typekit_id = '<?php echo $mk_typekit_id; // WPCS: XSS OK. ?>';
			</script>
			<?php
			$menus_html = array();
			$menus = wp_get_nav_menus();
			add_filter( 'wp_nav_menu', 'mkhb_active_current_menu_item' );
			foreach ( $menus as $wp_term_object ) {
				$menus_html[ $wp_term_object->slug ] = wp_nav_menu(
					array(
						'menu' => $wp_term_object->slug,
						'container' => 'nav',
						'container_class' => 'hb-navigation hb-js-main-nav',
						'menu_class' => 'hb-navigation-ul',
						'echo' => false,
						'fallback_cb' => 'mk_link_to_menu_editor',
						'walker' => new hb_main_menu(),
					)
				);
			}
			remove_filter( 'wp_nav_menu', 'mkhb_active_current_menu_item' );
			?>
			<script type="text/javascript">
				MK.HB.WP.icons = <?php echo wp_json_encode( get_template_directory_uri() . '/assets/icons' ); ?>;
				MK.HB.WP.menusHTML = <?php echo wp_json_encode( $menus_html ); ?>;
				MK.HB.WC = <?php echo wp_json_encode( mkhb_woocommerce_is_active() ); ?>;
			</script>

			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="hb" style="position: fixed; display: block; width: 100vw; height: 100vh; overflow: hidden;">
		<?php
	}

	/**
	 * Render the main application body. This contains all the main parts visible on the front end.
	 *
	 * @since 5.9.0
	 */
	public function setup_hb_admin_body() {
		?>
		<div id="root" style='position: absolute; top: 0; height: 100%; bottom: 0; left: 0; background: #d6d6d6; overflow: visible; right: 0; min-width: 0; font-family: Helvetica, sans-serif;'></div>
		<?php
	}

	/**
	 * Render the main application footer and all associated metas, scripts, links, etc.
	 *
	 * @since 5.9.0
	 */
	public function setup_hb_admin_footer() {
		wp_print_scripts( 'mkhb' );
		?>
			</body>
		</html>
		<?php do_action( 'wp_footer' ); ?>
		<style type="text/css">#wpadminbar {display: none;}</style>
		<?php
	}

	/**
	 * Get Header data for HB admin page from custom post type and post meta.
	 *
	 * @since 6.0.0
	 *
	 * @return JSON JSON data that will be loaded in HB admin page.
	 */
	public function get_header_data() {
		$data = '{"model":{"activeDevice":"desktop","activeHeader":"normal","normal":{"desktop":{"past":[],"future":[],"present":[]},"tablet":{"past":[],"future":[],"present":[]},"mobile":{"past":[],"future":[],"present":[]}},"sticky":{"desktop":{"past":[],"future":[],"present":[]},"tablet":{"past":[],"future":[],"present":[]},"mobile":{"past":[],"future":[],"present":[]}},"options":{}},"viewModel":{"dropPreviewIndexPath":null,"rowDropPreviewIndexPath":null}}';
		$header_id = false;

		// Default return data.
		$return = array(
			'data' => $data,
			'post_id' => 0,
		);

		// A.1. GET param - ID.
		if ( ! empty( $_GET['id'] ) ) { // WPCS: CSRF ok.
			$get_id = sanitize_text_field( $_GET['id'] );
			$header_id = $this->check_header_is_plublished( $get_id );
		}

		// A.2. Global - HB post ID in option.
		$global_id = get_option( 'mkhb_global_header', null );
		if ( ! empty( $global_id ) && ! $header_id ) {
			$header_id = $this->check_header_is_plublished( $global_id );
		}

		// A.3. Latest Post - HB post ID in mkhb_header post type.
		if ( empty( $header_id ) ) {
			$latest_param = array(
				'post_type' => 'mkhb_header',
				'post_status' => 'publish',
				'numberposts' => 1,
				'order' => 'DESC',
				'orderby' => 'ID',
			);

			$latest_post = wp_get_recent_posts( $latest_param );

			// If latest is empty or no HB data save in mkhb_header post type, return empty array.
			if ( empty( $latest_post ) ) {
				// Create a new post into mkhb_header.
				$params = array(
					'post_title' => 'Default Header',
					'post_type' => 'mkhb_header',
					'post_status' => 'publish',
				);

				$header_id = wp_insert_post( $params );

				// Set default header as Global Header.
				update_option( 'mkhb_global_header', $header_id );
			}

			// If the latest header is not empty, get the post ID.
			if ( ! empty( $latest_post[0] ) ) {
				$header_id = $latest_post[0]['ID'];
			}
		}

		// A. Set HB post ID.
		$return['post_id'] = $header_id;

		return $return;
	}

	/**
	 * Check post Header by checking its status is publish.
	 *
	 * @since 6.0.0
	 *
	 * @param integer $header_id Header post ID.
	 * @return mixed Integer as header ID if the header is exist and published, otherwise false.
	 */
	private function check_header_is_plublished( $header_id ) {
		$get_status = get_post_status( $header_id );

		if ( false !== $get_status && 'publish' === $get_status ) {
			return $header_id;
		}

		return false;
	}
}

new MKHB_Screen();
