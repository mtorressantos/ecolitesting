<?php
extract( shortcode_atts( array(
	'title' 		=> 'New Arrivals',
	'style' 		=> 'modern',
	'image_size'	=> 'woocommerce-recent-carousel',
	'per_page' 		=> -1,
	'category'		=> '',
	'author'		=> '',
	'posts'			=> '',
	'featured' 		=> 'false',
	'order'			=> 'DESC',
	'per_view' 		=> 3,
	'orderby'		=> 'date',
	'el_class' 		=> '',
	'visibility'    => '',
	'arrow_color'          => 'rgba(34,34,34,1)',
	'arrow_bg_color'       => 'rgba(255,255,255,1)',
	'arrow_hover_color'    => 'rgba(255,255,255,1)',
	'arrow_hover_bg_color' => 'rgba(34,34,34,1)',
), $atts ) );
Mk_Static_Files::addAssets('mk_woocommerce_recent_carousel');

if($style == 'modern') {
	Mk_Static_Files::addAssets('mk_swipe_slideshow');
}