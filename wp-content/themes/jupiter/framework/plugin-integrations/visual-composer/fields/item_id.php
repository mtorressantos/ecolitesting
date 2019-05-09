<?php
if (!defined('THEME_FRAMEWORK')) exit('No direct script access allowed');

/**
 * Adds options to generate a input with random ID in Visual Composer params
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1
 * @package     artbees
 */

if (function_exists('mk_add_shortcode_param')) {
    mk_add_shortcode_param('item_id', 'mk_item_id_form_field');
}



function mk_item_id_form_field($settings, $value) {
    $value = time() . '-' . uniqid();

    return '<div class="mk_param_block">
    			<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . $value . '" />
    			<label>' . $value . '</label>
    		</div>';
}