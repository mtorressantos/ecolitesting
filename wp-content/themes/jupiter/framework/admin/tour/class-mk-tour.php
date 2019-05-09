<?php
/**
 * Jupiter Tour class.
 *
 * @package Jupiter
 * @subpackage MK_Tour
 * @since 5.9.6
 */

/**
 * Class to initiate tour.
 *
 * @version 1.0.0
 * @author  Artbees Team
 *
 * @since 5.9.6
 */
class MK_Tour {

	/**
	 * Tour array.
	 *
	 * @since 5.9.6
	 * @access private
	 * @var array
	 */
	private $tour = array();

	/**
	 * Constructor.
	 *
	 * @since 5.9.6
	 */
	public function __construct() {
		$this->set_tour_list( $this->check_tour_option() );
		$this->get_tour_list();

		// Return when there's no tour.
		if ( empty( $this->tour['list'] ) ) {
			return;
		}

		$this->add_hooks();
	}

	/**
	 * Retrive list of tours.
	 *
	 * @since 5.9.6
	 */
	private function get_tour_list() {
		$tour = get_option( 'mk_tour' );

		foreach ( $tour['list'] as $key => $value ) {
			if ( true === $value['state'] ) {
				$this->tour['list'][] = $key;
			}
		}
	}

	/**
	 * Check if tour option exists.
	 *
	 * @since 5.9.6
	 *
	 * @return mixed array if option exists.
	 */
	private function check_tour_option() {
		$tour = get_option( 'mk_tour' );

		if ( empty( $tour ) ) {
			return false;
		}

		return $tour;
	}

	/**
	 * Set the list of tours.
	 *
	 * @since 5.9.6
	 *
	 * @param array $tour array of tour options.
	 */
	private function set_tour_list( $tour = array() ) {
		$tour_list = apply_filters( 'mk_tour_list', array() );

		// Create option if it doesn't exist.
		if ( empty( $tour ) ) {

			$tour = array(
				'list' => $tour_list,
			);

			update_option( 'mk_tour', $tour );

			$this->tour['list'] = $tour['list'];

			return;
		}

		// Merge user defined tour list with database version.
		$tour['list'] = array_merge( $tour_list, $tour['list'] );

		// Get the intersect of two lists.
		$tour['list'] = array_intersect_key( $tour['list'], $tour_list );

		update_option( 'mk_tour', $tour );
	}

	/**
	 * Add action and filter hooks.
	 *
	 * @since 5.9.6
	 */
	private function add_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_ajax_mk_tour_skip', array( $this, 'skip' ) );
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 5.9.6
	 */
	public function enqueue() {
		$suffix = ( defined( 'MK_DEV' ) && MK_DEV ) ? '' : '.min';

		wp_enqueue_style(
			'mk-tour-style',
			THEME_ADMIN_URI . '/tour/dist/styles' . $suffix . '.css',
			array(),
			THEME_VERSION
		);

		wp_enqueue_script(
			'mk-tour-script',
			THEME_ADMIN_URI . '/tour/dist/scripts' . $suffix . '.js',
			array( 'jquery' ),
			THEME_VERSION
		);

		$parsed_url = wp_parse_url( site_url() );
		$base_path = ( ! empty( $parsed_url['path'] ) ) ? $parsed_url['path'] : '' ;

		wp_localize_script( 'mk-tour-script', 'mk_tour', array(
			'nonce' => wp_create_nonce( 'mk_tour' ),
			'list' => $this->tour['list'],
			'base_path' => $base_path,
		) );
	}

	/**
	 * Save the state of each tour in database.
	 *
	 * @since 5.9.6
	 */
	public function skip() {
		check_ajax_referer( 'mk_tour', 'nonce' );

		$tour = $_POST['skip'];

		if ( empty( $tour ) ) {
			wp_send_json_error();
		}

		$mk_tour = get_option( 'mk_tour' );

		if ( $mk_tour ) {
			$mk_tour['list'][ $tour ]['state'] = false;

			update_option( 'mk_tour', $mk_tour );
			wp_send_json_success( $mk_tour );
		}

		wp_send_json_error();
	}

}

new MK_Tour();
