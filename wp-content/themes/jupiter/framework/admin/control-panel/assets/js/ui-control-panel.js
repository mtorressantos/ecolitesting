(function($) {
    "use strict";
    var $wrap = $('#js__mka-cp-wrap');

    // prevent window to be closed if there is a pending operation
    var requestsPending = false;
    window.onbeforeunload = function(event) {
	    var s;
	    event = event || window.event;
	    if (requestsPending > 0) {
	        event.returnValue = s;
	        return s;
	    }
	}

	/**
	 * Check if current browser is IE or Edge.
	 *
	 * Unfortunately, window.MK is not available in Control Panel page.
	 * We need to create a simple function to check current browser is
	 * IE or Edge since EventSource doesn't support them.
	 *
	 * @since 6.0.3
	 *
	 * @return {Boolean} True if it's IE or Edge.
	 */
	var isEdgeIE = function() {
		var userAgent = navigator.userAgent;
		var result = userAgent.match( /Edge|MSIE|Trident/ );

		// Return the status.
		if ( result ) {
			return true;
		}

		return false;
	}

    // Load scripts on load
    mk_register_api_key();
    mk_get_system_report();

    // Trigger on pane ajax load
	$(window).on('control_panel_pane_loaded', mk_get_system_report);
	$(window).on('control_panel_pane_loaded', mk_register_api_key);


    /* Control Panel > pane loading via ajax functionality
     *******************************************************/
     var control_panel_panes = {

     	trigger_pane : function(evt) {
     		evt.preventDefault();
	        var $this = $(this);
	        var hash = $this.attr('href');
	        window.location.hash = hash;
	        var pane_slug = hash.substring(7, hash.length);

	        control_panel_panes.get_pane(hash, pane_slug);

     	},
     	get_pane : function(hash, pane_name) {
     		var $panes = $('#js__mka-cp-panes');
	        $panes.addClass('loading-pane');
	        var data = {
	            action: 'mka_cp_load_pane_action',
	            slug: pane_name
	        };
	        $.post(ajaxurl, data, function(response) {
	            $panes.empty();
	            $panes.append(response.data);
	            $panes.removeClass('loading-pane');
	            $('.mka-cp-sidebar-list-items').removeClass('mka-is-active');
	            $('[href='+hash+']').parent().addClass('mka-is-active');
	            $(window).trigger('control_panel_pane_loaded');
	        });
     	},

     	get_pane_on_load : function() {
     		var hash = window.location.hash;
     		if (hash.length > 0) {
     			var pane_slug = hash.substring(7, hash.length);
     			control_panel_panes.get_pane(hash, pane_slug);
     		}
     		return;
     	},

     	call_to_register : function() {
     		if($('.mka-call-to-register-product').length > 0) {
	    		mk_modal({
		            title: mk_cp_textdomain.product_registeration_required,
		            text: mk_cp_textdomain.you_must_register_your_product,
		            type: 'warning',
		            showCancelButton: false,
		            showConfirmButton: true,
		            showLearnmoreButton: false,
		            confirmButtonText: mk_cp_textdomain.register_product,
		            onConfirm: function() {
		               control_panel_panes.get_pane('#mk-cp-register-product', 'register-product');
		            }
		        });
	    	}
     	}

     };

    $('.mka-cp-sidebar-link').on('click', control_panel_panes.trigger_pane);
    $(window).on('control_panel_pane_loaded', control_panel_panes.call_to_register);
    $(document).ready(control_panel_panes.get_pane_on_load);

    /***************/



    var icon_library = {

    	server_response_status : true,
    	category_name : false,
    	keyword_name : false,

    	init : function () {
    		if($('#mk-cp-icon-library').length == 0) return;
    		icon_library.get_icon_list();
    		$(document).on('change', '#js__icon-lib-category-filter', icon_library.get_icon_by_category);
    		$('#js__icon-lib-search').on('keyup', _.debounce(icon_library.get_icon_by_keyword, 800));
    		$(window).scroll(icon_library.throttle(icon_library.load_more_icons, 200));
    		//console.log('loaded');
    	},

    	load_more_icons : function() {
    		var hT = $('.mka-icon-lib-page-load-more');
			if (hT.length) {
				var wH = $(window).height(),
	            wS = $(window).scrollTop();

		        if (wS > (hT.offset().top - wH)) {
		            icon_library.get_icon_list();
		        }
			}

    	},

    	get_icon_list : function() {

    		if(icon_library.server_response_status === false) return false;

    		var from_number = Number($('.mka-icon-lib-page-load-more').data('from'));
    		var $icon_container = $('#js__icon-lib-list');
    		var data = {
	            action: 'abb_get_icon_list_action',
	            from: from_number,
		        count: 200,
	        };
	        if(icon_library.category_name.length > 0) {
	        	data['category'] = icon_library.category_name;
	        }

	        if(icon_library.keyword_name.length > 0) {
	        	data['keyword'] = icon_library.keyword_name;
	        }

	        $('.mka-icon-lib-page-load-more').addClass('is-active');

	        $.post(ajaxurl, data, function(response) {
	           	var response = JSON.parse(response);
	            $icon_container.append(response.data);

	            $('.mka-icon-lib-page-load-more').data('from', from_number + response.return_count);
	            if(response.return_count == 0) {
	            	icon_library.server_response_status = false;
	            }
	            $('.mka-icon-lib-page-load-more').removeClass('is-active');
	        });
    	},

    	get_icon_by_keyword : function() {
    		var keyword_name = $(this).val();
    			icon_library.keyword_name = keyword_name;
    			icon_library.reset_icon_list();
	        icon_library.server_response_status = true;
	        icon_library.get_icon_list();
    	},

    	get_icon_by_category : function() {
    		icon_library.reset_icon_list();
	        var category_name = $(this).val();
	        	icon_library.category_name = category_name;
	        icon_library.server_response_status = true;
	        icon_library.get_icon_list();
    	},

	    reset_icon_list : function() {
	    	$("#js__icon-lib-list").fadeOut(200, function() {
		        $(this).empty().fadeIn(200);
		    });
		    $('.mka-icon-lib-page-load-more').data('from', 0);
	    },

	    throttle : function(func, wait) {
		    var context, args, timeout, throttling, more, result;
		    var whenDone = _.debounce(function(){ more = throttling = false; }, wait);
		    return function() {
		      context = this; args = arguments;
		      var later = function() {
		        timeout = null;
		        if (more) func.apply(context, args);
		        whenDone();
		      };
		      if (!timeout) timeout = setTimeout(later, wait);
		      if (throttling) {
		        more = true;
		      } else {
		        result = func.apply(context, args);
		      }
		      whenDone();
		      throttling = true;
		      return result;
		    };
		}

    };
    $(document).ready(icon_library.init);
    $(window).on('control_panel_pane_loaded', icon_library.init);


    /* Control Panel > Register API key
     *******************************************************/

     function mk_register_api_key() {
	    $('#js__regiser-api-key-btn').on('click', function(e) {
	        e.preventDefault();
	        var $api_key = $('#mka-cp-register-api-input').val();
	        if ($api_key.length === 0) return false;
	        mk_modal({
	            title: mk_cp_textdomain.registering_theme,
	            text: mk_cp_textdomain.wait_for_api_key_registered,
	            type: '',
	            cancelButtonText: mk_cp_textdomain.discard,
	            showCancelButton: true,
	            showConfirmButton: false,
	            showCloseButton: false,
	            showLearnmoreButton: false,
	            showProgress: true,
	            indefiniteProgress: true,
	        });
	        var data = {
	            action: 'mka_cp_register_revoke_api_action',
	            method: 'register',
	            api_key: $api_key,
	            security: $('#security').val()
	        };
	        $.post(ajaxurl, data, function(response) {
	            var response = JSON.parse(response);
	            if (response.status === true) {
	                mk_modal({
	                    title: mk_cp_textdomain.thanks_registering,
	                    text: response.message,
	                    type: 'success',
	                    showCancelButton: false,
	                    showConfirmButton: true,
	                    showCloseButton: false,
	                    showLearnmoreButton: false,
	                    showProgress: false,
	                    indefiniteProgress: true,
	                });
	                $('.mka-cp-revoke-api').addClass('is-active');
	                $('.mka-cp-register-api').removeClass('is-active');
	                $('.mka-cp-api-key-text').text($api_key);
	                $('.js__no-api-key-warning-icon').removeClass('is-active');
	            } else {
	                mk_modal({
	                    title: mk_cp_textdomain.registeration_unsuccessful,
	                    text: response.message,
	                    type: 'error',
	                    showCancelButton: false,
	                    showConfirmButton: true,
	                    showCloseButton: false,
	                    showLearnmoreButton: false,
	                    //learnmoreButton: '#',
	                    showProgress: false,
	                    onConfirm: function() {
	                        $('#mka-cp-register-api-input').val('');
	                    }
	                });
	            }
	        });
	    });
	    $('#js__revoke-api-key-btn').on('click', function(e) {
	        e.preventDefault();
	        mk_modal({
	            title: mk_cp_textdomain.revoke_API_key,
	            text: mk_cp_textdomain.you_are_about_to_remove_API_key,
	            type: 'warning',
	            showCancelButton: true,
	            showConfirmButton: true,
	            showLearnmoreButton: false,
	            confirmButtonText: mk_cp_textdomain.ok,
	            cancelButtonText: mk_cp_textdomain.cancel,
	            onConfirm: function() {
	                var data = {
	                    action: 'mka_cp_register_revoke_api_action',
	                    method: 'revoke',
	                    security: $('#security').val()
	                };
	                $.post(ajaxurl, data, function(response) {
	                    var response = JSON.parse(response);
	                    if (response.status === true) {
	                        $('.mka-cp-register-api').addClass('is-active');
	                        $('.mka-cp-revoke-api').removeClass('is-active');
	                        $('#mka-cp-register-api-input').val('');
	                        $('.js__no-api-key-warning-icon').addClass('is-active');
	                    }
	                });
	            }
	        });
	    });
	}
	/***************/



	/* Control Panel > Install Plugins
     *******************************************************/

     var plugins = {

     	init : function() {
     		if($('#mk-cp-plugins').length == 0) return;
     		plugins.get_installed_plugin_list();
			plugins.get_new_plugin_list();

			$( document ).on('click', '.abb_plugin_activate', plugins.activate_init );
			$( document ).on('click', '.abb_plugin_deactivate', plugins.deactivate_init );
			$( document ).on('click', '.abb_plugin_update', plugins.update_plugin );
     	},

     	get_installed_plugin_list : function() {
		    var req_data = { action: 'abb_installed_plugins' };

		    $.post(ajaxurl, req_data, function(response) {
		        console.log('Install Plugin :', req_data, ' Response :', response);
		        $('#js__mka-installed-plugins').html('');
		        if (response.status == true) {
		            $.each(response.data, function(key, val) {
		                $('#js__mka-installed-plugins').append(plugins.get_installed_list_template(val));
		            });

		        } else {

		        	mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: response.message,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		        }
		    });

     	},

     	get_new_plugin_list : function() {

     		var req_data = {
		        action: 'abb_lazy_load_plugin_list',
		    }

		    $.post(ajaxurl, req_data, function(response) {

		        console.log('Get Plugin :', req_data, ' Response :', response);
		        $('#js__mka-new-plugins').html('');

		        if (response.status == true) {
		            if (response.data.length > 0) {
		                $.each(response.data, function(key, val) {
		                    $('#js__mka-new-plugins').append(plugins.get_new_list_template(val));
		                });
		            }
		        } else {
		        	mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: response.message,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		        }

		    });
     	},


     	get_installed_list_template : function(data) {
		    var btn = '';
		    var update_tag = '';

		    if (data.update_needed == true) {
		        btn += '<a href="#" class="mka-cp-plugin-update-button mka-button mka-button--blue mka-button--small abb_plugin_update" data-slug="' + data.slug + '" data-name="' + data.name + '">Update</a>';

		        update_tag = '<span class="mka-cp-plugin-update-tag">Update Available</span>';
		    }

		    btn += '<a href="#" class="mka-button mka-button--red mka-button--small abb_plugin_deactivate" data-slug="' + data.slug + '" data-name="' + data.name + '">Remove</a>';

		    var template = '<div class="mka-cp-plugin-item mka-clearfix">' +
		    					'<figure class="mka-cp-plugin-item-thumb">' +
		        						'<img src="'+data.img_url+'">'+
		        				'</figure>' +
		        				'<div class="mka-cp-plugin-item-meta">' +
		        					'<div class="mka-cp-plugin-item-name">' + data.name + '<span class="mka-cp-plugin-item-version">Version <span class="item-version-tag">' + data.version + '</span></span>' + update_tag +'</div>'+
		        					'<div class="mka-cp-plugin-item-desc">' + data.desc + '</div>' +
		        				'</div>' +
		        				'<div class="mka-cp-plugin-item-actions">' + btn + '</div>' +
		        			'</div>';
		    return template;
     	},


     	get_new_list_template : function(data) {

	       	return '<div class="mka-cp-plugin-item mka-clearfix">' +
    					'<figure class="mka-cp-plugin-item-thumb">' +
        						'<img src="'+data.img_url+'">'+
        				'</figure>' +
        				'<div class="mka-cp-plugin-item-meta">' +
        					'<div class="mka-cp-plugin-item-name">' + data.name + '<span class="mka-cp-plugin-item-version">Version <span class="item-version-tag">' + data.version + '</span></span></div>'+
        					'<div class="mka-cp-plugin-item-desc">' + data.desc + '</div>' +
        				'</div>' +
        				'<div class="mka-cp-plugin-item-actions">' +
        					'<a class="mka-button mka-button--green mka-button--small abb_plugin_activate" data-slug="' + data.slug + '" data-name="' + data.name + '">Activate</a>' +
        				'</div>' +
        			'</div>';

		},


     	activate_init : function(evt) {
     		evt.preventDefault();
     		var $this = $(this);
     		mk_modal({
                title: mk_cp_textdomain.installing_notice,
                text: plugins.language(mk_cp_textdomain.are_you_sure_you_want_to_install, [$this.data('name')]),
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: mk_cp_textdomain.conitune,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: false,
                onConfirm : function() {
                	plugins.activate_start($this.data('slug'));
                }
            });
     	},

     	activate_start : function(plugin_slug) {

			    var $btn = $('.abb_plugin_activate[data-slug="' + plugin_slug + '"]');

			    var plugin_name = $btn.data('name');

			    var req_data = {
			        action: 'abb_install_plugin',
			        abb_controlpanel_plugin_name: plugin_name,
			        abb_controlpanel_plugin_slug: plugin_slug,
			    }

			    mk_modal({
		            title: mk_cp_textdomain.activating_plugin,
		            text: mk_cp_textdomain.wait_for_plugin_activation,
		            type: '',
		            showCancelButton: false,
		            showConfirmButton: false,
		            showCloseButton: false,
		            showLearnmoreButton: false,
		            showProgress: true,
		            indefiniteProgress: true,
		        });

			    $.ajax({
			        type: "POST",
			        url: ajaxurl,
			        data: req_data,
			        dataType: "json",
			        timeout: 60000,
			        success: function(response) {

			            console.log('Install Plugin :', req_data, ' Response :', response);

			            if (response.hasOwnProperty('status')) {

			                if (response.status == true) {

			                    $btn.closest('.mka-cp-plugin-item').prependTo('#js__mka-installed-plugins');
			                    $btn.removeClass('mka-button--green').addClass('mka-button--red');
			                    $btn.html(mk_cp_textdomain.remove);
			                    $btn.addClass('abb_plugin_deactivate').removeClass('abb_plugin_activate');

			                    mk_modal({
				                    title: mk_cp_textdomain.all_done,
				                    text: plugins.language(mk_cp_textdomain.item_is_successfully_installed, [plugin_name]),
				                    type: 'success',
				                    showCancelButton: false,
				                    showConfirmButton: true,
				                    showCloseButton: false,
				                    showLearnmoreButton: false,
				                    showProgress: false,
				                    indefiniteProgress: true,
				                });
			                    return true;
			                } else {
			                    // Something goes wrong in install progress
			                    mk_modal({
				                    title: mk_cp_textdomain.oops,
				                    text: response.message,
				                    type: 'error',
				                    showCancelButton: false,
				                    showConfirmButton: true,
				                    showCloseButton: false,
				                    showLearnmoreButton: false,
				                    //learnmoreButton: '#',
				                    showProgress: false,
				                    onConfirm: function() {
				                        $('#mka-cp-register-api-input').val('');
				                    }
				                });
				                mk_modal({
					                title: mk_cp_textdomain.oops,
					                text: response.message,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
			                }
			            } else {
			                // Something goes wrong in server response
			                mk_modal({
				                title: mk_cp_textdomain.oops,
				                text: mk_cp_textdomain.something_wierd_happened_please_retry_again,
				                type: 'error',
				                showCancelButton: false,
				                showConfirmButton: true,
								showLearnmoreButton: false,
				            });
			            }
			        },
			        error: function(XMLHttpRequest, textStatus, errorThrown) {
			            plugins.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
			        }
			    });
     	},

     	deactivate_init : function(evt) {
		        evt.preventDefault();
		        var $this = $(this);

		        mk_modal({
	                title: plugins.language(mk_cp_textdomain.important_notice, []),
	                text: plugins.language(mk_cp_textdomain.are_you_sure_you_want_to_remove_plugin, [$this.data('name')]),
	                type: 'warning',
	                showCancelButton: true,
	                showConfirmButton: true,
	                confirmButtonText: plugins.language(mk_cp_textdomain.conitune, []),
	                showCloseButton: false,
	                showLearnmoreButton: false,
	                showProgress: false,
	                onConfirm : function() {
	                	plugins.deactivate_start($this.data('slug'));
	                }
	            });

     	},


     	deactivate_start : function(plugin_slug) {

			    var $btn = $('.abb_plugin_deactivate[data-slug="' + plugin_slug + '"]');
			    var plugin_name = $btn.data('name');

			    var req_data = {
			        action: 'abb_remove_plugin',
			        abb_controlpanel_plugin_name: plugin_name,
			        abb_controlpanel_plugin_slug: plugin_slug,
			    }

			    mk_modal({
		            title: mk_cp_textdomain.deactivating_plugin,
		            text: mk_cp_textdomain.wait_for_plugin_deactivation,
		            type: '',
		            showCancelButton: false,
		            showConfirmButton: false,
		            showCloseButton: false,
		            showLearnmoreButton: false,
		            showProgress: true,
		            indefiniteProgress: true,
		        });

			    $.ajax({
			        type: "POST",
			        url: ajaxurl,
			        data: req_data,
			        dataType: "json",
			        timeout: 60000,
			        success: function(response) {

			            console.log('Deactivate Process : ' , req_data , 'Response : ' , response);

			            if (response.hasOwnProperty('status')) {

			                if (response.status == true) {

			                    $btn.closest('.mka-cp-plugin-item').prependTo('#js__mka-new-plugins');
			                    $btn.closest('.mk-plugin-update-tag').remove();
			                    $btn.removeClass('mka-button--red').addClass('mka-button--green');
			                    $('.abb_plugin_update[data-slug="' + plugin_slug + '"]').remove();
			                    $btn.html(mk_cp_textdomain.activate);
			                    $btn.addClass('abb_plugin_activate').removeClass('abb_plugin_deactivate');

			                    mk_modal({
				                    title: mk_cp_textdomain.deactivating_notice,
				                    text: plugins.language(mk_cp_textdomain.plugin_deactivate_successfully, []),
				                    type: 'success',
				                    showCancelButton: false,
				                    showConfirmButton: true,
				                    showCloseButton: false,
				                    showLearnmoreButton: false,
				                    showProgress: false,
				                    indefiniteProgress: true,
				                });
			                    return true;
			                } else {
			                    // Something goes wrong in install progress
			                    mk_modal({
					                title: mk_cp_textdomain.oops,
					                text: response.message,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
			                }
			            } else {
			                // Something goes wrong in server response
			                mk_modal({
					                title: mk_cp_textdomain.oops,
					                text: mk_cp_textdomain.something_wierd_happened_please_retry_again,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
			            }
			        },
			        error: function(XMLHttpRequest, textStatus, errorThrown) {
			            plugins.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
			        }
			    });
     	},


     	update_plugin : function(evt) {
     		evt.preventDefault();
     		var $this = $(this);
     		var plugin_name = $this.data('name');
     		mk_modal({
                title: mk_cp_textdomain.update_plugin,
                text: mk_cp_textdomain.you_are_about_to_update + ' ' + plugin_name,
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: mk_cp_textdomain.conitune,
                showCloseButton: false,
                showLearnmoreButton: false,
                showProgress: false,
                onConfirm : function() {
                	plugins.update_start($this);
                }
            });
     	},

     	update_start : function($this) {

	        var plugin_slug = $this.data('slug');

     		var $this = $('.abb_plugin_update[data-slug="' + plugin_slug + '"]');
		    var plugin_name = $this.data('name');

			mk_modal({
	            title: mk_cp_textdomain.updating_plugin,
	            text: mk_cp_textdomain.wait_for_plugin_update,
	            type: '',
	            showCancelButton: false,
	            showConfirmButton: false,
	            showCloseButton: false,
	            showLearnmoreButton: false,
	            showProgress: true,
	            indefiniteProgress: true,
	        });

		    var req_data = {
		        action: 'abb_update_plugin',
		        abb_controlpanel_plugin_name: plugin_name,
		        abb_controlpanel_plugin_slug: plugin_slug,
		    }

		    $.ajax({
		        type: "POST",
		        url: ajaxurl,
		        data: req_data,
		        dataType: "json",
		        timeout: 60000,
		        success: function(response) {

		            console.log('Update Plugin :', req_data, ' Response :', response);

		            if (response.hasOwnProperty('status')) {
		                if (response.status == true) {
		                	var version = response.message;
		                	var this_plugin_item = $this.closest('.mka-cp-plugin-item');

		                	mk_modal({
			                    title: mk_cp_textdomain.plugin_is_successfully_updated,
			                    text: plugin_name + mk_cp_textdomain.plugin_updated_recent_version,
			                    type: 'success',
			                    showCancelButton: false,
			                    showConfirmButton: true,
			                    showCloseButton: false,
			                    showLearnmoreButton: false,
			                    showProgress: false,
			                });

		                    this_plugin_item.find('.mka-cp-plugin-update-tag').remove();
		                    this_plugin_item.find('.item-version-tag').text(version);
		                    $this.remove();
		                    return true;
		                } else {
		                    // Something goes wrong in install progress
		                    mk_modal({
					                title: mk_cp_textdomain.something_went_wrong,
					                text: response.message,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
		                }
		            } else {
		                // Something goes wrong in server response
		                mk_modal({
					                title: mk_cp_textdomain.oops,
					                text: mk_cp_textdomain.something_wierd_happened_please_retry_again,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
		            }
		        },
		        error: function(XMLHttpRequest, textStatus, errorThrown) {
		            plugins.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
		        }
		    });
     	},


     	request_error_handling : function(XMLHttpRequest, textStatus, errorThrown) {

     		 console.log(XMLHttpRequest);

		    if (XMLHttpRequest.readyState == 4) {
		        // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
		        mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: XMLHttpRequest.status,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		    } else if (XMLHttpRequest.readyState == 0) {
		        // Network error (i.e. connection refused, access denied due to CORS, etc.)
		        mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: mk_cp_textdomain.error_in_network_please_check_your_connection_and_try_again,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		    } else {
		    	mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: mk_cp_textdomain.something_wierd_happened_please_retry_again,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		    }
     	},

     	language : function(string, params) {
 		 	if (typeof string == 'undefined' || string == '') {
		        return;
		    }
		    var array_len = params.length;
		    if (array_len < 1) {
		        return string;
		    }
		    var indicator_len = (string.match(/{param}/g) || []).length;

		    if (array_len == indicator_len) {
		        $.each(params, function(key, val) {
		            string = string.replace('{param}', val);
		        });
		        return string;
		    }

		    // Array len and indicator lengh is not same;
		    console.log('Array len and indicator lengh is not same, Contact support with ID : (3-6H1T4I) .');
		    return string;
     	}
     };

     $(window).on('control_panel_pane_loaded', plugins.init);
     /***************/







     /* Control Panel > Install Plugins
     *******************************************************/


     var templates = {

     	template_pre_request : 9,
     	server_response_status : false,
	    install_types : ['preparation',
	    				'backup_db',
	    				'backup_media_records',
	    				'reset_db',
	    				'upload',
	    				'unzip',
	    				'validate',
	    				'plugin',
	    				'theme_content',
	    				'menu_locations',
	    				'setup_pages',
	    				'theme_options',
	    				'customizer',
	    				'header_builder',
	    				'theme_widget',
	    				'restore_media_records',
	    				'finalize'],
	    template_id : null,
	    template_name : null,
	    import_media : false,
	    progress_bar_html : '',

	    init : function() {
	    	if($('#mk-cp-templates').length == 0) return;
	    	console.log('templates loaded');
	    	templates.get_installed_template();
		    templates.get_template_list();
		    templates.get_template_category_list();
		    $(window).scroll(templates.throttle(templates.infinite_load_templates, 200));
		    $(document).on('click', '#js__restore-template-btn', templates.get_template_restore_init);
			$(document).on('click', '#js__cp_template_uninstall', templates.uninstall_template_trigger);
		    $(document).on('change', '#js__templates-category-filter', templates.template_category_filter);
		    $('#js__template-search').on('keyup', _.debounce(templates.template_search, 800));
		    $(document).on('click', '.js__cp_template_install', templates.template_install_init);
            $(document).on('click', '.mka-download-psd', templates.download_template_psd);
	    },

		 download_template_psd : function() {
			 var template_name = $(this).attr("data-slug");
			 var req_data = {
				 action: 'abb_get_template_psd_link',
				 template_name: template_name,
			 }

			 jQuery.post(ajaxurl, req_data, function(response) {
				 if (response.status == true) {
					top.location.href = response.data.psd_link;
				 } else {
					 console.log(response);
					 swal("Oops...", response.message, "error");
				 }
			 });
		 },

	    get_template_list_html : function(data) {

	    	// ES6 Patching - Setting default value for "installed" argument
	    	var installed = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

		    // Sanitize template names for URLs
		    var installed_item_class = '',
		    	slug = '';

		    if(data.slug == 'jupiter-default-template') {
		    	slug = '';
		    } else {
		    	slug = data.slug + '/';
		    }

		    if (installed == false) {
		        var btn =
		            '<a class="mka-button mka-button--green mka-button--small mka-cp-template-item-btn js__cp_template_install" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '">' +
		            mk_cp_textdomain.install +
		            '</a>' +
		            '<a class="mka-button mka-button--gray mka-button--small mka-cp-template-item-btn" href="http://demos.artbees.net/jupiter/' + slug + '" target="_blank">' +
		            mk_cp_textdomain.preview +
		            '</a>';
		    } else {
		        var btn =
		            '<a id="js__cp_template_uninstall" class="mka-button mka-button--red mka-button--small mka-cp-template-item-btn" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '">' +
		            mk_cp_textdomain.uninstall +
		            '</a>' +
		            '<a class="mka-button mka-button--gray mka-button--small mka-cp-template-item-btn" href="http://demos.artbees.net/jupiter/' + slug +'" target="_blank">' +
		            mk_cp_textdomain.preview +
		            '</a>';
		    }

		    // if psd file uploaded
			if(data.psd_file) {
				btn += '<a class="mka-button mka-download-psd" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '" title="' + mk_cp_textdomain.download_psd_files + '"></a>';
			}

		    return  '<div class="mka-cp-template-item ">' +
			            '<div class="mka-cp-template-item-inner">' +
				            '<figure class="mka-cp-template-item-fig">' +
				            	'<img src="' + data.img_url + '" alt="' + data.name + '">' +
				            '</figure>' +
				            '<div class="mka-cp-template-item-meta">' +
				            	'<h4 class="mka-cp-template-item-name">' + data.name + '</h4>' +
				            	'<div class="mka-cp-template-item-buttons' + (data.psd_file ? ' has-psd' : '') + '">' + btn + '</div>' +
				            '</div>' +
			            '</div>' +
		            '</div>';

		},

     	get_template_list : function() {
     		var from_number = Number($('.abb-template-page-load-more').data('from'));
		    templates.server_response_status = true;

		    var req_data = {
		        action: 'abb_template_lazy_load',
		        from: from_number,
		        count: templates.template_pre_request,
		    }

		    if (typeof templates.template_id !== 'undefined' && templates.template_id !== null) {
		        req_data['template_category'] = templates.template_id;
		    }
		    if (typeof templates.template_name !== 'undefined' && templates.template_name !== null) {
		        req_data['template_name'] = templates.template_name;
		    }

		    $('.abb-template-page-load-more').addClass('is-active');


		    $.post(ajaxurl, req_data, function(response) {

		        if (response.status == true) {
		            var backups        = response.data.backups;
		            var list_of_backups    = [];
		            var latest_backup_file = null;
		            var created_date       = "";

		            if(backups.hasOwnProperty('list_of_backups')){

		                list_of_backups = backups.list_of_backups;

		                if (list_of_backups == null) {

		                    console.log("List Of Backups is NULL!");

		                } else if (list_of_backups.length == 0) {

		                    console.log("List Of Backups is EMPTY!");

		                } else {

		                    latest_backup_file = backups.latest_backup_file;
		                    created_date = latest_backup_file.created_date;
		                    $('#js__restore-template-wrap').addClass('is-active');
		                    $('#js__restore-template-wrap .js__backup-date').html(created_date);
		                }
		            }
		            if (response.data.templates.length > 0) {

		                $('.abb-template-page-load-more').data('from', from_number + response.data.templates.length);

		                $.each(response.data.templates, function(key, val) {

		                	var $installed_template = $('#js__installed-template').data('installed-template');

		                	if($installed_template !== val.slug) {
                                    $('#js__new-templates-list').append(templates.get_template_list_html(val));
		                	}

		                });
		                templates.server_response_status = false;
		            }
		            $('.abb-template-page-load-more').removeClass('is-active');
		        } else {
		            console.log(response);
		            mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: response.message,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		        }
		    });

     	},


     	get_template_category_list : function() {
		    var empty_category_list = '<span class="mka-select-list-item" data-value="no-category">No category found</span>';
		    var category_option_wrap = $('.mka-cp-template-category-filter .mka-select-list');
		    $.ajax({
		        type: "POST",
		        url: ajaxurl,
		        data: { action: 'abb_get_templates_categories' },
		        dataType: "json",
		        timeout: 60000,
		        success: function(response) {
		            if (response.hasOwnProperty('status') === true) {
		                if (response.status === true) {
		                    var category_list = '<span class="mka-select-list-item" data-value="all-categories">All Categories</span>';
		                    $.each(response.data, function(key, val) {
		                        category_list += '<span class="mka-select-list-item" data-value="' + val.id + '">' + val.name + ' - ' + val.count + '</span>';
		                    });
		                    category_option_wrap.html(category_list);
		                } else {
		                    category_option_wrap.html(empty_category_list);
		                }
		            } else {
		                category_option_wrap.html(empty_category_list);
		            }
		        },
		        error: function(XMLHttpRequest, textStatus, errorThrown) {
		            category_option_wrap.html(empty_category_list);
		            templates.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
		        }
		    });
		},

		reset_template_list : function() {
		    $("#js__new-templates-list").fadeOut(200, function() {
		        $(this).empty().fadeIn(200);
		    });
		    $('.abb-template-page-load-more').data('from', 0);
		},

		template_category_filter : function() {
	        templates.reset_template_list();
	        templates.template_id = $(this).val();
	        templates.get_template_list();
		},

		template_search : function(e) {
	        templates.reset_template_list();
		    templates.template_name = $(this).val();
		    templates.get_template_list();

	    },

	    get_installed_template : function() {

	    	// ES6 Patching - Setting default value for "slug" argument
	    	var id = arguments.length > 0 && arguments[1] !== undefined ? arguments[1] : false;
	    	var slug = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

	    	var installed_template_id = $('#js__installed-template').data('installed-template-id');

	    		if(id) {
	    			installed_template_id = id;
	    		}

	    	var installed_template = $('#js__installed-template').data('installed-template');

	    		if(slug) {
	    			installed_template = slug;
	    		}

	    	if( installed_template_id.length <= 0 && installed_template.length <= 0  ) return;

	    	var req_data = {
		        action: 'abb_template_lazy_load',
		        from: 0,
		        count: 1,
		        template_id: installed_template_id,
		        template_name: installed_template,
		    }

		    $.post(ajaxurl, req_data, function(response) {

		        if (response.status == true) {

		            if (response.data.templates.length > 0) {

		                $.each(response.data.templates, function(key, val) {
		                	$('#js__installed-template-wrap').show();
		                    $('#js__installed-template').attr('data-installed-template-id', val.id).attr('data-installed-template', val.slug).empty().append(templates.get_template_list_html(val, true));
		                });
		            }
		        }
		    });

	    },

	    template_install_init : function() {
	        var $this = $(this);
	        var $CredentialsModal = $('#request-filesystem-credentials-dialog');

	        if($CredentialsModal.length){

	            var $CredentialsData = templates.get_fs_credential_data(); // Get stored credentials data.
	            if( !$CredentialsData ){
	                templates.get_fs_credential_modal($CredentialsModal, $this, 'install');
	            }else{
	                templates.get_template_install_modal($this);
	            }
	        }else{
	            templates.get_template_install_modal($this);
	        }
	    },

	    get_template_install_modal : function($btn){
	        mk_modal({
                title: mk_cp_textdomain.important_notice,
                text: mk_cp_textdomain.installing_sample_data_will_delete_all_data_on_this_website + '<br> You are about to install <strong>'   + $btn.data('name') + '</strong>',
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: mk_cp_textdomain.yes_install,
                showCloseButton: true,
                showLearnmoreButton: false,
                onConfirm : function() {
            		mk_modal({
		                title: mk_cp_textdomain.include_images_and_videos,
		                text: templates.language(mk_cp_textdomain.would_you_like_to_import_images_and_videos_as_preview, ['<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
		                type: 'warning',
		                showCancelButton: true,
		                showConfirmButton: true,
		                confirmButtonText: mk_cp_textdomain.do_not_include,
	                	cancelButtonText: mk_cp_textdomain.include,
		                showCloseButton: true,
		                showLearnmoreButton: false,
		                onConfirm : function() {
		                	templates.import_media = false;
		                	templates.start_importing_template($btn);
		                },
						onCancel : function() {
							if ( isEdgeIE() ) {
								mk_modal({
									title: mk_cp_textdomain.using_ie_edge_not_support,
									text: templates.language( mk_cp_textdomain.recommend_to_use_other_browsers, ['<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
									type: 'warning',
									showCancelButton: true,
									showConfirmButton: true,
									confirmButtonText: mk_cp_textdomain.continue,
									cancelButtonText: mk_cp_textdomain.cancel,
									showCloseButton: true,
									showLearnmoreButton: false,
									onConfirm : function() {
										templates.get_system_resource( $btn );
									}
								});
							} else {
								templates.get_system_resource( $btn );
							}
						}
		            });
                }
            });
	    },


	    start_importing_template : function( $btn ) {
	    		var custom_html = '';
			    custom_html += '<div>';
				    custom_html += '<h3 class="mka-modal-title">'+ mk_cp_textdomain.install_sample_data +'</h3>';
				    custom_html += '<div class="mka-modal-desc">';
				        custom_html += '<ul class="mka-modal-step-list">';
				        	custom_html += '<li id="js__cp-template-install-steps-backup">' + mk_cp_textdomain.backup_reset_database + ' <span class="result-message"></span></li>';
				            custom_html += '<li id="js__cp-template-install-steps-upload">' + mk_cp_textdomain.downloading_sample_package_data + ' <span class="result-message"></span></li>';
				            custom_html += '<li id="js__cp-template-install-steps-plugin">' + mk_cp_textdomain.install_required_plugins + ' <span class="result-message"></span></li>';
				            custom_html += '<li id="js__cp-template-install-steps-install">'+ mk_cp_textdomain.install_sample_data +' <span class="result-message"></span></li>';
				        custom_html += '</ul>';
				    custom_html += '</div>';
			    custom_html += '</div>';

			    templates.progress_bar_html = $(custom_html);

				mk_modal({
	        		html: templates.progress_bar_html,
	        		showProgress: true,
	        		progress: '0%',
	        		showCloseButton: false,
	        		closeOnOutsideClick: false,
				});

		    templates.install_template(0, $btn.data('name'), $btn.data('id'));
		},

		finalise_template_install : function(index, template_name, template_id) {

				templates.get_installed_template(template_name, template_id);

		       	var $installed_template = $('a[data-slug="' + template_name + '"]');
		       	$installed_template.parents('.mka-cp-template-item').hide();

		        mk_modal({
                    title: mk_cp_textdomain.hooray,
                    text: mk_cp_textdomain.template_installed_successfully,
                    type: 'success',
                    showCancelButton: false,
                    showConfirmButton: true,
                    showCloseButton: false,
                    showLearnmoreButton: false
	            });

	            requestsPending = false;

		        templates.create_restore_button();

		},

		install_template : function(index, template_name, template_id) {

			// If no more steps, exit
			if (templates.install_types[index] == undefined) {
				templates.finalise_template_install(index, template_name, template_id);
				return false;
			}

		    var $storedCredentialsData = templates.get_fs_credential_data();

		    var formDataTemplateProcedure = {
		        action: 'abb_install_template_procedure',
		        type: templates.install_types[index],
		        template_name: template_name,
		        template_id: template_id,
		        import_media: templates.import_media
		    };

		    // Inject stored FTP Credentials Data for each request.
		    if($storedCredentialsData){
		        formDataTemplateProcedure = $.extend(
		            {},
		            $storedCredentialsData,
		            formDataTemplateProcedure
		        );
		    }

			requestsPending = 1;

			/**
			 * Handle theme_content import with Server-Sent Event.
			 *
			 * If the type is theme_content import and EventSource is exist, use it.
			 * ATTENTION: EventSource is not supported in IE and Edge, except we use
			 * some extension library. If users are using unsupported browser, they
			 * will use Ajax request instead.
			 *
			 * @since 6.0.3
			 */
			if ( formDataTemplateProcedure.type == 'theme_content' && !! window.EventSource && ! isEdgeIE() ) {

				console.log( 'EventSource is triggered. The event log will be streamed below in separated interval.' )

				// Set URL param manually.
				var url = ajaxurl + '?action=abb_install_template_sse&template_name=' + template_name + '&fetch_attachments=' + templates.import_media + '&template_id=' + template_id;

				// Set error modal function.
				var errorModal = function( text ) {
					// Set default error text.
					var errorText = mk_cp_textdomain.something_wierd_happened_please_try_again;
					if ( text ) {
						errorText = text;
					}

					// Something goes wrong in server response.
					mk_modal({
						title: mk_cp_textdomain.oops,
						text: text,
						type: 'error',
						showCancelButton: false,
						showConfirmButton: true,
						showLearnmoreButton: false,
					});

					// Pending the installation process.
					requestsPending = false;
				}

				// Create EventSource instance.
				var evtSource = new EventSource( url );

				// Handle messages.
				evtSource.onmessage = function ( message ) {
					// Parse the response.
					var response = JSON.parse( message.data );

					var response_text = '';
					if ( response.hasOwnProperty( 'message' ) ) {
						response_text = response.message;
					}

					if ( response.hasOwnProperty( 'error' ) && response.hasOwnProperty( 'status' ) ) {

						// Success means the status is true and no error found.
						if ( ! response.error && response.status ) {

							console.log( 'Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

							// Update the progress bar in modal.
							mk_modal.update({
								progress: Math.round((index * 100) / (templates.install_types.length - 1)) + '%',
							});

							// Show install template message.
							templates.show_install_template_messages( templates.install_types[index], response_text );

							// Go to the next step, from theme_content to menu_locations.
							templates.install_template( ++index, template_name, template_id );

							// Close to avoid resent request after success.
							evtSource.close();
							return true;
						}

					}

					errorModal( response_text );

					// Close to avoid resent request after success.
					evtSource.close();
					return true;
				};

				// Handle error event.
				evtSource.onerror = function( e ) {
					console.log( 'EventSource take a long time, try to separate the log.' );
				};

				// Tracking and print the server log.
				evtSource.addEventListener( 'log', function ( message ) {
					console.log( message.data )
				});

			} else {

				// For other types use default Ajax request.
			    $.ajax({
			        type: "POST",
			        url: ajaxurl,
			        data: formDataTemplateProcedure,
			        dataType: "json",
			        success: function(response) {

			            console.log('Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

			            if (response.hasOwnProperty('status')) {
			                if (response.status == true) {

			                	mk_modal.update({
									progress: Math.round((index * 100) / (templates.install_types.length - 1)) + '%',
								});

								templates.show_install_template_messages(templates.install_types[index], response.message)

			                    templates.install_template(++index, template_name, template_id);

			                } else {
			                	console.log(formDataTemplateProcedure.type);
			                    mk_modal({
					                title: mk_cp_textdomain.oops,
					                text: response.message,
					                type: 'error',
					                showCancelButton: false,
					                showConfirmButton: true,
									showLearnmoreButton: false,
					            });
					            requestsPending = false;
			                }
			            } else {
			                // Something goes wrong in server response
			                mk_modal({
				                title: mk_cp_textdomain.oops,
				                text: mk_cp_textdomain.something_wierd_happened_please_try_again,
				                type: 'error',
				                showCancelButton: false,
				                showConfirmButton: true,
								showLearnmoreButton: false,
				            });
				            requestsPending = false;
			            }
			        },
			        error: function(XMLHttpRequest, textStatus, errorThrown) {
			            templates.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
			            requestsPending = false;
			        }
			    });

			}
		},


		uninstall_template_trigger : function(evt) {
				evt.preventDefault();

		        var $btn = $(this);

		        mk_modal({
	                title: mk_cp_textdomain.important_notice,
		            text: mk_cp_textdomain.uninstalling_template_will_remove_all_your_contents_and_settings,
	                type: 'warning',
	                showCancelButton: true,
	                showConfirmButton: true,
	                confirmButtonText: mk_cp_textdomain.yes_uninstall + $btn.data('name'),
	                showCloseButton: false,
	                showLearnmoreButton: false,
	                onConfirm : function() {
                            templates.uninstall_template($btn.data('name'))
	                }
	            });

		},

		uninstall_template :  function(template_slug) {

			mk_modal({
	            title: mk_cp_textdomain.uninstalling_Template,
	            text: mk_cp_textdomain.please_wait_for_few_moments,
	            type: '',
	            showCancelButton: false,
	            showConfirmButton: false,
	            showCloseButton: false,
	            showLearnmoreButton: false,
	            showProgress: true,
	            indefiniteProgress: true,
	        });

	        requestsPending = 1;

		    $.post(ajaxurl, {

		        action: 'abb_uninstall_template',

		    }).done(function(response) {

		        console.log('Ajax Req : ', response);

		        $('#js__installed-template-wrap').hide();

		        // Reset Get Templtes
		        templates.reset_template_list();
		        templates.get_template_list();

		        // Alert
		        mk_modal({
	                title: mk_cp_textdomain.hooray,
		            text: mk_cp_textdomain.template_uninstalled,
	                type: 'success',
	                showCancelButton: false,
	                showConfirmButton: true,
	                showCloseButton: false,
	                showLearnmoreButton: false,
	            });
	            requestsPending = false;
		    }).fail(function(data) {
		        console.log('Failed msg : ', data);
		        requestsPending = false;
		    });
		},


		get_template_restore_init : function() {
			var $btn = $(this);

	        var $CredentialsModal = $('#request-filesystem-credentials-dialog');

	        if($CredentialsModal.length){
	            var $CredentialsData = templates.get_fs_credential_data(); // Get stored credentials data.
	            if( !$CredentialsData ){
	                templates.get_fs_credential_modal($CredentialsModal, $btn, 'restore');
	            }else{
	                templates.get_template_restore_modal($btn);
	            }
	        }else{
	            templates.get_template_restore_modal($btn);
	        }
		},

		get_template_restore_modal : function(btn) {

			$.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: { action: 'abb_is_restore_db' },
	            dataType: "json",

	            success: function(response) {

	                var created_date = response.data.latest_backup_file.created_date;
	                mk_modal({
		                title: mk_cp_textdomain.restore_settings,
	                    text: "<p>" + mk_cp_textdomain.you_are_trying_to_restore_your_theme_settings_to_this_date + "<strong class='mk-tooltip-restore--created-date'>" + created_date + "</strong>. " + mk_cp_textdomain.are_you_sure + "</p>",
		                type: 'warning',
		                showCancelButton: true,
		                showConfirmButton: true,
		                confirmButtonText: mk_cp_textdomain.restore,
		                showCloseButton: false,
		                showLearnmoreButton: false,
		                onConfirm : function() {
		                	mk_modal({
					            title: mk_cp_textdomain.restoring_database,
					            text: mk_cp_textdomain.please_wait_for_few_moments,
					            type: '',
					            showCancelButton: false,
					            showConfirmButton: false,
					            showCloseButton: false,
					            showLearnmoreButton: false,
					            showProgress: true,
					            indefiniteProgress: true,
					        });

                            var restoreTemplateParams = {
		                        action: 'abb_restore_latest_db'
		                    };
		                    var $storedCredentialsData = templates.get_fs_credential_data();
		                    // Inject stored FTP Credentials Data for each request.
		                    if($storedCredentialsData){
		                        restoreTemplateParams = $.extend(
		                            {},
		                            $storedCredentialsData,
		                            restoreTemplateParams
		                        );
		                    }
		                    $.ajax({
		                        type: "POST",
		                        url: ajaxurl,
		                        data: restoreTemplateParams,
		                        dataType: "json",
		                        success: function(response) {
		                            if( response.status ){
		                                mk_modal({
						                    title: response.message,
						                    type: 'success',
						                    showCancelButton: false,
						                    showConfirmButton: true,
						                    showCloseButton: false,
						                    showLearnmoreButton: false,
						                    showProgress: false,
						                    indefiniteProgress: true,
						                    confirmButtonText: mk_cp_textdomain.reload_page,
						                    onConfirm : function() {
						                    	location.reload();
						                    }
						                });
		                            }else{
		                            	mk_modal({
							                title: mk_cp_textdomain.oops,
							                text: response.message,
							                type: 'error',
							                showCancelButton: false,
							                showConfirmButton: true,
											showLearnmoreButton: false,
							            });
		                            }
		                        },
		                        error: function(XMLHttpRequest, textStatus, errorThrown) {
		                            console.log("Fail: ", XMLHttpRequest);
		                        }
		                    });
	                	}
		            });
	            },
	            error: function(XMLHttpRequest, textStatus, errorThrown) {
	                console.log("Fail: ", XMLHttpRequest);
	            }
	        });
		},


		create_restore_button : function() {
			var formDataIsRestoreDb = {
		        action: 'abb_is_restore_db'
		    };

		    var $storedCredentialsData = templates.get_fs_credential_data();

		    // Inject stored FTP Credentials Data for each request.
		    if($storedCredentialsData){
		        formDataIsRestoreDb = $.extend(
		            {},
		            $storedCredentialsData,
		            formDataIsRestoreDb
		        );
		    }

		    $.ajax({
		        type: "POST",
		        url: ajaxurl,
		        data: formDataIsRestoreDb,
		        dataType: "json",
		        success: function(response) {

		            var data           = response.data,
		            list_of_backups    = [],
		            latest_backup_file = null,
		            created_date       = "",
		            $btnRestore        = "";

		            if(data.hasOwnProperty('list_of_backups')){

		                list_of_backups = data.list_of_backups;

		                if (list_of_backups == null) {
		                    console.log("List Of Backups is NULL!");
		                } else if (list_of_backups.length == 0) {
		                    console.log("List Of Backups is EMPTY!");
		                } else {
		                    latest_backup_file = data.latest_backup_file;
		                    created_date = latest_backup_file.created_date;
		                    $('#js__backup-date').text(created_date);
		                    $('#js__restore-template-wrap').addClass('is-active');
		                    console.log("Restore Buttons Created Successfully!");
		                }

		            }else{
		                console.log("No backup files found!");
		            }
		        },
		        error: function(XMLHttpRequest, textStatus, errorThrown) {
		            console.log("Fail: ", XMLHttpRequest);
		        }
		    });
		},


		show_install_template_messages : function (type, message) {
			var backup = 	$('#js__cp-template-install-steps-backup .result-message');
		    var upload = 	$('#js__cp-template-install-steps-upload .result-message');
		    var plugin = 	$('#js__cp-template-install-steps-plugin .result-message');
		    var install = 	$('#js__cp-template-install-steps-install .result-message');

		    switch (type) {
		        case 'backup_db':
		            backup.text(message);
		            break;
		        case 'backup_media_records':
		            backup.text(message);
		            break;
		        case 'reset_db':
		            backup.text(message);
		            $('#js__cp-template-install-steps-backup').addClass('mka-modal-step--done');
		            break;
		        case 'upload':
		            upload.text(message);
		            break;
		        case 'unzip':
		            upload.text(message);
		            break;
		        case 'validate':
		            upload.text(message);
		            $('#js__cp-template-install-steps-upload').addClass('mka-modal-step--done');
		            break;
		        case 'plugin':
		            plugin.html(message);
		            $('#js__cp-template-install-steps-plugin').addClass('mka-modal-step--done');
		            break;
		        case 'theme_content':
		            install.text(message);
		            break;
		        case 'menu_locations':
		            install.text(message);
		            break;
		        case 'setup_pages':
		            install.text(message);
		            break;
		        case 'theme_options':
		            install.text(message);
		            break;
		        case 'theme_widget':
		            install.text(message);
		            break;
		        case 'restore_media_records':
		            install.text(message);
		            break;
		        case 'finalize':
		            install.text(message);
		            $('#js__cp-template-install-steps-install').addClass('mka-modal-step--done');
		            break;
		    }
		},

		infinite_load_templates : function() {
			var hT = $('.abb-template-page-load-more');
			if (hT.length) {
				var wH = $(window).height(),
	            wS = $(window).scrollTop();

		        if (wS > (hT.offset().top - wH) && templates.server_response_status === false) {
		            templates.get_template_list();
		        }
			}
		},

		throttle : function(func, wait) {
		    var context, args, timeout, throttling, more, result;
		    var whenDone = _.debounce(function(){ more = throttling = false; }, wait);
		    return function() {
		      context = this; args = arguments;
		      var later = function() {
		        timeout = null;
		        if (more) func.apply(context, args);
		        whenDone();
		      };
		      if (!timeout) timeout = setTimeout(later, wait);
		      if (throttling) {
		        more = true;
		      } else {
		        result = func.apply(context, args);
		      }
		      whenDone();
		      throttling = true;
		      return result;
		    };
		},

		get_system_resource : function( $btn ) {

		    $.ajax({
		        type: "POST",
		        url: ajaxurl,
		        data: { action: 'abb_check_system_resource' },
		        dataType: "json",
		        timeout: 60000,

		        success: function(response) {

		            if (response.hasOwnProperty('status') === true) {

		                console.log( 'Check system resource', response );

		                if (response.status === true) {
		                	templates.import_media = true;
		                    templates.start_importing_template( $btn );

		                } else {
		                	mk_modal({
				                title: mk_cp_textdomain.insufficient_system_resource,
				                text: templates.language(mk_cp_textdomain.insufficient_system_resource_notes, ['<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
				                type: 'warning',
				                showCancelButton: true,
				                showConfirmButton: true,
				                confirmButtonText: mk_cp_textdomain.continue_without_media,
				                showCloseButton: false,
				                showLearnmoreButton: false,
				                onConfirm : function() {
			                            templates.import_media = false;
			                            templates.start_importing_template( $btn );
				                }
				            });

		                }
		            } else {
		                mk_modal({
			                title: mk_cp_textdomain.oops,
			                text: mk_cp_textdomain.something_wierd_happened_please_try_again,
			                type: 'error',
			                showCancelButton: false,
			                showConfirmButton: true,
							showLearnmoreButton: false,
			            });
		            }
		        },
		        error: function(XMLHttpRequest, textStatus, errorThrown) {
		            templates.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
		        }
		    });
		},

		store_fs_credential_data : function($data){

		    if(!$data || !Array.isArray($data)){
		        return false;
		    }

		    var $storedCreds = [];

		    for (var i = 0; i < $data.length; i++) {
		        if( $data[i].name && $data[i].name != 'action' ){
		            $storedCreds.push({name: $data[i].name, value: Base64.encode($data[i].value)});
		        }
		    }

		    $storedCreds = JSON.stringify($storedCreds);

		    if (typeof(Storage) !== 'undefined') {
		        window.sessionStorage.setItem('MkFTPCredentialsData', $storedCreds);
		        return $storedCreds;
		    }else if(navigator.cookieEnabled){
		        setCookie('MkFTPCredentialsData', $storedCreds);
		        return $storedCreds;
		    }

		    return false;
		},

		get_fs_credential_data : function(){

		    if (typeof(Storage) !== 'undefined') {
		        var $data = window.sessionStorage.getItem('MkFTPCredentialsData');
		    }else if(navigator.cookieEnabled){
		        var $data = getCookie('MkFTPCredentialsData');
		    }

		    if (typeof $data === 'undefined' || !$data) {
		        return false;
		    }

		    var $storedCreds = {};

		    $data = JSON.parse($data);

		    for (var i = 0; i < $data.length; i++) {
		        if( $data[i].name && $data[i].name != 'action' ){
		            $storedCreds[$data[i].name] = Base64.decode($data[i].value);
		        }
		    }
		    return $storedCreds;
		},

		get_fs_credential_modal : function($dialog, $btn, $action) {

			var custom_html = '';

	        var form_id = 'mk-install-template-credential';

	        var $dialog_content = $dialog.find('.request-filesystem-credentials-dialog-content').clone();

	        $dialog_content.find('#request-filesystem-credentials-title').hide();
	        $dialog_content.find('input#password, input#username, input#hostname').addClass('mka-textfield');
	        $dialog_content.find('#upgrade').hide();
	        $dialog_content.find('.cancel-button').hide();
	        $dialog_content.find('form').attr('id', form_id).append('<input type="hidden" name="action" value="abb_check_ftp_credentials">');

	        custom_html += '<div class="mka-modal--warning js__ftp-creds-container">';
			custom_html += '<span class="mka-modal-icon"></span>';
		    custom_html += '<h3 class="mka-modal-title">'+ $dialog_content.find('#request-filesystem-credentials-title').text() +'</h3>';
		    custom_html += '<div class="mka-modal-desc">'+$dialog_content.html()+'</div>';
		    custom_html += '<span class="mka-modal-message-box"></span>';
		    custom_html += '<div class="mka-modal-footer">';
			    custom_html += '<div class="mka-wrap mka-modal-ok-btn-wrap"><input type="button" id="js__ftp-creds-submit-btn" class="mka-button mka-button--blue mka-button--small mka-modal-ok-btn" value="'+$dialog_content.find('#upgrade').val()+'"></div>';
			    custom_html += '<div class="mka-wrap mka-modal-cancel-btn-wrap"><input type="button" class="mka-button mka-button--gray mka-button--small mka-modal-cancel-btn" value="'+$dialog_content.find('.cancel-button').text()+'"></div>';
		    custom_html += '</div>';
	    	custom_html += '</div>';

	        var fs_credential_modal = mk_modal({
	            html:$(custom_html),
	            showCloseButton: true,
        		showConfirmButton: true,
        		closeOnOutsideClick: false,
        		closeOnConfirm: false,
        		onConfirm: function(){
		            var formData = $('#' + form_id).serializeArray();
		            $.ajax(ajaxurl, {
		                data: formData,
		                method: 'POST',
		                beforeSend: function(jqXHR, settings) {
		                	$('.request-credentials-form-modal .mka-modal-message-box').hide();
		                },
		                success: function(response, textStatus, jqXHR) {
		                    if(response.status){
		                        fs_credential_modal.close();
		                        templates.store_fs_credential_data(formData);
		                        if( 'install' == $action ){
		                            templates.get_template_install_modal($btn);
		                        }else if( 'restore' == $action ){
		                            templates.get_template_restore_modal($btn);
		                        }
		                    }else{
		                        if(response.message){
		                        	$('.js__ftp-creds-container .mka-modal-message-box').show().text(response.message);
		                        }else{
		                        	$('.js__ftp-creds-container .mka-modal-message-box').show().text(mk_cp_textdomain.incorrect_credentials);
		                        }
		                    }
		                },
		                error: function(jqXHR, textStatus, errorThrown) {
		                    //swal.showInputError('error');
		                }
		            });
        		}
	        });

	    },

		request_error_handling : function(XMLHttpRequest, textStatus, errorThrown) {

     		 console.log(XMLHttpRequest);

     		 var custom_html = '';

				mk_modal({
	        		html: templates.progress_bar_html,
	        		showProgress: true,
	        		progress: '0%',
	        		showCloseButton: false,
	        		closeOnOutsideClick: false,
				});

 		  	if (XMLHttpRequest.readyState == 4) {
		        // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
		        if (XMLHttpRequest.responseText != '') {

		        	custom_html += '<div class="mka-modal--error">';
					custom_html += '<span class="mka-modal-icon"></span>';
				    custom_html += '<h3 class="mka-modal-title">'+ mk_cp_textdomain.whoops +'</h3>';
				    custom_html += '<div class="mka-modal-desc">'+templates.language(mk_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>'])+'</div>';
				    custom_html += '<textarea readonly="readonly" onclick="this.focus();this.select()" class="mka-modal-textarea">'+XMLHttpRequest.responseText+'</textarea>';
			    	custom_html += '</div>';

			    	mk_modal({
		        		html: $(custom_html),
		        		showCloseButton: true,
		        		showConfirmButton: true,
		        		closeOnOutsideClick: false,
					});

		        } else {
		        	mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: templates.language(mk_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
						showLearnmoreButton: false,
		            });
		        }
		    } else if (XMLHttpRequest.readyState == 0) {
		        // Network error (i.e. connection refused, access denied due to CORS, etc.)
		        mk_modal({
	                title: mk_cp_textdomain.oops,
	                text: mk_cp_textdomain.error_in_network_please_check_your_connection_and_try_again,
	                type: 'error',
	                showCancelButton: false,
	                showConfirmButton: true,
					showLearnmoreButton: false,
	            });
		    } else {
		        mk_modal({
	                title: mk_cp_textdomain.oops,
	                text: mk_cp_textdomain.something_wierd_happened_please_try_again,
	                type: 'error',
	                showCancelButton: false,
	                showConfirmButton: true,
					showLearnmoreButton: false,
	            });
		    }
     	},

     	language : function(string, params) {
 		 	if (typeof string == 'undefined' || string == '') {
		        return;
		    }
		    var array_len = params.length;
		    if (array_len < 1) {
		        return string;
		    }
		    var indicator_len = (string.match(/{param}/g) || []).length;

		    if (array_len == indicator_len) {
		        $.each(params, function(key, val) {
		            string = string.replace('{param}', val);
		        });
		        return string;
		    }

		    // Array len and indicator lengh is not same;
		    console.log('Array len and indicator lengh is not same, Contact support with ID : (3-6H1T4I) .');
		    return string;
     	}

     };
     $(window).on('control_panel_pane_loaded', templates.init);
     /***************/



	/* Control Panel > Updates ( Theme )
	*******************************************************/

	var update_releases = {
		init : function () {
			if($('#mk-cp-updates-downgrades').length == 0) return;
			console.log('updates.theme');
			$(document).on('click', '.js__cp_change_theme_version', update_releases.change_theme_version_init);
			$(document).on('click', '.release-download', update_releases.release_download);
		},

		release_download : function( e ) {
				e.preventDefault();
				e.stopPropagation();
				var btn = $(this);
				var status = btn.attr('status');
				if(typeof status === 'undefined')
				status = 'active';
				if( status === 'active' ) {
					var release_id = $(this).data("release-id");
					var get_release_download_link_nonce = $(this).data("nonce");
					btn.attr("status", 'deactive');
					setTimeout(function () {
						btn.attr("status", 'active');
					}, 9000);
					jQuery.ajax({
						url: ajaxurl,
						type: "POST",
						data: {
							security: get_release_download_link_nonce,
							release_id: release_id,
							action: "mk_get_theme_release_package_url",
						},
						success: function (res) {
							var data = jQuery.parseJSON(res);
							console.log(data);
							if (data.success) {
								top.location.href = data.download_link;
							}
						},
						error: function (res) {
							console.log(res);
							alert("An error occurred.");
						}
					});
				}
		},
		change_theme_version_init : function( e ){
			e.preventDefault();

			var release_id = $(this).data("release-id");
			var release_version = $(this).data("release-version");
			var get_release_download_link_nonce = $(this).data("nonce");
			console.log(release_id);
			console.log(get_release_download_link_nonce);
			mk_modal({
				title: mk_cp_textdomain.please_note,
				text: mk_cp_textdomain.any_customisation_you_have_made_to_theme_files_will_be_lost,
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				confirmButtonText: mk_cp_textdomain.agree,
				cancelButtonText: mk_cp_textdomain.discard.toUpperCase(),
				learnmoreTarget: '_blank',
				learnmoreLabel: 'Read More',
				learnmoreButton: 'https://themes.artbees.net/docs/updating-theme-child-theme/',
				showCloseButton: true,
				showLearnmoreButton: true,
				onConfirm : function() {
					console.log('agreed clicked');
					jQuery.ajax({
						url: ajaxurl,
						type: "POST",
						data: {
							security: get_release_download_link_nonce,
							release_id: release_id,
							release_version: release_version,
							action: "mk_modify_auto_update",
						},
						success: function (res) {
							var data = jQuery.parseJSON(res);
							console.log(data);
							if(data.success) {
							top.location.href = data.download_link;
							}
						},
						error: function (res) {
							console.log(res);
							alert("An error occurred.");
						}
					});
				}
			});
		}
	};
	$(window).on('control_panel_pane_loaded', update_releases.init);

     /* Control Panel > Image sizes
     *******************************************************/

     var image_sizes = {

     	init : function(){

     		$('.js__cp-clist-add-item').on('click', image_sizes.add );
			$('.js__cp-clist-edit-item').on('click', image_sizes.edit );
			$('.js__cp-clist-remove-item').on('click', image_sizes.remove );
			$('.js__cp-clist-apply-item').on('click', image_sizes.apply );
			$('.js__cp-clist-cancel-item').on('click', image_sizes.cancel );

     	},

		remove : function remove ( e ) {
			e.preventDefault();
			var $this = $(this);
			mk_modal({
                title: mk_cp_textdomain.remove_image_size,
                text: mk_cp_textdomain.are_you_sure_remove_image_size,
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                showCloseButton: false,
                showLearnmoreButton: false,
                onConfirm : function() {

					var $list = $this.closest('.mka-wrap').find('.mka-clist-list');
					// Ripplle Effect
					var $ripple = $this.find('.mka-clist-remove-ripple');
					TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
					TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
					TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });

					// Functionality
					var $list_item = $this.closest('.mka-clist-item');
					TweenLite.to( $list_item, 0.1, { css: { height: 0 }, ease: Power4.easeOut, delay: 0.2 });

					$list_item.remove();

					if ( ! $list.children().length ) {
						$list.addClass('mka-clist-list--empty');
					}

					$(window).trigger('control_panel_save_image_sizes');

                }
            });
		},

		add : function add (e) {

			e.preventDefault();
			var $this = $(this);
			var $add_button_text = $this.find('.mka-clist-add-text');
			var $list_item = $this.closest('.mka-wrap').find('.mka-clist-item-clone').clone(true, true).removeClass('mka-clist-item-clone').addClass('mka-clist-item--edit mka-clist-item--add');
			var $list_item_inner = $list_item.find('.mka-clist-item-inner');
			var $add_box = $this.closest('.mka-wrap').find('.mka-clist-addbox-clone').clone(true, true).removeClass('mka-clist-addbox-clone');
			var $add_box_apply_btn = $add_box.find('.mka-clist-item-apply-btn');
			var $add_box_cancel_btn = $add_box.find('.mka-clist-item-cancel-btn');
			var $list = $this.closest('.mka-wrap').find('.mka-clist-list');
			$this.css({
				top: $this.position().top,
				bottom: 'auto',
			});

			$list_item_inner.hide().css('opacity', 0);
			$list_item.append( $add_box );
			$list_item.appendTo( $list.removeClass('mka-clist-list--empty') ).css('height', '0');
			$add_box.css('opacity', '0').css('display', 'inline-block');

			TweenLite.to( $add_button_text, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $this, 0.2, { css: { scale: 0.01, display: 'none' }, ease: Power1.easeOut, delay: 0 });

			TweenLite.to( $add_box, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
			TweenLite.to( $list_item, 0.1, { css: { height: 90 }, ease: Power4.easeOut, delay: 0.1 });
			TweenLite.to( $add_box, 0.1, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
			TweenLite.from( $add_box_apply_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
			TweenLite.from( $add_box_cancel_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });

			TweenLite.to( $list_item, 0, { css: { overflow: 'visible' }, ease: Power1.easeOut, delay: 0.2 });
		},

		edit : function(e) {

			e.preventDefault();
			var $this = $(this);
			// Ripple Effect
			var $ripple = $this.find('.mka-clist-edit-ripple');
			TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });

			// Edit Functionality
			var $list_item = $this.closest('.mka-clist-item');
			var $list_item_inner = $list_item.find('.mka-clist-item-inner');
			var $add_box = $this.closest('.mka-wrap').find('.mka-clist-addbox-clone').clone(true, true).removeClass('mka-clist-addbox-clone');
			var $add_box_apply_btn = $add_box.find('.mka-clist-item-apply-btn');
			var $add_box_cancel_btn = $add_box.find('.mka-clist-item-cancel-btn');

			var $size_name = $list_item.find('[name=size_n]').val();
			var $size_width = $list_item.find('[name=size_w]').val();
			var $size_height = $list_item.find('[name=size_h]').val();
			var $size_crop = $list_item.find('[name=size_c]').val();
				$size_crop = ($size_crop == 'on') ? true : false;

			$add_box.find('[name=size_n]').val($size_name);
			$add_box.find('[name=size_w]').val($size_width);
			$add_box.find('[name=size_h]').val($size_height);
			$add_box.find('[name=size_c]').prop("checked", $size_crop);


			$list_item.css('height', $list_item.outerHeight() + 'px' ).addClass('mka-clist-item--edit');
			$list_item_inner.hide().css('opacity', 0);
			$add_box.appendTo( $list_item ).css('opacity', '0').css('display', 'inline-block');

			TweenLite.to( $add_box, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
			TweenLite.to( $list_item, 0.1, { css: { height: 90 }, ease: Power4.easeOut, delay: 0.1 });
			TweenLite.to( $add_box, 0.1, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
			TweenLite.from( $add_box_apply_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.3	 });
			TweenLite.from( $add_box_cancel_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.3	 });

			TweenLite.to( $list_item, 0, { css: { overflow: 'visible' }, ease: Power1.easeOut, delay: 0.2 });
		},


		apply : function(e) {
			e.preventDefault();

			var $this = $(this);
			var $add_button = $this.closest('.mka-wrap').find('.js__cp-clist-add-item');
			var $add_button_text = $add_button.find('.mka-clist-add-text');
			var $list_item = $this.closest('.mka-clist-item');
			var $add_box = $this.closest('.mka-clist-addbox');
			var $list_item_inner = $list_item.find('.mka-clist-item-inner');

			var $size_name = $add_box.find('[name=size_n]').val();
			var $size_width = $add_box.find('[name=size_w]').val();
			var $size_height = $add_box.find('[name=size_h]').val();
			var $size_crop = $add_box.find('[name=size_c]:checked').val();
				$size_crop = ($size_crop == 'on') ? 'on' : 'off';


			$list_item.find('.js__size-name').text($size_name);
			$list_item.find('.js__size-dimension').text($size_width + 'px ' + $size_height + 'px');

			var crop_text = ($size_crop == 'on') ? '&#x2713;' : '&#10005;';
			$list_item.find('.js__size-crop').html(crop_text);

			$list_item.find('[name=size_n]').val($size_name);
			$list_item.find('[name=size_w]').val($size_width);
			$list_item.find('[name=size_h]').val($size_height);
			$list_item.find('[name=size_c]').val($size_crop);

			TweenLite.to( $list_item, 0, { css: { overflow: 'hidden' }, ease: Power1.easeOut, delay: 0 });

			TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list_item, 0.1, { css: { height: 48 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list_item_inner, 0.1, { css: { display: 'inline-block', opacity: 1 }, ease: Power1.easeOut, delay: 0.1 });

			$add_button.css({
				top: 'auto',
				bottom: '',
			});

			TweenLite.to( $add_button_text, 0, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
			TweenLite.to( $add_button, 0.2, { css: { scale: 1, display: 'block' }, ease: Power1.easeOut, delay: 0.2 });

			$(window).trigger('control_panel_save_image_sizes');

			$add_box.remove();
			$list_item.removeClass('mka-clist-item--edit mka-clist-item--add');
		},

		cancel : function(e) {
			e.preventDefault();
			var $this = $(this);
			var $add_button = $this.closest('.mka-wrap').find('.js__cp-clist-add-item');
			var $add_button_text = $add_button.find('.mka-clist-add-text');
			var $list_item = $this.closest('.mka-clist-item');
			var $add_box = $this.closest('.mka-clist-addbox');
			var $list_item_inner = $list_item.find('.mka-clist-item-inner');
			var $list = $this.closest('.mka-wrap').find('.mka-clist-list');

			TweenLite.to( $list_item, 0, { css: { overflow: 'hidden' }, ease: Power1.easeOut, delay: 0 });

			$add_button.css({
				top: 'auto',
				bottom: '',
			});
			TweenLite.to( $add_button_text, 0, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
			TweenLite.to( $add_button, 0.2, { css: { scale: 1, display: 'block' }, ease: Power1.easeOut, delay: 0.2 });

			// If cancel is in Add New
			if ( $this.closest('.mka-clist-item--add').length ) {

				TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $list_item, 0.1, { css: { height: 0, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
				setTimeout( function() {
					$add_box.remove();
					$list_item.remove();

					if ( !$list.children().length ) {
						$list.addClass('mka-clist-list--empty');
					}
				}, 200);

			} else {

				TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $list_item, 0.1, { css: { height: 48 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $list_item_inner, 0.1, { css: { display: 'inline-block', opacity: 1 }, ease: Power1.easeOut, delay: 0.1 });
				setTimeout( function() {
					$add_box.remove();
					$list_item.removeClass('mka-clist-item--edit');
				}, 500);
			}
		},


		save : function(e){

			var $container = $('.js__mka-clist-list'),
				serialised = [];


			$container.find('.js__cp-clist-item').each(function(){
				serialised.push( $(this).find('input').serialize() );
			});

			var data = {
				action : 'abb_save_image_sizes',
				options : serialised,
				security : $('#security').val()
			};
			requestsPending = 1;
			 $.post(ajaxurl, data, function(response) {
	            console.log(response);

	            if(response != 1) {
	            	mk_modal({
		                title: mk_cp_textdomain.oops,
		                text: mk_cp_textdomain.image_sizes_could_not_be_stored,
		                type: 'error',
		                showCancelButton: false,
		                showConfirmButton: true,
		                showCloseButton: false,
		                showLearnmoreButton: false,
	            	});
	            }

	            requestsPending = false;

	        });

		}
	};


	$(window).on('control_panel_save_image_sizes', image_sizes.save);
	$(window).on('control_panel_pane_loaded', image_sizes.init);

	/***************/




    /* Control Panel > System status page get report functionality
     *******************************************************/
     function mk_get_system_report() {
	    $('.mka-button--get-system-report').click(function() {
	        var report = '';
	        $('#mk-cp-system-status thead, #mk-cp-system-status tbody').each(function() {
	            if ($(this).is('thead')) {
	                var label = $(this).find('th:eq(0)').data('export-label') || $(this).text();
	                report = report + "\n### " + $.trim(label) + " ###\n\n";
	            } else {
	                $('tr', $(this)).each(function() {
	                    var label = $(this).find('td:eq(0)').data('export-label') || $(this).find('td:eq(0)').text();
	                    var the_name = $.trim(label).replace(/(<([^>]+)>)/ig, ''); // Remove HTML
	                    var the_value = $.trim($(this).find('td:eq(2)').text());
	                    var value_array = the_value.split(', ');
	                    if (value_array.length > 1) {
	                        var output = '';
	                        var temp_line = '';
	                        $.each(value_array, function(key, line) {
	                            temp_line = temp_line + line + '\n';
	                        });
	                        the_value = temp_line;
	                    }
	                    report = report + '' + the_name + ': ' + the_value + "\n";
	                });
	            }
	        });
	        try {
	            $("#mka-textarea--get-system-report").slideDown();
	            $("#mka-textarea--get-system-report textarea").val(report).focus().select();
	            return false;
	        } catch (e) {
	            console.log(e);
	        }
	        return false;
	    });
	}
    /***************/



    /* Control Panel > dismiss update notice message
     *******************************************************/
    $('.cp-update-notice .close-button').on("click", function(e) {
        e.preventDefault();
        var $this = $(this),
            $new_version = $this.attr('data-new-version');
        console.log($new_version);
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'mk_dismiss_update_notice',
                'version': $new_version,
            },
            success: function(data) {
                $this.parent().fadeOut();
            }
        });
    });
    /***************/



    /* Control Panel > Create base64 character set
     *******************************************************/
    var Base64 = {
	    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	    encode: function(e) {
	        var t = "";
	        var n, r, i, s, o, u, a;
	        var f = 0;
	        e = Base64._utf8_encode(e);
	        while (f < e.length) {
	            n = e.charCodeAt(f++);
	            r = e.charCodeAt(f++);
	            i = e.charCodeAt(f++);
	            s = n >> 2;
	            o = (n & 3) << 4 | r >> 4;
	            u = (r & 15) << 2 | i >> 6;
	            a = i & 63;
	            if (isNaN(r)) {
	                u = a = 64
	            } else if (isNaN(i)) {
	                a = 64
	            }
	            t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
	        }
	        return t
	    },
	    decode: function(e) {
	        var t = "";
	        var n, r, i;
	        var s, o, u, a;
	        var f = 0;
	        e = e.replace(/[^A-Za-z0-9+/=]/g, "");
	        while (f < e.length) {
	            s = this._keyStr.indexOf(e.charAt(f++));
	            o = this._keyStr.indexOf(e.charAt(f++));
	            u = this._keyStr.indexOf(e.charAt(f++));
	            a = this._keyStr.indexOf(e.charAt(f++));
	            n = s << 2 | o >> 4;
	            r = (o & 15) << 4 | u >> 2;
	            i = (u & 3) << 6 | a;
	            t = t + String.fromCharCode(n);
	            if (u != 64) {
	                t = t + String.fromCharCode(r)
	            }
	            if (a != 64) {
	                t = t + String.fromCharCode(i)
	            }
	        }
	        t = Base64._utf8_decode(t);
	        return t
	    },
	    _utf8_encode: function(e) {
	        e = e.replace(/rn/g, "n");
	        var t = "";
	        for (var n = 0; n < e.length; n++) {
	            var r = e.charCodeAt(n);
	            if (r < 128) {
	                t += String.fromCharCode(r)
	            } else if (r > 127 && r < 2048) {
	                t += String.fromCharCode(r >> 6 | 192);
	                t += String.fromCharCode(r & 63 | 128)
	            } else {
	                t += String.fromCharCode(r >> 12 | 224);
	                t += String.fromCharCode(r >> 6 & 63 | 128);
	                t += String.fromCharCode(r & 63 | 128)
	            }
	        }
	        return t
	    },
	    _utf8_decode: function(e) {
	        var t = "";
	        var n = 0;
	        var r = 0;
	        var c1 = 0;
	        var c2 = 0;
	        while (n < e.length) {
	            r = e.charCodeAt(n);
	            if (r < 128) {
	                t += String.fromCharCode(r);
	                n++
	            } else if (r > 191 && r < 224) {
	                c2 = e.charCodeAt(n + 1);
	                t += String.fromCharCode((r & 31) << 6 | c2 & 63);
	                n += 2
	            } else {
	                c2 = e.charCodeAt(n + 1);
	                c3 = e.charCodeAt(n + 2);
	                t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
	                n += 3
	            }
	        }
	        return t
	    }
	};

	/**
	 * Export & Import API.
	 *
	 * @since 6.0.3
	 * @type {Object}
	 */
	var exportImport = {
		steps: [],
		modal: '',

		init: function() {
			$('.mka-cp-export-form').on('submit', exportImport.export)
			$('.mka-cp-import-btn').on('click', exportImport.import)
			$('.mka-cp-import-upload-btn').on('click', exportImport.upload)
		},

		export: function(event) {
			event.preventDefault()
			exportImport.steps = []
			exportImport.modal = ''
			exportImport.cancel = ''
			var options = $(this).serializeArray()

			// Convert options to a flat array.
			if (!exportImport._mapOptions(options)) {
				return
			}

			// Open the modal.
			exportImport.modal = mk_modal({
				type: false,
				title: mk_cp_textdomain.exporting + ' <span class="cp-export-step">' + exportImport.steps[1] + '</span>...',
				text: mk_cp_textdomain.export_waiting,
				showCancelButton: true,
				showConfirmButton: false,
				showCloseButton: false,
				showLearnmoreButton: false,
				showProgress: true,
				indefiniteProgress: true,
				cancelButtonText: mk_cp_textdomain.discard,
				closeOnConfirm: false,
				closeOnOutsideClick: false,
				onCancel: function() {
					exportImport.steps = []
					exportImport.cancel = true
					exportImport.send('Export', 'Discard')
					exportImport.modal.close()
				}
			});

			// Init the first step.
			exportImport.send('Export', _.first(exportImport.steps))
		},

		import: function(event) {
			event.preventDefault()
			exportImport.steps = []
			exportImport.modal = ''
			exportImport.cancel = ''
			var attachment_id = $('.mka-textfield', '.mka-upload').data('id')

			// Return false if no package is selected.
			if ('undefined' === typeof attachment_id) {
				return false;
			}

			exportImport.attachment_id = attachment_id;

			exportImport.modal = mk_modal({
				type: false,
				title: 'Import',
				text: mk_cp_textdomain.import_select_options + '\
				<form class="mka-cp-import-form">\
					<label>\
						<input type="checkbox" name="check" value="Content" checked>' +
						mk_cp_textdomain.site_content + '\
					</label>\
					<label>\
						<input type="checkbox" name="check" value="Widgets" checked>' +
						mk_cp_textdomain.widgets + '\
					</label>\
					<label>\
						<input type="checkbox" name="check" value="Theme Options" checked>' +
						mk_cp_textdomain.theme_options + '\
					</label>\
					<label>\
						<input type="checkbox" name="check" value="Customizer" checked>' +
						mk_cp_textdomain.customizer + '\
					</label>\
				</form>',
				showCancelButton: false,
				showConfirmButton: true,
				showCloseButton: true,
				showLearnmoreButton: false,
				showProgress: false,
				closeOnConfirm: false,
				confirmButtonText: mk_cp_textdomain.import,
				onConfirm: function() {
					var options = $('.mka-cp-import-form').serializeArray()

					// Convert options to a flat array.
					if (!exportImport._mapOptions(options)) {
						return
					}

					mk_modal({
						type: false,
						title: mk_cp_textdomain.importing + ' <span class="cp-export-step">' + exportImport.steps[1] + '</span>...',
						text: mk_cp_textdomain.import_waiting,
						showCancelButton: true,
						showConfirmButton: false,
						showCloseButton: false,
						showLearnmoreButton: false,
						showProgress: true,
						indefiniteProgress: true,
						cancelButtonText: mk_cp_textdomain.discard,
						closeOnOutsideClick: false,
						closeOnConfirm: false,
						onCancel: function() {
							exportImport.steps = []
							exportImport.cancel = true
							exportImport.send('Import', 'Discard')
							exportImport.modal.close()
						}
					})

					// Init the first step.
					exportImport.send('Import', _.first(exportImport.steps))
				}
			})
		},

		send: function(type, step) {
			wp.ajax.send( 'mk_cp_export_import', {
				data: {
					nonce: mk_control_panel.nonce,
					type: type,
					step: step,
					attachment_id: exportImport.attachment_id,
				},
				success: function(response) {
					exportImport.steps = _.without(exportImport.steps, response.step)
					var firstStep = _.first(exportImport.steps)

					// Open the download modal.
					if (!exportImport.steps.length) {
						if (true === exportImport.cancel) {
							return;
						}
						var confirmButtonText = ('Export' === type) ? mk_cp_textdomain.download : mk_cp_textdomain.close
						mk_modal({
							type: 'success',
							title: type + ' ' + mk_cp_textdomain.done,
							text: type + ' ' + mk_cp_textdomain.successfully_finished,
							showCancelButton: false,
							showConfirmButton: true,
							showCloseButton: false,
							showLearnmoreButton: false,
							showProgress: false,
							closeOnConfirm: false,
							confirmButtonText: confirmButtonText,
							onConfirm: function() {
								if ('Export' === type ) {
									window.location.href = response.download_url;
								}
								exportImport.modal.close();
							}
						});
						return;
					}

					// Update title in modal except Start one.
					if (response.step != 'Start') {
						$('.cp-export-step').text(response.step)
					}

					// Init the next step.
					exportImport.send(
						type,
						firstStep
					)
				},
				error: function(response) {

					console.log( response );

					mk_modal({
						type: 'error',
						title: mk_cp_textdomain.error,
						text: response + ' ' + mk_cp_textdomain.issue_persists,
						showCancelButton: false,
						showConfirmButton: true,
						showCloseButton: false,
						showLearnmoreButton: false,
						showProgress: false,
						closeOnConfirm: false,
						confirmButtonText: mk_cp_textdomain.try_again,
						onConfirm: function() {
							window.location.reload();
							exportImport.modal.close();
						}
					});
				}
			})
		},

		upload: function(event) {
			event.preventDefault()
			var frame
			var $input = $(event.target).parents('.mka-upload').find('input')

			if ( frame ) {
				frame.open()
				return
			}

			frame = wp.media({
				title: mk_cp_textdomain.select_zip_file,
				button: {
					text: mk_cp_textdomain.select
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on( 'select', function() {
				var attachment = frame.state().get('selection').first().toJSON()

				$input.attr('data-id', attachment.id)
				$input.val(attachment.url)
			});

			frame.open()
		},

		_mapOptions: function(options) {
			// Convert options to a flat array.
			_.map(options, function(option){
				return exportImport.steps.push(option.value)
			})

			// Return false if no option is selected.
			if (!exportImport.steps.length) {
				return false;
			}

			exportImport.steps.unshift('Start');
			exportImport.steps.push('End')

			return true
		}
	}

	// Initialize Export & Import after pane load.
	$(window).on('control_panel_pane_loaded', exportImport.init)

})(jQuery);
