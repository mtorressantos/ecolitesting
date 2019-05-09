jQuery(function($) {
	var api = wp.customize;

	api.bind('ready', function() {
		// Variables for handling upload preview box state.
		var curPreviewBox = null,
			curPrev = null,
			isPrevOpened = false;

		var setPreviewState = function(previewBox, opened) {
			if (previewBox !== curPreviewBox && (curPreviewBox && isPrevOpened)) {
				curPreviewBox.find('img').remove();
				curPreviewBox.removeClass('mk-upload-preview-open');
				curPreviewBox.removeClass('mk-upload-preview-loading');
			}

			curPreviewBox = previewBox || null;
			curPrev = previewBox ? previewBox.parents('.mk-upload') : null;
			isPrevOpened = open || false;
		};

		var togglePreviewBox = function(previewBox, show) {
			previewBox.find('img').remove();
			previewBox.toggleClass('mk-upload-preview-open', show);
			previewBox.toggleClass('mk-upload-preview-loading', show);
		};

		$('.mk-upload').each(function(i, el) {
			var $this = $(el),
				wrap = $this.find('.mk-upload-wrap'),
				field = $this.find('.mk-upload-field'),
				value = $this.find('.mk-upload-value'),
				fieldInput = field.find('.mk-element-input input'),
				previewButton = field.find('.mk-input-group-icon'),
				previewBox = $this.find('.mk-upload-preview'),
				button = field.find('.mk-button'),
				frame = null;

			var updateValue = function() {
				var tempObj = {};

				wrap.find('input[name]').each(function(j, input) {
					var $input = $(input);
					tempObj[$input.attr('name')] = $input.val();
				});

				value.val(JSON.stringify(tempObj));
				value.trigger('change');
			};

			var previewEnable = function() {
				field.toggleClass('mk-upload-field-empty', fieldInput.val() === '' ? true : false);
			};

			previewEnable();

			wrap.find('input[name]').on('keyup change', function() {
				updateValue();
				previewEnable();
			});

			previewButton.on('click', function(event) {
				event.preventDefault();

				var visible = !previewBox.is(':visible');

				// Either show or hide the box depending on the box visibility state.
				togglePreviewBox(previewBox, visible);
				setPreviewState(previewBox, visible);

				if (visible === true) {
					var image = $('<img src="' + fieldInput.val() + '" />');

					image.load(function() {
						previewBox.removeClass('mk-upload-preview-loading');
						previewBox.append(image);
					});
				}
			});

			button.on('click', function(event) {
				event.preventDefault();

				// Hide the preview box before frame opens.
				togglePreviewBox(previewBox, false);
				setPreviewState(previewBox, false);

				if (frame) {
					frame.open();
					return;
				}

				frame = wp.media({
					title: 'Insert Media',
					multiple: false,
					button: {
						text: 'Insert into field'
					}
				});

				frame.on('select', function() {
					var attachment = frame
						.state()
						.get('selection')
						.first()
						.toJSON();

					fieldInput.val(attachment.url);
					updateValue();
					previewEnable();
				});

				// Force media frame to go above the dialog box.
				frame.modal.$el[0].style.zIndex = '2000000';
				frame.open();
			});
		});

		$(document).on('click', function(event) {
			if (curPrev && isPrevOpened) {
				if (
					!curPrev.is(event.target) &&
					curPrev.has(event.target).length === 0
				) {
					togglePreviewBox(curPreviewBox, false);
					setPreviewState(null, false);
				}
			}
		});
	});
});
