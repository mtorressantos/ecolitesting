jQuery(document).ready(function( $ ) {

	var variations = '.woocommerce-page.single-product div.product form.cart .variations';

	// Typography.
	wp.customize('mk_cz[sh_pp_sty_var_typography]', function (value) {
		$( 'td label, td select', variations).css(
			mkPreviewTypography(value(), true)
		);
		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			$( 'td label, td select', variations).css(
				mkPreviewTypography(to, true)
			);
		});

	});

	// Background color.
	wp.customize('mk_cz[sh_pp_sty_var_background_color]', function (value) {
		$('td select', variations).css({
			'background-color': value()
		});
		value.bind(function (to) {
			$('td select', variations).css({
				'background-color': to
			});
		});
	});

	// Border width.
	wp.customize('mk_cz[sh_pp_sty_var_border]', function (value) {
		$('td select', variations).css({
			'border-width': value() + 'px'
		});
		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			$('td select', variations).css({
				'border-width': to + 'px'
			});
		});
	});

	// Border color.
	wp.customize('mk_cz[sh_pp_sty_var_border_color]', function (value) {
		$('td select', variations).css({
			'border-color': value()
		});
		value.bind(function (to) {
			$('td select', variations).css({
				'border-color': to
			});
		});
	});

	// Box model.
	wp.customize('mk_cz[sh_pp_sty_var_box_model]', function (value) {
		$('td', variations).css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			$('td', variations).css(mkPreviewBoxModel(to));
		});
	});

});
