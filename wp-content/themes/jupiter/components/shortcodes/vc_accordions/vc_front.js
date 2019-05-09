(function ( $ ) {
	window.InlineShortcodeView_vc_accordions = window.InlineShortcodeView_vc_row.extend( {
		render: function () {
			_.bindAll( this, 'stopSorting' );
			this.$accordion = this.$el.find( '> .mk-accordion' );
			window.InlineShortcodeView_vc_accordions.__super__.render.call( this );
			this.setDefaultActiveTab();

			return this;
		},
		setDefaultActiveTab: function () {
			if ( this.getParam( 'open_toggle' ) != 0 ) {
				return;
			}
			var $el = this.$el;
			
			setTimeout( function () {
				$el.find( '.mk-accordion-single' ).eq(0).addClass( 'current' );
			}, 500, $el );
		},
		changed: function () {
			if ( this.allowAddControlOnEmpty() && 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' ).find( '> :first' ).addClass( 'vc_empty-element' );
			} else {
				this.allowAddControlOnEmpty() && this.$el.removeClass( 'vc_empty' ).find( '> .vc_empty-element' ).removeClass(
					'vc_empty-element' );
				this.setSorting();
			}
		},
		buildAccordion: function ( active_model ) {
			var active = false;
			if ( active_model ) {
				active = this.$accordion.find( '[data-model-id=' + active_model.get( 'id' ) + ']' ).index();
			}
			vc.frame_window.vc_iframe.buildAccordion( this.$accordion, active );
		},
		setSorting: function () {
			vc.frame_window.vc_iframe.setAccordionSorting( this );
		},
		stopSorting: function () {
			this.$accordion.find( '> .wpb_accordion_wrapper > .vc_element[data-tag]' ).each( function () {
				var model = vc.shortcodes.get( $( this ).data( 'modelId' ) );
				model.save( { order: $( this ).index() }, { silent: true } );
			} );
		},
		addElement: function ( e ) {
			e && e.preventDefault();
			new vc.ShortcodesBuilder()
				.create( {
					shortcode: 'vc_accordion_tab',
					params: { title: window.i18nLocale.section },
					parent_id: this.model.get( 'id' )
				} )
				.render();
		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		}
	} );
})( window.jQuery );