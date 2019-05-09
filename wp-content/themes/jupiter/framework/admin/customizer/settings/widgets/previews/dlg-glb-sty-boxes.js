/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var box_style_container = '#mk-sidebar .widget';

	wp.customize( 'mk_cz[wg_glb_sty_box_background_color]', function( value ) {

		$( box_style_container ).css( 'background-color', value() );
		
		value.bind( function( to ) {
			$( box_style_container ).css( 'background-color', to );
		} );

	});

	wp.customize( 'mk_cz[wg_glb_sty_box_border_radius]', function( value ) {

		$( box_style_container ).css( 'border-radius', value() + 'px' );

		value.bind( function( to ) {
			$( box_style_container ).css( 'border-radius', to + 'px' );
		} );

	});

	wp.customize( 'mk_cz[wg_glb_sty_box_border_width]', function( value ) {

		$( box_style_container ).css( 'border-width', value() + 'px' );

		value.bind( function( to ) {
			$( box_style_container ).css( 'border-width', to + 'px' );
		} );

	});

	wp.customize( 'mk_cz[wg_glb_sty_box_border_color]', function( value ) {

		$( box_style_container ).css( 'border-color', value() );

		value.bind( function( to ) {
			$( box_style_container ).css( 'border-color', to );
		} );

	});

	wp.customize( 'mk_cz[wg_glb_sty_box_box_model]', function( value ) {

		$( box_style_container ).css(
			mkPreviewBoxModel( value() )
		);

		value.bind(function (to) {
			$( box_style_container ).css(
				mkPreviewBoxModel( to )
			);
		});

	});

} )( jQuery );

