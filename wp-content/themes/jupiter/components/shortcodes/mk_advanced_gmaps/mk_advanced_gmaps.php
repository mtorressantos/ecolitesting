<?php

global $mk_options;

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = Mk_Static_Files::shortcode_id();

// Quit if no lat / lang
if( $longitude == '' && $latitude == '') return null;

// Zoom cannot be less than one
if( $map_zoom < 1 ) $map_zoom = 1;

// Disable coloring options when full JSON customization is passed
if( $modify_json == 'true' ) $modify_coloring = 'false';



/**
 * Collect JSON config for JS
 * ==================================================================================*/

$json = array();
$json['places']  = array();
$json['options'] = array();
$json['style']   = array();
$json['icon']    = $pin_icon;


$json['places'][] = array(
	"address"   => htmlentities($address),
	"latitude"  => $latitude,
	"longitude" => $longitude,
	'marker'	=> $custom_marker_1
);

if( !empty($latitude_2) && !empty($longitude_2) ) {
	$json['places'][] = array(
		"address"   => htmlentities($address_2),
		"latitude"  => $latitude_2,
		"longitude" => $longitude_2,
		'marker'	=> $custom_marker_2
	);
}

if( !empty($latitude_3) && !empty($longitude_3) ) {
	$json['places'][] = array(
		"address"   => htmlentities($address_3),
		"latitude"  => $latitude_3,
		"longitude" => $longitude_3,
		'marker'	=> $custom_marker_3
	);
}

if( !empty($additional_markers) ) {

    if ( base64_decode($additional_markers, true) === false ) {
        $additional_markers = json_decode( urldecode($additional_markers), true);
    } else {
        $additional_markers = json_decode( urldecode( base64_decode($additional_markers) ), true);
    }

    foreach ($additional_markers as $marker) {
    	
    	$address  		= isset($marker['address']) ? $marker['address'] : '';
		$latitude  		= isset($marker['latitude']) ? $marker['latitude'] : '';
		$longitude 		= isset($marker['longitude']) ? $marker['longitude'] : '';
		$marker_icon 	= isset($marker['marker_icon']) ? $marker['marker_icon'] : '';

		$json['places'][] = array(
			"address"   => $address,
			"latitude"  => $latitude,
			"longitude" => $longitude,
			'marker'	=> $marker_icon
		);
    } 
}


$json['options'] = array(
	"zoom"      		=> intval($map_zoom),
	"draggable"			=> $draggable == 'true' ? true : false,
	"panControl"		=> $pan_control == 'true' ? true : false,
	"zoomControl"		=> $zoom_control == 'true' ? true : false,
	"scaleControl"		=> $scale_control == 'true' ? true : false,
	"mapTypeControl"	=> $map_type_control == 'true' ? true : false,
    "mapTypeId"			=> $map_type
);


if(isset($mk_options['google_maps_api_key']) && !empty($mk_options['google_maps_api_key'])) {
	$json['options']['apikey'] = $mk_options['google_maps_api_key'];
}

if( $modify_coloring != 'false' ) {
	$json['style'][] = array(
	    "stylers" => array(
	        array(  "hue" => $hue ),
		array(  "saturation" 	 => $saturation ),
	    	array(  "lightness"   	=> $lightness ),
		array(  "featureType" 	=> "landscape.man_made",
			"stylers" 		=> array(
				array( "visibility" => "on" )
		    	)
		)
	    )
	);
}
else if( $modify_json != 'false' ) {
	$json['style'] = json_decode( urldecode(base64_decode($map_json)), true );
}


$json = str_replace("'", "&apos;", json_encode( $json ) );


/**
 * Custom CSS Output
 * ==================================================================================*/

$style = array();
$class = '';
$class .= ' ' . $visibility;

if( $map_height == 'custom' ) {
	$style['mk_advanced_gmap'] = 'height: '. $height .'px;';
}

// $map_height = 'test';
$full_height = $map_height != 'custom' ? true : false;



Mk_Static_Files::addCSS('
	#mk-advanced-gmaps-'.$id.' .gm-style-iw,
	#mk-advanced-gmaps-'.$id.' .gm-style-iw .poi-info-window *:not(.renderable-component-text-box-content)
	{ background-color: '.$content_bg_color.'; }
	#mk-advanced-gmaps-'.$id.' .gm-style > div:nth-child(1) > div:last-child > div:last-child > div > div:first-child > div:last-child
	{ background-color: '.$content_bg_color.' !important; }
	#mk-advanced-gmaps-'.$id.' .gm-style > div:nth-child(1) > div:last-child > div:last-child > div > div:first-child > div:nth-child(3) > div > div
	{ background-color: '.$content_bg_color.' !important; }
	#mk-advanced-gmaps-'.$id.' .gm-style-iw .info_content p,
	#mk-advanced-gmaps-'.$id.' .gm-style-iw .info_content p strong
	{ color: '.$content_font_color.'; }
	#mk-advanced-gmaps-'.$id.' .gm-style-iw .poi-info-window *:not(.renderable-component-text-box-content)
	{ color: '.$content_font_color.'; }
', $id);


include( $path . '/template.php' );
