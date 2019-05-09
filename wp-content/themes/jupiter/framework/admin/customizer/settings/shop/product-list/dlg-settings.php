<?php
/**
 * Add Settings section of Product List.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 * @since 6.0.3 Add Default option to Image Ratio.
 */

// Settings tab.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pl_settings',
		array(
			'mk_belong' => 'mk_s_pl_dialog',
			'mk_tab' => array(
				'id' => 'sh_pl_set',
				'text' => __( 'Settings', 'mk_framework' ),
			),
			'mk_child' => false,
			'priority' => 0,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Sidebar.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_sidebar]', array(
		'type' => 'option',
		'default'   => 'full',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_sidebar]',
		array(
			'label' => __( 'Sidebar', 'mk_framework' ),
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-6',
			'choices' => array(
				'full'  => __( 'No Sidebar', 'mk_framework' ),
				'left'  => __( 'Left Sidebar', 'mk_framework' ),
				'right' => __( 'Right Sidebar', 'mk_framework' ),
			),
		)
	)
);

// Archive sidebar.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_archive_sidebar]', array(
		'type'      => 'option',
		'default'   => 'full',
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_archive_sidebar]',
		array(
			'label'   => __( 'Archive Sidebar', 'mk_framework' ),
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-6',
			'choices' => array(
				'full'  => __( 'No Sidebar', 'mk_framework' ),
				'left'  => __( 'Left Sidebar', 'mk_framework' ),
				'right' => __( 'Right Sidebar', 'mk_framework' ),
			),
		)
	)
);

// Stretch to Full Width.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_full_width]', array(
		'type'      => 'option',
		'default'   => 'false',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_full_width]',
		array(
			'label' => __( 'Stretch to Full Width', 'mk_framework' ),
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-12',
		)
	)
);

// Item Hover Style.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_hover_style]', array(
		'type' => 'option',
		'default'   => 'none',
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_hover_style]',
		array(
			'label' => __( 'Item Hover Style', 'mk_framework' ),
			'section' => 'mk_s_pl_settings',
			'choices' => array(
				'none' => __( 'None', 'mk_framework' ),
				'alternate' => __( 'Alternate', 'mk_framework' ),
				'zoom' => __( 'Zoom', 'mk_framework' ),
			),
			'column'  => 'mk-col-6',
		)
	)
);

// Display Product Info Label.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_product_info]', array(
		'type' => 'option',
		'default'   => array(
			'.woocommerce-loop-product__title',
			'.price ins',
			'.price del',
			'.button',
			'.star-rating',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Checkbox_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_product_info]',
		array(
			'label' => __( 'Display Product Info', 'mk_framework' ),
			'section' => 'mk_s_pl_settings',
			'choices' => array(
				'.woocommerce-loop-product__title' => __( 'Product Name', 'mk_framework' ),
				'.price del' => __( 'Regular Price', 'mk_framework' ),
				'.price ins' => __( 'Sale Price', 'mk_framework' ),
				'.button' => __( 'Add to Cart Button', 'mk_framework' ),
				'.star-rating' => __( 'Rating', 'mk_framework' ),
			),
			'column'  => 'mk-col-12',
		)
	)
);

// Align Product Info.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_product_info_align]', array(
		'type' => 'option',
		'default'   => '',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Radio_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_product_info_align]',
		array(
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-5',
			'label'  => __( 'Align Product Info', 'mk_framework' ),
			'input_type'  => 'icon',
			'choices' => array(
				'left' => 'mk-left',
				'center' => 'mk-center',
				'right' => 'mk-right',
				'' => 'mk-close',
			),
		)
	)
);

// Image Ratio.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_image_ratio]', array(
		'type' => 'option',
		'default'   => 'default',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Radio_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_image_ratio]',
		array(
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-8',
			'input_type'  => 'button',
			'label'  => __( 'Image Ratio', 'mk_framework' ),
			'choices' => array(
				'default' => __( 'Default', 'mk_framework' ),
				'16_by_9' => __( '16:9', 'mk_framework' ),
				'3_by_2' => __( '3:2', 'mk_framework' ),
				'4_by_3' => __( '4:3', 'mk_framework' ),
				'1_by_1' => __( '1:1', 'mk_framework' ),
				'3_by_4' => __( '3:4', 'mk_framework' ),
				'2_by_3' => __( '2:3', 'mk_framework' ),
				'9_by_16' => __( '9:16', 'mk_framework' ),
			),
		)
	)
);

// Divider.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_divider]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_divider]',
		array(
			'section' => 'mk_s_pl_settings',
		)
	)
);

// Grid Settings Label.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_grid_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_grid_label]',
		array(
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-12',
			'label'  => 'Grid Settings',
		)
	)
);

// Columns.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_columns]', array(
		'type' => 'option',
		'default'   => apply_filters( 'loop_shop_columns', 4 ),
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_columns]',
		array(
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-6',
			'icon'    => 'mk-columns',
			'choices' => array(
				1 => __( '1 Column', 'mk_framework' ),
				2 => __( '2 Columns', 'mk_framework' ),
				3 => __( '3 Columns', 'mk_framework' ),
				4 => __( '4 Columns', 'mk_framework' ),
			),
		)
	)
);

// Row.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_rows]', array(
		'type' => 'option',
		'default'   => 3,
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_rows]',
		array(
			'section' => 'mk_s_pl_settings',
			'column'  => 'mk-col-6',
			'icon'    => 'mk-rows',
			'choices' => array(
				1 => __( '1 Row', 'mk_framework' ),
				2 => __( '2 Rows', 'mk_framework' ),
				3 => __( '3 Rows', 'mk_framework' ),
				4 => __( '4 Rows', 'mk_framework' ),
			),
		)
	)
);

// Horizontal space.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_horizontal_space]', array(
		'type' => 'option',
		'default'   => 30,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_horizontal_space]',
		array(
			'section'       => 'mk_s_pl_settings',
			'column'        => 'mk-col-3',
			'icon'          => 'mk-horizontal-space',
			'unit'          => 'px',
			'input_type'    => 'number',
			'input_attrs'   => array(
				'min' => '0',
			),
		)
	)
);

// Vertical space.
$wp_customize->add_setting(
	'mk_cz[sh_pl_set_vertical_space]', array(
		'type' => 'option',
		'default'   => 30,
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pl_set_vertical_space]',
		array(
			'section'       => 'mk_s_pl_settings',
			'column'        => 'mk-col-3',
			'icon'          => 'mk-vertical-space',
			'unit'          => 'px',
			'input_type'    => 'number',
			'input_attrs'   => array(
				'min' => '0',
			),
		)
	)
);
