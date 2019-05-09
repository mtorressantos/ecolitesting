jQuery(function ($) {

	var api = wp.customize;

	// When Customizer has finished loading
	api.bind('ready', function () {

		// Save the values into hidden field on each change
		$('.mk-element-radio a').on('click', function (e) {
			e.preventDefault();
			$(this).siblings().removeClass('mk-selected');
			$(this).addClass('mk-selected');
			var $radio = $(this).closest('.mk-element-radio');
			var $input = $radio.siblings('.mk-radio-value');
			$input.val($radio.find('.mk-selected').attr('data-value'));
			$input.trigger('change'); // Magic to remind Custmizer this has changed, So don't forget to save it!
		});

		// Load and Display the values on the fields
		$('.mk-element-radio a').on('mk_load_value', function () {
			var radio_val = $(this).closest('.mk-element-radio').siblings('.mk-radio-value').val();
			if ($(this).attr('data-value') === radio_val) {
				$(this).addClass('mk-selected');
			}
		});

		$('.mk-element-radio a').trigger('mk_load_value');

	});


});