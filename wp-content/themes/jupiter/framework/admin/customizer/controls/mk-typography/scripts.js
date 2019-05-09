jQuery( function($) {

	var api = window.wp.customize;

	api.bind('ready', function () {

		// Input value change handler
		function mkTypographyValues(el) {
			var obj_val = {};
			var $wrap = el.closest('.mk-control-typography-inner');
			var $input = $wrap.next('.mk-typography-value');
			var source = $( '.mk-typography-item-font-family select', $wrap ).attr( 'data-source' );

			/**
			 * Find only selects and inputs have name property. There are other inputs 
 			 * which are not necessary.
			 */
			$wrap.find( 'select[name], input[name]' )
				.each(function (index, input) {
					var name = $(input).attr('name');
					var value = $(input).val();
					obj_val[name] = value;
				});

			if ( typeof source !== 'undefined' ) {
				obj_val['source'] = source;
			}

			$input.val(JSON.stringify(obj_val));
			$input.trigger('change'); // Magic to remind Custmizer this has changed, So don't forget to save it!
		}

		// Detect value change for input element
		$('.mk-control-typography input, .mk-typography-item-font-weight select').on('keyup change', function () {
			mkTypographyValues($(this));
		});

		$('.mk-typography-item-font-family select').selectize({

			onInitialize: function( value ) {
				var selected_option = this.options[ this.getValue() ];
				this.$input.attr( 'data-source', selected_option.source );
			},

			onDropdownOpen: function() {
				this.$dropdown_content
					.find( "[data-value='" + this.getValue()  + "']" )
					.addClass( 'mk-current' );
			},

			onChange: function( value ) {

				var data = this.options[value];
				var family = value;
				var source = data.source;
				var weights = ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900';
				var previewFrame = $( '#customize-preview' ).find( 'iframe' ).attr( 'name' );

				this.$dropdown_content.find( '.option' ).removeClass( 'mk-current' );
				this.$dropdown_content.find( "[data-value='" + value + "']" ).addClass( 'mk-current' );
				
				this.$input.attr( 'data-source', source );

				mkTypographyValues( this.$input );
			
				if ( typeof family === 'undefined' || source !== 'google-font' ) {
					return;
				}
			
				// Load the selected font in the preview iframe.
				WebFont.load({
					google: {
						families: [family + weights],
					},
					context: frames[previewFrame],
					timeout: 2000
				});	
			}

		});

	});

});