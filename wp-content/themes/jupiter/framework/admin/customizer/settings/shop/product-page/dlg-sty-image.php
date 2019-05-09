<?php
/**
 * Add Image section of Product Page > Styles.
 * Prefiesx: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 * @since 6.0.3 Add default margin button in box model.
 */

// Image dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_image',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Image', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_img',
			'priority' => 10,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Image Ratio Label.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_ratio_label]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_ratio_label]',
		array(
			'section' => 'mk_s_pp_s_image',
			'label' => __( 'Image Ratio', 'mk_framework' ),
		)
	)
);

// Image Ratio.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_image_ratio]', array(
	'type' => 'option',
	'default'   => 'default',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Radio_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_image_ratio]',
		array(
			'section' => 'mk_s_pp_s_image',
			'column' => 'mk-col-6',
			'input_type' => 'button',
			'choices' => array(
				'default' => __( 'Default', 'mk_framework' ),
				'3_by_2' => __( '3:2', 'mk_framework' ),
				'1_by_1' => __( '1:1', 'mk_framework' ),
				'2_by_3' => __( '2:3', 'mk_framework' ),
				'9_by_16' => __( '9:16', 'mk_framework' ),
			),
		)
	)
);

// Background color.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_background_color]', array(
	'type' => 'option',
	'default'   => 'rgba(255, 255, 255, 0)',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_background_color]',
		array(
			'section'  => 'mk_s_pp_s_image',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border width.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_border_width]', array(
	'type' => 'option',
	'default'   => 0,
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_border_width]',
		array(
			'section' => 'mk_s_pp_s_image',
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
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_border_color]', array(
	'type' => 'option',
	'default'   => '#fff',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_border_color]',
		array(
			'section'  => 'mk_s_pp_s_image',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider 1.
$wp_customize->add_setting(
	'mk_cz[sh_cc_sty_img_divider_1]', array(
		'type' => 'option',
		'column'   => 'mk-col-12 mk-divider-plain',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_img_divider_1]',
		array(
			'section' => 'mk_s_pp_s_image',
		)
	)
);

// Gallery Thumbnail Orientation.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_orientation]', array(
	'type' => 'option',
	'default'   => 'horizontal',
	'transport' => 'refresh',
) );

$wp_customize->add_control(
	new MK_Radio_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_orientation]',
		array(
			'section' => 'mk_s_pp_s_image',
			'column'  => 'mk-col-6',
			'label' => __( 'Gallery Thumbnail Orientation', 'mk_framework' ),
			'input_type'  => 'icon',
			'choices' => array(
				'vertical' => 'mk-thumbnails-vertical',
				'horizontal' => 'mk-thumbnails-horizontal',
				'none' => 'mk-thumbnails-none',
			),
		)
	)
);

// Divider.
$wp_customize->add_setting( 'mk_cz[sh_cc_sty_img_divider_2]', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_cc_sty_img_divider_2]',
		array(
			'section' => 'mk_s_pp_s_image',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'mk_cz[sh_pp_sty_img_box_model]', array(
	'type' => 'option',
	'default' => array(
		'margin_top' => 0,
		'margin_right' => 0,
		'margin_bottom' => 33,
		'margin_left' => 0,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_img_box_model]',
		array(
			'section' => 'mk_s_pp_s_image',
			'column'  => 'mk-col-12',
		)
	)
);
