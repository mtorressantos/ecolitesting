(function($) {

	"use strict";

	$('.mka-po-sidebar-tabs').on('click', function(){
		var hash = $(this).attr('href');
		$('.mka-po-pane-box').hide();

		$(hash).show();

		$('.mka-po-sidebar-list-items').removeClass('mka-is-active');
		$(this).parent().addClass('mka-is-active');

		return false;
	});

})(jQuery);