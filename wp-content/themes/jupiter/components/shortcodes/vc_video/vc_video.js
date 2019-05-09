(function($) {
	'use strict';

	function mk_video_play() {

		var lightboxMargin = 60;

		// Play self hosted video
		function playSelfHosted($video, isLightbox) {
			if (isLightbox === undefined || typeof isLightbox === 'undefined') {
				isLightbox = false;
			}

			if (isLightbox) {

				var content = $video.parent().html();

				playLightbox({
					content: '<div class="fancybox-video">' + $(content).attr('autoplay', 'autoplay').wrap('<div></div>').parent().html() + '</div>',
				});

			} else {
				playTagVideo($video);
			}
		}

		// Play social hosted video
		function playSocialHosted($iframe, isLightbox) {

			if (isLightbox === undefined || typeof isLightbox === 'undefined') {
				isLightbox = false;
			}

			if (isLightbox) {

				playLightbox({
					type: 'iframe',
					href: $iframe.attr('src'),
					helpers: {
						media: true
					}
				});

			} else {

				var videoData = getSocialVideoData($iframe.attr('src'));

				switch (videoData.source) {
					case 'youtube':
						playTagIframeYoutube(videoData.videoId, $iframe);
						break;
					case 'vimeo':
						playTagIframeVimeo(videoData.videoId, $iframe);
						break;
					default:
						playTagIframe($iframe);
						break;
				}
			}
		}

		// Play video in lightbox
		function playLightbox(args) {
			var options = {
				padding: 0,
				margin: lightboxMargin,
				showCloseButton: 1,
				autoSize: 0,
				width: getVideoboxWidth(),
				height: getVideoHeight(),
				tpl: {
					closeBtn: '<a title="Close" class="fancybox-item fancybox-close fancybox-video-close" href="javascript:;"></a>',
				},
			};
			$.extend(options, args);
			$.fancybox.open(options);
		}

		function playTagVideo($video) {
			$video.get(0).play();
			$video.closest('.video-container').find('.video-thumbnail').fadeOut('slow');
		}

		function playTagIframe($iframe, videoId) {
			var video_loop = '';
			if (videoId !== undefined && typeof videoId !== 'undefined') {
				video_loop = '&playlist=' + videoId;
			}

			var src = $iframe.attr('src');
			var separator = (src.indexOf('?') === -1) ? '?' : '&';
			src += separator + 'autoplay=1';
			separator = (src.indexOf('?') === -1) ? '?' : '&';
			video_loop = separator + 'loop=1' + video_loop;
			video_loop = ( $iframe.closest('.video-container').data('loop') == '1' ) ? video_loop : '';
			src += video_loop;
			$iframe.attr('src', src).closest('.video-container').find('.video-thumbnail').fadeOut(3000);
		}

		function playTagIframeYoutube(videoId, $iframe) {

			// Credit: https://goo.gl/ku3NgH
			if ( document.getElementById('mk_iframe_api') === null ) {
				var tag = document.createElement('script');
				tag.src = "https://www.youtube.com/iframe_api";
				tag.id = "mk_iframe_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			} else {
				runPlayer();
			}

			window.onYouTubeIframeAPIReady = function () {
				runPlayer();
			};

			function runPlayer(){
				var player,
					isPlayed = false;

				player = new YT.Player('video-player-' + $iframe.data('id'), {
					videoId: videoId,
					rel: false,
					events: {
						onReady: function(e) {
							e.target.playVideo();
						},
						onStateChange: function(e) {
							if (e.data === 1 && !isPlayed) {
								$(e.target.a).closest('.video-container').find('.video-thumbnail').fadeOut('slow');
								isPlayed = true;
							}

							if ($(e.target.a).closest('.video-container').data('loop') && e.data === YT.PlayerState.ENDED) {
								e.target.playVideo();
							}
						},
						onError: function(e) {
							playTagIframe($iframe, videoId);
						}
					}
				});
			}
		}

		function playTagIframeVimeo(videoId, $iframe) {
			// Embed async the vimeo API script
			$.getScript('//player.vimeo.com/api/player.js', function(data, textStatus, jqxhr) {
				if (jqxhr.status === 200) {

					var player,
						isPlayed = false;

					player = new Vimeo.Player('video-player-' + $iframe.data('id'), {
						id: videoId
					});

					player.play().then(function() {
						if (!isPlayed) {
							$iframe.closest('.video-container').find('.video-thumbnail').fadeOut('slow');
							isPlayed = true;
						}
					}).catch(function(error) {
						playTagIframe($iframe);
					});

					if ($iframe.closest('.video-container').data('loop')) {
						player.setLoop(true).then(function(loop) {
							// Enable loop
						}).catch(function(error) {
							playTagIframe($iframe);
						});
					}

				} else {
					playTagIframe($iframe);
				}
			});
		}

		// Get Social Provider Video Data
		function getSocialVideoData(url) {

			// Get Youtube video data
			var youtubeRegex = /(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i;
			var youtubeMatch = url.match(youtubeRegex);
			if (youtubeMatch && youtubeMatch != null) {
				return {
					source: 'youtube',
					videoId: youtubeMatch[3]
				};
			}

			// Get Vimeo video data
			var vimeoRegex = /(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/i;
			var vimeoMatch = url.match(vimeoRegex);
			if (vimeoMatch && vimeoMatch != null) {
				return {
					source: 'vimeo',
					videoId: vimeoMatch[1]
				};
			}

			return {
				source: false,
				videoId: false
			};
		}

		// Get lightbox width
		function getVideoboxWidth() {
			var $width,
				wWidth = $(window).width(),
				wHeight = $(window).height();

			if (wHeight >= wWidth) {

				$width = wWidth - (lightboxMargin * 2);

			} else {

				var wHeightMax = (wHeight * 90) / 100;

				if (wWidth > 1280) {
					var $width = (wHeightMax / 5768) * 10000;
				} else {
					var $width = (wHeightMax / 6120) * 10000;
				}

			}

			return Math.round($width) + 'px';
		}

		// Get lightbox height
		function getVideoHeight() {

			var $height,
				wWidth = $(window).width(),
				wHeight = $(window).height();

			if (wHeight >= wWidth) {
				$height = ((wWidth - (lightboxMargin * 2)) * 5670) / 10000;
			} else {
				$height = ((wHeight * 90) / 100) + (lightboxMargin * 2);
			}
			return Math.round($height) + 'px';
		}

		$('.video-container').each(function() {

			var $videoContainer = $(this);
			var playSource = $videoContainer.data('source');
			var playTarget = $videoContainer.data('target');
			var $iframe = $videoContainer.find('iframe');
			var $video = $videoContainer.find('video');

			if ($videoContainer.data('autoplay')) {
				switch (playSource) {
					case 'social_hosted':
						playSocialHosted($iframe);
						break;
					case 'self_hosted':
						playSelfHosted($video);
						break;
				}
			} else {
				var $playIcon = $videoContainer.find('.mk-svg-icon');
				$playIcon.bind('click', function(e) {
					e.preventDefault();
					var isLightbox = (playTarget == 'lightbox') ? true : false;
					if (!isLightbox) {
						$playIcon.hide().next('.preloader-preview-area').show();
					}
					switch (playSource) {
						case 'social_hosted':
							playSocialHosted($iframe, isLightbox);
							break;
						case 'self_hosted':
							playSelfHosted($video, isLightbox);
							break;
					}
				});
			}
		});
	}
	$(window).on('load vc_reload', mk_video_play);

	// Resize icon size for responsive layout
	function mk_video_resize_play_icon() {
		$('.video-thumbnail-overlay').each(function() {
			var $thumbnailOverlay = $(this);
			var thumbnailWidth = $thumbnailOverlay.width();
			var $svg = $thumbnailOverlay.find('svg');
			if (typeof $svg.data('width') === 'undefined') {
				$svg.attr('data-width', $svg.width());
			}
			if (typeof $svg.data('height') === 'undefined') {
				$svg.attr('data-height', $svg.height());
			}
			if (($svg.data('width') * 4) > thumbnailWidth) {
				$svg.css({
					width: Math.round((parseInt(thumbnailWidth) / 4)) + 'px',
					height: Math.round((parseInt(thumbnailWidth) / 4) * $svg.data('height') / $svg.data('width')) + 'px'
				});
			} else {
				$svg.css({
					width: $svg.data('width') + 'px',
					height: $svg.data('height') + 'px'
				});
			}
		});
	}
	$(window).on('load resize orientationChange vc_reload', mk_video_resize_play_icon);
}(jQuery));
