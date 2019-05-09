<?php
/**
 * Header Builder: Shortcode Render, MKHB_Post_Type class.
 *
 * For use in creating custom post type for HB.
 *
 * @package Header_Builder
 * @subpackage Elements_Generator
 * @since 6.0.0
 */

/**
 * Run hooks to create custom post types for HB.
 *
 * @since 6.0.0
 */
class MKHB_Post_Type {
	/**
	 * HB_Render constructor. Run some action to render HB.
	 *
	 * @since 6.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_mkhb_header' ) );
		add_action( 'init', array( $this, 'register_mkhb_header_revision' ) );
	}

	/**
	 * Create mkhb_header custom post type.
	 *
	 * @since 6.0.0
	 * @since 6.0.3 Remove support for 'editor' field as it is not used.
	 */
	public function register_mkhb_header() {
		$args = array(
			'labels'             => array(
				'name' => __( 'Headers' ),
				'singular_name' => __( 'Header' ),
			),
			'public'             => false,
			'rewrite'            => array(
				'slug' => 'mkhb_header',
			),
			'capability_type'    => 'post',
			'supports'           => array( 'title', 'author' ),
		);

		register_post_type( 'mkhb_header', $args );
	}

	/**
	 * Create mkhb_revision custom post type for storing drafts and enabling previews.
	 *
	 * @since 6.0.3
	 */
	public function register_mkhb_header_revision() {
		register_post_type( 'mkhb_revision', array(
			'labels' => array(
				'name' => __( 'Header Revisions' ),
				'singular_name' => __( 'Header Revision' ),
			),
			'public' => false,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'hierarchical' => false,
			'rewrite' => false,
			'query_var' => false,
			'can_export' => false,
			'delete_with_user' => true,
			'supports' => array( 'title', 'author' ),
		) );
	}

}

new MKHB_Post_Type();
