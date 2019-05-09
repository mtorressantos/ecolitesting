<?php

extract(
	shortcode_atts(
		array(
			'title'           => '',
			'style'           => 'classic',
			'hover_scenarios' => 'fadebox',
			'view_all'        => '',
			'view_all_text'   => 'VIEW ALL',
			'count'           => 10,
			'author'          => '',
			'posts'           => '',
			'offset'          => 0,
			'cat'             => '',
			'categories'      => '',
			'order'           => 'DESC',
			'orderby'         => 'date',
			'show_items'      => 4,
			'image_size'      => 'crop',
			'direction_vav'   => 'false',
			'visibility'      => 'false',
			'el_class'        => '',
			'meta_type'       => 'category',
			'animation_speed' => 400,
			'slideshow_speed' => 5000,
			'pause_on_hover'  => 'false',
		), $atts
	)
);

$direction_vav = ( 'modern' == $style ) ? 'true' : $direction_vav;
Mk_Static_Files::addAssets( 'mk_portfolio_carousel' );
