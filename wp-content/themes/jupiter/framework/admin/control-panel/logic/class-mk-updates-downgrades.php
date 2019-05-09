<?php
/**
 * This file contains Mk_Updates_Downgrades class only.
 *
 * @author      Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.9.9
 * @package     artbees
 */

if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * WordPress auto update and downgrade feature for theme
 *
 * @author      Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.9.9
 * @package     artbees
 */
class Mk_Updates_Downgrades {

	/**
	 * Api_url Api url address.
	 *
	 * @var string $api_url
	 */
	var $api_url;

	/**
	 * switch for mk_get_theme_release_package_url return type. Ajax parameter workaround.
	 *
	 * @var bool determines mk_get_theme_release_package will return only url or object.
	 */
	var $url_object;

	/**
	 * Mk_Updates_Downgrades constructor.
	 */
	function __construct() {
		$this->api_url = 'http://artbees.net/api/v1/';
		$stored_api_key = get_option( 'artbees_api_key' );
		$this->url_object = true;
		add_action( 'wp_ajax_mk_get_theme_release_package_url', array( &$this, 'mk_get_theme_release_package_url' ) );
		add_action( 'wp_ajax_mk_modify_auto_update', array( &$this, 'mk_modify_auto_update' ) );
		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		if ( ! $this->is_verified_to_update_product( $stored_api_key ) ) {
			add_action( 'after_theme_row_' . $theme_base, array( &$this, 'unauthorized_update_notice' ), 10, 3 );
		}

		add_filter( 'site_transient_update_themes', array( &$this, 'check_for_update' ), 1 );

	}

	/**
	 * Get notice for themes list when user is not authorised to update the theme. In other words the product is not registered via an API key.
	 */
	public function unauthorized_update_notice() {
		$table  = _get_list_table( 'WP_MS_Themes_List_Table' );
		?>
		<tr class="plugin-update-tr"><td colspan="<?php echo $table->get_column_count(); ?>" class="plugin-update colspanchange">
				<div class="update-message mk-update-screen-notice">
					<?php
					printf(
						__( 'You need to authorize this site in order to get upgrades or support for this theme. %1$sRegsiter Your Theme%2$s.', 'mk_framework' ),
						'<a href="' . admin_url( 'admin.php?page=' . THEME_NAME ) . '">', '</a>'
					);
					?>
				</div>
		</tr>
		<?php
	}


	/**
	 * Returns an array of data containing current theme version and theme folder name
	 *
	 * @return array
	 */
	public function get_theme_data() {

		$theme_data = wp_get_theme( get_option( 'template' ) );
		$theme_version = $theme_data->version;

		$theme_base = get_option( 'template' );

		return array(
			'theme_version' => $theme_version,
			'theme_base'    => $theme_base,
		);

	}



	/**
	 * Hook into WP check update data and inject custom array for theme WP updater
	 *
	 * @param array $checked_data
	 * @return array    $checked_data
	 */
	public function check_for_update( $checked_data ) {
		$transient_array = get_transient( 'mk_modify_auto_update' );
		if ( $transient_array ) {
			// extract method array into variables
			$theme_data = $this->get_theme_data();
			$this->url_object = false;
			$response['theme'] = $theme_data['theme_base'];
			$response['package'] = $transient_array['package_url'];
			// $response['package'] = str_replace( 'http://','http://test2', $response['package'] );
			$response['new_version'] = $transient_array['release_version'];
			$response['url'] = 'https://themes.artbees.net/support/jupiter/release-notes/';
			// $response['url'] = $response['package'];
			$checked_data->response[ $theme_data['theme_base'] ] = $response;
		}
		return $checked_data;

	}

	public function mk_modify_auto_update() {
		$this->url_object = false;
		$transient_name = 'mk_modify_auto_update';
		delete_transient( $transient_name );
		$transient_array['package_url'] = $this->mk_get_theme_release_package_url();
		$transient_array['release_version'] = $_POST['release_version'];
		$transient_array['release_id'] = $_POST['release_id'];
		set_transient( $transient_name, $transient_array, 60 );
		add_filter( 'site_transient_update_themes', array( &$this, 'check_for_update' ), 1 );
		$data['success'] = true;
		$data['download_link'] = html_entity_decode( $this->get_theme_update_url() );
		echo wp_json_encode( $data );
		wp_die();
	}


	public function get_release_notes() {
		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

			global $wp_version;
			$theme_data = $this->get_theme_data();
			$theme_base = $theme_data['theme_base'];
			$theme_version = $theme_data['theme_version'];
			$request = array(
				'slug' => $theme_base,
				'version' => $theme_version,
			);

			// Start checking for an update
			$data = array(
				'body' => array(
					'action' => 'get_release_notes',
					'request' => serialize( $request ),
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
			);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && (200 == $raw_response['response']['code'] ) ) {
			$response = $raw_response['body'];
		}

		if ( is_wp_error( $raw_response ) ) {
			$response = is_wp_error( $raw_response );
		}

		return json_decode( $response );

	}



	/**
	 * Get theme update url
	 *
	 * @return string $url
	 */
	public function get_theme_update_url() {

		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		return wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $theme_base ) ), 'upgrade-theme_' . $theme_base );
	}

	/**
	 * Get theme latest version package url
	 *
	 * @return string $url
	 */
	public function get_theme_latest_package_url() {

		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

		global $wp_version;

		$data = array(
			'body' => array(
				'action' => 'get_theme_package',
				'apikey' => get_option( 'artbees_api_key' ),
				'domain' => $_SERVER['SERVER_NAME'],
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {
			return $raw_response['body'];
		}
		return false;
	}

	/**
	 * Get theme release url.
	 *
	 * @return string $url
	 */
	public function mk_get_theme_release_package_url() {
		check_ajax_referer( 'mk-ajax-get-theme-release-package-url-nonce', 'security' );
		$release_id = $_POST['release_id'];
		$api_key = get_option( 'artbees_api_key' );
		if ( empty( $api_key ) ) {
			return false;
		}

		global $wp_version;

		$data = array(
			'body' => array(
				'action' => 'get_release_download_link',
				'apikey' => get_option( 'artbees_api_key' ),
				'domain' => $_SERVER['SERVER_NAME'],
				'release_id' => $release_id,
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && ( 200 == $raw_response['response']['code'] ) ) {
			$json_response = json_decode( json_decode( $raw_response['body'], JSON_FORCE_OBJECT ) );
			if ( ! is_object( $json_response ) ) {
				return false;
			}
			if ( $this->url_object ) {
				echo wp_json_encode( $json_response );
				wp_die();
			}
			return $json_response->download_link;
		}

		return false;
	}


	/**
	 *
	 *
	 * Check if Current Customer is verified and authorized to update product
	 */
	function is_verified_to_update_product() {

		$api_key = get_option( 'artbees_api_key' );

		if ( ! empty( $api_key ) ) {
			return true;
		}
		return false;

	}

}


new Mk_Updates_Downgrades();

