window.mk = window.mk || {};

jQuery( document ).ready( function( $ ) { 

	var mk = window.mk || {};
	var mkTours = {};
	// Localized data.
	var tourList = mk_tour.list || [];
	var tourNonce = mk_tour.nonce || '';

	// Instance the tour for each active 
	$.each( tourList, function( key, tour ) {
		localStorage.removeItem( tour + '_end' );

		mkTours[tour] = new Tour( {
			name: tour,
			delay: 50,
			template: "<div class='mk-tour popover tour'>\
				<div class='arrow'><span class=\"dashicons dashicons-arrow-left-alt2\"></span></div>\
				<h3 class='popover-title mk-tour-title'></h3>\
				<div class='popover-content'></div>\
				<button class='btn btn-link mk-tour-skip' data-role='end'>Skip Tutorials</button>\
				<div class='popover-navigation'>\
					<button class='btn btn-default mk-tour-btn mk-tour-next' data-role='next'>Next <span class='dashicons dashicons-arrow-right-alt2'></span></button>\
					<button class='btn btn-default mk-tour-btn mk-tour-done' data-role='end'>Done</button>\
				</div>\
			</div>",
			onShown: function ( tour ) {
				var currStepObj = tour.getStep( tour._current );
				var tourUniqueClass = '.tour-' + tour._options.name;

				if ( currStepObj.next < 0 ) {
					$( tourUniqueClass ).addClass( 'mk-tour-last' );
				}
			},
			onEnd: function ( tour ) {
				wp.ajax.send( 'mk_tour_skip', {
					data: {
						nonce: tourNonce,
						skip: tour._options.name,
					},
					success: function( response ) {
						console.log( tour._options.name + ' tour ended.' );
					}
				});
			},
		} );
		
	} );

	mk.tours = mkTours;
} );