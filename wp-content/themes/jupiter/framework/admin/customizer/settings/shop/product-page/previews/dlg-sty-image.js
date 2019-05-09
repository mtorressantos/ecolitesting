/**
 * Add live preview functions for Image section of Product Page > Styles.
 *
 * @since 5.9.4
 * @since 6.0.3 Add border color/width for layout 9/10.
 */
jQuery( document ).ready( function( $ ) {

	var flexViewportContainer = '.woocommerce-page.single-product .images';
	var flexViewport = '.woocommerce-page.single-product .images .flex-viewport';
	var galleryImage = '.woocommerce-product-gallery__image';

	if ( ! $('body').is('mk-product-layout-9, .mk-product-layout-9') ) {
		flexViewport += ', .single-product .images > .woocommerce-product-gallery__wrapper'
	}

	// Height.
	wp.customize( 'mk_cz[sh_pp_sty_img_image_ratio]' , function( value ) {

		value.bind( function( to ) {
			mkPreviewSaveReload();
		} );

	} );

	// Background color.
	wp.customize( 'mk_cz[sh_pp_sty_img_background_color]', function( value ) {
		$( flexViewport ).css( 'background-color', value() );

		value.bind( function( to ) {
			$( flexViewport ).css( 'background-color', to );
		} );
	});

	// Border width.
	wp.customize( 'mk_cz[sh_pp_sty_img_border_width]' , function( value ) {

		var el = 'sh_pp_sty_img_border_width';
		var styles = {};

		styles[ flexViewport ] = 'border-width: ' + value() + 'px';
		mkPreviewInternalStyle( styles, el );

		setTimeout( function() {
			$( '.flex-active-slide' ).resize();
		}, 1000);

		$( galleryImage ).css( 'border-width', value() + 'px' );

		value.bind( function( to ) {
			$( flexViewport ).css( 'border-width', to + 'px' );
			$( galleryImage ).css( 'border-width', to + 'px' );

			setTimeout( function() {
				$( '.flex-active-slide' ).resize();
			}, 1000);
		} );

	} );

	// Border color.
	wp.customize( 'mk_cz[sh_pp_sty_img_border_color]' , function( value ) {

		var el = 'sh_pp_sty_img_border_color';
		var styles = {};

		styles[ flexViewport ] = 'border-color: ' + value();
		mkPreviewInternalStyle( styles, el );

		$( galleryImage ).css( 'border-color', value() );

		value.bind( function( to ) {
			$( flexViewport ).css( 'border-color', to );
			$( galleryImage ).css( 'border-color', to );
		} );

	} );

	// Box model.
	wp.customize('mk_cz[sh_pp_sty_img_box_model]', function (value) {
		var boxModel = mkPreviewBoxModel(value());
		// calculate container width.
		var newWidth = parseInt( boxModel['margin-left'], 10 ) + parseInt( boxModel['margin-right'], 10 );
		var containerWidth = mk_get_image_gallery_width('mk_cz[sh_pp_set_layout]');

		$( flexViewportContainer ).css({
			width: 'calc(' + containerWidth + '% - ' + newWidth + 'px)'
		});
		$( flexViewportContainer ).css(boxModel);

		value.bind(function (to) {
			var boxModel = mkPreviewBoxModel(to);
			// calculate container width.
			var newWidth = parseInt( boxModel['margin-left'], 10 ) + parseInt( boxModel['margin-right'], 10 );
			var containerWidth = mk_get_image_gallery_width('mk_cz[sh_pp_set_layout]');

			$( flexViewportContainer ).css({
				width: 'calc(' + containerWidth + '% - ' + newWidth + 'px)'
			});
			$( flexViewportContainer ).css(boxModel);
			$( '.flex-control-nav' ).resize()
		});
	});

} );
