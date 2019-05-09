<?php
/**
 * Customize API: MK_Button_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 6.0.3
 */

/**
 * Customize Button Control class.
 *
 * @since 6.0.3
 *
 * @see MK_Control
 */
class MK_Button_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-button';

	/**
	 * Enqueue control styles and scripts.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$this->render_label();
		$this->render_description();
		?>
		<div class="mk-control-wrap mk-control-button">
			<?php $this->render_button(); ?>
		</div>
		<?php
	}
}
