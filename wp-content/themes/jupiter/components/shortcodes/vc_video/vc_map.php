<?php
vc_map(array(
	'name' => __( 'Video Player', 'mk_framework' ),
	'base' => 'vc_video',
	'html_template' => dirname( __FILE__ ) . '/vc_video.php',
	'icon' => 'icon-mk-video-player vc_mk_element-icon',
	'description' => __( 'Youtube, Vimeo,..', 'mk_framework' ),
	'category' => __( 'Social', 'mk_framework' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Shortcode Title', 'mk_framework' ),
			'param_name' => 'title',
			'value' => '',
			'description' => __( '', 'mk_framework' ),
		) ,
		array(
			'type' => 'range',
			'heading' => __( 'Video Max Width', 'mk_framework' ),
			'param_name' => 'max_width',
			'value' => '0',
			'min' => '0',
			'max' => '2000',
			'step' => '1',
			'unit' => 'px',
			'description' => __( 'If set zero, the video will expand to the parent container width', 'mk_framework' ),
		) ,
		array(
			'heading' => __( 'Video Type', 'mk_framework' ),
			'description' => __( '', 'mk_framework' ),
			'param_name' => 'host',
			'value' => array(
				__( 'Social Hosted (Youtube, Vimeo,..)', 'mk_framework' ) => 'social_hosted',
				__( 'Self Hosted', 'mk_framework' ) => 'self_hosted',
			),
			'type' => 'dropdown',
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'MP4 Format', 'mk_framework' ),
			'param_name' => 'mp4',
			'value' => '',
			'description' => __( 'Compatibility for Safari, IE10', 'mk_framework' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'WebM Format', 'mk_framework' ),
			'param_name' => 'webm',
			'value' => '',
			'description' => __( 'Compatibility for Firefox4, Opera, and Chrome', 'mk_framework' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'upload',
			'heading' => __( 'Video Preview image (and fallback image)', 'mk_framework' ),
			'param_name' => 'poster_image',
			'value' => '',
			'description' => __( 'This Image will shown until video load. in case of video is not supported or did not load the image will remain as fallback.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'textfield',
			'heading' => __( 'Video link', 'mk_framework' ),
			'param_name' => 'link',
			'value' => '',
			'description' => __( 'Link to the video. For YouTube HD videos add this snippet at the of a link "&vq=1080" (video quality set to 1080p). More about supported formats at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'social_hosted'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Autoplay, Loop, Controls Visibility', 'mk_framework' ),
			'param_name' => 'autoplay',
			'value' => 'false',
			'description' => __( 'To start playing the video automatically, turn on &quot;Autoplay&quot;.', 'mk_framework' ),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Loop', 'mk_framework' ),
			'param_name' => 'loop',
			'value' => 'false',
			'description' => __( 'To start playing the video from beginning after video ends, turn on &quot;Loop&quot;.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Hide Controls', 'mk_framework' ),
			'param_name' => 'hide_controls',
			'value' => 'false',
			'description' => __( 'To hide the video control buttons and progress bar, turn on &quot;Hide Controls&quot;. This option will work only with self hosted videos.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'true'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Custom thumbnail and Lightbox', 'mk_framework' ),
			'param_name' => 'custom_thumbnail',
			'value' => 'false',
			'description' => __( 'Use your own image and playback icon for the video thumbnail and play videos inside a lightbox.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'autoplay',
				'value' => array(
					'false'
				),
			),
		) ,
		array(
			'type' => 'toggle',
			'heading' => __( 'Aspect Ratio', 'mk_framework' ),
			'param_name' => 'aspect_ratio',
			'value' => 'false',
			'description' => __( 'To keep the video aspect ratio, turn on &quot;Aspect Ratio&quot;.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'host',
				'value' => array(
					'self_hosted'
				),
			),
		) ,
		array(
			'type' => 'attach_image',
			'heading' => __( 'Thumbnail image', 'mk_framework' ),
			'param_name' => 'thumbnail_image',
			'description' => __( 'The background image which covers the thumbnail.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'icon_selector',
			'heading' => __( 'Playback Icon', 'mk_framework' ),
			'param_name' => 'play_icon',
			'value' => 'mk-icon-play',
			'description' => __( 'This icon will appear in the center of thumbnail.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Playback Icon Size', 'mk_framework' ),
			'param_name' => 'play_icon_size',
			'value' => array(
				__( '32px', 'mk_framework' ) => '32',
				__( '64px', 'mk_framework' ) => '64',
				__( '128px', 'mk_framework' ) => '128',
				__( '256px', 'mk_framework' ) => '256',
			),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'alpha_colorpicker',
			'heading' => __( 'Playback Icon Color', 'mk_framework' ),
			'param_name' => 'play_icon_color',
			'value' => '#ffffff',
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Playback Icon Hover Animation', 'mk_framework' ),
			'param_name' => 'play_icon_animation',
			'value' => array(
				__( 'Fade In', 'mk_framework' ) => 'fade-in',
				__( 'Scale Up', 'mk_framework' ) => 'scale-up',
				__( 'None', 'mk_framework' ) => 'none',
			),
			'default' => 'fade-in',
			'description' => __( 'What happens when userhovers the mouse pointer above this video thumbnail.', 'mk_framework' ),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		array(
			'type' => 'dropdown',
			'heading' => __( 'Open this video in', 'mk_framework' ),
			'param_name' => 'play_target',
			'default' => 'lightbox',
			'value' => array(
				__( 'Lightbox', 'mk_framework' ) => 'lightbox',
				__( 'Same place', 'mk_framework' ) => 'same',
			),
			'dependency' => array(
				'element' => 'custom_thumbnail',
				'value' => array( 'true' ),
			),
		) ,
		$add_css_animations,
		$add_device_visibility,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'mk_framework' ),
			'param_name' => 'el_class',
			'value' => '',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'mk_framework' ),
		),
	),
));
