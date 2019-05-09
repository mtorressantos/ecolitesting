<?php
/**
 * Customize API: MK_Shop_Control class
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Custom class used to create a shop customizer element
 *
 * @since 5.9.4
 *
 * @see MK_Control
 */
class MK_Dialog_Control extends MK_Control {

	/**
	 * Control type data
	 *
	 * @var string $type
	 */
	public $type = 'mk-dialog';

	/**
	 * List of pages.
	 *
	 * @var array $pages
	 */
	public $buttons = [];

	/**
	 * Show only button with the empty dif for dialog.
	 * It's mostly used for tiggering other dialogs.
	 *
	 * @var array $pages
	 */
	public $button_only = false;

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/styles.css' );
		wp_enqueue_style( $this->type . '-control-tippy', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/tippy.min.css' );
		wp_enqueue_script( $this->type . '-control', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/scripts.js' );
		wp_enqueue_script( $this->type . '-control-tippy', THEME_CUSTOMIZER_URI . '/controls/' . $this->type . '/tippy.min.js' );
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		echo '<p>' . esc_html( $this->label ) . '</p>';

		$mkfs = new Mk_Fs();

		foreach ( $this->buttons as $key => $value ) {

			$icon_path = THEME_CUSTOMIZER_DIR . '/assets/icons/' . esc_attr( $key ) . '.svg';
			$icon = '';

			if ( file_exists( $icon_path ) ) {
				$icon = $mkfs->get_contents( $icon_path );

				if ( $mkfs->get_error_codes() || ! $icon ) {
					$icon = '<img src="' . $icon_path . '">';
				}
			}

			// @codingStandardsIgnoreLine
			echo '<button type="button" class="button mk-dialog-button" data-dialog="' . esc_attr( $key ) . '">' . $icon . esc_html( $value ) . '</button>';

			if ( ! $this->button_only ) {
				echo '<div id="' . esc_attr( $key ) . '"></div>';
			}
		}

	}

}

