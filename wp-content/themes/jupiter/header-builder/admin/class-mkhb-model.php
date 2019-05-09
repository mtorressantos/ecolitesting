<?php
/**
 * Header Builder: MKHB_Model class.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * Class file for handling data management request from the HB admin page.
 *
 * @since 6.0.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * - The class MKHB_Model has an overall complexity of 51 which is very high. The configured
 *   complexity threshold is 50.
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * - The class HB_Model has 1011 lines of code. Current threshold is 1000. Avoid really long
 *   classes.
 */
class MKHB_Model {
	/**
	 * Constructor.
	 *
	 * @since 6.0.0
	 *
	 * Create custom handlers for your own custom AJAX requests.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mkhb_save_header_revision', array( &$this, 'save_header_revision' ) );
		add_action( 'wp_ajax_mkhb_set_header', array( &$this, 'set_header' ) );
		add_action( 'wp_ajax_mkhb_get_header', array( &$this, 'get_header' ) );
		add_action( 'wp_ajax_mkhb_get_headers', array( &$this, 'get_headers' ) );
		add_action( 'wp_ajax_mkhb_clone_header', array( &$this, 'clone_header' ) );
		add_action( 'wp_ajax_mkhb_delete_header', array( &$this, 'delete_header' ) );
		add_action( 'wp_ajax_mkhb_set_global_header', array( &$this, 'set_global_header' ) );
		add_action( 'wp_ajax_mkhb_check_title_header', array( &$this, 'check_title_header' ) );
		add_action( 'save_post', array( &$this, 'page_styling_meta_update' ) );
	}

	/**
	 * Save a revision of the header post.
	 *
	 * This function is intended to be called on two occasions:
	 *   (1) every tick of a WP heartbeat (i.e., autosave);
	 *   (2) every time a user clicks the "Preview" button.
	 *
	 * @see mkhb_header_preview()
	 *
	 * @since 6.0.3
	 */
	public function save_header_revision() {
		$preview_link = mkhb_header_preview();

		if ( is_wp_error( $preview_link ) ) {
			wp_send_json_error( $preview_link->get_error_message() );
		}

		wp_send_json_success( $preview_link );
	}

	/**
	 * Save Header data as a post and post metas.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If insert process is error, return WordPress error message.
	 */
	public function set_header() {
		try {
			check_ajax_referer( 'mkhb-set-header', 'nonce_mkhb_set' );

			// Default parameter.
			$params = array(
				'post_type' => 'mkhb_header',
				'post_status' => 'publish',
			);

			$post_data = $_POST;
			$post_data = _mkhb_translate_header_post_data( $post_data );

			// UPDATE - Check if post id is not null, it means update.
			if ( ! empty( $post_data['post_id'] ) ) {
				$params['ID'] = absint( $post_data['post_id'] );
				$data = get_post( $params['ID'] );
				if ( ! empty( $data->post_title ) ) {
					$params['post_title'] = $data->post_title;
				}
			}

			// RENAME - Check if post title is not null.
			if ( ! empty( $post_data['title'] ) ) {
				$params['post_title'] = sanitize_text_field( $post_data['title'] );
			}

			// SAVE CONTENT - Check if post meta is not empty.
			if ( ! empty( $post_data['metas'] ) ) {
				$params['meta_input'] = $post_data['metas'];
			}

			// Save the data into post mkhb_header.
			$result = wp_insert_post( $params );

			// Check if the saving process is failed.
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}

			$return = array(
				'message' => 'Header data stored successfully.',
				'post_id' => $result,
			);

			// If we need to return list of header post after saving the data.
			if ( ! empty( $post_data['retrieve'] ) ) {
				$return['list'] = $this->get_headers_data( 'title_id' );
			}

			wp_send_json_success( $return );
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Set an Header as global header.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header post request is not exist, return to check.
	 * @throws Exception If header ID is empty, return to check.
	 * @throws Exception If saving process is error, return WordPress error message.
	 */
	public function set_global_header() {
		try {
			check_ajax_referer( 'mkhb-set-global-header', 'nonce_mkhb_set_global' );

			if ( ! isset( $_POST['post_id'] ) ) {
				throw new Exception( 'Header ID is not exist, please check!' );
			}

			$post_id = absint( $_POST['post_id'] );

			if ( empty( $post_id ) ) {
				throw new Exception( 'Header ID is empty, please check!' );
			}

			// Check the action is remove or not. If POST remove is 1, it means remove.
			$remove = 0;
			if ( ! empty( $_POST['remove'] ) ) {
				$remove = absint( $_POST['remove'] );
			}

			$global_id = get_option( 'mkhb_global_header', 0 );

			// REMOVE - If it's remove and current header ID is Global Header, set ID to null.
			if ( 1 === $remove && $global_id === $post_id ) {
				$post_id = '';
			}

			// Update new ID.
			update_option( 'mkhb_global_header', $post_id );

			$post_list = $this->get_headers_data( 'title_id' );

			wp_send_json_success(
				array(
					'message' => 'Global Header updated successfully.',
					'list' => $post_list,
				)
			);

		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Get Header data from specific post and post metas.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header post request is not exist, return to check.
	 * @throws Exception If header ID is empty, return to check.
	 * @throws Exception If header not exist, return empty header.
	 */
	public function get_header() {
		try {
			check_ajax_referer( 'mkhb-get-header', 'nonce_mkhb_get' );

			if ( ! isset( $_POST['post_id'] ) ) {
				throw new Exception( 'Header ID is not exist, please check!' );
			}

			$post_id = absint( $_POST['post_id'] );

			if ( empty( $post_id ) ) {
				throw new Exception( 'Header ID is empty, please check!' );
			}

			$return = $this->get_header_data( $post_id );

			if ( empty( $return ) ) {
				throw new Exception( 'Header is not exist!' );
			}

			$return['message'] = 'Header data fetched successfully.';

			wp_send_json_success( $return );

		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Get Headers list.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header not exist, return false status.
	 */
	public function get_headers() {
		try {
			check_ajax_referer( 'mkhb-get-headers', 'nonce_mkhb_get_s' );

			// Get posts list.
			$post_list = $this->get_headers_data( 'title_id' );

			if ( empty( $post_list ) ) {
				throw new Exception( 'No header exist!' );
			}

			$return = array(
				'list' => $post_list,
				'message' => 'Headers list fetched successfully.',
			);

			wp_send_json_success( $return );

		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}


	/**
	 * Clone Header data as new post and post metas.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header post request is not exist, return to check.
	 * @throws Exception If header title or meta is empty, return to check.
	 * @throws Exception If insert process is error, return WordPress error message.
	 */
	public function clone_header() {
		try {
			check_ajax_referer( 'mkhb-clone-header', 'nonce_mkhb_clone' );

			if ( ! isset( $_POST['post_id'] ) ) {
				throw new Exception( 'Header ID is not exist, please check!' );
			}

			$post_id = absint( $_POST['post_id'] );

			if ( empty( $post_id ) ) {
				throw new Exception( 'Header ID is empty, please check!' );
			}

			// Get original post.
			$post = get_post( $post_id );
			if ( empty( $post ) ) {
				throw new Exception( "The header post doesn't exist!" );
			}

			// Get current user as new post author.
			$current_user = wp_get_current_user();
			$post_author = $current_user->ID;
			$meta_input = $this->get_meta_header( $post_id, 'clone' );

			// Set custom title.
			$ori_title = 'Copy of ' . $post->post_title;
			$custom_title = $ori_title;

			// Set suffix if needed.
			$title_exist = true;
			$suffix = '';
			$number = 0;
			while ( $title_exist ) {
				$custom_title = $ori_title . $suffix;

				// Get header ID.
				$header_id = post_exists( $custom_title );

				$title_exist = false;
				if ( 0 < $header_id ) {
					$number++;
					$suffix = ' (' . $number . ')';
					$title_exist = true;
				}
			}

			// Save the data into post mkhb_header.
			$params = array(
				'post_title' => $custom_title,
				'post_type' => 'mkhb_header',
				'post_status' => 'publish',
				'post_author' => $post_author,
				'meta_input' => $meta_input['post_meta'],
			);

			$result = wp_insert_post( $params );

			// Check if the saving process is failed.
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}

			// Get list and duplicated data.
			$post_list = $this->get_headers_data( 'title_id' );
			$post_cloned = $this->get_header_data( $result );

			wp_send_json_success(
				array(
					'message' => 'Header data cloned successfully.',
					'list' => $post_list,
					'cloned' => $post_cloned,
				)
			);
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Delete Header data both of post and post metas.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header post request is not exist, return to check.
	 * @throws Exception If header title or meta is empty, return to check.
	 * @throws Exception If insert process is error, return WordPress error message.
	 */
	public function delete_header() {
		try {
			check_ajax_referer( 'mkhb-delete-header', 'nonce_mkhb_delete' );

			if ( ! isset( $_POST['post_id'] ) ) {
				throw new Exception( 'Header ID is not exist, please check!' );
			}

			$post_id = absint( $_POST['post_id'] );

			if ( empty( $post_id ) ) {
				throw new Exception( 'Header ID is empty, please check!' );
			}

			$global_id = get_option( 'mkhb_global_header', 0 );
			$global_id = absint( $global_id );
			if ( $post_id === $global_id ) {
				throw new Exception( 'Delete global header is not allowed!' );
			}

			// Delete header by post ID permanently.
			$result = wp_delete_post( $post_id, true );

			// Check if the saving process is failed.
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}

			$post_list = $this->get_headers_data( 'title_id' );

			wp_send_json_success(
				array(
					'message' => 'Header data deleted successfully.',
					'list' => $post_list,
				)
			);
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Check if the header title is exist or not.
	 *
	 * @since 6.0.0
	 *
	 * @throws Exception If header post title is not exist, return to check.
	 * @throws Exception If header title is empty, return to check.
	 * @throws Exception If header is exist, return exist statement.
	 */
	public function check_title_header() {
		try {
			check_ajax_referer( 'mkhb-check-title-header', 'nonce_mkhb_check_title' );

			if ( ! isset( $_POST['title'] ) ) {
				throw new Exception( 'Header title is not exist, please check!' );
			}

			$title = sanitize_text_field( $_POST['title'] );

			if ( empty( $title ) ) {
				throw new Exception( 'Header title is empty, please check!' );
			}

			// Get header ID.
			$header_id = post_exists( $title );

			if ( 0 < $header_id ) {
				$header_post_type = get_post_type( $header_id );
				if ( 'mkhb_header' === $header_post_type ) {
					throw new Exception( 'Header title is already exist!' );
				}
			}

			$return = array(
				'title' => $title,
				'message' => 'Header title is not exist.',
			);

			wp_send_json_success( $return );

		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		} // End try().
	}

	/**
	 * Update header active_on meta after page saving process.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $post_id Current post ID.
	 */
	public function page_styling_meta_update( $post_id ) {
		// Check if post type exist or not.
		$post_type = get_post_type( $post_id );
		if ( ! $post_type ) {
			return false;
		}

		// Skip the process if the post type is in the ignored post type.
		$ignore_post_type = array( 'revision' );
		if ( in_array( $post_type, $ignore_post_type, true ) ) {
			return false;
		}

		// Go to delete post meta update if post status is changed to trash.
		$post_status = get_post_status( $post_id );
		if ( 'trash' === $post_status ) {
			$this->page_deleted_meta_update( $post_id );
			return true;
		}

		// Go to update/create post meta update as default.
		$this->page_updated_meta_update( $post_id );
	}

	/**
	 * Save current page ID into specific header after updated.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $post_id Current post ID.
	 */
	private function page_updated_meta_update( $post_id ) {
		// A. Check if hb_override_template_id is exist or not. If not, skip.
		if ( ! isset( $_POST['_hb_override_template_id'] ) ) { // WPCS: CSRF ok.
			return false;
		}

		// B. Get header ID value from POST request.
		$header_id = null;
		if ( ! empty( $_POST['_hb_override_template_id'] ) ) { // WPCS: CSRF ok.
			$header_id = sanitize_text_field( $_POST['_hb_override_template_id'] );
			$header_id = absint( $header_id );
		}

		// C. Check Header ID is empty or not.
		if ( empty( $header_id ) ) {
			return false;
		}

		// 1.a Check the header post is exist. If empty, skip.
		$header_post = get_post( $header_id );
		if ( empty( $header_post ) ) {
			return false;
		}

		// D. Get old hb_override_template_id.
		$header_id_old = get_post_meta( $post_id, '_hb_override_template_id_old', true );
		$header_id_old = absint( $header_id_old );
		if ( $header_id === $header_id_old ) {
			return false;
		}

		// E. Replace the old store with new header ID.
		update_post_meta( $post_id, '_hb_override_template_id_old', $header_id );

		// 1.b. Get active_on pages list from the header. If empty, set it as array.
		$active_on = get_post_meta( $header_id, '_mkhb_active_on', true );
		if ( empty( $active_on ) || ! is_array( $active_on ) ) {
			$active_on = array();
		}

		// 1.c. Push current post ID into array and make sure only one post ID in the list.
		$active_on[] = $post_id;
		$active_on = array_unique( $active_on );

		// 1.d. Save current post/page ID into _mkhb_active_on meta of the header.
		update_post_meta( $header_id, '_mkhb_active_on', $active_on );

		// 2. Update the old header active_on.
		$this->page_updated_meta_update_old( $header_id_old, $post_id );
	}

	/**
	 * Save current page ID into specific header after updated.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $header_id_old Old header ID.
	 * @param  integer $post_id       Current post ID.
	 */
	private function page_updated_meta_update_old( $header_id_old, $post_id ) {
		// 2.a Check the old header post is exist. If empty, skip.
		$header_post_old = get_post( $header_id_old );
		if ( empty( $header_post_old ) ) {
			return false;
		}

		// 2.b. Get active_on pages list from the old header. If empty, set it as array.
		$active_on_old = get_post_meta( $header_id_old, '_mkhb_active_on', true );
		if ( empty( $active_on_old ) || ! is_array( $active_on_old ) ) {
			$active_on_old = array();
		}

		// 2.c. Remove current post_id from list.
		$active_on_old = array_diff( $active_on_old, array( $post_id ) );

		// 2.d. Save current list of post IDs into old header.
		update_post_meta( $header_id_old, '_mkhb_active_on', $active_on_old );
	}

	/**
	 * Save current page ID into specific header after deleted.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $post_id Current post ID.
	 */
	private function page_deleted_meta_update( $post_id ) {
		// A. Get current header builder ID used from current post.
		$header_id = get_post_meta( $post_id, '_hb_override_template_id', true );
		$header_id = absint( $header_id );

		// B. Check Header ID is empty or not.
		if ( empty( $header_id ) ) {
			return false;
		}

		// 1. Check the header ID and post are exist. If empty, skip.
		$header_post = get_post( $header_id );
		if ( empty( $header_post ) ) {
			return false;
		}

		// 2. Get active_on pages list from the header.
		$active_on = get_post_meta( $header_id, '_mkhb_active_on', true );
		if ( empty( $active_on ) || ! is_array( $active_on ) ) {
			update_post_meta( $post_id, '_hb_override_template_id', '' );
			update_post_meta( $post_id, '_hb_override_template_id_old', '' );
			return;
		}

		// 3. Delete post ID from list.
		$new_active_on = array_diff( $active_on, array( $post_id ) );
		$new_active_on = array_unique( $new_active_on );

		// 4. Save removed post/page ID into _mkhb_active_on meta of the header.
		update_post_meta( $post_id, '_hb_override_template_id', '' );
		update_post_meta( $post_id, '_hb_override_template_id_old', '' );
		update_post_meta( $header_id, '_mkhb_active_on', $new_active_on );
	}

	/**
	 * Get meta data from current header based on device and workspace.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $post_id Active header ID.
	 * @param  string  $action  Action request.
	 * @return array            Meta data from post header.
	 */
	private function get_meta_header( $post_id, $action ) {
		// Get post metas.
		$metas = get_post_meta( $post_id, false, true );

		$post_meta = array();
		$meta_data = array(
			'normal_desktop' => '',
			'normal_tablet'  => '',
			'normal_mobile'  => '',
			'sticky_desktop' => '',
			'sticky_tablet'  => '',
			'sticky_mobile'  => '',
			'chains'         => array(),
			'chain'          => array(),
			'specificities'  => array(),
			'active_header'  => 'normal',
			'active_device'  => 'desktop',
			'options_laptop'                     => true,
			'options_mobile'                     => false,
			'options_overlapping_content'        => true,
			'options_sticky_header'              => false,
			'options_sticky_header_offset'       => 'header',
			'options_sticky_header_behaviour'    => 'slide-down',
		);

		// Normal - Desktop.
		if ( ! empty( $metas['_mkhb_content_normal_header_desktop'][0] ) ) {
			$meta_data['normal_desktop'] = $metas['_mkhb_content_normal_header_desktop'][0];
			$post_meta['_mkhb_content_normal_header_desktop'] = $metas['_mkhb_content_normal_header_desktop'][0];
		}

		// Normal - Tablet.
		if ( ! empty( $metas['_mkhb_content_normal_header_tablet'][0] ) ) {
			$meta_data['normal_tablet'] = $metas['_mkhb_content_normal_header_tablet'][0];
			$post_meta['_mkhb_content_normal_header_tablet'] = $metas['_mkhb_content_normal_header_tablet'][0];
		}

		// Normal - Mobile.
		if ( ! empty( $metas['_mkhb_content_normal_header_mobile'][0] ) ) {
			$meta_data['normal_mobile'] = $metas['_mkhb_content_normal_header_mobile'][0];
			$post_meta['_mkhb_content_normal_header_mobile'] = $metas['_mkhb_content_normal_header_mobile'][0];
		}

		// Sticky - Desktop.
		if ( ! empty( $metas['_mkhb_content_sticky_header_desktop'][0] ) ) {
			$meta_data['sticky_desktop'] = $metas['_mkhb_content_sticky_header_desktop'][0];
			$post_meta['_mkhb_content_sticky_header_desktop'] = $metas['_mkhb_content_sticky_header_desktop'][0];
		}

		// Sticky - Tablet.
		if ( ! empty( $metas['_mkhb_content_sticky_header_tablet'][0] ) ) {
			$meta_data['sticky_tablet'] = $metas['_mkhb_content_sticky_header_tablet'][0];
			$post_meta['_mkhb_content_sticky_header_tablet'] = $metas['_mkhb_content_sticky_header_tablet'][0];
		}

		// Sticky - Mobile.
		if ( ! empty( $metas['_mkhb_content_sticky_header_mobile'][0] ) ) {
			$meta_data['sticky_mobile'] = $metas['_mkhb_content_sticky_header_mobile'][0];
			$post_meta['_mkhb_content_sticky_header_mobile'] = $metas['_mkhb_content_sticky_header_mobile'][0];
		}

		$option_meta = $this->get_option_meta_header( $metas, $meta_data, $post_meta );
		$additional_meta = $this->get_additional_meta_header( $post_id, $metas, $option_meta['meta_data'], $option_meta['post_meta'], $action );

		return array(
			'meta_data' => $additional_meta['meta_data'],
			'post_meta' => $additional_meta['post_meta'],
		);
	}

	/**
	 * Get option meta data from current header.
	 *
	 * @since 6.0.0
	 *
	 * @param  array $metas     All meta from header ID.
	 * @param  array $meta_data Collected meta data.
	 * @param  array $post_meta Collected meta post.
	 * @return array            Meta data from post header.
	 */
	private function get_option_meta_header( $metas, $meta_data, $post_meta ) {
		// Option Laptop.
		if ( ! empty( $metas['_mkhb_options_laptop'][0] ) ) {
			$meta_data['options_laptop'] = $metas['_mkhb_options_laptop'][0];
			$post_meta['_mkhb_options_laptop'] = $metas['_mkhb_options_laptop'][0];
		}

		// Option Mobile.
		if ( ! empty( $metas['_mkhb_options_mobile'][0] ) ) {
			$meta_data['options_mobile'] = $metas['_mkhb_options_mobile'][0];
			$post_meta['_mkhb_options_mobile'] = $metas['_mkhb_options_mobile'][0];
		}

		// Option Overlapping.
		if ( ! empty( $metas['_mkhb_options_overlapping_content'][0] ) ) {
			$meta_data['options_overlapping_content'] = $metas['_mkhb_options_overlapping_content'][0];
			$post_meta['_mkhb_options_overlapping_content'] = $metas['_mkhb_options_overlapping_content'][0];
		}

		// Option Sticky.
		if ( ! empty( $metas['_mkhb_options_sticky_header'][0] ) ) {
			$meta_data['options_sticky_header'] = $metas['_mkhb_options_sticky_header'][0];
			$post_meta['_mkhb_options_sticky_header'] = $metas['_mkhb_options_sticky_header'][0];
		}

		// Option Offset.
		if ( ! empty( $metas['_mkhb_options_sticky_header_offset'][0] ) ) {
			$meta_data['options_sticky_header_offset'] = $metas['_mkhb_options_sticky_header_offset'][0];
			$post_meta['_mkhb_options_sticky_header_offset'] = $metas['_mkhb_options_sticky_header_offset'][0];
		}

		// Option Behaviour.
		if ( ! empty( $metas['_mkhb_options_sticky_header_behaviour'][0] ) ) {
			$meta_data['options_sticky_header_behaviour'] = $metas['_mkhb_options_sticky_header_behaviour'][0];
			$post_meta['_mkhb_options_sticky_header_behaviour'] = $metas['_mkhb_options_sticky_header_behaviour'][0];
		}

		return array(
			'meta_data' => $meta_data,
			'post_meta' => $post_meta,
		);
	}

	/**
	 * Get additional meta data from current header.
	 *
	 * @since 6.0.0
	 *
	 * @param  integer $post_id   Active header ID.
	 * @param  array   $metas     All meta from header ID.
	 * @param  array   $meta_data Collected meta data.
	 * @param  array   $post_meta Collected meta post.
	 * @param  string  $action    Action request.
	 * @return array              Meta data from post header.
	 */
	private function get_additional_meta_header( $post_id, $metas, $meta_data, $post_meta, $action ) {
		// Active On.
		if ( ! empty( $metas['_mkhb_active_on'][0] ) && 'clone' !== $action ) {
			$meta_data['active_on'] = $metas['_mkhb_active_on'][0];
			$post_meta['_mkhb_active_on'] = $metas['_mkhb_active_on'][0];
		}

		// Chains.
		if ( ! empty( $metas['_mkhb_chains'][0] ) ) {
			$meta_data['chains'] = maybe_unserialize( $metas['_mkhb_chains'][0] );
			$post_meta['_mkhb_chains'] = maybe_unserialize( $metas['_mkhb_chains'][0] );
		}

		// Chain.
		if ( ! empty( $metas['_mkhb_chain'][0] ) ) {
			$meta_data['chain'] = maybe_unserialize( $metas['_mkhb_chain'][0] );
			$post_meta['_mkhb_chain'] = maybe_unserialize( $metas['_mkhb_chain'][0] );
		}

		// Specifity.
		if ( ! empty( $metas['_mkhb_specificities'][0] ) ) {
			$meta_data['specificities'] = maybe_unserialize( $metas['_mkhb_specificities'][0] );
			$post_meta['_mkhb_specificities'] = maybe_unserialize( $metas['_mkhb_specificities'][0] );
		}

		// Active Header.
		if ( ! empty( $metas['_mkhb_active_header'][0] ) ) {
			$meta_data['active_header'] = $metas['_mkhb_active_header'][0];
			$post_meta['_mkhb_active_header'] = $metas['_mkhb_active_header'][0];
		}

		// Active Device.
		if ( ! empty( $metas['_mkhb_active_device'][0] ) ) {
			$meta_data['active_device'] = $metas['_mkhb_active_device'][0];
			$post_meta['_mkhb_active_device'] = $metas['_mkhb_active_device'][0];
		}

		// Check if the header is Global or not.
		$meta_data['global'] = 0;
		$global_id = get_option( 'mkhb_global_header', 0 );
		if ( absint( $global_id ) === $post_id ) {
			$meta_data['global'] = 1;
		}

		return array(
			'meta_data' => $meta_data,
			'post_meta' => $post_meta,
		);
	}

	/**
	 * Get single header post.
	 *
	 * @param  integer $post_id Current post ID will be generated.
	 * @return array            Post data.
	 */
	private function get_header_data( $post_id ) {
		// Get post data.
		$data = get_post( $post_id );

		if ( empty( $data ) ) {
			return null;
		}

		// Meta data.
		$meta_input = $this->get_meta_header( $data->ID, 'get' );

		// Active on pages.
		$active_on = get_post_meta( $data->ID, '_mkhb_active_on', true );

		// Global header ID.
		$global_id = get_option( 'mkhb_global_header', 0 );
		$global_id = absint( $global_id );

		// Global status of the header.
		$global_status = 0;
		if ( $data->ID === $global_id ) {
			$global_status = 1;
		}

		$return = array(
			'id'      => $data->ID,
			'title'   => $data->post_title,
			'metas'   => $meta_input['meta_data'],
			'in'      => array_filter( (array) $active_on ),
			'global'  => $global_status,
		);

		return $return;
	}

	/**
	 * Get header post list.
	 *
	 * @param  string $display Display all data or only specific group.
	 * @return array           Post list data.
	 */
	private function get_headers_data( $display = 'all' ) {
		$data = array();

		// Get all posts order by title.
		$posts = get_posts( array(
			'post_type' => 'mkhb_header',
			'post_status' => 'publish',
			'orderby' => 'title',
			'numberposts' => 100,
		) );

		if ( empty( $posts ) ) {
			return $data;
		}

		// Global header ID.
		$global_id = get_option( 'mkhb_global_header', 0 );
		$global_id = absint( $global_id );

		// If need return as title_id group.
		if ( 'title_id' === $display ) {
			foreach ( $posts as $post ) {
				// Active on pages.
				$active_on = get_post_meta( $post->ID, '_mkhb_active_on', true );

				// Global status of the header.
				$global_status = 0;
				if ( $post->ID === $global_id ) {
					$global_status = 1;
				}

				$data[ $post->ID ] = array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'in' => array_filter( (array) $active_on ),
					'global' => $global_status,
				);
			}
		}

		asort( $data );

		return $data;
	}
}

new MKHB_Model();
