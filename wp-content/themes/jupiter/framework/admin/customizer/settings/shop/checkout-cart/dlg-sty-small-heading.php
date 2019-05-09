<?php
/**
 * Add Field Label section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles, big_heading -> sml_hdn
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Field Label dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_small_heading',
		array(
			'mk_belong' => 'mk_s_cc_dialog_disabled',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Small Heading', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_sml_hdn',
			'priority' => 30,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_sml_hdn_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 14,
		'weight' => 600,
		'style' => 'normal',
		'color' => '#404040',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_sml_hdn_typography]',
		array(
			'section' => 'mk_s_cc_s_small_heading',
			'column'  => 'mk-col-12',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_sml_hdn_divider_2]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_sml_hdn_divider_2]',
		array(
			'section' => 'mk_s_cc_s_small_heading',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_sml_hdn_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 9,
		'margin_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_sml_hdn_box_model]',
		array(
			'section' => 'mk_s_cc_s_small_heading',
			'column'  => 'mk-col-12',
		)
	)
);
