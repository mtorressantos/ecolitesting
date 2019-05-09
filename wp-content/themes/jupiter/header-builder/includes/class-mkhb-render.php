<?php
/**
 * Header Builder: Shortcode Render, MKHB_Render class.
 *
 * For use in front end integration with Jupiter.
 *
 * @package Header_Builder
 * @subpackage Elements_Generator
 * @since 6.0.0
 */

/**
 * Run hooks to render HB shortcodes in HB Custom Header.
 *
 * @since 6.0.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * - The class HB_Model has an overall complexity of 53 which is very high. The configured
 *   complexity threshold is 50.
 */
class MKHB_Render {

	/**
	 * HB current post ID.
	 *
	 * @var string
	 */
	public $header_id;

	/**
	 * Current post/page ID.
	 *
	 * @var integer
	 */
	public $post_id;

	/**
	 * HB pre rendered shortcode available categorized by workspace and device.
	 *
	 * @var string
	 */
	public $content;

	/**
	 * All HB pre rendered shortcode collected.
	 *
	 * @var string
	 */
	public $raw_content;

	/**
	 * HB all rendered shortcodes.
	 *
	 * @var string
	 */
	public $rendered;

	/**
	 * All HB shortcode hooks.
	 *
	 * @var string
	 */
	public $hooks;

	/**
	 * MKHB_Render constructor. Run some action to render HB.
	 *
	 * @since 6.0.0
	 */
	public function __construct() {
		// Get shortcodes from database.
		$result = $this->get_shortcodes();
		$this->content = $result['content'];
		$this->raw_content = $result['raw_content'];

		// If Header content is empty, stop all process.
		if ( empty( $this->content ) ) {
			return false;
		}

		// Prepare current post ID and generate all rendered shortcodes.
		$this->post_id = global_get_post_id();
		$this->rendered = $this->render_shortcode();

		// Run hooks after shortcodes are rendered.
		$this->run_hooks();

		// Render shortcode.
		add_action( 'hb_grid_markup', array( $this, 'run_shortcode' ) );

		// Enqueue all styles and scripts based on available shortcodes.
		add_action( 'mk_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'mk_enqueue_styles_minified', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Print rendered HB shortcodes.
	 *
	 * @since 6.0.0
	 */
	public function run_shortcode() {
		echo $this->rendered; // WPCS: XSS OK.
	}

	/**
	 * Run hooks after HB shortcodes are generated.
	 *
	 * @since 6.0.0
	 * @since 6.0.3 Add hook action for adding internal CSS.
	 */
	public function run_hooks() {
		// Initiate hooks instance.
		$instance = mkhb_hooks();

		// Fetch hooks data and set is as public var.
		$this->hooks = array();
		if ( ! empty( $instance ) ) {
			$this->hooks = $instance::get_hooks();
		}

		// Internal styles.
		if ( ! empty( $this->hooks['styles'] ) ) {
			$minifier = new SimpleCssMinifier();
			$style = $minifier->minify( $this->hooks['styles'] );
			wp_register_style( 'mkhb', false, array( 'mkhb-render' ) );
			wp_enqueue_style( 'mkhb' );
			wp_add_inline_style( 'mkhb', $style );
		}

		// Navigation, Textbox, and Button fonts.
		if ( ! empty( $this->hooks['fonts'] ) ) {
			add_filter( 'mk_google_fonts', array( $this, 'enqueue_fonts' ) );
		}

		// Navigation current menu item.
		if ( ! empty( $this->hooks['resp-navigation'] ) ) {
			add_action( 'hb_grid_extra', 'mkhb_resp_navigation' );
		}

		// Shopping Icon.
		if ( ! empty( $this->hooks['shopping-icon'] ) ) {
			add_filter( 'body_class', 'mkhb_shopping_icon_body_class' );
			add_filter( 'woocommerce_add_to_cart_fragments', 'mkhb_shopping_icon_add_to_cart_fragments' );
			remove_action( 'add_to_cart_responsive', 'mk_add_to_cart_responsive', 20 );
			add_action( 'add_to_cart_responsive', 'mkhb_shopping_icon_add_to_cart_responsive', 20 );
		}
	}

	/**
	 * Enqueue all shortcodes fonts used.
	 *
	 * @since 6.0.0
	 *
	 * @param  array $google_fonts All Google Fonts list from theme options.
	 * @return array               All updated list after rendering shortcodes.
	 */
	public function enqueue_fonts( $google_fonts ) {
		$fonts = array_unique( $this->hooks['fonts'], SORT_REGULAR );

		foreach ( $fonts as $font ) {
			// If font type or family is empty, skip.
			if ( empty( $font['font-type'] ) || empty( $font['font-family'] ) ) {
				continue;
			}

			// If font type is not Google font, skip.
			if ( 'google' !== $font['font-type'] ) {
				continue;
			}

			// Check font label.
			$font_label = $font['font-family'] . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900';

			// Add to list if not exist yet.
			if ( ! in_array( $font_label, $google_fonts, true ) ) {
				$google_fonts[] = $font_label;
			}
		}

		return $google_fonts;
	}

	/**
	 * Get all shortcode from database.
	 *
	 * @since 6.0.0
	 *
	 * @return string HB markups and styles.
	 */
	private function get_shortcodes() {
		// Default Header content.
		$return = array(
			'content' => array(),
			'raw_content' => '',
		);

		// Get and set public Header ID.
		$this->header_id = mkhb_get_active_header_id();

		// If Header ID is empty, return empty content.
		if ( empty( $this->header_id ) ) {
			return $return;
		}

		// Device and workspace list.
		$list = array(
			'normal_header_desktop',
			'normal_header_tablet',
			'normal_header_mobile',
			'sticky_header_desktop',
			'sticky_header_tablet',
			'sticky_header_mobile',
		);

		// Render all shortcodes based on device and workspace.
		foreach ( $list as $point ) {
			// Get all shortcodes data.
			$data = get_post_meta( $this->header_id, '_mkhb_content_' . $point, true );

			if ( empty( $data ) ) {
				continue;
			}

			$return['content'][ $point ] = $data;
			$return['raw_content'] .= $data;
		}

		return $return;
	}

	/**
	 * Render HB based on device and workspace.
	 *
	 * @since 6.0.0
	 */
	private function render_shortcode() {
		$content = '';

		// Get sticky status.
		$sticky_status = $this->get_header_option( 'sticky_header', false );
		$sticky_status = filter_var( $sticky_status, FILTER_VALIDATE_BOOLEAN );

		// Render all shortcodes based on device and workspace.
		foreach ( $this->content as $point => $data ) {
			if ( empty( $data ) ) {
				continue;
			}

			// Fetch device and workspace.
			$point_raw = explode( '_header_', $point );
			$workspace = $point_raw[0];
			$device = $point_raw[1];

			if ( 'sticky' === $workspace && ! $sticky_status ) {
				continue;
			}

			$shortcode = do_shortcode( $data );

			// Add class and attributes if the header is sticky or fixed/overlapping.
			$header_class = $this->get_header_type_class( $device, $workspace, $shortcode );
			$header_attr = $this->get_header_type_attr( $device, $workspace );

			$content .= sprintf( '
				<div class="mkhb-device mkhb-%s mkhb-%s %s" %s>
					<div class="mkhb-device-container">
						%s
					</div>
				</div>',
				esc_attr( $workspace ),
				esc_attr( $device ),
				esc_attr( $header_class ),
				$header_attr,
				$shortcode
			);
		}

		return $content;
	}

	/**
	 * Get header type class name based on the options. (Sticky or Fixed/Overlapping)
	 *
	 * @since 6.0.0
	 *
	 * @param  string $device    Current device name.
	 * @param  string $workspace Current data type for device, normal or sticky.
	 * @param  string $markup    Device markup content.
	 * @return string            Header type class name.
	 */
	private function get_header_type_class( $device, $workspace, $markup ) {
		$class_name = '';

		// Device category, only Laptop and Mobile.
		$device_category = array(
			'desktop' => 'laptop',
			'tablet' => 'mobile',
			'mobile' => 'mobile',
		);

		// Get overlapping status.
		$overlapping_status = $this->is_overlap();

		// Get sticky status.
		$sticky_status = $this->get_header_option( 'sticky_header', false );
		$sticky_status = filter_var( $sticky_status, FILTER_VALIDATE_BOOLEAN );

		// Get fixed status. Only active if sticky is disabled.
		$fixed_status = false;
		if ( ! $sticky_status ) {
			$fixed_status = $this->get_header_option( $device_category[ $device ], false );
			$fixed_status = filter_var( $fixed_status, FILTER_VALIDATE_BOOLEAN );
		}

		// 1. Overlapping class.
		if ( $overlapping_status && 'normal' === $workspace ) {
			$class_name .= 'mkhb-overlap ';
		}

		// 2. Sticky class.
		if ( $sticky_status && 'sticky' === $workspace ) {
			$class_name .= 'mkhb-sticky ';
			$sticky_effect = $this->get_header_option( 'sticky_header_behaviour', '' );
			if ( ! empty( $sticky_effect ) ) {
				$class_name .= 'mkhb-sticky--' . $sticky_effect . ' ';
			}
		}

		// 3. Fixed class.
		if ( $fixed_status ) {
			$class_name .= 'mkhb-fixed ';
			// Add class if is fixed and the content is not empty.
			if ( ! empty( $markup ) ) {
				$class_name .= 'mkhb-fixed--filled ';
			}
		}

		return $class_name;
	}

	/**
	 * Get header type attribute key and values name based on the options. (Sticky)
	 *
	 * @since 6.0.0
	 *
	 * @param  string $device    Current device name.
	 * @param  string $workspace Current data type for device, normal or sticky.
	 * @return string            Header type class name.
	 */
	private function get_header_type_attr( $device, $workspace ) {
		// Default value.
		$sticky_offset_attr = '';
		$sticky_effect_attr = '';

		// Sticky Header Special Attributes.
		$sticky_status = $this->get_header_option( 'sticky_header', false );
		$sticky_status = filter_var( $sticky_status, FILTER_VALIDATE_BOOLEAN );
		if ( 'sticky' === $workspace && true === $sticky_status ) {
			// Get data offset.
			$sticky_offset = $this->get_header_sticky_offset();
			$sticky_offset_attr = 'data-offset="' . esc_attr( $sticky_offset ) . '"';

			// Get data effect.
			$sticky_effect = $this->get_header_option( 'sticky_header_behaviour', 'slide-down' );
			$sticky_effect_attr = 'data-effect="' . esc_attr( $sticky_effect ) . '"';
		}

		$attr = sprintf(
			'data-device="%s" data-workspace="%s" %s %s',
			esc_attr( $device ),
			esc_attr( $workspace ),
			$sticky_offset_attr,
			$sticky_effect_attr
		);

		return $attr;
	}

	/**
	 * Get HB global options value.
	 *
	 * @since 6.0.0
	 *
	 * @param  string $key     Option key that will be searched.
	 * @param  string $default Default option value if the option is not exist or empty.
	 * @return mixed           The value of current option. No empty value return, except it's
	 *                         the default value.
	 */
	private function get_header_option( $key, $default ) {
		$option = get_post_meta( $this->header_id, '_mkhb_options_' . $key, true );

		if ( empty( $option ) ) {
			return $default;
		}

		return $option;
	}

	/**
	 * Get current header overlapping status.
	 *
	 * @since 6.0.0
	 *
	 * @return boolean Current header overlapping status.
	 */
	private function is_overlap() {
		// Get overlapping status.
		$overlapping_status = $this->get_header_option( 'overlapping_content', false );

		// Check if current page override HB logo source.
		if ( mkhb_is_override_by_styling() ) {
			$overlapping_status = get_post_meta( $this->post_id, '_transparent_header', true );
		}

		$overlapping_status = filter_var( $overlapping_status, FILTER_VALIDATE_BOOLEAN );

		return $overlapping_status;
	}

	/**
	 * Get current header offset.
	 *
	 * @since 6.0.0
	 *
	 * @return string Current sticky header offset.
	 */
	private function get_header_sticky_offset() {
		// Get data offset.
		$sticky_offset = $this->get_header_option( 'sticky_header_offset', 0 );

		// Check if current page override HB logo source.
		if ( mkhb_is_override_by_styling() ) {
			$sticky_offset_over = get_post_meta( $this->post_id, '_sticky_header_offset', true );
			if ( ! empty( $sticky_offset_over ) ) {
				return $sticky_offset_over;
			}
		}

		return $sticky_offset;
	}

	/**
	 * Load our styles when mk_enqueue_styles() is called.
	 *
	 * @since 6.0.0
	 */
	public function enqueue_styles() {
		// Enqueue HB shortcodes default style.
		if ( has_shortcode( $this->raw_content, 'mkhb_logo' ) ) {
			wp_enqueue_style( 'mkhb-logo', HB_ASSETS_URI . 'css/mkhb-logo.css', array(), THEME_VERSION );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_textbox' ) ) {
			wp_enqueue_style( 'mkhb-textbox', HB_ASSETS_URI . 'css/mkhb-textbox.css', array(), THEME_VERSION );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_button' ) ) {
			wp_enqueue_style( 'mkhb-button', HB_ASSETS_URI . 'css/mkhb-button.css', array(), THEME_VERSION );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_navigation' ) ) {
			wp_enqueue_style( 'mkhb-navigation', HB_ASSETS_URI . 'css/mkhb-navigation.css', array(), THEME_VERSION );
			wp_enqueue_script( 'mkhb-navigation-burger', HB_ASSETS_URI . 'js/navigation/mkhb-navigation-burger.js', array( 'jquery' ), THEME_VERSION, true );
			wp_enqueue_script( 'mkhb-navigation-responsive', HB_ASSETS_URI . 'js/navigation/mkhb-navigation-responsive.js', array( 'jquery' ), THEME_VERSION, true );
			wp_enqueue_script( 'mkhb-navigation-script', HB_ASSETS_URI . 'js/navigation/mkhb-navigation-script.js', array( 'jquery' ), THEME_VERSION, true );
			wp_enqueue_script( 'mkhb-navigation', HB_ASSETS_URI . 'js/navigation/mkhb-navigation.js', array( 'jquery' ), THEME_VERSION, true );
		}

		// Load Icons Styles.
		$this->enqueue_styles_icons();

		// Load Overriding Styles.
		$this->enqueue_styles_over();
	}

	/**
	 * Load Icons styles when mk_enqueue_styles() is called.
	 *
	 * @since 6.0.0
	 * @since 6.0.3 Enqueue mkhb-icon before mkhb-social. Most of the markup of Social
	 *              element is similar with Icon element. We can use the exact same CSS.
	 */
	private function enqueue_styles_icons() {
		if ( has_shortcode( $this->raw_content, 'mkhb_search' ) ) {
			wp_enqueue_style( 'mkhb-search', HB_ASSETS_URI . 'css/mkhb-search.css', array(), THEME_VERSION );
			wp_enqueue_script( 'mkhb-search', HB_ASSETS_URI . 'js/mkhb-search.js', array( 'jquery' ), THEME_VERSION, true );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_social_media' ) ) {
			wp_enqueue_style( 'mkhb-icon', HB_ASSETS_URI . 'css/mkhb-icon.css', array(), THEME_VERSION );
			wp_enqueue_style( 'mkhb-social', HB_ASSETS_URI . 'css/mkhb-social.css', array(), THEME_VERSION );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_shopping_icon' ) ) {
			wp_enqueue_style( 'mkhb-shop-cart', HB_ASSETS_URI . 'css/mkhb-shop-cart.css', array(), THEME_VERSION );
			wp_enqueue_script( 'mkhb-shop-cart', HB_ASSETS_URI . 'js/mkhb-shop-cart.js', array( 'jquery' ), THEME_VERSION, true );
		}

		if ( has_shortcode( $this->raw_content, 'mkhb_icon' ) ) {
			wp_enqueue_style( 'mkhb-icon', HB_ASSETS_URI . 'css/mkhb-icon.css', array(), THEME_VERSION );
		}
	}

	/**
	 * Load Overriding styles when mk_enqueue_styles() is called.
	 *
	 * @since 6.0.0
	 */
	private function enqueue_styles_over() {
		if ( ! mkhb_is_override_by_styling() ) {
			return;
		}

		// Background attributes.
		$banner_bg_position = get_post_meta( $this->post_id, 'banner_position', true );
		$banner_bg_repeat = get_post_meta( $this->post_id, 'banner_repeat', true );
		$banner_bg_attachment = get_post_meta( $this->post_id, 'banner_attachment', true );

		$style = ".hb-custom-header {
			background-position: {$banner_bg_position};
		    background-repeat: {$banner_bg_repeat};
		    background-attachment: {$banner_bg_attachment};
		";

		// Override Blog Title background size.
		$banner_bg_size = get_post_meta( $this->post_id, 'banner_size', true );
		$banner_bg_size = filter_var( $banner_bg_size, FILTER_VALIDATE_BOOLEAN );
		if ( $banner_bg_size ) {
			$style .= 'background-size: cover;';
		}

		// Override Blog Title background image.
		$banner_bg_image = get_post_meta( $this->post_id, 'banner_image', true );
		if ( ! empty( $banner_bg_image ) ) {
			$style .= "background-image: url({$banner_bg_image});";
		}

		// Override Blog Title background gradient.
		$banner_bg_gradient = get_post_meta( $this->post_id, 'banner_color_gradient', true );
		if ( 'gradient' === $banner_bg_gradient ) {
			$banner_bg_1 = get_post_meta( $this->post_id, 'banner_color', true );
			$banner_bg_2 = get_post_meta( $this->post_id, 'banner_color_2', true );

			$gradient_angle = get_post_meta( $this->post_id, 'banner_color_gradient_angle', true );
			$direction = array(
				'vertical' => '',
				'horizontal' => 'to right,',
				'diagonal_left_bottom' => 'to right bottom,',
				'diagonal_left_top' => 'to right top,',
			);

			$gradient_style = get_post_meta( $this->post_id, 'banner_color_gradient_style', true );

			if ( 'radial' === $gradient_style ) {
				$style .= "background: radial-gradient({$banner_bg_1} 0%, {$banner_bg_2} 100%);";
			}

			if ( 'linear' === $gradient_style ) {
				$style .= "background: linear-gradient({$direction[ $gradient_angle ]} {$banner_bg_1} 0%, {$banner_bg_2} 100%);";
			}
		}

		if ( 'single' === $banner_bg_gradient ) {
			// Override Blog Title background color.
			$banner_bg = get_post_meta( $this->post_id, 'banner_color', true );
			if ( ! empty( $banner_bg ) ) {
				$style .= "background-color: {$banner_bg};";
			}
		}

		$style .= '}';

		wp_add_inline_style( 'mkhb', $style );
	}
}
