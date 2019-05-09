<?php

/**
 * template part for header style 3 navigation. views/header/holders
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options, $is_header_shortcode_added;

$style = !empty($mk_options['secondary_menu']) ? $mk_options['secondary_menu'] : 'fullscreen';

$seondary_header_for_all = !empty($mk_options['seondary_header_for_all']) ? $mk_options['seondary_header_for_all'] : 'false';

/**
 * After new changed, it will return null if there is no header shortcode
 * added. Need to check and save it as array if it's null to avoid error.
 * And also, $view_params['header_shortcode_style'] value will be overriden
 * by the next header shortcode. So, it can't be used for multiple header
 * shortcodes in the same page.
 *
 * @since 6.0.3
 * @see /components/shortcodes/mk_header/config.php
 */
if ( ! is_array( $is_header_shortcode_added ) ) {
	$is_header_shortcode_added = array();
}

// Disable when seconday menu style is false and header style is not 3. So basically user can not disable this module if header style 3 is chosen.
if ( $seondary_header_for_all == 'false' && get_header_style() != 3 && ! in_array( '3', $is_header_shortcode_added, true ) ) return false;

    
if ($style == 'dashboard') {
    
    mk_get_header_view('master', 'side-dashboard');
} 
else if ($style == 'fullscreen') {
    
    mk_get_header_view('master', 'full-screen-nav');
}
