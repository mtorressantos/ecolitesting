<?php
/**
 * Customize API: MK_Divider_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Custom class used to create a divider customizer element
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Divider_Control extends MK_Control {

	/**
	 * Control type data
	 *
	 * @var string $type
	 */
	public $type = 'mk-divider';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		?>
		<hr>
		<?php
	}
}

