<?php
extract(
	shortcode_atts(
		array(
			'placeholder_text'          => 'Your e-mail',
			'button_text'               => 'SEND',
			'list_id'                   => '',
			'optin'                     => '',
			'el_class'                  => '',
			'animation'                 => '',
			'corner_radius'             => 0,
			'space_between'             => 0,
			'subscribe_size'            => 'large',
			'btn_bg_color'              => '',
			'btn_text_color'            => '#eee',
			'btn_border_style'          => 'solid',
			'btn_border_width'          => '1',
			'btn_border_color'          => '#eee',
			'input_bg_color'            => '#ffffff',
			'input_placeholder_color'   => '#eee',
			'input_border_style'        => 'solid',
			'input_border_width'        => '1',
			'input_border_color'        => '#eee',
			'btn_hover_bg_color'        => '',
			'btn_hover_text_color'      => '',
			'btn_hover_border_color'    => '',
			'input_focus_bg_color'      => '',
			'input_focus_placeholder_color'  => '',
			'gdpr_consent'              => 'true',
			'gdpr_consent_text'         => sprintf( __( 'I consent to %s collecting my details through this form.', 'mk_framework' ), get_bloginfo( 'name' ) ),
			'visibility'                => '',
		), $atts
	)
);
global $mk_options;

$list_id = empty($list_id) ? $mk_options['mailchimp_list_id'] : $list_id;