<?php
/**
 * Header Builder: mkhb_textbox shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Textbox element shortcode.
 *
 * @since 6.0.0
 *
 * @param  array  $atts All parameter will be used in the shortcode.
 * @param  string $content The enclosed content.
 * @return string $markup Rendered HTML.
 */
function mkhb_textbox_shortcode( $atts, $content = null ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-textbox-1',
			'alignment' => '',
			'color' => '',
			'display' => '',
			'font-family' => 'Open Sans',
			'font-type' => 'google',
			'font-size' => '',
			'font-style' => '',
			'font-weight' => '',
			'hover-color' => '',
			'href' => '',
			'line-height' => '',
			'margin' => '',
			'padding' => '',
			'target' => '_blank',
			'type' => 'plain',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if textbox is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Social inline style.
	$style = mkhb_textbox_style( $options );

	// Set Social markup.
	$markup = mkhb_textbox_markup( $options, $content );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue inline style.
	$hooks::concat_hook( 'styles', $style );

	// Enqueue current font.
	$data = array(
		'font-family' => $options['font-family'],
		'font-type' => $options['font-type'],
		'font-weight' => $options['font-weight'],
	);
	$hooks::set_hook( 'fonts', $data );

	return $markup;
}
add_shortcode( 'mkhb_textbox', 'mkhb_textbox_shortcode' );

/**
 * Generate the element's markup for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Rename function name.
 *
 * @param  array  $options All options will be used in the shortcode.
 * @param  string $content The enclosed content.
 * @return string $markup Element HTML code.
 */
function mkhb_textbox_markup( $options, $content ) {
	$markup  = '';

	// Textbox input_text.
	$prefix = array(
		'plain' => '',
		'phone' => 'tel:',
		'email' => 'mailto:',
	);

	$link = '';
	$target = '';
	$smooth_scroll = '';
	if ( ! empty( $options['href'] ) ) {
		// Textbox input type.
		$text_type = ( ! empty( $options['type'] ) ) ? $options['type'] : 'plain';

		// Textbox URL target.
		if ( 'plain' === $text_type && ! empty( $options['target'] ) ) {
			$target = 'target = "' . $options['target'] . '"';
		}

		// Textbox class for smooth scroll, only on #link.
		if ( null !== wp_parse_url( $options['href'], PHP_URL_FRAGMENT ) ) {
			$smooth_scroll = 'js-smooth-scroll';
		}

		$url = $prefix[ $text_type ] . $options['href'];
		$link = 'href = "' . esc_url( $url ) . '"';
	}

	// Textbox font family.
	$font_family = '';
	if ( ! empty( $options['font-family'] ) ) {
		$font_family = 'font-family: ' . $options['font-family'] . ';';
	}

	// Textbox additional class.
	$textbox_class = mkhb_shortcode_display_class( $options );

	// Textbox attributes.
	// @todo Temporary Solution - Data Attribute for inline container.
	$data_attr = mkhb_shortcode_display_attr( $options );

	/*
	 * Textbox text. According to http://www.json.org/, we cannot have the
	 * \n "character" in JSON data. We can only thus store new line
	 * characters as, well the \n "string", which makes it indistinguishable
	 * from any \n "string" directly entered by the user. For this reason,
	 * we need to encode our strings in base64 to circumvent this problem.
	 */
	$text = htmlentities( rawurldecode( base64_decode( strip_tags( $content ), true ) ), ENT_COMPAT, 'UTF-8' );

	$markup = sprintf( '
		<div id="%s" class="mkhb-textbox-el %s" %s>
			<a %s class="mkhb-textbox-el__link %s" %s>%s</a>
		</div>',
		esc_attr( $options['id'] ),
		esc_attr( $textbox_class ),
		$data_attr,
		$link,
		$smooth_scroll,
		$target,
		// We do not support HTML in the text area except for the break tag.
		// Supporting HTML here implies we need to render the CSS attached
		// to any "class" attribute. This means both CSS included in
		// Jupiter, any any custom CSS you may have in Theme Options. A of
		// this writing that is out of scope.
		nl2br( preg_replace( '/&lt;br\W*?\&gt;/i', '<br>', esc_html( $text ) ) )
	);

	return $markup;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Print social style only if it's needed. Rename function name.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $style Element CSS code.
 */
function mkhb_textbox_style( $options ) {
	$textbox_style = '';
	$style = '';

	// Textbox ID.
	$textbox_id = $options['id'];

	// Textbox Display.
	if ( ! empty( $options['display'] ) ) {
		if ( 'inline' === $options['display'] ) {
			$textbox_style .= 'display: inline-block; vertical-align: top;';
		}
	}
	// Textbox Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$textbox_style .= "text-align: {$options['alignment']};";
	}

	// Textbox Padding.
	if ( ! empty( $options['padding'] ) ) {
		$textbox_style .= "padding: {$options['padding']};";
	}

	// Textbox Margin.
	if ( ! empty( $options['margin'] ) ) {
		$textbox_style .= "margin: {$options['margin']};";
	}

	// If textbox style not empty.
	if ( ! empty( $textbox_style ) ) {
		$style .= "#{$textbox_id} { $textbox_style }";
	}

	// Textbox Link style.
	$style .= mkhb_textbox_link_style( $options );

	return $style;
}

/**
 * Generate the element's style for textbox link on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Print social style only if it's needed. Rename function name.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $style Element CSS code.
 */
function mkhb_textbox_link_style( $options ) {
	$link_style = '';
	$style = '';

	// Textbox ID.
	$textbox_id = $options['id'];

	// Textbox Color.
	if ( ! empty( $options['color'] ) ) {
		$link_style .= "color: {$options['color']};";
	}

	// Textbox Font Family.
	if ( ! empty( $options['font-family'] ) && 'Open Sans' !== $options['font-family'] ) {
		$link_style .= "font-family: {$options['font-family']};";
	}

	// Textbox Font Size.
	if ( ! empty( $options['font-size'] ) ) {
		$link_style .= "font-size: {$options['font-size']};";
	}

	// Textbox Font Style.
	if ( ! empty( $options['font-style'] ) ) {
		$link_style .= "font-style: {$options['font-style']};";
	}

	// Textbox Font Weight.
	if ( ! empty( $options['font_weight'] ) ) {
		$link_style .= "font-weight: {$options['font-weight']};";
	}

	// Textbox Line Height.
	if ( ! empty( $options['line-height'] ) ) {
		$link_style .= "line-height: {$options['line-height']};";
	}

	// If textbox style not empty.
	if ( ! empty( $link_style ) ) {
		$style .= "#{$textbox_id} .mkhb-textbox-el__link { $link_style }";
	}

	// Textbox Hover Link.
	$style .= mkhb_textbox_hover_link_style( $options );

	return $style;
}

/**
 * Generate the textbox link hover state.
 *
 * There are 2 cases here:
 * 1. If textbox link hover styles are overriden, return the overriden hover style.
 * 2. If textbox link styles are overriden, return the default hover style. It's
 *    used to fix hover issue on the link.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $style Element CSS code.
 */
function mkhb_textbox_hover_link_style( $options ) {
	$style = '';

	// Textbox ID.
	$textbox_id = $options['id'];

	// 1.a Textbox color hover.
	// 2.a If textbox color is overriden, set default color for hover state.
	if ( ! empty( $options['hover-color'] ) ) {
		$style .= "#{$textbox_id} .mkhb-textbox-el__link[href]:hover { color: {$options['hover-color']}; }";
	} elseif ( ! empty( $options['color'] ) ) {
		$style .= "#{$textbox_id} .mkhb-textbox-el__link[href]:hover { color: #444444; }";
	}

	return $style;
}
