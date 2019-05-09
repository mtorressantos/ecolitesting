<?php

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$html = file_get_contents( $path . '/template.php' );
$html = phpQuery::newDocument( $html );

$id = Mk_Static_Files::shortcode_id();

// Main logic here
$custom_box = pq('.mk-custom-box');
$custom_box->addClass($visibility);
$custom_box->addClass($el_class);

$custom_box_container = $custom_box->find('.box-holder');

$custom_box->attr( 'id', 'box-'.$id );
if ( !empty( $bg_image ) ) {
	$custom_box->addClass('hover-effect-image');
}else {
	$custom_box->addClass('hover-effect-'.$background_hov_color_style);
}

if ($background_hov_color_style == 'image' && $bg_image_hov_effect != 'none') {
	$custom_box->addClass('image-effect-'.$bg_image_hov_effect);
}

if ( $animation != '' ) {
	$custom_box->addClass(get_viewport_animation_class($animation));
}

if( !empty($overlay_color) ){
	$custom_box_container->append('<div class="mk-custom-box--overlay"></div>');
}

$custom_box_container->prepend(wpb_js_remove_wpautop( $content ));

/**
 * Custom CSS Output
 * ==================================================================================*/
if($border_color_style == 'gradient_color') {
	$gradients_border = mk_gradient_option_parser($border_gradient_color_style, $border_gradient_color_angle);
	Mk_Static_Files::addCSS('
		#box-'.$id.'{
	 		padding: '.$border_width.'px;
	    	background: '.$bg_grandient_color_fallback.';
			background: -webkit-'.$gradients_border['type'].'-gradient('.$gradients_border['angle_1'].''.$border_grandient_color_from.' 0%, '.$border_grandient_color_to.' 100%);
			background: '.$gradients_border['type'].'-gradient('.$gradients_border['angle_2'].''.$border_grandient_color_from.' 0%, '.$border_grandient_color_to.' 100%);
	    }
	', $id);

}else if($border_color_style == 'single_color'){
	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder{
			border: '.$border_width.'px '.$border_style.' '.$border_color.';
		}
	', $id);
}

// Delete after first update this css for
// before v5 added background image
if ( !empty( $bg_image ) ) {
	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder::after,
		#box-'.$id.'.hover-effect-image.image-effect-blur .box-holder::before{
			content: "";
			background-image: url('.$bg_image.');
			background-position: '.$bg_position.';
			background-repeat: '.$bg_repeat.';
		}
	', $id);
}
if( !empty($overlay_color) ){
	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder .mk-custom-box--overlay {
			background-color: '.$overlay_color.';
		}
	', $id);
}


if($background_style == 'gradient_color') {
	$gradients = mk_gradient_option_parser($bg_gradient_color_style, $bg_gradient_color_angle);
	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder {
	    	background: '.$bg_grandient_color_fallback.';
			background: -webkit-'.$gradients['type'].'-gradient('.$gradients['angle_1'].''.$bg_grandient_color_from.' 0%, '.$bg_grandient_color_to.' 100%);
			background: '.$gradients['type'].'-gradient('.$gradients['angle_2'].''.$bg_grandient_color_from.' 0%, '.$bg_grandient_color_to.' 100%);
	    }
	', $id);
}else if ($background_style == 'image') {
	if ( !empty( $bg_image ) ) {
		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder::after,
			#box-'.$id.'.hover-effect-image.image-effect-blur .box-holder::before{
				content: "";
				background-image: url('.$bg_image.');
				background-position: '.$bg_position.';
				background-repeat: '.$bg_repeat.';
			}
		', $id);
	}

		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder{
				background-color: '.$bg_color.';
			}
		', $id);

	if($bg_stretch == 'true') {
		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder::after,
			#box-'.$id.'.hover-effect-image.image-effect-blur .box-holder::before{
				background-size: cover;
				-webkit-background-size: cover;
				-moz-background-size: cover;
			}
		', $id);
	}

}


if( $corner_radius > 0 ) {
	Mk_Static_Files::addCSS('
		#box-'.$id.',
		#box-'.$id.' .box-holder,
		#box-'.$id.' .mk-custom-box--overlay{
			border-radius: '.$corner_radius.'px;
		}
	', $id);
	if($border_color_style == 'single_color') {
		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder::after,
			#box-'.$id.' .box-holder::before,
			#box-'.$id.' .mk-custom-box--overlay{
				border-radius: '.($corner_radius - 4).'px;
			}
		', $id);
	}else if($border_color_style == 'gradient_color') {
		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder,
			#box-'.$id.' .box-holder::after,
			#box-'.$id.' .box-holder::before,
			#box-'.$id.' .mk-custom-box--overlay{
				border-radius: '.($corner_radius - 5).'px;
			}
		', $id);
	}else if($border_color_style == 'none') {
		Mk_Static_Files::addCSS('
			#box-'.$id.' .box-holder::after,
			#box-'.$id.' .box-holder::before,
			#box-'.$id.' .mk-custom-box--overlay{
				border-radius: '.$corner_radius.'px;
			}
		', $id);
	}
}

Mk_Static_Files::addCSS('
	#box-'.$id.'{
		margin-bottom: '.$margin_bottom.'px;
	}
	#box-'.$id.' .box-holder{
		min-height: '.$min_height.'px;
		padding: '.$padding_vertical.'px '.$padding_horizental.'px;
	}
', $id);

if ( 'image' === $background_hov_color_style && ! empty( $bg_hov_color ) ) {
	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder:hover{
			background-color: '.$bg_hov_color.';
		}
	', $id);
}else if($background_hov_color_style == 'gradient_color') {
	$gradients_hover = mk_gradient_option_parser($bg_gradient_hov_color_style, $bg_gradient_hov_color_angle);
	$main_bg_image = '';
	if ( ! empty( $bg_image ) ) {
		$main_bg_image = ', url(' . $bg_image . ')';
	}

	Mk_Static_Files::addCSS('
		#box-'.$id.' .box-holder:hover::after {
	    	background: '.$bg_grandient_hov_color_fallback.';
			background-image: -webkit-'.$gradients_hover['type'].'-gradient('.$gradients_hover['angle_1'].''.$bg_grandient_hov_color_from.' 0%, '.$bg_grandient_hov_color_to.' 100%)' . $main_bg_image . ';
			background-image: '.$gradients_hover['type'].'-gradient('.$gradients_hover['angle_2'].''.$bg_grandient_hov_color_from.' 0%, '.$bg_grandient_hov_color_to.' 100%)' . $main_bg_image . ';
	    }
	', $id);
}

if ( $elevation_effect == 'true' ) {

	$rest_values = [ 'y1' => 3, 'blur1' => 6, 'y2' => 6, 'blur2' => 12];
	$rise_values['y1'] = $rest_values['y1'] + $elevation_effect_distance;
	$rise_values['blur1'] = $rise_values['y1'] * 2;
	$rise_values['y2'] = $rise_values['y1'] * 2;
	$rise_values['blur2'] = $rise_values['y2'] * 2;

	$elevation_effect_rest = '0 ' . $rest_values['y1'] . 'px ' . $rest_values['blur1'] . 'px rgba(0,0,0,0.2), 0 ' . $rest_values['y2'] . 'px ' . $rest_values['blur2'] . 'px rgba(0,0,0,0.2)';
	$elevation_effect_rise = '0 ' . $rise_values['y1'] . 'px ' . $rise_values['blur1'] . 'px rgba(0,0,0,0.2), 0 ' . $rise_values['y2'] . 'px ' . $rise_values['blur2'] . 'px rgba(0,0,0,0.2)';

	Mk_Static_Files::addCSS('
		#box-'.$id.' {
			transition: transform 0.3s cubic-bezier(.25,.8,.25,1), box-shadow 0.3s cubic-bezier(.25,.8,.25,1);
			box-shadow: ' . $elevation_effect_rest . ';
		}
		#box-'.$id.':hover {
			transform: translate(0,-2px);
			box-shadow: ' . $elevation_effect_rise . ';
		}
	', $id);

}

if ( $drop_shadow == 'true' && $elevation_effect == 'false' ) {

	$drop_shadow_value = mk_shadow_angle_parser($drop_shadow_angle, $drop_shadow_distance ) . ' ' .  $drop_shadow_blur . 'px' . ' 0px ' . $drop_shadow_color;
	Mk_Static_Files::addCSS('
		#box-'.$id.' {
			box-shadow: ' . $drop_shadow_value . ';
		}
	', $id);

}

/**
 * Fix AM-2993
 *
 * Fancy title gradient color doesn't work in Custom Box. This issue is caused by
 * phpQuery that move tag <i> ... </i> to the top and unwrap the Fancy Title tag <p>
 * as the main content.
 *
 * Syntax Problem:              It should be:
 * <span>                       <span>
 *     <i></i>                      <i>
 *     <p>Testing Text</p>              <p>Testing Text</p>
 * </span>                          </i>
 *                              </span>
 *
 * To fix this, we need to:
 * - Ensure .mk-fancy-title + .color-gradient is exist
 * - If it's exist, remove empty tag i
 * - Repopulate tag i and reassign it to wrap the Fancy Title content
 *
 * ATTENTION: This code only runs in Custom Box when Fancy Title is exist and it uses
 *            gradient color.
 */
$fancy_title_exist = $html->find( '.mk-fancy-title.color-gradient' )->html();
if ( ! empty( $fancy_title_exist ) ) {
	$html->find( '.mk-fancy-title.color-gradient span i' )->remove();
	$html->find( '.mk-fancy-title.color-gradient span p' )->wrapAll( '<i></i>' );
}

print $html;
