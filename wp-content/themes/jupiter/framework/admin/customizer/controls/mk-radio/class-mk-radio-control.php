<?php
/**
 * Customize API: MK_Radio_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Customize Radio Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Radio_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-radio';

	/**
	 * Input type data
	 *
	 * @var string $input_type
	 */
	public $input_type = 'button';

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
		$classes = ' mk-radio mk-radio-';
		$classes .= $this->input_type;
	?>
		<label>
			<div class="mk-control-wrap mk-control-radio">
				<?php
				$this->render_label();
				$this->render_description();
				$this->render_radio( array(
					'input_type' => $this->input_type,
					'choices' => $this->choices,
					'selected' => $this->value(),
				) );
				?>
			</div>
		</label>
	<?php
	}
}
