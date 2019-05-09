<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Class to get loop items pased on the paged parameter
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @version     5.0
 * @package     artbees
 */


class Mk_Load_More {


	function __construct() {
		add_action(
			'wp_ajax_nopriv_mk_load_more', array(
				&$this,
				'get_loop',
			)
		);
		add_action(
			'wp_ajax_mk_load_more', array(
				&$this,
				'get_loop',
			)
		);
	}


	public function get_loop() {
		$content = '';

		check_ajax_referer( 'mk-load-more', 'safe_load_more' );

		if ( method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
			WPBMap::addAllMappedShortcodes();
		}

			$query = isset( $_REQUEST['query'] ) ? json_decode( base64_decode( $_REQUEST['query'] ), true ) : false;
		$atts = isset( $_REQUEST['atts'] ) ? json_decode( base64_decode( $_REQUEST['atts'] ), true ) : false;
		$loop_iterator = isset( $_REQUEST['loop_iterator'] ) ? $_REQUEST['loop_iterator'] : 0;

		if ( isset( $_REQUEST['term'] ) ) {
			$query['categories'] = ! empty( $_REQUEST['term'] ) ? $_REQUEST['term'] : false;
		}
		if ( isset( $_REQUEST['author'] ) ) {
			$query['author'] = ! empty( $_REQUEST['author'] ) ? $_REQUEST['author'] : false;
		}
		if ( isset( $_REQUEST['posts'] ) ) {
			$query['post__in'] = ! empty( $_REQUEST['posts'] ) ? explode( ',', $_REQUEST['posts'] ) : false;
		}

			$query['post_status'] = 'publish';

			$query['paged'] = isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : false;

			$offset = $query['offset'];

			$loaded_posts = ! empty( $_REQUEST['loaded_posts'] ) ? $_REQUEST['loaded_posts'] : array();

		/**
		 * Fix AM-2852
		 *
		 * Send query option empty 'post__not_in' caused an issue on
		 * WP_Query call. Just send it if the $loaded_posts is not
		 * empty.
		 *
		 * @since 6.0.3
		 */
		if ( ! empty( $loaded_posts ) ) {
			$query['post__not_in'] = $loaded_posts;
		}

			$query     = mk_wp_query( $query );
			$r = $query['wp_query'];
			$atts['i'] = $loop_iterator;

		if ( $query && $atts ) {
			if ( $r->have_posts() ) :
				while ( $r->have_posts() ) :
					$r->the_post();
					$loaded_posts[] = $r->post->ID;
					$content .= mk_get_shortcode_view( $atts['shortcode_name'], 'loop-styles/' . $atts['style'], true, $atts );
					$atts['i']++;
					endwhile;
			endif;
		}

			// If there is no posts_in and loaded posts are not empty, return the actual found posts
		if ( empty( $_REQUEST['posts'] ) && ! empty( $_REQUEST['loaded_posts'] ) ) {
			$found_posts = $r->found_posts - $r->post_count - $offset;
		}

			echo json_encode(
				array(
					'i' => $atts['i'],
					'maxPages'     => $r->max_num_pages,
					'loaded_posts' => $loaded_posts,
					'found_posts'  => $found_posts,
					'content' => $content,
				)
			);

		wp_die();
	}


}


new Mk_Load_More();
