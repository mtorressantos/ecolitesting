<?php

extract(
	shortcode_atts(
		array(
			'title' => '',
			'twitter_name' => 'twitter',
			'tweets_count' => 5,
			'text_color' => '',
			'link_color' => '',
			'item_id' => '',
			'visibility' => '',
			'el_class' => '',
		) , $atts
	)
);

$item_id = ( ! empty( $item_id )) ? $item_id : global_get_post_id();

global $mk_options;

$consumer_key = $mk_options['twitter_consumer_key'];
$consumer_secret = $mk_options['twitter_consumer_secret'];
$access_token = $mk_options['twitter_access_token'];
$access_token_secret = $mk_options['twitter_access_token_secret'];

Mk_Static_Files::addAssets( 'vc_twitter' );
