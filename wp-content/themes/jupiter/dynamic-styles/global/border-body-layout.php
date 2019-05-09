<?php

global $mk_options;

if ( ! empty( $mk_options['body_border'] ) && 'true' == $mk_options['body_border'] ) {
	$min_width = ( 'true' == $mk_options['body_border_on_mobile_devices'] ) ? 0 : 768;
	$body_border_thickness = empty( $mk_options['body_border_thickness'] ) ? '1' : $mk_options['body_border_thickness'];
	$body_border_color = empty( $mk_options['body_border_color'] ) ? 'black' : $mk_options['body_border_color'];
	$min_responsive_nav_width = $mk_options['responsive_nav_width'] + 1;
	$max_responsive_nav_width = $mk_options['responsive_nav_width'];

	Mk_Static_Files::addGlobalStyle(
		"
	@media handheld, only screen and (min-width: {$min_width}px)
	{
		.border-body
		{
			background: {$body_border_color};
			display: block;
			float: left;
			clear: both;
			position: fixed;
			z-index: 300;
		}

		.border-body--top,
		.border-body--bottom
		{
			height: {$body_border_thickness}px;
			width: 100%;
		}
		.border-body--top
		{
			top: 0;
		}
		.admin-bar .border-body--top
		{
			top: 32px;
		}
		.admin-bar .sticky-style-fixed.header-style-1 .mk-header-holder,
		.admin-bar .sticky-style-fixed.header-style-3 .mk-header-holder,
		.admin-bar .sticky-style-fixed.header-style-1.a-sticky.toolbar-false .mk-header-holder,
		.admin-bar .sticky-style-fixed.header-style-3.a-sticky.toolbar-false .mk-header-holder
		{
			top: calc({$body_border_thickness}px + 32px);
		}
		.admin-bar .sticky-style-fixed.header-style-1.a-sticky .mk-header-holder,
		.admin-bar .sticky-style-fixed.header-style-3.a-sticky .mk-header-holder
		{
			top: calc({$body_border_thickness}px - 3px);
		}
		.sticky-style-fixed.header-style-1 .mk-header-holder,
		.sticky-style-fixed.header-style-3 .mk-header-holder,
		.sticky-style-fixed.header-style-1.a-sticky.toolbar-false .mk-header-holder,
		.sticky-style-fixed.header-style-3.a-sticky.toolbar-false .mk-header-holder,
		.header-style-2.a-sticky .mk-header-nav-container,
		#mk-boxed-layout
		{
			top: {$body_border_thickness}px;
		}
		.sticky-style-fixed.header-style-1.a-sticky .mk-header-holder,
		.sticky-style-fixed.header-style-3.a-sticky .mk-header-holder
		{
			top: calc(-32px + {$body_border_thickness}px);
		}
		.admin-bar .header-style-2.a-sticky .mk-header-nav-container,
		.admin-bar .header-style-4 .mk-header-inner
		{
			top: calc(32px + {$body_border_thickness}px);
		}
		.header-style-4.header-align-center .mk-header-inner,
		.header-style-4.header-align-left .mk-header-inner
		{
			left: {$body_border_thickness}px;
		}
		.header-style-4.header-align-right .mk-header-inner
		{
			right: {$body_border_thickness}px;
		}
		.header-style-4 .mk-header-inner
		{
			height: calc(100% - {$body_border_thickness}px) !important;
			top: {$body_border_thickness}px;
		}
		.header-style-4.header-align-center .mk-header-right,
		.header-style-4.header-align-left .mk-header-right,
		.header-style-4.header-align-right .mk-header-right
		{
			bottom: calc(30px + {$body_border_thickness}px);
		}
		.sticky-style-fixed.header-style-1 .mk-header-holder,
		.sticky-style-fixed.header-style-3 .mk-header-holder
		{
			width: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			left: {$body_border_thickness}px;
		}
		.rtl .sticky-style-fixed.header-style-1 .mk-header-holder,
		.rtl .sticky-style-fixed.header-style-3 .mk-header-holder
		{
			left: 0;
			right: {$body_border_thickness}px;
		}

		.border-body--side
		{
			height: 100%;
			top: 0;
			left: 0px;
		}
		.admin-bar .border-body--side
		{
			height: calc(100% - 32px);
			top: 32px;
		}
		.border-body--right,
		.border-body--left
		{
			width: {$body_border_thickness}px;
		}
		.border-body--right
		{
			right: 0px;
			left: auto;
		}
		#mk-boxed-layout
		{
			width: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			left: {$body_border_thickness}px;
		}
		.rtl #mk-boxed-layout
		{
			right: {$body_border_thickness}px;
			left: 0;
		}
		.mk-post-prev
		{
			left: calc(-200px + {$body_border_thickness}px);
		}
		.mk-post-prev .pagenav-image
		{
			left: {$body_border_thickness}px;
		}
		.mk-post-next
		{
			right: calc(-200px + {$body_border_thickness}px);
		}
		.mk-post-next .pagenav-image
		{
			right: {$body_border_thickness}px;
		}
		.mk-fullscreen-nav
		{
			width: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			height: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			left: {$body_border_thickness}px;
			top: {$body_border_thickness}px;
		}
		.mk-fullscreen-nav .mk-fullscreen-nav-close
		{
			right: calc(50px + {$body_border_thickness}px);
			top:   calc(40px + {$body_border_thickness}px);
		}
		.admin-bar .mk-fullscreen-nav {
			height: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px - 32px);
			top: calc({$body_border_thickness}px + 32px);
		}
		.admin-bar .mk-fullscreen-nav .mk-fullscreen-nav-close
		{
			top: calc(40px + {$body_border_thickness}px + 32px);
		}
		.mk-fullscreen-search-overlay {
			width: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			height: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px);
			left: {$body_border_thickness}px;
			top: {$body_border_thickness}px;
		}
		.admin-bar .mk-fullscreen-search-overlay {
			height: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px - 32px);
			top: calc({$body_border_thickness}px + 32px);
		}



		.border-body--bottom
		{
			bottom: 0;
		}
		#mk-footer
		{
			margin-bottom: {$body_border_thickness}px;
		}
		.resize-triggers, .resize-triggers > div, .contract-trigger:before
		{
			top: {$body_border_thickness}px;
			height: calc(100% - {$body_border_thickness}px);
		}
		.bottom-corner-btns .mk-quick-contact-wrapper
		{
			right: calc(10px + {$body_border_thickness}px);
			bottom: calc(15px + {$body_border_thickness}px);
		}
		.bottom-corner-btns .mk-quick-contact-wrapper.is-active
		{
			right: calc(70px + {$body_border_thickness}px);
		}
		.bottom-corner-btns .mk-go-top
		{
			bottom: calc(15px + {$body_border_thickness}px) !important;
		}
		.bottom-corner-btns .mk-go-top.is-active
		{
			right: calc(15px + {$body_border_thickness}px);
		}

		@media handheld, only screen and (max-width: 768px)
		{
			.admin-bar .mk-fullscreen-nav {
				height: calc(100% - {$body_border_thickness}px - {$body_border_thickness}px - 46px);
				top: calc({$body_border_thickness}px + 46px);
			}
			.admin-bar .mk-fullscreen-nav .mk-fullscreen-nav-close
			{
				top: calc(40px + {$body_border_thickness}px + 46px);
			}
			.admin-bar .border-body--top
			{
				top: 46px !important;
			}
			.bottom-corner-btns .mk-quick-contact-wrapper,
			.bottom-corner-btns .mk-go-top
			{
				right: calc(22px + {$body_border_thickness}px);
				bottom: calc(15px + {$body_border_thickness}px);
			}
			.bottom-corner-btns .mk-quick-contact-wrapper.is-active
			{
				right: calc(22px + {$body_border_thickness}px);
			}
			.bottom-corner-btns .mk-go-top
			{
				bottom: calc(15px + {$body_border_thickness}px);
			}
			.bottom-corner-btns .mk-go-top.is-active
			{
				bottom: calc(72px + {$body_border_thickness}px) !important;
				right: calc(22px + {$body_border_thickness}px) !important;
			}
		}
		@media handheld, only screen and (max-width: {$mk_options['responsive_nav_width']}px)
		{
			.sticky-style-fixed.header-style-1 .mk-header-holder,
			.sticky-style-fixed.header-style-3 .mk-header-holder
			{
				width: 100%;
				left: 0;
			}
			.header-style-4.header-align-center .mk-header-inner,
			.header-style-4.header-align-left .mk-header-inner
			{
				left: 0;
				top: 0;
			}
		}

		body:not(.vertical-header-enabled) #theme-page .theme-content > .mk-page-section-wrapper .mk-page-section,
		body:not(.vertical-header-enabled) #theme-page .theme-content > .wpb_row.mk-fullwidth-true {
			padding-left: {$body_border_thickness}px;
			padding-right: {$body_border_thickness}px;
		}

		.compose-mode .mk-page-section-wrapper .mk-page-section .page-section-fullwidth .vc_controls-out-tl,
		.compose-mode .wpb_row.mk-fullwidth-true .vc_controls-out-tl {
			padding-left: {$body_border_thickness}px;
		}

	}

	@media handheld, only screen and (max-width: {$max_responsive_nav_width}px) {
		#theme-page .theme-content > .mk-page-section-wrapper .mk-page-section,
		#theme-page .theme-content > .wpb_row.mk-fullwidth-true {
			padding-left: {$body_border_thickness}px;
			padding-right: {$body_border_thickness}px;
		}
	}

	@media handheld, only screen and (min-width: {$min_responsive_nav_width }px) {
		/* Vertical header left */
		body.vertical-header-enabled.vertical-header-left .trans-header .theme-content > .wpb_row.mk-fullwidth-true,
		body.vertical-header-enabled.vertical-header-left .trans-header .theme-content > .mk-page-section-wrapper > .mk-page-section:not(.half_boxed):not(.half_fluid) {
			padding-left: calc(270px + {$body_border_thickness}px);
			padding-right: {$body_border_thickness}px;
		}

		body.vertical-header-enabled.vertical-header-left #mk-theme-container:not(.trans-header) .theme-content > .wpb_row.mk-fullwidth-true,
		body.vertical-header-enabled.vertical-header-left #mk-theme-container:not(.trans-header) .theme-content > .mk-page-section-wrapper > .mk-page-section:not(.half_boxed):not(.half_fluid) {
			padding-left: calc(270px + {$body_border_thickness}px);
			padding-right: {$body_border_thickness}px;
		}

		body.vertical-header-enabled.vertical-header-left #mk-theme-container.trans-header .header-style-4.a-sticky ~ .master-holder .theme-content > .mk-page-section-wrapper > div[class*='half_'],
		body.vertical-header-enabled.vertical-header-left #mk-theme-container:not(.trans-header) .theme-content > .mk-page-section-wrapper > div[class*='half_'] {
			margin-left: calc(270px + {$body_border_thickness}px);
			margin-right: {$body_border_thickness}px;
		}

		/* Vertical header right */
		body.vertical-header-enabled.vertical-header-right .trans-header .theme-content > .wpb_row.mk-fullwidth-true,
		body.vertical-header-enabled.vertical-header-right .trans-header .theme-content > .mk-page-section-wrapper > .mk-page-section:not(.half_boxed):not(.half_fluid) {
			padding-right: calc(270px + {$body_border_thickness}px);
			padding-left: {$body_border_thickness}px;
		}

		body.vertical-header-enabled.vertical-header-right #mk-theme-container:not(.trans-header) .theme-content > .wpb_row.mk-fullwidth-true,
		body.vertical-header-enabled.vertical-header-right #mk-theme-container:not(.trans-header) .theme-content > .mk-page-section-wrapper > .mk-page-section:not(.half_boxed):not(.half_fluid) {
			padding-right: calc(270px + {$body_border_thickness}px) !important;
			padding-left: {$body_border_thickness}px;
		}

		body.vertical-header-enabled.vertical-header-right #mk-theme-container.trans-header .header-style-4.a-sticky ~ .master-holder .theme-content > .mk-page-section-wrapper > div[class*='half_'],
		body.vertical-header-enabled.vertical-header-right #mk-theme-container:not(.trans-header) .theme-content > .mk-page-section-wrapper > div[class*='half_'] {
			margin-right: calc(270px + {$body_border_thickness}px);
			margin-left: {$body_border_thickness}px;
		}
	}

	"
	);
}
