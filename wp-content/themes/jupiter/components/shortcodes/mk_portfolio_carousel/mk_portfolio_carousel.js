jQuery(document).ready(function($) {
    'use strict';
    /**
     * Get dynamic width of items for passing in `flexslider()`.
     * @param  string style      Style of the carousel, classic/modern
     * @param  integer showItems Number of items to show
     * @param  integer id        ID of the carousel
     * @return interger          The width for items
     */
    function get_item_width(style, showItems, id) {
        var item_width;
        if (style == "classic") {
            item_width = 275;
            items_to_show = 4;
        } else {
            var screen_width = $('#portfolio-carousel-' + id).width(),
                items_to_show = showItems;
            if (screen_width >= 1100) {
                item_width = screen_width / items_to_show;
            } else if (screen_width <= 1200 && screen_width >= 800) {
                item_width = screen_width / 3;
            } else if (screen_width <= 800 && screen_width >= 540) {
                item_width = screen_width / 2;
            } else {
                item_width = screen_width;
            }
        }
        return item_width;
    }
    jQuery(window).on("load vc_reload", function() {
        MK.core.loadDependencies([MK.core.path.plugins + 'jquery.flexslider.js'], function() {

            $('.portfolio-carousel .mk-flexslider').each(function() {
            	var $this = $(this);
            	var $pauseOnHover = $this.attr('data-pauseOnHover') == "true" ? true : false;

                $this.flexslider({
                    selector: ".mk-flex-slides > li",
                    slideshow: !isTest,
                    animation: "slide",
                    slideshowSpeed: parseInt($this.attr('data-slideshowSpeed')),
                    animationSpeed: parseInt($this.attr('data-animationSpeed')),
                    pauseOnHover: $pauseOnHover,
                    controlNav: false,
                    smoothHeight: false,
                    useCSS: false,
                    directionNav: $this.data('directionNav'),
                    prevText: "",
                    nextText: "",
                    itemWidth: get_item_width($this.data('style'), $this.data('showItems'), $this.data('id')),
                    itemMargin: 0,
                    maxItems: ($this.data('style') === 'modern') ? $this.data('showItems') : 4,
                    minItems: 1,
                    move: 1
                });
            }); // End each().
        }); // End loadDependencies().
    }); // End on().
});