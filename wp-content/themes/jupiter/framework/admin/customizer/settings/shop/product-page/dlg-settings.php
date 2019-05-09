<?php
/**
 * Add Settings section of Product Page.
 * Prefixes: s -> shop, pp -> product-page
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 * @since 6.0.3
 *        Add new product layouts.
 *        Add sticky product info toggle option.
 */

// Settings tab.
$wp_customize->add_section(
	new MK_Dialog(
		$wp_customize,
		'mk_s_pp_settings',
		array(
			'mk_belong' => 'mk_s_pp_dialog',
			'mk_tab' => array(
				'id' => 'sh_pp_set',
				'text' => __( 'Settings', 'mk_framework' ),
			),
			'mk_child' => false,
			'priority' => 0,
			'active_callback' => 'mk_cz_hide_section',
		)
	)
);

// product Page Layout.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_layout]', array(
		'type' => 'option',
		'default'   => '1',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Radio_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_layout]',
		array(
			'label' => __( 'Layout', 'mk_framework' ),
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-12',
			'input_type'  => 'image',
			'choices' => array(
				1 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-1.svg',
				// 2 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-2.svg',
				3 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-3.svg',
				4 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-4.svg',
				5 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-5.svg',
				// 6 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-5.svg',
				7 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-7.svg',
				8 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-8.svg',
				9 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-9.svg',
				10 => THEME_CUSTOMIZER_URI . '/assets/icons/mk-layout-10.svg',
			),
		)
	)
);

// Sidebar.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_sidebar]', array(
		'type' => 'option',
		'default'   => 'full',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Select_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_sidebar]',
		array(
			'label' => __( 'Sidebar', 'mk_framework' ),
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'choices' => array(
				'full' => __( 'No Sidebar', 'mk_framework' ),
				'left' => __( 'Left Sidebar', 'mk_framework' ),
				'right' => __( 'Right Sidebar', 'mk_framework' ),
			),
		)
	)
);

// Display Product Info.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_product_info]', array(
		'type' => 'option',
		'default'   => array(
			'.summary .price ins',
			'.summary .price del',
			'.summary .woocommerce-product-rating',
			'.summary .product_meta .posted_in',
			'.summary .product_meta .tagged_as',
			'.summary .product_meta .sku_wrapper',
			'.summary .woocommerce-product-details__short-description',
			'.summary .cart .quantity',
			'.summary .variations',
			'.summary .social-share',
			'.woocommerce-tabs #tab-title-description|.woocommerce-tabs #tab-description',
			'.woocommerce-tabs #tab-title-reviews|.woocommerce-tabs #tab-reviews',
			'.woocommerce-tabs #tab-title-additional_information|.woocommerce-tabs #tab-additional_information',
		),
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Checkbox_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_product_info]',
		array(
			'label' => __( 'Display Product Info', 'mk_framework' ),
			'section' => 'mk_s_pp_settings',
			'choices' => array(
				'.summary .price del' => __( 'Regular Price', 'mk_framework' ),
				'.summary .price ins' => __( 'Sale Price', 'mk_framework' ),
				'.summary .woocommerce-product-rating' => __( 'Rating', 'mk_framework' ),
				'.summary .product_meta .posted_in' => __( 'Category', 'mk_framework' ),
				'.summary .product_meta .tagged_as' => __( 'Tags', 'mk_framework' ),
				'.summary .product_meta .sku_wrapper' => __( 'SKU', 'mk_framework' ),
				'.summary .woocommerce-product-details__short-description' => __( 'Product Description', 'mk_framework' ),
				'.summary .variations' => __( 'Product Options', 'mk_framework' ),
				'.summary .cart .quantity' => __( 'Quantity', 'mk_framework' ),
				'.summary .social-share' => __( 'Social Share', 'mk_framework' ),
				'.woocommerce-tabs #tab-title-description|.woocommerce-tabs #tab-description' => __( 'Description', 'mk_framework' ),
				'.woocommerce-tabs #tab-title-reviews|.woocommerce-tabs #tab-reviews' => __( 'Review', 'mk_framework' ),
				'.woocommerce-tabs #tab-title-additional_information|.woocommerce-tabs #tab-additional_information' => __( 'Additional Info', 'mk_framework' ),
			),
			'column'  => 'mk-col-12',
		)
	)
);

// Sticky Product Info.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_sticky_info_enabled]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_sticky_info_enabled]',
		array(
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'label' => __( 'Sticky Product Info (layout 9/10)', 'mk_framework' ),
		)
	)
);

// Product Lightbox.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_photoswipe_enabled]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_photoswipe_enabled]',
		array(
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'label' => __( 'Product Lightbox', 'mk_framework' ),
		)
	)
);

// Product Magnifier.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_zoom_enabled]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'refresh',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_zoom_enabled]',
		array(
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'label' => __( 'Product Zoom', 'mk_framework' ),
		)
	)
);

// Related Products.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_related_products_enabled]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_related_products_enabled]',
		array(
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'label' => __( 'Related Products', 'mk_framework' ),
		)
	)
);

// Up Sells.
$wp_customize->add_setting(
	'mk_cz[sh_pp_set_up_sells_enabled]', array(
		'type' => 'option',
		'default' => 'true',
		'transport' => 'postMessage',
	)
);

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'mk_cz[sh_pp_set_up_sells_enabled]',
		array(
			'section' => 'mk_s_pp_settings',
			'column'  => 'mk-col-6',
			'label' => __( 'Up-Sells', 'mk_framework' ),
		)
	)
);
