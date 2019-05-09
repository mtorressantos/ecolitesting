<?php
	extract(
		shortcode_atts(
			array(
				'title'                     => '',
				'email'                     => get_bloginfo( 'admin_email' ),
				'style'                     => 'outline',
				'skin'                      => 'dark',
				'button_text'               => '',
				'bg_color'                  => '#f6f6f6',
				'border_color'              => '#f6f6f6',
				'button_color'              => '#373737',
				'button_font_color'         => '#ffffff',
				'font_color'                => '#373737',
				'line_skin_color'           => '#000',
				'line_button_text_color'    => 'dark',
				'phone'                     => 'false',
				'captcha'                   => 'true',
				'visibility'                => '',
				'gdpr_consent'              => 'true',
				'gdpr_consent_text'         => sprintf( __( 'I consent to %s collecting my details through this form.', 'mk_framework' ), get_bloginfo( 'name' ) ),
				'el_class'                  => '',
			), $atts
		)
	);
