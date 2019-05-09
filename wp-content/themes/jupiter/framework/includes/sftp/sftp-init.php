<?php
/**
 * Filters the path for a specific filesystem method class file.
 *
 * @see get_filesystem_method()
 * @since 5.7
 *
 * @param string $path   Path to the specific filesystem method class file.
 * @param string $method The filesystem method to use.
 */
function mk_sftp_filesystem_method_file( $abstraction_file, $method ) {
	if ( 'sftp' == $method ) {
		$abstraction_file = dirname( __FILE__ ) . '/class-wp-filesystem-sftp.php';
	}
	return $abstraction_file;
}
add_filter( 'filesystem_method_file', 'mk_sftp_filesystem_method_file', 10, 2 );

/**
 * Filters the connection types to output to the filesystem credentials form.
 *
 *                            for being writable.
 *
 * @since 2.9.0
 * @since 4.6.0 The `$context` parameter default changed from `false` to an empty string.
 *
 * @param array  $types       Types of connections.
 * @param array  $credentials Credentials to connect with.
 * @param string $type        Chosen filesystem method.
 * @param object $error       Error object.
 * @param string $context     Full path to the directory that is tested
 */
function mk_sftp_fs_ftp_connection_types( $types, $credentials, $type, $error, $context ) {
	if ( function_exists( 'stream_get_contents' ) ) {
		$types['sftp'] = __( 'SFTP', 'mk_framework' );
	}
	return $types;
}
add_filter( 'fs_ftp_connection_types', 'mk_sftp_fs_ftp_connection_types', 10, 5 );

/**
 * Filters the filesystem method to use.
 *
 * @since 5.7
 *
 * @param string $method                       Filesystem method to return.
 * @param array  $args                         An array of connection details for the method.
 * @param string $context                      Full path to the directory that is tested for being writable.
 * @param bool   $allow_relaxed_file_ownership Whether to allow Group/World writable.
 */
function mk_sftp_filesystem_method( $method, $args, $context, $allow_relaxed_file_ownership ) {

	if ( 'direct' == $method ) {
		return $method;
	}

	if ( isset( $args['connection_type'] ) && 'sftp' == $args['connection_type'] ) {
		return 'sftp';
	}

	return $method;
}
add_filter( 'filesystem_method', 'mk_sftp_filesystem_method', 10, 4 );

/**
 * Filters the filesystem credentials form output
 *
 * @since 5.7
 *
 * @param string $credentials                  Default credentials data passed.
 * @param string $form_post                    The URL to post the form to.
 * @param string $type                         Chosen type of filesystem.
 * @param string $error                        Whether the current request has failed to connect.
 * @param string $context                      Full path to the directory that is tested for being writable.
 * @param string $extra_fields                 Extra POST fields.
 * @param bool   $allow_relaxed_file_ownership Whether to allow Group/World writable.
 */
function mk_request_filesystem_credentials( $credentials, $form_post, $type, $error, $context, $extra_fields, $allow_relaxed_file_ownership ) {

	if ( isset( $extra_fields['mk_check_ftp_credentials'] ) ) {
		return $credentials;
	}

	$upload_dir = wp_upload_dir();

	$config_file = trailingslashit( $upload_dir['basedir'] ) . 'mk-fs-creds/index.php';

	if ( file_exists( $config_file ) ) {
		require $config_file;
		if ( ! empty( $mkfs_config ) ) {
			$mkfs_config_arr = json_decode( $mkfs_config );
			if ( $mkfs_config_arr && ( is_array( $mkfs_config_arr ) || is_object( $mkfs_config_arr ) ) ) {
				$credentials = array();
				// Assign $credentials data
				foreach ( $mkfs_config_arr as $key => $value ) {
					$credentials[ $key ] = Abb_Logic_Helpers::encrypt_decrypt( $value, 'd' );
				}
			}
		}
	}

	return $credentials;
}
add_filter( 'request_filesystem_credentials', 'mk_request_filesystem_credentials', 100, 7 );
