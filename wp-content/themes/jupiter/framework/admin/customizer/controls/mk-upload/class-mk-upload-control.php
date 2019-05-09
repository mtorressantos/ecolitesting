<?php
/**
 * Customize API: MK_Upload_Control class
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
class MK_Upload_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-upload';

	/**
	 * Control to show width field.
	 *
	 * @var boolean $width
	 */
	public $width = false;

	/**
	 * Enqueue control styles and scripts.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
		wp_enqueue_script( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/scripts.js', array( 'jquery' ) );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$current_value = mk_maybe_json_decode( $this->value() );

		?>
		<div class="mk-control-wrap mk-control-upload">
			<div class="mk-upload">
				<div class="mk-upload-wrap">
					<div class="mk-row">
						<div class="mk-col-9">
							<div class="mk-upload-group">
								<?php
								$this->render_label();
								$this->render_description();
								?>
								<label>
									<div class="mk-upload-field">
										<div class="mk-upload-field-input">
											<?php
											$this->render_upload_url( $current_value );
											$this->render_upload_preview();
											?>
										</div>
										<?php
										$this->render_upload_button();
										?>
									</div>
								</label>
							</div>
						</div>
						<?php if ( $this->width ) : ?>
							<div class="mk-col-3">
								<div class="mk-upload-group mk-upload-width">
									<?php $this->render_label( __( 'Width', 'mk_framework' ) ); ?>
									<label><?php $this->render_upload_width( $current_value ); ?></label>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<input class="mk-upload-value" type="hidden" value="<?php echo esc_attr( mk_maybe_json_encode( $this->value() ) ); ?>" <?php $this->link(); ?> />
			</div>
		</div>
		<?php
	}

	/**
	 * Render the url field element
	 *
	 * @param object $current_value JSON decoded current value.
	 */
	private function render_upload_url( $current_value ) {
		$this->render_input(
			array(
				'link'        => $this->id . '-url',
				'input_type'  => 'text',
				'icon'        => 'mk-eye',
				'value'       => isset( $current_value->url ) ? $current_value->url : '',
				'input_attrs' => array(
					'name' => 'url',
				),
			)
		);
	}

	/**
	 * Render the url field element
	 *
	 * @param object $current_value JSON decoded current value.
	 */
	private function render_upload_width( $current_value ) {
		$this->render_input(
			array(
				'link'        => $this->id . '-width',
				'input_type'  => 'text',
				'unit'        => 'px',
				'value'       => isset( $current_value->width ) ? $current_value->width : '',
				'input_attrs' => array(
					'name' => 'width',
				),
			)
		);
	}

	/**
	 * Render the upload button.
	 */
	private function render_upload_button() {
		$this->render_button(
			array(
				'icon' => 'mk-upload',
			)
		);
	}

	/**
	 * Render the preview box.
	 */
	private function render_upload_preview() {
		?>
		<div class="mk-upload-preview">
			<svg class="mk-upload-spinner" width="20px" height="20px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle class="mk-upload-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg>
		</div>
		<?php
	}
}
