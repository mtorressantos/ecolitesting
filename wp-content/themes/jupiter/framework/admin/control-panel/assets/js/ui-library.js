(function($) {

	"use strict";


	/*---------------------------------------------------------------------------------*/
	/* Checkbox
	/*---------------------------------------------------------------------------------*/

	$(document).on('click', '.mka-checkbox', function() {

		// Cache
		var $ripple = $(this).siblings('.mka-checkbox-skin').find('.mka-checkbox-ripple');
		var $bullet = $(this).siblings('.mka-checkbox-skin').find('.mka-checkbox-bullet');
		var $bullet_inactive = $(this).siblings('.mka-checkbox-skin').find('.mka-checkbox-bullet-inactive');
		var is_checked = $(this).attr('checked') || false;

		// Bullet Animate
		if ( is_checked ) {
			TweenLite.to( $bullet, 0.3, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $bullet_inactive, 0.3, { css: {  opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		} else {
			TweenLite.to( $bullet, 0.3, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $bullet_inactive, 0.3, { css: {  opacity: 1 }, ease: Power1.easeOut, delay: 0 });
		}

		// Background Animate
		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.3, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.3, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.2 });
		
	});


	/*---------------------------------------------------------------------------------*/
	/* Range Slider
	/*---------------------------------------------------------------------------------*/

	$(document).on('mousedown', '.mka-range-bg', function(e){
        if(e.which === 1) {
        	$(this).next('.mka-range-btn').addClass('mka-range-btn--active');
        }
    });

    $(document).on('mouseup', '.mka-range-bg', function(e){
        if(e.which === 1) {
        	$(this).next('.mka-range-btn').removeClass('mka-range-btn--active');
        }
    });

	$('.mka-range-input').rangeslider({

	    polyfill: false,

	    // Default CSS classes
	    rangeClass: 'rangeslider',
	    disabledClass: 'rangeslider--disabled',
	    horizontalClass: 'rangeslider--horizontal',
	    verticalClass: 'rangeslider--vertical',
	    fillClass: 'rangeslider__fill',
	    handleClass: 'rangeslider__handle',

	    // Callback function
	    onSlide: function(position, value) {
	    	this.$element.closest('.mka-wrap').find('.mka-range-val').val(value);
	    },

	    // Callback function
	    onSlideEnd: function(position, value) {
	    }

	});

	
	$(document).on('click', '.mka-range-bg', function() {
		var $range = $(this).closest('.mka-wrap').find('.mka-range');
		var is_range_slider_hidden = $range.css('display') === 'none'  ? true : false;
		$('.mka-range-bg').closest('.mka-wrap').find('.mka-range').hide();
		if ( is_range_slider_hidden ) {
			$range.show();
			is_range_slider_hidden = false;
		} else {
			$range.hide();
			is_range_slider_hidden = true;
		}
	});
	$(document).on('click', function(e) {
		if ( ($(e.target).closest('.mka-range-bg').length == 0) && ($(e.target).closest(".mka-range").length == 0) ) {
			$('.mka-range').hide();
		}
	});

	$(document).on('mouseenter', '.mka-range', function() {
		var $ripple = $(this).find('.rangeslider__ripple-handle');
		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
	});
	$(document).on('mouseleave', '.mka-range', function() {
		var $ripple = $(this).find('.rangeslider__ripple-handle');
		TweenLite.to( $ripple, 0.2, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
	});

	// The code for range slider scale effect when it is pressed was writter inside the Plugin's soruce
	// due to the limitations, The custom codes are flagged and can be found searching for "***Edited***"


	/*---------------------------------------------------------------------------------*/
	/* Toggle
	/*---------------------------------------------------------------------------------*/
	
	$.fn.mkToggle = function() {
		
		var $input = this.find('.mka-toggle-input');
		var $bullet = this.find('.mka-toggle-bullet');
		var $ripple = this.find('.mka-toggle-bullet-ripple');
		var event = new Event('input', {
			'bubbles': true,
			'cancelable': true
		});
		
		if ( $input.val() === 'true' ) {
			toggleOn( $(this) );
		} else {
			toggleOff( $(this) );
		}
		
 		this.on( 'click', function() {
			if ( $input.val() === 'true' ) {
				toggleOff( $(this) );
			} else {
				toggleOn( $(this) );
			}
			
			$input[0].dispatchEvent(event);
		} );
		
		function toggleOff( elm ) {
			TweenLite.to( $bullet, 0.4, { css: { x: '0%' }, ease: Power4.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
			elm.removeClass('mka-toggle--active');
			$input.val('false');
		}
		
		function toggleOn( elm ) {
			TweenLite.to( $bullet, 0.4, { css: { x: '99.5%' }, ease: Power4.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
			TweenLite.to( $ripple, 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
			elm.addClass('mka-toggle--active');
			$input.val('true');
		}
		
		
        return this;
    };
		
	// $(document).on('click', '.mka-toggle', function() {
	// 	var $input = $(this).find('.mka-toggle-input');
	// 	var $bullet = $(this).find('.mka-toggle-bullet');
	// 	var $ripple = $(this).find('.mka-toggle-bullet-ripple');
	// 	if ( $input.val() === 'true' ) {
	// 		TweenLite.to( $bullet, 0.4, { css: { x: '0%' }, ease: Power4.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
	// 		$(this).removeClass('mka-toggle--active');
	// 		$input.val('false');
	// 		
	// 	} else {
	// 		TweenLite.to( $bullet, 0.4, { css: { x: '99.5%' }, ease: Power4.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
	// 		TweenLite.to( $ripple, 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
	// 		$(this).addClass('mka-toggle--active');
	// 		$input.val('true');
	// 	}
	// 
	// });


	/*---------------------------------------------------------------------------------*/
	/* Image Upload
	/*---------------------------------------------------------------------------------*/

	$(document).on('click', '.mka-image-upload-view-btn', function(e) {
		e.preventDefault();
		var $input = $(this).closest('.mka-wrap').find('.mka-textfield');
		var $view = $(this).closest('.mka-wrap').find('.mka-image-upload-view');
		var is_view_open = $view.css('display') !== 'none'  ? true : false;
		if ( $.trim( $input.val() ).length > 0 ) {
			if ( is_view_open ) {
				$view.hide().find('img').remove();
			} else {
				$view.find('img').remove();
				$view.addClass('mka-image-upload-view--loading');
				$view.show();
				$('<img src="' + $input.val() + '" />').load(function() {
					var $img = $(this);
					$view.removeClass('mka-image-upload-view--loading');
					$view.append( $img );
				});
			}
			
		}
	});

	$(document).on('click', function(e) {
		if ( $(e.target).closest('.mka-image-upload-wrap').length == 0 ) {
			$('.mka-image-upload-view').hide();
		}
	});
	
	$( '.wp-color-picker-field' ).wpColorPicker( );


	/*---------------------------------------------------------------------------------*/
	/* Options Box
	/*---------------------------------------------------------------------------------*/

	// $(document).on('click', '.mka-options-item-wrap', function(e) {
	// 	$(this).addClass('current').siblings().removeClass('current');
	// 	var $ripple = $(this).find('.mka-options-item-ripple');
	// 	TweenLite.to( $ripple, 0, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
	// 	TweenLite.to( $ripple, 0.3, { css: { scaleX: 1.2, scaleY: 1.6, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
	// 	TweenLite.to( $ripple, 0.2, { css: { scaleX: 1.4, scaleY: 1.8, opacity: 0 }, ease: Power1.easeOut, delay: 0.2 });
	// });


	/*---------------------------------------------------------------------------------*/
	/* Select Box
	/*---------------------------------------------------------------------------------*/

	$(document).on('click', '.mka-select-wrap .mka-select-box', function(e) {
		var $list = $(this).closest('.mka-wrap').find('.mka-select-list-wrap');
		var is_list_open = $list.css('display') !== 'none'  ? true : false;
		$('.mka-select-wrap .mka-select-box').closest('.mka-wrap').find('.mka-select-list-wrap').hide();
		if ( is_list_open ) {
			$list.hide();
		} else {
			TweenLite.to( $list, 0, { css: { 'transform-origin': 'top', scaleY: 0.1, scaleX: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list, 0.1, { css: { scaleX: 1, opacity: 0.5, display: 'block' }, ease: Power4.easeInOut, delay: 0.0 });
			TweenLite.to( $list, 0.2, { css: { scaleY: 1, opacity: 1, display: 'block' }, ease: Power4.easeInOut, delay: 0 });
			var anim_index = 0,
				$anim_elements = $list.find('.mka-select-list-item').css('opacity', '0');
			var interval = setInterval( function() {
				TweenLite.to( $anim_elements.eq(anim_index), 0, { css: { y: -10 , opacity: 0 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $anim_elements.eq(anim_index), 0.2, { css: { y: 0 , opacity: 1 }, ease: Power4.easeOut, delay: 0 });
				if ( ++anim_index > $anim_elements.size() ) clearInterval(interval);
			}, 25);
		}
	});
	//Modified by @sofyan to prevent conflict with multiselect element dropdown
	$(document).on('click', function(e) {
		if ( $(e.target).hasClass('mka-select-wrap') || $(e.target).closest('.mka-select').length == 0 ) {
			$('.mka-select-wrap .mka-select-box-list-wrap').hide();
		}
	});
	$(document).on('click', '.mka-select-wrap .mka-select-list-item', function(e) {
		var $list = $(this).closest('.mka-wrap').find('.mka-select-list-wrap');
		var $select_box = $(this).closest('.mka-wrap').find('.mka-select-box');
		var $select_box_value = $(this).closest('.mka-wrap').find('.mka-select-box-value');
		var value = $(this).attr('data-value');
		var text = $(this).text();
		$select_box.text( text );
		$select_box_value.val(value).trigger('change');;
		$list.hide();
	});


	/*---------------------------------------------------------------------------------*/
	/* Search Box
	/*---------------------------------------------------------------------------------*/

	$('.mka-search-wrap .mka-search-box').on('keypress', function(e) {
		var $search = $(this).closest('.mka-search');
		var $list = $(this).closest('.mka-wrap').find('.mka-search-list');
		$search.addClass('mka-search--active');
		setTimeout( function() {
			$list.show();
			$search.removeClass('mka-search--active');
		}, 2000);
	});
	$(document).on('click', function(e) {
		if ( $(e.target).hasClass('mka-search-wrap') || $(e.target).closest('.mka-search-wrap').length == 0 ) {
			$('.mka-search-list').hide();
		}
	});


	/*---------------------------------------------------------------------------------*/
	/* Font Field
	/*---------------------------------------------------------------------------------*/

	$(document).on('click', '.mka-font-filter-selected', function(e) {
		var $list = $(this).siblings('.mka-font-filter-list');
		var $list_items = $list.children();
		TweenLite.to( $list, 0, { css: { 'transform-origin': 'top', scaleY: 0.1, scaleX: 0.6, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $list, 0.2, { css: { scaleX: 1, opacity: 0.5, display: 'block' }, ease: Power4.easeInOut, delay: 0.0 });
		TweenLite.to( $list, 0.2, { css: { scaleY: 1, opacity: 1, display: 'block' }, ease: Power4.easeInOut, delay: 0 });

		var anim_index = 0,
			$anim_elements = $list_items;
		$list_items.css('opacity', '0');
		var interval = setInterval( function() {
			TweenLite.to( $anim_elements.eq(anim_index), 0, { css: { y: -10 , opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $anim_elements.eq(anim_index), 0.2, { css: { y: 0 , opacity: 1 }, ease: Power4.easeOut, delay: 0 });
			if ( ++anim_index > $anim_elements.size() ) clearInterval(interval);
		}, 50);
	});
	$(document).on('click', '.mka-font-filter-item', function(e) {
		var $selected = $(this).parent().siblings('.mka-font-filter-selected');
		var value = $(this).attr('data-value');
		var text = $(this).text();
		$selected.text( text ).attr('data-value', value);
		$(this).addClass('mka-font-filter-item--selected').siblings().removeClass('mka-font-filter-item--selected');
		TweenLite.to( $('.mka-font-filter-list'), 0.2, { css: { scaleY: 0.1, scaleX: 0.9, opacity: 0, display: 'none' }, ease: Power4.easeInOut, delay: 0.0 });
		TweenLite.to( $('.mka-font-filter-list').children(), 0.1, { css: {  opacity: 0 }, ease: Power4.easeOut, delay: 0.0 });
	});
	$(document).on('click', function(e) {
		if ( $(e.target).hasClass('mka-font-filter') || $(e.target).closest('.mka-font-filter').length == 0 ) {
			TweenLite.to( $('.mka-font-filter-list'), 0.2, { css: { scaleY: 0.1, scaleX: 0.9, opacity: 0, display: 'none' }, ease: Power4.easeInOut, delay: 0.0 });
			TweenLite.to( $('.mka-font-filter-list').children(), 0.1, { css: {  opacity: 0 }, ease: Power4.easeOut, delay: 0.0 });
		}
	});
	$('.mka-font-field').on('keypress', function(e) {
		var $font_field = $(this).closest('.mka-font');
		var $list = $(this).closest('.mka-wrap').find('.mka-font-list');
		$font_field.addClass('mka-font--active');
		setTimeout( function() {
			$list.show();
			$font_field.removeClass('mka-font--active');
		}, 2000);
	});
	$(document).on('click', function(e) {
		if ( $(e.target).hasClass('mka-font-filter') || $(e.target).closest('.mka-font-filter').length === 1  || $(e.target).closest('.mka-font-wrap').length == 0  ) {
			$('.mka-font-list').hide();
		}
	});


	/*---------------------------------------------------------------------------------*/
	/* Tip Info
	/*---------------------------------------------------------------------------------*/
	
	$(document).on('click', '.mka-tip', function(e) {
		e.preventDefault();
		var $content = $(this).closest('.mka-wrap').find('.mka-tip-content');
		var $arrow = $(this).closest('.mka-wrap').find('.mka-tip-arrow');

		var viewportWidth = $(window).width();
		var offsetWidth = $(this).offset().left;
		var remainingWidth = viewportWidth - offsetWidth;
		var contentWidth = $content.outerWidth();

		if (remainingWidth < contentWidth) {
			var newWidth = contentWidth - remainingWidth;
			$content.css('left', -newWidth);
		}

		$('.mka-tip-content').not($content).hide();
		$('.mka-tip-arrow').not($arrow).hide();

		var $ripple = $(this).closest('.mka-wrap').find('.mka-tip-ripple');
		var is_tip_open = $content.css('display') !== 'none'  ? true : false;
		if ( is_tip_open ) {
			$arrow.hide();
			TweenLite.to( $content, 0.2, { css: { y: 0, opacity: 0, 'clip-path': 'circle(5% at 9% -9%)', display: 'none' }, ease: Power1.easeInOut, delay: 0 });
		} else {
			$arrow.show();
			TweenLite.to( $content, 0, { css: { y: 0, opacity: 0, 'clip-path': 'circle(5% at 9% -9%)' }, ease: Power4.easeOut, delay: 0 });
			TweenLite.to( $content, 0.3, { css: { y: 0, opacity: 1, 'clip-path': 'circle(150% at 9% -9%)', display: 'block' }, ease: Power1.easeInOut, delay: 0.0 });
		}
		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.3, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.2 });
	});
	$(document).on('click', function(e) {
		if (  $(e.target).closest('.mka-tip-wrap').length == 0  ) {
			var $content = $('.mka-tip-content');
			TweenLite.to( $content, 0.2, { css: { y: 0, opacity: 0, 'clip-path': 'circle(5% at 9% -9%)', display: 'none' }, ease: Power1.easeInOut, delay: 0 });
			$('.mka-tip-arrow').hide();
		}
	});


	/*---------------------------------------------------------------------------------*/
	/* Custom List
	/*---------------------------------------------------------------------------------*/

	// Edit
	$(document).on('click', '.mka-clist-edit', function(e) {
		e.preventDefault();

		// Ripple Effect
		var $ripple = $(this).find('.mka-clist-edit-ripple');
		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });

		// Edit Functionality
		var $list_item = $(this).closest('.mka-clist-item');
		var $list_item_inner = $list_item.find('.mka-clist-item-inner');
		var $add_box = $(this).closest('.mka-wrap').find('.mka-clist-addbox-clone').clone(true, true).removeClass('mka-clist-addbox-clone');
		var $add_box_apply_btn = $add_box.find('.mka-clist-item-apply-btn');
		var $add_box_cancel_btn = $add_box.find('.mka-clist-item-cancel-btn');
		var $addbox_selectbox = $add_box.find('.mka-select-box-value');
		var $addbox_url = $add_box.find('.mka-clist-addbox-social-url');
		var $social_title = $list_item.find('.mka-clist-social-title');
		var $url = $list_item.find('.mka-clist-social-url');

		$addbox_url.val( $url.text() );
		$addbox_selectbox.val( $social_title.data('key') );
		$addbox_selectbox.siblings('.mka-select-box').text( $social_title.text() );

		$list_item.css('height', $list_item.outerHeight() + 'px' ).addClass('mka-clist-item--edit');
		$list_item_inner.hide().css('opacity', 0);
		$add_box.appendTo( $list_item ).css('opacity', '0').css('display', 'inline-block');

		TweenLite.to( $add_box, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
		TweenLite.to( $list_item, 0.1, { css: { height: 90 }, ease: Power4.easeOut, delay: 0.1 });
		TweenLite.to( $add_box, 0.1, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
		TweenLite.from( $add_box_apply_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.3	 });
		TweenLite.from( $add_box_cancel_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.3	 });

		TweenLite.to( $list_item, 0, { css: { overflow: 'visible' }, ease: Power1.easeOut, delay: 0.2 });

	});

	// Remove
	$(document).on('click', '.mka-clist-remove', function(e) {
		e.preventDefault();
		var $list = $(this).closest('.mka-wrap').find('.mka-clist-list');

		// Ripplle Effect
		var $ripple = $(this).find('.mka-clist-remove-ripple');
		TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $ripple, 0.2, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });

		// Functionality
		var $list_item = $(this).closest('.mka-clist-item');
		TweenLite.to( $list_item, 0.1, { css: { height: 0 }, ease: Power4.easeOut, delay: 0.2 });
		setTimeout(function() {
			$list_item.remove();

			if ( ! $list.children().length ) {
				$list.addClass('mka-clist-list--empty');
			}
		}, 300);

	});

	// Add
	$(document).on('click', '.mka-clist-add', function(e) {
		e.preventDefault();
		var $add_button = $(this);
		var $add_button_text = $add_button.find('.mka-clist-add-text');
		var $list_item = $(this).closest('.mka-wrap').find('.mka-clist-item-clone').clone(true, true).removeClass('mka-clist-item-clone').addClass('mka-clist-item--edit mka-clist-item--add');
		var $list_item_inner = $list_item.find('.mka-clist-item-inner');
		var $add_box = $(this).closest('.mka-wrap').find('.mka-clist-addbox-clone').clone(true, true).removeClass('mka-clist-addbox-clone');
		var $add_box_apply_btn = $add_box.find('.mka-clist-item-apply-btn');
		var $add_box_cancel_btn = $add_box.find('.mka-clist-item-cancel-btn');
		var $list = $(this).closest('.mka-wrap').find('.mka-clist-list');
		$add_button.css({
			top: $add_button.position().top,
			bottom: 'auto',
		});

		$list_item_inner.hide().css('opacity', 0);
		$list_item.append( $add_box );
		$list_item.appendTo( $list.removeClass('mka-clist-list--empty') ).css('height', '0');
		$add_box.css('opacity', '0').css('display', 'inline-block');

		TweenLite.to( $add_button_text, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $add_button, 0.2, { css: { scale: 0.01, display: 'none' }, ease: Power1.easeOut, delay: 0 });

		TweenLite.to( $add_box, 0, { css: { opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
		TweenLite.to( $list_item, 0.1, { css: { height: 90 }, ease: Power4.easeOut, delay: 0.1 });
		TweenLite.to( $add_box, 0.1, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
		TweenLite.from( $add_box_apply_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
		TweenLite.from( $add_box_cancel_btn, 0.1, { css: { scale: 0.01, opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });

		TweenLite.to( $list_item, 0, { css: { overflow: 'visible' }, ease: Power1.easeOut, delay: 0.2 });

	});

	// Apply
	$(document).on('click', '.mka-clist-item-apply-btn', function(e) {
		e.preventDefault();
		var $add_button = $(this).closest('.mka-wrap').find('.mka-clist-add');
		var $add_button_text = $add_button.find('.mka-clist-add-text');
		var $list_item = $(this).closest('.mka-clist-item');
		var $add_box = $(this).closest('.mka-clist-addbox');
		var $filed_selectbox = $add_box.find('.mka-select-box-value');
		var $filed_url = $add_box.find('.mka-clist-addbox-social-url');
		var $list_item_inner = $list_item.find('.mka-clist-item-inner');
		var $list_item_icon = $list_item.find('.mka-clist-social-icon');
		var $title = $list_item.find('.mka-clist-social-title');
		var $url = $list_item.find('.mka-clist-social-url');
		var $select_list = $list_item.find('.mka-select-list');

		if ( $filed_selectbox.val() == "" ) {
			$filed_selectbox.closest('.mka-select-wrap').addClass('mka-select-wrap--error');
			return;
		}
		if ( $filed_url.val() == "" ) {
			$filed_url.addClass('mka-textfield--error');
			return;
		}
		$title.text( $select_list.find( '[data-value="' + $filed_selectbox.val() + '"]' ).text() );
		$title.data('key', $filed_selectbox.val() );
		$list_item_icon.html( $select_list.find( '[data-value="' + $filed_selectbox.val() + '"]' ).data('icon') );
		$url.text( $filed_url.val() );

		TweenLite.to( $list_item, 0, { css: { overflow: 'hidden' }, ease: Power1.easeOut, delay: 0 });

		TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $list_item, 0.1, { css: { height: 48 }, ease: Power1.easeOut, delay: 0 });
		TweenLite.to( $list_item_inner, 0.1, { css: { display: 'inline-block', opacity: 1 }, ease: Power1.easeOut, delay: 0.1 });

		$add_button.css({
			top: 'auto',
			bottom: '',
		});
		TweenLite.to( $add_button_text, 0, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
		TweenLite.to( $add_button, 0.2, { css: { scale: 1, display: 'block' }, ease: Power1.easeOut, delay: 0.2 });

		setTimeout( function() {
			$add_box.remove();
			$list_item.removeClass('mka-clist-item--edit mka-clist-item--add');
		}, 500);

	});

	// Cancel
	$(document).on('click', '.mka-clist-item-cancel-btn', function(e) {
		e.preventDefault();
		var $add_button = $(this).closest('.mka-wrap').find('.mka-clist-add');
		var $add_button_text = $add_button.find('.mka-clist-add-text');
		var $list_item = $(this).closest('.mka-clist-item');
		var $add_box = $(this).closest('.mka-clist-addbox');
		var $list_item_inner = $list_item.find('.mka-clist-item-inner');
		var $list = $(this).closest('.mka-wrap').find('.mka-clist-list');

		TweenLite.to( $list_item, 0, { css: { overflow: 'hidden' }, ease: Power1.easeOut, delay: 0 });

		$add_button.css({
			top: 'auto',
			bottom: '',
		});
		TweenLite.to( $add_button_text, 0, { css: { opacity: 1 }, ease: Power1.easeOut, delay: 0.2 });
		TweenLite.to( $add_button, 0.2, { css: { scale: 1, display: 'block' }, ease: Power1.easeOut, delay: 0.2 });

		// If cancel is in Add New
		if ( $(this).closest('.mka-clist-item--add').length ) {
			
			TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list_item, 0.1, { css: { height: 0, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
			setTimeout( function() {
				$add_box.remove();
				$list_item.remove();

				if ( !$list.children().length ) {
					$list.addClass('mka-clist-list--empty');
				}
			}, 200);

		// If cancel is in Edit
		} else {

			TweenLite.to( $add_box, 0.1, { css: { opacity: 0, display: 'none' }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list_item, 0.1, { css: { height: 48 }, ease: Power1.easeOut, delay: 0 });
			TweenLite.to( $list_item_inner, 0.1, { css: { display: 'inline-block', opacity: 1 }, ease: Power1.easeOut, delay: 0.1 });
			setTimeout( function() {
				$add_box.remove();
				$list_item.removeClass('mka-clist-item--edit');
			}, 500);

		}

	});



	/*---------------------------------------------------------------------------------*/
	/*  Modals
	/*---------------------------------------------------------------------------------*/

	$('.mka-modal-warning-btn').on('click', function(e) {
		e.preventDefault();
		$('body').mk_modal({
			title:  'This is a warning title',
	        text: 'This is a simple warning message.',
	        type: 'warning',
	        showCancelButton: false,
	        showConfirmButton: true,
	        showCloseButton: false,
	        showLearnmoreButton: false,
	        showProgress: false,
	        confirmButtonText: 'OK',
	        cancelButtonText: 'Cancel',
			onComplete: function() {
				console.log('Completed');
			}
		});
	});

	$('.mka-modal-error-btn').on('click', function(e) {
		e.preventDefault();
		$('body').mk_modal({
			title:  'This is a error title',
	        text: 'Try to close the modal by clicking on overlay, I dare you!',
	        type: 'error',
	        showCancelButton: true,
	        showConfirmButton: true,
	        showCloseButton: true,
	        showLearnmoreButton: true,
	        closeOnOutsideClick: false,
	        showProgress: false,
	        learnmoreButton: '#',
	        confirmButtonText: 'OK',
	        cancelButtonText: 'Cancel',
			onComplete: function() {
				console.log('Completed');
			}
		});
	});

	$('.mka-modal-success-btn').on('click', function(e) {
		e.preventDefault();
		$('body').mk_modal({
			title:  'This is a success title',
	        text: 'Try to scroll up and down, I dare you!',
	        type: 'success',
	        showCancelButton: true,
	        showConfirmButton: true,
	        showCloseButton: true,
	        showLearnmoreButton: false,
	        showProgress: false,
	        confirmButtonText: 'OK',
	        cancelButtonText: 'Cancel',
			onComplete: function() {
				console.log('Completed');
			}
		});
	});


	// For demo purposes onlu
	var customHtml = '';
    customHtml += '<div>';
	    customHtml += '<h3 class="mka-modal-title">Installing in progress</h3>';
	    customHtml += '<div class="mka-modal-desc">';
	        customHtml += '<ul class="mka-modal-step-list">';
	            customHtml += '<li>This is first step <span>- Downloading</span></li>';
	            customHtml += '<li>This is second step</li>';
	            customHtml += '<li>This is third step</li>';
	        customHtml += '</ul>';
	    customHtml += '</div>';
    customHtml += '</div>';

    

	$('.mka-modal-multistep-btn').on('click', function(e) {
		e.preventDefault();
		var modal = mk_modal({
			title:  'Multistep Demo',
	        text: 'By clicking OK you will see a demo of Multistep Modal.<br>Pssst! You can not close modal when operating imprtant stuff, It\'s all under your control!',
	        type: 'success',
	        showCancelButton: true,
	        showConfirmButton: true,
	        showCloseButton: true,
	        showLearnmoreButton: false,
	        showProgress: false,
	        confirmButtonText: 'OK',
	        cancelButtonText: 'Cancel',
			closeOnConfirm: false,
			onConfirm: function() {
				
				var $customHtml = $(customHtml);

				mk_modal({
	        		html: $customHtml,
	        		showProgress: true,
	        		progress: '10%',
	        		showCloseButton: false,
	        		closeOnOutsideClick: false,
				});

				setTimeout( function() {
					$customHtml.find('li:first-child').addClass('mka-modal-step--done').find('span').remove();
					mk_modal.update({
						progress: '40%',
					});
				}, 2000);

				setTimeout( function() {
					$customHtml.find('li:nth-child(2)').addClass('mka-modal-step--done');
					mk_modal.update({
						progress: '80%',
					});
				}, 4000);

				setTimeout( function() {
					$customHtml.find('li:nth-child(3)').addClass('mka-modal-step--done');
					mk_modal.update({
						progress: '100%',
					});
				}, 6000);

				setTimeout( function() {
					mk_modal({
		        		title:  'Done',
				        text: 'Click OK to close the modal window.',
				        type: 'success',
				        showCancelButton: false,
				        showConfirmButton: true,
				        showCloseButton: true,
				        showLearnmoreButton: false,
				        showProgress: false,
				        closeOnOutsideClick: false,
					});
				}, 8000);

			},
			onCancel: function() {
				console.log('Cancel');
			},
			onClose: function() {
				console.log('Close');
			}
		});
		modal.update({
			progress: '60%',
		});
	});

	$('.mka-modal-callback-btn').on('click', function(e) {
		e.preventDefault();
		var modal = mk_modal({
			title:  'Callback',
	        text: 'By clicking on confirm button you get "Happy" alert and by clicking on cancel you get "Sad" alert.',
	        type: 'warning',
	        showCancelButton: true,
	        showConfirmButton: true,
	        showCloseButton: false,
	        showLearnmoreButton: false,
	        showProgress: false,
	        confirmButtonText: 'Confirm',
	        cancelButtonText: 'Cancel',
			closeOnConfirm: true,
			onConfirm: function() {
				alert('Happy');
			},
			onCancel: function() {
				alert('Sad');
			},
		});
		modal.update({
			progress: '60%',
		});
	});

	$('.mka-modal-indefinite-btn').on('click', function(e) {
		e.preventDefault();
		var modal = mk_modal({
			title:  'Indefinite Progress Bar',
	        text: 'Look at the progress bar, Focus on my voice, Look at the progress bar, Now you are hypnotized!',
	        type: '',
	        showCancelButton: false,
	        showConfirmButton: true,
	        showCloseButton: false,
	        showLearnmoreButton: false,
	        showProgress: true,
	        indefiniteProgress: true,
		});
	});

	$('.mka-color-picker').wpColorPicker();

})(jQuery);


/*---------------------------------------------------------------------------------*/
/* Multiselect
/*---------------------------------------------------------------------------------*/
(function($) {
    $.fn.mk_multiselect = function(options) {
        // set some defaults
        var defaults = {
            delay: 500,
            keyword_length: 3,
            is_tag: false,
            ajax: {
                url: ''
            },
            filter_data: function(data, keyword) {
                return data;
            },
            template: function(css_class) {
				return '<div class="mka-multiselect ' + css_class.wrap + '">\
                    <div class="mka-multiselect-top-box ' + css_class.selected_box + '">\
                    	<div class="' + css_class.selected_list_wrap + '">\
                        	<ul class="mka-multiselect-selected-list ' + css_class.selected_list + '"></ul>\
                        </div>\
                    </div>\
                    <div class="mka-multiselect-bottom-box">\
                        <div class="mka-multiselect-bottom-box-left ' + css_class.unselected_box + '">\
                            <div class="mka-search ' + css_class.search_wrap + '">\
                                <input type="text" class="mka-search-box ' + css_class.search_input + '" placeholder="Type to choose an element">\
                                <span class="mka-search-icon-wrap">\
                                    <span class="mka-search-icon"></span>\
                                    <div class="mka-bubbling">\
                                        <span class="mka-bubbling-1"></span>\
                                        <span class="mka-bubbling-2"></span>\
                                        <span class="mka-bubbling-3"></span>\
                                    </div>\
                                </span>\
                            </div>\
                            <div class="mka-select-box-list-wrap mka-select-list-wrap mka-multiselect-unselected-list-wrap ' + css_class.unselected_list_wrap + '">\
                                <ul class="mka-select-list mka-multiselect-unselected-list ' + css_class.unselected_list + '"></ul>\
                            </div>\
                        </div>\
                        <div class="mka-multiselect-bottom-box-right ' + css_class.msg_wrap + '"></div>\
                    </div>\
                </div>';
            },
            template_item_selected: function(item, css_class) {
                return '<li class="mka-multiselect-selected-item"><span class="mka-multiselect-selected-item-close ' + css_class.close + '"></span>' + item.label + '</li>';
            },
            template_item_unselected: function(item, css_class) {
                return '<li class="mka-select-list-item mka-multiselect-unselected-item">' + item.label + '</li>';
            },
            template_msg_error: function(msg, css_class) {
                return '<span class="mka-multiselect-msg-error">' + msg +'</span>';
            },
            template_msg_success: function(msg, css_class) {
                return '<span class="mka-multiselect-msg-success">' + msg + '</span>';
            },
            onChange: null
        };
        var css_class = {
            wrap: 'mka-multiselect--wrap',
            selected_box: 'mka-multiselect-selected--box',
            selected_list_wrap: 'mka-multiselect-selected--list-wrap',
            selected_list: 'mka-multiselect-selected--list',
            selected_item: 'mka-multiselect-selected--item',
            unselected_box: 'mka-multiselect-unselected--box',
            unselected_list_wrap: 'mka-multiselect-unselected--list-wrap',
            unselected_list: 'mka-multiselect-unselected--list',
            unselected_item: 'mka-multiselect-unselected--item',
            search_wrap: 'mka-multiselect-search',
            search_input: 'mka-multiselect-search--input',
            search_input_animated: 'mka-search--active',
            search_input_is_tag: 'mka-multiselect-search--input-is-tag',
            search_input_is_error: 'mka-multiselect-search--input-is-error',
            search_input_is_success: 'mka-multiselect-search--input-is-success',
            msg_wrap: 'mka-multiselect-msg',
            close: 'mka-multiselect-selected--close',
        }
        var options = $.extend({}, defaults, options);
        var element = $(this);
        var items = [];
        var init = function() {
            on_load();
            on_focusin();
            on_focusout();
            on_keyup();
            on_change();
        };
        var on_load = function() {
            var element_parent = element.parent();
            var element_siblings = element_parent.find(' > *');
            var html = $(options.template(css_class));
            element_siblings.each(function(index, el) {
                if ($(el).is(element)) {
                    html.insertAfter($(this));
                    element.css('display', 'none').prependTo(html);
                }
            });
            render_selected_list(populate_data());
        }
        var on_focusin = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.search_input).focusin(function(e) {
            	$('.' + css_class.wrap).each(function(index, el) {
                    if (!$(el).find(e.currentTarget).length) {
                    	$(el).find('.' + css_class.unselected_list_wrap).hide();
                    }else{
                    	search_data($(e.currentTarget).val(), true);
                    }
                });
                msg_hide();
            });
        }
        var on_focusout = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.search_input).focusout(function(e) {
                msg_hide();
            });
        }
        var on_keyup = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.search_input).keyup(_.debounce(function(e) {
                msg_hide();
                var keyword = $(e.currentTarget).val();
                switch (e.keyCode) {
                    case 27:
                        hide_unselected_list();
                        break;
                    case 13:
                        if (keyword.length >= options.keyword_length && options.is_tag && $(e.currentTarget).hasClass(css_class.search_input_is_tag) && !is_selected(keyword)) {
                            render_unselected_item({
                                value: keyword,
                                label: keyword,
                                selected: true
                            }).click();
                            $(e.currentTarget).removeClass(css_class.search_input_is_tag).val('');
                        } else {
                            search_data(keyword, true);
                        }
                        break;
                    default:
                        search_data(keyword, true);
                        break;
                }
            }, options.delay));
        }
        var on_change = function() {
            element.on('change', function(e) {
                var onChange = options.onChange;
                if (typeof onChange === 'function') {
                    onChange.call(element);
                }
            });
        }
        var populate_data = function() {
            var data = [];
            element.find('option').each(function(index, option) {
                data.push({
                    value: $(option).val(),
                    label: $(option).text(),
                    selected: $(option).is(':selected')
                });
            });
            return data;
        }
        var search_data = function(keyword, force) {
            if (!force && keyword.length < options.keyword_length) return; //wasn't enter, not > 2 char
            animation_start();
            if (typeof options.ajax.url === "undefined" || !options.ajax.url.length) {
                process_data(populate_data(), keyword);
            } else {
                $.ajax($.extend(options.ajax, {
                    data: {
                        keyword: keyword
                    },
                    beforeSend: function(jqXHR, settings) {
                        
                    },
                    success: function(data, textStatus, jqXHR) {
                        process_data(data, keyword);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        animation_stop();
                        if (errorThrown.length) {
                            msg_show_error('ERROR: ' + errorThrown);
                        } else {
                            switch (jqXHR.status) {
                                case 0:
                                    msg_show_error('ERROR: Requested URL is not reachable');
                                    break;
                                case 404:
                                    msg_show_error('ERROR: Requested URL is not found');
                                    break;
                                default:
                                    msg_show_error('ERROR ' + jqXHR.status);
                                    break;
                            }
                        }
                    }
                }));
            }
        }
        var process_data = function(data, keyword) {
            data = options.filter_data(data, keyword);
            var results = [];
            if (Array.isArray(data)) {
                for (var i = 0; i < data.length; i++) {
                    var item = data[i];
                    if (typeof item === "object") {
                        if (item.hasOwnProperty('value') && item.hasOwnProperty('label')) {
                            results.push({
                                value: item.value,
                                label: item.label,
                                selected: element.find('option[value="' + item.value + '"]').is(':selected')
                            });
                        } else if (item.hasOwnProperty('value')) {
                            results.push({
                                value: item.value,
                                label: item.value,
                                selected: element.find('option[value="' + item.value + '"]').is(':selected')
                            });
                        } else if (item.hasOwnProperty('label')) {
                            results.push({
                                value: item.label,
                                label: item.label,
                                selected: element.find('option[value="' + item.label + '"]').is(':selected')
                            });
                        } else {
                            results.push({
                                value: item[Object.keys(item)[0]],
                                label: item[Object.keys(item)[0]],
                                selected: element.find('option[value="' + item[Object.keys(item)[0]] + '"]').is(':selected')
                            });
                        }
                    } else if (typeof item === "string") {
                        results.push({
                            value: item,
                            label: item,
                            selected: element.find('option[value="' + item + '"]').is(':selected')
                        });
                    }
                }
            }
            var unselected_items = [];
            if (results.length) {
                var regExp = new RegExp(keyword.replace(/\\/g, '&#92;'), "gi");
                for (var i = 0; i < results.length; i++) {
                    if (String(results[i].value).match(regExp) || String(results[i].label).match(regExp)) {
                        unselected_items.push(results[i]);
                    }
                }
            }
            var search_input = element.closest('.' + css_class.wrap).find('.' + css_class.search_input).removeClass(css_class.search_input_is_tag);
            if (!render_unselected_list(unselected_items)) {
                if (keyword.length >= options.keyword_length && options.is_tag && !is_selected(keyword)) {
                    msg_show_error('No element could be found. Press "ENTER" key to add new entry.');
                    search_input.addClass(css_class.search_input_is_tag);
                } else {
                    msg_show_error('No element could be found. Try a different keyword.');
                }
            }
            animation_stop();
        }
        var is_selected = function(value) {
            return element.find('option[value="' + value + '"]').is(':selected');
        }
        var render_selected_list = function(items) {
            var selectedList = element.closest('.' + css_class.wrap).find('.' + css_class.selected_list);
            $.each(items, function(index, item) {
                if (typeof item.selected === 'boolean' && item.selected) {
                    render_selected_item(item).appendTo(selectedList);
                }
            });
        }
        var render_unselected_list = function(items) {
            var unselectedList = element.closest('.' + css_class.wrap).find('.' + css_class.unselected_list).empty();
            var show_list = false;
            $.each(items, function(index, item) {
                render_unselected_item(item).appendTo(unselectedList);
                if (!item.selected) {
                    show_list = true;
                }
            });
            if (show_list) {
                show_unselected_list();
            } else {
                hide_unselected_list();
            }
            return show_list;
        }
        var render_selected_item = function(item) {
            var selected_item = $(options.template_item_selected(item, css_class)).attr({
                'data-value': item.value,
                'data-label': item.label
            }).addClass(css_class.selected_item);
            var close_item = selected_item.find('.' + css_class.close);
            close_item.bind('click', function(e) {
                unselect_item(selected_item);
            });
            return selected_item;
        }
        var render_unselected_item = function(item) {
            var unselected_item = $(options.template_item_unselected(item, css_class)).attr({
                'data-value': item.value,
                'data-label': item.label
            }).addClass(css_class.unselected_item).bind('click', function(e) {
                select_item($(this));
            });
            if (typeof item.selected === 'boolean' && item.selected) {
                unselected_item.hide();
            }
            return unselected_item;
        }
        var select_item = function(item) {
            item.fadeOut(100, function() {
                var selectedList = element.closest('.' + css_class.wrap).find('.' + css_class.selected_list);
                if (!is_selected(item.data('value'))) {
                    render_selected_item(item.data()).appendTo(selectedList);
                }
            }).promise().done(function() {
                var element_option = element.find('[value="' + item.data('value') + '"]');
                if (element_option.length) {
                    element_option.attr('selected', 'selected');
                } else {
                    $('<option></option>').val(item.data('value')).text(item.data('label')).attr('selected', 'selected').appendTo(element);
                }
                element.change();
                if (!item.siblings(':visible').length) {
                    hide_unselected_list();
                }
            });
        }
        var unselect_item = function(item) {
            item.fadeOut(100, function() {
                element.find('[value="' + item.data('value') + '"]').removeAttr('selected');
                element.change();
                hide_unselected_list();
            }).promise().done(function() {
                item.remove();
            });
        }
        var show_unselected_list = function() {
            var unselectedWrap = element.closest('.' + css_class.wrap).find('.' + css_class.unselected_list_wrap + ':hidden');
            if (!unselectedWrap.length) return;
            TweenLite.to(unselectedWrap, 0, {
                css: {
                    'transform-origin': 'top',
                    scaleY: 0.1,
                    scaleX: 1,
                    opacity: 0
                },
                ease: Power1.easeOut,
                delay: 0
            });
            TweenLite.to(unselectedWrap, 0.1, {
                css: {
                    scaleX: 1,
                    opacity: 0.5,
                    display: 'block'
                },
                ease: Power4.easeInOut,
                delay: 0.0
            });
            TweenLite.to(unselectedWrap, 0.2, {
                css: {
                    scaleY: 1,
                    opacity: 1,
                    display: 'block'
                },
                ease: Power4.easeInOut,
                delay: 0
            });
            var anim_index = 0,
                $anim_elements = unselectedWrap.find('.' + css_class.unselected_item).css('opacity', '0');
            var interval = setInterval(function() {
                TweenLite.to($anim_elements.eq(anim_index), 0, {
                    css: {
                        y: -10,
                        opacity: 0
                    },
                    ease: Power1.easeOut,
                    delay: 0
                });
                TweenLite.to($anim_elements.eq(anim_index), 0.2, {
                    css: {
                        y: 0,
                        opacity: 1
                    },
                    ease: Power4.easeOut,
                    delay: 0
                });
                if (++anim_index > $anim_elements.size()) clearInterval(interval);
            }, 25);
            var showed = true;
            $(document).bind("click", function(e) {
                if ($(e.target).closest('.' + css_class.unselected_box).length === 0 && $(e.target).closest('.' + css_class.selected_item).length === 0) {
                    hide_unselected_list();
                    showed = false;
                }
                if (!showed) {
                    $(this).unbind(e);
                }
            });
        }
        var hide_unselected_list = function(wrapper) {
            if (typeof wrapper === "undefined") {
                wrapper = element.closest('.' + css_class.wrap).find('.' + css_class.unselected_list_wrap + ':visible');
            }
            wrapper.hide();
        }
        var animation_start = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.search_wrap).addClass(css_class.search_input_animated);
        }
        var animation_stop = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.search_wrap).removeClass(css_class.search_input_animated);
        }
        var msg_show_error = function(msg) {
            element.closest('.' + css_class.wrap).find('.' + css_class.msg_wrap).html(options.template_msg_error(msg, css_class));
        	element.closest('.' + css_class.wrap).find('.' + css_class.search_input).removeClass(css_class.search_input_is_success).addClass(css_class.search_input_is_error);
        }
        var msg_show_success = function(msg) {
            element.closest('.' + css_class.wrap).find('.' + css_class.msg_wrap).html(options.template_msg_success(msg, css_class));
        	element.closest('.' + css_class.wrap).find('.' + css_class.search_input).removeClass(css_class.search_input_is_error).addClass(css_class.search_input_is_success);
        }
        var msg_hide = function() {
            element.closest('.' + css_class.wrap).find('.' + css_class.msg_wrap).empty();
            element.closest('.' + css_class.wrap).find('.' + css_class.search_input).removeClass(css_class.search_input_is_error).removeClass(css_class.search_input_is_success);
        }
        init();
    };
})(jQuery);
(function($) {

    $('.mka-multiselect--select-only-ajax').mk_multiselect({
        ajax: {
            url: 'https://jsonplaceholder.typicode.com/users'
        },
        filter_data: function(data, keyword) {
            var filtered_data = [];
            for (var i = 0; i < data.length; i++) {
                filtered_data.push({
                    value: data[i].id,
                    label: data[i].name
                });
            }
            return filtered_data;
        }
    });

    $('.mka-multiselect--select-only-no-ajax').mk_multiselect();

    $('.mka-multiselect--entry-adder-ajax').mk_multiselect({
        ajax: {
            url: 'https://jsonplaceholder.typicode.com/users'
        },
        filter_data: function(data, keyword) {
            var filtered_data = [];
            for (var i = 0; i < data.length; i++) {
                filtered_data.push({
                    value: data[i].id,
                    label: data[i].name
                });
            }
            return filtered_data;
        },
        is_tag: true
    });

    $('.mka-multiselect--entry-adder-no-ajax').mk_multiselect({
        is_tag: true
    });

})(jQuery);
