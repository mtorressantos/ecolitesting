<?php
global $mk_options;
extract( shortcode_atts( array(
	'el_class' 		=> '',
	'title' 		=> '',
	'style' 		=> 'mk-icon-check',
	'icon_color'	=> $mk_options['skin_color'],
	'animation' 	=> '',
	'align' 		=> 'none',
	'margin_bottom' => 30,
	'visibility'    => ''
), $atts ) );
Mk_Static_Files::addAssets('mk_custom_list');