<?php
/**
 * Add Shop section.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Shop section.
// @codingStandardsIgnoreStart
$wp_customize->add_section( 'mk_shop' , array(
	'title' => __( 'Shop','mk-framework' ),
	'priority' => 500,
	'description_hidden' => true,
	'description' => sprintf(
		__( 'This section allows you to modify product list, product page and checkout pages settings. To learn more about this feature, please read <a href="%s" target="_blank">Shop Customizer</a> article.', 'mk_framework' ),
		esc_url( 'https://themes.artbees.net/docs/shop-customizer' )
	),
) );

// Shop notice.
$wp_customize->add_setting( 'mk_shop_notice', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_Alert_Control(
		$wp_customize,
		'mk_shop_notice',
		array(
			'section' => 'mk_shop',
			'description' => sprintf(
				__( 'To use <a href="%1$s" target="_blank">Shop Customizer</a>, please install/activate <a href="%2$s" target="_blank">WooCommerce</a> plugin from <a href="%3$s" target="_blank">Plugins</a> page.', 'mk_framework' ),
				esc_url( 'https://themes.artbees.net/docs/shop-customizer' ),
				esc_url( 'https://wordpress.org/plugins/woocommerce' ),
				esc_url( admin_url( 'plugins.php' ) )
			),
			'active_callback' => 'mk_cz_wc_is_disabled',
			'column' => '',
		)
	)
);
// @codingStandardsIgnoreEnd

// Shop pages dialogs.
$wp_customize->add_setting( 'mk_shop_pages', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_dialog_Control(
		$wp_customize,
		'mk_shop_pages',
		array(
			'label' => __( 'Shop Pages', 'mk_framework' ),
			'section' => 'mk_shop',
			'buttons' => array(
				'mk_s_pl_dialog' => __( 'Product List', 'mk_framework' ),
				'mk_s_pp_dialog' => __( 'Product Page', 'mk_framework' ),
				'mk_s_cc_dialog' => __( 'Checkout & Cart', 'mk_framework' ),
			),
			'column'  => '',
			'active_callback' => 'mk_cz_wc_is_enabled',
		)
	)
);

// Widgets Styles dialog shortcut.
$wp_customize->add_setting( 'mk_shop_widgets_styles', array(
	'type' => 'option',
) );

$wp_customize->add_control(
	new MK_dialog_Control(
		$wp_customize,
		'mk_shop_widgets_styles',
		array(
			'label' => __( 'Widgets Styles', 'mk_framework' ),
			'section' => 'mk_shop',
			'buttons' => array(
				'mk_w_s_dialog' => __( 'Styles', 'mk_framework' ),
			),
			'button_only' => true,
			'column'  => '',
			'active_callback' => 'mk_cz_wc_is_enabled',
		)
	)
);

// Dialogs.
$dialogs = glob( dirname( __FILE__ ) . '/*/dlg-*.php' );

// Load all the dialogs.
foreach ( $dialogs as $dialog ) {
	require_once( $dialog );
}
