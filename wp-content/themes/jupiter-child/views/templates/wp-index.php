<?php
if(shortcode_exists('mk_blog')) {
	echo do_shortcode( '[mk_blog style="modern"]' );
} else {
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	endif;
}