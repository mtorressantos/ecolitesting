<?php
/**
 * Contains various outputs to wp_head action
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       4.2
 * @since       5.9.7 Remove 'loading' class from body class.
 * @package     artbees
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * App Modules data collector
 */
if ( ! function_exists( 'mk_app_modules_header' ) ) {
	function mk_app_modules_header() {
		global $app_modules, $mk_options, $mk_shortcode_order, $is_header_shortcode_added;
		$sticky_header_offset = isset( $mk_options['sticky_header_offset'] ) ? $mk_options['sticky_header_offset'] : 'header';
		$toolbar_toggle = ! empty( $mk_options['theme_toolbar_toggle'] ) ? $mk_options['theme_toolbar_toggle'] : 'true';
		$post_id = global_get_post_id();
		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {
				$toolbar_toggle_meta = get_post_meta( $post_id, 'theme_toolbar_toggle', true );
				$sticky_header_offset_meta = get_post_meta( $post_id, '_sticky_header_offset', true );
				$toolbar_toggle = (isset( $toolbar_toggle_meta ) && ! empty( $toolbar_toggle_meta )) ? $toolbar_toggle_meta : $toolbar_toggle;
				$sticky_header_offset = (isset( $sticky_header_offset_meta ) && ! empty( $sticky_header_offset_meta )) ? $sticky_header_offset_meta : $sticky_header_offset;
			}
		}
		$app_modules[] = array(
			'name' => 'theme_header',
			'params' => array(
				'id' => 'mk-header',
				'height' => $mk_options['header_height'],
				'stickyHeight' => $mk_options['header_scroll_height'],
				'stickyOffset' => $sticky_header_offset,
				'hasToolbar' => $toolbar_toggle,
			),
		);

		$mk_shortcode_order = 0;
	}
	add_action( 'wp_head', 'mk_app_modules_header', 1 );
}

/**
 * Output header meta tags
 */
if ( ! function_exists( 'mk_head_meta_tags' ) ) {
	function mk_head_meta_tags() {
		echo '<meta charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '" />';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';
		echo '<meta name="format-detection" content="telephone=no">';
	}
	add_action( 'wp_head', 'mk_head_meta_tags', 0 );
}

/**
 * Output Facebook Open Graph meta.
 */
if ( ! function_exists( 'mk_open_graph_meta' ) ) {
	function mk_open_graph_meta() {

		if ( ! is_single() || defined( 'WPSEO_VERSION' ) ) {
			return false;
		}

		global $post;

		$post_type = get_post_meta( $post->ID, '_single_post_type', true );
		$post_thumb_id = get_post_thumbnail_id();

		if ( 'portfolio' == $post_type && empty( $post_thumb_id ) ) {
			$slideshow_posts = get_post_meta( $post->ID, '_gallery_images', true );
			$slideshow_posts = explode( ',', $slideshow_posts );
			$image_src_array = wp_get_attachment_image_src( $slideshow_posts[0], 'full' );
		} else {
			$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
		}

		$output = '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '"/>';

		if ( ! Mk_Image_Resize::is_default_thumb( $image_src_array[0] ) && ! empty( $image_src_array[0] ) ) {
			$output .= '<meta property="og:image" content="' . esc_url( $image_src_array[0] ) . '"/>';
		}

		$output .= '<meta property="og:url" content="' . esc_url( get_permalink() ) . '"/>';
		$output .= '<meta property="og:title" content="' . the_title_attribute(
			array(
				'echo' => false,
			)
		) . '"/>';
		$output .= '<meta property="og:description" content="' . esc_attr( get_the_excerpt() ) . '"/>';
		$output .= '<meta property="og:type" content="article"/>';
		echo $output;
	}
	add_action( 'wp_head', 'mk_open_graph_meta' );
}

/**
 * Outputs custom fav icons and apple touch icons into head tag.
 */
if ( ! function_exists( 'mk_apple_touch_icons' ) ) {
	function mk_apple_touch_icons() {
		global $mk_options;

		echo "\n";

		if ( $mk_options['custom_favicon'] ) :
			echo '<link rel="shortcut icon" href="' . esc_url( $mk_options['custom_favicon'] ) . '"  />' . "\n";
		else :
			echo '<link rel="shortcut icon" href="' . esc_url( THEME_IMAGES ) . '/favicon.png"  />' . "\n";
		endif;

		if ( $mk_options['iphone_icon'] ) :
			echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url( $mk_options['iphone_icon'] ) . '">' . "\n";
		endif;

		if ( $mk_options['iphone_icon_retina'] ) :
			echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . esc_url( $mk_options['iphone_icon_retina'] ) . '">' . "\n";
		endif;

		if ( $mk_options['ipad_icon'] ) :
			echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . esc_url( $mk_options['ipad_icon'] ) . '">' . "\n";
		endif;

		if ( $mk_options['ipad_icon_retina'] ) :
			echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . esc_url( $mk_options['ipad_icon_retina'] ) . '">' . "\n";
		endif;
	}
	add_action( 'wp_head', 'mk_apple_touch_icons', 2 );
}


/**
 * Outputs custom fav icons and apple touch icons into head tag.
 */
if ( ! function_exists( 'mk_dynamic_js_vars' ) ) {
	function mk_dynamic_js_vars() {
		global $mk_options;

		$post_id = global_get_post_id();
		$wp_p_id = $post_id ? $post_id : '';

		echo '<script type="text/javascript">';
		echo 'window.abb = {};';
		echo 'php = {};'; // it gets overwritten somewhere. do not attach anything more. remove ASAP and reattach to PHP.
		echo 'window.PHP = {};';
		echo 'PHP.ajax = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";';
		echo 'PHP.wp_p_id = "' . esc_js( $wp_p_id ) . '";';
		// What is really needed assign to php namespace (as it ships from php). Do not expose globals.
		// Remove rest.
		echo 'var mk_header_parallax, mk_banner_parallax, mk_page_parallax, mk_footer_parallax, mk_body_parallax;';

		echo 'var mk_images_dir = "' . esc_js( THEME_IMAGES ) . '",';
		echo 'mk_theme_js_path = "' . esc_js( THEME_JS ) . '",';
		echo 'mk_theme_dir = "' . esc_js( THEME_DIR_URI ) . '",';
		echo 'mk_captcha_placeholder = "' . esc_js( __( 'Enter Captcha', 'mk_framework' ) ) . '",';
		echo 'mk_captcha_invalid_txt = "' . esc_js( __( 'Invalid. Try again.', 'mk_framework' ) ) . '",';
		echo 'mk_captcha_correct_txt = "' . esc_js( __( 'Captcha correct.', 'mk_framework' ) ) . '",';
		echo 'mk_responsive_nav_width = ' . esc_js( $mk_options['responsive_nav_width'] ) . ',';
		echo 'mk_vertical_header_back = "' . esc_js( __( 'Back', 'mk_framework' ) ) . '",';
		echo 'mk_vertical_header_anim = "' . esc_js( $mk_options['vertical_menu_anim'] ) . '",';

		echo 'mk_check_rtl = ' . esc_js( (is_rtl()) ? 'false' : 'true' ) . ',';

		echo 'mk_grid_width = ' . esc_js( $mk_options['grid_width'] ) . ',';
		echo 'mk_ajax_search_option = "' . esc_js( $mk_options['header_search_location'] ) . '",';
		echo 'mk_preloader_bg_color = "' . esc_js( ($mk_options['preloader_bg_color']) ? $mk_options['preloader_bg_color'] : '#fff' ) . '",';
		echo 'mk_accent_color = "' . esc_js( $mk_options['skin_color'] ) . '",';
		echo 'mk_go_to_top =  "' . esc_js( ($mk_options['go_to_top']) ? $mk_options['go_to_top'] : 'false' ) . '",';
		echo 'mk_smooth_scroll =  "' . esc_js( ($mk_options['smoothscroll']) ? $mk_options['smoothscroll'] : 'false' ) . '",';

		$mk_preloader_bar_color = (isset( $mk_options['preloader_bar_color'] ) && ! empty( $mk_options['preloader_bar_color'] )) ? $mk_options['preloader_bar_color'] : $mk_options['skin_color'];

		echo 'mk_preloader_bar_color = "' . esc_js( $mk_preloader_bar_color ) . '",';

		echo 'mk_preloader_logo = "' . esc_js( $mk_options['preloader_logo'] ) . '";';
		if ( $post_id ) :
			echo 'var mk_header_parallax = ' . esc_js( get_post_meta( $post_id, 'header_parallax', true ) ? get_post_meta( $post_id, 'header_parallax', true ) : 'false' ) . ',';
			echo 'mk_banner_parallax = ' . esc_js( get_post_meta( $post_id, 'banner_parallax', true ) ? get_post_meta( $post_id, 'banner_parallax', true ) : 'false' ) . ',';
			echo 'mk_footer_parallax = ' . esc_js( get_post_meta( $post_id, 'footer_parallax', true ) ? get_post_meta( $post_id, 'footer_parallax', true ) : 'false' ) . ',';
			echo 'mk_body_parallax = ' . esc_js( get_post_meta( $post_id, 'body_parallax', true ) ? get_post_meta( $post_id, 'body_parallax', true ) : 'false' ) . ',';
			echo 'mk_no_more_posts = "' . esc_js( __( 'No More Posts', 'mk_framework' ) ) . '",';
		endif;

		// Webfonts.
		echo 'mk_typekit_id   = "' . esc_js( ( $mk_options['typekit_id'] ) ? $mk_options['typekit_id'] : '' ) . '",';
		echo 'mk_google_fonts = ' . mk_google_fonts() . ',';
		echo 'mk_global_lazyload = ' . esc_js( ( ! empty( $mk_options['global_lazyload'] ) ) ? $mk_options['global_lazyload'] : 'false' ) . ';';

		echo '</script>';
	}
	add_action( 'wp_head', 'mk_dynamic_js_vars', 3 );
}// End if().


/**
 * Adds preloaders overlay div when its option is enabled
 *
 * @return HTML
 */
if ( ! function_exists( 'mk_preloader_body_overlay' ) ) {
	function mk_preloader_body_overlay() {
		global $mk_options;
		$preloader_check = '';
		$post_id = global_get_post_id();

		$singular_preloader = ($post_id) ? get_post_meta( $post_id, 'page_preloader', true ) : '';

		if ( 'true' == $singular_preloader ) {
			$preloader_check = 'enabled';
		} else {
			if ( 'true' == $mk_options['preloader'] ) {
				$preloader_check = 'enabled';
			}
		}
		if ( 'enabled' == $preloader_check ) {
			echo '<div class="mk-body-loader-overlay page-preloader" style="background-color:' . esc_attr( $mk_options['preloader_bg_color'] ) . ';">';
			$loaderStyle = isset( $mk_options['preloader_animation'] ) ? $mk_options['preloader_animation'] : 'ball_pulse';

			if ( ! empty( $mk_options['preloader_logo'] ) ) {
				$preloader_logo_id = mk_get_attachment_id_from_url( $mk_options['preloader_logo'] );
				if ( ! empty( $preloader_logo_id ) ) {
					$preloader_logo_array = wp_get_attachment_image_src( $preloader_logo_id, 'full', true );
					$prelaoder_logo_width = $preloader_logo_array[1];
					$prelaoder_logo_height = $preloader_logo_array[2];
				} else {
					$preloader_logo_array = mk_getimagesize( $mk_options['preloader_logo'] );
					$prelaoder_logo_width = $preloader_logo_array[0];
					$prelaoder_logo_height = $preloader_logo_array[1];
				}

				if ( 'true' == $mk_options['retina_preloader'] ) {
					$prelaoder_logo_width = absint( $prelaoder_logo_width / 2 );
					$prelaoder_logo_height = absint( $prelaoder_logo_height / 2 );
				}
				echo '<img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="preloader-logo" src="' . esc_url( $mk_options['preloader_logo'] ) . '" width="' . esc_attr( $prelaoder_logo_width ) . '" height="' . esc_attr( $prelaoder_logo_height ) . '" >';

			}

			echo ' <div class="preloader-preview-area">';
			if ( 'ball_pulse' == $loaderStyle ) {
				echo '  <div class="ball-pulse">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'ball_clip_rotate_pulse' == $loaderStyle ) {
				echo '  <div class="ball-clip-rotate-pulse">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="border-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . ' transparent ' . esc_attr( $mk_options['preloader_icon_color'] ) . ' transparent;"></div>
                        </div>';
			} elseif ( 'square_spin' == $loaderStyle ) {
				echo '  <div class="square-spin">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'cube_transition' == $loaderStyle ) {
				echo '  <div class="cube-transition">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'ball_scale' == $loaderStyle ) {
				echo '  <div class="ball-scale">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'line_scale' == $loaderStyle ) {
				echo '  <div class="line-scale">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'ball_scale_multiple' == $loaderStyle ) {
				echo '  <div class="ball-scale-multiple">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'ball_pulse_sync' == $loaderStyle ) {
				echo '  <div class="ball-pulse-sync">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			} elseif ( 'transparent_circle' == $loaderStyle ) {
				echo '  <div class="transparent-circle" style="
                                border-top-color: ' . esc_attr( mk_hex2rgba( $mk_options['preloader_icon_color'], 0.2 ) ) . ';
                                border-right-color: ' . esc_attr( mk_hex2rgba( $mk_options['preloader_icon_color'], 0.2 ) ) . ';
                                border-bottom-color: ' . esc_attr( mk_hex2rgba( $mk_options['preloader_icon_color'], 0.2 ) ) . ';
                                border-left-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . ';">
                        </div>';
			} elseif ( 'ball_spin_fade_loader' == $loaderStyle ) {
				echo '  <div class="ball-spin-fade-loader">
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                            <div style="background-color: ' . esc_attr( $mk_options['preloader_icon_color'] ) . '"></div>
                        </div>';
			}// End if().
			echo '  </div>';
			echo '</div>';
		}// End if().
	}

	add_action( 'theme_after_body_tag_start', 'mk_preloader_body_overlay' );
}// End if().

/**
 * Populates classes to be added to body tag
 *
 * @since 6.0.1 Override current header style into 'custom' if HB is active.
 *
 * @return HTML
 */
if ( ! function_exists( 'mk_get_body_class' ) ) {
	function mk_get_body_class( $post_id ) {
		global $mk_options;
		$body_class = array();

		$header_style = ! empty( $mk_options['theme_header_style'] ) ? $mk_options['theme_header_style'] : 1;

		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {
				$header_style_meta = get_post_meta( $post_id, 'theme_header_style', true );
				$header_style = (isset( $header_style_meta ) && ! empty( $header_style_meta )) ? $header_style_meta : $header_style;
			}
		}

		// Get current header type. Default is 'pre_built_header'.
		$to_header = 'pre_built_header';
		if ( ! empty( $mk_options['header_layout_builder'] ) ) {
			$to_header = $mk_options['header_layout_builder'];
		}

		// If current header is HB, change $header_style into 'custom'.
		if ( 'header_builder' === $to_header ) {
			$header_style = 'custom';
		}

		if ( ('boxed_layout' == $mk_options['background_selector_orientation']) && ! ($post_id && get_post_meta( $post_id, '_enable_local_backgrounds', true ) == 'true' && get_post_meta( $post_id, 'background_selector_orientation', true ) == 'full_width_layout') ) {

			$body_class[] = 'mk-boxed-enabled';
		} elseif ( $post_id && get_post_meta( $post_id, '_enable_local_backgrounds', true ) == 'true' && get_post_meta( $post_id, 'background_selector_orientation', true ) == 'boxed_layout' ) {

			$body_class[] = 'mk-boxed-enabled';
		}

		if ( 4 == $header_style ) {
			$vertical_header_logo_align = (isset( $mk_options['vertical_header_logo_align'] ) && ! empty( $mk_options['vertical_header_logo_align'] )) ? $mk_options['vertical_header_logo_align'] : 'center';
			$header_align = ! empty( $mk_options['theme_header_align'] ) ? $mk_options['theme_header_align'] : 'left';

			if ( $post_id ) {
				$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

				if ( 'true' == $enable ) {
					$header_align_meta = get_post_meta( $post_id, 'theme_header_align', true );
					$header_align = (isset( $header_align_meta ) && ! empty( $header_align_meta )) ? $header_align_meta : $header_align;
				}
			}

			$body_class[] = 'vertical-header-enabled vertical-header-' . esc_attr( $header_align ) . ' logo-align-' . esc_attr( $vertical_header_logo_align );
		}

		return $body_class;
	}
}

/*
Checks if header is transparent
*/
if ( ! function_exists( 'is_header_transparent' ) ) {
	function is_header_transparent( $output = false ) {

		$post_id = global_get_post_id();
		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {
				$meta = get_post_meta( $post_id, '_transparent_header', true );
				$check = (isset( $meta ) && ! empty( $meta )) ? $meta : 'false';
				if ( 'true' == $check ) {
					if ( empty( $output ) ) {
						return true;
					} else {
						return $output;
					}
				}
			}
		}
		return false;
	}
}


/*
Checks header style
*/
if ( ! function_exists( 'get_header_style' ) ) {
	function get_header_style() {

		global $mk_options;

		$style = ! empty( $mk_options['theme_header_style'] ) ? $mk_options['theme_header_style'] : 1;

		$post_id = global_get_post_id();

		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {
				$meta = get_post_meta( $post_id, 'theme_header_style', true );
				$style = (isset( $meta ) && ! empty( $meta )) ? $meta : $style;
			}
		}

		return apply_filters( 'get_header_style', $style );
	}
}

/*
Check if header is enabled in meta options.
*/
if ( ! function_exists( 'is_header_show' ) ) {
	function is_header_show( $is_shortcode = false ) {

		if ( $is_shortcode ) {
			return true;
		}

		$post_id = global_get_post_id();
		$show_header = '';
		if ( $post_id ) {
			$show_header = get_post_meta( $post_id, '_template', true );
		} else {
			return true;
		}

		if ( ! in_array( $show_header, array( 'no-header', 'no-header-title', 'no-header-title-footer', 'no-header-footer' ) ) ) {

			return true;
		}
	}
}


/*
Check if header and page title is enabled in meta options.
*/
if ( ! function_exists( 'is_header_and_title_show' ) ) {
	function is_header_and_title_show( $is_shortcode = false ) {

		if ( $is_shortcode ) {
			return true;
		}

		$post_id = global_get_post_id();
		$show_header = '';
		if ( $post_id ) {
			$show_header = get_post_meta( $post_id, '_template', true );
		} else {
			return true;
		}

		if ( ! in_array( $show_header, array( 'no-header-title', 'no-header-title-footer' ) ) ) {

			return true;
		}
	}
}


/*
Check if header and page title is enabled in meta options.
*/
if ( ! function_exists( 'is_page_title_show' ) ) {
	function is_page_title_show( $is_shortcode = false ) {

		if ( $is_shortcode ) {
			return true;
		}

		$post_id = global_get_post_id();
		$show_header = '';
		if ( $post_id ) {
			$show_header = get_post_meta( $post_id, '_template', true );
		} else {
			return true;
		}

		if ( ! in_array( $show_header, array( 'no-title', 'no-footer-title', 'no-header-title', 'no-header-title-footer' ) ) ) {

			return true;
		}
	}
}


/*
Check if header toolbar is enabled in theme options or meta options.
*/
if ( ! function_exists( 'is_header_toolbar_show' ) ) {
	function is_header_toolbar_show( $is_shortcode = false ) {

		if ( $is_shortcode ) {
			return false;
		}

		global $mk_options;

		$post_id = global_get_post_id();
		$toolbar = ! empty( $mk_options['theme_toolbar_toggle'] ) ? $mk_options['theme_toolbar_toggle'] : 'true';

		if ( $post_id ) {
			$in_post = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' === $in_post ) {
				$meta = get_post_meta( $post_id, 'theme_toolbar_toggle', true );
				$toolbar = (isset( $meta ) && ! empty( $meta )) ? $meta : $toolbar;
			}
		}

		if ( 'false' === check_toolbar_elements() ) {
			$toolbar = 'false';
		}

		return $toolbar;
	}
} // End if().

/**
 * Check if some of toolbar's elements are disabled.
 *
 * @since 5.9.6
 * @return bool False when all elements are disabled.
 */
function check_toolbar_elements() {

	global $mk_options;

	if ( 'true' !== $mk_options['enable_header_date'] &&
		empty( $mk_options['header_toolbar_phone'] ) &&
		empty( $mk_options['header_toolbar_email'] ) &&
		empty( $mk_options['header_toolbar_tagline'] ) &&
		'false' === check_remaining_toolbar_elements()
	) {
		return 'false';
	}
}

/**
 * Check if remaining of toolbar's elements are disabled.
 *
 * @since 5.9.6
 * @return bool False when all elements are disabled.
 */
function check_remaining_toolbar_elements() {

	global $mk_options;

	if ( 'toolbar' !== $mk_options['header_search_location'] &&
		'true' !== $mk_options['header_toolbar_login'] &&
		'toolbar' !== $mk_options['header_social_location'] &&
		'true' !== $mk_options['header_toolbar_subscribe'] &&
		true !== has_nav_menu( 'toolbar-menu' )
	) {
		return 'false';
	}
}


/*
Check if header is enabled in meta options.
*/
if ( ! function_exists( 'get_header_json_data' ) ) {
	function get_header_json_data( $is_shortcode = false, $header_style ) {
		$skin = '';
		global $mk_options;

		$sticky_style = ! empty( $mk_options['header_sticky_style'] ) ? $mk_options['header_sticky_style'] : 'false';
		$sticky_style = $is_shortcode ? 'none' : $sticky_style;
		$sticky_offset = isset( $mk_options['sticky_header_offset'] ) ? $mk_options['sticky_header_offset'] : $mk_options['header_height'];
		$header_style = (isset( $header_style ) && ! empty( $header_style ) ) ? $header_style : get_header_style();

		$post_id = global_get_post_id();

		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {
				$skin = get_post_meta( $post_id, '_transparent_header_skin', true );
				$skin = (isset( $skin ) && ! empty( $skin )) ? $skin : 'light';
				$meta_sticky_offset = get_post_meta( $post_id, '_sticky_header_offset', true );
				$sticky_offset = ( ! empty( $meta_sticky_offset )) ? $meta_sticky_offset : $sticky_offset;
			}
		}

		$data = array(
			'height' => $mk_options['header_height'],
			'sticky-height' => $mk_options['header_scroll_height'],
			'responsive-height' => $mk_options['res_header_height'],
			'transparent-skin' => $skin,
			'header-style' => $header_style,
			'sticky-style' => $sticky_style,
			'sticky-offset' => $sticky_offset,
		);

		// TODO : Bart should remove below code and use data-settings data attribute.
		return "data-height='" . $mk_options['header_height'] . "'
                data-sticky-height='" . $mk_options['header_scroll_height'] . "'
                data-responsive-height='" . $mk_options['res_header_height'] . "'
                data-transparent-skin='" . $skin . "'
                data-header-style='" . $header_style . "'
                data-sticky-style='" . $sticky_style . "'
                data-sticky-offset='" . $sticky_offset . "'";

	}
}// End if().



/*
Get Header class
*/
if ( ! function_exists( 'mk_get_header_class' ) ) {
	function mk_get_header_class( $atts = array() ) {

		extract( $atts );

		global $mk_options;

		if ( is_header_toolbar_show() === 'false' ) {
			$mk_options['theme_toolbar_toggle'] = 'false';
		}

		$header_layout = ('true' == $mk_options['header_grid']) ? 'boxed-header' : 'full-header';
		$header_align = ! empty( $mk_options['theme_header_align'] ) ? $mk_options['theme_header_align'] : 'left';
		$toolbar_toggle = ! empty( $mk_options['theme_toolbar_toggle'] ) ? $mk_options['theme_toolbar_toggle'] : 'true';
		$sticky_style = ! empty( $mk_options['header_sticky_style'] ) ? $mk_options['header_sticky_style'] : 'false';
		$sticky_style_class = ('lazy' == $sticky_style) ? 'sticky-style-fixed' : 'sticky-style-' . $sticky_style;

		$sticky_style_class = $is_shortcode ? false : $sticky_style_class;

		$post_id = global_get_post_id();

		if ( $post_id ) {
			$enable = get_post_meta( $post_id, '_enable_local_backgrounds', true );

			if ( 'true' == $enable ) {

				$header_align_meta = get_post_meta( $post_id, 'theme_header_align', true );
				$header_align = (isset( $header_align_meta ) && ! empty( $header_align_meta )) ? $header_align_meta : $header_align;

				$toolbar_toggle_meta = get_post_meta( $post_id, 'theme_toolbar_toggle', true );
				$toolbar_toggle = (isset( $toolbar_toggle_meta ) && ! empty( $toolbar_toggle_meta )) ? $toolbar_toggle_meta : $toolbar_toggle;

				$skin_meta = get_post_meta( $post_id, '_transparent_header_skin', true );
				$skin = (isset( $skin_meta ) && ! empty( $skin_meta )) ? $skin_meta : 'light';

				$remove_bg_meta = get_post_meta( $post_id, '_trans_header_remove_bg', true );
				$remove_bg = (isset( $remove_bg_meta ) && ! empty( $remove_bg_meta )) ? $remove_bg_meta : 'true';
			}
		}

		$header_align = (isset( $sh_header_align ) && ! empty( $sh_header_align )) ? $sh_header_align : $header_align;
		$header_style = (isset( $sh_header_style ) && ! empty( $sh_header_style )) ? $sh_header_style : get_header_style();
		$toolbar_toggle = ('false' == $header_style) ? 'false' : $toolbar_toggle;
		$hover_styles = isset( $sh_hover_styles ) ? $sh_hover_styles : $mk_options['main_nav_hover'];
		$is_transparent = (isset( $sh_is_transparent )) ? ('false' == $sh_is_transparent ? false : is_header_transparent()) : is_header_transparent();
		$id = ! empty( $sh_id ) ? 'id="mk-header-' . esc_attr( $sh_id ) . '" ' : '';

		$logo_in_middle = (1 == $header_style) ? ('true' == $mk_options['logo_in_middle'] ? 'js-logo-middle logo-in-middle' : '') : '';

		$class[] = 'mk-header';
		$class[] = 'header-style-' . $header_style;
		$class[] = 'header-align-' . $header_align;
		$class[] = $logo_in_middle;
		$class[] = 'toolbar-' . $toolbar_toggle;
		$class[] = 'menu-hover-' . $hover_styles;
		$class[] = $sticky_style_class;
		$class[] = mk_get_bg_cover_class( $mk_options['banner_size'] );
		$class[] = $header_layout;
		$class[] = isset( $el_class ) ? $el_class : '';

		if ( $is_transparent ) {
			$class[] = 'transparent-header';
			$class[] = $skin . '-skin';
			$class[] = 'bg-' . $remove_bg;
		}

		return $id . 'class="' . esc_attr(implode( ' ', $class ) ) . '"';
	}
}// End if().


/*
Adds debugging information to front-end
*/
if ( ! function_exists( 'mk_theme_debugging_info' ) ) {
	function mk_theme_debugging_info() {
		$theme_data = wp_get_theme();
		echo '<meta name="generator" content="' . esc_attr( wp_get_theme() ) . ' ' . esc_attr( $theme_data['Version'] ) . '" />';
	}
	add_action( 'wp_head', 'mk_theme_debugging_info', 999 );
}


/*
Enables Testing environment variable for regression testings
*/
if ( ! function_exists( 'mk_enable_regression_testing' ) ) {
	function mk_enable_regression_testing() {
		$is_test = isset( $_GET['testing'] ) ? 'true' : 'false';
		echo '<script> var isTest = ' . esc_js( $is_test ) . '; </script>';
		if ( 'true' == $is_test ) {
			echo '<style>.mk-edge-slider .mk-slider-slide .edge-scale-down .edge-buttons,
                    .mk-edge-slider .mk-slider-slide .edge-scale-down .edge-desc,
                    .mk-edge-slider .mk-slider-slide .edge-scale-down .edge-title
                    { opacity: 1 !important; transform: scale(1) !important; }
                    .mk-moving-image img
                    { animation: none !important; } </style>';
		}
	}
	add_action( 'wp_head', 'mk_enable_regression_testing' );
}


/**
 * Generate Google fonts array. The array can be used directly in the
 * Webfont loader.
 *
 * @since  5.9.3
 * @return array
 */
if ( ! function_exists( 'mk_google_fonts' ) ) {

	function mk_google_fonts() {

		global $mk_options;

		$fonts = ! empty( $mk_options['fonts'] ) ? $mk_options['fonts'] : array();
		$weights = ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900';
		$google_fonts = array();

		foreach ( $fonts as $font ) {

			if ( empty( $font['fontFamily'] ) || empty( $font['elements'] ) || 'google' !== $font['type'] ) {
				continue;
			}

			$subset = ! empty( $font['subset'] ) && 'latin' != $font['subset'] ? ':' . $font['subset'] : '';

			$google_fonts[] = $font['fontFamily'] . $weights . $subset;

		}

		/**
		 * Filter google fonts.
		 *
		 * @since 5.9.4
		 * @var array
		 */
		$google_fonts = apply_filters( 'mk_google_fonts', $google_fonts );

		return wp_json_encode( $google_fonts );
	}
}
