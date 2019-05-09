<?php
/**
 * Customize API: MK_Textarea_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 6.0.2
 */

/**
 * Customize Textarea Control class.
 *
 * @since 6.0.2
 *
 * @see MK_Control
 */
class MK_Textarea_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-textarea';

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
		?>
		<label>
			<?php
			$this->render_label();
			$this->render_description();
			?>
			<div class="mk-control-wrap mk-control-textarea">
				<?php $this->render_textarea(); ?>
			</div>
		</label>
		<?php
	}
}
