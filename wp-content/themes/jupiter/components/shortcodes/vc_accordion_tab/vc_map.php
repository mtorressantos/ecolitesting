<?php
vc_map(array(
    "name" => __("Accordion Section", "mk_framework") ,
    "base" => "vc_accordion_tab",
	'html_template' => dirname( __FILE__ ) . '/vc_accordion_tab.php',
    "allowed_container_element" => 'vc_row',
    "is_container" => true,
    "content_element" => false,
	'front_enqueue_js' => THEME_COMPONENTS . '/shortcodes/vc_accordion_tab/vc_front.js',
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "mk_framework") ,
            "param_name" => "title",
            "description" => __("Accordion section title.", "mk_framework")
        ) ,
        array(
            "type" => "icon_selector",
            "heading" => __("Add Icon (optional)", "mk_framework") ,
            "param_name" => "icon",
            "value" => "",
        )
    ) ,
    'js_view' => 'VcAccordionTabView'
));
