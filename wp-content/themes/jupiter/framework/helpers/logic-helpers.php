<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Helper functions for logic part of control panel
 *
 * @author         Reza Marandi <ross@artbees.net>
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @package     artbees
 */
class Abb_Logic_Helpers {

	/**
	 * method that is resposible to unzip compress files .
	 * it used native WordPress functions.
	 *
	 * @since       1.0.0
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $zip_path compress file absolute path.
	 * @param str $dest_path Where should it be uncompressed.
	 *
	 * @return bool will return boolean status of action
	 */
	static function unZip( $zip_path, $dest_path ) {

		$zip_path  = realpath( $zip_path );
		$dest_path = realpath( $dest_path );

		$mkfs = new Mk_Fs(
			array(
				'context' => $dest_path,
			)
		);

		if ( $mkfs->get_error_code() ) {
			throw new Exception( $mkfs->get_error_message() );
			return false;
		}

		if ( ! $mkfs->exists( $zip_path ) ) {
			throw new Exception( __( 'Zip file that you are looking for is not exist' , 'mk_framework' ) );
			return false;
		}

		if ( ! $mkfs->exists( $dest_path ) ) {
			if ( ! $mkfs->mkdir( $dest_path ) ) {
				throw new Exception( __( 'Unzip destination path not exist' , 'mk_framework' ) );
				return false;
			}
		}

		if ( ! $mkfs->is_writable( $dest_path ) ) {
			throw new Exception( __( 'Unzip destination is not writable , Please resolve this issue first.' , 'mk_framework' ) );
			return false;
		}

		$unzipfile = unzip_file( $zip_path, $dest_path );
		if ( is_wp_error( $unzipfile ) ) {
			throw new Exception( $unzipfile->get_error_message(), 1 );
			return false;
		} else {
			return true;
		}
	}
	/**
	 * You can create a directory using this helper , it will check the dest directory for if its writable or not then
	 * try to create new one
	 *
	 * @since       1.0.0
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $path path of directory that need to be created
	 * @param int $perm permission of new directory , default is : 0775
	 *
	 *     @return bool will return boolean status of action , all message is setted to $this->message()
	 */
	static function checkPermAndCreate( $path, $perm = 0775 ) {

		$mkfs = new Mk_Fs(
			array(
				'context' => $path,
			)
		);

		if ( $mkfs->get_error_code() ) {
			throw new Exception( $mkfs->get_error_message() );
			return false;
		}

		if ( $mkfs->exists( $path ) ) {
			if ( ! $mkfs->is_writable( $path ) ) {
				throw new Exception( sprintf( __( '%s directory is not writable', 'mk_framework' ) , $path ) );
				return false;
			}
			return true;
		} else {
			if ( ! $mkfs->mkdir( $path, $perm ) ) {
				throw new Exception( sprintf( __( 'Can\'t create directory %s', 'mk_framework' ) , $path ) );
				return false;
			}
			return true;
		}
	}
	/**
	 * this method is resposible to download file from url and save it on server.
	 * it will check if curl is available or not and then decide to use curl or file_get_content
	 *
	 * @since       1.0.0
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param string $url url of file (http://yahoo.com/test-plugin.zip).
	 * @param string $file_name name of the fire that should be create at destination directory.
	 * @param string $dest_directory absolute path of directory that file save on it.
	 *
	 * @return bool will return action status
	 */
	static function uploadFromURL( $url, $file_name, $dest_directory ) {

		set_time_limit( 0 );

		try {
			self::checkPermAndCreate( $dest_directory );
		} catch ( Exception $e ) {
			throw new Exception( sprintf( __( 'Destination directory is not ready for upload . {%s}',  'mk_framework' ) , $dest_directory ) );
			return false;
		}

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
			return false;
		}

		$response_body = wp_remote_retrieve_body( $response );

		// Check for error
		if ( is_wp_error( $response_body ) ) {
			throw new Exception( $response_body->get_error_message() );
			return false;
		}

		$mkfs = new Mk_Fs(
			array(
				'context' => $dest_directory,
			)
		);

		if ( $mkfs->get_error_code() ) {
			throw new Exception( $mkfs->get_error_message() );
			return false;
		}

		if ( ! $mkfs->put_contents( $dest_directory . $file_name, $response_body ) ) {
			throw new Exception( sprintf( __( "Can't write file to {%s}", 'mk_framework' ) , $dest_directory . $file_name ) );
			return false;
		}

		return $dest_directory . $file_name;
	}


	/**
	 * this method is resposible to check a directory for see if its writebale or not
	 *
	 * @since       1.0.0
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $path for example (/var/www/jupiter/wp-content/plugins)
	 *
	 * @return bool true or false
	 */
	static function writableOwnerCheck( $path ) {

		$mkfs = new Mk_Fs(
			array(
				'context' => $path,
			)
		);

		if ( $mkfs->get_error_code() ) {
			return false;
		}

		return $mkfs->is_writable( $path );
	}
	/**
	 * this method is resposible to delete a directory or file
	 * if the path is pointing to a directory it will remove all the includes file recursivly and then remove directory at last step
	 * if the path is pointing to a file it will remove it
	 *
	 * @since       1.0.0
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param str $dir for example (/var/www/jupiter/wp-content/plugins)
	 *
	 * @return bool true or false
	 */
	static function deleteFileNDir( $dir ) {

		if ( empty( $dir ) == true || strlen( $dir ) < 2 ) {
			return false;
		}

		$dir = realpath( $dir );

		$mkfs = new Mk_Fs(
			array(
				'context' => $dir,
			)
		);

		if ( $mkfs->get_error_code() ) {
			return false;
		}

		if ( ! $mkfs->exists( $dir ) ) {
			return true;
		}

		if ( $mkfs->is_dir( $dir ) ) {
			return $mkfs->rmdir( $dir, true );
		} else {
			return $mkfs->delete( $dir );
		}

	}
	/**
	 * Safely and securely get file from server.
	 * It attempts to read file using WordPress native file read functions
	 * If it fails, we use wp_remote_get. if the site is ssl enabled, we try to convert it http as some servers may fail to get file
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param $file_url         string    its directory URL
	 * @param $file_dir         string    its directory Path
	 *
	 * @return $wp_file_body    string
	 */
	static function getFileBody( $file_uri, $file_dir ) {

		$file_dir = realpath( $file_dir );

		$mkfs = new Mk_Fs(
			array(
				'context' => $file_dir,
			)
		);

		if ( $mkfs->get_error_code() ) {
			throw new Exception( $mkfs->get_error_message() );
			return false;
		}

		$wp_get_file_body = $mkfs->get_contents( $file_dir );
		if ( $wp_get_file_body == false ) {
			$wp_remote_get_file = wp_remote_get( $file_uri );

			if ( is_array( $wp_remote_get_file ) and array_key_exists( 'body', $wp_remote_get_file ) ) {
				$wp_remote_get_file_body = $wp_remote_get_file['body'];

			} elseif ( is_numeric( strpos( $file_uri, 'https://' ) ) ) {

				$file_uri           = str_replace( 'https://', 'http://', $file_uri );
				$wp_remote_get_file = wp_remote_get( $file_uri );

				if ( ! is_array( $wp_remote_get_file ) or ! array_key_exists( 'body', $wp_remote_get_file ) ) {
					throw new Exception( __( 'SSL connection error. Code: template-assets-get','mk_framework' ) );
					return false;
				}

				$wp_remote_get_file_body = $wp_remote_get_file['body'];
			}

			$wp_file_body = $wp_remote_get_file_body;

		} else {
			$wp_file_body = $wp_get_file_body;
		}
		return $wp_file_body;
	}
	/**
	 * It will check the header of URL and return boolean.
	 * False if header is 404
	 * True if header is something else
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param string $url string of url for checking
	 *
	 * @return boolean true if header is not 404
	 */
	static function remoteURLHeaderCheck( $url ) {
		if ( strpos( @get_headers( $url )[0] , '404 Not Found' ) == false ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * It will create a compress file from list of files
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param array   $files for example : array('preload-images/5.jpg','kwicks/ringo.gif','rod.jpg','reddit.gif');
	 * @param string  $destination name of the file or full address of destination for example : my-archive.zip
	 * @param boolean $overwrite if destionation exist , should it overwrite the compress file ?
	 *
	 * @return boolean true if completed and false if something goes wrong
	 */
	static function zip( $files = array(), $destination = '', $overwrite = false ) {

		$mkfs = new Mk_Fs(
			array(
				'context' => $destination,
			)
		);

		if ( $mkfs->get_error_code() ) {
			return false;
		}

		// if the zip file already exists and overwrite is false, return false
		if ( $mkfs->exists( $destination ) && ! $overwrite ) {
			return false;
		}

		// vars
		$valid_files = array();

		// if files were passed in...
		if ( is_array( $files ) ) {
			// cycle through each file
			foreach ( $files as $file_name => $file_path ) {
				// make sure the file exists
				if ( $mkfs->exists( $file_path ) ) {
					$valid_files[ $file_name ] = $file_path;
				}
			}
		}
		// if we have good files...
		if ( count( $valid_files ) ) {

			$temp_file = tempnam( sys_get_temp_dir(), 'zip' );

			if ( class_exists( 'ZipArchive', false ) ) {
				$zip = new ZipArchive();

				// Try open the temp file.
				$zip->open( $temp_file );

				// add the files to archive.
				foreach ( $valid_files as $file_name => $file_path ) {
					$zip->addFile( $file_path, $file_name );
				}

				// close the zip -- done!
				$zip->close();

			} else {

				mbstring_binary_safe_encoding();

				require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

				$zip = new PclZip( $temp_file );

				foreach ( $valid_files as $file_name => $file_path ) {
					$zip->create( $file_path, $file_name );
				}

				reset_mbstring_encoding();
			}

			// add the files to archive.
			foreach ( $valid_files as $file_name => $file_path ) {
				$zip->addFile( $file_path, $file_name );
			}

			// debug
			// echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			// close the zip -- done!
			$zip->close();

			// Copy the temp file to destination.
			$mkfs->copy( $temp_file, $destination, true, 0644 );

			// Try delete the temp file.
			@$mkfs->delete( $temp_file );

			// check to make sure the file exists.
			return $mkfs->exists( $destination );

		} else {
			return false;
		}
	}
	static function search_multdim( $array, $key, $value ) {
		return (array_search( $value, array_column( $array, $key ) ));
	}
	/**
	 * It will check wether wordpress-importer plugin is exist in plugin directory or not.
	 * if exist it will return the WordPress importer file
	 * if not it will use jupiter version
	 *
	 * @author      Reza Marandi <ross@artbees.net>
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.5
	 */

	static function include_wordpress_importer() {
		// Avoid redeclare WP_Importer class.
		if ( ! class_exists( 'WP_Importer' ) ) {
			defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
			include ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		// Avoid redeclare WXR_Importer class and others.
		if ( class_exists( 'WXR_Importer' ) ) {
			return true;
		}

		// Load all needed class.
		include THEME_CONTROL_PANEL . '/logic/importer/class-logger.php';
		include THEME_CONTROL_PANEL . '/logic/importer/class-logger-serversentevents.php';
		include THEME_CONTROL_PANEL . '/logic/importer/class-wxr-import-info.php';
		include THEME_CONTROL_PANEL . '/logic/importer/class-wxr-importer.php';

		return true;
	}
	/**
	 * It will return permission of directory
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param string $path Full path of directory
	 *
	 * @return int
	 */
	static function get_perm( $path ) {
		return substr( sprintf( '%o', fileperms( ABSPATH . $path ) ), -4 );
	}

	/**
	 * Encrypt and decrypt string using openssl_encrypt/openssl_decrypt
	 *
	 * @author Sofyan Sitorus <sofyan@artbees.net>
	 *
	 * @param string $string Text data will be encrypted/decrypted
	 *
	 * @param string $action Action type either to encrypt or decrypt
	 *
	 * @return string
	 */
	static function encrypt_decrypt( $string, $action = 'e' ) {

		$output = false;

		$encrypt_method = 'AES-256-CBC';
		$secret_key = SECURE_AUTH_SALT;
		$secret_iv = NONCE_SALT;

		// hash
		$key = hash( 'sha256', $secret_key );

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if ( $action == 'e' ) {
			$output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
			$output = base64_encode( $output );
		} elseif ( $action == 'd' ) {
			$output = base64_decode( $string );
			$output = openssl_decrypt( $output, $encrypt_method, $key, 0, $iv );
		}

		return $output;
	}
}
