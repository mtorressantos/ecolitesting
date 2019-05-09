<?php
/**
 * Add Widgets Styles section.
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

// Widgets Styles section (it's hidden by default).
$wp_customize->add_section( 'mk_widgets_styles' , array(
	'title'  => __( 'Styles','mk-framework' ),
	'panel' => 'widgets',
	'priority' => 500,
) );

// Widgets Styles dialogs.
$wp_customize->add_setting( 'mk_widgets_styles' );

$wp_customize->add_control(
	new MK_dialog_Control(
		$wp_customize,
		'mk_widgets_styles',
		array(
			'section' => 'mk_widgets_styles',
			'buttons' => array(
				'mk_w_s_dialog' => __( 'Styles', 'mk_framework' ),
			),
			'column'  => '',
		)
	)
);

// Dialogs.
$dialogs = glob( dirname( __FILE__ ) . '/dlg-*.php' );

// Load all the dialogs.
foreach ( $dialogs as $dialog ) {
	require_once $dialog;
}
