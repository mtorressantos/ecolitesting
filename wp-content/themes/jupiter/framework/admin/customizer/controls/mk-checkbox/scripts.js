jQuery( function($) {
	
	var api = wp.customize;

	// When Customizer has finished loading
	api.bind( 'ready', function() {
		
		$( '.mk-checkbox input[type="checkbox"]' ).on( 'change', function() {
            checkbox_values = $( this ).parents( '.mk-checkbox' ).find( 'input[type="checkbox"]:checked' ).map(
	                function() {
	                    return this.value;
	                }
	            ).get().join( ',' );
            
            	$( this ).parents( '.mk-checkbox' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
	        }
	    );
		
	});
	

});



