(function($) {

    "use strict";

    if ( $('.mka-to-wrap').length ) {

        var $paneBoxes = $('.mka-to-pane-box'),
            $nav = $('.mka-to-nav'),
            $nav_items = $nav.find('.mka-to-nav-item'),
            navTopOffset = $nav.offset().top,
            $sidebar = $('.mka-to-sidebar'),
            sidebarTopOffset = $sidebar.offset().top,
            sidebarBotOffset = sidebarTopOffset + $sidebar.outerHeight() ,
            $sidebarActions = $('.mka-to-sidebar-actions'),
            sidebarActionsTopOffset = $sidebarActions.offset().top,
            sidebarActionsBotOffset = sidebarActionsTopOffset + $sidebarActions.outerHeight(),
            $header = $('.mka-to-header'),
            header_height = $header.outerHeight(),
            headerTopOffset = $header.offset().top - 30;  // Negetive 30 to account for WP Admin Bar


        /*---------------------------------------------------------------------------------*/
        /*  Navigation
        /*---------------------------------------------------------------------------------*/
        
        // Nav Menu
        $nav_items.find('a').on('click', function(e) {
            
            e.preventDefault();

            var hash = $(this).attr('href');
            $paneBoxes.hide();
            $(hash).show();
            $nav_items.removeClass('mka-to-nav-item--active');

            if ( $(this).closest('.mka-to-nav-subitem').length ) {
                $(this).closest('.mka-to-nav-item').addClass('mka-to-nav-item--active');
                $(this).parent().siblings().removeClass('mka-to-nav-subitem--active');
                $(this).parent().addClass('mka-to-nav-subitem--active');
            } else {
                $(this).parent().addClass('mka-to-nav-item--active');
            }

        });

        // Nav Sticky
        $(window).scroll(function(){
            var sidebar_auto_padding = (navTopOffset - sidebarTopOffset) + (sidebarBotOffset - sidebarActionsBotOffset);
            var asd = $sidebarActions.outerHeight();
            var computedStopPoint = $sidebar.outerHeight() - ( $sidebarActions.outerHeight() + $nav.outerHeight() ) - sidebar_auto_padding;

            if ( computedStopPoint <= 1 ) {
                $nav.css({
                    position: 'static',
                    top: 'auto',
                });
                return;
            }
            // If reaches to the end of the sidebar, Stop before hitting actions buttons
            if ( $(window).scrollTop() > computedStopPoint + navTopOffset - header_height - 65 ) {  // 65 Pixels to account for WP Bottom Padding
                $nav.css({
                    position: 'absolute',
                    top: computedStopPoint,
                });
            // Start Sticky Nav
            } else if ( $(window).scrollTop() > navTopOffset - 100 ) {
                $nav.css({
                    position: 'fixed',
                    top: 100,
                    width: 'inherit'
                });
            // Stop Sticky Nav
            } else {
                $nav.css({
                    position: 'static',
                    top: 'auto',
                });
            }

        });

        
        /*---------------------------------------------------------------------------------*/
        /*  Header
        /*---------------------------------------------------------------------------------*/

        $(window).scroll(function(){
            if ( $(window).scrollTop() > headerTopOffset && !$header.hasClass('mka-to-header--sticky') ) {
                $header.addClass('mka-to-header--sticky');
            } else if ( $(window).scrollTop() < headerTopOffset ) {
                $header.removeClass('mka-to-header--sticky');
            }
        });


        /*---------------------------------------------------------------------------------*/
        /*  Demo Purposes Only
        /*---------------------------------------------------------------------------------*/

        $('.mka-to-save-button').on('mk_show_progress', function() {
            TweenLite.to( $(this).find('.mka-button-icon-spinner'), 0, { css: { scale: 1, display: 'inline-block'}, ease: Power1.easeOut, delay: 0 });
        });

        $('.mka-to-save-button').on('mk_hide_progress', function() {
            TweenLite.to( $(this).find('.mka-button-icon-spinner'), 0.1, { css: { scale: 0.1, display: 'none'}, ease: Power1.easeOut, delay: 0 });
        });

        $('.mka-to-save-button').on('mk_show_success', function() {
            TweenLite.to( $(this).find('.mka-button-icon-success'), 0, { css: { 'clip-path': 'circle(43% at 5% 108%)'}, ease: Power1.easeOut, delay: 0 });
            TweenLite.to( $(this).find('.mka-button-icon-success'), 0.6, { css: { 'clip-path': 'circle(130% at 9% -9%)', display: 'inline-block'}, ease: Power1.easeOut, delay: 0.1 });
        });

        $('.mka-to-save-button').on('mk_hide_success', function() {
            TweenLite.to( $(this).find('.mka-button-icon-success'), 0, { css: { display: 'none'}, ease: Power1.easeOut, delay: 0 });
        });

        $('.mka-to-save-button').on('click', function(e) {
            e.preventDefault();
            var $btn = $(this);
            $btn.trigger('mk_hide_success');
            $btn.trigger('mk_show_progress');
            setTimeout( function() {
                $btn.trigger('mk_hide_progress');
                $btn.trigger('mk_show_success');
            }, 2000);
            setTimeout( function() {
                $btn.trigger('mk_hide_success');
            }, 4000);
        });

    }

    // Browser Detection
    (function() {
        var dataBrowser = [
            {string: navigator.userAgent, subString: "Edge", identity: "Edge"},
            {string: navigator.userAgent, subString: "Chrome", identity: "Chrome"},
            {string: navigator.userAgent, subString: "MSIE", identity: "IE"},
            {string: navigator.userAgent, subString: "Trident", identity: "IE"},
            {string: navigator.userAgent, subString: "Firefox", identity: "Firefox"},
            {string: navigator.userAgent, subString: "Safari", identity: "Safari"},
            {string: navigator.userAgent, subString: "Opera", identity: "Opera"}
        ];

        var versionSearchString = null;
        var searchString = function (data) {
            for (var i = 0; i < data.length; i++) {
                var dataString = data[i].string;
                versionSearchString = data[i].subString;

                if (dataString.indexOf(data[i].subString) !== -1) {
                    return data[i].identity;
                }
            }
        };
        
        var searchVersion = function (dataString) {
            var index = dataString.indexOf(versionSearchString);
            if (index === -1) {
                return;
            }

            var rv = dataString.indexOf("rv:");
            if (versionSearchString === "Trident" && rv !== -1) {
                return parseFloat(dataString.substring(rv + 3));
            } else {
                return parseFloat(dataString.substring(index + versionSearchString.length + 1));
            }
        };

        var name = searchString(dataBrowser) || "Other";
        var version = searchVersion(navigator.userAgent) || searchVersion(navigator.appVersion) || "Unknown";

        // Expose for css
        $('html').addClass(name).addClass(name + version);
        
    })();
    
})(jQuery);