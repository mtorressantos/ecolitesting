<?php
/**
 * Customize API: MK_Toggle_Control class
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

/**
 * Customize Toggle Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Toggle_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-toggle';

	/**
	 * Sub Label
	 *
	 * @var string $sublabel Label for toggle
	 */
	public $sublabel = '';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
		wp_enqueue_script( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/scripts.js' );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
	?>
		<label>
			<?php
				$this->render_label();
				$this->render_description();
			?>
			<?php $this->render_toggle( $this->sublabel ); ?>
		</label>
	<?php
	}
}
