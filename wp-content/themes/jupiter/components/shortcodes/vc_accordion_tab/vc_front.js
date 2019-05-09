(function ( $ ) {
	window.InlineShortcodeView_vc_accordion_tab = window.InlineShortcodeViewContainerWithParent.extend( {
		controls_selector: "#vc_controls-template-vc_tab",
		events: {
			'click > .vc_controls .vc_element .vc_control-btn-delete': 'destroy',
			'click > .vc_controls .vc_element .vc_control-btn-edit': 'edit',
			'click > .vc_controls .vc_element .vc_control-btn-clone': 'clone',
			'click > .vc_controls .vc_element .vc_control-btn-prepend': 'prependElement',
			'click > .vc_controls .vc_control-btn-append': 'appendElement',
			'click > .vc_controls .vc_parent .vc_control-btn-delete': 'destroyParent',
			'click > .vc_controls .vc_parent .vc_control-btn-edit': 'editParent',
			'click > .vc_controls .vc_parent .vc_control-btn-clone': 'cloneParent',
			'click > .vc_controls .vc_parent .vc_control-btn-prepend': 'addSibling',
			'click > .mk-accordion-single > .vc_empty-element': 'appendElement',
			'click > .vc_controls .vc_control-btn-switcher': 'switchControls',
			'mouseenter': 'resetActive',
			'mouseleave': 'holdActive'
		},
		changed: function () {
			if ( this.allowAddControlOnEmpty() && 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' );
				this.content().addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' );
				this.content().removeClass( 'vc_empty-element' );
			}
		},
		render: function () {
			window.InlineShortcodeView_vc_tab.__super__.render.call( this );
			if ( ! this.content().find( '.vc_element[data-tag]' ).length ) {
				this.content().empty();
			}
			this.parent_view.buildAccordion( ! this.model.get( 'from_content' ) && ! this.model.get( 'default_content' ) ? this.model : false );

			// Show Append btn when pane is hovered.
			this.$el.find( '.mk-accordion-pane' ).mouseenter( function() {
				$( this ).parents( '.vc_vc_accordion_tab' ).find( '.vc_controls-bc' ).css({
					'visibility': 'visible',
					'z-index': 9999
				});
			} );
			
			return this;
		},
		rowsColumnsConverted: function () {
			_.each( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
			} );
		},
		destroy: function ( e ) {
			var parent_id = this.model.get( 'parent_id' );
			window.InlineShortcodeView_vc_accordion_tab.__super__.destroy.call( this, e );
			if ( ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
				vc.shortcodes.get( parent_id ).destroy();
			}
		},
		allowAddControl: function () {
			return vc_user_access().shortcodeAll( 'vc_accordion_tab' );
		}
	} );
})( window.jQuery );