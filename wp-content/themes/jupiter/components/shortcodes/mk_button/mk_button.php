<?php
global $mk_options;

$path = pathinfo( __FILE__ ) ['dirname'];

include( $path . '/config.php' );

$style_id = Mk_Static_Files::shortcode_id();

$id = ! empty( $id ) ? ( 'id="' . $id . '"' ) : '';
$url = ! empty( $url ) ? $url : '#';
$product_id = ! empty( $product_id ) ? $product_id : false;

if ( ! empty( $icon ) ) {
	if ( ( strpos( $icon, 'mk-' ) === false ) ) {
		$icon = 'mk-' . $icon;
	}

	$icon = Mk_SVG_Icons::get_svg_icon_by_class_name( false, $icon, 16 );

	// We need to add <i> to work with mk_button.css correctly.
	$icon = '<i class="mk-button--icon">' . $icon . '</i>';
}

if ( $product_id ) {
	$url = '?add-to-cart=' . $product_id;
}

// Element attributes.
$element_attributes[] = ( 'true' === $nofollow ) ? 'rel="nofollow"' : '';
$element_attributes[] = ! empty( $target ) ? 'target="' . $target . '"' : '';
$element_attributes[] = ( $product_id ) ? 'data-quantity="1" data-product_id="' . $product_id . '"' : '';

/**
 * Class Output
 * ==================================================================================*/
$class_container[] = ($fullwidth == 'true') ? 'width-full' : '';
$class_container[] = get_viewport_animation_class($animation);
$class_container[] = ($icon_anim != 'none') ? 'mk-button--anim-' . $icon_anim : '';
$class_container[] = ($align == 'center') ? 'block text-center' : 'inline '.$align.'';
$class_container[] = $el_class;

$class_element[] = 'mk-button--dimension-'.$dimension;
$class_element[] = 'mk-button--size-'.$size;
$class_element[] = 'mk-button--corner-'.$corner_style;

if ( $product_id ) {
	$class_element[] = 'add_to_cart_button ajax_add_to_cart';
}

$class_element_atomic[] = '';

if($size == 'small' || $size == 'medium') {
	$class_element_atomic[] = 'letter-spacing-1';
}else if($size == 'large' || $size == 'x-large' || $size == 'xx-large') {
	$class_element_atomic[] = 'letter-spacing-2';
}

if($fullwidth != 'true') {
	$class_element_atomic[] = 'inline';
}else {
	$class_element_atomic[] = 'block';
}

/**
 * Custom CSS Output
 * ==================================================================================*/
$app_styles = '
	#mk-button-'.$style_id.' {
		margin-bottom: '.$margin_bottom.'px;
		margin-top: '.$margin_top.'px;
		margin-right: '.$margin_right.'px;
	}
';

if($letter_spacing > 0) {
	$app_styles .= '
		#mk-button-'.$style_id.' .mk-button span{
			letter-spacing:'.$letter_spacing.'px;
			margin-right:-'.$letter_spacing.'px;
    		display: inline-block;
		}
	';
}

if($fullwidth != 'true'){
	$app_styles .= '
		#mk-button-'.$style_id.' .mk-button {
			display: inline-block;
			max-width: 100%;
		}
	';
	if ($button_custom_width > 0) {
		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button {
				width: '.$button_custom_width.'px;
			}
		';
	}
}
if ($dimension == 'three' || $dimension == 'two' || $dimension == 'flat') {
	$class_element[] = 'text-color-'.$text_color;
	$app_styles .= '
		#mk-button-'.$style_id.' .mk-button {
			background-color: '.$bg_color.';
		}
	';
	if ($dimension == 'three' || $dimension == 'two') {
		$color = ($text_color == 'light') ? 'color: #fff!important;' : 'color: #585858!important';
		$fill  = ($text_color == 'light') ? 'color: #fff!important;' : 'color: #585858!important';
		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button {
				background-color: '.$bg_color.';
				'.$color.'
			}
			#mk-button-'.$style_id.' .mk-button .mk-svg-icon {
				'.$fill.'
			}
			#mk-button-'.$style_id.' .mk-button:hover {
				background-color: '.hexDarker($bg_color, 7).';
			}
		';
	}
	if($dimension == 'three') {
		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button,
			#mk-button-'.$style_id.' .mk-button:active {
				box-shadow: 0px 3px 0px 0px '.hexDarker($bg_color, 20).';
				margin-bottom: 3px;
			}
		';
	}
	if($dimension == 'flat') {
		$hover_text_color = ($btn_hover_txt_color != '') ? 'color:'.$btn_hover_txt_color.' !important;' : '';
		$fill = ($btn_hover_txt_color != '') ? 'color:'.$btn_hover_txt_color.' !important;' : '';
		$hover_bg_color = ($btn_hover_bg != '') ? 'background-color:'.$btn_hover_bg.';' : '';
		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button:hover {
				'.$hover_text_color.'
				'.$hover_bg_color.'
			}
			#mk-button-'.$style_id.' .mk-button:hover .mk-svg-icon {
				'.$fill.'
			}
		';
	}
}
if($dimension == 'outline' || $dimension == 'double-outline' || $dimension == 'savvy'){
	$active_text_color = ($outline_active_text_color != '') ? $outline_active_text_color : $outline_active_color;
	$hover_bg_color = ($outline_hover_bg_color != '') ? $outline_hover_bg_color : $outline_active_color;

	if ($outline_skin != 'custom') {
		$class_element[] = 'skin-'.$outline_skin;
	}
	if ($dimension == 'outline' && $outline_skin == 'custom') {

		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button {
				border-color: '.$outline_active_color.';
				color: '.$active_text_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button .mk-svg-icon {
				fill: '.$active_text_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button:hover {
				background-color: '.$hover_bg_color.';
				color: '.$outline_hover_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button:hover .mk-svg-icon {
				fill: '.$outline_hover_color.'!important;
			}
		';
	}
	if ($dimension == 'savvy' && $outline_skin == 'custom') {


		$app_styles .= '
			#mk-button-'.$style_id.' .mk-button {
				border-color: '.$outline_active_color.';
				color: '.$active_text_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button .mk-svg-icon {
				fill: '.$active_text_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button::after {
				background-color: '.$hover_bg_color.';
			}
			#mk-button-'.$style_id.' .mk-button:hover {
				color: '.$outline_hover_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button:hover .mk-svg-icon {
				fill: '.$outline_hover_color.'!important;
			}
		';
	}
	if ($dimension == 'double-outline') {
		$app_styles .= '
			#mk-button-'.$style_id.' {
				padding: 4px 3px 2px;
			}
		';
	}
	if ($dimension == 'double-outline' && $outline_skin == 'custom') {

		if(empty($outline_active_text_color) || empty($outline_hover_bg_color)) {
			$app_styles .= '
			#mk-button-'.$style_id.' .mk-button {
				border-color: '.$outline_active_color.';
				color: '.$outline_hover_color.'!important;
				background-color: '.$outline_active_color.';
			}
			#mk-button-'.$style_id.' .mk-button .mk-svg-icon {
				fill: '.$outline_hover_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button .double-outline-inside {
				border-color: '.$outline_active_color.';
			}
			#mk-button-'.$style_id.' .mk-button:hover {
				background-color: '.$outline_hover_color.';
				color: '.$outline_active_color.'!important;
			}
			#mk-button-'.$style_id.' .mk-button:hover .mk-svg-icon {
				fill: '.$outline_active_color.'!important;
			}
		';
		}else {
			$app_styles .= '
				#mk-button-'.$style_id.' .mk-button {
					border-color: '.$outline_active_color.';
					color: '.$outline_active_text_color.'!important;
					background-color: '.$outline_active_color.';
				}
				#mk-button-'.$style_id.' .mk-button .mk-svg-icon {
					fill: '.$outline_active_text_color.'!important;
				}
				#mk-button-'.$style_id.' .mk-button .double-outline-inside {
					border-color: '.$outline_active_color.';
				}
				#mk-button-'.$style_id.' .mk-button:hover {
					background-color: '.$outline_hover_bg_color.';
					color: '.$outline_hover_color.'!important;
				}
				#mk-button-'.$style_id.' .mk-button:hover .mk-svg-icon {
					fill: '.$outline_active_color.'!important;
				}
			';
		}
	}
}

/**
 * Button attributes and content.
 */
$btn_attr    = '';
$btn_content = '';

// Concatinate all attributes.
$btn_attr .= $id;
$btn_attr .= 'href="' . $url . '"';
$btn_attr .= implode( ' ', $element_attributes );
$btn_attr .= 'class="mk-button js-smooth-scroll ' . implode( ' ', $class_element ) . ' _ relative text-center font-weight-700 no-backface ' . implode( ' ', $class_element_atomic ) . '"';

// Content for button.
$btn_content .= ( $dimension == 'double-outline' ) ? '<span class="double-outline-inside"></span>' : '';
$btn_content .= $icon;
$btn_content .= '<span class="mk-button--text">' . do_shortcode( strip_tags( $content ) ) . '</span>';
$btn_content .= ( $icon_anim == 'vertical' ) ? '<span class="is-vis-hidden">'. do_shortcode( strip_tags( $content ) ) . '</span>' : '';

Mk_Static_Files::addCSS($app_styles, $style_id);

?>

<div id="mk-button-<?php echo $style_id; ?>" class="mk-button-container _ relative <?php echo implode(' ', $class_container);?>">

	<?php if ( ! empty( $visibility ) ) : ?>
	<div class="<?php echo $visibility; ?>">
	<?php endif; ?>

		<a <?php echo $btn_attr ?>><?php echo $btn_content ?></a>

	<?php if ( ! empty( $visibility ) ) : ?>
	</div>
	<?php endif; ?>

</div>
