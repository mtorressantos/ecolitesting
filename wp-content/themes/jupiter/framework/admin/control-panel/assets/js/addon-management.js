var mk_addon_count_per_request = 5,
    mk_disable_until_server_respone = false,
    mk_addon_category_name = null,
    mk_addon_name = null;
(function($) {
    if ($('.mk-addon-load-more').length == 0) {
        return false;
    }
    // Get List of Categories and Addons
    mkGetAddonsCategories();
    mkGetAddonsList(mk_addon_count_per_request);
    mkGetInstalledAddonsList();

    // Load More
    $(document).on('click', '.mk-addon-load-more', function() {
        mkGetAddonsList(mk_addon_count_per_request);
    });

    // Activate Addon
    $(document).on('click', '.abb_addon_activate', function() {
        var $btn = $(this);
        swal({
            title: mk_cp_textdomain.installing_notice,
            text: mk_language(mk_cp_textdomain.are_you_sure_you_want_to_install, [$btn.data('name')]),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#32d087",
            confirmButtonText: mk_cp_textdomain.conitune,
            closeOnConfirm: false,
            html: true,
        }, function() {
            mkActivateAddon($btn.data('slug'));
        });
    });

    // Deactivate Addon
    $(document).on('click', '.abb_addon_deactivate', function() {
        event.preventDefault();
        var $btn = $(this);
        swal({
            title: mk_language(mk_cp_textdomain.important_notice, []),
            text: mk_language(mk_cp_textdomain.are_you_sure_you_want_to_remove_addon, [$btn.data('name')]),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#32d087",
            confirmButtonText: mk_language(mk_cp_textdomain.conitune, []),
            closeOnConfirm: false,
            html: true
        }, function() {
            mkDeactivateAddon($btn.data('slug'), $btn.data('name'));
        });
    });

    // Update Addon
    $(document).on('click', '.abb_addon_update', function() {
        event.preventDefault();
        var $btn = $(this);
        mkUpdateAddon($btn.data('slug'));
    });

    // Get list of addons based on categories
    $(document).on('change', '.mk-addon-category-select', function() {
        var $select = $(this);
        mkResetFromNumber();
        mkResetSection('.mk-api-addons-list');
        mk_addon_category_name = $select.val();
        mkGetAddonsList(mk_addon_count_per_request);
    });

    // Get specific addon
    $(document).on('keypress', '.mk-addon-search-txt', function(e) {
        if (e.which == 13) {
            var txt = $(this);
            mkResetSection('.mk-api-addons-list');
            mkResetFromNumber();
            mk_addon_name = txt.val();
            mkGetAddonsList(mk_addon_count_per_request);
        }
    });
}(jQuery));

function mkGetInstalledAddonsList() {
    mkResetSection('.mk-installed-addons');
    var req_data = { action: 'abb_get_installed_addons' }
    jQuery.post(ajaxurl, req_data, function(response) {
        if (response.status == true) {
            jQuery.each(response.data, function(key, val) {
                jQuery('.mk-installed-addons').append(mkInstalledAddonTemplateGenerator(val));
            });
            mk_disable_until_server_respone = false;
        } else {
            swal("Oops ...", response.message, "error");
        }
    });
}

function mkAddonLoadingIndic($which_div, show_hide_status) {
    if (show_hide_status) {
        $which_div.removeClass('mk-addon-load-more-non-active');
    } else {
        $which_div.addClass('mk-addon-load-more-non-active');
    }
}

function mkGetAddonsList(count_number) {
    var from_number = Number(jQuery('.mk-addon-load-more').data('from'));
    mk_disable_until_server_respone = true;
    var req_data = {
        action: 'abb_addon_lazy_load',
        from: from_number,
        count: count_number,
    }
    if (typeof mk_addon_category_name !== 'undefined' && mk_addon_category_name !== null) {
        req_data['category'] = mk_addon_category_name;
    }
    if (typeof mk_addon_name !== 'undefined' && mk_addon_name !== null) {
        req_data['addon_name'] = mk_addon_name;
    }
    mkAddonLoadingIndic(jQuery('.mk-addon-load-more'), true);
    jQuery.post(ajaxurl, req_data, function(response) {
        mkAddonLoadingIndic(jQuery('.mk-addon-load-more'), false);
        if (response.status == true) {
            if (response.data.length > 0) {

                // Set counter for new loading
                jQuery('.mk-addon-load-more').data('from', from_number + response.data.length);

                // Remove load more if response is empty
                if (response.data.length < mk_addon_count_per_request) {
                    jQuery('.mk-addon-load-more').hide();
                } else {
                    jQuery('.mk-addon-load-more').show();
                }

                jQuery.each(response.data, function(key, val) {
                    jQuery('.mk-api-addons-list').append(mkApiAddonTemplateGenerator(val));
                });
                mk_disable_until_server_respone = false;
            } else {
                // Response data is empty
                jQuery('.mk-addon-load-more').hide();
            }
        } else {
            swal("Oops ...", response.message, "error");
        }
    });
}

function mkGetAddonsCategories() {
    var empty_category_list = '<option value="no-category">No Category Found</option>';
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'abb_get_addons_categories' },
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status') === true) {
                if (response.status === true) {
                    var category_list = '<option value="">All Categories</option>';
                    jQuery.each(response.data, function(key, val) {
                        category_list += '<option value="' + val.name + '">' + val.name + '</option>';
                    });
                    jQuery('.mk-addon-category-select').html(category_list);
                } else {
                    jQuery('.mk-addon-category-select').html(empty_category_list);
                    swal("Oops ...", response.message, "error");
                }
            } else {
                jQuery('.mk-addon-category-select').html(empty_category_list);
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_retry_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            jQuery('.mk-addon-category-select').html(empty_category_list);
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkApiAddonTemplateGenerator(data) {
    // <a class="mk-btn-border mk-blue mk-small mk-addon-btn">Learn More</a>
    var template = '<div class="mk-addon-tbl-row"><div class="mk-addon-tbl-col-icon">' +
        '<div class="icon"></div></div><div class="mk-addon-tbl-col-title-desc">' +
        '<div class="mk-addon-name"><span class="mk-bold">' + data.name + '</span>' +
        '<span class="mk-addon-subtitle">Version ' + data.version + '</span></div>' +
        '<div class="mk-addon-desc">' + data.desc.substring(0, 85) + ' ...' + '</div></div>' +
        '<div class="mk-addon-tbl-col-action-btn"><a class="cp-button green small mk-addon-btn abb_addon_activate" data-slug="' +
        data.slug + '" data-name="' + data.name + '">Activate</a></div></div>';
    return template;
}

function mkInstalledAddonTemplateGenerator(data) {
    var btn = '',
        update_tag = '';
    if (data.update_needed == true) {
        btn += '<a href="#" class="mk-btn mk-btn-update ' +
            'abb_addon_update" data-slug="' + data.slug + '" data-name="' + data.name + '">' +
            '<span class="mk-btn-txt">Update</span>' +
            '<span class="mk-btn-spinner"></span>' +
            '</a>';
        update_tag = '<span class="mk-addon-update-tag">Update Available</span>';
    }

    btn += '<a href="#" class="cp-button red small mk-addon-btn abb_addon_deactivate" data-slug="' + data.slug + '" data-name="' + data.name + '">Deactivate</a>';
    var template = '<div class="mk-addon-tbl-row"><div class="mk-addon-tbl-col-icon">' +
        '<div class="icon"></div></div><div class="mk-addon-tbl-col-title-desc">' +
        '<div class="mk-addon-name"><span class="mk-bold">' + data.name + '</span>' +
        '<span class="mk-addon-subtitle">Version ' + data.version + '</span>' + update_tag +
        '</div><div class="mk-addon-desc">' + data.desc.substring(0, 85) + ' ...' +
        '</div></div><div class="mk-addon-tbl-col-action-btn">' + btn + '</div></div>';
    return template;
}

function mkDeactivateAddon(addon_slug, addon_name) {
    var $btn = jQuery('.abb_addon_deactivate[data-slug="' + addon_slug + '"]');
    var req_data = {
        action: 'abb_deactivate_addon',
        addon_slug: addon_slug,
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: req_data,
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status')) {
                if (response.status == true) {
                    $btn.closest('.mk-addon-tbl-row').appendTo('.mk-api-addons-list');
                    $btn.addClass('green').removeClass('red');
                    jQuery('.abb_addon_update[data-slug="' + addon_slug + '"]').remove();
                    $btn.html(mk_cp_textdomain.activate);
                    $btn.addClass('abb_addon_activate').removeClass('abb_addon_deactivate');
                    swal({
                        title: mk_cp_textdomain.deactivating_notice,
                        text: mk_language(mk_cp_textdomain.addon_deactivate_successfully, [addon_name]),
                        type: "success",
                        html: true
                    });
                    return true;
                } else {
                    // Something goes wrong in install progress
                    swal(mk_cp_textdomain.oops, response.message, "error");
                }
            } else {
                // Something goes wrong in server response
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_retry_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkActivateAddon(addon_slug) {
    var $btn = jQuery('.abb_addon_activate[data-slug="' + addon_slug + '"]');
    var addon_name = $btn.data('name');
    var req_data = {
        action: 'abb_activate_addon',
        addon_slug: addon_slug,
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: req_data,
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status')) {
                if (response.status == true) {
                    $btn.closest('.mk-addon-tbl-row').appendTo('.mk-installed-addons');
                    $btn.addClass('red').removeClass('green');
                    $btn.html(mk_cp_textdomain.deactivate);
                    $btn.addClass('abb_addon_deactivate').removeClass('abb_addon_activate');
                    swal({
                        title: mk_cp_textdomain.all_done,
                        text: mk_language(mk_cp_textdomain.addon_is_successfully_installed, [addon_name]),
                        type: "success",
                        html: true
                    });
                    return true;
                } else {
                    // Something goes wrong in install progress
                    swal(mk_cp_textdomain.oops, response.message, "error");
                }
            } else {
                // Something goes wrong in server response
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_retry_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkUpdateAddon(addon_slug) {
    var $btn = jQuery('.abb_addon_update[data-slug="' + addon_slug + '"]');
    jQuery('.abb_addon_update[data-slug="' + addon_slug + '"]').addClass('mk-btn-updating');
    var req_data = {
        action: 'abb_update_addon',
        addon_slug: addon_slug,
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: req_data,
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status')) {
                if (response.status == true) {
                    $btn.removeClass('mk-addon-btn-updating');
                    $btn.closest('.mk-addon-tbl-row').find('.mk-addon-update-tag').slideUp("normal", function() { jQuery(this).remove(); });
                    $btn.remove();
                    return true;
                } else {
                    // Something goes wrong in install progress
                    swal(mk_language(mk_cp_textdomain.something_went_wrong, []), response.message, "error");
                }
            } else {
                // Something goes wrong in server response
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_retry_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown) {
    console.log(XMLHttpRequest);
    if (XMLHttpRequest.readyState == 4) {
        // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
        swal("Oops ...", 'Error in API (' + XMLHttpRequest.status + ')', "error");
    } else if (XMLHttpRequest.readyState == 0) {
        // Network error (i.e. connection refused, access denied due to CORS, etc.)
        swal("Oops ...", 'Error in network , please check your connection and try again', "error");
    } else {
        swal("Oops ...", 'Something wierd happened , please retry again', "error");
    }
}

function mkResetSection($which_section) {
    jQuery($which_section).fadeOut(300, function() {
        jQuery(this).empty().fadeIn(300);
    });
}

function mkResetFromNumber() {
    jQuery('.mk-addon-load-more').data('from', 0);
}

/**
 * [ description]
 * 
 * @author Reza Marandi <ross@artbees.net>
 * @since 5.5
 * @package Jupiter
 * 
 * @param {string} string The string of translation we want to replace param with
 * @param {array} params The array of params we want to replace in translate text
 *
 * @return {string} Will return string of translate text after replacing params
 */

function mk_language(string, params) {
    array_len = params.length;
    if (array_len < 1) {
        return string;
    }
    indicator_len = (string.match(/{param}/g) || []).length;

    if (array_len == indicator_len) {
        jQuery.each(params, function(key, val) {
            string = string.replace('{param}', val);
        });
        return string;
    }

    // Array len and indicator lengh is not same;
    console.log('Array len and indicator lengh is not same, Contact support with ID : (3-6H1T4I) .');
    return string;
}
