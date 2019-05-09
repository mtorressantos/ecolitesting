<?php

add_action('admin_footer', 'mk_import_icon_selector_view');

function mk_import_icon_selector_view() {
	if ( NEW_CUSTOM_ICON == true ) {
    	include_once THEME_CONTROL_PANEL . '/views/icon-selector.php';
	}
}