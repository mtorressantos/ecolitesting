<?php
/**
 * Add Box section of Product List > Styles.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Box dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pl_s_box',
		array(
			'mk_belong' => 'mk_s_pl_dialog',
			'mk_tab' => array(
				'id' => 'sh_pl_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Box', 'mk_framework' ),
			'mk_reset' => 'sh_pl_sty_box',
			'priority' => 10,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Background color.
$wp_customize->add_setting( 'mk_cz[sh_pl_sty_box_background_color]', array(
	'type' => 'option',
	'default'   => '#FFFFFF',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_box_background_color]',
		array(
			'section'  => 'mk_s_pl_s_box',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border radius.
$wp_customize->add_setting( 'mk_cz[sh_pl_sty_box_border_radius]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_box_border_radius]',
		array(
			'section'  => 'mk_s_pl_s_box',
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
$wp_customize->add_setting( 'mk_cz[sh_pl_sty_box_border_width]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_box_border_width]',
		array(
			'section'  => 'mk_s_pl_s_box',
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
$wp_customize->add_setting( 'mk_cz[sh_pl_sty_box_border_color]', array(
	'type' => 'option',
	'default'   => '#FFFFFF',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_box_border_color]',
		array(
			'section'  => 'mk_s_pl_s_box',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_pl_sty_box_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top'     => 0,
		'margin_right'   => 0,
		'margin_bottom'  => 0,
		'margin_left'    => 0,
		'padding_top'    => 0,
		'padding_right'  => 0,
		'padding_bottom' => 0,
		'padding_left'   => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_box_box_model]',
		array(
			'section' => 'mk_s_pl_s_box',
			'column'  => 'mk-col-12',
		)
	)
);
