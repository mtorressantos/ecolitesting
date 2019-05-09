(function ( $ ) {
	window.InlineShortcodeView_mk_imagebox = window.InlineShortcodeViewContainer.extend( {
		render: function () {
			window.InlineShortcodeView_mk_imagebox.__super__.render.call( this );

			this.$el.children( '.vc_controls-container.vc_controls' )
				.find( '.vc_controls-out-tl' )
				.addClass( 'vc_controls-out-tr')
				.removeClass( 'vc_controls-out-tl' );

			this.$el.children('.vc_controls-container.vc_controls')
				.find( '.vc_controls-bc' )
				.find( '.vc_control-btn-append' )
				.css('top', '-=30');

			return this;
		}
	} );
})( window.jQuery );
