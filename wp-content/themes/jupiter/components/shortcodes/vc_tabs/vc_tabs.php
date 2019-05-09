<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $heading_title
 * @var $style
 * @var $orientation
 * @var $tab_location
 * @var $responsive
 * @var $container_bg_color
 * @var $visibility
 * @var $el_class
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Tabs
 */
$title = $interval = $el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$id = Mk_Static_Files::shortcode_id();

wp_enqueue_script( 'jquery-ui-tabs' );

$el_class = $this->getExtraClass( $el_class );

$element = 'wpb_tabs mk-tabs-' . $id;
$element_id = 'mk-tabs';
if ( 'vc_tour' === $this->shortcode ) {
	$element = 'wpb_tour';
	$element_id = '';
}

// Extract tab titles.
preg_match_all( '/vc_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

// vc_tabs.
if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}
$tabs_nav = '';
$tabs_nav .= '<ul class="wpb_tabs_nav ui-tabs-nav vc_clearfix">';
foreach ( $tab_titles as $tab ) {
	$tab_atts = shortcode_parse_atts( $tab[0] );
	if ( isset( $tab_atts['title'] ) ) {
		$tabs_nav .= '<li><a href="#tab-' . ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) ) . '">';
		$tabs_nav .= ( ! empty( $tab_atts['icon'] ) ) ? Mk_SVG_Icons::get_svg_icon_by_class_name( false,  $tab_atts['icon'], 16 ) : '' ;
		$tabs_nav .= $tab_atts['title'];
		$tabs_nav .= '</a></li>';
	}
}
$tabs_nav .= '</ul>';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element ' . $el_class ), $this->settings['base'], $atts );

if ( 'vc_tour' === $this->shortcode ) {
	$next_prev_nav = '<div class="wpb_tour_next_prev_nav vc_clearfix"> <span class="wpb_prev_slide"><a href="#prev" title="' . __( 'Previous tab', 'js_composer' ) . '">' . __( 'Previous tab', 'js_composer' ) . '</a></span> <span class="wpb_next_slide"><a href="#next" title="' . __( 'Next tab', 'js_composer' ) . '">' . __( 'Next tab', 'js_composer' ) . '</a></span></div>';
} else {
	$next_prev_nav = '';
}

if ( ! empty( $visibility ) ) {
	$css_class .= ' ' . $visibility;
}

if ( ! empty( $responsive ) ) {
	$css_class .= ' mobile-' . $responsive;
}

if ( ! empty( $style ) ) {
	$css_class .= ' ' . $style . '-style';
}

if ( ! empty( $orientation ) ) {
	$css_class .= ' ' . $orientation . '-style';
}

if ( ( ! empty( $orientation ) && 'vertical' === $orientation ) && ! empty( $tab_location ) ) {
	$css_class .= ' vertical-' . $tab_location;
}

if ( ! empty( $container_bg_color ) ) {
	Mk_Static_Files::addCSS('
		#mk-tabs.mk-tabs-' . $id . ' .wpb_tabs_nav .ui-state-active a,
		#mk-tabs.mk-tabs-' . $id . ' .wpb_tab {
			background-color: ' . $container_bg_color . '
		}
		#mk-tabs.mk-tabs-' . $id . '.default-style.horizontal-style .wpb_tabs_nav .ui-state-active a {
			border-bottom-color: ' . $container_bg_color . ';
		}
		#mk-tabs.mk-tabs-' . $id . '.vertical-left .wpb_tabs_nav .ui-state-active a {
			border-right-color: ' . $container_bg_color . ';
		}
		#mk-tabs.mk-tabs-' . $id . '.vertical-right .wpb_tabs_nav .ui-state-active a {
			border-left-color: ' . $container_bg_color . ';
		}
	', $id);
}

$output = '<div id="' . $element_id . '" class="' . $css_class . '" data-interval="' . $interval . '">';

if ( ! empty( $heading_title ) ) {
	$output .= '<h3 class="mk-fancy-title pattern-style">
		<span>' . $heading_title . '</span>
	</h3>';
}

$output .=	'<div class="wpb_wrapper wpb_tour_tabs_wrapper ui-tabs vc_clearfix">
			' . wpb_widget_title( array(
						'title' => $title,
						'extraclass' => $element . '_heading',
					) )
	. $tabs_nav
	. wpb_js_remove_wpautop( $content )
	. $next_prev_nav . '
		</div>
	</div>
';

echo $output;
