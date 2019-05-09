<?php
/**
 * Header post revision functions.
 *
 * @package Header_Builder
 */

/**
 * Sets up the post object for preview based on the post autosave.
 *
 * @since 6.0.3
 * @access private
 *
 * @param int $header_id The post ID for the header the preview.
 * @param int $user_id The currently logged in user's ID.
 * @return int The last autsaved header post ID associated to header and user.
 */
function _mkhb_set_header_preview( $header_id = 0, $user_id = 0 ) {
	if ( ! is_numeric( $header_id ) ) {
		return 0;
	}

	$preview = mkhb_get_header_post_autosave( $header_id, $user_id );
	if ( ! is_object( $preview ) ) {
		return $header_id;
	}

	return $preview->ID;
}

/**
 * Filters the latest content for preview from the post autosave.
 *
 * @since 6.0.3
 * @access private
 */
function _mkhb_show_header_preview() {
	if ( isset( $_GET['header-builder-preview-id'] ) && isset( $_GET['header-builder-preview-nonce'] ) ) {
		$preview_id = (int) $_GET['header-builder-preview-id'];

		if ( false === wp_verify_nonce( $_GET['header-builder-preview-nonce'], 'header-builder-preview-' . $preview_id ) ) {
			wp_die( esc_html( __( 'Sorry, you are not allowed to preview custom header drafts.', 'mk_framework' ) ) );
		}

		add_filter( '_mkhb_active_header_id', '_mkhb_set_header_preview' );
	}
}

add_action( 'init', '_mkhb_show_header_preview' );

/**
 * Returns all revisions of specified post.
 *
 * @since 6.0.3
 *
 * @see get_children()
 *
 * @param int|WP_Post $post_id Optional. Post ID or WP_Post object.
 * @return array An array of revisions, or an empty array if none.
 */
function mkhb_get_header_post_revisions( $post_id = 0 ) {
	$post = get_post( $post_id );
	if ( ! $post || empty( $post->ID ) ) {
		return array();
	}

	$args = array(
		'order' => 'DESC',
		'orderby' => 'date ID',
		'post_parent' => $post->ID,
		'post_type' => 'mkhb_revision',
		'post_status' => 'inherit',
	);

	$revisions = get_children( $args );
	if ( ! $revisions ) {
		return array();
	}

	return $revisions;
}

/**
 * Retrieve the autosaved data of the specified post.
 *
 * Returns a post object containing the information that was autosaved for the
 * specified post. If the optional $user_id is passed, returns the autosave for that user
 * otherwise returns the latest autosave.
 *
 * @since 6.0.3
 *
 * @param int $post_id The post ID.
 * @param int $user_id Optional The post author ID.
 * @return WP_Post|false The autosaved data or false on failure or when no autosave exists.
 */
function mkhb_get_header_post_autosave( $post_id, $user_id = 0 ) {
	$revisions = mkhb_get_header_post_revisions( $post_id );

	foreach ( $revisions as $revision ) {
		if ( false !== strpos( $revision->post_name, "{$post_id}-autosave" ) ) {
			if ( $user_id && (int) $user_id !== (int) $revision->post_author ) {
				continue;
			}

			return $revision;
		}
	}

	return false;
}

/**
 * Deletes a revision.
 *
 * Deletes the row from the posts table corresponding to the specified revision.
 *
 * @since 6.0.3
 *
 * @param int|WP_Post $revision_id Revision ID or revision object.
 * @return array|false|WP_Post|WP_Error|null Null or WP_Error if error, deleted post if success.
 */
function mkhb_delete_header_post_revision( $revision_id ) {
	$revision = mkhb_get_header_post_revision( $revision_id );
	if ( ! $revision ) {
		return $revision;
	}

	$delete = wp_delete_post( $revision->ID );

	return $delete;
}
