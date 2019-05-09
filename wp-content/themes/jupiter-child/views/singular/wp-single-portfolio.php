<?php

/**
 * Template part for portfolio single portfolio-single.php. views/portfolio/components
 *
 * @author  Artbees
 * @package jupiter/views
 * @since   5.0.0
 * @since   5.9.1 Added if conditions for `mk_get_view()` functions.
 */

global $mk_options;

if (have_posts()) while (have_posts()):
    the_post();
    
    do_action('portfolio_single_before_meta');
    
    mk_get_view('portfolio/components', 'portfolio-single-meta');
    
    if ( get_post_meta( $post->ID, '_portfolio_featured_image', true ) !== 'false' ) {
        mk_get_view( 'portfolio/components', 'portfolio-single-featured' );
    }
    
    do_action('portfolio_single_before_the_content');
    
    the_content();
    
    do_action('portfolio_single_after_the_content');
    
    if ( $mk_options['enable_portfolio_comment'] == 'true' ) {
        mk_get_view( 'portfolio/components', 'portfolio-single-comments' );
    }
    
    do_action('portfolio_single_after_comments');
    
endwhile;
?>
