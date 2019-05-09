<?php
/**
 * Add Field Label section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles, small_heading -> s_h
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Field Label dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_fl',
		array(
			'mk_belong' => 'mk_s_cc_dialog',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Field Label', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_fld_lbl',
			'priority' => 70,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_lbl_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 16,
		'weight' => 700,
		'style' => 'normal',
		'color' => '#157cf2',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_lbl_typography]',
		array(
			'section' => 'mk_s_cc_s_fl',
			'column'  => 'mk-col-12',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_lbl_divider_2]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_lbl_divider_2]',
		array(
			'section' => 'mk_s_cc_s_fl',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_lbl_box_model]', array(
	'type' => 'option',
	'default' => array(
		'padding_top' => 0,
		'padding_right' => 0,
		'padding_bottom' => 0,
		'padding_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_lbl_box_model]',
		array(
			'section' => 'mk_s_cc_s_fl',
			'column'  => 'mk-col-12',
		)
	)
);
