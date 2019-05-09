<?php
extract( shortcode_atts( array(
	'title' 		=> false,
	'style' 		=> 'simple',
	'icon' 		=> '',
	'visibility' => '',
	"el_class" 	=> '',
), $atts ) );
Mk_Static_Files::addAssets('vc_accordions');