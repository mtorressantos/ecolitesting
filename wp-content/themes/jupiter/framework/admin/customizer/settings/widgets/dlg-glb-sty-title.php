<?php
/**
 * Add Title dialog of Widgets Styles.
 * Prefix: w -> widgets, gs -> global-styles, t -> title.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Title dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_w_gs_title',
		array(
			'mk_belong' => 'mk_w_s_dialog',
			'mk_tab' => array(
				'id' => 'wg_glb_sty',
				'text' => __( 'Global Styles', 'mk_framework' ),
			),
			'title' => __( 'Title', 'mk_framework' ),
			'mk_reset' => 'wg_glb_sty_ttl',
			'priority' => 10,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_ttl_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 14,
		'weight' => 700,
		'style' => 'normal',
		'color' => '#333333',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_ttl_typography]',
		array(
			'section' => 'mk_w_gs_title',
			'column'  => 'mk-col-12',
		)
	)
);

// Line Height.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_ttl_line_height]', array(
	'type' => 'option',
	'default'   => 1.66,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_ttl_line_height]',
		array(
			'section' => 'mk_w_s_title',
			'column'  => 'mk-col-5',
			'text' => __( 'Line Height', 'mk_framework' ),
			'unit' => __( 'em', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_ttl_divider]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_ttl_divider]',
		array(
			'section' => 'mk_w_gs_title',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_ttl_box_model]', array(
	'type' => 'option',
	'default' => array(
		'padding_top' => 0,
		'padding_right' => 0,
		'padding_bottom' => 15,
		'padding_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_ttl_box_model]',
		array(
			'section' => 'mk_w_gs_title',
			'column'  => 'mk-col-12',
		)
	)
);
