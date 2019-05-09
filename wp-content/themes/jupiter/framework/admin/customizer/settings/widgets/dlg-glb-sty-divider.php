<?php
/**
 * Add Divider section of Checkout & Cart > Styles.
 * Prefix: s -> shop, cc -> checkout-cart, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_w_gs_divider',
		array(
			'mk_belong' => 'mk_w_s_dialog',
			'mk_tab' => array(
				'id' => 'wg_glb_sty',
				'text' => __( 'Global Styles', 'mk_framework' ),
			),
			'title' => __( 'Divider', 'mk_framework' ),
			'mk_reset' => 'wg_glb_sty_div',
			'priority' => 100,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);


// Border width.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_div_border_width]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_div_border_width]',
		array(
			'section' => 'mk_w_gs_divider',
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

// Border Color.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_div_border_color]', array(
	'type' => 'option',
	'default'   => '#d5d8de',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_div_border_color]',
		array(
			'section'  => 'mk_w_gs_divider',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);


// Divider.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_div_divider]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_div_divider]',
		array(
			'section' => 'mk_w_gs_divider',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[wg_glb_sty_div_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_bottom' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[wg_glb_sty_div_box_model]',
		array(
			'section' => 'mk_w_gs_divider',
			'column'  => 'mk-col-12',
		)
	)
);
