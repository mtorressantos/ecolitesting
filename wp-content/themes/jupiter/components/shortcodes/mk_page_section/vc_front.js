(function ( $ ) {
	window.InlineShortcodeView_mk_page_section = window.InlineShortcodeView.extend( {
		column_tag: 'vc_column',
		events: {
			'mouseenter': 'removeHoldActive'
		},
		layout: 1,
		addControls: function () {
			this.$controls = $( '<div class="no-controls"></div>' );
			this.$controls.appendTo( this.$el );
			return this;
		},
		render: function () {
			var $content = this.content();
			if ( $content && $content.hasClass( 'vc_row-has-fill' ) ) {
				$content.removeClass( 'vc_row-has-fill' );
				this.$el.addClass( 'vc_row-has-fill' );
			}
			window.InlineShortcodeView_mk_page_section.__super__.render.call( this );
			return this;
		},
		removeHoldActive: function () {
			vc.unsetHoldActive();
		},
		addColumn: function () {
			vc.builder.create( {
				shortcode: this.column_tag,
				parent_id: this.model.get( 'id' )
			} ).render();
		},
		addElement: function ( e ) {
			e && e.preventDefault();
			this.addColumn();
		},
		changeLayout: function ( e ) {
			e && e.preventDefault();
			this.layoutEditor().render( this.model ).show();
		},
		layoutEditor: function () {
			if ( _.isUndefined( vc.row_layout_editor ) ) {
				vc.row_layout_editor = new vc.RowLayoutUIPanelFrontendEditor( { el: $( '#vc_ui-panel-row-layout' ) } );
			}
			return vc.row_layout_editor;
		},
		convertToWidthsArray: function ( string ) {
			return _.map( string.split( /_/ ), function ( c ) {
				var w = c.split( '' );
				w.splice( Math.floor( c.length / 2 ), 0, '/' );
				return w.join( '' );
			} );
		},
		changed: function () {
			window.InlineShortcodeView_mk_page_section.__super__.changed.call( this );
			this.addLayoutClass();
		},
		content: function () {
			if ( false === this.$content ) {
				this.$content = this.$el.find( '.vc_container-anchor:first' ).parent();
			}
			this.$el.find( '.vc_container-anchor:first' ).remove();
			return this.$content;
		},
		addLayoutClass: function () {
			this.$el.removeClass( 'vc_layout_' + this.layout );
			this.layout = _.reject( vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } ), function ( model ) {
				return model.get( 'deleted' )
			} ).length;
			this.$el.addClass( 'vc_layout_' + this.layout );
		},
		convertRowColumns: function ( layout, builder ) {
			if ( ! layout ) {
				return false;
			}
			var column_params, new_model,
				columns_contents = [],
				columns = this.convertToWidthsArray( layout );
			vc.layout_change_shortcodes = [];
			vc.layout_old_columns = vc.shortcodes.where( { parent_id: this.model.get( 'id' ) } );
			_.each( vc.layout_old_columns, function ( column ) {
				column.set( 'deleted', true );
				columns_contents.push( {
					shortcodes: vc.shortcodes.where( { parent_id: column.get( 'id' ) } ),
					params: column.get( 'params' )
				} );
			} );
			_.each( columns, function ( column ) {
				var prev_settings = columns_contents.shift();
				if ( _.isObject( prev_settings ) ) {
					new_model = builder.create( {
						shortcode: this.column_tag,
						parent_id: this.model.get( 'id' ),
						order: vc.shortcodes.nextOrder(),
						params: _.extend( {}, prev_settings.params, { width: column } )
					} ).last();
					_.each( prev_settings.shortcodes, function ( shortcode ) {
						shortcode.save( {
								parent_id: new_model.get( 'id' ),
								order: vc.shortcodes.nextOrder()
							},
							{ silent: true } );
						vc.layout_change_shortcodes.push( shortcode );
					}, this );
				} else {
					column_params = { width: column };

					new_model = builder.create( {
						shortcode: this.column_tag,
						parent_id: this.model.get( 'id' ),
						order: vc.shortcodes.nextOrder(),
						params: column_params
					} ).last();
				}
			}, this );
			_.each( columns_contents, function ( column ) {
				_.each( column.shortcodes, function ( shortcode ) {
					shortcode.save( {
							parent_id: new_model.get( 'id' ),
							order: vc.shortcodes.nextOrder()
						},
						{ silent: true } );
					vc.layout_change_shortcodes.push( shortcode );
					shortcode.view.rowsColumnsConverted && shortcode.view.rowsColumnsConverted()
				}, this );
			}, this );
			builder.render( function () {
				_.each( vc.layout_change_shortcodes, function ( shortcode ) {
					shortcode.trigger( 'change:parent_id' );
					shortcode.view.rowsColumnsConverted && shortcode.view.rowsColumnsConverted();
				} );
				_.each( vc.layout_old_columns, function ( column ) {
					column.destroy();
				} );
				vc.layout_old_columns = [];
				vc.layout_change_shortcodes = [];
			} );
			return columns;
		},
		allowAddControl: function () {
			return vc_user_access().getState( 'shortcodes' ) !== 'edit';
		},
		allowAddControlOnEmpty: function () {
			return vc_user_access().getState( 'shortcodes' ) !== 'edit';
		}
	} );
	
	vc.AddElementUIPanelFrontendEditor = vc.AddElementUIPanelBackendEditor.vcExtendUI(vc.HelperPanelViewHeaderFooter).extend({
		events: {
			'click [data-vc-ui-element="button-close"]': "hide",
			'click [data-vc-ui-element="panel-tab-control"]': "filterElements",
			"click .vc_shortcode-link": "createElement",
			"keyup #vc_elements_name_filter": "filterElements"
		},
		createElement: function(e) {
			e && e.preventDefault && e.preventDefault();
			var showSettings, newData, i, column_params, row_params, inner_row_params, inner_column_params, $control, tag, preset, presetType, closestPreset;
			if ($control = $(e.currentTarget), tag = $control.data("tag"), row_params = {}, column_params = {
				width: "1/1"
			}, closestPreset = $control.closest("[data-preset]"), closestPreset && (preset = closestPreset.data("preset"), presetType = closestPreset.data("element")), this.prepend && (vc.activity = "prepend"), 0 == this.model)
			if ("vc_section" === tag) {
				var modelOptions = {
					shortcode: tag
				};
				preset && "vc_section" === presetType && (modelOptions.preset = preset), this.builder.create(modelOptions), this.model = this.builder.last()
			} else {
				var container_tag = ("vc_row" === tag || "mk_page_section" === tag) ? tag : "vc_row";
				var rowOptions = {
					shortcode: container_tag ,
					params: row_params
				};
				preset && "vc_row" === presetType && (rowOptions.preset = preset), this.builder.create(rowOptions);
				var columnOptions = {
					shortcode: "vc_column",
					parent_id: this.builder.lastID(),
					params: column_params
				};
				if (preset && "vc_column" === presetType && (columnOptions.preset = preset), this.builder.create(columnOptions), container_tag !== tag) {
					var options = {
						shortcode: tag,
						parent_id: this.builder.lastID()
					};
					preset && presetType === tag && (options.preset = preset), this.builder.create(options)
				}
				this.model = this.builder.last()
			}
			else if ("vc_row" === tag) "vc_section" === this.model.get("shortcode") ? (this.builder.create({
				shortcode: "vc_row",
				params: row_params,
				parent_id: this.model.id,
				order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
			}).create({
				shortcode: "vc_column",
				params: column_params,
				parent_id: this.builder.lastID()
			}), this.model = this.builder.last()) : (inner_row_params = {}, inner_column_params = {
				width: "1/1"
			}, this.builder.create({
				shortcode: "vc_row_inner",
				params: inner_row_params,
				parent_id: this.model.id,
				order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
			}).create({
				shortcode: "vc_column_inner",
				params: inner_column_params,
				parent_id: this.builder.lastID()
			}), this.model = this.builder.last());
			else {
				var options = {
					shortcode: tag,
					parent_id: this.model.id,
					order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.nextOrder()
				};
				preset && presetType === tag && (options.preset = preset), this.builder.create(options), this.model = this.builder.last()
			}
			for (i = this.builder.models.length - 1; i >= 0; i--) this.builder.models[i].get("shortcode");
			_.isString(vc.getMapped(tag).default_content) && vc.getMapped(tag).default_content.length && (newData = this.builder.parse({}, vc.getMapped(tag).default_content, this.builder.last().toJSON()), _.each(newData, function(object) {
				object.default_content = !0, this.builder.create(object)
			}, this)), this.model = this.builder.last(), showSettings = !(_.isBoolean(vc.getMapped(tag).show_settings_on_create) && !1 === vc.getMapped(tag).show_settings_on_create), this.hide(), showSettings && this.showEditForm(), this.builder.render()
		}
	})
	
	
})( window.jQuery );