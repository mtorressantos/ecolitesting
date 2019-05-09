<?php
/**
 * Plugin generic functions file
 *
 * @package WP Responsive Recent Post Slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Function to get plugin image sizes array
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.2.2
 */
function wppsac_get_unique() {
  static $unique = 0;
  $unique++;

  return $unique;
}
/**
 * Function to get post featured image
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.2.5
 */
function wppsac_get_post_featured_image( $post_id = '', $size = 'full') {
    $size   = !empty($size) ? $size : 'full';
    $image  = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

    if( !empty($image) ) {
        $image = isset($image[0]) ? $image[0] : '';
    }
    return $image;
}


/**
 * Function to get Taxonomies list 
 * 
 * @package WP Responsive Recent Post Slider Pro
 * @since 1.3.3
 */
function wppsac_get_category_list( $post_id = 0, $taxonomy = '' ) {
    $output = '';
    $terms  = get_the_terms( $post_id, $taxonomy );

    if( $terms && !is_wp_error($terms) && !empty($taxonomy) ) {
        $output .= '<ul class="post-categories wppsac-post-categories">';
        foreach ( $terms as $term ) {
             $output .= '<li><a href="'.get_term_link($term).'" rel="'.$taxonomy.'"> '.$term->name.' </a></li>';
        }
        $output .= '</ul>';
    }
    return $output;
}


/**
 * Function to get shortcode designs
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.2.5
 */
function wppsac_slider_designs() {
    $design_arr = array(
        'design-1'  	=> __('Design 1', 'wp-responsive-recent-post-slider'),
        'design-2'  	=> __('Design 2', 'wp-responsive-recent-post-slider'),
        'design-3'  	=> __('Design 3', 'wp-responsive-recent-post-slider'),
        'design-4' 		=> __('Design 4', 'wp-responsive-recent-post-slider'),              
	);
	return apply_filters('wppsac_slider_designs', $design_arr );
}


/**
 * Function to add array after specific key
 * 
 * @package wp-responsive-recent-post-slider
 * @since 1.2.5
 */
function wppsac_add_array(&$array, $value, $index, $from_last = false) {
    
    if( is_array($array) && is_array($value) ) {

        if( $from_last ) {
            $total_count    = count($array);
            $index          = (!empty($total_count) && ($total_count > $index)) ? ($total_count-$index): $index;
        }
        
        $split_arr  = array_splice($array, max(0, $index));
        $array      = array_merge( $array, $value, $split_arr);
    }
    
    return $array;
}


// Manage Category Shortcode Columns

add_filter("manage_category_custom_column", 'wppsac_category_columns' , 10, 3);
add_filter("manage_edit-category_columns", 'wppsac_category_manage_columns'); 
function wppsac_category_manage_columns($columns) {
   $new_columns['wpos_shortcode'] = __( 'Category ID', 'wp-responsive-recent-post-slider' );
		$columns = wppsac_add_array( $columns, $new_columns, 2 );
		return $columns;
}

function wppsac_category_columns($ouput, $column_name, $tax_id) {
	if( $column_name == 'wpos_shortcode' ) {
			$ouput .= $tax_id;
			
	    }		
	    return $ouput;

}

// Manage conetnt limit
function wprps_limit_words($string, $word_limit)
{
    if( !empty($string) ) {
        $content = strip_shortcodes( $string ); // Strip shortcodes
        $content = wp_trim_words( $string, $word_limit, '...' );
		return $content;
    }   
}