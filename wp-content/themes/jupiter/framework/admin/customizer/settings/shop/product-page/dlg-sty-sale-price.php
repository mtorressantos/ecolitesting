<?php
/**
 * Add Sale Price section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Sale Price dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_sale_price',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Sale Price', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_sal_prc',
			'priority' => 40,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Typography.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_sal_prc_typography]', array(
	'type' => 'option',
	'default' => array(
		'family' => 'inherit',
		'size'   => 30,
		'weight' => 400,
		'style'  => 'normal',
		'color'  => '#ff3d00',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_sal_prc_typography]',
		array(
			'section' => 'mk_s_pp_s_sale_price',
			'column'  => 'mk-col-12',
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_sal_prc_divider]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_sal_prc_divider]',
		array(
			'section' => 'mk_s_pp_s_sale_price',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_sal_prc_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top'    => 0,
		'margin_right'  => 0,
		'margin_bottom' => 0,
		'margin_left'   => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_sal_prc_box_model]',
		array(
			'section' => 'mk_s_pp_s_sale_price',
			'column'  => 'mk-col-12',
		)
	)
);
