<?php
/**
 * Header Builder: mkhb_shopping_icon shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Shopping Icon element shortcode.
 *
 * @since 6.0.0
 *
 * @param  array $atts All parameter will be used in the shortcode.
 * @return string      Rendered HTML.
 */
function mkhb_shopping_icon_shortcode( $atts ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-shopping-icon-1',
			'alignment' => '',
			'color' => '',
			'display' => '',
			'hover-color' => '',
			'margin' => '',
			'padding' => '',
			'size' => '16px',
			'icon' => '1',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if shopping icon is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Shopping Icon internal style.
	$style = mkhb_shopping_icon_style( $options );

	// Set Shopping Icon markup.
	$markup = mkhb_shopping_icon_markup( $options );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	// Collect Shopping Cart Hooks.
	$data = array(
		'id' => $options['id'],
		'device' => $options['device'],
		'icon' => $options['icon'],
	);

	$hooks::set_hook( 'shopping-icon', $data );

	return $markup;
}
add_shortcode( 'mkhb_shopping_icon', 'mkhb_shopping_icon_shortcode' );

/**
 * Generate the element's markup for use on the front-end.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return array {
 *      HTML and CSS for the element, based on all its given properties and settings.
 *
 *      @type string $markup Element HTML code.
 * }
 */
function mkhb_shopping_icon_markup( $options ) {
	$markup  = '';

	// Render this cart only when WooCommerce is activated.
	if ( class_exists( 'WooCommerce' ) && ! empty( WC()->cart ) && 'desktop' === $options['device'] ) {
		// Collect the WooCommerce Cart widget.
		ob_start();
		the_widget( 'WC_Widget_Cart' );
		$cart_widget = ob_get_clean();

		$cart_url = esc_url( wc_get_cart_url() );
		$cart_icon = mkhb_shopping_icon_get_svg_icon( $options['icon'], $options['size'] );
		$cart_count = WC()->cart->cart_contents_count;

		$shopping_icon_id = $options['id'];

		// Shopping Icon additional class.
		$shopping_icon_class = mkhb_shortcode_display_class( $options );

		// Shopping Icon attributes.
		// @todo Temporary Solution - Data Attribute for inline container.
		$data_attr = mkhb_shortcode_display_attr( $options );

		$markup = sprintf(
			'<div id="%s" class="mkhb-shop-cart-el-container %s" %s>
				<div class="mkhb-shop-cart-el">
					<a class="mkhb-shop-cart-el__link" href="%s">
						%s
						<span class="mkhb-shop-cart-el__count">%s</span>
					</a>
					<div class="mkhb-shop-cart-el__box mk-shopping-cart-box">
						%s
						<div class="clearboth"></div>
					</div>
				</div>
			</div>',
			esc_attr( $shopping_icon_id ),
			esc_attr( $shopping_icon_class ),
			$data_attr,
			$cart_url,
			$cart_icon,
			$cart_count,
			$cart_widget
		);
	} // End if().

	return $markup;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * There are 2 cases here:
 * 1. If shop icon link hover styles are overriden, return the overriden hover style.
 * 2. If shop icon link styles are overriden, return the default hover style. It's
 *    used to fix hover issue on the link.
 *
 * @since 6.0.0
 * @since 6.0.3 Print social style only if it's needed.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return array {
 *      HTML and CSS for the element, based on all its given properties and settings.
 *
 *      @type string $style Element CSS code.
 * }
 */
function mkhb_shopping_icon_style( $options ) {
	$shopping_icon_style = '';
	$style = '';

	// Shopping Icon ID.
	$shopping_icon_id = $options['id'];

	// Shopping Icon Display.
	if ( ! empty( $options['display'] ) ) {
		if ( 'inline' === $options['display'] ) {
			$shopping_icon_style .= 'display: inline-block; vertical-align: top;';
		}
	}

	// Shopping Icon Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$shopping_icon_style .= "text-align: {$options['alignment']};";
	}

	// If Shopping Icon container style not empty, render.
	if ( ! empty( $shopping_icon_style ) ) {
		$style .= "#{$shopping_icon_id}.mkhb-shop-cart-el-container { $shopping_icon_style }";
	}

	// Shopping Icon margin and padding.
	$shopping_icon_layout = mkhb_shopping_icon_layout( $options );
	if ( ! empty( $shopping_icon_layout ) ) {
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el { $shopping_icon_layout }";
	}

	// Shopping Icon Link color.
	if ( ! empty( $options['color'] ) ) {
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el__link {color: {$options['color']};}";
	}

	// 1.a Shopping Icon Link color.
	// 2.a If icon link color is overriden, set default color for hover state.
	if ( ! empty( $options['hover-color'] ) ) {
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el:hover .mkhb-shop-cart-el__link {color: {$options['hover-color']};}";
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el:hover .mkhb-shop-cart-el__link svg path {fill: {$options['hover-color']};}";
	} elseif ( ! empty( $options['color'] ) ) {
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el:hover .mkhb-shop-cart-el__link {color: #444444;}";
		$style .= "#{$shopping_icon_id} .mkhb-shop-cart-el:hover .mkhb-shop-cart-el__link svg path {fill: #444444;}";
	}

	return $style;
}

/**
 * Generate internal style for HB Shopping Icon Layout.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Shopping Icon internal CSS margin and padding.
 */
function mkhb_shopping_icon_layout( $options ) {
	$style = '';

	// Shopping Icon Padding.
	if ( ! empty( $options['padding'] ) ) {
		$style .= "padding: {$options['padding']};";
	}

	// Shopping Icon Margin.
	if ( ! empty( $options['margin'] ) ) {
		$style .= "margin: {$options['margin']};";
	}

	return $style;
}

/**
 * Get SVG icon based on key and set the icon width and height size in pixel.
 *
 * @since 6.0.0
 *
 * @param string $icon_name Icon name.
 * @param string $icon_size Icon size.
 * @return string           Icon SVG.
 */
function mkhb_shopping_icon_get_svg_icon( $icon_name, $icon_size ) {
	// If the icon type is empty or not array, return null and don't render the element.
	if ( empty( $icon_name ) ) {
		return '';
	}

	$icon_class = mkhb_shopping_icon_list( $icon_name );

	/*
	 * Mk_SVG_Icons is a class from Jupiter package. HB - Icon will use it to load the SVG
	 * icon based on the class name. Make sure this class is exist.
	 */
	if ( ! class_exists( 'Mk_SVG_Icons' ) ) {
		require_once THEME_HELPERS . '/svg-icons.php';
	}

	$mk_svg = new Mk_SVG_Icons();
	$icon = $mk_svg::get_svg_icon_by_class_name( false, $icon_class, (int) $icon_size );

	return $icon;
}

/**
 * Return icon class name.
 *
 * @since 6.0.0
 *
 * @param  string $key Icon key number.
 * @return string      Icon class name.
 */
function mkhb_shopping_icon_list( $key ) {
	$icons = array(
		'1' => 'mk-moon-cart-2',
		'2' => 'mk-moon-cart-3',
		'3' => 'mk-moon-cart-7',
		'4' => 'mk-moon-cart',
		'5' => 'mk-moon-cart-6',
		'6' => 'mk-li-cart',
		'7' => 'mk-icon-shopping-cart',
		'8' => 'mk-moon-cart-4',
	);

	if ( ! empty( $key ) || ! empty( $icons[ $key ] ) ) {
		return $icons[ $key ];
	}

	return $icons['1'];
}

/**
 * Generate the responsive element's markup for mobile and tablet devices.
 *
 * @since 6.0.0
 *
 * @return boolean False if option is empty.
 */
function mkhb_shopping_icon_add_to_cart_responsive() {
	// Fetch hooks data.
	$instance = mkhb_hooks();
	$options = $instance::get_hook( 'shopping-icon', array() );

	// If shopping icon hook is empty, stop.
	if ( empty( $options ) ) {
		return false;
	}

	foreach ( $options as $option ) {
		// Render this cart only when WooCommerce is activated.
		/**
		 * ATTENTION:
		 * Shopping cart for responsive state only works if:
		 * - WooCommerce is active.
		 * - The device is not Dekstop.
		 * - LATER: The workspace is not Sticky.
		 */
		if ( class_exists( 'WooCommerce' ) && ! empty( WC()->cart ) && 'desktop' !== $option['device'] ) {
			$cart_url = esc_url( wc_get_cart_url() );
			$cart_icon = mkhb_shopping_icon_get_svg_icon( $option['icon'], 16 );
			$cart_count = WC()->cart->cart_contents_count;

			$markup = sprintf(
				'<div id="%s" class="mkhb-shop-cart-el-res mkhb-el-%s">
	                <a class="mkhb-shop-cart-el-res__link" href="%s">
			            %s <span class="mkhb-shop-cart-el-res__count">%s</span>
			        </a>
				</div>',
				esc_attr( $option['id'] ),
				esc_attr( $option['device'] ),
				$cart_url,
				$cart_icon,
				$cart_count
			);

			echo wp_kses( $markup, array(
				'div' => array(
					'id' => array(),
					'class' => array(),
				),
				'a' => array(
					'class' => array(),
					'href' => array(),
				),
				'svg' => array(
					'xmlns' => array(),
					'style' => array(),
					'viewbox' => array(),
				),
				'path' => array(
					'd' => array(),
					'transform' => array(),
				),
				'span' => array(
					'class' => array(),
				),
			) );
		} // End if().
	} // End foreach().
}

/**
 * Set count value on shopping cart.
 *
 * @since 6.0.0
 *
 * @param array $fragments WooCommerce shooping cart HTML fragments.
 */
function mkhb_shopping_icon_add_to_cart_fragments( $fragments ) {
	// Desktop device.
	ob_start();
	$cart_link = sprintf(
		'<span class="mkhb-shop-cart-el__count">%s</span>',
		WC()->cart->cart_contents_count
	);
	echo wp_kses( $cart_link, array(
		'span' => array(
			'class' => array(),
		),
	) );
	$fragments['span.mkhb-shop-cart-el__count'] = ob_get_clean();

	// Tablet & Mobile devices.
	ob_start();
	$cart_link2 = sprintf(
		'<span class="mkhb-shop-cart-el-res__count">%s</span>',
		WC()->cart->cart_contents_count
	);
	echo wp_kses( $cart_link2, array(
		'span' => array(
			'class' => array(),
		),
	) );
	$fragments['span.mkhb-shop-cart-el-res__count'] = ob_get_clean();

	return $fragments;
}

/**
 * Add new class 'mkhb-shopping-icon' to body.
 *
 * @since 6.0.0
 *
 * @param  array $classes Current body class list.
 * @return array $classes Latest body class list with additional mkhb-shopping-icon class.
 */
function mkhb_shopping_icon_body_class( $classes ) {
	// Fetch hooks data.
	$instance = mkhb_hooks();
	$options = $instance::get_hook( 'shopping-icon', array() );

	$classes[] = 'mkhb-shopping-icon';

	if ( ! empty( $options ) ) {
		foreach ( $options as $option ) {
			if ( ! isset( $option['device'] ) ) {
				continue;
			}

			if ( empty( $option['device'] ) || 'desktop' === $option['device'] ) {
				continue;
			}

			$classes[] = 'mkhb-shopping-icon--' . $option['device'];
		}
	}

	return $classes;
}
