<?php
extract( shortcode_atts( array(
	'id' => '',
	'visibility' => '',
), $atts ) );
Mk_Static_Files::addAssets('mk_layerslider');