<?php
/**
 * WordPress Customize Section classes
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 3.4.0
 */

if ( ! class_exists( 'WP_Customize_Section' ) ) {
	return;
}

/**
 * Extend WordPress Customize Section class.
 *
 * @since 5.9.4
 *
 * @see WP_Customize_Section
 */
class MK_Dialog extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'mk-dialog';

	/**
	 * Belong to which dialog.
	 *
	 * @access public
	 * @var string|boolean
	 */
	public $mk_belong = false;

	/**
	 * Name of the dialog tab.
	 *
	 * @access public
	 * @var string|boolean
	 */
	public $mk_tab = false;

	/**
	 * If it's a child dialog.
	 *
	 * @access public
	 * @var string|boolean
	 */
	public $mk_child = true;

	/**
	 * Unique prefix for resetting part of data.
	 *
	 * @access public
	 * @var string|boolean
	 */
	public $mk_reset = false;

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {

		$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section' ) );
		$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content'] = $this->get_content();
		$array['active'] = $this->active();
		$array['instanceNumber'] = $this->instance_number;
		$array['customizeAction'] = __( 'Customizing', 'mk_framework' );

		$array['mkBelong'] = $this->mk_belong;
		$array['mkTab'] = $this->mk_tab;
		$array['mkChild'] = $this->mk_child;
		$array['mkReset'] = $this->mk_reset;

		return $array;

	}

}
