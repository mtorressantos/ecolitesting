(function($) {

	var gridWrapper = '.woocommerce-page .theme-page-wrapper.mk-grid';
	var gridContent = '.woocommerce-page .theme-page-wrapper .theme-content';
	var listProduct = '.woocommerce-page:not(.single-product) ul.products';
	var listProduct1 = '.woocommerce-page ul.products.per-row-1';
	var listProduct2 = '.woocommerce-page ul.products.per-row-2';
	var listProduct3 = '.woocommerce-page ul.products.per-row-3';
	var listProduct4 = '.woocommerce-page ul.products.per-row-4';
	var boxProduct = '.woocommerce-page:not(.single-product) ul.products li.product';

	var selectors = [
		".woocommerce-loop-product__title",
		".price ins",
		".price del",
		".button",
		".star-rating",
	];

	// Method for Control's event handlers: sh_pl_set_full_width.
	function mkCustomizerProductsListFullWidth() {
		wp.customize('mk_cz[sh_pl_set_full_width]', function(value) {

			if ('true' == value()) {
				$(gridWrapper).css('width', '100%');
				$(gridWrapper).css('max-width', '100%');
			} else {
				$(gridWrapper).css('max-width', mk_grid_width + 'px');
			}

			value.bind(function(to) {
				if ('true' == to) {
					$(gridWrapper).css('width', '100%');
					$(gridWrapper).css('max-width', '100%');
				} else {
					$(gridWrapper).css('max-width', mk_grid_width + 'px');
				}
			});

		});
	}

	// Method for Control's event handlers: sh_pl_set_hover_style.
	function mkCustomizerProductsListHoverStyle() {
		wp.customize('mk_cz[sh_pl_set_hover_style]', function(value) {
			$(listProduct).removeClass('thumbnail-hover-style-none thumbnail-hover-style-alternate thumbnail-hover-style-zoom');
			$(listProduct).addClass('thumbnail-hover-style-' + value());
			value.bind(function(to) {
				$(listProduct).removeClass('thumbnail-hover-style-none thumbnail-hover-style-alternate thumbnail-hover-style-zoom');
				$(listProduct).addClass('thumbnail-hover-style-' + to);
			});

		});
	}

	// Method for Control's event handlers: sh_pl_set_product_info.
	function mkCustomizerProductsListProductInfo() {
		wp.customize('mk_cz[sh_pl_set_product_info]', function(value) {

			var selecteds = typeof value() === 'object' ? value() : value().split(',');

			for (var i = 0, len = selectors.length; i < len; i++) {
				$(boxProduct + ' ' + selectors[i]).hide();
				if (selectors[i] === '.price del') {
					$(boxProduct + ' .price > .amount').hide();
				}
			}

			for (var i = 0, len = selecteds.length; i < len; i++) {
				$(boxProduct + ' ' + selecteds[i]).show();
				if (selecteds[i] === '.price del') {
					$(boxProduct + ' .price > .amount').show();
				}
				if (selecteds[i] === '.star-rating' || selecteds[i] === '.button') {
					$(boxProduct + ' ' + selecteds[i]).css({
						'display': 'inline-block'
					});
				}
			}

			value.bind(function(to) {

				selecteds = typeof to === 'object' ? to : to.split(',');

				for (var i = 0, len = selectors.length; i < len; i++) {
					$(boxProduct + ' ' + selectors[i]).hide();
					if (selectors[i] === '.price del') {
						$(boxProduct + ' .price > .amount').hide();
					}
				}

				for (var i = 0, len = selecteds.length; i < len; i++) {
					$(boxProduct + ' ' + selecteds[i]).show();
					if (selecteds[i] === '.price del') {
						$(boxProduct + ' .price > .amount').show();
					}
					if (selecteds[i] === '.star-rating' || selecteds[i] === '.button') {
						$(boxProduct + ' ' + selecteds[i]).css({
							'display': 'inline-block'
						});
					}
				}

			});

		});
	}

	// Image Ratio.
	wp.customize( 'mk_cz[sh_pl_set_image_ratio]' , function( value ) {

		value.bind( function( to ) {
			mkPreviewSaveReload();
		} );

	} );

	// Method for Control's event handlers: sh_pl_set_product_info_align.
	function mkCustomizerProductsListAlignProductInfo() {
		wp.customize('mk_cz[sh_pl_set_product_info_align]', function(value) {

			align = value();

			if (!align.length) {
				align = 'center';
			}

			$(boxProduct + ' .mk-product-warp').css('text-align', align);

			value.bind(function(to) {

				if (!to.length) {
					to = 'center';
				}

				$(boxProduct + ' .mk-product-warp').css('text-align', to);
			});

		});
	}

	mkCustomizerProductsListFullWidth();
	mkCustomizerProductsListHoverStyle();
	mkCustomizerProductsListProductInfo();
	mkCustomizerProductsListAlignProductInfo();

	// Horizontal space.
	wp.customize( 'mk_cz[sh_pl_set_horizontal_space]', function( value ) {

		var el = 'sh_pl_set_horizontal_space';
		var styles = {};
		var columns = 100 / wp.customize('mk_cz[sh_pl_set_columns]' ).get();

		shPlSetHorizontalSpace( value(), styles, el, columns );

		value.bind( function( to ) {
			shPlSetHorizontalSpace( to, styles, el, columns );
		});

	} );

	function shPlSetHorizontalSpace( to, styles, el, columns ) {
		$( listProduct ).css({
			marginLeft: -to / 2 + 'px',
			marginRight: -to / 2 + 'px',
		});

		$( boxProduct ).css({
			marginLeft: to / 2 + 'px',
			marginRight: to / 2 + 'px',
		});

		styles[ boxProduct ] = 'width: calc(' + columns + '% - ' + to + 'px) !important';

		mkPreviewInternalStyle( styles, el );
	}

	// Vertical space.
	wp.customize( 'mk_cz[sh_pl_set_vertical_space]', function( value ) {

		$( boxProduct ).css({
			marginBottom: value() + 'px',
		});

		value.bind( function( to ) {
			$( boxProduct ).css({
				marginBottom: to + 'px',
			});
		});

	} );

	// Sidebar.
	wp.customize( 'mk_cz[sh_pl_set_sidebar]', function( value ) {
		value.bind(function( to ) {
			mkPreviewSaveReload();
		} );
	} );

})(jQuery);
