<?php
/**
 * Dynamic styles for Field Style section in Checkout & Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.7
 */

$bg_color = mk_cz_get_option( 'sh_cc_sty_fld_background_color' );
$border_radius = mk_cz_get_option( 'sh_cc_sty_fld_border_radius' );
$border_width = mk_cz_get_option( 'sh_cc_sty_fld_border_width' );
$border_color = mk_cz_get_option( 'sh_cc_sty_fld_border_color' );
$focus_text_color = mk_cz_get_option( 'sh_cc_sty_fld_focus_color', '#888888' );
$focus_bg_color = mk_cz_get_option( 'sh_cc_sty_fld_focus_background_color', '#ffffff' );
$focus_border_color = mk_cz_get_option( 'sh_cc_sty_fld_focus_border_color', '#888888' );

$css = 'body.woocommerce-checkout .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty),
		body.woocommerce-checkout .woocommerce textarea,
		body.woocommerce-checkout .woocommerce .select2-selection,
		body.woocommerce-cart .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty),
		body.woocommerce-cart .woocommerce textarea,
		body.woocommerce-cart .woocommerce .select2-selection,
		body.woocommerce-order-received .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty),
		body.woocommerce-order-received .woocommerce  textarea,
		body.woocommerce-order-received .woocommerce .select2-selection {';

	$css .= mk_cs_typography( 'sh_cc_sty_fld_typography' );
	$css .= "background-color: {$bg_color};";
	$css .= "border-radius: {$border_radius}px;";
	$css .= "border-width: {$border_width}px;";
	$css .= "border-color: {$border_color};";

$css .= '}';

$css .= 'body.woocommerce-checkout .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus,
		body.woocommerce-checkout .woocommerce textarea:focus,
		body.woocommerce-checkout .woocommerce .select2-selection:focus,
		body.woocommerce-cart .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus,
		body.woocommerce-cart .woocommerce textarea:focus,
		body.woocommerce-cart .woocommerce .select2-selection:focus,
		body.woocommerce-order-received .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus,
		body.woocommerce-order-received .woocommerce  textarea:focus {';

	$css .= "color: {$focus_text_color};";
	$css .= "background-color: {$focus_bg_color};";
	$css .= "border-color: {$focus_border_color};";

$css .= '}';


$css .= 'body.woocommerce-checkout .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code),
		body.woocommerce-checkout .woocommerce textarea,
		body.woocommerce-cart .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code),
		body.woocommerce-cart .woocommerce textarea,
		body.woocommerce-order-received .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code),
		body.woocommerce-order-received .woocommerce  textarea {';

	$css .= mk_cs_box_model( 'sh_cc_sty_fld_box_model' );

$css .= '}';

$field_box_model = mk_maybe_json_decode( mk_cz_get_option( 'sh_cc_sty_fld_box_model' ) );
if ( $field_box_model ) {
	$css .= 'body.woocommerce-checkout .woocommerce .select2-container {';
		$css .= 'margin: ' . $field_box_model->margin_top . 'px ' . $field_box_model->margin_right . 'px ' . $field_box_model->margin_bottom . 'px ' . $field_box_model->margin_left . 'px';
	$css .= '}';
	$css .= 'body.woocommerce-checkout .woocommerce .select2-selection {';
		$css .= 'padding: ' . $field_box_model->padding_top . 'px ' . $field_box_model->padding_right . 'px ' . $field_box_model->padding_bottom . 'px ' . $field_box_model->padding_left . 'px';
	$css .= '}';
}


return $css;
