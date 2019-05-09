jQuery(document).ready(function( $ ) {

	/**
	 * Align quantity input based on variation inputs.
	 *
	 * @todo Improve logic to do to also align variation inputs based on quantity input.
	 * @since 5.9.4
	 */
	var verticalStatus = false;
	var slidesToShow = 6;

	if ( $( '.single-product .product' ).hasClass( 'mk-product-orientation-vertical' ) ) {
		verticalStatus = true;
		slidesToShow = 3;
	}

	$( '.flex-control-nav' ).slick({
		infinite: false,
		slidesToShow: slidesToShow,
		slidesToScroll: 1,
		vertical: verticalStatus,
		prevArrow: '<button class="slick-prev" aria-label="Previous" type="button">\
		<svg fill="#333333" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\
			 width="7.2px" height="12px" viewBox="0 0 7.2 12" style="enable-background:new 0 0 7.2 12;" xml:space="preserve">\
		<path class="st0" d="M2.4,6l4.5-4.3c0.4-0.4,0.4-1,0-1.4c-0.4-0.4-1-0.4-1.4,0l-5.2,5C0.1,5.5,0,5.7,0,6s0.1,0.5,0.3,0.7l5.2,5\
			C5.7,11.9,6,12,6.2,12c0.3,0,0.5-0.1,0.7-0.3c0.4-0.4,0.4-1,0-1.4L2.4,6z"/>\
		</svg>\
		</button>',
		nextArrow: '<button class="slick-next" aria-label="Next" type="button">\
			<svg fill="#333333" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="7.2px" height="12px" viewBox="0 0 7.2 12" style="enable-background:new 0 0 7.2 12;" xml:space="preserve">\
			<path class="st0" d="M4.8,6l-4.5,4.3c-0.4,0.4-0.4,1,0,1.4c0.4,0.4,1,0.4,1.4,0l5.2-5C7.1,6.5,7.2,6.3,7.2,6S7.1,5.5,6.9,5.3l-5.2-5C1.5,0.1,1.2,0,1,0C0.7,0,0.5,0.1,0.3,0.3c-0.4,0.4-0.4,1,0,1.4L4.8,6z"/>\
			</svg>\
		</button>',
	});

	$( '.flex-direction-nav' ).on( 'click', function() {
		$( '.flex-control-nav' ).slick( 'slickGoTo', $( '.flex-active-slide').index() - 2 );
	} );

	/**
	 * Align quantity input based on variation inputs.
	 *
	 * @todo Improve logic to do to also align variation inputs based on quantity input.
	 * @since 6.0.2
	 * @since 6.0.3 Add min-width to varations label based on qty label width.
	 */
	function mkAlignVariationsQuantity() {
		var $variations = $( '.variations', '.summary' );
		var $quantity = $( '.mk-product-quantity', '.summary' );

		if ( ! $variations.is(':visible') ) {
			$quantity.removeAttr('style');
			return;
		}

		if ( $('body').hasClass('mk-product-layout-8') ) {
			$quantity.removeAttr('style');
			return;
		}

		var $variationLabel = $( 'td.label', $variations );
		var $quantityLabelWidth = $( '.mk-quantity-label', '.summary' ).width();

		$variationLabel.css({
			minWidth: $quantityLabelWidth
		});

		var variationLabelWidth = $variationLabel.outerWidth()
		var variationLabelPadding = $variationLabel.css('padding-left');
		var left = variationLabelWidth + parseInt(variationLabelPadding);

		$quantity.css({
			left: left,
			position: 'absolute'
		});

	}

	var mkHandleAlign = debounce(function() {
		mkAlignVariationsQuantity()
	}, 250)

	mkAlignVariationsQuantity()
	$(document.body).on('mk-woo-align-quantity', mkHandleAlign)
	$(window).on('resize', mkHandleAlign)

	/**
	 * Add zoom for all images in layout 9 and 10.
	 * Codes borrowed from `woocommerce/assets/js/frontend/single-product.js`.
	 *
	 * @since 6.0.3
	 */
	if ( $.isFunction( $.fn.zoom ) && wc_single_product_params.zoom_enabled ) {
		var zoomTarget = $( '.woocommerce-product-gallery__image' );

		var galleryWidth = zoomTarget.parents( '.flex-viewport' ).width(),
			zoomEnabled  = false;

		$( zoomTarget ).each( function( index, target ) {
			var image = $( target ).find( 'img' );

			if ( image.data( 'large_image_width' ) > galleryWidth ) {
				zoomEnabled = true;
				return false;
			}
		} );

		// But only zoom if the img is larger than its container.
		if ( zoomEnabled ) {
			var zoom_options = {
				touch: false
			};

			if ( 'ontouchstart' in window ) {
				zoom_options.on = 'click';
			}

			zoomTarget.trigger( 'zoom.destroy' );
			zoomTarget.zoom( zoom_options );
		}
	}

});
