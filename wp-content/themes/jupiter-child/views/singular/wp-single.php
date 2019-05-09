<?php

/**
 * Template part for blog single single.php. views/blog/components
 *
 * @author  Artbees
 * @package jupiter/views
 * @since   5.0.0
 * @since   5.9.1 Added if conditions for `mk_get_view()` functions and removed unnecessary php tags.
 */

global $mk_options;

if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<article id="<?php the_ID(); ?>" <?php post_class(array('mk-blog-single')); ?> <?php echo get_schema_markup('blog_posting'); ?>>

	<?php
	
	do_action('blog_single_before_featured_image');

	if ( mk_get_blog_single_style() !== 'bold' ) {
		mk_get_view( 'blog/components', 'blog-single-featured' );
		mk_get_view( 'blog/components', 'blog-single-meta' ); 
	}

	do_action('blog_single_before_the_content');

	mk_get_view('blog/components', 'blog-single-content');

	do_action('blog_single_after_the_content');

	if ( mk_get_blog_single_style() === 'bold' ) {
		mk_get_view( 'blog/components', 'blog-single-bold-share' ); 
	}

	if ( $mk_options['enable_blog_author'] === 'true' && get_post_meta( $post->ID, '_disable_about_author', true ) !== 'false' ) {
		mk_get_view( 'blog/components', 'blog-single-about-author' ); 
	}

	if ( mk_get_blog_single_style() !== 'bold' ) {
		mk_get_view( 'blog/components', 'blog-similar-posts' ); 
	}

	if ( $mk_options['blog_single_comments'] === 'true' && get_post_meta( $post->ID, '_disable_comments', true ) !== 'false' ) {
		mk_get_view( 'blog/components', 'blog-single-comments' ); 
	}

	do_action('blog_single_after_comments'); 
	
	?>

</article>

<?php endwhile; ?>