<?php
/**
 * Add Name section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Name dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_name',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Name', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_nam',
			'priority' => 20,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_nam_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size' => 48,
		'weight' => 400,
		'style' => 'normal',
		'color' => '#000000',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_nam_typography]',
		array(
			'section' => 'mk_s_pp_s_name',
			'column'  => 'mk-col-12',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_nam_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 16,
		'margin_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_nam_box_model]',
		array(
			'section' => 'mk_s_pp_s_name',
			'column'  => 'mk-col-12',
		)
	)
);

