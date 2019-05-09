<?php
/**
 * Add Variations section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 6.0.1
 */

// Variations dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_variations',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Variations', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_var',
			'priority' => 90,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_typography]', array(
		'type' => 'option',
		'default' => array(
			'family' => 'inherit',
			'size' => 14,
			'weight' => 400,
			'style' => 'normal',
			'color' => '#222222',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_typography]',
		array(
			'section' => 'mk_s_pp_s_variations',
			'column'  => 'mk-col-12',
		)
	)
);

// Background Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_background_color]', array(
		'type' => 'option',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_background_color]',
		array(
			'section'  => 'mk_s_pp_s_variations',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_border]', array(
		'type' => 'option',
		'default'   => 1,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_border]',
		array(
			'section'  => 'mk_s_pp_s_variations',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-border',
			'unit'     => 'px',
			'input_type' => 'number',
			'input_attrs'   => array(
				'min' => '1',
			),
		)
	)
);

// Border Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_border_color]', array(
		'type' => 'option',
		'default'   => '#e3e3e3',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_border_color]',
		array(
			'section'  => 'mk_s_pp_s_variations',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider 1.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_divider_1]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_divider_1]',
		array(
			'section' => 'mk_s_pp_s_variations',
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_var_box_model]', array(
		'type' => 'option',
		'default' => array(
			'padding_top' => 12,
			'padding_right' => 12,
			'padding_bottom' => 12,
			'padding_left' => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_var_box_model]',
		array(
			'section' => 'mk_s_pp_s_variations',
			'column'  => 'mk-col-12',
		)
	)
);
