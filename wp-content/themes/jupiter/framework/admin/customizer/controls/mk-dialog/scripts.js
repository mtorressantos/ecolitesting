jQuery( document ).ready( function( $ ) {

	var api = wp.customize;
	var tippyOptions = {
		zIndex: 99999999,
		theme: 'light',
		arrow: true
	};

	/**
	 * Open the parent dialog and show the tabs.
	 *
	 * @since 5.9.4
	 */
	$( document ).on( 'click', '.mk-dialog-button', function() {

		if ( $( this ).prop( 'disabled' ) ) {
			return;
		}

		$( this ).addClass( 'active' );

		var id = $( this ).data( 'dialog' );
		var title = $( this ).text() + ' Settings';

		$( '#' + id ).dialog( {
			title: title,
			dialogClass: 'mk-dialog mk-dialog-parent ' + id + '-dialog',
			width: 460,
			height: 590,
			resizable: false,
			position: {
				my: 'left+50 top+48',
				at: 'left top',
			},
			create: function() {
				var dialog = $( this );

				// Buttons.
				var closeBtn = $( '<button/>', {
					class: 'mk-close',
					html: '<span class="dashicons dashicons-no"></span> Close',
					click: function() {
						dialog.dialog( 'close' );
					}
				} );

				var resetBtn = $( '<button/>', {
					class: 'mk-reset',
					title: 'loading',
					html: '<span class="dashicons dashicons-backup"></span>',
					click: function() {
						var activeTab = $( '.ui-tabs-nav .ui-state-active' );
						var reset = activeTab.attr( 'aria-controls' );

						$( document.body ).trigger( 'mk-dialog:reset', [ reset, $( this ) ] );
					}
				} );

				// Add reset button in titlebar.
				$( this ).parent().find( '.ui-dialog-titlebar' ).append( [ closeBtn, resetBtn ] );
			},
			open: function() {
				// Disable the buttons to prevent multiple dialogs.
				$( '.mk-dialog-button:not(".active")' ).prop( 'disabled', true );

				tippy('.mk-reset', {
					zIndex: 99999999,
					theme: 'light',
					arrow: true,
					onShow: function () {
						var activeTab = $( '.ui-tabs-nav .ui-state-active:visible' );
						var text = activeTab.text().trim();
						var content = this.querySelector('.tippy-tooltip-content');

						content.innerHTML = 'Reset settings of <strong>' + text + ' tab</strong> to defaults';
					},
				});

				// Build the tabs and their contents.
				mkBuildTabs( $( this ), id );
			},
			beforeClose: function() {
				// Enable the buttons to allow a dialog.
				$( '.mk-dialog-button' ).removeClass( 'active' ).prop( 'disabled', false );
			}
		} );

	} ); // End of parent dialog.

	/**
	 * Open child dialog inside its parent dialog.
	 *
	 * @since 5.9.4
	 */
	$( document ).on( 'click', '.mk-child-dialog-button', function() {

		var id = $( this ).data( 'dialog' );
		var title = $( this ).data( 'title' );

		$( '#' + id + '-dialog' ).dialog({
			title: title,
			dialogClass: 'mk-dialog mk-dialog-child',
			width: 409,
			height: 525,
			resizable: false,
			position: { 
				my: 'left+25 top+35', 
				at: 'left top', 
				of: '.mk-dialog-parent:visible' 
			},
			create: function() {
				var dialog = $( this ),
					dialogId = dialog.attr( 'id' ).replace( '-dialog', '' ),
					dialogParams = api.section( dialogId ).params;

				// Buttons.
				var closeBtn = $( '<button/>', {
					class: 'mk-close',
					html: '<span class="dashicons dashicons-no"></span>',
					click: function() {
						dialog.dialog( 'close' );
					}
				} );

				var resetBtn = $( '<button/>', {
					class: 'mk-reset',
					title: 'Reset settings of <strong>this dialog</strong> to defaults',
					html: '<span class="dashicons dashicons-backup"></span>',
					click: function() {
						$( document.body ).trigger( 'mk-dialog:reset', [ dialogParams.mkReset, $( this ) ] );
					}
				} );

				// Add reset button in titlebar.
				$( this ).parent().find( '.ui-dialog-titlebar' ).append( [ closeBtn, resetBtn ] );
			},
			open: function() {
				var content = $( this );

				$( '.mk-dialog-parent:visible' ).addClass( 'mk-dialog-child-open' );

				if ( $( 'ul', content ).length ) {
					return;
				}

				tippy('.mk-reset', tippyOptions);

				content.append( mkLoadSettings( id ) );
			},
			close: function() {
				$( '.mk-dialog-parent:visible' ).removeClass( 'mk-dialog-child-open' );
			},
			drag: function() {
				// Move the parent dialog according to child dialog.
				$( '.mk-dialog-parent:visible' ).position({
					my: 'center center-30', 
					of: $( this ),
					collision: 'fit',
					// collision: 'none'
				});

				// Close open color picker.
				$( '.mk-color-picker-holder input' ).spectrum( 'hide' );
			}
		});

	} ); // End of child dialog.

	/**
	 * When customizer is ready.
	 *
	 * @since 5.9.4
	 */
	api.bind( 'ready', function() {

		// Add 'Styles' button at top of Widgets section.
		$( '#sub-accordion-panel-widgets .panel-meta .accordion-section-title' ).append( '<button type="button" class="button mk-dialog-button mk-widgets-styles" data-dialog="mk_w_s_dialog">Styles</button>' );

	});

	/**
	 * Reset part of the dialogs settings. If the customizer is dirty, it saves
	 * then reset the settings.
	 *
	 * @since 5.9.4
	 */
	$( document.body ).on( 'mk-dialog:reset', function( event, reset, button ) {
		if ( typeof reset === 'undefined' ) {
			return;
		}

		var userConfirm = confirm( 'Are you sure to reset the settings to default?' );

		if ( ! userConfirm ) {
			return false;
		}

		button.addClass( 'mk-resetting' );

		// If Customizer has unsaved state, save first then reset.
		if ( ! api.state( 'saved' ).get() ) {
			api.previewer.save();

			api.bind( 'saved', function() {
				MkAjaxReset( reset );
			} );

			return false;
		}

		MkAjaxReset( reset );

		return false;
	} );

	/**
	 * Build the tabs inside the parent dialog and append settings.
	 *
	 * @since 5.9.4
	 * @param string content   Main content of the dialog.
	 * @param string dialogId The parent dialog section id.
	 * @return mixed           Necessary markup.
	 */
	function mkBuildTabs( content, dialogId ) {

		// Return if the dialog opened once.
		if ( content.find( 'ul' ).length ) {
			return;
		}

		var dialogs = {};
		var tabs;
		var tabsId = dialogId + '-tabs';

		// Filter sections for dialogs.
		api.section.each( function( section ) {

			// Check if type of the section is dialog.
			if ( section.params.type !== 'mk-dialog' ) {
				return;
			}

			// Return if tab parameter is false.
			if ( section.params.mkTab === false ) {
				return;
			}

			// Return if section doesn't belong to this dialog.
			if ( section.params.mkBelong !== dialogId ) {
				return;
			}

			dialogs[ section.id ] = section.params;

		});

		// Sort based on priority.
		var dialogs = _.sortBy( dialogs, 'priority' );

		// Group dialogs based on tabs.
		tabs = _.groupBy( dialogs, function ( dialog ) { 
			return dialog.mkTab['id'] + ':' + dialog.mkTab['text'];
		} );

		// Create tabs markup.
		content.append( '<div id="' + tabsId + '">\
			<ul></ul>\
		</div>' );
		
		_.each( tabs, function( tab, index ) {

			// Tabs nav.
			var tab_nav = index.split( ':' );

			$( '#' + tabsId + ' > ul' ).append( '<li>\
				<a href="#' + tab_nav[0] + '">' + tab_nav[1] + '</a>\
			</li>' );
			
			$( '#' + tabsId ).append( '<div id="' + tab_nav[0] + '"></div>' );

			// Tabs content.
			_.each( tab, function( element, index ) {

				var tabId = element.mkTab['id'];

				if ( element.mkChild === false ) {
					return content.find( '#' + tabId ).append( mkLoadSettings( element.id ) );
				}

				if ( ! $( '#' + tabId, '#' + tabsId ).find( 'ul' ).length ) {
					$( '#' + tabId, '#' + tabsId ).append( '<ul class="mk-row"></ul>' );
				}

				return $( '#' + tabId + ' > ul', '#' + tabsId ).append( '<li class="mk-col-5 textright"><span class="mk-style-title">' + element.title + '</span></li>\
					<li class="mk-col-7">\
						<button type="button" class="button mk-child-dialog-button" data-dialog="' + element.id + '" data-title="' + element.title + '">\
							<span class="dashicons dashicons-admin-generic"></span> Customize\
						</button>\
						<div id="' + element.id + '-dialog"></div>\
					</li>' );
			});

		} );
		// Initialte the tabs.
		$( "#" + tabsId ).tabs();

	}

	/**
	 * Load a section of settings from Customizer.
	 *
	 * @since 5.9.4
	 * @param  string sectionId ID of the section.
	 * @return object           An object contains of necessary markup.
	 */
	function mkLoadSettings( sectionId ) {
		var settings = '';

		if ( typeof api.section( sectionId ) === 'undefined' ) {
			return 'Invalid section ID.';
		}

		settings = api.section( sectionId ).contentContainer;

		// Remove meta section which is useless.
		settings.children().remove( '.section-meta' );

		// Remove all the class then add necessary class. 
		settings.removeClass().addClass( 'mk-row' );

		return settings[0];
	}

	/**
	 * Send an Ajax request to reset the settings.
	 *
	 * @since 5.9.4
	 * @param string reset ID of the reset section.
	 */
	function MkAjaxReset( reset ) {
		setTimeout( function () {
			wp.ajax.send( 'mk_customizer_reset', {
				data: {
					nonce: mk_cz.nonce,
					reset : reset,
				},
				success: function( response ) {
					window.location.reload( true );
				}
			});
		}, 1000 );
	}

} ); // End of ready function.
