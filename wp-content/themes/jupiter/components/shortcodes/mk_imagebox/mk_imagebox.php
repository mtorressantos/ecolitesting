<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );
$id = Mk_Static_Files::shortcode_id();

Mk_Static_Files::addCSS('#mk-imagebox-' . $id . ' .item-holder, #mk-imagebox-' . $id . ' .swiper-navigation{margin: 0 '.$padding.'px;}', $id);

$atts = array(
  'id' => $id,
  'el_class' => $el_class,
  'column' => $column,
  'content' => $content,
  'per_view' => $per_view,
  'slideshow_speed' => $slideshow_speed,
  'animation_speed' => $animation_speed,
  'scroll_nav' => $scroll_nav
);

if ( ! empty( $visibility ) ) {
	echo '<div class="' . $visibility . '">';
}

echo mk_get_shortcode_view('mk_imagebox', 'show-as/'.$show_as, true, $atts);  

if ( ! empty( $visibility ) ) {
	echo '</div>';
}