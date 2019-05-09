jQuery( document ).ready( function( $ ) { 

	var mk = window.mk || {};
	var basePath = mk_tour.base_path || '';
	// Change the last part to the unique tour id defined in functions.php.
	var tour = mk.tours.intro;
	var path = basePath + '/wp-admin/admin.php?page=Jupiter';

	if ( typeof tour === 'undefined' || ! $( 'body.toplevel_page_Jupiter' ).length ) {
		return;
	}

	// There're no classes, add a class to item so Tour can find them.
	$( '#toplevel_page_Jupiter ul li' ).each( function( index ) {
		$( this ).addClass( 'mk-submenu-' + index );
	} );

	// Step 1.
	tour.addStep( {
		element: '.mk-submenu-1',
		placement: 'right',
		content: "You can find the most important settings here! From <strong>Registering Jupiter</strong> & <strong>Installing your template</strong> all the way to <strong>updating your theme</strong>, it's all here!"
	} );

	// Step 2.
	tour.addStep( {
		element: '.mk-submenu-2',
		placement: 'right',
		content: "Select your website's <strong>Settings & Styles</strong> here!"
	} );

	// Step 3.
	tour.addStep( {
		element: '.mk-submenu-3',
		placement: 'right',
		content: "Create multiple headers using an easy <strong>drag & drop builder</strong>, all with limitless customizability!"
	} );

	// Step 4.
	tour.addStep( {
		element: '.mk-submenu-4',
		placement: 'right',
		content: "Here, customize your online shop's <strong>Product Lists</strong>, <strong>Product Pages</strong> and so much more!"
	} );

	// Step 5.
	tour.addStep( {
		element: '#menu-posts-portfolio',
		placement: 'right',
		content: "Showcase your work here with our <strong>Portfolio</strong> post options!"
	} );

	// Step 6.
	tour.addStep( {
		element: '#menu-posts-edge',
		placement: 'right',
		content: "To add an interactive movement and flair to your images, try out <strong>Edge Slider</strong> by adding your slides here!"
	} );

	// Initialize the tour.
	tour.init();

	// Start the tour.
	tour.start();

} );
