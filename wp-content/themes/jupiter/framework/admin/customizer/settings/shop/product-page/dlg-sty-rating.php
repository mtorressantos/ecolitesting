<?php
/**
 * Add Rating section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Rating dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_rating',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Rating', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_rat',
			'priority' => 160,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Label.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_label]',
		array(
			'section' => 'mk_s_pp_s_rating',
			'label' => 'Star',
		)
	)
);

// Font size.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_font_size]', array(
		'type' => 'option',
		'default'   => 12,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_font_size]',
		array(
			'section' => 'mk_s_pp_s_rating',
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
	'mk_cz[sh_pp_sty_rat_star_color]', array(
		'type' => 'option',
		'default'   => '#ffc400',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_star_color]',
		array(
			'section'  => 'mk_s_pp_s_rating',
			'column'   => 'mk-col-3-alt',
			'text' => __( 'Star color', 'mk_framework' ),
		)
	)
);

// Active Star color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_active_star_color]', array(
		'type' => 'option',
		'default'   => '#ffc400',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_active_star_color]',
		array(
			'section'  => 'mk_s_pp_s_rating',
			'column'   => 'mk-col-5',
			'text' => __( 'Active Star color', 'mk_framework' ),
		)
	)
);

// Divider.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_divider]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_divider]',
		array(
			'section' => 'mk_s_pp_s_rating',
		)
	)
);

// Label.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_label]',
		array(
			'section' => 'mk_s_pp_s_rating',
			'label' => 'Text',
		)
	)
);

// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_typography]', array(
		'type' => 'option',
		'default' => array(
			'family' => 'inherit',
			'size'   => 14,
			'weight' => 400,
			'style'  => 'normal',
			'color'  => '#2e2e2e',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_typography]',
		array(
			'section' => 'mk_s_pp_s_rating',
			'column'  => 'mk-col-12',
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_rat_box_model]', array(
		'type' => 'option',
		'default' => array(
			'margin_top'    => 0,
			'margin_right'  => 0,
			'margin_bottom' => 25,
			'margin_left'   => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_rat_box_model]',
		array(
			'section' => 'mk_s_pp_s_rating',
			'column'  => 'mk-col-12',
		)
	)
);
