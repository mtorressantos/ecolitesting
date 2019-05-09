/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var el_title = '#mk-sidebar div.widgettitle';
	
	// Method for Control's event handlers: mk_cz[wg_glb_sty_ttl_typography].
	wp.customize('mk_cz[wg_glb_sty_ttl_typography]', function (value) {
		var typography = mkPreviewTypography(value(), true);
		$( el_title ).css(typography);

		value.bind(function (to) {
			var typography = mkPreviewTypography(to);
			$( el_title ).css(typography);
		});
	
	});

	// Method for Control's event handlers: mk_cz[wg_glb_sty_ttl_line_height].
	wp.customize('mk_cz[wg_glb_sty_ttl_line_height]', function (value) {
	
		$( el_title ).css({
			'line-height': value() + "em",
		});
	
		value.bind(function (to) {
			$( el_title ).css({
				'line-height': to + "em",
			});
		});
	
	});

	// Method for Control's event handlers: mk_cz[wg_glb_sty_ttl_box_model].
	wp.customize('mk_cz[wg_glb_sty_ttl_box_model]', function (value) {
		$( el_title ).css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$( el_title ).css(mkPreviewBoxModel(to));
		});
	});

} )( jQuery );
