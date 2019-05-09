(function ($) {

	wp.customize('mk_cz[wg_glb_sty_div_border_width]', function (value) {

		var el = 'mk_cz[wg_glb_sty_div_border_width]';
		var styles = {};
		styles['#mk-sidebar .widget::after'] = 'border-width: ' + value() + 'px';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles['#mk-sidebar .widget::after'] = 'border-width: ' + to + 'px';
			mkPreviewInternalStyle( styles, el );
		});

	});

	wp.customize( 'mk_cz[wg_glb_sty_div_border_color]', function( value ) {

		var el = 'mk_cz[wg_glb_sty_div_border_color]';
		var styles = {};
		styles['#mk-sidebar .widget::after'] = 'border-color: ' + value();
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles['#mk-sidebar .widget::after'] = 'border-color: ' + to;
			mkPreviewInternalStyle( styles, el );
		} );

	});

	wp.customize( 'mk_cz[wg_glb_sty_div_box_model]', function( value ) {

		var el = 'mk_cz[wg_glb_sty_div_box_model]';
		var styles = {};
		styles['#mk-sidebar .widget::after'] = mkPreviewBoxModel( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles['#mk-sidebar .widget::after'] = mkPreviewBoxModel( value() );
			mkPreviewInternalStyle( styles, el );
		} );

	});

})(jQuery);