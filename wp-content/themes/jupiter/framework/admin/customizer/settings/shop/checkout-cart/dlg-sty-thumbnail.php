<?php
/**
 * Add Thumbnail section of Checkout & Cart > Styles.
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
		'mk_s_cc_s_tn',
		array(
			'mk_belong' => 'mk_s_cc_dialog',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Thumbnail', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_tmn',
			'priority' => 120,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Show or Hide.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_display]', array(
	'type' => 'option',
	'default' => 'true',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_display]',
		array(
			'sublabel' => __( 'Show', 'mk_framework' ),
			'section' => 'mk_s_cc_s_tn',
			'column'  => 'mk-col-12',
		)
	)
);

// Border radius.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_border_radius]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_border_radius]',
		array(
			'section' => 'mk_s_cc_s_tn',
			'column'  => 'mk-col-3-alt',
			'icon' => 'mk-corner-radius',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);


// Border width.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_border_width]', array(
	'type' => 'option',
	'default'   => 1,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_border_width]',
		array(
			'section' => 'mk_s_cc_s_tn',
			'column'  => 'mk-col-3-alt',
			'icon' => 'mk-border',
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Border color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_border_color]', array(
	'type' => 'option',
	'default'   => '#cfd3d9',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_border_color]',
		array(
			'section'  => 'mk_s_cc_s_tn',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);


// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_divider_2]' , array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_divider_2]',
		array(
			'section' => 'mk_s_cc_s_tn',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_tmn_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 0,
		'margin_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_tmn_box_model]',
		array(
			'section' => 'mk_s_cc_s_tn',
			'column'  => 'mk-col-12',
		)
	)
);
