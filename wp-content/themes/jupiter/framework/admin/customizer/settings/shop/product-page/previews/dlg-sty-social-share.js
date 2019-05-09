(function ($) {

	var selector = '.woocommerce-page.single-product .product .social-share';

	// Method for Control's event handlers: sh_pp_sty_soc_shr_networks.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_networks]', function (value) {
		$(selector + ' .share-by').hide();
		var networks = typeof value.get() === 'object' ? value.get() : value.get().split(',');
		for (var i = 0, len = networks.length; i < len; i++) {
			$(selector + ' .share-by-' + networks[i]).css('display', 'inline-block');
		}
		value.bind(function (to) {
			$(selector + ' .share-by').hide();
			var networks = typeof to === 'object' ? to : to.split(',');
			for (var i = 0, len = networks.length; i < len; i++) {
				$(selector + ' .share-by-' + networks[i]).css('display', 'inline-block');
			}
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_fill_color.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color]', function (value) {
		$(selector + ' svg').css({
			'fill': value.get()
		});
		value.bind(function (to) {
			$(selector + ' svg').css({
				'fill': to
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_fill_color_hover.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color_hover]', function (value) {
		$(selector + '.share-by').on({
			mouseenter: function () {
				$(this).find('svg').css({
					'fill': value()
				});
			},
			mouseleave: function () {
				$(this).find('svg').css({
					'fill': wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color]')()
				});
			}
		});
		value.bind(function (to) {
			$(selector + '.share-by').on({
				mouseenter: function () {
					$(this).find('svg').css({
						'fill': to
					});
				},
				mouseleave: function () {
					$(this).find('svg').css({
						'fill': wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color]')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_background_color.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_background_color]', function (value) {
		$(selector + ' a').css({
			'background-color': value.get()
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'background-color': to
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_background_color_hover.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_background_color_hover]', function (value) {
		$(selector).find('a').on({
			mouseenter: function () {
				$(this).css({
					'background-color': value()
				});
				$(this).find('svg').css({
					'fill': wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color_hover]')()
				});
			},
			mouseleave: function () {
				$(this).css({
					'background-color': wp.customize('mk_cz[sh_pp_sty_soc_shr_background_color]')()
				});
				$(this).find('svg').css({
					'fill': wp.customize('mk_cz[sh_pp_sty_soc_shr_fill_color]')()
				});
			}
		});
		value.bind(function (to) {
			$(selector).find('a').on({
				mouseenter: function () {
					$(this).css({
						'background-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'background-color': wp.customize('mk_cz[sh_pp_sty_soc_shr_background_color]')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_border_radius.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_border_radius]', function (value) {
		$(selector + ' a').css({
			'border-radius': value.get() + 'px'
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-radius': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_border.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_border]', function (value) {
		$(selector + ' a').css({
			'border-width': value.get() + 'px'
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-width': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_border_color.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_border_color]', function (value) {
		$(selector + ' a').css({
			'border-color': value.get()
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-color': to
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_border_color_hover.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_border_color_hover]', function (value) {
		$(selector).find('a').on({
			mouseenter: function () {
				$(this).css({
					'border-color': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'border-color': wp.customize('mk_cz[sh_pp_sty_soc_shr_border_color]')()
				});
			}
		});
		value.bind(function (to) {
			$(selector).find('a').on({
				mouseenter: function () {
					$(this).css({
						'border-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'border-color': wp.customize('mk_cz[sh_pp_sty_soc_shr_border_color]')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_soc_shr_box_model.
	wp.customize('mk_cz[sh_pp_sty_soc_shr_box_model]', function (value) {
		$(selector).css(
			mkPreviewBoxModel(value.get())
		);
		value.bind(function (to) {
			$(selector).css(
				mkPreviewBoxModel(to)
			);
		});
	});

})(jQuery);
