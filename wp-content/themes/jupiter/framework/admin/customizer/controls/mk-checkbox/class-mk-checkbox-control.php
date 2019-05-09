<?php
/**
 * Customize API: MK_Checkbox_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Customize Checkbox Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Checkbox_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-checkbox';

	/**
	 * Input type data
	 *
	 * @var string $input_type Form checkbox type: button, image
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
		$classes = ' mk-checkbox ';

	?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<?php
				$this->render_label();
				$this->render_description();
			?>
			<div>
				<?php $this->render_checkbox( $this->input_type, $this->choices ); ?>
			</div>
		</div>
	<?php
	}
}
