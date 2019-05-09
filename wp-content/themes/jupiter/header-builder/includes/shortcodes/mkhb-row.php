<?php
/**
 * Header Builder: mkhb_row shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Row element shortcode.
 *
 * @since 6.0.0
 * @since 6.0.3 Separate markup logic as new function mkhb_logo_markup().
 *
 * @param  array  $atts    All parameter will be used in the shortcode.
 * @param  string $content Content inside Row shortcode.
 * @return string          Rendered HTML.
 */
function mkhb_row_shortcode( $atts, $content ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-row-1',
			'width' => 'full',
			'margin' => '',
			'padding' => '',
			'border-width' => '',
			'border-color' => '',
			'background-color' => '',
			'background-image' => '',
			'background-repeat' => '',
			'background-position' => '',
			'background-attachment' => '',
			'sequence' => '0,2,1',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if the row is empty or it should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) || empty( $content ) ) {
		return '';
	}

	// Set Row internal style.
	$style = mkhb_row_style( $options );

	// Set Row markup.
	$markup = mkhb_row_markup( $options, $content );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	return $markup;
}
add_shortcode( 'mkhb_row', 'mkhb_row_shortcode' );

/**
 * Generate markup for HB Row.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $content Row content e.g: shortcodes.
 * @return string         Row markup.
 */
function mkhb_row_markup( $options, $content ) {
	// Row ID.
	$row_id = $options['id'];

	// Additional Class.
	$row_class = '';
	if ( ! empty( $options['background-image'] ) ) {
		$row_class = 'mkhb-row--bg-image';
	}

	$markup = sprintf(
		'<div id="%s" class="mkhb-row mkhb-equal-height-columns %s">
			<div class="mkhb-row__container">
				%s
			</div>
			<div class="clearfix"></div>
		</div>',
		$row_id,
		esc_attr( $row_class ),
		do_shortcode( $content )
	);

	return $markup;
}

/**
 * Generate inline style for HB Row.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Row inline CSS.
 */
function mkhb_row_style( $options ) {
	$row_layout = '';
	$style = '';

	// Row ID.
	$row_id = $options['id'];

	// Row BG styles.
	$row_bg = mkhb_row_background( $options );
	if ( ! empty( $row_bg ) ) {
		$style .= "#$row_id { $row_bg }";
	}

	// Row Container Width.
	$fixed_container = '';
	if ( 'fixed' === $options['width'] ) {
		$fixed_container = '.mkhb-row__container';

		// Because we don't have any default value for fixed. We use mk_options grid_width.
		global $mk_options;
		$grid_width = 1140;
		if ( ! empty( $mk_options['grid_width'] ) ) {
			$grid_width = intval( $mk_options['grid_width'] ) + 60;
		}

		$style .= "#$row_id > .mkhb-row__container { max-width: {$grid_width}px; }";
	}

	// Row Margin and Padding Style.
	$row_layout .= mkhb_row_layout( $options );

	// Row Border Style.
	$row_layout .= mkhb_row_border( $options );

	// Row Container Layout.
	if ( ! empty( $row_layout ) ) {
		$style .= "#$row_id {$fixed_container} { $row_layout }";
	}

	return $style;
}

/**
 * Generate internal style for HB Row Background.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Row internal CSS background.
 */
function mkhb_row_background( $options ) {
	$style = '';

	// Row Background Color.
	if ( ! empty( $options['background-color'] ) && mkhb_row_sequence( $options ) ) {
		$style .= "background-color: {$options['background-color']};";
	}

	// Row Background Image.
	if ( ! empty( $options['background-image'] ) ) {
		$bg_images = explode( ';', $options['background-image'] );
		foreach ( $bg_images as $bg_image ) {
			if ( ! empty( $bg_image ) ) {
				$style .= "background-image: {$bg_image};";
			}
		}
	}

	// Row Background Repeat.
	if ( ! empty( $options['background-repeat'] ) ) {
		$style .= "background-repeat: {$options['background-repeat']};";
	}

	// Row Background Position.
	if ( ! empty( $options['background-position'] ) ) {
		$style .= "background-position: {$options['background-position']};";
	}

	// Row Background Attachment.
	if ( ! empty( $options['background-attachment'] ) ) {
		$style .= "background-attachment: {$options['background-attachment']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Row Border.
 *
 * @since 6.0.0
 * @since 6.0.3 Update border CSS property.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Row internal CSS border.
 */
function mkhb_row_border( $options ) {
	$style = '';

	// Border Width.
	if ( ! empty( $options['border-width'] ) ) {
		$style .= "border-width: {$options['border-width']};";
	}

	// Border Color.
	if ( ! empty( $options['border-color'] ) ) {
		$style .= "border-color: {$options['border-color']};";
	}

	return $style;
}

/**
 * Get current row sequence status.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return boolean         Current sequence status.
 */
function mkhb_row_sequence( $options ) {
	// Row sequence status.
	$sequence = 0;
	if ( ! empty( $options['sequence'] ) ) {
		$sequence_raw = explode( ',', $options['sequence'] );
		$sequence = absint( end( $sequence_raw ) );
	}

	return $sequence;
}

/**
 * Generate internal style for HB Row Layout.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Row internal CSS margin and padding.
 */
function mkhb_row_layout( $options ) {
	$style = '';

	// Row Padding.
	if ( ! empty( $options['padding'] ) ) {
		$style .= "padding: {$options['padding']};";
	}

	// Row Margin.
	if ( ! empty( $options['margin'] ) ) {
		$style .= "margin: {$options['margin']};";

		if ( 'fixed' === $options['width'] ) {
			$style .= 'margin-left: auto;';
			$style .= 'margin-right: auto;';
		}
	}

	return $style;
}
