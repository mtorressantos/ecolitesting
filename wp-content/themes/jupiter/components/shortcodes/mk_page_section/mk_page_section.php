<?php

global $mk_options;
$post_id = global_get_post_id();

$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$data_config[] = ( 'true' == $full_height ) ? 'data-mk-component="FullHeight" data-fullHeight-config=\'{"min": ' . $min_height . '}\'' : '';
$data_config[] = ( 'social' == $video_source ) ? 'data-source="' . $stream_host_website . '"' : '' ;
$data_config[] = ( 'social' == $video_source ) ? 'data-sound="' . $stream_sound . '"' : '' ;
$data_config[] = 'data-intro-effect="' . $intro_effect . '"';

$classes[] = 'mk-page-section';
$classes[] = $video_source . '-hosted';
$classes[] = ( 'false' != $intro_effect ) ? 'intro-true' : '';
$classes[] = ( 'false' !== $adaptive_height ) ? 'mk-adaptive-height' : '';
$classes[] = $layout_structure . '_layout';
if ( 'full' !== $layout_structure ) {
	$classes[] = 'half_' . ( 'true' === $full_width ? 'fluid' : 'boxed' );
}
$classes[] = 'full-width-' . $id;
$classes[] = 'js-el';
$classes[] = 'js-master-row';
$classes[] = $visibility;
$classes[] = get_viewport_animation_class( $animation );
$classes[] = ( 'true' == $top_shadow) ? 'drop-top-shadow' : '';
$classes[] = $el_class;
if ( 'true' == $js_vertical_centered || 'true' == $full_height ) {
	$classes[] = 'center-y';
}

$stretch_content = 'true';
$global_header_style = $mk_options['theme_header_style'];
$local_override = get_post_meta( $post_id, '_enable_local_backgrounds', true );
$local_header_style = get_post_meta( $post_id, 'theme_header_style', true );
$local_header_trans = get_post_meta( $post_id, '_transparent_header', true );
$local_layout = get_post_meta( $post_id, '_layout', true );

if ( '4' === $global_header_style && 'false' === $local_override ) {
	$stretch_content = 'false';
}

if ( 'true' == $local_override && '4' == $local_header_style ) {
	$stretch_content = 'false';
}

if ( 'true' == $local_override && '4' == $local_header_style && 'true' == $local_header_trans ) {
	$stretch_content = 'true';
}

if ( 'full-width' !== $local_layout ) {
	$wrapper_attributes = 'data-mk-full-width="true" data-mk-full-width-init="true" data-mk-stretch-content="' . esc_attr( $stretch_content ) . '"';
}
?>

<div class="mk-page-section-wrapper" <?php echo $wrapper_attributes; ?>>
	<div <?php echo $section_id; ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo implode( ' ', $data_config ); ?>>

			<?php
			if ( 'true' == $has_top_shape_divider ) {
				echo mk_get_shortcode_view( 'mk_page_section', 'components/shape-divider', true, $top_shape_atts );
			}
			?>

			<div class="mk-page-section-inner">
				<?php echo mk_get_shortcode_view( 'mk_page_section', 'components/overlay', true, $overlay_atts ); ?>

				<?php
				if ( 'full' == $layout_structure ) {
					echo mk_get_shortcode_view( 'mk_page_section', 'components/video-background', true, $video_atts );
				}
				?>

				<?php echo mk_get_shortcode_view( 'mk_page_section', 'components/background-layer', true, $layer_atts ); ?>
			</div>

			<?php echo mk_get_shortcode_view( 'mk_page_section', 'components/layout-structure__full', true, $layout_structure_full_atts ); ?>

			<?php echo mk_get_shortcode_view( 'mk_page_section', 'components/layout-structure__half', true, $layout_structure_half_atts ); ?>

			<?php
			echo mk_get_shortcode_view(
				'mk_page_section', 'components/skip-arrow', true, [
					'skip_arrow' => $skip_arrow,
					'skip_arrow_skin' => $skip_arrow_skin,
				]
			);
?>

			<?php
			if ( 'true' == $has_bottom_shape_divider ) {
				echo mk_get_shortcode_view( 'mk_page_section', 'components/shape-divider', true, $bottom_shape_atts );
			}
			?>

		<div class="clearboth"></div>
	</div>
</div>
<div class="vc_row-full-width vc_clearfix"></div>

<?php

if ( ! function_exists( 'is_gradient_stop_transparent' ) ) {
	function is_gradient_stop_transparent( $color ) {
		if ( strpos( $color, 'rgba' ) !== false ) {
			$var = $color;
			$var = str_replace( 'rgba(', '', $var );
			$var = str_replace( ')', '', $var );
			$var = explode( ',', $var );

			if ( floatval( $var[3] ) > 0.05 ) {
				return false;
			}

			return true;
		}

		return false;
	}
}


if ( 'false' != $bg_gradient ) {

	$el = '.full-width-' . $id . ' .mk-video-color-mask';
	$vertical = $horizontal = $left_top = $left_bottom = $radial = '';
	$gr_start = $video_color_mask;

	$gr_start = is_gradient_stop_transparent( $gr_start ) ? 'transparent' : $gr_start;
	$gr_end   = is_gradient_stop_transparent( $gr_end ) ? 'transparent' : $gr_end;

	if ( 'vertical' == $bg_gradient ) {
		$vertical = '
            background: ' . $gr_start . '; /* Old browsers */
            background: -moz-linear-gradient(top,  ' . $gr_start . ' 0%, ' . $gr_end . ' 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . $gr_start . '), color-stop(100%,' . $gr_end . ')); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* IE10+ */
            background: linear-gradient(to bottom,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* W3C */
        ';
	}

	if ( 'horizontal' == $bg_gradient ) {
		$horizontal = '
            background: ' . $gr_start . '; /* Old browsers */
            background: -moz-linear-gradient(left,  ' . $gr_start . ' 0%, ' . $gr_end . ' 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, right top, color-stop(0%,' . $gr_start . '), color-stop(100%,' . $gr_end . ')); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(left,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(left,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(left,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* IE10+ */
            background: linear-gradient(to right,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* W3C */
        ';
	}

	if ( 'left_top' == $bg_gradient ) {
		$left_top = '
            background: ' . $gr_start . '; /* Old browsers */
            background: -moz-linear-gradient(-45deg,  ' . $gr_start . ' 0%, ' . $gr_end . ' 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,' . $gr_start . '), color-stop(100%,' . $gr_end . ')); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(-45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(-45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(-45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* IE10+ */
            background: linear-gradient(135deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* W3C */
        ';
	}

	if ( 'left_bottom' == $bg_gradient ) {
		$left_bottom = '
            background: ' . $gr_start . '; /* Old browsers */
            background: -moz-linear-gradient(45deg,  ' . $gr_start . ' 0%, ' . $gr_end . ' 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,' . $gr_start . '), color-stop(100%,' . $gr_end . ')); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* IE10+ */
            background: linear-gradient(45deg,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* W3C */
        ';
	}

	if ( 'radial' == $bg_gradient ) {
		$radial = '
            background: ' . $gr_start . '; /* Old browsers */
            background: -moz-radial-gradient(center, ellipse cover,  ' . $gr_start . ' 0%, ' . $gr_end . ' 100%); /* FF3.6+ */
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,' . $gr_start . '), color-stop(100%,' . $gr_end . ')); /* Chrome,Safari4+ */
            background: -webkit-radial-gradient(center, ellipse cover,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Chrome10+,Safari5.1+ */
            background: -o-radial-gradient(center, ellipse cover,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* Opera 12+ */
            background: -ms-radial-gradient(center, ellipse cover,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* IE10+ */
            background: radial-gradient(ellipse at center,  ' . $gr_start . ' 0%,' . $gr_end . ' 100%); /* W3C */
        ';
	}

	$opacity = 'opacity:' . $video_opacity . ';';

	Mk_Static_Files::addCSS(
		$el . '{'
		. $vertical
		. $horizontal
		. $left_top
		. $left_bottom
		. $radial
		. $opacity
		. '}', $id
	);
}



$bg_color_css = $bg_color ? ('background-color:' . $bg_color . ';') : '';
$bg_layer_bg_css = $blend_mode !== 'none' ? ('background-color:' . $bg_color . ';') : '';
$border_css = ( ! empty( $border_color )) ? 'border:1px solid ' . $border_color . ';border-left:none;border-right:none;' : '';


if ( 'fixed' == $attachment ) {
	$bgAttachment = 'position: fixed;';
}
if ( 'fixed' == $attachment && 'true' == $parallax ) {
	if ( $mk_options['smoothscroll'] == 'true' ) {
		$bgAttachment = 'position: absolute;';
	}
}

$padding_top = ('true' == $has_top_shape_divider ) ? (floatval( $padding_top ) + 100) : $padding_top;
$padding_bottom = ('true' == $has_bottom_shape_divider ) ? (floatval( $padding_bottom ) + 100) : $padding_bottom;

Mk_Static_Files::addCSS(
	"
    .full-width-{$id} {
        min-height:{$min_height}px;
        margin-bottom:{$margin_bottom}px;
        {$bg_color_css}
        {$border_css}
    }

    .full-width-{$id} .page-section-content {
        padding:{$padding_top}px 0 {$padding_bottom}px;
    }
    #background-layer--{$id} {
        {$bg_layer_bg_css};
        background-position:{$bg_position};
        background-repeat:{$bg_repeat};
        {$bgAttachment};
    }

    #background-layer--{$id} .mk-color-layer {
        {$bg_layer_bg_css};
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

", $id
);

if ( 'true' == $has_bottom_shape_divider ) {
	Mk_Static_Files::addCSS(
		"
    .full-width-{$id} .mk-skip-to-next {
        bottom: 100px;
    }
", $id
	);
}

if ( ! empty( $bg_color ) ) {
	Mk_Static_Files::addCSS(
		"
    .full-width-{$id} .mk-fancy-title.pattern-style span,
    .full-width-{$id} .mk-blog-view-all
    {
        background-color: {$bg_color} !important;
    }
", $id
	);
}


if ( ! empty( $adaptive_height ) && 'true' === $adaptive_height ) {

	$overflow_property   = ( empty( $overflow ) || 'true' === $overflow ) ? 'overflow: hidden' : '';
	$bg_stretch_property = ( empty( $bg_stretch ) || 'true' === $overflow ) ? 'background-size: contain' : '';
	$max_height_property = ! empty( $max_height ) ? 'max-height: ' . $max_height . 'px' : '';
	$min_height_property = ! empty( $min_height ) ? 'min-height: ' . $min_height . 'px' : '';

	Mk_Static_Files::addCSS(
		"
        #page-section-{$id}.mk-adaptive-height {
            $overflow_property
        }

        .mk-adaptive-height #background-layer--{$id} {
            $bg_stretch_property
        }

        #page-section-{$id} .mk-adaptive-image {
            opacity: 0;
            $max_height_property;
            $min_height_property
        }

        #page-section-{$id} .mk-adaptive-image.mk-background-stretch {
            width: 100%;
        }
    ", $id
	);
}
