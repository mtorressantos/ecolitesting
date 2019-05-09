<?php
/**
 * Customize API: MK_Alert_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Custom class used to create a alert customizer element
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Alert_Control extends MK_Control {

	/**
	 * Control type data
	 *
	 * @var string $type
	 */
	public $type = 'mk-alert';

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		// @codingStandardsIgnoreLine
		echo $this->description;
	}
}

