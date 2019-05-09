<?php
extract(
	shortcode_atts(
		array(
			'el_class' => '',
			'title' => '',
			'disable_pattern' => 'true',
			'margin_bottom' => 0,
			'align' => 'left',
			'animation' => '',
			'visibility' => '',
			'css' => '',
		) , $atts
	)
);

$fancy_style = '';
$pattern = ! empty( $disable_pattern ) ? $disable_pattern : 'true';
