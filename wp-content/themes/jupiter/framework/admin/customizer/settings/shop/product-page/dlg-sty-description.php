<?php
/**
 * Add Description section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Description dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_descripiton',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Description', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_des',
			'priority' => 80,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_des_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size'   => 14,
		'weight' => 400,
		'style'  => 'normal',
		'color'  => '#888888',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_des_typography]',
		array(
			'section' => 'mk_s_pp_s_descripiton',
			'column'  => 'mk-col-12',
		)
	)
);

// Background color.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_des_background_color]', array(
	'type' => 'option',
	'default'   => 'rgba(255, 255, 255, 0)',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_des_background_color]',
		array(
			'section'  => 'mk_s_pp_s_descripiton',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_des_divider]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_des_divider]',
		array(
			'section' => 'mk_s_pp_s_descripiton',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_des_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top'     => 20,
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
		'mk_cz[sh_pp_sty_des_box_model]',
		array(
			'section' => 'mk_s_pp_s_descripiton',
			'column'  => 'mk-col-12',
		)
	)
);
