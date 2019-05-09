<?php
/**
 * Header Builder Header Post Admin API.
 *
 * Class-based functionality can later be migrated into this file for easier use and managemnent.
 *
 * @package Header Builder
 */

/**
 * Rename $_POST data from AJAX-submitted key names to post meta keys for direct
 * insertion using wp_inser_post() "meta_input" argument, etc.
 *
 * This function mutates $_POST directly.
 *
 * @since 6.0.3
 * @access private
 *
 * @param array $post_data Array of post data. Defaults to the contents of $_POST.
 * @return array Copy of $_POST with keys modified.
 */
function _mkhb_translate_header_post_data( $post_data = null ) {
	if ( ! is_array( $post_data ) && ! empty( $post_data ) ) {
		$post_data = &$_POST; // CSRF ok.
	}

	/**
	 * Make sure that $_POST['post_id'] and $_POST['post_ID'] are both set when we send only the 'post_id'.
	 *
	 * Different parts of WordPress use either post_id, post_ID, and ID inside of $_POST. WordPress
	 * reads from either $_POST['post_id'] or $_POST['post_ID'] in various locations and uses this
	 * information to set $_POST['ID'] when it needs to.
	 *
	 * @since 6.0.3
	 */
	if ( array_key_exists( 'post_id', $post_data ) ) {
		$post_id = (int) $post_data['post_id'];
	}

	if ( ! isset( $post_id ) ) {
		return new WP_Error( 'no_post_id', __( 'You need to pass a valid post ID.', 'mk_framework' ) );
	}

	$post_data['post_id'] = $post_id;
	$post_data['post_ID'] = $post_id;

	if ( ! array_key_exists( 'metas', $post_data ) ) {
		return $post_data;
	}

	$metas = $post_data['metas'];
	$meta_map = array(
		'_mkhb_content_normal_header_desktop' => 'normal_desktop',
		'_mkhb_content_normal_header_tablet' => 'normal_tablet',
		'_mkhb_content_normal_header_mobile' => 'normal_mobile',
		'_mkhb_content_sticky_header_desktop' => 'sticky_desktop',
		'_mkhb_content_sticky_header_tablet' => 'sticky_tablet',
		'_mkhb_content_sticky_header_mobile' => 'sticky_mobile',
		'_mkhb_chains' => 'chains',
		'_mkhb_chain' => 'chain',
		'_mkhb_specificities' => 'specificities',
		'_mkhb_active_device' => 'active_device',
		'_mkhb_active_header' => 'active_header',
		'_mkhb_options_laptop' => 'options_laptop',
		'_mkhb_options_mobile' => 'options_mobile',
		'_mkhb_options_overlapping_content' => 'options_overlapping_content',
		'_mkhb_options_sticky_header' => 'options_sticky_header',
		'_mkhb_options_sticky_header_offset' => 'options_sticky_header_offset',
		'_mkhb_options_sticky_header_behaviour' => 'options_sticky_header_behaviour',
	);

	$meta_input = array();
	foreach ( $meta_map as $db_meta_key => $form_meta_key ) {
		if ( array_key_exists( $form_meta_key, $metas ) ) {
			$meta_input[ $db_meta_key ] = $metas[ $form_meta_key ];
		}
	}

	$post_data['metas'] = $meta_input;

	return $post_data;
}

/**
 * Saves a draft or manually autosaves for the purpose of showing a post preview.
 *
 * @since 6.0.3
 *
 * @return string URL to redirect to show the preview.
 */
function mkhb_header_preview() {
	$post_id = (int) $_POST['post_id'];

	if ( ! get_post( $post_id ) ) {
		return new WP_Error( 'edit_post_not_allowed', __( 'Sorry, you are not allowed to edit this post.', 'mk_framework' ) );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return new WP_Error( 'edit_post_not_allowed', __( 'Sorry, you are not allowed to edit this post.', 'mk_framework' ) );
	}

	$update = (bool) $_POST['update_preview'];
	if ( $update ) {
		$saved_post_id = mkhb_create_header_autosave( $post_id );

		if ( is_wp_error( $saved_post_id ) ) {
			return $saved_post_id;
		}
	}

	$query_args = array();
	$query_args['header-builder-preview-id'] = $post_id;
	$query_args['header-builder-preview-nonce'] = wp_create_nonce( 'header-builder-preview-' . $post_id );

	return mkhb_get_header_preview_link( $query_args );
}

/**
 * Returns a post array ready to be inserted into the posts table as a post revision.
 *
 * @since 6.0.3
 * @access private
 *
 * @param array|WP_Post $post Optional. A post array or a WP_Post object to be processed
 *                            for insertion as a header revision. Default empty array.
 * @param string        $mode Optional. Whether we will store a revision or an autosave.
 * @return array Post array ready to be inserted as a post revision.
 */
function _mkhb_header_post_revision_data( $post = array(), $mode = 'revision' ) {
	if ( ! is_array( $post ) ) {
		$post = get_post( $post, ARRAY_A );
	}

	$revision_data = array();
	$revision_data['post_title']    = $post['post_title'];
	$revision_data['post_parent']   = $post['ID'];
	$revision_data['post_status']   = 'inherit';
	$revision_data['post_type']     = 'mkhb_revision';

	// "1" is the revisioning system version. If we make critical changes on this system, we'll bump this to "2", "3" and so on.
	$revision_data['post_name'] = 'autosave' === $mode ? "$post[ID]-autosave-v1" : "$post[ID]-revision-v1";

	$revision_data['post_date'] = '';
	if ( isset( $post['post_modified'] ) ) {
		$revision_data['post_date'] = $post['post_modified'];
	}

	$revision_data['post_date_gmt'] = '';
	if ( isset( $post['post_modified_gmt'] ) ) {
		$revision_data['post_date_gmt'] = $post['post_modified_gmt'];
	}

	$revision_data['meta_input'] = $post['metas'];

	return $revision_data;
}

/**
 * Inserts post data into the posts table as a post revision.
 *
 * @since 6.0.3
 * @access private
 *
 * @param int|WP_Post|array|null $post Post ID, post object OR post array.
 * @param string                 $mode Optional. Whether we will store a revision or an autosave.
 * @return int|WP_Error WP_Error or 0 if error, new revision ID if success.
 */
function _mkhb_put_header_post_revision( $post = null, $mode = 'revision' ) {
	if ( is_object( $post ) ) {
		$post = get_object_vars( $post );
	} elseif ( ! is_array( $post ) ) {
		$post = get_post( $post, ARRAY_A );
	}

	if ( ! $post || empty( $post['ID'] ) ) {
		return new WP_Error( 'invalid_post', __( 'Invalid post ID.' ) );
	}

	if ( isset( $post['post_type'] ) && 'mkhb_revision' === $post['post_type'] ) {
		return new WP_Error( 'post_type', __( 'Cannot create a revision of a revision' ) );
	}

	$post = _mkhb_header_post_revision_data( $post, $mode );
	$post = wp_slash( $post ); // Since data is from database.

	$revision_id = wp_insert_post( $post );
	if ( is_wp_error( $revision_id ) ) {
		return $revision_id;
	}

	return $revision_id;
}

/**
 * Gets a post revision.
 *
 * @since 6.0.3
 *
 * @param int|WP_Post $post   The post ID or object.
 * @param string      $output Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond to
 *                            a WP_Post object, an associative array, or a numeric array, respectively. Default OBJECT.
 * @param string      $filter Optional sanitation filter. See sanitize_post().
 * @return WP_Post|array|null WP_Post (or array) on success, or null on failure.
 */
function mkhb_get_header_post_revision( &$post, $output = OBJECT, $filter = 'raw' ) {
	$revision = get_post( $post, OBJECT, $filter );
	if ( ! $revision ) {
		return $revision;
	}
	if ( 'mkhb_revision' !== $revision->post_type ) {
		return null;
	}

	if ( OBJECT === $output ) {
		return $revision;
	} elseif ( ARRAY_A === $output ) {
		$_revision = get_object_vars( $revision );
		return $_revision;
	} elseif ( ARRAY_N === $output ) {
		$_revision = array_values( get_object_vars( $revision ) );
		return $_revision;
	}

	return $revision;
}

/**
 * Creates autosave data for the specified post from $_POST data.
 *
 * @since 6.0.3
 *
 * @param int $post_id Post ID.
 * @return mixed The autosave revision ID. WP_Error or 0 on error.
 */
function mkhb_create_header_autosave( $post_id = 0 ) {
	$post_data = $_POST; // CSRF ok.

	if ( ! isset( $post_id ) || ! is_numeric( $post_id ) ) {
		return new WP_Error( 'no_post_id', __( 'You need to pass a valid post ID.', 'mk_framework' ) );
	}

	$post_data = _mkhb_translate_header_post_data( $post_data );
	if ( is_wp_error( $post_data ) ) {
		return $post_data;
	}

	$post_data = _wp_translate_postdata( true, $post_data );
	if ( is_wp_error( $post_data ) ) {
		return $post_data;
	}

	$post_author = get_current_user_id();

	// Store one autosave per header post per user. If there is already an autosave, overwrite it.
	$old_autosave = mkhb_get_header_post_autosave( $post_id, $post_author );
	if ( $old_autosave ) {
		$new_autosave = _mkhb_header_post_revision_data( $post_data, 'autosave' );
		$new_autosave['ID'] = $old_autosave->ID;

		/**
		 * If the new autosave has the same content as the post, delete the autosave.
		 *
		 * @todo This part is still in progress and should not be tested. It is important that we do our garbage collection.
		 *
		 * @since 6.0.3
		 */
		$post = get_post( $post_id );
		$different = false;
		foreach ( array_intersect( array_keys( $new_autosave ), array_keys( _mkhb_header_post_revision_data( $post ) ) ) as $field ) {
			if ( $new_autosave[ $field ] !== $post->$field ) {
				$different = true;
				break;
			}
		}

		if ( ! $different ) {
			mkhb_delete_header_post_revision( $old_autosave->ID );
			return 0;
		}

		return wp_update_post( $new_autosave );
	}

	$post_data = wp_unslash( $post_data );

	// Otherwise create the new autosave as a special post revision.
	return _mkhb_put_header_post_revision( $post_data, 'autosave' );
}
