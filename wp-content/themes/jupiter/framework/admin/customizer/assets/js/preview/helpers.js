/**
 * Helper functions for customizer preview.
 */

/**
 * Generate CSS for Typography.
 *
 * @since 5.9.4
 */
function mkPreviewTypography(typography, loadFonts, excludes) {
	if (typeof typography === 'string') {
		typography = jQuery.parseJSON(typography);
	}

	if (typeof loadFonts !== 'undefined' && true === loadFonts && typeof typography.family !== 'undefined' && typography.source === 'google-font') {
		// Load the selected font in the preview iframe.
		var previewFrame = jQuery('#customize-preview').find('iframe').attr('name');
		var weights = ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900';
		WebFont.load({
			google: {
				families: [typography.family + weights],
			},
			context: frames[previewFrame],
			timeout: 2000
		});
	}

	if (typeof excludes !== 'object') {
		excludes = [];
	}

	var css = {};

	for (var key in typography) {

		if (-1 === excludes.indexOf(key)) {
			switch (key) {
			case 'source':
				break;
			case 'color':
				css[key] = typography[key];
				break;
			case 'size':
				css['font-' + key] = typography[key] + 'px';
				break;
			default:
				css['font-' + key] = typography[key];
				break;
			}
		}
	}

	return css;
}

/**
 * Generate CSS for BoxModel.
 *
 * @since 5.9.4
 */
function mkPreviewBoxModel(boxModel, excludes) {
	if (typeof boxModel === 'string') {
		boxModel = jQuery.parseJSON(boxModel);
	}

	if (typeof excludes !== 'array') {
		excludes = [];
	}

	var css = {};

	for (var key in boxModel) {
		if (-1 === excludes.indexOf(key)) {
			css[key.replace('_', '-')] = boxModel[key] + 'px';
		}
	}

	return css;
}

/**
 * Add internal style in customizer preview head.
 * Useful for targeting pseudo elements & hovers.
 * Credit to https://goo.gl/hAzJ1q
 *
 * @since 5.9.4
 */
function mkPreviewInternalStyle(styles, el) {
	var styleTag;
	var css = '';

	jQuery.each(styles, function (selector, value) {
		if (typeof value === 'object') {
			var tmpVal = '';
			jQuery.each(value, function(key, val){
				tmpVal = tmpVal + key + ':' + val +';';
			});
			value = tmpVal;
		}
		css += selector + '{' + value + '}';
	});

	// Build the style element.
	styleTag = '<style class="' + el + '">' + css + '</style>';

	// Look for a matching style element that might already be there.
	el = jQuery('.' + el);

	// Add the style element into the DOM or replace the matching style element that is already there
	if (el.length) {
		el.replaceWith(styleTag);
	} else {
		jQuery('head').append(styleTag);
	}
}

/**
 * Save settings then reload.
 *
 * @since 5.9.4
 */
function mkPreviewSaveReload() {

	wp.customize.preview.trigger( 'loading-initiated' );

	jQuery( '#save', window.parent.document.body ).trigger( 'click' );

	// Refresh the preview after settings are saved.
	wp.customize.bind( 'saved', function() {
		wp.customize.preview.send( 'refresh' );
	} );

}

/**
 * Get container width based on product layout.
 *
 * @todo Improve logic to get width automatically not hard coded value per layout.
 * @since 5.9.4
 * @since 6.0.3 Improve logic to handle layout 9/10.
 */
function mk_get_image_gallery_width( setting ) {
	var productLayout = wp.customize( setting )();
	var containerWidth = 48; // Default value.

	if ( productLayout == 3 ) {
		containerWidth = 100;
	}

	if ( 9 == productLayout || 10 == productLayout ) {
		containerWidth = 61;
	}

	return containerWidth;
}
