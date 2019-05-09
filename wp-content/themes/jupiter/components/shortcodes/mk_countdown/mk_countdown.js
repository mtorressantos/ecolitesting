(function($) {
    'use strict';
    // Update countdown style in VC FEE - AM-2684.
    $(window).on('vc_reload', function() {
        $('.mk-event-countdown-ul').each(function() {
            if ($(this).width() < 750) {
                $(this).addClass('mk-event-countdown-ul-block');
            } else {
                $(this).removeClass('mk-event-countdown-ul-block');
            }
        });
    });
})(jQuery);