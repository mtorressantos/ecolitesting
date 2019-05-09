<?php
/**
Add padding to header stretcher which is enabled when header becomes fixed.
Fills the gap that is created by taking element out of layout.
 */

global $mk_options;

// Flags.
// $is_sticky = !empty($mk_options['header_sticky_style']); AM-2177.
$is_sticky = isset( $mk_options['header_sticky_style'] );
$has_header = is_header_show(); // don't like gramma here
$has_header_toolbar = (is_header_toolbar_show() === 'true'); // make bool.
$is_style_2 = (get_header_style() === '2');

// Quit if no need to fill the gap.
if ( ! $has_header || ! $is_sticky || is_header_transparent() ) {
	return false;
}


$header_components_height = $mk_options['header_height'];
$header_components_height++; // border-bottom of mk-header-inner.
if ( $has_header_toolbar ) {
	$header_components_height += 35;
}
if ( $is_style_2 ) {
	$header_components_height += 50; // comes directly from css.
}



Mk_Static_Files::addLocalStyle(
	"
	.header-style-1 .mk-header-padding-wrapper,
	.header-style-2 .mk-header-padding-wrapper,
	.header-style-3 .mk-header-padding-wrapper {
		padding-top:{$header_components_height}px;
	}
"
);


/*
Making process steps responisve both to column width and window width.
We need to keep it in HTML document since CDN servers create problem with this markup.
*/
Mk_Static_Files::addLocalStyle(
	'
	.mk-process-steps[max-width~="950px"] ul::before {
	  display: none !important;
	}
	.mk-process-steps[max-width~="950px"] li {
	  margin-bottom: 30px !important;
	  width: 100% !important;
	  text-align: center;
	}
	.mk-event-countdown-ul[max-width~="750px"] li {
	  width: 90%;
	  display: block;
	  margin: 0 auto 15px;
	}
'
);


/*
Remove extra height from VC controls. Somehow this is not reproducable with other WP themes!
*/
Mk_Static_Files::addLocalStyle(
	'
	.compose-mode .vc_element-name .vc_btn-content {
    	height: auto !important;
	}
'
);


