<?php
/**
 * General helper functions.
 *
 * @package Header_Builder
 * @subpackage Helpers
 * @since 5.9.1
 * @since 5.9.3 Add checking function for WooCommerce.
 * @since 5.9.5 Function 'hb_is_frontend_active' is deprecated.
 * @since 6.0.0 Change hb_ prefix into mkhb_.
 */

/**
 * Check if HB front end is active outside the preview page.
 *
 * @since 5.9.1
 * @since 5.9.5 Deprecated. But, we will keep this as a note.
 *
 * @return boolean True if active. Default is false.
 */
function hb_is_frontend_active() {
	$hb_options = json_decode( get_option( 'artbees_header_builder' ) );

	return isset( $hb_options->model ) && isset( $hb_options->model->activeOnFrontEnd ) && $hb_options->model->activeOnFrontEnd;
}

/**
 * Check if HB front end is activated from Theme Options.
 *
 * @since 5.9.5
 * @since 6.0.0 Rename prefix from hb_ into mkhb_. Optimize conditional statement.
 *
 * @return boolean True if active. Default is false.
 */
function mkhb_is_to_active() {
	global $mk_options;

	$to_header = 'pre_built_header';
	if ( ! empty( $mk_options['header_layout_builder'] ) ) {
		$to_header = $mk_options['header_layout_builder'];
	}

	if ( 'header_builder' === $to_header ) {
		return true;
	}

	return false;
}

/**
 * Check if header is active from template setting of Page Options.
 *
 * @since 6.1.2
 *
 * @return boolean True if active. Default is true.
 */
function mkhb_is_po_active() {
	global $post;

	// Ensure $post is not empty.
	if ( ! empty( $post->ID ) ) {
		// Disallowed templates to display header.
		$disallowed = array( 'no-header', 'no-header-title', 'no-header-footer', 'no-header-title-footer' );

		// Get page template option.
		$template = get_post_meta( $post->ID, '_template', true );

		// If current template is disallowed, return false.
		if ( in_array( $template, $disallowed ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Add current-menu-item class in preview navigation.
 *
 * @since 5.9.1
 * @since 6.0.0 Rename prefix from hb_ into mkhb_.
 *
 * @param  string $output Navigation HTML output.
 * @return boolean        True if active. Default is false.
 */
function mkhb_active_current_menu_item( $output ) {
	$output = preg_replace( '/class="menu-item/', 'class="current-menu-item menu-item', $output, 1 );
	return $output;
}

/**
 * Check if WooCommerce is active or not by checking if WooCommerce is exist or not.
 *
 * @since 5.9.3
 * @since 6.0.0 Rename prefix from hb_ into mkhb_.
 *
 * @return boolean WooCommerce activation status.
 */
function mkhb_woocommerce_is_active() {
	if ( class_exists( 'WooCommerce' ) ) {
		return true;
	}
	return false;
}

/**
 * Check if the data is correct JSON format or not.
 *
 * @since 5.9.8
 * @since 6.0.0 Rename prefix from hb_ into mkhb_.
 *
 * @param mixed $data Data need to be check.
 * @return boolean    True if the data is correct JSON.
 */
function mkhb_is_json( $data ) {
	json_decode( $data );
	return ( json_last_error() === JSON_ERROR_NONE );
}

/**
 * Check if the shortcode is rendered in current device.
 *
 * @since 6.0.0
 *
 * @param  string $device    Current device used.
 * @param  array  $visibilty Shortcode device visibility.
 * @return boolean           True if displayed, false if not.
 */
function mkhb_is_shortcode_displayed( $device, $visibilty ) {
	// If device or visibility are empty, return false. Default return false.
	if ( empty( $device ) || empty( $visibilty ) ) {
		return false;
	}

	// Return true only if device name is found in visibility list.
	if ( false !== strpos( $visibilty, $device ) ) {
		return true;
	}

	return false;
}

/**
 * Get additional container class based on element display and alignment.
 *
 * @since 6.0.0
 *
 * @param  array $options All shortcode attributes.
 * @return string         Additional class for display and alignment.
 */
function mkhb_shortcode_display_class( $options ) {
	$el_class = 'mkhb-block';

	// If display option is not exist, return default block.
	if ( ! isset( $options['display'] ) ) {
		return $el_class;
	}

	// If display is not inline, return default block.
	if ( 'inline' !== $options['display'] ) {
		return $el_class;
	}

	$el_class = 'mkhb-inline-left';

	// If alignment option is not empty, add it as inline class suffix.
	if ( ! empty( $options['alignment'] ) ) {
		$el_class = 'mkhb-inline-' . $options['alignment'];
	}

	return $el_class;
}

/**
 * Get additional container attribute based on element display and alignment.
 *
 * @since 6.0.0
 *
 * @param  array $options All shortcode attributes.
 * @return string         Additional attribute for display and alignment.
 */
function mkhb_shortcode_display_attr( $options ) {
	$data_align = 'left';
	$data_display = 'block';

	if ( ! empty( $options['alignment'] ) ) {
		$data_align = $options['alignment'];
	}

	if ( ! empty( $options['display'] ) ) {
		$data_display = $options['display'];
	}

	$data_attr = 'data-align="' . esc_attr( $data_align ) . '" data-display="' . esc_attr( $data_display ) . '"';

	return $data_attr;
}

/**
 * Get HB header list.
 *
 * @since 6.0.0
 *
 * @return array Header builder list in array with key and title.
 */
function mkhb_get_header_list() {
	// Get Global Header post.
	$fallback_id   = get_option( 'mkhb_global_header', 'nothing' );
	$fallback_post = get_post( $fallback_id );

	// Get data from DB.
	$posts = get_posts( array(
		'post_type'   => 'mkhb_header',
		'post_status' => 'publish',
		'numberposts' => -1,
		'orderby'     => 'title',
		'order'       => 'ASC',
		'exclude'     => $fallback_id,
	) );

	// Insert Global Header to the list in the first place.
	if ( null !== $fallback_post ) {
		array_unshift( $posts, $fallback_post );
	}

	// Set header options.
	$options = array();
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $header ) {
			$options[ $header->ID ] = $header->post_title;

			// If the header is the global header, add prefix Global Header.
			if ( absint( $header->ID ) === absint( $fallback_id ) ) {
				/* translators: %s: page title */
				$options[ $header->ID ] = sprintf( __( 'Global Header - %s', 'mk_framework' ), $header->post_title );
			}
		}
	}

	// If the options generated is empty, set default option with "No header found" option.
	if ( empty( $options ) ) {
		$options = array(
			0 => __( 'No header found', 'mk_framework' ),
		);
	}

	return $options;
}

/**
 * Check HB is active or not for Jupiter Styling Options in Post/Page Admin area.
 *
 * NOTE: Research only.
 *
 * Right now, this function is not used. But, this function is very helpful to decide HB option
 * should be displayed or not in Jupiter Styling Option of post/page admin area. We have a task
 * about that.
 *
 * @since 6.0.0
 *
 * @return boolean Return false if the process is already done.
 */
function mkhb_check_header_layout_builder() {
	$header_type = 'pre_built_header';
	$mk_options = get_option( THEME_OPTIONS );

	// If theme option is empty.
	if ( empty( $mk_options ) ) {
		return false;
	}

	// If header layout builder of theme options is not exist.
	if ( ! isset( $mk_options['header_layout_builder'] ) ) {
		return false;
	}

	$header_type = $mk_options['header_layout_builder'];

	// Get current post ID.
	$post_id_active = 0;
	if ( ! empty( $_GET['post'] ) ) { // WPCS: CSRF ok.
		$post_id_active = absint( $_GET['post'] );
	}

	// Get current meta of _header_layout_builder type.
	$header_type_meta = get_post_meta( $post_id_active, '_header_layout_builder', true );

	// If data already exist and equal, skip.
	if ( $header_type === $header_type_meta ) {
		return false;
	}

	update_post_meta( $post_id_active, '_header_layout_builder', $header_type );
}


/**
 * Check if current Jupiter Styling Options override HB settings.
 *
 * @since 6.0.0
 *
 * @return boolean Return true if HB settings are overriden by Jupiter Styling Options.
 */
function mkhb_is_override_by_styling() {
	$post_id = global_get_post_id();

	if ( ! empty( $post_id ) ) {
		$override = get_post_meta( $post_id, '_enable_local_backgrounds', true );
		$override = filter_var( $override, FILTER_VALIDATE_BOOLEAN );
		return $override;
	}

	return false;
}

/**
 * Check if current header is active or not.
 *
 * @since 6.0.0
 *
 * @param  integer $header_id Current header ID will be checked.
 * @return boolean            Current header status.
 */
function mkhb_is_post_header_active( $header_id ) {
	if ( empty( $header_id ) ) {
		return false;
	}

	$post_id_status = get_post_status( $header_id );

	if ( 'publish' === $post_id_status ) {
		return true;
	}

	return false;
}

/**
 * Get active header ID.
 *
 * @since 6.0.0
 *
 * @return integer Active header ID.
 */
function mkhb_get_active_header_id() {
	$header_id = (int) get_query_var( 'header-builder-preview-id', 0 );

	// A.2. User current override header ID from current post.
	if ( empty( $header_id ) && mkhb_is_override_by_styling() ) {
		// Get correct header ID.
		$post_id = global_get_post_id();
		$header_id = get_post_meta( $post_id, '_hb_override_template_id', true );
	}

	// A.3. Use global header if there is no overriding on header.
	if ( empty( $header_id ) || ! mkhb_is_post_header_active( $header_id ) ) {
		$header_id = get_option( 'mkhb_global_header', null );
	}

	// A.4. Use the latest post if there is no global header.
	if ( empty( $header_id ) || ! mkhb_is_post_header_active( $header_id ) ) {
		$latest_param = array(
			'post_type' => 'mkhb_header',
			'post_status' => 'publish',
			'numberposts' => 1,
			'order' => 'DESC',
			'orderby' => 'ID',
		);

		$latest_post = wp_get_recent_posts( $latest_param );

		// If the latest header is not empty, get the post ID.
		if ( ! empty( $latest_post[0]['ID'] ) ) {
			$header_id = $latest_post[0]['ID'];
		}
	}

	/**
	 * Filter the active header ID.
	 *
	 * This filter is used internaly to generate the header previews.
	 *
	 * @since 6.0.3
	 */
	return apply_filters( '_mkhb_active_header_id', $header_id, get_current_user_id() );
}
