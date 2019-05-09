<?php
/**
 * URL functions.
 *
 * @package Header_Builder
 */

/**
 * Retrieves the URL used for the header preview.
 *
 * Allows additional query args to be appended.
 *
 * @since 6.0.3
 *
 * @param array $query_args Optional. Array of additional query args to be appended to the link.
 * @return string URL used for the header preview. Base used is home URL.
 */
function mkhb_get_header_preview_link( $query_args = array() ) {
	$query_args['header-builder-preview'] = 'true';
	$preview_link = add_query_arg( $query_args, get_home_url() );

	return $preview_link;
}
