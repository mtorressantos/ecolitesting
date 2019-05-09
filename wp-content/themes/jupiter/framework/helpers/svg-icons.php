<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * This file is resposnible for generating SVG icons from the given font family and icon name
 *
 * @author      Bob Ulusoy & Bartosz Makos & Reza Marandi
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 1.0
 * @package     artbees
 */

class Mk_SVG_Icons {

	static $font_families = array(
		'mk-icon' => 'awesome-icons',
		'mk-moon' => 'icomoon',
		'mk-li' => 'pe-line-icons',
		'mk-jupiter-icon' => 'theme-icons',
	);


	private $pagination_start;
	public function set_pagination_start( $pagination_start ) {
		$this->pagination_start = $pagination_start;
		return $this;
	}
	public function get_pagination_start() {
		return $this->pagination_start;
	}

	private $pagination_count;
	public function set_pagination_count( $pagination_count ) {
		$this->pagination_count = $pagination_count;
		return $this;
	}
	public function get_pagination_count() {
		return $this->pagination_count;
	}

	private $icon_family;
	public function set_icon_family( $icon_family ) {
		$this->icon_family = $icon_family;
		return $this;
	}
	public function get_icon_family() {
		return $this->icon_family;
	}

	private $icon_name;
	public function set_icon_name( $icon_name ) {
		$this->icon_name = $icon_name;
		return $this;
	}
	public function get_icon_name() {
		return $this->icon_name;
	}

	private $ajax_mode;
	public function set_ajax_mode( $ajax_mode ) {
		$this->ajax_mode = $ajax_mode;
		return $this;
	}
	public function get_ajax_mode() {
		return $this->ajax_mode;
	}

	private $system_test_env;
	public function set_system_test_env( $system_test_env ) {
		$this->system_test_env = $system_test_env;
		return $this;
	}
	public function get_system_test_env() {
		return $this->system_test_env;
	}

	private $message;
	public function set_message( $message ) {
		$this->message = $message;
		return $this;
	}
	public function get_message() {
		return $this->message;
	}


	function __construct( $ajax_mode = true, $system_test_env = false ) {
		add_action(
			'wp_ajax_nopriv_mk_get_icon', array(
				&$this,
				'get_icons',
			)
		);

		add_action(
			'wp_ajax_mk_get_icon', array(
				&$this,
				'get_icons',
			)
		);

		// New version of handling icons
		add_action(
			'wp_ajax_mk_get_icons_list', array(
				&$this,
				'get_icons_list',
			)
		);
		add_action(
			'wp_ajax_nopriv_mk_get_icons_list', array(
				&$this,
				'get_icons_list',
			)
		);

		add_action(
			'wp_ajax_mk_search_icons_by_name', array(
				&$this,
				'search_icon_by_name',
			)
		);
		add_action(
			'wp_ajax_nopriv_mk_search_icons_by_name', array(
				&$this,
				'search_icon_by_name',
			)
		);

		$this->set_ajax_mode( $ajax_mode );
		$this->set_system_test_env( $system_test_env );
	}
	/**
	 * This method is responsible to get all list of icons based on icon family
	 * With pagination
	 * Icons list with be returned in alphabet order
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @since 5.5
	 *
	 * @param string $icon_family  Icon family should be valued before
	 *                             running this method with $this->set_icon_family();
	 *
	 * @param int    $pagination_start     Start point of pagination
	 *                                     should be valued with $this->set_pagination_start();
	 *
	 * @param int    $pagination_count     Count number of records in pagination
	 *                                     should be valued with $this->set_pagination_count();
	 *                                     Set pagination_count -1 will disable pagination and return all records.
	 *
	 * @return bool
	 */

	public function get_icons_db( $sort_alphabetically = true ) {
		$pagination_start = $this->get_pagination_start();
		$pagination_count = $this->get_pagination_count();

		// Check pagination's start point
		if ( is_null( $pagination_start ) || is_numeric( $pagination_start ) === false || $pagination_start < 0 ) {
			throw new Exception( __( 'Pagination start point is not valued correctly.' , 'mk_framework' ) );
			return false;
		}

		// Check pagination's count point
		if ( is_null( $pagination_count ) || is_numeric( $pagination_count ) === false || ($pagination_count < 1 && $pagination_count != -1) ) {
			throw new Exception( __( 'Pagination count point is not valued correctly.' , 'mk_framework' ) );
			return false;
		}

		// Check if icon's family is valued
		if ( $this->get_icon_family() == '' || $this->get_icon_family() == null ) {
			throw new Exception( __( 'Please choose an icon family.' , 'mk_framework' ) );
			return false;
		}
		// Check if selected icon's family is exist.
		if ( $this->get_icon_family() !== 'all' && array_search( $this->get_icon_family() , self::$font_families ) === false ) {
			throw new Exception( __( 'Icon family that you choosed is not exist.' , 'mk_framework' ) );
			return false;
		}
		$icon_family = array( $this->get_icon_family() );
		if ( $this->get_icon_family() == 'all' ) {
			$icon_family = self::$font_families;
		}

		// Getting icon's map.json for all of them or one specific family
		$icons_map = [];
		foreach ( $icon_family as $key => $value ) {
			$map_json_address = '/assets/icons/' . $value ;
			$map_json_uri = get_template_directory_uri() . $map_json_address . '/map.json';
			$map_json_dir = get_template_directory() . $map_json_address . '/map.json';

			if ( file_exists( $map_json_dir ) === false ) {
				throw new Exception( __( 'Font family icon datbase is not exist.' , 'mk_framework' ) );
				return false;
			}

			$map_json_response = @json_decode( $this->remote_get( $map_json_uri, $map_json_dir ), true );
			$icons_map = array_merge( $icons_map , $map_json_response );
		}

		if ( is_array( $icons_map ) === false || count( $icons_map ) < 1 ) {
				throw new Exception( __( 'Font family icon datbase have wrong structure , Call support.' , 'mk_framework' ) );
				return false;
		}

		// Sorting
		if ( $sort_alphabetically == true ) {
			ksort( $icons_map );
		}

		// Paginate
		if ( $pagination_count > 0 && $pagination_count != -1 ) {
			$icons_map = array_splice( $icons_map, $pagination_start, $pagination_count );
		}

		return $icons_map;
	}

	/**
	 * This method is responsible to get list of icons and their source based on icon family
	 * With Pagination
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @since 5.5
	 *
	 * @param string $icon_family  Icon family should be valued before
	 *                             running this method with $this->set_icon_family();
	 *
	 * @param int    $pagination_start     Start point of pagination
	 *                                     should be valued with $this->set_pagination_start();
	 *
	 * @param int    $pagination_count     Count number of records in pagination
	 *                                     should be valued with $this->set_pagination_count();
	 *                                     Set pagination_count -1 will disable pagination and return all records.
	 *
	 * @return bool
	 */

	public function get_icons_list() {
		try {

			$pagination_start = @$_POST['pagination_start'];
			$pagination_count = @$_POST['pagination_count'];
			$icon_family = @$_POST['icon_family'];

			if ( is_null( $pagination_start ) || is_numeric( $pagination_start ) === false || $pagination_start < 0 ) {
				throw new Exception( __( 'Pagination start point is not valued correctly.' , 'mk_framework' ) );
			}
			if ( is_null( $pagination_count ) || is_numeric( $pagination_count ) === false || ($pagination_count < 1 && $pagination_count != -1) ) {
				throw new Exception( __( 'Pagination count point is not valued correctly.' , 'mk_framework' ) );
			}
			if ( empty( $icon_family ) ) {
				throw new Exception( __( 'Icon family is not valued correctly.' , 'mk_framework' ) );
			}

			$this->set_pagination_start( $pagination_start );
			$this->set_pagination_count( $pagination_count );
			$this->set_icon_family( $icon_family );
			// if any error came up from get_icons_db this will return false
			// error messages contain in message already
						$response = $this->get_icons_db();
			foreach ( $response as $key => $icons_class ) {
				$response[ $key ] = self::get_svg_icon_by_class_name( false , $key );
			}

			$this->message( __( 'Successful' , 'mk_framework' ), true, $response );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}
	}

	/**
	 * This method is responsible to search icons by name
	 * With Pagination
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 * @since 5.5
	 *
	 * @param string $icon_name  Icon name that needed for search in icon classes
	 *
	 * @param int    $pagination_start     Start point of pagination
	 *                                     should be valued with $this->set_pagination_start();
	 *
	 * @param int    $pagination_count     Count number of records in pagination
	 *                                     should be valued with $this->set_pagination_count();
	 *                                     Set pagination_count -1 will disable pagination and return all records.
	 *
	 * @return bool
	 */
	public function search_icon_by_name() {
		try {
			$pagination_start = @$_POST['pagination_start'];
			$pagination_count = @$_POST['pagination_count'];
			$icon_name = @$_POST['icon_name'];
			if ( is_null( $pagination_start ) || is_numeric( $pagination_start ) === false || $pagination_start < 0 ) {
				throw new Exception( __( 'Pagination start point is not valued correctly.' , 'mk_framework' ) );
			}
			if ( is_null( $pagination_count ) || is_numeric( $pagination_count ) === false || ($pagination_count < 1 && $pagination_count != -1) ) {
				throw new Exception( __( 'Pagination count point is not valued correctly.' , 'mk_framework' ) );
			}
			if ( empty( $icon_name ) ) {
				throw new Exception( __( 'Icon name is not valued correctly.' , 'mk_framework' ) );
			}

			$this->set_icon_name( $icon_name );

			if ( strpos( $this->get_icon_name(), 'mk-' ) !== false ) {
				foreach ( self::$font_families as $key => $icon_family_item ) {
					if ( strpos( $this->get_icon_name(), $key ) !== false ) {
						$icon_family = $icon_family_item;
						break;
					}
				}
				if ( empty( $icon_family ) ) {
					$icon_family = 'all';
				}
			} else {
				$icon_family = 'all';
			}

			// Set Pagination and icon family for get_icons_db to get all icons ...
			// If we set pagination count as 0 it will return all the icon's
			$this->set_pagination_start( 0 );
			$this->set_pagination_count( -1 );
			$this->set_icon_family( $icon_family );

			$icons_db = $this->get_icons_db();
			$searchword = $this->get_icon_name();
			$matches_icon = array_flip( preg_grep( "/\b$searchword\b/i", array_keys( $icons_db ) ) );

			if ( is_array( $matches_icon ) === false || count( $matches_icon ) < 1 ) {
				$this->message( __( 'Successful' , 'mk_framework' ), true, array() );
				return true;
			}

			foreach ( $matches_icon as $icons_class => $icons_value ) {
				$matches_icon[ $icons_class ] = self::get_svg_icon_by_class_name( false , $icons_class );
			}

			// Set for current method
			$this->set_pagination_start( $pagination_start );
			$this->set_pagination_count( $pagination_count );
			if ( $this->get_pagination_count() > 0 && $this->get_pagination_count() != -1 ) {
				$matches_icon = array_splice( $matches_icon, $this->get_pagination_start(), $this->get_pagination_count() );
			}
			$this->message( __( 'Successful' , 'mk_framework' ), true, $matches_icon );
			return true;

		} catch ( Exception $e ) {
			$this->message( $e->getMessage(), false );
			return false;
		}// End try().
	}



	/**
	 * Safely and securely get file from server.
	 * It attempts to read file using WordPress native file read functions
	 * If it fails, we use wp_remote_get. if the site is ssl enabled, we try to convert it http as some servers may fail to get file
	 *
	 * @param $file_uri         string    its directory URI
	 * @return $wp_file_body    string
	 */
	private static function remote_get( $file_uri, $file_dir ) {

		$mkfs = new Mk_Fs(
			array(
				'context' => THEME_DIR . '/assets/stylesheet/min',
			)
		);

		$wp_get_file_body = $mkfs->get_contents( $file_dir );

		if ( $wp_get_file_body == false ) {
			$wp_remote_get_file = wp_remote_get( $file_uri );

			if ( is_array( $wp_remote_get_file ) and array_key_exists( 'body', $wp_remote_get_file ) ) {
				$wp_remote_get_file_body = $wp_remote_get_file['body'];

			} elseif ( is_numeric( strpos( $file_uri, 'https://' ) ) ) {

				$file_uri           = str_replace( 'https://', 'http://', $file_uri );
				$wp_remote_get_file = wp_remote_get( $file_uri );

				if ( ! is_array( $wp_remote_get_file ) or ! array_key_exists( 'body', $wp_remote_get_file ) ) {
					echo 'SSL connection error. Code: icon-get';
					die;
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
	 * get SVG friendly directions
	 *
	 * @param $direction    string
	 * @return string
	 */
	static function get_gradient_cords( $direction ) {
		switch ( $direction ) {
			case 'horizontal':
			case 'right':
				return 'x1="0%" y1="0%" x2="100%" y2="0%"';
			case 'vertical':
			case 'bottom':
				return 'x1="0%" y1="100%" x2="0%" y2="0%"';
			case 'diagonal_left_bottom':
			case 'right-bottom':
				return 'x1="0%" y1="100%" x2="100%" y2="0%"';
			case 'diagonal_left_top':
			case 'right-top':
				return 'x1="0%" y1="0%" x2="100%" y2="100%"';
			default:
				return 'x1="0%" y1="100%" x2="0%" y2="0%"';
		}
	}


	static function get_width( $svg, $h ) {
		preg_match_all( '`"([^"]*)"`', $svg, $m );
		$vb = $m[1][1];
		$vb_arr = explode( ' ', $vb );
		$natural_width = isset( $vb_arr[2] ) ? floatval( $vb_arr[2] ) : false;
		$natural_height = isset( $vb_arr[3] ) ? floatval( $vb_arr[3] ) : false;

		$p = ($natural_height > 0 && $h > 0) ? ($natural_height / $h) : false;
		$width = ($p != 0 && $natural_width != 0) ? ($natural_width / $p) : false;

		return $width;
	}



	/**
	 * Find first occurrence of the given param and replace
	 *
	 * @param $search   string
	 * @param $replace  string
	 * @param $subject  string
	 * @return string
	 */
	static function str_replace_first( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );
		if ( $pos !== false ) {
			return substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}
		return $subject;
	}


	/**
	 * Get the font icon unicode by providing the name and font family
	 *
	 * @param $name         string       (e.g. mk-moon-phone-3)
	 * @param $family       string       (awesome-icons, icomoon, pe-line-icons, theme-icons)
	 * @return $unicode     string/boolean
	 */
	public function get_class_name_by_unicode( $family, $unicode ) {

				$file_path = '/assets/icons/' . $family;
		$file_uri = get_template_directory_uri() . $file_path . '/map.json';
		$file_dir     = get_template_directory() . $file_path . '/map.json';

		if ( file_exists( $file_dir ) ) {
			$map = json_decode( $this->remote_get( $file_uri, $file_dir ), true );
			return array_search( $unicode, $map );
		}

		return false;
	}




	/**
	 * Get the font icon unicode by providing the name and font family
	 *
	 * @param $name         string       (e.g. mk-moon-phone-3)
	 * @param $family       string       (awesome-icons, icomoon, pe-line-icons, theme-icons)
	 * @return $unicode     string/boolean
	 */
	static function get_unicode( $name, $family ) {

		$file_path = '/assets/icons/' . $family;
		$file_uri = get_template_directory_uri() . $file_path . '/map.json';
		$file_dir     = get_template_directory() . $file_path . '/map.json';

		if ( file_exists( $file_dir ) ) {
			$map = json_decode( self::remote_get( $file_uri, $file_dir ), true );
			if ( isset( $map[ $name ] ) ) {
				return $map[ $name ];
			}
		}

		return false;
	}



	/**
	 * Get the SVG content by given font family and unicode
	 *
	 * @param $unicode      string       (e.g. e47e)
	 * @param $family       string       (awesome-icons, icomoon, pe-line-icons, theme-icons)
	 * @return string/boolean
	 */
	static function get_svg_content( $family, $unicode ) {
		$file_path = '/assets/icons/' . $family . '/svg/' . $unicode . '.svg';
		$file_uri = get_template_directory_uri() . $file_path;
		$file_dir     = get_template_directory() . $file_path;

		if ( file_exists( $file_dir ) ) {
			$file_content = self::remote_get( $file_uri, $file_dir );
			return $file_content;
		}

		return false;

	}




	/**
	 * Function to get the svg icon content send via ajax. This function is hooked into a WP native ajax action.
	 *
	 * $config is an encoded array of objects which contains font info. Sample $config of one icon:
	 *  Encoded: %5B%7B%22family%22%3A%22awesome-icons%22%2C%22unicode%22%3Afalse%2C%22name%22%3A%22mk-icon-chevron-down%22%2C%22gradient_type%22%3Afalse%2C%22gradient_start%22%3Afalse%2C%22gradient_stop%22%3Afalse%2C%22gradient_direction%22%3Afalse%2C%22height%22%3A%2216px%22%2C%22id%22%3A0%7D%5D
	 *  Decoded: Array
	 *          (
	 *              [0] => Array
	 *              (
	 *                  [family] => awesome-icons
	 *                  [unicode] =>
	 *                  [name] => mk-icon-chevron-down
	 *                  [gradient_type] =>
	 *                  [gradient_start] =>
	 *                  [gradient_stop] =>
	 *                  [gradient_direction] =>
	 *                  [height] => 16px
	 *                  [id] => 0
	 *              )
	 *          )
	 */
	public function get_icons() {

		$config             = $_POST['config'];

		$family             = isset( $c['family'] ) ? $c['family'] : '';
		$name               = isset( $c['name'] ) ? $c['name'] : '';
		$unicode            = isset( $c['unicode'] ) ? $c['unicode'] : '';
		$height             = isset( $c['height'] ) ? $c['height'] : '';
		$fill               = isset( $c['fill'] ) ? $c['fill'] : '';
		$gradient_type      = isset( $c['gradient_type'] ) ? $c['gradient_type'] : '';
		$gradient_direction = isset( $c['gradient_direction'] ) ? $c['gradient_direction'] : '';
		$gradient_start     = isset( $c['gradient_start'] ) ? $c['gradient_start'] : '';
		$gradient_stop      = isset( $c['gradient_stop'] ) ? $c['gradient_stop'] : '';
		$id                 = isset( $c['id'] ) ? $c['id'] : '';

		if ( ! empty( $config ) ) {
			$config = json_decode( urldecode( $config ), true );
			foreach ( $config as $c ) {
				$this->get_svg_icon(
					true, $family, $name, $unicode, $height, $fill, $gradient_type,
					$gradient_direction, $gradient_start, $gradient_stop, $id
				);

				/**
				 * You could use get_svg_icon_by_class_name, too:
				 *
				 *   $this->get_svg_icon_by_class_name($c['name'], $c['height'], $c['fill'], $c['gradient_type'],
				 *   $c['gradient_direction'], $c['gradient_start'], $c['gradient_stop'], $c['id']);
				 */
			}
		}

		wp_die();
	}

	/**
	 * Function to generate a svg and print it by class name;
	 *
	 * @param $echo                 boolean     Check return type. it $echo was true, we will print svg.
	 * @param $name                 string      (e.g. mk-moon-phone-3)
	 * @param $height               string      The desired height of generated svg. the width will be generated by this.
	 * @param $fill                 string      sets the color inside the object (e.g. green)
	 * @param $gradient_type        string      (e.g. linear, radial)
	 * @param $gradient_direction   string      Direction of gradient. (e.g. right, right-bottom)
	 * @param $gradient_start       string      Gradient start color. (e.g. white)
	 * @param $gradient_stop        string      Gradient stop color. (e.g. red)
	 * @param $id                   string      Id
	 * @return string. return generated svg content.
	 */
	static function get_svg_icon_by_class_name( $echo, $name, $height = null, $fill = null, $gradient_type = null, $gradient_direction = null,
											   $gradient_start = null, $gradient_stop = null, $id = null ) {
		if ( ! empty( $name ) ) {
			foreach ( self::$font_families as $prefix => $font_family ) {
				if ( strpos( $name, $prefix ) !== false ) {
					return self::get_svg_icon(
						$echo, $font_family, $name, null, $height, $fill, $gradient_type, $gradient_direction, $gradient_start,
						$gradient_stop, $id
					);
					break;
				}
			}
		}

				return '';
	}

	/**
	 * Function to generate a svg and print it;
	 *
	 * @param $echo                 boolean     Check return type. it $echo was true, we will print svg.
	 * @param $family               string      (awesome-icons, icomoon, pe-line-icons, theme-icons)
	 * @param $name                 string      (e.g. mk-moon-phone-3)
	 * @param $unicode              string      (e.g. e47e)
	 * @param $height               string      The desired height of generated svg. the width will be generated by this.
	 * @param $fill                 string      sets the color inside the object (e.g. green)
	 * @param $gradient_type        string      (e.g. linear, radial)
	 * @param $gradient_direction   string      Direction of gradient. (e.g. right, right-bottom)
	 * @param $gradient_start       string      Gradient start color. (e.g. white)
	 * @param $gradient_stop        string      Gradient stop color. (e.g. red)
	 * @param $id                   string      Id
	 * @return string. return generated svg content.
	 */
	static function get_svg_icon( $echo, $family, $name, $unicode, $height, $fill, $gradient_type, $gradient_direction, $gradient_start,
						  $gradient_stop, $id ) {
		// Use unique ID for plumbing any leaks in advance
		// $id = uniqid(); Unused local variable 'id'. The value of the variable is overwritten immediately.
		// Check if unicode sent to the function, if not we will figure it out via get_unicode method.
		$unicode = ! empty( $unicode ) ? : self::get_unicode( $name, $family );

		// Get the SVG icon content
		$svg = self::get_svg_content( $family, $unicode );

		// Return if svg does not exist.
		if ( empty( $svg ) ) {
			return '';
		} else {
			if ( empty( $id ) ) {
				$id = uniqid( 'icon-' ); // We need to set id to set gradient path
			}

						// Append SVG attributes
			self::append_svg_attributes( $svg, $id, $name, $fill, $height );

			// Append SVG Gradient
			self::append_svg_gradient( $svg, $id, $gradient_type, $gradient_direction, $gradient_start, $gradient_stop );

			// Append SVG Path attributes
			self::append_svg_path_attributes( $svg, $id, $gradient_type );

			// Print
			if ( $echo ) {
				echo $svg;
			}

			return $svg;        }
	}

	/**
	 * Function to get svg attributes.
	 *
	 * @param $svg                  string      SVG file for calculating width.
	 * @param $id                   string      Id
	 * @param $name                 string      (e.g. mk-moon-phone-3)
	 * @param $fill                 string      sets the color inside the object (e.g. green)
	 * @param $height               string      The desired height of generated svg. the width will be generated by this.
	 * @return string attributes.
	 */
	private static function get_svg_attributes( $svg, $id, $name, $fill, $height ) {
		$atts = '';
		$atts .= ' class="mk-svg-icon"';
		$atts .= ' data-name="' . $name . '"';
		$atts .= ' data-cacheid="' . $id . '"';

		$style = '';

		if ( $fill ) {
			// We need to set fill in style instead of fill attribute because of css priority;
			$style .= ' fill: ' . $fill . '; ';
		}

		if ( $height ) {
			$width = self::get_width( $svg, $height );

			// In Ajax requests, height is set with px unit bu in backend we just set integer value. So, to prevent
			// showing something like 16pxpx, we need to check if height contains unit or not.
			if ( strpos( $height, 'px' ) === false ) {
				$height .= 'px';
			}

			$style .= ' height:' . $height . '; width: ' . $width . 'px; ';
		}

		if ( ! empty( $style ) ) {
			$atts .= ' style="' . $style . '" ';
		}

		return $atts;
	}

	/**
	 * Function to append attributes to svg.
	 *
	 * @param $svg                  string      reference of svg string value.
	 * @param $id                   string      Id
	 * @param $name                 string      (e.g. mk-moon-phone-3)
	 * @param $fill                 string      sets the color inside the object (e.g. green)
	 * @param $height               string      The desired height of generated svg. the width will be generated by this.
	 */
	private static function append_svg_attributes( &$svg, $id, $name, $fill, $height ) {
		$atts = self::get_svg_attributes( $svg, $id, $name, $fill, $height );

			// Insert attributes and defs
		if ( ! empty( $atts ) ) {
			$svg = self::str_replace_first( '<svg', '<svg ' . $atts, $svg );
		}
	}

	/**
	 * Function to generate gradient tags of svg.
	 *
	 * @param $id                   string      Id
	 * @param $gradient_type        string      (e.g. linear, radial)
	 * @param $gradient_direction   string      Direction of gradient. (e.g. right, right-bottom)
	 * @param $gradient_start       string      Gradient start color. (e.g. white)
	 * @param $gradient_stop        string      Gradient stop color. (e.g. red)
	 * @return string gradient defs.
	 */
	private static function get_svg_gradient( $id, $gradient_type, $gradient_direction, $gradient_start, $gradient_stop ) {
		$defs = '';

		if ( $gradient_type == 'linear' ) {
			$cords = self::get_gradient_cords( $gradient_direction );
			$defs .= '<linearGradient id="gradient-' . $id . '" ' . $cords . '><stop offset="0%"  stop-color="' . $gradient_start . '"/><stop offset="100%" stop-color="' . $gradient_stop . '"/></linearGradient>';
		} elseif ( $gradient_type == 'radial' ) {
			$defs .= '<radialGradient id="gradient-' . $id . '"><stop offset="0%"  stop-color="' . $gradient_start . '"/><stop offset="100%" stop-color="' . $gradient_stop . '"/></radialGradient>';
		}

		return $defs;
	}

	/**
	 * Function to append gradient defs to svg.
	 *
	 * @param $svg                  string      reference of svg string value.
	 * @param $id                   string      Id
	 * @param $gradient_type        string      (e.g. linear, radial)
	 * @param $gradient_direction   string      Direction of gradient. (e.g. right, right-bottom)
	 * @param $gradient_start       string      Gradient start color. (e.g. white)
	 * @param $gradient_stop        string      Gradient stop color. (e.g. red)
	 */
	private static function append_svg_gradient( &$svg, $id, $gradient_type, $gradient_direction, $gradient_start, $gradient_stop ) {
		// Prepare gradient defs
		$defs = self::get_svg_gradient( $id, $gradient_type, $gradient_direction, $gradient_start, $gradient_stop );

		// wrap with tags
		$defs = ! empty( $defs ) ? '<defs>' . $defs . '</defs>' : '';

		if ( ! empty( $defs ) ) {
			$svg = self::str_replace_first( '>', '>' . $defs, $svg );
		}
	}

	/**
	 * Function to generate path attributes
	 *
	 * @param $id                   string      Id
	 * @param $gradient_type        string      (e.g. linear, radial)
	 * @return string
	 */
	private static function get_svg_path_attributes( $id, $gradient_type ) {
		// Prepare PATH attributes
		$path_atts = $gradient_type ? (' fill="url(#gradient-' . $id . ')"') : '';
		return $path_atts;
	}

	/**
	 * Function to append path attributes to svg.
	 *
	 * @param $svg
	 * @param $id
	 * @param $gradient_type
	 */
	private static function append_svg_path_attributes( &$svg, $id, $gradient_type ) {
		$path_atts = self::get_svg_path_attributes( $id, $gradient_type );

		if ( ! empty( $path_atts ) ) {
			$svg = self::str_replace_first( '<path', '<path ' . $path_atts, $svg );
		}
	}

	/**
	 * This method is responsible to manage all class messages.
	 *
	 * @author Reza Marandi <ross@artbees.net>
	 *
	 * @param string $message Message that will return to front-end
	 * @param string $status This status is when action was succesfull or not
	 * @param string $data If class want to return a data , it can be through this arg
	 *
	 * @return json will echo a json of responses and then wp_die();
	 */

	public function message( $message = '', $status = false, $data = array() ) {
		$return_response = array(
			'status'  => $status,
			'message' => $message,
			'data'    => $data,
		);
		if ( $this->get_ajax_mode() === true ) {
			if ( $this->get_system_test_env() === false ) {
				header( 'Content-Type: application/json' );
			}
			wp_die( json_encode( $return_response ) );
		} else {
			$this->set_message( $return_response );
		}

		return $this;
	}
}

global $abb_phpunit;
if ( empty( $abb_phpunit ) === false || $abb_phpunit === true ) {
	new Mk_SVG_Icons( true , true );
} else {
	new Mk_SVG_Icons();
}
