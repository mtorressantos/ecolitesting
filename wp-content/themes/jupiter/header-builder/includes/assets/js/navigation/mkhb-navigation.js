(function($) {
	'use strict';

	/**
	* HB Mega menu
	*/
	var $navList = $('.mkhb-navigation-ul');
	var hbMegaMenu = function hbMegaMenu() {

		$navList.HbMegaMenu({
			type: "vertical",
			delay: 200
		});
	};

	$(window).on('load', hbMegaMenu);

	/**
	* WPML
	*/
	var $hb_lang_item = $('.mkhb-navigation > .mkhb-navigation-ul > .menu-item-language');
	$hb_lang_item.addClass('mkhb-no-mega-menu').css('visibility', 'visible');
	$('.mkhb-navigation .menu-item-language > a').addClass('menu-item-link');

	/**
	* Assign header builder navigation click handlers
	*/
	$( document ).on( 'click', '.mkhb-js-smooth-scroll, .mkhb-js-nav a', smoothScrollToAnchor);

	function smoothScrollToAnchor( evt ) {
		var anchor = MK.HB.utils.detectAnchor( this );
		var $this = $(evt.currentTarget);
		var loc = window.location;
		var currentPage = loc.origin + loc.pathname;
		var href = $this.attr( 'href' );
		var linkSplit = (href) ? href.split( '#' ) : '';
		var hrefPage  = linkSplit[0] ? linkSplit[0] : '';

		if( anchor.length ) {
			if(hrefPage === currentPage || hrefPage === '') evt.preventDefault();
			MK.HB.utils.scrollToAnchor( anchor );

		} else if( $this.attr( 'href' ) === '#' ) {
			evt.preventDefault();
		}
	}

	var width  = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );

	// Open the shop list box when hover.
	$( '.menu-item-has-children.mkhb-no-mega-menu' ).hover( function(){
		var offset = $( this ).offset();

		// Measure shop-cart-box current offset and width.
		var this_sub_menu_box = $( this ).find( '.sub-menu' );
		var sub_menu_width = this_sub_menu_box.width();

		// Set shop-cart-box position.
		if ( offset.hasOwnProperty( 'left' ) && ( offset.left + sub_menu_width ) > width ) {
			this_sub_menu_box.css( { 'left': 'auto', 'right': 0 } );
		}
	} );

}(jQuery));
