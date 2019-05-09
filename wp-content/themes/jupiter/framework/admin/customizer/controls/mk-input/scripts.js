jQuery(function ($) {

	var api = wp.customize;

	// When Customizer has finished loading
	api.bind('ready', function () {

		// Trigger change on any input events.
		$( 'input[type="number"]' ).on( 'input', function () {
			$( this ).trigger( 'change' );
		} );

	});


});