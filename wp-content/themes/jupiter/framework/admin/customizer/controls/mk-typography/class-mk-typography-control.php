<?php
/**
 * Customize API: MK_Typography_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Customize Typography Group Control class.
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Typography_Control extends MK_Control {

	/**
	 * Control type
	 *
	 * @var string $type
	 */
	public $type = 'mk-typography';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
		wp_enqueue_script( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/scripts.js', array( 'jquery', 'mk-select-control-selectize' ) );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$current_value = mk_maybe_json_decode( $this->value() );
		?>
		<div class="mk-control-wrap mk-control-typography">
			<div class="mk-control-typography-inner">
				<div class="mk-row">
					<div class="mk-col-8">
						<div class="mk-typography-item mk-typography-item-font-family">
						<label>
						<?php
						$this->_render_element_family( $current_value );
						?>
						</label>
						</div>
					</div>
					<div class="mk-col-4">
						<div class="mk-typography-item mk-typography-item-font-size">
							<label>
							<?php
							$this->_render_element_size( $current_value );
							?>
							</label>
						</div>
					</div>
				</div>
				<div class="mk-row">
					<div class="mk-col-6">
						<div class="mk-typography-item mk-typography-item-font-weight">
						<label>
						<?php
						$this->_render_element_weight( $current_value );
						?>
						</label>
						</div>
					</div>
					<div class="mk-col-3">
						<div class="mk-typography-item mk-typography-item-font-style">
						<?php
						$this->_render_element_style( $current_value );
						?>
						</div>
					</div>
					<div class="mk-col-3">
						<div class="mk-typography-item mk-typography-item-color alignright">
							<label>
							<?php
							$this->_render_element_color( $current_value );
							?>
							</label>
						</div>
					</div>
				</div>
			</div>
			<input class="mk-typography-value" type="hidden" value="<?php echo esc_attr( mk_maybe_json_encode( $this->value() ) ); ?>" <?php $this->link(); ?> />
		</div>
		<?php
	}

	/**
	 * Render font family element
	 *
	 * @param string $current_value Currrent element value.
	 */
	private function _render_element_family( $current_value ) {
		$this->render_select( array(
			'icon' => 'mk-font-family',
			'choices' => $this->all_fonts(),
			'link' => $this->id . '-family',
			'input_attrs' => array(
				'name' => 'family',
			),
			'selected' => isset( $current_value->family ) ? $current_value->family : '',
		) );
	}

	/**
	 * Render font size element
	 *
	 * @param string $current_value Currrent element value.
	 */
	private function _render_element_size( $current_value ) {
		$this->render_input( array(
			'link' => $this->id . '-size',
			'input_type' => 'number',
			'input_attrs' => array(
				'name' => 'size',
				'min' => 0,
			),
			'icon' => 'mk-font-size',
			'unit' => 'px',
			'value' => isset( $current_value->size ) ? $current_value->size : '',
		) );
	}

	/**
	 * Render font weight element
	 *
	 * @param string $current_value Currrent element value.
	 */
	private function _render_element_weight( $current_value ) {
		$this->render_select( array(
			'icon' => 'mk-font-weight',
			'choices' => $this->font_weight(),
			'link' => $this->id . '-weight',
			'input_attrs' => array(
				'name' => 'weight',
			),
			'selected' => isset( $current_value->weight ) ? $current_value->weight : '',
		) );
	}

	/**
	 * Render font style element
	 *
	 * @param string $current_value Currrent element value.
	 */
	private function _render_element_style( $current_value ) {
		$this->render_radio( array(
			'choices' => array(
				'normal' => 'mk-font-style-normal',
				'italic' => 'mk-font-style-italic',
			),
			'input_attrs' => array(
				'name' => 'style',
			),
			'selected' => isset( $current_value->style ) ? $current_value->style : '',
			'input_type' => 'icon',
			'link' => $this->id . '-style',
		) );
	}

	/**
	 * Render font color element
	 *
	 * @param string $current_value Currrent element value.
	 */
	private function _render_element_color( $current_value ) {
		$this->render_input( array(
			'link' => $this->id . '-color',
			'input_attrs' => array(
				'name' => 'color',
			),
			'icon' => 'mk-font-color',
			'wrap_class' => 'mk-color-picker-holder',
			'value' => isset( $current_value->color ) ? $current_value->color : '',
		) );
	}

	/**
	 * Get all fonts weight list
	 *
	 * @return array
	 */
	private function font_weight() {
		return array(
			'' => __( 'Select Option', 'mk_framework' ),
			'100' => __( '100', 'mk_framework' ),
			'200' => __( '200 (light)', 'mk_framework' ),
			'300' => __( '300', 'mk_framework' ),
			'400' => __( '400 (normal)', 'mk_framework' ),
			'500' => __( '500 (medium)', 'mk_framework' ),
			'600' => __( '600 (semi-bold)', 'mk_framework' ),
			'700' => __( '700 (bold)', 'mk_framework' ),
			'bolder' => __( '800 (bolder)', 'mk_framework' ),
			'900' => __( '900', 'mk_framework' ),
		);
	}

	/**
	 * Get all fonts list
	 *
	 * @return array
	 */
	private function all_fonts() {
		$all_fonts = array(
			'inherit' => __( 'Select Option', 'mk_framework' ),
		);

		$safe_fonts = $this->safe_fonts();
		if ( $safe_fonts ) {
			foreach ( $safe_fonts as $safe_font ) {
				$all_fonts[ $safe_font ] = array(
					'label' => $safe_font,
					'data-data' => wp_json_encode( array(
						'source' => 'safe-font',
					) ),
				);
			}
		}

		$google_fonts = $this->google_fonts();
		if ( $google_fonts ) {
			foreach ( $google_fonts as $google_font ) {
				$all_fonts[ $google_font ] = array(
					'label' => $google_font,
					'data-data' => wp_json_encode( array(
						'source' => 'google-font',
					) ),
				);
			}
		}

		return $all_fonts;
	}

	/**
	 * Get Safe fonts list
	 *
	 * @return array
	 */
	private function safe_fonts() {
		return array(
			'HelveticaNeue-Light, Helvetica Neue Light, Helvetica Neue, Helvetica, Arial, "Lucida Grande", sans-serif',
			'Arial, Helvetica, sans-serif',
			'Arial Black, Gadget, sans-serif',
			'Bookman Old Style, serif',
			'Courier, monospace',
			'Courier New, Courier, monospace',
			'Garamond, serif',
			'Georgia, serif',
			'Impact, Charcoal, sans-serif',
			'Lucida Console, Monaco, monospace',
			'Lucida Grande, Lucida Sans Unicode, sans-serif',
			'MS Sans Serif, Geneva, sans-serif',
			'MS Serif, New York, sans-serif',
			'Palatino Linotype, Book Antiqua, Palatino, serif',
			'Tahoma, Geneva, sans-serif',
			'Times New Roman, Times, serif',
			'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
			'Comic Sans MS, cursive',
		);
	}

	/**
	 * Get Google fonts list from Theme Options.
	 *
	 * @return array
	 */
	private function google_fonts() {
		$theme_options = new MK_Theme_Options();
		return $theme_options->get_google_fonts();
	}
}
