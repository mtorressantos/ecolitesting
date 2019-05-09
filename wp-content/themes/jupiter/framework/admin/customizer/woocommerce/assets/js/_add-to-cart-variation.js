(function ($) {
    $(document).ready(function () {
        var variations_form = $('form.variations_form');
        variations_form.on('woocommerce_variation_has_changed', function (e) {
            var button = variations_form.find('.single_add_to_cart_button');
            $('.mk-single-product-badges').find('.onsale').hide();
            if (button.is('.wc-variation-is-unavailable')) {
                $('.mk-single-product-badges').find('.mk-out-of-stock').show();
            } else {
                $('.mk-single-product-badges').find('.mk-out-of-stock').hide();
                if (variations_form.find('.price del').length) {
                    $('.mk-single-product-badges').find('.onsale').show();
                }
            }
        });
    });
})(jQuery)