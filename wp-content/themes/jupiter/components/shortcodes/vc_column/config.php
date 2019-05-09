<?php
extract(shortcode_atts(array(
    'el_class' => '',
    'border_color' => '',
    'bg_color' => '',
    'width' => '1/1',
    'blend_mode' => 'none',
    'offset' => '',
    'css' => '',
    'visibility' => ''
) , $atts));

$el_class = $this->getExtraClass($el_class);
$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);

$el_class .= ' wpb_column column_container ';

$border_color = ($border_color != '') ? ('border:1px solid '.$border_color.';') : '';
$bg_color = ($bg_color != '') ? ('background-color:'.$bg_color.';') : '';
$blend_mode_css = ( $blend_mode != 'none' ) ? ' background-blend-mode:'.$blend_mode.';' : '';
$custom_css_class = vc_shortcode_custom_css_class( $css, '' );

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . $custom_css_class, $this->settings['base'],  $atts);
Mk_Static_Files::addAssets('vc_column');
