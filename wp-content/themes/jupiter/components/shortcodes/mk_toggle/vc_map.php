<?php
vc_map(array(
    "name" => __("Toggle", "mk_framework"),
    "base" => "mk_toggle",
	'html_template' => dirname( __FILE__ ) . '/mk_toggle.php',
    "wrapper_class" => "clearfix",
    'icon' => 'icon-mk-toggle vc_mk_element-icon',
    "category" => __('Typography', 'mk_framework'),
    'description' => __( 'Expandable toggle element', 'mk_framework' ),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Style", "mk_framework"),
            "param_name" => "style",
            "width" => 150,
            "value" => array(
                __('Simple', "mk_framework") => "simple",
                __('Fancy', "mk_framework") => "fancy"
            ),
            "description" => __("", "mk_framework")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Toggle Title", "mk_framework"),
            "param_name" => "title",
            "value" => ""
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Toggle Content.", "mk_framework"),
            "param_name" => "content",
            "value" => __("", "mk_framework")
        ),
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon for Title", "mk_framework"),
            "param_name" => "icon",
            "value" => "",
             "dependency" => array(
                'element' => "style",
                'value' => array(
                    'fancy'
                )
            ),
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "mk_framework"),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", "mk_framework")
        )
    )
));