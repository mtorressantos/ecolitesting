<?php
/**
 * Header Builder: mkhb_column shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Column element shortcode.
 *
 * @since 6.0.0
 * @since 6.0.3 Print column style only if it's needed. Separate style and markup
 *              logic to other functions.
 *
 * @param  array  $atts    All parameter will be used in the shortcode.
 * @param  string $content Content inside Column shortcode.
 * @return string          Rendered HTML.
 */
function mkhb_column_shortcode( $atts, $content ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-col-1',
			'vertical-alignment' => '',
			'margin' => '',
			'padding' => '',
			'border-width' => '',
			'border-color' => '',
			'background-color' => '',
			'background-image' => '',
			'background-repeat' => '',
			'background-position' => '',
			'background-attachment' => '',
			'width' => '12',
			'device' => 'desktop',
			'sequence' => '0,2,0',
		),
		$atts
	);

	// If the column widht is 0, don't render it.
	if ( '0' === $options['width'] ) {
		return '';
	}

	// Set Column internal style.
	$style = mkhb_column_style( $options );

	// Set Column markup.
	$markup = mkhb_column_markup( $options, $content );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	return $markup;
}
add_shortcode( 'mkhb_col', 'mkhb_column_shortcode' );

/**
 * Generate markup for HB Column.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $content Column content e.g: shortcodes.
 * @return string         Column markup.
 */
function mkhb_column_markup( $options, $content ) {
	// Column ID.
	$column_id = $options['id'];

	// Column Vertical Alignment class.
	$column_class = '';
	if ( ! empty( $options['vertical-alignment'] ) ) {
		$column_class .= 'mkhb-col--align-' . $options['vertical-alignment'];
	}

	// Column BG Image class.
	if ( ! empty( $options['background-image'] ) ) {
		$column_class .= ' mkhb-col--bg-image';
	}

	// Devices class name prefix.
	$prefix = array(
		'mobile' => 'xs',
		'tablet' => 'sm',
		'desktop' => 'md',
	);

	// Suffix class for container display.
	$suffix_class = '';
	if ( strpos( $content, 'display="inline"' ) || strpos( $content, "display='inline'" ) ) {
		$suffix_class = '-inline';
	}

	// If the content is not empty, run do_shortcode.
	$content_rendered = '';
	if ( ! empty( $content ) ) {
		$content_rendered = do_shortcode( $content );
	}

	$markup = sprintf( '
		<div id="%s" class="mkhb-col mkhb-col-%s %s">
			<div class="mkhb-col__container%s">%s</div>
		</div>',
		esc_attr( $column_id ),
		esc_attr( $prefix[ $options['device'] ] . '-' . $options['width'] ),
		esc_attr( $column_class ),
		esc_attr( $suffix_class ),
		$content_rendered
	);

	return $markup;
}

/**
 * Generate internal style for HB Column.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Column internal CSS.
 */
function mkhb_column_style( $options ) {
	$column_style = '';
	$style = '';

	// Column Margin and Padding Style.
	$column_style .= mkhb_column_layout( $options );

	// Column Border Style.
	$column_style .= mkhb_column_border( $options );

	// Column Background Style.
	$column_style .= mkhb_column_background( $options );

	// Column Vertical Alignment.
	if ( ! empty( $options['vertical-alignment'] ) ) {
		$column_style .= "vertical-align: {$options['vertical-alignment']};";
	}

	// Column ID.
	$column_id = $options['id'];

	// Set column style.
	if ( ! empty( $column_style ) ) {
		$style .= "#$column_id { $column_style }";
	}

	// Column Width Offset.
	$style .= mkhb_column_offset( $options );

	return $style;
}

/**
 * Generate internal style for HB Column Background.
 *
 * @since 6.0.0
 * @since 6.0.3 Set default bg color if it's active.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Column internal CSS background.
 */
function mkhb_column_background( $options ) {
	$style = '';

	// Column Background Color.
	$bg_color = '#ffffff';
	if ( ! empty( $options['background-color'] ) ) {
		$bg_color = $options['background-color'];
	}

	// Column Background Color Status.
	if ( mkhb_column_sequence( $options ) ) {
		$style .= "background-color: {$bg_color};";
	}

	// Column Background Image.
	if ( ! empty( $options['background-image'] ) ) {
		$bg_images = explode( ';', $options['background-image'] );
		foreach ( $bg_images as $bg_image ) {
			if ( ! empty( $bg_image ) ) {
				$style .= "background-image: {$bg_image};";
			}
		}
	}

	// Column Background Repeat.
	if ( ! empty( $options['background-repeat'] ) ) {
		$style .= "background-repeat: {$options['background-repeat']};";
	}

	// Column Background Position.
	if ( ! empty( $options['background-position'] ) ) {
		$style .= "background-position: {$options['background-position']};";
	}

	// Column Background Attachment.
	if ( ! empty( $options['background-attachment'] ) ) {
		$style .= "background-attachment: {$options['background-attachment']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Column Layout.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Column internal CSS margin and padding.
 */
function mkhb_column_layout( $options ) {
	$style = '';

	// Column Padding.
	if ( ! empty( $options['padding'] ) ) {
		$style .= "padding: {$options['padding']};";
	}

	// Column Margin.
	if ( ! empty( $options['margin'] ) ) {
		$style .= "margin: {$options['margin']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Column Border.
 *
 * @since 6.0.0
 * @since 6.0.3 Update border CSS property.
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Column internal CSS border.
 */
function mkhb_column_border( $options ) {
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
 * Generate internal style for HB Column Width Offset.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Column internal CSS width offset.
 */
function mkhb_column_offset( $options ) {
	$style = '';

	if ( empty( $options['margin'] ) ) {
		return $style;
	}

	// Column ID.
	$column_id = $options['id'];

	// Column Margin Raw.
	$column_margins = explode( ' ', $options['margin'] );

	// Column Offset.
	$column_offset = intval( $column_margins[1] ) + intval( $column_margins[3] );

	if ( 0 < $column_offset ) {
		$column_offset .= 'px';
		$style = "
			#{$column_id}div[class^=mkhb-col-xs-],
			#{$column_id}.mkhb-col-sm-12,
			#{$column_id}.mkhb-col-md-12 {
				width: calc(100% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-11,
			#{$column_id}.mkhb-col-md-11 {
				width: calc(91.66666667% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-10,
			#{$column_id}.mkhb-col-md-10 {
				width: calc(83.33333333% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-9,
			#{$column_id}.mkhb-col-md-9 {
				width: calc(75% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-8,
			#{$column_id}.mkhb-col-md-8 {
				width: calc(66.66666667% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-7,
			#{$column_id}.mkhb-col-md-7 {
				width: calc(58.33333333% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-6,
			#{$column_id}.mkhb-col-md-6 {
				width: calc(50% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-5,
			#{$column_id}.mkhb-col-md-5 {
				width: calc(41.66666667% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-4,
			#{$column_id}.mkhb-col-md-4 {
				width: calc(33.33333333% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-3,
			#{$column_id}.mkhb-col-md-3 {
			 	width: calc(25% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-2,
			#{$column_id}.mkhb-col-md-2 {
			 	width: calc(16.66666667% - {$column_offset});
			}
			#{$column_id}.mkhb-col-sm-1,
			#{$column_id}.mkhb-col-md-1 {
				width: calc(8.33333333% - {$column_offset});
			}
		";
	} // End if().

	return $style;
}

/**
 * Get current column sequence status.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return boolean         Current sequence status.
 */
function mkhb_column_sequence( $options ) {
	// Column sequence status.
	$sequence = 0;
	if ( ! empty( $options['sequence'] ) ) {
		$sequence_raw = explode( ',', $options['sequence'] );
		$sequence = absint( end( $sequence_raw ) );
	}

	return $sequence;
}

