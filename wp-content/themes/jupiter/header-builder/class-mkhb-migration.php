<?php
/**
 * Header Builder: Migration functions file, MKHB_Migration.
 *
 * NOTE: Need update about how to save the data. This class is still using OPTIONS not POST.
 *
 * @since 5.9.8
 * @package Header_Builder
 */

/**
 * MKHB_Migration class used for updating current user HB default data on database. We need to add
 * new migrate function every time we update the value or the structure of store.json. You aslo
 * need to update the HB database version number constant.
 *
 * Current migration version is:
 *
 * 1.   Migrate database version from v1 to v2.
 *      - Update default value of logo width from 200 into ''.
 *      - Add logoTheme option in Logo element.
 *
 * @since 5.9.8
 * @since 6.0.0 Change class prefix from HB_ into MKHB_.
 *
 * @see header-builder/hb-config.php Update HB database version here.
 */
class MKHB_Migration {
	/**
	 * Constructor.
	 *
	 * Call some functions to check before running migrate function.
	 *
	 * @since 5.9.8
	 */
	public function __construct() {
		// Check if current database version is the latest one.
		if ( ! $this->is_latest_db_version() ) {
			$this->backup_data();
			$this->run_migration();
		}
	}

	/**
	 * Run migration function for specific version.
	 *
	 * @since 5.9.8
	 */
	private function run_migration() {
		/**
		 * Load migration library only when we need to run migration function. All process
		 * should be stored in this file as function.
		 *
		 * @since 5.9.8
		 * @see  header-builder/includes/libs/migration.php
		 */
		require_once HB_INCLUDES_DIR . '/libs/migration.php';

		$version = $this->get_current_db_version();

		// Migration for version less than 2.
		if ( $version < 2 ) {
			hb_upgrade_db_2();
		}

		// After all migration process are done, update the version.
		$this->update_db_version();
	}

	/**
	 * Check and compare if the current HB Database version is the latest one.
	 *
	 * @since 5.9.8
	 * @access private
	 *
	 * @return boolean True if the latest one, false if the old one.
	 */
	private function is_latest_db_version() {
		$version = $this->get_current_db_version();
		if ( HB_DB_VERSION !== intval( $version ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get current HB database version.
	 *
	 * @since 5.9.8
	 * @access private
	 *
	 * @return string Current HB database version.
	 */
	private function get_current_db_version() {
		$version = get_option( 'artbees_header_builder_db_version', null );

		// If version is empty, save and return current version from HB_DB_VERSION.
		if ( empty( $version ) ) {
			$this->update_db_version();
			return HB_DB_VERSION;
		}

		return $version;
	}

	/**
	 * Update latest HB database version.
	 *
	 * @since 5.9.8
	 * @access private
	 */
	private function update_db_version() {
		update_option( 'artbees_header_builder_db_version', HB_DB_VERSION );
	}

	/**
	 * Backup current user data for the last 5 sets.
	 *
	 * @since 5.9.8
	 * @access private
	 *
	 * @return boolean Only if the old data is empty or not JSON, return false.
	 */
	private function backup_data() {
		// If HB data is empty or data is not JSON, just leave it. No need for backup.
		$data = get_option( 'artbees_header_builder', null );
		if ( empty( $data ) || ! mkhb_is_json( $data ) ) {
			return false;
		}

		// Get the backup list. If it's already 5 sets, shift first backup.
		$backup = get_option( 'artbees_header_builder_bu', array() );
		if ( count( $backup ) >= 5 ) {
			array_shift( $backup );
		}

		// Set the details before save the backup.
		$old_ver = $this->get_current_db_version();

		$details = array(
			'created' => current_time( 'Y-m-d H:i:s' ),
			'data' => $data,
			'action' => 'Migration from version ' . $old_ver . ' to ' . HB_DB_VERSION,
		);

		array_push( $backup, $details );
		update_option( 'artbees_header_builder_bu', $backup );
	}
}

new MKHB_Migration();
