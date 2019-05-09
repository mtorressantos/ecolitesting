// ---------------------------------
// ---------- MK Modal ----------
// ---------------------------------
// This plugin is used to output messages in Jupiter WordPress Theme's Admin Panel
// ------------------------

;(function ( $, window, document ) {

    var pluginName = 'mk_modal';

    // Create the plugin constructor
    function Modal ( element, options ) {

        /*
            Provide local access to the DOM node(s) that called the plugin,
            as well local access to the plugin name and default options.
        */
        this.element = element;
        this.body = document.body;
        this.$modal = '';
        this.$overlay = '';
        this.isModalOpen = false;
        this._name = pluginName;
        this._defaults = $.fn.mk_modal.defaults;


        /*
            Abracadabra!
        */
        this.init(options);
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Modal.prototype, {

        // Init logic
        init: function (options) {

            /*
                Extending options & defaults
            */
            this.options = $.extend( {}, this._defaults, options );

            if ( this.isModalOpen ) {
                this.templateInit();
                this.cacheElements();
                this.open();
            } else {
                this.disableScroll();
                this.templateInit();
                this.showOverlay();
                this.cacheElements();
                this.open();
            }
            
        },

        // Disable document scrolling
        disableScroll: function () {

            $('body').addClass('mka-modal-active');

        },

        // Enable document scrolling
        enableScroll: function () {

            $('body').removeClass('mka-modal-active');

        },

        // Show modal overlay
        showOverlay: function () {

            var isOverlayCreated = this.$overlay.length;
            if ( isOverlayCreated ) {
                TweenLite.to( this.$overlay, 0.1, { css: {  opacity: 1, display: 'block' }, ease: Power1.easeOut, delay: 0 });
            } else {
                this.$overlay = $('<div class="mka-modal-overlay"></div>');
                $(this.body).append( this.$overlay );
                TweenLite.to( this.$overlay, 0.1, { css: {  opacity: 1, display: 'block' }, ease: Power1.easeOut, delay: 0 });
            }

        },

        // Hide modal overlay
        hideOverlay: function () {

            TweenLite.to( this.$overlay, 0.1, { css: {  opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });

        },

        // Initilize Template
        templateInit: function () {
            this.bindEvents( this.templateBuilder() );
        },

        // Build modal templates based on options
        templateBuilder: function () {
            var options = this.options;
            var html = '';

            var indefClass = (options.indefiniteProgress) ? 'mka-modal--indef-progress' : '';
            var typeClass = (options.type) ? 'mka-modal--' + options.type : '';

            html += '<div id="mka-modal" class="mka-modal ' + typeClass + ' ' + indefClass + '">';
                if ( options.showCloseButton ) {
                html += '<a href="#" class="mka-modal-close-btn"></a>';
                }
                if ( options.showProgress ) {
                html += '<div class="mka-modal-progress">';
                    html += '<div class="mka-modal-progress-bar" style="width:' +   options.progress + '"></div>';
                html += '</div>';
                }
                html += '<div class="mka-modal-content">';
                    if ( options.html ) {
                        html += ( ! options.html instanceof jQuery ) ? options.html : '';
                    } else {
                    html += '<span class="mka-modal-icon"></span>';
                    html += '<h3 class="mka-modal-title">' + options.title + '</h3>';
                    html += '<div class="mka-modal-desc">';
                        html += options.text;
                    html += '</div>';
                    html += '<div class="mka-modal-footer">';
                        if ( options.showConfirmButton ) {
                        html += '<div class="mka-wrap mka-modal-ok-btn-wrap">';
                            html += '<input type="button" class="mka-button mka-button--blue mka-button--small mka-modal-ok-btn" value="' + options.confirmButtonText + '">';
                        html += '</div>';
                        }
                        if ( options.showCancelButton ) {
                        html += '<div class="mka-wrap mka-modal-cancel-btn-wrap">';
                            html += '<input type="button" class="mka-button mka-button--gray mka-button--small mka-modal-cancel-btn" value="' + options.cancelButtonText +  '">';
                        html += '</div>';
                        }
                        if ( options.showLearnmoreButton ) {
                            var learnmoreLabel = 'More Help';
                            if ( options.learnmoreLabel ) {
                                learnmoreLabel = options.learnmoreLabel;
                            }
                            var learnmoreTarget = '';
                            if ( options.learnmoreTarget ) {
                                learnmoreTarget = 'target="' + options.learnmoreTarget + '"';
                            }
                        html += '<a ' + learnmoreTarget + ' href="' + options.learnmoreButton + '" class="mka-modal-readmore-btn">' + learnmoreLabel + '</a>';
                        }
                    html += '</div>';
                    }
                html += '</div>';
            html += '</div>';

            var $html = $(html);

            if ( options.html && options.html instanceof jQuery ) {
                $html.find('.mka-modal-content').prepend(options.html);
            }

            return $html;
        },

        // Open modal
        open: function() {

            var $new_modal = this.$modal;
            var $modal = $(this.body).children('#mka-modal');
            var isModalAdded = $modal.length;
            if ( isModalAdded && this.isModalOpen ) {
                TweenLite.to( $new_modal, 0, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0 });
                $modal.replaceWith($new_modal);
            } else if ( isModalAdded && !this.isModalOpen ) {
                $modal.replaceWith($new_modal);
                TweenLite.to( $new_modal, 0, { css: { opacity: 0, y: '30' }, ease: Power1.easeOut, delay: 0 });
                TweenLite.to( $new_modal, 0.2, { css: { opacity: 1, y: '0'  }, ease: Power4.easeInOut, delay: 0.1 });
            } else {
                $(this.body).append($new_modal);
                TweenLite.to( $new_modal, 0, { css: { opacity: 0, y: '30' }, ease: Power1.easeOut, delay: 0 });
                TweenLite.to( $new_modal, 0.2, { css: { opacity: 1, y: '0'  }, ease: Power4.easeInOut, delay: 0.1 });
            }

            $new_modal.css({
                marginTop: function() {
                    return - $(this).outerHeight() / 2 + 'px';
                }
            });

            this.isModalOpen = true;

        },

        // Close modal
        close: function(template) {

            this.enableScroll();
            this.hideOverlay();
            this.$modal.hide();
            this.isModalOpen = false;

        },

        // Bind events that trigger methods
        bindEvents: function($template) {
            var plugin = this;
            var $modal = $template;
            var $closeBtn = $modal.find('.mka-modal-close-btn');
            var $confirmBtn = $modal.find('.mka-modal-ok-btn-wrap');
            var $cancelBtn = $modal.find('.mka-modal-cancel-btn-wrap');

            // Close Button
            $closeBtn.on('click' + '.' + plugin._name, function(e) {
                e.preventDefault();
                plugin.close();
                plugin.onClose();
            });

            $confirmBtn.on('click' + '.' + plugin._name, function(e) {
                e.preventDefault();
                if ( plugin.options.closeOnConfirm ) {
                    plugin.close();
                }
                plugin.onConfirm();
            });

            $cancelBtn.on('click' + '.' + plugin._name, function(e) {
                e.preventDefault();
                
                if ( plugin.options.closeOnCancel ) {
                    plugin.close();
                }
                plugin.onCancel();
            });

            $(document).on('click' + '.' + plugin._name, '.mka-modal-overlay', function(e) {
                if (plugin.options.closeOnOutsideClick) {
                    e.preventDefault();
                    plugin.close();
                    plugin.onOutside();
                }
            });

            this.$modal = $modal;

        },

        // Cache elements for update method
        cacheElements: function() {

            var $modal = this.$modal;
            this.$progressBar = $modal.find('.mka-modal-progress-bar');
            this.$title = $modal.find('.mka-modal-title');
            this.$desc = $modal.find('.mka-modal-desc');
            
        },

        // Update properties
        update: function(updatedOptions) {
            var options = $.extend( {}, this.options, updatedOptions );
            this.$progressBar.css('width', options.progress);
        },

        // Create custom methods
        someOtherFunction: function() {
            alert('I promise to do something cool!');
            this.callback();
        },

        onConfirm: function() {

            var onConfirm = this.options.onConfirm;
            if ( typeof onConfirm === 'function' ) {
                onConfirm.call(this.element);
            }
            
        },

        onCancel: function() {

            var onCancel = this.options.onCancel;
            if ( typeof onCancel === 'function' ) {
                onCancel.call(this.element);
            }

        },

        onClose: function() {

            var onClose = this.options.onClose;
            if ( typeof onClose === 'function' ) {
                onClose.call(this.element);
            }

        },

        onOutside: function() {

            var onOutside = this.options.onOutside;
            if ( typeof onOutside === 'function' ) {
                onOutside.call(this.element);
            }

        }

    });

    /*
        Create a lightweight plugin wrapper around the "Plugin" constructor,
        preventing against multiple instantiations.
    */
    $.fn.mk_modal = function ( options ) {

        var pluginInstance = $.data( document.body, "plugin_" + pluginName );

        if ( !pluginInstance ) {
            /*
                Use "$.data" to save each instance of the plugin in case
                the user wants to modify it. Using "$.data" in this way
                ensures the data is removed when the DOM elements are
                removed via jQuery methods, as well as when the userleaves
                the page. It's a smart way to prevent memory leaks.
            */
            pluginInstance = $.data( document.body, "plugin_" + pluginName, new Modal( this, options ) );
        } else {
            pluginInstance.init(options);
        }


        /*
            "return this;" returns the original jQuery object. This allows
            additional jQuery methods to be chained.
        */
        return pluginInstance;
    };

    /*
        Attach the default plugin options directly to the plugin object. This
        allows users to override default plugin options globally, instead of
        passing the same option(s) every time the plugin is initialized.

        For example, the user could set the "property" value once for all
        instances of the plugin with
        "$.fn.pluginName.defaults.property = 'myValue';". Then, every time
        plugin is initialized, "property" will be set to "myValue".
    */
    $.fn.mk_modal.defaults = {
        title:  '',
        text: '',
        html: null,
        type: 'error',
        showCancelButton: false,
        showConfirmButton: true,
        showCloseButton: true,
        showLearnmoreButton: true,
        showProgress: false,
        progress: '0%',
        indefiniteProgress: false,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        learnmoreButton: '#',
        learnmoreLabel: 'More Help',
        learnmoreTarget: '',
        closeOnConfirm: true,
        closeOnCancel: true,
        closeOnOutsideClick: true,
        onConfirm: null,
        onCancel: null,
        onClose: null,
        onOutside: null,
    };

})( jQuery, window, document );

var mk_modal = function(options) {
    return jQuery(document.body).mk_modal(options);
}
mk_modal.update = function(update_obj) {
    var pluginInstance = jQuery.data( document.body, "plugin_mk_modal" );
    if ( pluginInstance ) {
        pluginInstance.update(update_obj);
    }
}