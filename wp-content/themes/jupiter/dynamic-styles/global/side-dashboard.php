<?php

global $mk_options;

$links_hover_color = (!empty($mk_options['dash_links_hover_color'])) ? $mk_options['dash_links_hover_color'] : $mk_options['skin_color'];

/* Side dashboard menu typography options */
$top_menu_text_size_property   = ( ! empty( $mk_options['dash_top_menu_text_size'] ) ) ? 'font-size: ' . $mk_options['dash_top_menu_text_size'] . 'px' : '';
$top_menu_text_weight_property = ( ! empty( $mk_options['dash_top_menu_text_weight'] ) ) ? 'font-weight: ' . $mk_options['dash_top_menu_text_weight'] : '';
$top_menu_transform_property   = ( ! empty( $mk_options['dash_top_menu_transform'] ) ) ? 'text-transform: ' . $mk_options['dash_top_menu_transform'] : '';
$sub_menu_text_size_property   = ( ! empty( $mk_options['dash_sub_menu_text_size'] ) ) ? 'font-size: ' . $mk_options['dash_sub_menu_text_size'] . 'px' : '';
$sub_menu_text_weight_property = ( ! empty( $mk_options['dash_sub_menu_text_weight'] ) ) ? 'font-weight: ' . $mk_options['dash_sub_menu_text_weight'] : '';
$sub_menu_transform_property   = ( ! empty( $mk_options['dash_sub_menu_transform'] ) ) ? 'text-transform: ' . $mk_options['dash_sub_menu_transform'] : '';

Mk_Static_Files::addGlobalStyle("

.mk-side-dashboard {
	background-color: {$mk_options['dash_bg_color']};
}

.mk-side-dashboard,
.mk-side-dashboard p
{
		font-size: {$mk_options['dash_text_size']}px;
		color: {$mk_options['dash_text_color']};
		font-weight: {$mk_options['dash_text_weight']};
}



.mk-side-dashboard .widgettitle
{
		text-transform: {$mk_options['dash_title_transform']};
		font-size: {$mk_options['dash_title_size']}px;
		color: {$mk_options['dash_title_color']};
		font-weight: {$mk_options['dash_title_weight']};
}



.mk-side-dashboard .widgettitle a
{
		color: {$mk_options['dash_title_color']};
}



.mk-side-dashboard .widget a
{
		color: {$mk_options['dash_links_color']};

}

.sidedash-navigation-ul li a {
	{$top_menu_text_size_property};
	{$top_menu_text_weight_property};
	{$top_menu_transform_property}
}

.sidedash-navigation-ul .sub-menu li a {
	{$sub_menu_text_size_property};
	{$sub_menu_text_weight_property};
	{$sub_menu_transform_property}
}

.sidedash-navigation-ul li a,
.sidedash-navigation-ul li .mk-nav-arrow {
	color:{$mk_options['dash_nav_link_color']};
}

.sidedash-navigation-ul li a:hover {
	color:{$mk_options['dash_nav_link_hover_color']};
	background-color:{$mk_options['dash_nav_bg_hover_color']};
}

.mk-side-dashboard .widget:not(.widget_social_networks) a:hover
{
	color: {$links_hover_color};
}

");