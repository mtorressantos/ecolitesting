<?php
/**
 * Migration functions library.
 *
 * @package Header_Builder
 * @subpackage Libs
 * @since 5.9.8
 *
 * @see header-builder/hb-config.php
 * @see HB_Migration run_migration()
 *
 * List of migration functions:
 * 2    hb_upgrade_db_2    Migrate from 1 to 2.
 */

/**
 * Migration function from v1 to v2
 * - Update default value of Logo width from 200 to ''.
 * - Add new field to choose the logo type as Dark/Default or Light.
 *
 * @since 5.9.8
 */
function hb_upgrade_db_2() {
	$data = get_option( 'artbees_header_builder', null );

	// Stop process if the data is empty.
	if ( empty( $data ) ) {
		return false;
	}

	// 1. Update default value of Logo width from 200 into ''.
	$logo_ids = array(
		'cj6m476if000e3e5ojut0zwww',
		'cj6m476if000d3e5oglr1z9ue',
		'cj6m476if000c3e5ofkm3zszw',
	);

	// Find width of logo value based on the default template ID of Logo.
	foreach ( $logo_ids as $logo_id ) {
		$result_1 = preg_replace(
			'/(' . $logo_id . '[^\}]+width\"\:\{\"value\"\:)(200)(\,)/i',
			'$1""$3',
			$data
		);
		$data = $result_1;
	}

	/**
	 * 2. Add logoTheme key-value in Logo element.
	 *
	 * HB will add new data "logoTheme":{"value":"dark","specificity":{"value":[]}} before
	 * "linkHomepage". Only do this if logoTheme is not exist or not append in data.
	 */
	preg_match_all( '/\"logoTheme\"/s', $data, $find_matches );
	if ( empty( $find_matches[0] ) ) {
		$result_2 = preg_replace(
			'/(\"linkHomepage\")/s',
			'"logoTheme":{"value":"dark","specificity":{"value":[]}},"linkHomepage"',
			$data
		);
		$data = $result_2;
	}

	// Save new data only if it's correct JSON format.
	if ( mkhb_is_json( $data ) ) {
		update_option( 'artbees_header_builder', $data );
	}
}
