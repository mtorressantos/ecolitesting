(function( $ ) {

	// Icon size.
	wp.customize( 'mk_cz[sh_cc_sty_stp_icon_active_icon_size]', function( value ) {
		
		var el = '.mk-checkout-steps-icon .mk-checkout-step svg';

		$( el ).css({
			height: value() + 'px',
		});
	
		value.bind( function( to ) {
			$( el ).css({
				height: to + 'px',
			});
		} );
	
	} );

	// Icon Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_icon_active_fill_color]', function( value ) {
		
		var el = '.mk-checkout-steps-icon .mk-checkout-step-active svg path';
		
		$( el ).css({
			fill: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				fill: to,
			});
		} );
	
	} );

	// Margin.
	wp.customize( 'mk_cz[sh_cc_sty_stp_icon_active_box_model]', function( value ) {

		var el = '.mk-checkout-steps.mk-checkout-steps-icon';

		$(el).css(
			mkPreviewBoxModel(value(), true)
		);

		value.bind(function (to) {
			$(el).css(
				mkPreviewBoxModel(to)
			);
			$( 'body' ).trigger( 'mk-position-order-summery' );
		});
	
	} );

	// Active State Typography.
	wp.customize('mk_cz[sh_cc_sty_stp_icon_active_typography]', function (value) {
		
		var el = '.mk-checkout-steps-icon .mk-checkout-step .mk-checkout-step-text';
		var el_active = '.mk-checkout-steps-icon .mk-checkout-step-active .mk-checkout-step-text';

		var typography = mkPreviewTypography(value(), true, ['color']);
		$( el ).css(typography);
		$( el_active ).css({
			color: mkPreviewTypography( value() )['color']
		});

		value.bind(function (to) {
			var typography = mkPreviewTypography(to, false, ['color']);
			$( el ).css(typography);
			$( el_active ).css({
				color: mkPreviewTypography( to )['color']
			});
		});
	
	});

	// Passive Icon Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_icon_passive_icon_color]', function( value ) {
		
		var el = '.mk-checkout-steps-icon .mk-checkout-step:not(.mk-checkout-step-active) svg path';

		$( el ).css({
			fill: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				fill: to,
			});
		} );
	
	} );

	// Passive Text Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_icon_passive_text_color]', function( value ) {
		
		var el = '.mk-checkout-steps-icon .mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-text';
		
		$( el ).css({
			color: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				color: to,
			});
		} );
	
	} );


	// Number ---------------------------------------------------------------
	

	// Active - Number Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_number_active_number_background_color]', function( value ) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step-active .mk-checkout-step-number';
		
		$( el ).css({
			backgroundColor: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				backgroundColor: to,
			});
		} );
	
	} );

	// Active - Number Typography.
	wp.customize('mk_cz[sh_cc_sty_stp_icon_active_number_typography]', function (value) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step .mk-checkout-step-number';
		var el_active = '.mk-checkout-steps-number .mk-checkout-step-active .mk-checkout-step-number';

		var typography = mkPreviewTypography( value(), true, ['color'] );
		$( el ).css( typography );
		$( el_active ).css({
			color: mkPreviewTypography( value() )['color']
		});

		value.bind(function (to) {
			var typography = mkPreviewTypography( to, false, ['color'] );
			$( el ).css( typography );
			$( el_active ).css({
				color: mkPreviewTypography(to)['color']
			});
		});
	
	});

	// Active - Title Typography.
	wp.customize('mk_cz[sh_cc_sty_stp_icon_active_title_typography]', function (value) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step .mk-checkout-step-text';
		var el_active = '.mk-checkout-steps-number .mk-checkout-step-active .mk-checkout-step-text';

		var typography = mkPreviewTypography(value(), true, ['color']);
		$( el ).css(typography);
		$( el_active ).css({
			color: mkPreviewTypography( value() )['color']
		});

		value.bind(function (to) {
			var typography = mkPreviewTypography(to, false,  ['color']);
			$( el ).css(typography);
			$( el_active ).css({
				color: mkPreviewTypography(to)['color']
			});
		});
	
	});

	// Margin.
	wp.customize( 'mk_cz[sh_cc_sty_stp_number_active_box_model]', function( value ) {

		var el = '.mk-checkout-steps.mk-checkout-steps-number';

		if ( typeof value() === 'string' ) {
			$(el).css( mkPreviewBoxModel(value(), true) );
		}

		value.bind(function (to) {
			if ( typeof to === 'string' ) {
				boxModel = jQuery.parseJSON( to );
				$(el).css( mkPreviewBoxModel(to) );
			}

			$( 'body' ).trigger( 'mk-position-order-summery' );
		});
	
	} );

	// Passive - Number Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_number_passive_number_background_color]', function( value ) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-number';
		var el2 = '.mk-checkout-steps-number .mk-checkout-step-svg-wrap';
		var el2_icon = '.mk-checkout-steps-number .mk-checkout-step-svg-wrap path';
		
		$( el ).css({
			backgroundColor: value(),
		});		
		$( el2 ).css({
			borderColor: value(),
		});
		$( el2_icon ).css({
			'fill': value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				backgroundColor: to,
			});
			$( el2 ).css({
				borderColor: to,
			});
			$( el2_icon ).css({
				'fill': to,
			});
		} );
	
	} );

	// Passive - Number Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_number_passive_number_text_color]', function( value ) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-number';
		
		$( el ).css({
			color: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				color: to,
			});
		} );
	
	} );

	// Passive - Number Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_stp_number_passive_title_color]', function( value ) {
		
		var el = '.mk-checkout-steps-number .mk-checkout-step:not(.mk-checkout-step-active) .mk-checkout-step-text';
		
		$( el ).css({
			color: value(),
		});

		value.bind( function( to ) {
			$( el ).css({
				color: to,
			});
		} );
	
	} );

	

} )( jQuery );