<?php
extract(shortcode_atts(array(
    'title'     => '',
    'max_width' => 0,
    'host'      => 'social_hosted',
    'poster_image' => '',
    'mp4'       => '',
    'webm'      => '',
    'link'      => '',
    'visibility' => '',
    'el_class'  => '',
    'animation' => '',
    'autoplay' => 'false',
    'loop' => 'false',
    'hide_controls' => 'false',
    'aspect_ratio' => 'false',
    'custom_thumbnail' => 'false',
    'thumbnail_image' => '',
    'play_icon' => 'mk-icon-play',
    'play_icon_size' => '32',
    'play_icon_animation' => 'fade-in',
    'play_icon_color' => '#ffffff',
    'play_target' => 'lightbox',
), $atts));


if( empty( $play_icon ) ){
    $play_icon = 'mk-icon-play';
}

if( empty( $play_icon_size ) ){
	$play_icon_size = '32';
}

if( empty( $play_icon_color ) ){
	$play_icon_color = '#ffffff';
}

if( empty( $play_target ) ){
	$play_target = 'lightbox';
}

if( empty( $play_icon_animation ) ){
    $play_icon_animation = 'fade-in';
}

Mk_Static_Files::addAssets('vc_video');
