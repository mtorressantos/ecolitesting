<?php
/**
 * Add Add to Cart Button section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 * @since 6.0.3 Add full width option.
 */

// Add to Cart Button dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_add_to_cart_button',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Add to Cart Button', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_atc_btn',
			'priority' => 70,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Show Icon.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_show_icon]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_show_icon]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
			'column'  => 'mk-col-4',
			'sublabel' => __( 'Show Icon', 'mk_framework' ),
		)
	)
);

// Full width.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_full_width]', array(
		'type' => 'option',
		'default' => 'false',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_full_width]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
			'column'  => 'mk-col-4',
			'sublabel' => __( 'Full Width', 'mk_framework' ),
		)
	)
);

// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_typography]', array(
		'type' => 'option',
		'default' => array(
			'family' => 'inherit',
			'size' => 12,
			'weight' => 700,
			'style' => 'normal',
			'color' => '#ffffff',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_typography]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
			'column'  => 'mk-col-12',
		)
	)
);

// Background Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_background_color]', array(
		'type' => 'option',
		'default'   => '#f97352',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_background_color]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Corner Radius.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_border_radius]', array(
		'type' => 'option',
		'default'   => 3,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_border_radius]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-corner-radius',
			'unit'     => 'px',
			'input_type' => 'number',
			'input_attrs'   => array(
				'min' => '0',
			),
		)
	)
);

// Border.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_border]', array(
		'type' => 'option',
		'default'   => 0,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_border]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-border',
			'unit'     => 'px',
			'input_type' => 'number',
			'input_attrs'   => array(
				'min' => '0',
			),
		)
	)
);

// Border Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_border_color]', array(
		'type' => 'option',
		'default'   => '#f97352',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_border_color]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);

// Icon Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_icon_color]', array(
		'type' => 'option',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_icon_color]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Divider 1.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_divider_1]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_divider_1]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
		)
	)
);

// Hover Label.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_label]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
			'label' => __( 'Hover Style', 'mk_framework' ),
			'icon' => 'mk-hover-style-arrow',
		)
	)
);

// Font Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_color_hover]', array(
		'type' => 'option',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-font-color',
		)
	)
);

// Background Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_background_color_hover]', array(
		'type' => 'option',
		'default'   => '#ae5039',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_background_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_border_color_hover]', array(
		'type' => 'option',
		'default'   => '#f97352',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_border_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Icon Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_icon_color_hover]', array(
		'type' => 'option',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_icon_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Divider 2.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_divider_2]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_divider_2]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_atc_btn_box_model]', array(
		'type' => 'option',
		'default' => array(
			'margin_top' => 0,
			'margin_right' => 0,
			'margin_bottom' => 0,
			'margin_left' => 0,
			'padding_top' => 13,
			'padding_right' => 30,
			'padding_bottom' => 13,
			'padding_left' => 30,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_atc_btn_box_model]',
		array(
			'section' => 'mk_s_pp_s_add_to_cart_button',
			'column'  => 'mk-col-12',
		)
	)
);
