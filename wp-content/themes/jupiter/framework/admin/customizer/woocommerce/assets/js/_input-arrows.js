/**
 * Add custom arrows to number input.
 * Credit: https://goo.gl/25ohzX
 *
 * @since 5.9.4
 */

jQuery(document).ready(function( $ ) {

	var mkQuantityInput = function() {

		$('<div class="quantity-nav">\
			<div class="quantity-button quantity-up"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="10px" height="6px" fill="#a7adb8" viewBox="0 0 13 7.5" style="enable-background:new 0 0 13 7.5;"><path d="M5.8,0.3L0.3,5.8c-0.4,0.4-0.4,1,0,1.4s1,0.4,1.4,0l4.8-4.8l4.8,4.8c0.4,0.4,1,0.4,1.4,0C12.9,7,13,6.8,13,6.5S12.9,6,12.7,5.8L7.2,0.3C6.8-0.1,6.2-0.1,5.8,0.3z"/></svg></div>\
			<div class="quantity-button quantity-down"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="10px" height="6px" fill="#a7adb8" viewBox="0 0 13 7.5" style="enable-background:new 0 0 13 7.5;"><path d="M7.2,7.2l5.5-5.5c0.4-0.4,0.4-1,0-1.4s-1-0.4-1.4,0L6.5,5.1L1.7,0.3c-0.4-0.4-1-0.4-1.4,0C0.1,0.5,0,0.7,0,1s0.1,0.5,0.3,0.7l5.5,5.5C6.2,7.6,6.8,7.6,7.2,7.2z"/></svg></div>\
		</div>').appendTo('.mk-product-quantity');

		$('.quantity').each(function() {
			var spinner = jQuery(this),
			input = spinner.find('input[type="number"]'),
			btnUp = spinner.find('.quantity-up'),
			btnDown = spinner.find('.quantity-down'),
			min = input.attr('min'),
			max = input.attr('max');
			
			btnUp.click(function() {
				var oldValue = parseFloat(input.val());

				if ( oldValue >= max && max != '' ) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue + 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});
			
			btnDown.click(function() {
				var oldValue = parseFloat(input.val());
				if (oldValue <= min) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue - 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});
			
		});

	}

	mkQuantityInput();

	$( document.body ).on( 'updated_cart_totals', function() {
		mkQuantityInput();
	} );

	MK.utils.eventManager.subscribe('quickViewOpen', function() {
		mkQuantityInput();
	});
	
});