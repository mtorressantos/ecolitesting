<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function get_wppsac_slider( $atts, $content = null ) {
	
    extract(shortcode_atts(array(
		"limit" 				=> '10',
		"design" 				=> 'design-1',
		"category"              => '',	
        "show_date" 			=> 'true',
        "show_category_name" 	=> 'true',
        "show_content" 			=> 'true',
        "content_words_limit" 	=> '20',
		"dots"     				=> 'true',
		"arrows"     			=> 'true',				
		"autoplay"     			=> 'true',		
		"autoplay_interval" 	=> '3000',				
		"speed"             	=> '500',
		"hide_post"        		=> array(),
		"post_type"       		=> 'post',		
		"taxonomy"				=> 'category',
		"show_author" 			=> 'true',
		"show_read_more" 		=> 'true',
		"media_size"			=> 'full',	
		"rtl"                  	=> 'false'	
	), $atts));
	
	$unique 			= wppsac_get_unique();
	$shortcode_designs 	= wppsac_slider_designs();
	$posts_per_page 	= !empty($limit) 					? $limit 						: '-1';	
	$cat 				= (!empty($category)) 				? explode(',', $category) 		: '';	
	$design 			= ($design && (array_key_exists(trim($design), $shortcode_designs))) ? trim($design) : 'design-1';
	$showCategory 		= ( $show_category_name == 'false' ) 		? false 				: true;	
	$showContent 		= ( $show_content == 'false' ) 		? false 						: true;
	$showDate 			= ( $show_date == 'false') 			? false 						: true;	
	$showAuthor 		= ( $show_author == 'false') 		? false 						: true;	
	$showreadmore 		= ( $show_read_more == 'false') 	? false 						: true;	
	$words_limit 		= !empty( $content_words_limit ) 	? $content_words_limit	 		: 20;
	$dots 				= ( $dots == 'false' ) 				? 'false' 						: 'true';
	$arrows 			= ( $arrows == 'false' ) 			? 'false' 						: 'true';
	$autoplay 			= ( $autoplay == 'false' ) 			? 'false' 						: 'true';
	$autoplay_interval 	= (!empty($autoplay_interval)) 		? $autoplay_interval 			: 3000;
	$speed 				= (!empty($speed)) 					? $speed 						: 500;
	$post_type 			= !empty($post_type)                ? $post_type 					: 'post';
	$taxonomy 			= !empty($taxonomy)					? $taxonomy						: 'category';
	$media_size 		= !empty($media_size) 				? $media_size 					: 'full'; // you can use thumbnail, medium, medium_large, large, full
	$exclude_post		= !empty($hide_post)				? explode(',', $hide_post) 		: array();
	
	// For RTL
	if( empty($rtl) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}
	
	// Shortcode file
	$design_file_path 	= WPRPS_DIR . '/templates/' . $design . '.php';
	$design_file 		= (file_exists($design_file_path)) ? $design_file_path : '';
	
	// Enqueus required script
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wppsac-public-script' );
	
	// Slider configuration
	$slider_conf = compact('dots', 'arrows', 'autoplay', 'autoplay_interval','speed', 'rtl');
	
	// Taking some global
	global $post;
	
	ob_start();
		
    // WP Query Parameters
	$args = array (
		'post_type'      		=> $post_type,
		'post_status' 			=> array( 'publish' ),
		'orderby'        		=> 'date',
		'order'          		=> 'DESC',
		'posts_per_page' 		=> $posts_per_page,
		'post__not_in'	 		=> $exclude_post,		
	);

 	// Category Parameter
	if($cat != "") {

		$args['tax_query'] = array(
									array(
											'taxonomy' 		=> $taxonomy,
											'field' 		=> 'term_id',
											'terms' 		=> $cat,
								));
	}

	$query = new WP_Query($args);
	$post_count = $query->post_count;
         
             if ( $query->have_posts() ) :
			 ?>
				<div class="wppsac-slick-slider-wrp wppsac-clearfix">
					<div id="recent-post-slider-<?php echo $unique; ?>" class="recent-post-slider <?php echo $design; ?>">
						<?php
					 while ( $query->have_posts() ) : $query->the_post();
						$post_id 		= isset($post->ID) ? $post->ID : '';						
						$cat_list		= wppsac_get_category_list($post->ID, $taxonomy);
						$feat_image 	= wppsac_get_post_featured_image( $post->ID, $media_size, true );	
							if( $design_file ) {
									include( $design_file );
								}
							endwhile; ?>
					</div>
					<div class="wppsac-slider-conf wppsac-hide" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>"></div>
				</div>
		  <?php
            endif; 
             wp_reset_query();
		return ob_get_clean();
}
add_shortcode('recent_post_slider', 'get_wppsac_slider');