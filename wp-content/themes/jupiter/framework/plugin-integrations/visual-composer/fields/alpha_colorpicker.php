<?php
/**
 * Add Alpha Colorpicker Option to Visual Composer Params
 *
 * @author      Michael Taheri
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.9.5
 * @package     artbees
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) exit( 'No direct script access allowed' );

if ( function_exists( 'mk_add_shortcode_param' ) ) {
    mk_add_shortcode_param( 'alpha_colorpicker', 'mk_alpha_colorpicker_param_field' );
}

function mk_alpha_colorpicker_param_field( $settings, $value ) {
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $output = '';
    $uniqeID = uniqid();

    $output .= '<div class="mk-vc-alpha-colorpicker ' . $class . '" id="icon-selector' . $uniqeID . '">';
        $output .= '<input name="' . $param_name  . '" class="wp-color-picker-field wpb_vc_param_value ' . $param_name  . ' ' . $type  . '_field" type="text" value="' . $value. '" />';
    $output .= '</div>';

    return $output;
}
