<?php

/**
 * Whether the current request is in theme options pages
 *
 * @param mixed $post_types
 * @return bool True if inside theme options pages.
 */
function mk_theme_is_options() {
	if ( 'admin.php' == basename( $_SERVER['PHP_SELF'] ) ) {
		return true;
	}
	return false;
}
function mk_theme_is_menus() {
	if ( 'nav-menus.php' == basename( $_SERVER['PHP_SELF'] ) ) {
		return true;
	}

	return false;
}

function mk_theme_is_themes() {

	if ( 'themes.php' == basename( $_SERVER['PHP_SELF'] ) ) {
		return true;
	}

	return false;
}

function mk_theme_is_widgets() {
	if ( 'widgets.php' == basename( $_SERVER['PHP_SELF'] ) ) {
		return true;
	}

	return false;
}
function mk_theme_is_masterkey() {
	$theme_options_slugs = array( 'theme_options' );

	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $theme_options_slugs ) ) {
		return true;
	}
	return false;
}
function mk_is_control_panel() {
	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( THEME_NAME ) ) ) {
		return true;
	}
	return false;
}

/**
 * Whether the current request is in post type pages
 *
 * @param mixed $post_types
 * @return bool True if inside post type pages
 */
function mk_theme_is_post_type( $post_types = '' ) {
	if ( mk_theme_is_post_type_list( $post_types ) || mk_theme_is_post_type_new( $post_types ) || mk_theme_is_post_type_edit( $post_types ) || mk_theme_is_post_type_post( $post_types ) || mk_theme_is_post_type_taxonomy( $post_types ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Whether the current request is in post type list page
 *
 * @param mixed $post_types
 * @return bool True if inside post type list page
 */
function mk_theme_is_post_type_list( $post_types = '' ) {
	if ( 'edit.php' != basename( $_SERVER['PHP_SELF'] ) ) {
		return false;
	}
	if ( '' == $post_types ) {
		return true;
	} else {
		$check = isset( $_GET['post_type'] ) ? $_GET['post_type'] : (isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post');
		if ( is_string( $post_types ) && $check == $post_types ) {
			return true;
		} elseif ( is_array( $post_types ) && in_array( $check, $post_types ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Whether the current request is in post type new page
 *
 * @param mixed $post_types
 * @return bool True if inside post type new page
 */
function mk_theme_is_post_type_new( $post_types = '' ) {
	if ( 'post-new.php' != basename( $_SERVER['PHP_SELF'] ) ) {
		return false;
	}
	if ( '' == $post_types ) {
		return true;
	} else {
		$check = isset( $_GET['post_type'] ) ? $_GET['post_type'] : (isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post');
		if ( is_string( $post_types ) && $check == $post_types ) {
			return true;
		} elseif ( is_array( $post_types ) && in_array( $check, $post_types ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Whether the current request is in post type post page
 *
 * @param mixed $post_types
 * @return bool True if inside post type post page
 */
function mk_theme_is_post_type_post( $post_types = '' ) {
	if ( 'post.php' != basename( $_SERVER['PHP_SELF'] ) ) {
		return false;
	}
	if ( $post_types == '' ) {
		return true;
	} else {
		$post = isset( $_GET['post'] ) ? $_GET['post'] : (isset( $_POST['post'] ) ? $_POST['post'] : false);
		$check = get_post_type( $post );
		if ( is_string( $post_types ) && $check == $post_types ) {
			return true;
		} elseif ( is_array( $post_types ) && in_array( $check, $post_types ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Whether the current request is in post type edit page
 *
 * @param mixed $post_types
 * @return bool True if inside post type edit page
 */
function mk_theme_is_post_type_edit( $post_types = '' ) {
	if ( 'post.php' != basename( $_SERVER['PHP_SELF'] ) ) {
		return false;
	}
	$action = isset( $_GET['action'] ) ? $_GET['action'] : (isset( $_POST['action'] ) ? $_POST['action'] : '');
	if ( 'edit' != $action ) {
		return false;
	}
	if ( $post_types == '' ) {
		return true;
	} else {
		$post = isset( $_GET['post'] ) ? $_GET['post'] : (isset( $_POST['post'] ) ? $_POST['post'] : false);
		$check = get_post_type( $post );
		if ( is_string( $post_types ) && $check == $post_types ) {
			return true;
		} elseif ( is_array( $post_types ) && in_array( $check, $post_types ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Whether the current request is in post type taxonomy pages
 *
 * @param mixed $post_types
 * @return bool True if inside post type taxonomy pages
 */
function mk_theme_is_post_type_taxonomy( $post_types = '' ) {
	if ( 'edit-tags.php' != basename( $_SERVER['PHP_SELF'] ) ) {
		return false;
	}
	if ( '' == $post_types ) {
		return true;
	} else {
		$check = isset( $_GET['post_type'] ) ? $_GET['post_type'] : (isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post');
		if ( is_string( $post_types ) && $check == $post_types ) {
			return true;
		} elseif ( is_array( $post_types ) && in_array( $check, $post_types ) ) {
			return true;
		}
		return false;
	}
}

function mk_assoc_to_pairs( array $assoc = array() ) {
	if ( empty( $assoc ) ) {
			return $assoc;
	}

		$pairs = array();

	foreach ( $assoc as $key => $value ) {
		$pairs[] = array( $key, $value );
	}

		return $pairs;
}

/**
 * Add SVG support for WordPress.
 */
add_action(
	'upload_mimes', function( $mimes ) {
		if ( ! current_user_can( 'administrator' ) ) {
			return $mimes;
		}

		global $mk_options;

		if ( empty( $mk_options['svg_support'] ) ) {
			return $mimes;
		}

		if ( 'false' === $mk_options['svg_support'] ) {
			return $mimes;
		}

		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
);

/**
 * Removes premium plugin update notifications.
 */
function mk_remove_update_notifications( $value ) {

	if ( isset( $value ) && is_object( $value ) ) {
		unset( $value->response['js_composer_theme/js_composer.php'] );
		unset( $value->response['revslider/revslider.php'] );
		unset( $value->response['masterslider/masterslider.php'] );
		unset( $value->response['LayerSlider/layerslider.php'] );
	}
	return $value;
}
add_filter( 'site_transient_update_plugins', 'mk_remove_update_notifications' );
