<?php
/**
 * Customizer Dynamic Styles: Section Steps Style.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.7
 */

$css = '';

if ( 'icon' === mk_cz_get_option( 'sh_cc_sty_stp_style', 'number' ) ) {

	$css .= '.mk-checkout-steps-icon .mk-checkout-step svg {';
		$css .= 'height: ' . mk_cz_get_option( 'sh_cc_sty_stp_icon_active_icon_size' ) . 'px';
	$css .= '}';
	$css .= '.mk-checkout-steps-icon .mk-checkout-step-active .mk-checkout-step-svg-wrap path {';
		$css .= 'fill: ' . mk_cz_get_option( 'sh_cc_sty_stp_icon_active_fill_color', 'rgba(21, 124, 242, 1)' );
	$css .= '}';
	$css .= '.mk-checkout-steps {';
		$css .= mk_cs_box_model( 'sh_cc_sty_stp_icon_active_box_model' );
	$css .= '}';
	$css .= '.mk-checkout-step .mk-checkout-step-text {';
		$css .= mk_cs_typography( 'sh_cc_sty_stp_icon_active_typography', [ 'color' ] );
	$css .= '}';
	$typography = mk_maybe_json_decode( mk_cz_get_option( 'sh_cc_sty_stp_icon_active_typography' ) );
	if ( $typography ) {
		$css .= '.mk-checkout-steps-icon .mk-checkout-step-active .mk-checkout-step-text {';
			$css .= 'color: ' . $typography->color;
		$css .= '}';
	}
	$css .= '.mk-checkout-step:not(.mk-checkout-step-active) svg path {';
		$css .= 'fill: ' . mk_cz_get_option( 'sh_cc_sty_stp_icon_passive_icon_color' );
	$css .= '}';
	$css .= '.mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-text {';
		$css .= 'color: ' . mk_cz_get_option( 'sh_cc_sty_stp_icon_passive_text_color' );
	$css .= '}';

} elseif ( 'number' === mk_cz_get_option( 'sh_cc_sty_stp_style', 'number' ) ) {

	$css .= '.mk-checkout-steps-number .mk-checkout-step-active .mk-checkout-step-number {';
		$css .= 'background-color: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_active_number_background_color' );
	$css .= '}';
	$css .= '.mk-checkout-steps-number .mk-checkout-step .mk-checkout-step-number {';
		$css .= mk_cs_typography( 'sh_cc_sty_stp_icon_active_number_typography' );
	$css .= '}';
	$css .= '.mk-checkout-steps-number .mk-checkout-step .mk-checkout-step-text {';
		$css .= mk_cs_typography( 'sh_cc_sty_stp_icon_active_title_typography' );
	$css .= '}';
	$css .= '.mk-checkout-steps {';
		$css .= mk_cs_box_model( 'sh_cc_sty_stp_number_active_box_model' );
	$css .= '}';
	$css .= '.mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-number {';
		$css .= 'background-color: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_passive_number_background_color', '#d8d8d8' );
	$css .= '}';
	$css .= '.mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-number {';
		$css .= 'color: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_passive_number_text_color', '#ffffff' );
	$css .= '}';
	$css .= '.mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-text {';
		$css .= 'color: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_passive_title_color', '#d8d8d8' );
	$css .= '}';
	$css .= '.mk-checkout-steps-number .mk-checkout-step-svg-wrap {';
		$css .= 'border-color: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_passive_number_background_color', '#d8d8d8' );
	$css .= '}';
	$css .= '.mk-checkout-steps-number .mk-checkout-step-svg-wrap path {';
		$css .= 'fill: ' . mk_cz_get_option( 'sh_cc_sty_stp_number_passive_number_background_color', '#d8d8d8' );
	$css .= '}';

} // End if().


return $css;
