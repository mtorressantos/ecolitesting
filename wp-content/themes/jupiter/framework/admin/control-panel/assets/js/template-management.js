var mk_template_count_per_request = 10,
    mk_disable_until_server_respone = false,
    mk_install_types = ['preparation', 'backup_db', 'backup_media_records', 'reset_db', 'upload', 'unzip', 'validate', 'plugin', 'theme_content', 'menu_locations', 'setup_pages', 'theme_options', 'theme_widget', 'restore_media_records', 'finalize'],
    mk_template_id = null,
    mk_template_name = null,
    mk_template_media_import_status = false;
(function($) {
    if ($('.abb-template-page-load-more').length == 0) {
        return false;
    }
    $(".hidden").hide().removeClass("hidden");
    mkGetTemplatesCategories();
    mkGetTemplatesList(mk_template_count_per_request);
    $(window).scroll(function() {
        var hT = $('.abb-template-page-load-more').offset().top,
            hH = $('.abb-template-page-load-more').outerHeight(),
            wH = $(window).height(),
            wS = $(this).scrollTop();
        if (wS > (hT + hH - wH) && mk_disable_until_server_respone === false) {
            mkGetTemplatesList(mk_template_count_per_request);
        }
    });

    $(document).on('click', '.abb_template_install', function() {
        var $btn = $(this);
        var $CredentialsModal = $('#request-filesystem-credentials-dialog');
        if($CredentialsModal.length){
            var $CredentialsData = mkGetCredentialsData(); // Get stored credentials data.
            if( !$CredentialsData ){
                mkCredentialsModal($CredentialsModal, $btn, 'install');
            }else{
                mkInstalTemplateModal($btn);
            }
        }else{
            mkInstalTemplateModal($btn);
        }

    });

    $(document).on('click', '.mk-restore-btn', function() {
        var $btn = $(this);
        var $CredentialsModal = $('#request-filesystem-credentials-dialog');
        if($CredentialsModal.length){
            var $CredentialsData = mkGetCredentialsData(); // Get stored credentials data.
            if( !$CredentialsData ){
                mkCredentialsModal($CredentialsModal, $btn, 'restore');
            }else{
                mkRestoreTemplateModal($btn);
            }
        }else{
            mkRestoreTemplateModal($btn);
        }
    });

    function mkCredentialsModal($dialog, $btn, $action) {

        var form_id = 'mk-install-template-credential';

        var $dialog_content = $dialog.find('.request-filesystem-credentials-dialog-content').clone();

        $dialog_content.find('#request-filesystem-credentials-title').hide();
        $dialog_content.find('#upgrade').hide();
        $dialog_content.find('.cancel-button').hide();
        $dialog_content.find('form').attr('id', form_id).append('<input type="hidden" name="action" value="abb_check_ftp_credentials">');

        swal({
            customClass: "request-credentials-form-modal",
            html:true,       
            title: $dialog_content.find('#request-filesystem-credentials-title').text(),
            text: $dialog_content.html(),
            type: "warning",
            showCancelButton: true,
            cancelButtonText: $dialog_content.find('.cancel-button').val(),
            confirmButtonColor: "#32d087",
            confirmButtonText: $dialog_content.find('#upgrade').val(),
            closeOnConfirm: false
        }, function(isConfirm) {
            if(isConfirm){
                var formData = $('#' + form_id).serializeArray();
                $.ajax(ajaxurl, {
                    data: formData,
                    method: 'POST',
                    beforeSend: function(jqXHR, settings) {
                        swal.disableButtons();
                        swal.resetInputError();
                    },
                    success: function(response, textStatus, jqXHR) {
                        if(response.status){
                            mkStoreCredentialsData(formData);
                            if( 'install' == $action ){
                                mkInstalTemplateModal($btn);
                            }else if( 'restore' == $action ){
                                mkRestoreTemplateModal($btn);
                            }
                        }else{
                            if(response.message){
                                swal.showInputError(response.message);
                            }else{
                                swal.showInputError(mk_cp_textdomain.incorrect_credentials);
                                $('.request-credentials-form-modal .sa-input-error.show').hide();
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.showInputError('error');
                    }
                });
            }
        });
    };

    function mkInstalTemplateModal($btn){
        swal.enableButtons();

        swal({
            title: mk_cp_textdomain.important_notice,
            text: mk_cp_textdomain.installing_sample_data_will_delete_all_data_on_this_website,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#32d087",
            confirmButtonText: mk_cp_textdomain.yes_install + $btn.data('name'),
            closeOnConfirm: false,
            showLoaderOnConfirm: false
        }, function() {
            swal({
                title: mk_cp_textdomain.include_images_and_videos,
                text: mk_template_language(mk_cp_textdomain.would_you_like_to_import_images_and_videos_as_preview, ['<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
                type: "warning",
                showCancelButton: true,
                html: true,
                confirmButtonColor: "#32d087",
                confirmButtonText: mk_cp_textdomain.do_not_include,
                cancelButtonText: mk_cp_textdomain.include,
                closeOnConfirm: false,
                closeOnCancel: false,
            }, function(media_import_status) {
                mk_template_media_import_status = !media_import_status;
                if ( mk_template_media_import_status ) {
                    mkCheckSystemResource( $btn );
                } else {
                    swal({
                        title: "<h2>" + mk_cp_textdomain.install_sample_data + "</h2>",
                        text: '<div class="import-modal-container"><ul><li class="upload">' + mk_cp_textdomain.downloading_sample_package_data + '<span class="result-message"></span></li><li class="plugin">' + mk_cp_textdomain.install_required_plugins + '<span class="result-message"></span></li><li class="install">' + mk_cp_textdomain.install_sample_data + '<span class="result-message"></span></li></ul><div id="mk_templates_progressbar"><div></div></div></div>',
                        html: true,
                        showConfirmButton: false,
                    });
                    mkInstallTemplate(0, $btn.data('slug'));
                }
            });
        });
    }

    function mkRestoreTemplateModal($btn){
        console.log("Click in .mk-restore-btn");
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'abb_is_restore_db' },
            dataType: "json",
            success: function(response) {
                swal.enableButtons();
                var created_date = response.data.latest_backup_file.created_date;
                swal({
                    title: mk_cp_textdomain.restore_settings,
                    text: "<p>" + mk_cp_textdomain.you_are_trying_to_restore_your_theme_settings_to_this_date + "<strong class='mk-tooltip-restore--created-date'>" + created_date + "</strong>. " + mk_cp_textdomain.are_you_sure + "</p>",
                    type: "warning",
                    showCancelButton: true,
                    html: true,
                    confirmButtonColor: "#32d087",
                    confirmButtonText: mk_cp_textdomain.restore,
                    closeOnConfirm: false
                }, function () {
                    var restoreTemplateParams = {
                        action: 'abb_restore_latest_db'
                    };
                    var $storedCredentialsData = mkGetCredentialsData();
                    // Inject stored FTP Credentials Data for each request.
                    if($storedCredentialsData){
                        restoreTemplateParams = jQuery.extend(
                            {},
                            $storedCredentialsData, 
                            restoreTemplateParams
                        );
                    }
                    swal.disableButtons();
                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: restoreTemplateParams,
                        dataType: "json",
                        success: function(response) {
                            if( response.status ){
                                swal({
                                  title: response.message,
                                  text: '',
                                  type: "success",
                                  showCancelButton: false,
                                  confirmButtonText: mk_cp_textdomain.reload_page
                                },
                                function(){
                                    location.reload();
                                });
                            }else{
                                swal(mk_cp_textdomain.whoops, response.message, "error");
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log("Fail: ", XMLHttpRequest);
                        }
                    });
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log("Fail: ", XMLHttpRequest);
            }
        });
    }

    $(document).on('click', '.abb_template_uninstall', function() {
        var $btn = $(this);
        swal({
            title: mk_cp_textdomain.important_notice,
            text: mk_cp_textdomain.uninstalling_template_will_remove_all_your_contents_and_settings,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd5434",
            confirmButtonText: mk_cp_textdomain.yes_uninstall + $btn.data('name'),
            closeOnConfirm: false
        }, function() {
            mkUninstallTemplate($btn.data('slug'));
        });
    });
    $(document).on('change', '.mk-templates-categories', function() {
        var $select = $(this);
        mkResetGetTemplateInfo();
        mk_template_id = $select.val();
        mkGetTemplatesList(mk_template_count_per_request);
    });
    $('.mk-search-template').on('keyup', _.debounce(function(e) {
        var txt = $(this);
        mkSearchTemplateByName(txt.val());
    }, 500));

}(jQuery));

function mkUninstallTemplate(template_slug) {
    jQuery.post(ajaxurl, {
        action: 'abb_uninstall_template',
    }).done(function(response) {
        console.log('Ajax Req : ', response);
        // Cache selectors
        var $link = jQuery('a[data-slug="' + template_slug + '"]');
        var $template_list = jQuery('#template-list');
        var $installed_template = jQuery('.mk-installed-template');
        // Handle related elements
        $link.html(mk_cp_textdomain.install);
        $link.closest('.mk-template-item').remove();
        $installed_template.hide();
        jQuery('.mk-installed-template .mk-restore-template-holder').hide();
        jQuery('.mk-templates-header .mk-restore-template-holder').show();
        // Reset Get Templtes
        mkResetGetTemplateInfo();
        mk_template_id = 'all-categories';
        mkGetTemplatesList(mk_template_count_per_request);
        // Reset Categories
        jQuery('.mk-templates-categories').val('all-categories');
        // Alert
        swal(mk_cp_textdomain.template_uninstalled, "", "success");
    }).fail(function(data) {
        console.log('Failed msg : ', data);
    });
}

function mkInstallTemplate(index, template_name) {
    if (mk_install_types[index] == undefined) {

        // Cache selectors
        var $link = jQuery('a[data-slug="' + template_name + '"]');
        var $installed_template_list = jQuery('#installed-template-list');
        var $installed_template = jQuery('.mk-installed-template');

        // Handle related elements
        $link.html(mk_cp_textdomain.uninstall);
        $link.addClass('abb_template_uninstall').removeClass('abb_template_install');
        $installed_template_list.empty();
        $link.closest('.mk-template-item').appendTo($installed_template_list).addClass('mk-installed-template-item');

        // Show installed Templates
        $installed_template.show();
        jQuery('.mk-templates-header .mk-restore-template-holder').hide();
        jQuery('.mk-installed-template .mk-restore-template-holder').show();

        // Alert
        swal(mk_cp_textdomain.hooray, mk_cp_textdomain.template_installed_successfully, "success");

        return;
    }

    var $storedCredentialsData = mkGetCredentialsData();

    var formDataTemplateProcedure = {
        action: 'abb_install_template_procedure', 
        type: mk_install_types[index], 
        template_name: template_name, 
        template_imgurl: jQuery('img[alt="'+template_name+'"\i]').attr('src'),
        import_media: mk_template_media_import_status 
    };

    // Inject stored FTP Credentials Data for each request.
    if($storedCredentialsData){
        formDataTemplateProcedure = jQuery.extend(
            {},
            $storedCredentialsData, 
            formDataTemplateProcedure
        );
    }
    
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: formDataTemplateProcedure,
        dataType: "json",
        success: function(response) {
            console.log('Install Template - ', mk_install_types[index], ' - Fetch media : ', mk_template_media_import_status, ' : Req data - ', template_name, ' , Response - ', response);
            if (response.hasOwnProperty('status')) {
                if (response.status == true) {
                    mkProgressBar(mkCalcPercentage(mk_install_types.length - 1, index), jQuery('#mk_templates_progressbar'));
                    mkShowResult(mk_install_types[index], response.message);
                    mkInstallTemplate(++index, template_name);
                } else {
                    switch ( formDataTemplateProcedure.type ){
                        case 'check_plugin':
                            swal({
                              title: response.message,
                              html: true,
                              text: response.data,
                              type: "warning",
                              showCancelButton: false,
                              confirmButtonText: mk_cp_textdomain.yes_install,
                              closeOnConfirm: false
                            },
                            function(){
                                window.location = mk_admin_page_instal_plugin;
                            });
                        break;
                        default:
                            // Something goes wrong in install progress
                            swal(mk_cp_textdomain.oops, response.message, "error");
                        break;
                    }
                }
            } else {
                // Something goes wrong in server response
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_try_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });

    var formDataIsRestoreDb = {
        action: 'abb_is_restore_db'
    };

    // Inject stored FTP Credentials Data for each request.
    if($storedCredentialsData){
        formDataIsRestoreDb = jQuery.extend(
            {},
            $storedCredentialsData, 
            formDataIsRestoreDb
        );
    }

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: formDataIsRestoreDb,
        dataType: "json",
        success: function(response) {

            var data           = response.data,
            list_of_backups    = [],
            latest_backup_file = null,
            created_date       = "";
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
                    var restore_btn = jQuery('.mk-restore-template-holder');
                    if(restore_btn.length){
                        jQuery('.mk-restore-template-holder .mk-tooltip--text .mk-tooltip--created-date').each(function () {
                            jQuery(this).html(created_date);
                        });
                    }else{
                        $btnRestore = '<a class="mk-restore-template-holder"><span class="mk-restore--text mk-restore-btn">' + mk_cp_textdomain.restore_from_last_backup + '</span><span class="mk-tooltip--text">' + mk_cp_textdomain.restore_theme_settings_to_this_version + ': <span class="mk-tooltip--created-date">' + created_date + '</span></span></a>';
                        jQuery('.mk-restore-template-wrapper').append($btnRestore);
                    }
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

}

function mkGetTemplatesCategories() {
    var empty_category_list = '<option value="no-category">No template found</option>';
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'abb_get_templates_categories' },
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status') === true) {
                if (response.status === true) {
                    var category_list = '<option value="all-categories">All Categories</option>';
                    jQuery.each(response.data, function(key, val) {
                        category_list += '<option value="' + val.id + '">' + val.name + ' - ' + val.count + '</option>';
                    });
                    jQuery('.mk-templates-categories').html(category_list);
                } else {
                    jQuery('.mk-templates-categories').html(empty_category_list);
                    swal("Oops ...", response.message, "error");
                }
            } else {
                jQuery('.mk-templates-categories').html(empty_category_list);
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_try_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            jQuery('.mk-templates-categories').html(empty_category_list);
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkShowResult(type, message) {
    message = '-    ' + message;
    switch (type) {
        case 'backup_db':
            jQuery('.import-modal-container .upload .result-message').text(message);
            break;
        case 'backup_media_records':
            jQuery('.import-modal-container .upload .result-message').text(message);
            break;
        case 'reset_db':
            jQuery('.import-modal-container .upload .result-message').text(message);
            break;
        case 'upload':
            jQuery('.import-modal-container .upload .result-message').text(message);
            break;
        case 'unzip':
            jQuery('.import-modal-container .upload .result-message').text(message);
            break;
        case 'validate':
            jQuery('.import-modal-container .upload .result-message').text(message);
            jQuery('.import-modal-container .upload').addClass('mk-done');
            break;
        case 'plugin':
            jQuery('.import-modal-container .plugin .result-message').text(message);
            jQuery('.import-modal-container .plugin').addClass('mk-done');
            break;
        case 'theme_content':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'menu_locations':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'setup_pages':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'theme_options':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'theme_widget':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'restore_media_records':
            jQuery('.import-modal-container .install .result-message').text(message);
            break;
        case 'finalize':
            jQuery('.import-modal-container .install .result-message').text(message);
            jQuery('.import-modal-container .install').addClass('mk-done');
            break;
    }
}

function mkCalcPercentage(bigNumber, littleNumber) {
    return Math.round((littleNumber * 100) / bigNumber);
}

function mkGetTemplatesList(count_number) {
    var from_number = Number(jQuery('.abb-template-page-load-more').data('from'));
    mk_disable_until_server_respone = true;
    var req_data = {
        action: 'abb_template_lazy_load',
        from: from_number,
        count: count_number,
    }
    if (typeof mk_template_id !== 'undefined' && mk_template_id !== null) {
        req_data['template_category'] = mk_template_id;
    }
    if (typeof mk_template_name !== 'undefined' && mk_template_name !== null) {
        req_data['template_name'] = mk_template_name;
    }
    var $spinner = jQuery('<div class="mk-template-loading-spinner"><div class="mk-loading-spinner"></div></div>');
    jQuery('#template-list').append($spinner);
    console.log(req_data);
    jQuery.post(ajaxurl, req_data, function(response) {
        $spinner.remove();
        if (response.status == true) {

            var backups        = response.data.backups,
            list_of_backups    = [],
            latest_backup_file = null,
            created_date       = "";
            $btnRestore        = "";

            if(backups.hasOwnProperty('list_of_backups')){
                list_of_backups = backups.list_of_backups;
                if (list_of_backups == null) {
                    console.log("List Of Backups is NULL!");
                } else if (list_of_backups.length == 0) {
                    console.log("List Of Backups is EMPTY!");
                } else {
                    latest_backup_file = backups.latest_backup_file;
                    created_date = latest_backup_file.created_date;
                    $btnRestore = '<a class="mk-restore-template-holder"><span class="mk-restore--text mk-restore-btn">Restore from Last Backup</span><span class="mk-tooltip--text">Restore theme settings to this version: <span class="mk-tooltip--created-date">' + created_date + '</span></span></a>';
                    jQuery('.mk-restore-template-wrapper').find('.mk-restore-template-holder').remove();
                    jQuery('.mk-restore-template-wrapper').append($btnRestore);
                    console.log("Restore Buttons Created Successfully!");
                }
            }

            if (response.data.templates.length > 0) {
                jQuery('.abb-template-page-load-more').data('from', from_number + response.data.templates.length);
                jQuery.each(response.data.templates, function(key, val) {
                    if (val.installed){
                        jQuery('.mk-templates-header .mk-restore-template-holder').hide();
                    }
                    // If is intalled but it's not in the page
                    if (val.installed == true && jQuery('.mk-installed-template-item').length == 0) {
                        jQuery('.mk-installed-template').show();
                        jQuery('#installed-template-list').empty().append(mkTemplateGenerator(val));
                        // Reset Get Templtes
                        mkResetGetTemplateInfo();
                        mk_template_id = 'all-categories';
                        mkGetTemplatesList(mk_template_count_per_request);
                        // If is NOT intalled
                    } else if (val.installed != true) {
                        jQuery('#template-list').append(mkTemplateGenerator(val));
                    }
                });
                mk_disable_until_server_respone = false;
            }
        } else {
            console.log(response);
            swal("Oops...", response.message, "error");
        }
    });
}

function mkTemplateGenerator(data) {

    // Sanitize template names for URLs
    sanitized_template_name = data.name.replace(/\s+/g, '-').toLowerCase();

    if (data.installed == false) {
        var btn =
            '<a class="abb_template_install mk-template-item-btn mk-template-item-btn-action" data-name="' + data.name + '" data-slug="' + data.name + '">' +
            mk_cp_textdomain.install +
            '</a>' +
            '<a class="mk-template-item-btn mk-template-item-btn-preview" href="http://demos.artbees.net/jupiter/' + sanitized_template_name + '/" target="_blank">' +
            mk_cp_textdomain.preview +
            '</a>';
    } else {
        var btn =
            '<a class="mk-template-item-btn mk-template-item-btn-action abb_template_uninstall" data-name="' + data.name + '" data-slug="' + data.name + '">' +
            mk_cp_textdomain.uninstall +
            '</a>' +
            '<a class="mk-template-item-btn mk-template-item-btn-preview" href="http://demos.artbees.net/jupiter/' + sanitized_template_name + '/" target="_blank">' +
            mk_cp_textdomain.preview +
            '</a>';
    }
    if (data.installed == false) {
        var template =
            '<div class="mk-template-item">' +
            '<div class="mk-template-item-inner">' +
            '<form method="post">' +
            '<figure class="mk-template-item-fig">' +
            '<img src="' + data.img_url + '" alt="' + data.name + '">' +
            '</figure>' +
            '<div class="mk-template-item-desc">' +
            '<h4 class="mk-template-item-title">' + data.name + '</h4>' +
            '<div class="mk-template-item-buttons">' + btn + '</div>' +
            '</div>' +
            '</form>' +
            '</div>' +
            '</div>';
    } else {
        var template =
            '<div class="mk-template-item mk-installed-template-item">' +
            '<div class="mk-template-item-inner">' +
            '<form method="post">' +
            '<figure class="mk-template-item-fig">' +
            '<img src="' + data.img_url + '" alt="' + data.name + '">' +
            '</figure>' +
            '<div class="mk-template-item-desc">' +
            '<h4 class="mk-template-item-title">' + data.name + '<span class="mk-template-item-subtitle">Installed</span>' + '</h4>' +
            '<div class="mk-template-item-buttons">' + btn + '</div>' +
            '</div>' +
            '</form>' +
            '</div>' +
            '</div>';
    }
    return template;
}

function mkProgressBar(percent, $element) {
    var progressBarWidth = percent * $element.width() / 100;
    $element.find('div').animate({ width: progressBarWidth }, 500).html(percent + "% ");
}

function mkResetGetTemplateInfo() {
    jQuery("#template-list").fadeOut(300, function() {
        jQuery(this).empty().fadeIn(300);
    });
    jQuery('.abb-template-page-load-more').data('from', 0);
}

function mkSearchTemplateByName(template_name) {
    mkResetGetTemplateInfo();
    mk_template_name = template_name;
    mkGetTemplatesList(mk_template_count_per_request);
}

function mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown) {
    if (XMLHttpRequest.readyState == 4) {
        // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
        if (XMLHttpRequest.responseText != '') {
            var sweet_alert_config = {
                title: mk_cp_textdomain.whoops,
                text: mk_template_language(mk_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
                type: "input",
                html: true,
                inputValue: window.btoa(XMLHttpRequest.responseText),

            };
        } else {
            var sweet_alert_config = {
                title: mk_cp_textdomain.whoops,
                text: mk_template_language(mk_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
                type: "error",
                html: true,
            };
        }
        swal(sweet_alert_config);
    } else if (XMLHttpRequest.readyState == 0) {
        // Network error (i.e. connection refused, access denied due to CORS, etc.)
        swal(mk_cp_textdomain.whoops, mk_cp_textdomain.error_in_network_please_check_your_connection_and_try_again, "error");
    } else {
        swal(mk_cp_textdomain.whoops, mk_cp_textdomain.something_wierd_happened_please_try_again, "error");
    }
}

function mk_template_language(string, params) {
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

function mkCheckSystemResource( $btn ) {
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'abb_check_system_resource' },
        dataType: "json",
        timeout: 60000,
        success: function(response) {
            if (response.hasOwnProperty('status') === true) {
                console.log( 'Check system resource', response );
                if (response.status === true) {
                    mkContinueInstall( $btn );
                } else {
                    swal({
                        title: mk_cp_textdomain.insufficient_system_resource,
                        text: mk_template_language(mk_cp_textdomain.insufficient_system_resource_notes, ['<a href="https://themes.artbees.net/docs/installing-template/" target="_blank">Learn More</a>']),
                        type: "warning",
                        showCancelButton: true,
                        html: true,
                        confirmButtonColor: "#32d087",
                        confirmButtonText: mk_cp_textdomain.continue_without_media,
                        closeOnConfirm: false,
                    }, function( isContinue ) {
                        if ( isContinue ) {
                            mk_template_media_import_status = false;
                            mkContinueInstall( $btn );
                        }
                    });
                }
            } else {
                swal(mk_cp_textdomain.oops, mk_cp_textdomain.something_wierd_happened_please_try_again, "error");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            mkRequestErrorHandling(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function mkContinueInstall( $btn ) {
    swal({
        title: "<h2>" + mk_cp_textdomain.install_sample_data + "</h2>",
        text: '<div class="import-modal-container"><ul><li class="upload">' + mk_cp_textdomain.downloading_sample_package_data + '<span class="result-message"></span></li><li class="plugin">' + mk_cp_textdomain.install_required_plugins + '<span class="result-message"></span></li><li class="install">' + mk_cp_textdomain.install_sample_data + '<span class="result-message"></span></li></ul><div id="mk_templates_progressbar"><div></div></div></div>',
        html: true,
        showConfirmButton: false,
    });
    mkInstallTemplate(0, $btn.data('slug'));
}

function mkStoreCredentialsData($data){

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
}

function mkGetCredentialsData(){

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
}

// Create Base64 Object
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
        var r = c1 = c2 = 0;
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
}