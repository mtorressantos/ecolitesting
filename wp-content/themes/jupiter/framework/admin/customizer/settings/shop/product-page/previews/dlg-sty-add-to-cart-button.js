/**
 * Add live preview functions for Add to Cart Button section of Product Page > Styles.
 *
 * @since 5.9.4
 * @since 6.0.3 Add full width option.
 */
(function ($) {

	var add_to_cart = '.woocommerce-page.single-product div.product .single_add_to_cart_button';
	var add_to_cart_button = '.woocommerce-page.single-product div.product button.single_add_to_cart_button';

	// Show icon.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_show_icon]', function (value) {
		if ('true' == value()) {
			$(add_to_cart_button).find('.mk-button-icon').show();
		} else {
			$(add_to_cart_button).find('.mk-button-icon').hide();
		}
		value.bind(function (to) {
			if ('true' == to) {
				$(add_to_cart_button).find('.mk-button-icon').show();
			} else {
				$(add_to_cart_button).find('.mk-button-icon').hide();
			}
		});
	});

	// Full width.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_full_width]', function (value) {
		if ('true' == value()) {
			$('div.product').addClass('mk-button-full-width');
		} else {
			$('div.product').removeClass('mk-button-full-width');
		}

		value.bind(function (to) {
			$('div.product').toggleClass('mk-button-full-width');
		});
	});

	// Typography.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_typography]', function (value) {
		var typography = mkPreviewTypography(value(), true);
		$(add_to_cart).css(typography);

		// It's an exception.
		$( add_to_cart ).find( 'svg' ).css({
			width: value()['size'],
			height: value()['size']
		});

		value.bind(function (to) {
			var typography = mkPreviewTypography(to);
			$(add_to_cart).css(typography);

			// It's an exception.
			var typographySize = jQuery.parseJSON( to )['size'];

			$( add_to_cart ).find( 'svg' ).css({
				width: typographySize,
				height: typographySize
			});
		});

	});

	// Background color.
	wp.customize( 'mk_cz[sh_pp_sty_atc_btn_background_color]', function( value ) {

		var el = 'sh_pp_sty_atc_btn_background_color';
		var styles = {};

		styles[add_to_cart] = 'background-color: ' + value() + ' !important';
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[add_to_cart] = 'background-color: ' + to + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );

	} );

	// Border radius.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_border_radius]', function (value) {
		$(add_to_cart).css({
			'border-radius': value() + 'px'
		});
		value.bind(function (to) {
			$(add_to_cart).css({
				'border-radius': to + 'px'
			});
		});
	});

	// Border.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_border]', function (value) {
		$(add_to_cart).css({
			'border-width': value() + 'px'
		});
		value.bind(function (to) {
			$(add_to_cart).css({
				'border-width': to + 'px'
			});
		});
	});

	// Border color.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_border_color]', function (value) {
		$(add_to_cart).css({
			'border-color': value()
		});
		value.bind(function (to) {
			$(add_to_cart).css({
				'border-color': to
			});
		});
	});

	// Icon color.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_icon_color]', function (value) {
		$(add_to_cart).find('svg').css({
			'fill': value()
		});
		value.bind(function (to) {
			$(add_to_cart).find('svg').css({
				'fill': to
			});
		});
	});

	// Color hover.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_color_hover]', function (value) {
		$(add_to_cart).on({
			mouseenter: function () {
				$(this).css({
					color: value()
				});
			},
			mouseleave: function () {
				$(this).css({
					color: mkPreviewTypography(wp.customize('mk_cz[sh_pp_sty_atc_btn_typography]')())['color']
				});
			}
		});
		value.bind(function (to) {
			$(add_to_cart).on({
				mouseenter: function () {
					$(this).css({
						color: to
					});
				},
				mouseleave: function () {
					$(this).css({
						color: mkPreviewTypography(wp.customize('mk_cz[sh_pp_sty_atc_btn_typography]')())['color']
					});
				}
			});
		});
	});

	// Background color hover.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_background_color_hover]', function (value) {

		var el = 'sh_pp_sty_atc_btn_background_color_hover';
		var styles = {};

		styles[add_to_cart + ':hover'] = 'background-color: ' + value() + ' !important';
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[add_to_cart + ':hover'] = 'background-color: ' + to + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );

	});

	// Border color hover.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_border_color_hover]', function (value) {
		$(add_to_cart).on({
			mouseenter: function () {
				$(this).css({
					'border-color': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'border-color': wp.customize('mk_cz[sh_pp_sty_atc_btn_border_color]')()
				});
			}
		});
		value.bind(function (to) {
			$(add_to_cart).on({
				mouseenter: function () {
					$(this).css({
						'border-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'border-color': wp.customize('mk_cz[sh_pp_sty_atc_btn_border_color]')()
					});
				}
			});
		});
	});

	// Border color hover.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_icon_color_hover]', function (value) {
		$(add_to_cart).on({
			mouseenter: function () {
				$(this).find('svg').css({
					'fill': value()
				});
			},
			mouseleave: function () {
				$(this).find('svg').css({
					'fill': wp.customize('mk_cz[sh_pp_sty_atc_btn_icon_color]')()
				});
			}
		});
		value.bind(function (to) {
			$(add_to_cart).on({
				mouseenter: function () {
					$(this).find('svg').css({
						'fill': to
					});
				},
				mouseleave: function () {
					$(this).find('svg').css({
						'fill': wp.customize('mk_cz[sh_pp_sty_atc_btn_icon_color]')()
					});
				}
			});
		});
	});

	// Box model.
	wp.customize('mk_cz[sh_pp_sty_atc_btn_box_model]', function (value) {
		$(add_to_cart).css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$(add_to_cart).css(mkPreviewBoxModel(to));
		});
	});

})(jQuery);
