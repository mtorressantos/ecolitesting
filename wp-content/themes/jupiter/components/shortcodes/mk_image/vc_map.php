<?php

vc_map(array(
    'name'        => __( 'Image', 'mk_framework' ),
    'base'        => 'mk_image',
	'html_template' => dirname( __FILE__ ) . '/mk_image.php',
    'category'    => __( 'General', 'mk_framework' ),
    'description' => __( 'Adds Image element with many styles.', 'mk_framework' ),
    'icon'        => 'icon-mk-image vc_mk_element-icon',
    'params'      => array(
        array(
            "type" => "textfield",
            "heading" => __("Heading Title", "mk_framework") ,
            "param_name" => "heading_title",
            "value" => "",
            "description" => __("", "mk_framework")
        ) ,

        array(
            "type" => "upload",
            "heading" => __("Upload Your image", "mk_framework") ,
            "param_name" => "src",
            "value" => "",
            "description" => __("", "mk_framework")
        ) ,
         array(
            "heading" => __("Image Size", 'mk_framework'),
            "description" => __("Please note that image size option will work if the image is uploaded locally in this server. If it's hot-linked from different source you will get the full image size!", 'mk_framework'),
            "param_name" => "image_size",
            "value" => mk_get_image_sizes(),
            "type" => "dropdown"
        ),
        /*array(
            "type" => "toggle",
            "heading" => __("Image Cropping", "mk_framework") ,
            "param_name" => "crop",
            "value" => "true",
            "description" => __("If you dont want to crop your image based on the dimensions you defined above disable this option. Only wdith will be used to give the image container max-width property.", "mk_framework")
        ) ,*/
        array(
            "type" => "range",
            "heading" => __("Image Width", "mk_framework") ,
            "param_name" => "image_width",
            "value" => "800",
            "min" => "10",
            "max" => "2600",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", "mk_framework"),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Image Height", "mk_framework") ,
            "param_name" => "image_height",
            "value" => "350",
            "min" => "10",
            "max" => "5000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", "mk_framework"),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop'
                )
            )
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("SVG Enable?", "mk_framework") ,
            "param_name" => "svg",
            "value" => "false",
            "description" => __("If enabled max-width property will be added to image tag and you should enable this option if you are using SVG format in this image shortcode.", "mk_framework")
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Lightbox", "mk_framework") ,
            "param_name" => "lightbox",
            "value" => "false",
            "description" => __("If you would like to have lightbox (image zoom in a frame) enable this option.", "mk_framework")
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Custom Lightbox URL", "mk_framework") ,
            "param_name" => "custom_lightbox",
            "value" => "",
            "description" => __("You can use this field to add your custom lightbox URL to appear in pop up box. it can be image SRC, youtube URL.", "mk_framework")
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Lightbox Group rel", "mk_framework") ,
            "param_name" => "group",
            "value" => "",
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Image Frame Style", "mk_framework") ,
            "param_name" => "frame_style",
            "value" => array(
                "No Frame" => "simple",
                "Rounded Frame" => "rounded",
                "Single Line Frame" => "single_line",
                "Gray Border Frame" => "gray_border",
                "Border With Shadow" => "border_shadow",
                "Shadow Only" => "shadow_only"
            ) ,
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Link", "mk_framework") ,
            "param_name" => "link",
            "value" => "",
            "description" => __("Optionally you can link your image.", "mk_framework")
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Target", "mk_framework") ,
            "param_name" => "target",
            "width" => 200,
            "value" => $target_arr,
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Caption Title", "mk_framework") ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Caption Description", "mk_framework") ,
            "param_name" => "desc",
            "value" => "",
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Image Caption Location", "mk_framework") ,
            "param_name" => "caption_location",
            "value" => array(
                "Inside Image" => "inside-image",
                "Outside Image" => "outside-image"
            ) ,
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type"        => "toggle",
            "heading"     => __( "Image Hover Overlay", "mk_framework" ) ,
            "param_name"  => "hover_image_overlay",
            "value"       => "true",
            "description" => __( "", "mk_framework" ),
            "dependency" => array(
                'element' => "lightbox",
                'value' => array(
                    'true'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Align", "mk_framework") ,
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                __('Left', "mk_framework") => "left",
                __('Right', "mk_framework") => "right",
                __('Center', "mk_framework") => "center"
            ) ,
            "description" => __("", "mk_framework")
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", "mk_framework") ,
            "param_name" => "margin_bottom",
            "value" => "10",
            "min" => "-50",
            "max" => "300",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", "mk_framework")
        ) ,
        $add_device_visibility,
        $add_css_animations,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "mk_framework") ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "mk_framework")
        ),
        array(
            'type'        => 'message',
            'heading'     => __( 'Drop Shadow', 'mk_framework' ),
            'param_name'  => 'drop_shadow_placeholder',
            'description' => __( 'Set <strong>Image Frame Style</strong> option to <strong>No Frame</strong>, <strong>Rounded Frame</strong>, <strong>Single Line Frame</strong> or <strong>Gray Border Frame</strong> to enable drop shadow.', 'mk_framework' ),
            'dependency'  => array(
                'element' => 'frame_style',
                'value'   => array( 
                    'border_shadow', 
                    'shadow_only'
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
        array(
            'type'        => 'toggle',
            'heading'     => __( 'Drop Shadow', 'mk_framework' ),
            'param_name'  => 'drop_shadow',
            'value'       => 'false',
            'description' => __( 'Enable drop shadow for the image.', 'mk_framework' ),
            'dependency'  => array(
                'element' => 'frame_style',
                'value'   => array( 
                    'simple',
                    'rounded',
                    'single_line',
                    'gray_border'
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Angle', 'mk_framework' ),
            'param_name'  => 'drop_shadow_angle',
            'value'       => '45',
            'min'         => '0',
            'max'         => '360',
            'step'        => '1',
            'unit'        => 'deg',
            'description' => __( 'Set the angle of the shadow between 0 and 360.', 'mk_framework' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array( 
                    'true' 
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Distance', 'mk_framework' ),
            'param_name'  => 'drop_shadow_distance',
            'value'       => '8',
            'min'         => '1',
            'max'         => '100',
            'step'        => '1',
            'unit'        => 'px',
            'description' => __( 'Set the distance/size of the shadow between 1 and 100.', 'mk_framework' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Blur', 'mk_framework' ),
            'param_name'  => 'drop_shadow_blur',
            'value'       => '20',
            'min'         => '0',
            'max'         => '100',
            'step'        => '1',
            'unit'        => 'px',
            'description' => __( 'Set the blur radius of the shadow between 0 and 100.', 'mk_framework' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
        array(
            'type'       => 'alpha_colorpicker',
            'heading'    => __( 'color', 'mk_framework' ),
            'param_name' => 'drop_shadow_color',
            'value'      => 'rgba(0,0,0,0.5)',
            'dependency' => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'mk_framework' )
        ),
		$mk_vc_map_parallax_scroll['pxs'],
		$mk_vc_map_parallax_scroll['pxs_x'],
		$mk_vc_map_parallax_scroll['pxs_y'],
		$mk_vc_map_parallax_scroll['pxs_z'],
		$mk_vc_map_parallax_scroll['pxs_smoothness'],
	)
));
