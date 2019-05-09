(function($) {

	var class_prefix = '.woocommerce-page.single-product .product ';

	wp.customize( 'mk_cz[sh_pp_set_layout]', function( value ) {

		value.bind(function( to ) {
			mkPreviewSaveReload();
		} );

	} );

	// Sidebar.
	wp.customize( 'mk_cz[sh_pp_set_sidebar]', function( value ) {

		value.bind(function( to ) {
			mkPreviewSaveReload();
		} );

	} );

	// This requires refactoring.
	wp.customize('mk_cz[sh_pp_set_product_info]', function(value) {

		var selectors = [
			'.summary .price ins',
			'.summary .price del',
			'.summary .woocommerce-product-rating',
			'.summary .product_meta .posted_in',
			'.summary .product_meta .tagged_as',
			'.summary .product_meta .sku_wrapper',
			'.summary .woocommerce-product-details__short-description',
			'.summary .variations',
			'.summary .cart .quantity',
			'.summary .social-share',
			'.woocommerce-tabs #tab-title-reviews|.woocommerce-tabs #tab-reviews',
			'.woocommerce-tabs #tab-title-additional_information|.woocommerce-tabs #tab-additional_information',
			'.woocommerce-tabs #tab-title-description|.woocommerce-tabs #tab-description',
		];

		var infos = typeof value.get() === 'object' ? value.get() : value.get().split(',');

		$(class_prefix + '.woocommerce-tabs').hide();

		for (var i = 0; i < selectors.length; i++) {
			var parts = selectors[i].split('|');
			for (var j = 0; j < parts.length; j++) {
				if (infos.indexOf(selectors[i]) === -1) {
					$(class_prefix + parts[j]).hide();
					if (parts[j] === '.summary .price del') {
						$(class_prefix + '.summary .price > .amount').hide();
					}
				} else {
					$(class_prefix + parts[j]).show();
					if (parts[j].indexOf('.product_meta') !== -1) {
						$(class_prefix + parts[j]).css({
							'display': 'block'
						});
					}
					if (parts[j].indexOf('#tab-title') !== -1) {
						$(class_prefix + parts[j]).css({
							'display': 'inline-block'
						});
						$(class_prefix + parts[j]).find('a').click();
					}
					if (parts[j].indexOf('#tab-description') !== -1) {
						$(class_prefix + '.woocommerce-tabs').show();
						$(document).ready(function(){
							$('.woocommerce-tabs #tab-title-description').find('a').trigger('click');
						});
					} else if (parts[j].indexOf('#tab-additional_information') !== -1) {
						var wooInfoTab = $('.woocommerce-tabs #tab-title-additional_information');
						if ( wooInfoTab.length ) {
							$(class_prefix + '.woocommerce-tabs').show();
							$(document).ready(function(){
								wooInfoTab.find('a').trigger('click');
							});
						}
					} else if (parts[j].indexOf('#tab-reviews') !== -1) {
						$(class_prefix + '.woocommerce-tabs').show();
						$(document).ready(function(){
							$('#tab-title-reviews').find('a').trigger('click');
						});
					}
					if (parts[j] === '.summary .price del') {
						$(class_prefix + '.summary .price > .amount').show();
					}
				}
			}
		}

		value.bind(function(to) {

			$(document.body).trigger('mk-woo-align-quantity');

			infos = typeof to === 'object' ? to : to.split(',');

			$(class_prefix + '.woocommerce-tabs').hide();

			for (var i = 0; i < selectors.length; i++) {
				var parts = selectors[i].split('|');
				for (var j = 0; j < parts.length; j++) {
					if (infos.indexOf(selectors[i]) === -1) {
						$(class_prefix + parts[j]).hide();
						if (parts[j] === '.summary .price del') {
							$(class_prefix + '.summary .price > .amount').hide();
						}
					} else {
						$(class_prefix + parts[j]).show();
						if (parts[j].indexOf('.product_meta') !== -1) {
							$(class_prefix + parts[j]).css({
								'display': 'block'
							});
						}
						if (parts[j].indexOf('#tab-title') !== -1) {
							$(class_prefix + parts[j]).css({
								'display': 'inline-block'
							});
							$(class_prefix + parts[j]).find('a').click();
						}
						if (parts[j].indexOf('.woocommerce-tabs') !== -1) {
							$(class_prefix + '.woocommerce-tabs').show();
						}
						if (parts[j] === '.summary .price del') {
							$(class_prefix + '.summary .price > .amount').show();
						}
					}
				}
			}

		});
	});

	wp.customize( 'mk_cz[sh_pp_set_related_products_enabled]', function( value ) {

		if (value() === 'true') {
			$('section.related.products').show();
		} else {
			$('section.related.products').hide();
		}

		value.bind(function( to ) {
			if (to === 'true') {
				$('section.related.products').show();
			} else {
				$('section.related.products').hide();
			}
		} );

	} );

	wp.customize( 'mk_cz[sh_pp_set_up_sells_enabled]', function( value ) {

		if (value() === 'true') {
			$('section.upsells.products').show();
		} else {
			$('section.upsells.products').hide();
		}

		value.bind(function( to ) {
			if (to === 'true') {
				$('section.upsells.products').show();
			} else {
				$('section.upsells.products').hide();
			}
		} );

	} );

	// Sticky product info.
	wp.customize( 'mk_cz[sh_pp_set_sticky_info_enabled]', function( value ) {

		if (value() === 'true') {
			$( class_prefix ).addClass( 'mk-info-sticky' );
			$( 'body' ).trigger( 'mk:woo-info-sticky' );
		} else {
			$( class_prefix ).removeClass( 'mk-info-sticky' );
		}

		value.bind(function( to ) {
			$( class_prefix ).toggleClass( 'mk-info-sticky' );
			$( 'body' ).trigger( 'mk:woo-info-sticky' );
		} );

	} );

})(jQuery);
