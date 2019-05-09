(function ( $ ) {
	window.InlineShortcodeView_mk_contact_form = window.InlineShortcodeView.extend( {
		render: function () {
			window.InlineShortcodeView_mk_contact_form.__super__.render.call( this );

			this.$el.children().find( '.mk-progress-button' )
				.wrap( '<div class="mk-progress-button--wrap"></div>');

			return this;
		}
	} );
})( window.jQuery );
