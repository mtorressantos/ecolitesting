(function($) {
    'use strict';
    // Move header to last wrapper of page section if its added into page section. 
    $('.js-header-shortcode').each(function() {
        var $this = $(this),
            $parent_page_section = $this.parents('.mk-page-section'),
            $parent_row = $this.parents('.js-master-row'),
            $is_inside = $parent_page_section.attr('id');
        if ($is_inside) {
            $this.detach().appendTo($parent_page_section);
        }
        /* Its needed for sub menus to appear */
        $parent_page_section.css({
            'overflow': 'visible'
        });
        $parent_row.css({
            'overflow': 'visible'
        });
        /* Fix: AM-1918 Add z-index to the header shortcode parent element so that menu is visible on responsive screen when menu icon is clicked */
        $this.parent().css('z-index', 99999);
    });
})(jQuery);