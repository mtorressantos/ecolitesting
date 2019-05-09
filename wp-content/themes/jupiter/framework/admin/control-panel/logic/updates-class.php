<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * WordPress auto update feature for theme
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.1
 * @package     artbees
 */


class Mk_Wp_Theme_Update {

	var $api_url;

	function __construct() {

		// Enable update check on every request. Normally you don't need this! This is for testing only!
		// set_site_transient('update_themes', null).
		$this->api_url = 'https://artbees.net/api/v1/';
		// $this->api_url = 'http://localhost/artbees-api/v1/';
		$stored_api_key = get_option( 'artbees_api_key' );

		/*
         * This hook allows you to create custom handlers for your own custom AJAX requests.
         * The wp_ajax_ hook follows the format "wp_ajax_$youraction", where $youraction is your AJAX request's 'action' property.
        */
		add_action(
			'wp_ajax_mk_dismiss_update_notice', array(
				&$this,
				'mk_dismiss_update_notice',
			)
		);

		$theme_data = $this->get_theme_data();
		$theme_base = $theme_data['theme_base'];

		if ( ! $this->is_verified_to_update_product( $stored_api_key ) ) {
			add_action( 'after_theme_row_' . $theme_base, array( &$this, 'unauthorized_update_notice' ), 10, 3 );
		}

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
				'apikey' => get_option( 'artbees_api_key' ),
				'domain' => $_SERVER['SERVER_NAME'],
				'action' => 'theme_update',
				'request' => serialize( $request ),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {

			// Feed the update data into WP updater
			// 400 means the version in server and in client is same.
			if ( ! empty( $raw_response['body'] ) && $raw_response['body'] != 400 ) {
				$response = unserialize( $raw_response['body'] );
				$checked_data->response[ $theme_base ] = $response;
			}
		}

		return $checked_data;

	}



	/**
	 * Check if there is a new version and if so, it returns the latest version number
	 *
	 * @param int $set_transient Set site transient for update_themes data.
	 */
	public function check_latest_version( $set_transient = 0 ) {

		if ( $set_transient ) {
			$current = get_site_transient( 'update_themes' );
			set_site_transient( 'update_themes', $current );
		}

		if ( false == get_transient( 'mk_jupiter_theme_version' ) ) {

				global $wp_version;

				$theme_data = $this->get_theme_data();
				$theme_base = $theme_data['theme_base'];
				$theme_version = $theme_data['theme_version'];

				$request = array(
					'slug' => $theme_base,
					'version' => $theme_version,
				);

				// Start checking for an update.
				$data = array(
					'body' => array(
						'action' => 'check_new_version',
						'request' => serialize( $request ),
					),
					'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
				);

				$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

			if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {

				$current_version = get_option( 'mk_jupiter_theme_current_version' );

				$new_version = trim( $raw_response['body'] );

				set_transient( 'mk_jupiter_theme_version', null );
				if ( version_compare( $current_version, $new_version, '<' ) ) {
					set_transient( 'mk_jupiter_theme_version', $new_version, DAY_IN_SECONDS );
				}
			}
		}

		if ( version_compare( get_option( 'mk_jupiter_theme_current_version' ), get_transient( 'mk_jupiter_theme_version' ), '<' ) ) {
			return get_transient( 'mk_jupiter_theme_version' );
		}
		return false;

	}


	public function fix_str_length( $matches ) {
				$string = $matches[2];
				$right_length = strlen( $string ); // yes, strlen even for UTF-8 characters, PHP wants the mem size, not the char count.
				return 's:' . $right_length . ':"' . $string . '";';
	}


	public function fixed_serialized( $string ) {
		// securities
		if ( ! preg_match( '/^[aOs]:/', $string ) ) {
			return $string;
		}
		if ( @unserialize( $string ) !== false ) {
			return $string;
		}
		$string = preg_replace( "%\n%", '', $string );
		// doublequote exploding.
		$data = preg_replace( '%";%', 'µµµ', $string );
		$tab = explode( 'µµµ', $data );
		$new_data = '';
		foreach ( $tab as $line ) {
			$new_data .= preg_replace_callback( '%\bs:(\d+):"(.*)%', array( &$this, 'fix_str_length' ), $line );
		}
		return $new_data;
	}


	public function get_release_note() {
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

			// Start checking for an update.
			$data = array(
				'body' => array(
					'action' => 'get_release_note',
					'request' => serialize( $request ),
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
			);

			$raw_response = wp_remote_post( $this->api_url . 'update-theme' , $data );

		if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {
			$response = $raw_response['body'];
		}
		if ( is_wp_error( $raw_response ) ) {
			$response = is_wp_error( $raw_response );
		}

			$repair_serialize = $this->fixed_serialized( $response );

		return unserialize( $repair_serialize );

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
	 * Get theme latest verion package url
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



	public function new_update_notice() {
		$get_new_version = $this->check_latest_version();
		$dismissed_version = get_option( 'mk_dismiss_warning_version' );
		if ( ! empty( $get_new_version ) && $get_new_version != $dismissed_version ) {
			?>
			<div class="cp-update-notice clearfix">
				<?php _e( 'A new version of Jupiter is available to download.', 'mk_framework' ); ?>
				<a href="<?php echo admin_url( 'admin.php?page=theme-updates' ); ?> "><?php _e( 'Update Now', 'mk_framework' ); ?></a>
				<a class="close-button" href="#" data-new-version="<?php echo $get_new_version; ?>"><img src="<?php echo THEME_CONTROL_PANEL_ASSETS; ?>/images/close-cp.svg"></a>
			</div>
		<?php
		}
	}

	public function mk_dismiss_update_notice() {

				$version = $_POST['version'];

		update_option( 'mk_dismiss_warning_version', $version );

		echo json_encode( 'saved' );

				wp_die();
	}




	/**
	 * get the download URL for plugins
	 *
	 * @param $plugins_name string
	 * @return string   download link
	 */
	public function get_plugin_download_link( $plugin_name ) {
		global $wp_version;

		$data = array(
			'body' => array(
				'apikey' => get_option( 'artbees_api_key' ),
				'domain' => $_SERVER['SERVER_NAME'],
				'plugin_name' => $plugin_name,
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);

		$raw_response = wp_remote_post( $this->api_url . 'update-plugin' , $data );

		if ( ! is_wp_error( $raw_response ) && ($raw_response['response']['code'] == 200) ) {

			if ( $raw_response['body'] ) {
				return trim( $raw_response['body'] );
			}
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


new Mk_Wp_Theme_Update();

