<?php
/**
 * Add Steps section of Checkout & Cart > Styles.
 * Prefixes: s -> shop, cc -> checkout-cart, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Steps dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_cc_s_steps',
		array(
			'mk_belong'       => 'mk_s_cc_dialog',
			'mk_tab'          => array(
				'id'   => 'sh_cc_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title'           => __( 'Steps', 'mk_framework' ),
			'mk_reset'        => 'sh_cc_sty_stp',
			'priority'        => 10,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Step Style.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_style]', array(
		'type'      => 'option',
		'default'   => 'number',
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_style]',
		array(
			'text'    => __( 'Step Style', 'mk_framework' ),
			'section' => 'mk_s_cc_s_steps',
			'choices' => array(
				'number' => __( 'Number', 'mk_framework' ),
				'icon'   => __( 'Icon', 'mk_framework' ),
			),
			'column'  => 'mk-col-12',
		)
	)
);

// Active Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_active_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_active_label]',
		array(
			'section'    => 'mk_s_cc_s_steps',
			'label'      => __( 'ACTIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color'      => 'green',
			'condition'  => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Icon size.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_icon_size]', array(
		'type'      => 'option',
		'default'   => 50,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_icon_size]',
		array(
			'section'     => 'mk_s_cc_s_steps',
			'column'      => 'mk-col-5',
			'text'        => __( 'Icon Size', 'mk_framework' ),
			'unit'        => __( 'px', 'mk_framework' ),
			'input_type'  => 'number',
			'input_attrs' => array(
				'min' => 0,
			),
			'condition'   => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Fill Color.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_fill_color]', array(
		'type'      => 'option',
		'default'   => 'rgba(21, 124, 242, 1)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_fill_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-2-alt',
			'icon'      => 'mk-icon-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_box_model]', array(
		'type'      => 'option',
		'default'   => array(
			'margin_top'    => 0,
			'margin_right'  => 0,
			'margin_bottom' => 60,
			'margin_left'   => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_box_model]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_typography_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_typography_label]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'label'     => 'Title',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_typography]', array(
		'type'      => 'option',
		'default'   => array(
			'family' => 'inherit',
			'size'   => 14,
			'weight' => 700,
			'style'  => 'normal',
			'color'  => '#888888',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_typography]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Passive Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_passive_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_passive_label]',
		array(
			'section'    => 'mk_s_cc_s_steps',
			'label'      => __( 'PASSIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color'      => 'yellow',
			'condition'  => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Passive Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_passive_text_color]', array(
		'type'      => 'option',
		'default'   => '#d8d8d8',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_passive_text_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-2-alt',
			'icon'      => 'mk-font-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Passive Background Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_passive_icon_color]', array(
		'type'      => 'option',
		'default'   => '#d8d8d8',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_passive_icon_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-2-alt',
			'icon'      => 'mk-icon-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'icon',
				),
			),
		)
	)
);

// Number Style --------------------------------------------------------------------
// Active Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_active_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_active_label]',
		array(
			'section'    => 'mk_s_cc_s_steps',
			'label'      => __( 'ACTIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color'      => 'green',
			'condition'  => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Number Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_active_number_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_active_number_label]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'label'     => 'Number',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Number Color.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_active_number_background_color]', array(
		'type'      => 'option',
		'default'   => '#157cf2',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_active_number_background_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'icon'      => 'mk-background-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_number_typography]', array(
		'type'      => 'option',
		'default'   => array(
			'family' => 'inherit',
			'size'   => 18,
			'weight' => 700,
			'style'  => 'normal',
			'color'  => '#ffffff',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_number_typography]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Title Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_active_title_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_active_title_label]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'label'     => 'Title',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Typography.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_icon_active_title_typography]', array(
		'type'      => 'option',
		'default'   => array(
			'family' => 'inherit',
			'size'   => 18,
			'weight' => 700,
			'style'  => 'normal',
			'color'  => '#888888',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_icon_active_title_typography]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_active_box_model]', array(
		'type'      => 'option',
		'default'   => array(
			'margin_top'    => 0,
			'margin_right'  => 0,
			'margin_bottom' => 60,
			'margin_left'   => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_active_box_model]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-12',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Passive Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_label]',
		array(
			'section'    => 'mk_s_cc_s_steps',
			'label'      => __( 'PASSIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color'      => 'yellow',
			'condition'  => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Number Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_number_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_number_label]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'label'     => 'Number',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Number Background Color.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_number_background_color]', array(
		'type'      => 'option',
		'default'   => '#d8d8d8',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_number_background_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-3',
			'icon'      => 'mk-background-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Text Color.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_number_text_color]', array(
		'type'      => 'option',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_number_text_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-3',
			'icon'      => 'mk-font-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);


// Title Label.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_title_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_title_label]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'label'     => 'Title',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);

// Title Color.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_stp_number_passive_title_color]', array(
		'type'      => 'option',
		'default'   => '#d8d8d8',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_stp_number_passive_title_color]',
		array(
			'section'   => 'mk_s_cc_s_steps',
			'column'    => 'mk-col-3',
			'icon'      => 'mk-font-color',
			'condition' => array(
				array(
					'setting' => 'mk_cz[sh_cc_sty_stp_style]',
					'value'   => 'number',
				),
			),
		)
	)
);
