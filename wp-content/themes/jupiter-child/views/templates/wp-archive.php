<?php
global $mk_options;

// Set the post type to 'post' when user is in the blog home page or date related archive page
$post_type = ( is_home() || is_date() ) ? 'post' : 'any';

if(shortcode_exists('mk_blog')) {
		echo do_shortcode('
								[mk_blog 
									post_type = 			"' . $post_type . '"
									style =					"' . $mk_options['archive_loop_style'] . '" 
									grid_image_height =		"' . $mk_options['archive_blog_image_height'] . '" 
									disable_meta = 			"' . $mk_options['archive_blog_meta'] . '" 
									pagination_style = 		"' . $mk_options['archive_pagination_style'] . '"
								]'
						  );
}