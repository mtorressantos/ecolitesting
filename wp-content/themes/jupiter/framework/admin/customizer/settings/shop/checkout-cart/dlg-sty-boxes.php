<?php
/**
 * Add Boxes section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles, boxes_style -> box_s
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Boxes dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_boxes_style',
		array(
			'mk_belong' => 'mk_s_cc_dialog',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Boxes', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_box',
			'priority' => 70,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Background color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_background_color]', array(
	'type' => 'option',
	'default'   => '#F1F1F5',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_background_color]',
		array(
			'section'  => 'mk_s_cc_s_boxes_style',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border radius.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_border_radius]', array(
	'type' => 'option',
	'default'   => 5,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_border_radius]',
		array(
			'section'  => 'mk_s_cc_s_boxes_style',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-corner-radius',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Border width.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_border_width]', array(
	'type' => 'option',
	'default'   => 1,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_border_width]',
		array(
			'section'  => 'mk_s_cc_s_boxes_style',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-border',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Border color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_border_color]', array(
	'type' => 'option',
	'default'   => '#D5D8DE',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_border_color]',
		array(
			'section'  => 'mk_s_cc_s_boxes_style',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_divider]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_divider]',
		array(
			'section' => 'mk_s_cc_s_boxes_style',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_box_box_model]', array(
	'type' => 'option',
	'default' => array(
		'padding_top' => 25,
		'padding_right' => 25,
		'padding_bottom' => 20,
		'padding_left' => 25,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_box_box_model]',
		array(
			'section' => 'mk_s_cc_s_boxes_style',
			'column'  => 'mk-col-12',
		)
	)
);
