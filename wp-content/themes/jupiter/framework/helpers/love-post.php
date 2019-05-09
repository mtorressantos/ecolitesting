<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Adds love to the posts used in various post types.
 *
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @version     4.0
 * @package     artbees
 * @deprecated since V6.0
 * @removed since V6.1.4
 */


class Mk_Love_Post {

	function __construct() {

	}

	function action_triger( $post_id ) {

	}

	static function love_post( $post_id, $action = 'get' ) {

	}
	
	static function send_love( $icon = 'mk-icon-heart' ) {

	}
}
new Mk_Love_Post();


