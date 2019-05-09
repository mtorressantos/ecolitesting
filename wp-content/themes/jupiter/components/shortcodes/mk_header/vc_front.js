(function($) {
    window.InlineShortcodeView_mk_header = window.InlineShortcodeView.extend({
        render: function() {
            window.InlineShortcodeView_mk_header.__super__.render.call(this);
            this.$el.children().find('.mk-header-holder').css('position', 'relative');
            return this;
        }
    });
})(window.jQuery);