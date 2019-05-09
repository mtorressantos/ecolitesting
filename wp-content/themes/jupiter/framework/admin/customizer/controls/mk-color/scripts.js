jQuery( function( $ ) {

	var api = wp.customize;

	api.bind( 'ready', function() {

		$( '.mk-color-picker-holder input' ).spectrum( {
			preferredFormat: "hex3",
			showInput: true,
			showAlpha: true,
			showButtons: false,
			showInitial: true,
			move: function( tinycolor ) {
				$( this ).attr( 'value', tinycolor.toString() ).trigger( 'change' );
			},
			show: function() {
				var dialog = $( '.mk-dialog-child:visible' );
				var picker = $( '.sp-container:visible' );
				var dialogRight = dialog.offset().left + dialog.outerWidth();
				var pickerRight = picker.offset().left + picker.outerWidth();
				var inputBox = $( this ).next( '.sp-replacer' ).offset().left;

				if ( pickerRight > dialogRight ) {
					picker.css( 'left', inputBox - 173 );
				}
			},
			// Fix https://github.com/bgrins/spectrum/issues/400.
			change: function( color ) {
				var a = color.getAlpha(),
				fmt;

				if ( a < 1 ) { // If there is an alpha value, use RGBa format
					fmt = color.toRgbString();
				} else { // Otherwise, use hex3 if possible, falling back to hex
					fmt = color.toHexString( true );
				}

				this.value = fmt;
			}
		} );

	} );

} );
