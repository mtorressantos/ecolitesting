<?php
/**
 * WordPress SFTP Filesystem.
 *
 * @package WordPress
 * @subpackage Filesystem
 */

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/phpseclib/');

require_once('Net/SFTP.php');
require_once('Crypt/RSA.php');

/**
 * WordPress Filesystem Class for implementing SFTP.
 *
 * @uses WP_Filesystem_Base Extends class
 * @package WordPress
 * @subpackage Filesystem
 *
 * @since 1.0
 */


class WP_Filesystem_SFTP extends WP_Filesystem_Base {

	/**
	 * @var mixed
	 */
	public $link = false;
	/**
	 * @var mixed
	 */
	public $sftp_link = false;
	/**
	 * @var mixed
	 */
	public $keys = false;
	/**
	 * @var mixed
	 */
	public $password = false;
	/**
	 * @var array
	 */
	public $errors = [];
	/**
	 * @var array
	 */
	public $options = [];

	/**
	 * @param $opt
	 */
	public function __construct( $opt = '' ) {
		$this->method = 'sftp';
		$this->errors = new WP_Error();

		if ( ! function_exists( 'stream_get_contents' ) ) {
			$this->errors->add( 'ssh2_php_requirement', __( 'We require the PHP5 function <code>stream_get_contents()</code>', 'mk_framework' ) );

			return false;
		}

		// Set defaults:
		if ( empty( $opt['port'] ) ) {
			$this->options['port'] = 22;
		} else {
			$this->options['port'] = $opt['port'];
		}

		if ( empty( $opt['hostname'] ) ) {
			$this->errors->add( 'empty_hostname', __( 'SSH2 hostname is required', 'mk_framework' ) );
		} else {
			$this->options['hostname'] = $opt['hostname'];
		}

		if ( ! empty( $opt['base'] ) ) {
			$this->wp_base = $opt['base'];
		}

		if ( ! empty( $opt['private_key'] ) ) {
			$this->options['private_key'] = $opt['private_key'];

			$this->keys = true;
		} else if ( empty( $opt['username'] ) ) {
			$this->errors->add( 'empty_username', __( 'SSH2 username is required', 'mk_framework' ) );
		}

		if ( ! empty( $opt['username'] ) ) {
			$this->options['username'] = $opt['username'];
		}

		if ( empty( $opt['password'] ) ) {
			if ( ! $this->keys ) {
				$this->errors->add( 'empty_password', __( 'SSH2 password is required', 'mk_framework' ) );
			}
		} else {
			$this->options['password'] = $opt['password'];

			$this->password = true;
		}
	}

	public function handle_connect_error() {
		if ( ! $this->link->isConnected() ) {
			$this->errors->add( 'connect', sprintf( __( 'Failed to connect to SSH2 Server %1$s:%2$s', 'mk_framework' ), $this->options['hostname'], $this->options['port'] ) );
			$this->errors->add( 'connect2', __( 'If SELinux is installed check to make sure that <code>httpd_can_network_connect</code> is set to 1', 'mk_framework' ) );

			return true;
		}

		return false;
	}

	/**
	 * @param $command
	 * @param $returnbool
	 * @return mixed
	 */
	public function run_command( $command, $returnbool = false ) {
		if ( ! $this->link ) {
			return false;
		}

		$data = $this->link->exec( $command );

		if ( $returnbool ) {
			return ( false === $data ) ? false : '' != trim( $data );
		} else {
			return $data;
		}
	}

	/**
	 * Gets the permissions of the specified file or filepath in their octal format
	 *
	 * @access public
	 * @since 2.5.0
	 * @param string $file
	 * @return string the last 3 characters of the octal number
	 */
	public function getchmod( $file ) {
		$result = $this->link->stat( $file );

		return substr( decoct( $result['permissions'] ), 3 );
	}

	/**
	 * Change the ownership of a file / folder.
	 *
	 * Default behavior is to do nothing, override this in your subclass, if desired.
	 *
	 * @access public
	 * @since 2.5.0
	 *
	 * @param string $file      Path to the file.
	 * @param mixed  $owner     A user name or number.
	 * @param bool   $recursive Optional. If set True changes file owner recursivly. Defaults to False.
	 * @return bool Returns true on success or false on failure.
	 */
	public function chown( $file, $owner, $recursive = false ) {
		if ( ! $this->exists( $file ) ) {
			return false;
		}

		if ( ! $recursive || ! $this->is_dir( $file ) ) {
			return $this->run_command( sprintf( 'chown %o %s', $mode, escapeshellarg( $file ) ), true );
		}

		return $this->run_command( sprintf( 'chown -R %o %s', $mode, escapeshellarg( $file ) ), true );
	}

	/**
	 * Connect filesystem.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @return bool True on success or false on failure (always true for WP_Filesystem_Direct).
	 */
	public function connect() {

		$this->link = new Net_SFTP( $this->options['hostname'], $this->options['port'] );

		if ( ! $this->keys ) {
			if ( ! $this->link->login( $this->options['username'], $this->options['password'] ) ) {
				if ( $this->handle_connect_error() ) {
					return false;
				}

				$this->errors->add( 'auth', sprintf( __( 'Username/Password incorrect for %s', 'mk_framework' ), $this->options['username'] ) );

				return false;
			}
		} else {
			$rsa = new Crypt_RSA();

			if ( $this->password ) {
				$rsa->setPassword( $this->options['password'] );
			}

			$rsa->loadKey( $this->options['private_key'] );

			if ( ! $this->link->login( $this->options['username'], $rsa ) ) {
				if ( $this->handle_connect_error() ) {
					return false;
				}

				$this->errors->add( 'auth', sprintf( __( 'Private key incorrect for %s', 'mk_framework' ), $this->options['username'] ) );
				$this->errors->add( 'auth', __( 'Make sure that the key you are using is an RSA key and not a DSA key', 'mk_framework' ) );

				return false;
			}
		}

		return true;
	}

	/**
	 * Read entire file into a string.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Name of the file to read.
	 * @return mixed|bool Returns the read data or false on failure.
	 */
	public function get_contents( $file, $type = '', $resumepos = 0 ) {
		return $this->link->get( $file );
	}

	/**
	 * Read entire file into an array.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to the file.
	 * @return array|bool the file contents in an array or false on failure.
	 */
	public function get_contents_array( $file ) {
		$lines    = preg_split( '#(\r\n|\r|\n)#', $this->link->get( $file ), -1, PREG_SPLIT_DELIM_CAPTURE );
		$newLines = [];

		for ( $i = 0; $i < count( $lines ); $i += 2 ) {
			$newLines[] = $lines[ $i ] . $lines[ $i + 1 ];
		}

		return $newLines;
	}

	/**
	 * Write a string to a file.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file     Remote path to the file where to write the data.
	 * @param string $contents The data to write.
	 * @param int    $mode     Optional. The file permissions as octal number, usually 0644.
	 * @return bool False on failure.
	 */
	public function put_contents( $file, $contents, $mode = false ) {
		$ret = $this->link->put( $file, $contents );

		$this->chmod( $file, $mode );

		return false !== $ret;
	}

	/**
	 * Get the current working directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @return string|bool The current working directory on success, or false on failure.
	 */
	public function cwd() {
		$cwd = $this->run_command( 'pwd' );

		if ( $cwd ) {
			$cwd = trailingslashit( $cwd );
		}

		return $cwd;
	}

	/**
	 * Change current directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $dir The new current directory.
	 * @return bool|string
	 */
	public function chdir( $dir ) {
		$this->list->chdir( $dir );

		return $this->run_command( 'cd ' . $dir, true );
	}

	/**
	 * Change the file group.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file      Path to the file.
	 * @param mixed  $group     A group name or number.
	 * @param bool   $recursive Optional. If set True changes file group recursively. Defaults to False.
	 * @return bool|string
	 */
	public function chgrp( $file, $group, $recursive = false ) {
		if ( ! $this->exists( $file ) ) {
			return false;
		}

		if ( ! $recursive || ! $this->is_dir( $file ) ) {
			return $this->run_command( sprintf( 'chgrp %o %s', $mode, escapeshellarg( $file ) ), true );
		}

		return $this->run_command( sprintf( 'chgrp -R %o %s', $mode, escapeshellarg( $file ) ), true );
	}

	/**
	 * Change filesystem permissions.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file      Path to the file.
	 * @param int    $mode      Optional. The permissions as octal number, usually 0644 for files, 0755 for dirs.
	 * @param bool   $recursive Optional. If set True changes file group recursively. Defaults to False.
	 * @return bool|string
	 */
	public function chmod( $file, $mode = false, $recursive = false ) {
		return false === $mode ? false : $this->link->chmod( $mode, $file, $recursive );
	}

	/**
	 * Get the file owner.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 * 
	 * @param string $file Path to the file.
	 * @return string|bool Username of the user or false on error.
	 */
	public function owner( $file, $owneruid = false ) {
		if ( false === $owneruid ) {
			$result   = $this->link->stat( $file );
			$owneruid = $result['uid'];
		}

		if ( ! $owneruid ) {
			return false;
		}

		if ( ! function_exists( 'posix_getpwuid' ) ) {
			return $owneruid;
		}

		$ownerarray = posix_getpwuid( $owneruid );

		return $ownerarray['name'];
	}

	/**
	 * Get the file's group.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to the file.
	 * @return string|bool The group or false on error.
	 */
	public function group( $file, $gid = false ) {
		if ( false === $gid ) {
			$result = $this->link->stat( $file );
			$gid    = $result['gid'];
		}

		if ( ! $gid ) {
			return false;
		}

		if ( ! function_exists( 'posix_getgrgid' ) ) {
			return $gid;
		}

		$grouparray = posix_getgrgid( $gid );

		return $grouparray['name'];
	}

	/**
	 * Copy a file.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $source      Path to the source file.
	 * @param string $destination Path to the destination file.
	 * @param bool   $overwrite   Optional. Whether to overwrite the destination file if it exists.
	 *                            Default false.
	 * @param int    $mode        Optional. The permissions as octal number, usually 0644 for files, 0755 for dirs.
	 *                            Default false.
	 * @return bool True if file copied successfully, False otherwise.
	 */
	public function copy( $source, $destination, $overwrite = false, $mode = false ) {
		if ( ! $overwrite && $this->exists( $destination ) ) {
			return false;
		}

		$content = $this->get_contents( $source );

		if ( false === $content ) {
			return false;
		}

		return $this->put_contents( $destination, $content, $mode );
	}

	/**
	 * Move a file.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $source      Path to the source file.
	 * @param string $destination Path to the destination file.
	 * @param bool   $overwrite   Optional. Whether to overwrite the destination file if it exists.
	 *                            Default false.
	 * @return bool True if file copied successfully, False otherwise.
	 */
	public function move( $source, $destination, $overwrite = false ) {
		return $this->link->rename( $source, $destination );
	}

	/**
	 * Delete a file or directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file      Path to the file.
	 * @param bool   $recursive Optional. If set True changes file group recursively. Defaults to False.
	 *                          Default false.
	 * @param bool   $type      Type of resource. 'f' for file, 'd' for directory.
	 *                          Default false.
	 * @return bool True if the file or directory was deleted, false on failure.
	 */
	public function delete( $file, $recursive = false, $type = false ) {
		if ( 'f' == $type || $this->is_file( $file ) ) {
			return $this->link->delete( $file );
		}

		if ( ! $recursive ) {
			return $this->link->rmdir( $file );
		}

		return $this->link->delete( $file, $recursive );
	}

	/**
	 * Check if a file or directory exists.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file/directory.
	 * @return bool Whether $file exists or not.
	 */
	public function exists( $file ) {
		return $this->link->file_exists( $file );
	}

	/**
	 * Check if resource is a file.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file File path.
	 * @return bool Whether $file is a file.
	 */
	public function is_file( $file ) {
		$result = $this->link->stat( $file );

		return NET_SFTP_TYPE_REGULAR == $result['type'];
	}

	/**
	 * Check if resource is a directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $path Directory path.
	 * @return bool Whether $path is a directory.
	 */
	public function is_dir( $path ) {
		$result = $this->link->stat( $path );

		return NET_SFTP_TYPE_DIRECTORY == $result['type'];
	}

	/**
	 * Check if a file is readable.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file.
	 * @return bool Whether $file is readable.
	 */
	public function is_readable( $file ) {
		return $this->link->is_readable( $file );
	}

	/**
	 * Check if a file or directory is writable.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file.
	 * @return bool Whether $file is writable.
	 */
	public function is_writable( $file ) {
		return $this->link->is_writable( $file );
	}

	/**
	 * Gets the file's last access time.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file.
	 * @return int|bool Unix timestamp representing last access time.
	 */
	public function atime( $file ) {
		$result = $this->link->stat( $file );

		return $result['atime'];
	}

	/**
	 * Gets the file modification time.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file.
	 * @return int|bool Unix timestamp representing modification time.
	 */
	public function mtime( $file ) {
		$result = $this->link->stat( $file );

		return $result['mtime'];
	}

	/**
	 * Gets the file size (in bytes).
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Path to file.
	 * @return int|bool Size of the file in bytes.
	 */
	public function size( $file ) {
		$result = $this->link->stat( $file );

		return $result['size'];
	}

	/**
	 * Set the access and modification times of a file.
	 *
	 * Note: If $file doesn't exist, it will be created.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file  Path to file.
	 * @param int    $time  Optional. Modified time to set for file.
	 *                      Default 0.
	 * @param int    $atime Optional. Access time to set for file.
	 *                      Default 0.
	 * @return bool Whether operation was successful or not.
	 */
	public function touch( $file, $time = 0, $atime = 0 ) {
		return $this->link->touch( $file, $time, $atime );
	}

	/**
	 * Create a directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $path  Path for new directory.
	 * @param mixed  $chmod Optional. The permissions as octal number, (or False to skip chmod)
	 *                      Default false.
	 * @param mixed  $chown Optional. A user name or number (or False to skip chown)
	 *                      Default false.
	 * @param mixed  $chgrp Optional. A group name or number (or False to skip chgrp).
	 *                      Default false.
	 * @return bool False if directory cannot be created, true otherwise.
	 */
	public function mkdir( $path, $chmod = false, $chown = false, $chgrp = false ) {
		$path = untrailingslashit( $path );

		if ( ! $chmod ) {
			$chmod = FS_CHMOD_DIR;
		}

		// if ( ! ssh2_sftp_mkdir($this->sftp_link, $path, $chmod, true) )
		// return false;
		if ( ! $this->link->mkdir( $path ) && $this->link->chmod( $chmod, $path ) ) {
			return false;
		}

		if ( $chown ) {
			$this->chown( $path, $chown );
		}

		if ( $chgrp ) {
			$this->chgrp( $path, $chgrp );
		}

		return true;
	}

	/**
	 * Delete a directory.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $path      Path to directory.
	 * @param bool   $recursive Optional. Whether to recursively remove files/directories.
	 *                          Default false.
	 * @return bool Whether directory is deleted successfully or not.
	 */
	public function rmdir( $path, $recursive = false ) {
		return $this->delete( $path, $recursive );
	}

	/**
	 * Get details for files in a directory or a specific file.
	 *
	 * @access public
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $path           Path to directory or file.
	 * @param bool   $include_hidden Optional. Whether to include details of hidden ("." prefixed) files.
	 *                               Default true.
	 * @param bool   $recursive      Optional. Whether to recursively include file details in nested directories.
	 *                               Default false.
	 * @return array|bool {
	 *     Array of files. False if unable to list directory contents.
	 *
	 *     @type string $name        Name of the file/directory.
	 *     @type string $perms       *nix representation of permissions.
	 *     @type int    $permsn      Octal representation of permissions.
	 *     @type string $owner       Owner name or ID.
	 *     @type int    $size        Size of file in bytes.
	 *     @type int    $lastmodunix Last modified unix timestamp.
	 *     @type mixed  $lastmod     Last modified month (3 letter) and day (without leading 0).
	 *     @type int    $time        Last modified time.
	 *     @type string $type        Type of resource. 'f' for file, 'd' for directory.
	 *     @type mixed  $files       If a directory and $recursive is true, contains another array of files.
	 * }
	 */
	public function dirlist( $path, $include_hidden = true, $recursive = false ) {
		if ( $this->is_file( $path ) ) {
			$limit_file = basename( $path );
			$path       = dirname( $path );
		} else {
			$limit_file = false;
		}

		if ( ! $this->is_dir( $path ) ) {
			return false;
		}

		$ret     = [];
		$entries = $this->link->rawlist( $path );

		if ( false === $entries ) {
			return false;
		}

		foreach ( $entries as $name => $entry ) {
			$struc         = [];
			$struc['name'] = $name;

			if ( '.' == $struc['name'] || '..' == $struc['name'] ) {
				continue;
			}

			// Do not care about these folders.
			if ( ! $include_hidden && '.' == $struc['name'][0] ) {
				continue;
			}

			if ( $limit_file && $struc['name'] != $limit_file ) {
				continue;
			}

			$struc['perms']       = $entry['permissions'];
			$struc['permsn']      = $this->getnumchmodfromh( $struc['perms'] );
			$struc['number']      = false;
			$struc['owner']       = $this->owner( $path . '/' . $name, $entry['uid'] );
			$struc['group']       = $this->group( $path . '/' . $name, $entry['gid'] );
			$struc['size']        = $entry['size'];  // $this->size($path.'/'.$entry);
			$struc['lastmodunix'] = $entry['mtime']; // $this->mtime($path.'/'.$entry);
			$struc['lastmod']     = date( 'M j', $struc['lastmodunix'] );
			$struc['time']        = date( 'h:i:s', $struc['lastmodunix'] );
			$struc['type']        = NET_SFTP_TYPE_DIRECTORY == $entry['type'] ? 'd' : 'f';

			if ( 'd' == $struc['type'] ) {
				if ( $recursive ) {
					$struc['files'] = $this->dirlist( $path . '/' . $struc['name'], $include_hidden, $recursive );
				} else {
					$struc['files'] = [];
				}
			}

			$ret[ $struc['name'] ] = $struc;
		}

		return $ret;
	}
}
