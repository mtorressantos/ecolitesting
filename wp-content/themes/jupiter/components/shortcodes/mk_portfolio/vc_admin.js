(function($) {
    'use strict';
    
    $(document).on('ajaxComplete.mk_portfolio_lazyload_option', function(e) {
        if ( theme_backend_localized_data.mk_global_lazyload == 'true' ) {
            var $lazyload = $('.vc_shortcode-param').filter(function(index) {
                return $(this).attr('data-vc-shortcode-param-name') === 'lazyload';
            });
            $lazyload.hide();
        } else {
            var $disable_lazyload = $('.vc_shortcode-param').filter(function(index) {
                return $(this).attr('data-vc-shortcode-param-name') === 'disable_lazyload';
            });
            $disable_lazyload.hide();
        }
    });

})(jQuery);