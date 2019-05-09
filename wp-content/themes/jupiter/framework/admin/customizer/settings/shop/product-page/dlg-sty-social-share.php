<?php
/**
 * Add Social Share section of Product Page > Styles.
 * Prefixes: s -> shop, pp -> product-page, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Social Share dialog.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_s_social_share',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_sty',
				'text' => __( 'Styles', 'mk_framework' ),
			),
			'title' => __( 'Social Share', 'mk_framework' ),
			'mk_reset' => 'sh_pp_sty_soc_shr',
			'priority' => 120,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// Social networks.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_networks]', array(
		'type' => 'option',
		'transport' => 'postMessage',
		'default' => array(
			'facebook',
			'twitter',
			'pinterest',
			'linkedin',
			'googleplus',
			'reddit',
			'digg',
			'email',
		),
	)
);

$wp_customize->add_control(
	new MK_Checkbox_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_networks]',
		array(
			'section'       => 'mk_s_pp_s_social_share',
			'label'         => __( 'Social Networks to Display', 'mk_framework' ),
			'input_type'    => 'image',
			'choices' => array(
				'facebook' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-facebook.svg',
				'twitter' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-twitter.svg',
				'pinterest' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-pinterest.svg',
				'linkedin' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-linkedin.svg',
				'googleplus' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-googleplus.svg',
				'reddit' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-reddit.svg',
				'digg' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-digg.svg',
				'email' => THEME_CUSTOMIZER_URI . '/assets/icons/mk-share-email.svg',
			),
		)
	)
);

// Divider 1.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_divider_1]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_divider_1]',
		array(
			'section' => 'mk_s_pp_s_social_share',
		)
	)
);

// Fill Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_fill_color]', array(
		'type' => 'option',
		'default'   => 'rgba(34, 34, 34, 1)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_fill_color]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Background Color.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_background_color]', array(
		'type' => 'option',
		'default'   => 'rgba(200, 200, 200, 0)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_background_color]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Corner Radius.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_border_radius]', array(
		'type' => 'option',
		'default'   => 0, // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_border_radius]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
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
	'mk_cz[sh_pp_sty_soc_shr_border]', array(
		'type' => 'option',
		'default'   => 0, // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_border]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
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
	'mk_cz[sh_pp_sty_soc_shr_border_color]', array(
		'type' => 'option',
		'default'   => 'rgba(34, 34, 34, 0)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_border_color]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider 2.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_divider_2]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_divider_2]',
		array(
			'section' => 'mk_s_pp_s_social_share',
		)
	)
);

// Hover Label.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_label]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_label]',
		array(
			'section' => 'mk_s_pp_s_social_share',
			'label' => __( 'Hover Style', 'mk_framework' ),
			'icon'     => 'mk-hover-style-arrow',
		)
	)
);

// Fill Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_fill_color_hover]', array(
		'type' => 'option',
		'default'   => 'rgba(34, 34, 34, 1)', // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_fill_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Background Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_background_color_hover]', array(
		'type' => 'option',
		'default'   => 'rgba(200, 200, 200, 0)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_background_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border Color Hover.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_border_color_hover]', array(
		'type' => 'option',
		'default'   => 'rgba(34, 34, 34, 0)',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_border_color_hover]',
		array(
			'section'  => 'mk_s_pp_s_social_share',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Divider 3.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_divider_3]', array(
		'type' => 'option',
	)
);

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_divider_3]',
		array(
			'section' => 'mk_s_pp_s_social_share',
		)
	)
);

// Box Model.
$wp_customize->add_setting(
	'mk_cz[sh_pp_sty_soc_shr_box_model]', array(
		'type' => 'option',
		'default' => array(
			'margin_top' => 20,
			'margin_right' => 0,
			'margin_bottom' => 20,
			'margin_left' => 0,
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'mk_cz[sh_pp_sty_soc_shr_box_model]',
		array(
			'section' => 'mk_s_pp_s_social_share',
			'column'  => 'mk-col-12',
		)
	)
);
