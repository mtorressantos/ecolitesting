<?php
/**
 * Customize API: MK_Label_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Customize Label Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Label_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-label';

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
		$this->color = ( '' !== $this->color ) ? 'mk-' . $this->color : '';
		?>
		<label class="<?php echo esc_attr( 'mk-' . $this->label_type . '-label ' . $this->color ); ?>">
			<?php
			$this->render_input_group_icon( $this->icon );
			$this->render_label();
			$this->render_description();
			?>
		</label>
		<?php
	}
}
