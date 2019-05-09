jQuery( function($) {
	
	var api = wp.customize;

	// When Customizer has finished loading
	api.bind( 'ready', function() {

		$('.mk-element-select select').selectize({
			maxItems: 1,

			onDropdownOpen: function() {
				this.$dropdown_content
					.find( "[data-value='" + this.getValue()  + "']" )
					.addClass( 'mk-current' );
			},

			onChange: function( value ) {
				this.$dropdown_content.find( '.option' ).removeClass( 'mk-current' );
				this.$dropdown_content
					.find( "[data-value='" + value + "']" )
					.addClass( 'mk-current' );
			}
		});

		// Disable search.
		$( '.mk-element-select .selectize-input > input' ).prop( 'disabled', 'disabled' );

	});

});