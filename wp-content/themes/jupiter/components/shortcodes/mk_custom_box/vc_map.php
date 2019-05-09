<?php
    vc_map(array(
        "name" => __("Custom Box", "mk_framework") ,
        "base" => "mk_custom_box",
		'html_template' => dirname( __FILE__ ) . '/mk_custom_box.php',
        "as_parent" => array(
            'except' => 'mk_page_section'
        ) ,
        "admin_enqueue_js" => THEME_COMPONENTS . "/shortcodes/mk_custom_box/vc_admin.js",
		'front_enqueue_js' => THEME_COMPONENTS . '/shortcodes/mk_custom_box/vc_front.js',
        "content_element" => true,
        "show_settings_on_create" => false,
        "description" => __("Custom Box For your contents.", "mk_framework") ,
        'icon' => 'icon-mk-custom-box vc_mk_element-icon',
        "category" => __('General', 'mk_framework') ,
        "params" => array(
            array(
                "type" => "range",
                "heading" => __("Corner Radius", "mk_framework") ,
                "param_name" => "corner_radius",
                "value" => "0",
                "min" => "0",
                "max" => "50",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", "mk_framework")
            ) ,
            array(
                "type" => "range",
                "heading" => __("Padding Top and Bottom", "mk_framework") ,
                "param_name" => "padding_vertical",
                "value" => "30",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", "mk_framework")
            ) ,
            array(
                "type" => "range",
                "heading" => __("Padding Left and Right", "mk_framework") ,
                "param_name" => "padding_horizental",
                "value" => "20",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", "mk_framework")
            ) ,
            array(
                "type" => "range",
                "heading" => __("Margin Bottom", "mk_framework") ,
                "param_name" => "margin_bottom",
                "value" => "10",
                "min" => "0",
                "max" => "200",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", "mk_framework")
            ) ,
            array(
                "type" => "range",
                "heading" => __("Section Min Height", "mk_framework") ,
                "param_name" => "min_height",
                "value" => "100",
                "min" => "0",
                "max" => "1000",
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
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Background Color Style", "mk_framework") ,
                "param_name" => "background_style",
                "default" => "image",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => array(
                    __('Image & Single Color', "mk_framework") => "image",
                    __('Gradient Color', "mk_framework") => "gradient_color",
                    
                ) ,
                "description" => __("", "mk_framework")
            ) ,

            /**
             * Background Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Background Color", "mk_framework") ,
                "param_name" => "bg_color",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image'
                    )
                )
            ) ,

            /**
             * Background Gradient Color
             * ==================================================================================
             */

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", "mk_framework") ,
                "param_name" => "bg_grandient_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", "mk_framework") ,
                "param_name" => "bg_grandient_color_to",
                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", "mk_framework") ,
                "param_name" => "bg_gradient_color_style",
                "value" => array(
                    __('Linear', "mk_framework") => "linear",
                    __('Radial', "mk_framework") => "radial"
                ) ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", "mk_framework") ,
                "param_name" => "bg_gradient_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => array(
                    __('Vertical ↓', "mk_framework") => "vertical",
                    __('Horizontal →', "mk_framework") => "horizontal",
                    __('Diagonal ↘', "mk_framework") => "diagonal_left_bottom",
                    __('Diagonal ↗', "mk_framework") => "diagonal_left_top",
                ) ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", "mk_framework") ,
                "param_name" => "bg_grandient_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            /**
             * Background Image
             * ==================================================================================
             */
            array(
                "type" => "upload",
                "heading" => __("Background Image", "mk_framework") ,
                "param_name" => "bg_image",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "toggle",
                "heading" => __("Cover whole background", "mk_framework") ,
                "description" => __("Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.", "mk_framework") ,
                "param_name" => "bg_stretch",
                "width" => 300,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Background Position", "mk_framework") ,
                "param_name" => "bg_position",
                "width" => 300,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => array(
                    __('Center Center', "mk_framework") => "center center",
                    __('Left Center', "mk_framework") => "left center",
                    __('Right Center', "mk_framework") => "right center",
                    __('Left Top', "mk_framework") => "left top",
                    __('Center Top', "mk_framework") => "center top",
                    __('Right Top', "mk_framework") => "right top",
                    __('Left Bottom', "mk_framework") => "left bottom",
                    __('Center Bottom', "mk_framework") => "center bottom",
                    __('Right Bottom', "mk_framework") => "right bottom"
                ) ,
                "description" => __("First value defines horizontal position and second vertical positioning.", "mk_framework") ,
                 "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Background Repeat", "mk_framework") ,
                "param_name" => "bg_repeat",
                "width" => 300,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => array(
                    __('No Repeat', "mk_framework") => "no-repeat",
                    __('Repeat', "mk_framework") => "repeat",
                    __('Horizontally repeat', "mk_framework") => "repeat-x",
                    __('Vertically Repeat', "mk_framework") => "repeat-y"
                ) ,
                "description" => __("", "mk_framework") ,
                 "dependency" => array(
                    'element' => "background_style",
                    'value' => array(
                        'image',
                    )
                ) ,
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Background Color Style", "mk_framework") ,
                "param_name" => "background_hov_color_style",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => array(
                    __('None', "mk_framework") => "none",
                    __('Image & Single Color', "mk_framework") => "image",
                    __('Gradient Color', "mk_framework") => "gradient_color",
                ) ,
                "description" => __("", "mk_framework")
            ) ,

            array(
                "type" => "dropdown",
                "heading" => __("Border Color Style", "mk_framework") ,
                "param_name" => "border_color_style",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => array(
                    __('None', "mk_framework") => "none",
                    __('Single Color', "mk_framework") => "single_color",
                    __('Gradient Color', "mk_framework") => "gradient_color"
                ) ,
                "description" => __("", "mk_framework")
            ) ,

            /**
             * Border Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Border Color", "mk_framework") ,
                "param_name" => "border_color",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color'
                    )
                )
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Border Style", "mk_framework") ,
                "param_name" => "border_style",
                "width" => 300,
                "value" => array(
                    __('Solid', "mk_framework") => "solid",
                    __('Dashed', "mk_framework") => "dashed",
                    __('Dotted', "mk_framework") => "dotted",
                ) ,
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color'
                    )
                )
            ) ,
            array(
                "type" => "range",
                "heading" => __("Border Width", "mk_framework") ,
                "param_name" => "border_width",
                "value" => "1",
                "min" => "1",
                "max" => "50",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'single_color',
                        'gradient_color'
                    )
                )
            ) ,

            /**
             * Border Gradient Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", "mk_framework") ,
                "param_name" => "border_grandient_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", "mk_framework") ,
                "param_name" => "border_grandient_color_to",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", "mk_framework") ,
                "param_name" => "border_gradient_color_style",
                "value" => array(
                    __('Linear', "mk_framework") => "linear",
                    __('Radial', "mk_framework") => "radial"
                ) ,
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", "mk_framework") ,
                "param_name" => "border_gradient_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => array(
                    __('Vertical ↓', "mk_framework") => "vertical",
                    __('Horizontal →', "mk_framework") => "horizontal",
                    __('Diagonal ↘', "mk_framework") => "diagonal_left_bottom",
                    __('Diagonal ↗', "mk_framework") => "diagonal_left_top",
                ) ,
                "description" => __("", "mk_framework") ,
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", "mk_framework") ,
                "param_name" => "border_grandient_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "value" => "",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "border_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Overlay Color", "mk_framework") ,
                "param_name" => "overlay_color",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") 
            ) ,

            array(
                "type" => "toggle",
                "heading" => __("Drop Shadow", "mk_framework") ,
                "description" => __("Drop shadow option is disabled when the elevation effect is turned on.", "mk_framework") ,
                "param_name" => "drop_shadow",
                "value" => 'false',
                "group" => __('Styles & Colors', 'mk_framework') ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Angle", "mk_framework") ,
                "param_name" => "drop_shadow_angle",
                "value" => "45",
                "min" => "0",
                "max" => "360",
                "step" => "1",
                "unit" => '°',
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Distance", "mk_framework") ,
                "param_name" => "drop_shadow_distance",
                "value" => "8",
                "min" => "1",
                "max" => "100",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Blur", "mk_framework") ,
                "param_name" => "drop_shadow_blur",
                "value" => "20",
                "min" => "0",
                "max" => "100",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Styles & Colors', 'mk_framework') ,
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Color", "mk_framework") ,
                "param_name" => "drop_shadow_color",
                "group" => __('Styles & Colors', 'mk_framework') ,
                "value" => "rgba(0, 0, 0, 0.5)",
                "dependency" => array(
                    'element' => "drop_shadow",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,
            


            /**
             * Background Hover Single Color
             * ==================================================================================
             */
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Background Color", "mk_framework") ,
                "param_name" => "bg_hov_color",
                "value" => "",
                "group" => __('Hover Options', 'mk_framework') ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'image'
                    )
                )
            ) ,

            /**
             * Background Gradient Hover Color
             * ==================================================================================
             */

            array(
                "type" => "alpha_colorpicker",
                "heading" => __("From", "mk_framework") ,
                "param_name" => "bg_grandient_hov_color_from",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("To", "mk_framework") ,
                "param_name" => "bg_grandient_hov_color_to",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Style", "mk_framework") ,
                "param_name" => "bg_gradient_hov_color_style",
                "value" => array(
                    __('Linear', "mk_framework") => "linear",
                    __('Radial', "mk_framework") => "radial"
                ) ,
                "group" => __('Hover Options', 'mk_framework') ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "dropdown",
                "heading" => __("Angle", "mk_framework") ,
                "param_name" => "bg_gradient_hov_color_angle",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => array(
                    __('Vertical ↓', "mk_framework") => "w",
                    __('Horizontal →', "mk_framework") => "horizontal",
                    __('Diagonal ↘', "mk_framework") => "diagonal_left_bottom",
                    __('Diagonal ↗', "mk_framework") => "diagonal_left_top",
                ) ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Gradient Fallback Color", "mk_framework") ,
                "param_name" => "bg_grandient_hov_color_fallback",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => "",
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'gradient_color'
                    )
                ) ,
            ) ,

            /**
             * Background Image Hover Effect
             * ==================================================================================
             */
            array(
                "type" => "dropdown",
                "heading" => __("Background Image Effect", "mk_framework") ,
                "param_name" => "bg_image_hov_effect",

                //"edit_field_class" => "vc_col-sm-3",
                "group" => __('Hover Options', 'mk_framework') ,
                "value" => array(
                    __('None', "mk_framework") => "none",
                    __('Zoom In', "mk_framework") => "zoom-in",
                    __('Blur', "mk_framework") => "blur",
                    __('Grayscale to Color', "mk_framework") => "grayscale",
                ) ,
                "description" => __("", "mk_framework") ,
                "dependency" => array(
                    'element' => "background_hov_color_style",
                    'value' => array(
                        'image'
                    )
                ) ,
            ) ,

            array(
                "type" => "toggle",
                "heading" => __("Elevation effect", "mk_framework") ,
                "param_name" => "elevation_effect",
                "value" => 'false',
                "group" => __('Hover Options', 'mk_framework') ,
            ) ,
            array(
                "type" => "range",
                "heading" => __("Distance", "mk_framework") ,
                "param_name" => "elevation_effect_distance",
                "description" => __("The distance of custom box from the background when it is elevated.", "mk_framework") ,
                "value" => "2",
                "min" => "2",
                "max" => "16",
                "step" => "1",
                "unit" => 'px',
                "group" => __('Hover Options', 'mk_framework') ,
                "dependency" => array(
                    'element' => "elevation_effect",
                    'value' => array(
                        'true',
                    )
                ) ,
            ) ,

            
        ) ,
        "js_view" => 'VcColumnView'
    ));