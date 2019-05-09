<?php
/**
 * Add Field section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles, small_heading -> s_h
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Field dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_f',
		array(
			'mk_belong' => 'mk_s_cc_dialog',
			'mk_tab' => array(
				'id' => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Field', 'mk_framework' ),
			'mk_reset' => 'sh_cc_sty_fld',
			'priority' => 80,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 16,
		'weight' => 400,
		'style' => 'normal',
		'color' => '#888888',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_typography]',
		array(
			'section' => 'mk_s_cc_s_f',
			'column'  => 'mk-col-12',
		)
	)
);


// Field Background Color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_background_color]', array(
	'type' => 'option',
	'default'   => '#ffffff',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_background_color]',
		array(
			'section'  => 'mk_s_cc_s_f',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border radius.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_border_radius]', array(
	'type' => 'option',
	'default'   => 3,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_border_radius]',
		array(
			'section' => 'mk_s_cc_s_f',
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
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_border_width]', array(
	'type' => 'option',
	'default'   => 1,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_border_width]',
		array(
			'section' => 'mk_s_cc_s_f',
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
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_border_color]', array(
	'type' => 'option',
	'default'   => '#d5d8de',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_border_color]',
		array(
			'section'  => 'mk_s_cc_s_f',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);


// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_divider_1]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_divider_1]',
		array(
			'section' => 'mk_s_cc_s_f',
		)
	)
);

// Focus Style Label.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_focus_label]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_focus_label]',
		array(
			'section' => 'mk_s_cc_s_f',
			'label' => __( 'Focus Style', 'mk_framework' ),
		)
	)
);

// Focus Text color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_focus_color]', array(
	'type' => 'option',
	'default'   => '#888888',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_focus_color]',
		array(
			'section'  => 'mk_s_cc_s_f',
			'column'   => 'mk-col-3',
			'icon'     => 'mk-font-color',
		)
	)
);

// Focus Background color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_focus_background_color]', array(
	'type' => 'option',
	'default'   => '#ffffff',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_focus_background_color]',
		array(
			'section'  => 'mk_s_cc_s_f',
			'column'   => 'mk-col-3',
			'icon'     => 'mk-background-color',
		)
	)
);

// Focus Border color.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_focus_border_color]', array(
	'type' => 'option',
	'default'   => '#157cf2',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_focus_border_color]',
		array(
			'section'  => 'mk_s_cc_s_f',
			'column'   => 'mk-col-3',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_divider_2]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_divider_2]',
		array(
			'section' => 'mk_s_cc_s_f',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_fld_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 0,
		'margin_left' => 0,
		'padding_top' => 13,
		'padding_right' => 12,
		'padding_bottom' => 12,
		'padding_left' => 12,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_fld_box_model]',
		array(
			'section' => 'mk_s_cc_s_f',
			'column'  => 'mk-col-12',
		)
	)
);
