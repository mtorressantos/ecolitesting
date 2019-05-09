<?php

extract(
	shortcode_atts(
		array(
			'author'        	=> '',
			'posts'             => '',
			'cat'           	=> '',
			'offset'            => 0,
			'order'         	=> 'DESC',
			'orderby'       	=> 'date',
			'excerpt_length'    => 200,
			'visibility'    	=> '',
			'el_class'      	=> '',
		), $atts
	)
);
