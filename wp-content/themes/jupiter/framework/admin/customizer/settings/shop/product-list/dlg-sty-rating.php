<?php
/**
 * Add Rating section of Product List > Styles.
 * Prefixes: s -> shop, pl -> product-list, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Rating dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pl_s_rating',
		array(
			'mk_belong' => 'mk_s_pl_dialog',
			'mk_tab' => array(
				'id' => 'sh_pl_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Rating', 'mk_framework' ),
			'mk_reset' => 'sh_pl_sty_rat',
			'priority' => 90,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Font size.
$wp_customize->add_setting(
	'mk_cz[sh_pl_sty_rat_font_size]', array(
		'type' => 'option',
		'default'   => 15,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_rat_font_size]',
		array(
			'section' => 'mk_s_pl_s_rating',
			'column'  => 'mk-col-3-alt',
			'text' => __( 'Size', 'mk_framework' ),
			'unit' => __( 'px', 'mk_framework' ),
			'input_type' => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
		)
	)
);

// Star color.
$wp_customize->add_setting(
	'mk_cz[sh_pl_sty_rat_star_color]', array(
		'type' => 'option',
		'default'   => '#ffc400',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_rat_star_color]',
		array(
			'section'  => 'mk_s_pl_s_rating',
			'column'   => 'mk-col-3-alt',
			'text' => __( 'Star color', 'mk_framework' ),
		)
	)
);

// Active Star color.
$wp_customize->add_setting(
	'mk_cz[sh_pl_sty_rat_active_star_color]', array(
		'type' => 'option',
		'default'   => '#ffc400',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_rat_active_star_color]',
		array(
			'section'  => 'mk_s_pl_s_rating',
			'column'   => 'mk-col-5',
			'text' => __( 'Active Star color', 'mk_framework' ),
		)
	)
);

// Divider.
$wp_customize->add_setting(
	'mk_cz[sh_pl_sty_rat_divider]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_rat_divider]',
		array(
			'section' => 'mk_s_pl_s_rating',
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_pl_sty_rat_box_model]', array(
		'type' => 'option',
		'default' => array(
			'margin_top'     => 0,
			'margin_right'   => 0,
			'margin_bottom'  => 6,
			'margin_left'    => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pl_sty_rat_box_model]',
		array(
			'section' => 'mk_s_pl_s_rating',
			'column'  => 'mk-col-12',
		)
	)
);
