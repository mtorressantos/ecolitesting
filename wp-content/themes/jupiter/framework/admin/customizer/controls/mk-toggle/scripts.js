jQuery( function($) {
	
	var api = wp.customize;

	// When Customizer has finished loading
	api.bind( 'ready', function() {
		
		$('.mk-toggle').on('click', function() {
			var $input = $(this).find('.mk-toggle-value');
			var $bullet = $(this).find('.mk-toggle-bullet');
			if ( $input.val() === 'true' ) {
				$(this).removeClass('mk-toggle-active');
				$input.val('false');
			} else {
				$(this).addClass('mk-toggle-active');
				$input.val('true');
			}
			$input.trigger('change');  // Magic to remind Custmizer this has changed, So don't forget to save it!
		});

		// Load and Display the values on the fields
		$('.mk-toggle').on('mk_load_value', function() {
			var $input = $(this).find('.mk-toggle-value');
			var $bullet = $(this).find('.mk-toggle-bullet');
			if ( $input.val() === 'true' ) {
				$(this).addClass('mk-toggle-active');
			} else {
				$(this).removeClass('mk-toggle-active');
			}
		});

		$('.mk-toggle').trigger('mk_load_value');
		

	});
	

});