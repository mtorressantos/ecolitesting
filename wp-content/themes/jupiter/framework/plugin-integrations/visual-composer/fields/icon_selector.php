<?php
if (!defined('THEME_FRAMEWORK')) exit('No direct script access allowed');

/**
 * Add Icon Selector Option to Visual Composer Params
 *
 * @author      Michael Taheri
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.5
 * @package     artbees
 */



if (function_exists('mk_add_shortcode_param')) {
    mk_add_shortcode_param('icon_selector', 'mk_icon_selector_param_field');
}



function mk_icon_selector_param_field($settings, $value) {
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $options = isset($settings['value']) ? $settings['value'] : '';
    $output = '';
    $uniqeID = uniqid();

    if( strpos( $value, 'mk-') === 0 ) {
        $icon_class = $value;
    } else {
        if ( substr( $value, 0, 1 ) == 'f' ) {
            $font_family = 'awesome-icons';
        } else if (substr( $value, 0, 2 ) == 'e6' ) {
            $font_family = 'pe-line-icons';
        } else {
            $font_family = 'icomoon';
        }
        $icons = new Mk_SVG_Icons();
        $icon_class = $icons->get_class_name_by_unicode($font_family, $value);
    }

    if ( !empty( $value ) ) {
        $button_text = __( 'Replace Icon', 'mk_framework' );
        $icon_view_class = '';
    } else {
        $button_text = __( 'Select Icon', 'mk_framework' );
        $icon_view_class = 'mka-hidden';
    }
    
    $output.= '<div class="mka-wrap mk-vc-icon-selector ' . $class . '" id="icon-selector' . $uniqeID . '">';
        $output .= '<input name="' . $param_name  . '" class="wpb_vc_param_value ' . $param_name  . ' ' . $type  . '_field" type="hidden" value="' . $value. '" />';
        $output .= '<div class="mk-vc-icon-selector-view-wrap ' . $icon_view_class . '"><span class="mk-vc-icon-selector-view">' . Mk_SVG_Icons::get_svg_icon_by_class_name(false, $icon_class) . '</span><a href="#" class="mk-vc-icon-selector-view-remove"></a></div>';
        $output .= '<a href="#" class="mk-vc-icon-selector-btn mka-button mka-button--gray mka-button--small">' . $button_text  . '</a>';
    $output.= '</div>';
    
    return $output;
}