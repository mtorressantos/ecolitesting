<?php
/**
 * Customize API: MK_Input_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Customize Input Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Input_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-input';

	/**
	 * Input type data
	 *
	 * @var string $input_type Form input type: text, url, email, number
	 */
	public $input_type = 'text';

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
			<div class="mk-control-wrap mk-control-input">
				<?php
				$this->render_input(
					array(
						'input_type' => $this->input_type,
					)
				);
					?>
			</div>
		</label>
		<?php
	}
}
