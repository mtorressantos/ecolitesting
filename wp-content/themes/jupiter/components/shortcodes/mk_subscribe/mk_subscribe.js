(function($) {
	'use strict';

	$(".mk-subscribe").each(function() {
		var $this = $(this);
		
		$this.find('.mk-subscribe--form').submit(function(e){
			$this.addClass('form-in-progress');
			e.preventDefault();
			$.ajax({
				url: MK.core.path.ajaxUrl,
				type: "POST",
				data: {
					action: "mk_ajax_subscribe",
					email: $this.find(".mk-subscribe--email").val(),
					list_id: $this.find(".mk-subscribe--list-id").val(),
					optin: $this.find(".mk-subscribe--optin").val()
				},
				success: function (response) {
					$this.removeClass('form-in-progress');
					var data = $.parseJSON(response),
						$messaage_box = $this.find(".mk-subscribe--message");

					$messaage_box.html(data.message);

					if(data.action_status == true) {
						$messaage_box.addClass('success');
					} else {
						$messaage_box.addClass('error');
					}

					$this.find(".mk-subscribe--email").val('');

				}
			});
		});
	});

}(jQuery));