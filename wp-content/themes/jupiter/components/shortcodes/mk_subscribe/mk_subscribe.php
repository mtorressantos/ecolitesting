<?php

global $mk_options;
$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$mailchimp_api_key = trim( $mk_options['mailchimp_api_key'] );

if ( empty( $mailchimp_api_key ) && ! defined( 'MK_DEMO_SITE' ) ) {
	printf( __( 'Please add MailChimp API Key in <a href="%s" target="_blank">Theme Options > General Settings > API Integrations</a>', 'mk_framework' ), admin_url( 'admin.php?page=theme_options#api_integrations' ) );
}

$id = Mk_Static_Files::shortcode_id();

$tabindex = mt_rand( 99,999 );
$tabindex_email = $tabindex++;
$tabindex_gdpr = $tabindex++;
$tabindex_button = $tabindex++;


$container_class[] = $visibility;
if ( '' != $animation ) {
	$container_class[] = get_viewport_animation_class( $animation );
}
$container_class[] = $subscribe_size . '-size';
$container_class[] = $el_class;

?>
<div id="mk-subscribe-<?php echo esc_attr( $id ); ?>" class="mk-subscribe _ width-full <?php echo esc_attr( implode( ' ', $container_class ) ); ?>">
	<form action="mk_ajax_subscribe" method="post" class="mk-subscribe--form">
		<div class="table width-full">
			<div class="mk-subscribe--form-column _ table-cell">
				<input type="email" required="required" tabindex="<?php echo esc_attr( $tabindex_email ); ?>" placeholder="<?php echo esc_attr( $placeholder_text ); ?>" name="mk-subscribe--email" class="mk-subscribe--email" autocomplete="off">
				<input type="hidden" name="mk-subscribe--list-id" value="<?php echo esc_attr( $list_id ); ?>" class="mk-subscribe--list-id">
				<input type="hidden" name="mk-subscribe--optin" value="<?php echo esc_attr( $optin ); ?>" class="mk-subscribe--optin">
			</div>
			<div class="mk-subscribe--form-column _ table-cell">
				<button id="mk-subscribe--button_<?php echo esc_attr( $id ); ?>" tabindex="<?php echo esc_attr( $tabindex_button ); ?>" class="mk-subscribe--button _ font-weight-b"> 
					<span><?php echo wp_kses_post( $button_text ); ?></span>
				</button>
			</div>
		</div>
		<?php if ( 'true' == $gdpr_consent ) : ?>
		<div class="mk-mailchimp-gdpr-consent">
				<input type="checkbox" name="mailchimp_gdpr_check_<?php echo esc_attr( $id ); ?>" id="mailchimp_gdpr_check_<?php echo esc_attr( $id ); ?>" class="mk-checkbox" required="required" value="" tabindex="<?php echo esc_attr( $tabindex_gdpr ); ?>" /><label for="mailchimp_gdpr_check_<?php echo esc_attr( $id ); ?>"><?php echo wp_kses_post( $gdpr_consent_text ); ?></label>
		</div>
	<?php endif; ?>
	</form>
	<div id="mk-subscribe--message" class="mk-subscribe--message _ block width-full"></div>
</div>

<?php

/**
 * Custom CSS Output
 * ==================================================================================*/
Mk_Static_Files::addCSS(
	'
	#mk-subscribe-' . $id . ' .mk-subscribe--email,
	#mk-subscribe-' . $id . ' .mk-subscribe--button {
		border-radius: ' . $corner_radius . 'px;
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--form-column {
		padding-right: ' . $space_between . 'px;
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email {
		background-color: ' . $input_bg_color . ';
		color: ' . $input_placeholder_color . ';
		border: ' . $input_border_width . 'px ' . $input_border_style . ' ' . $input_border_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email::-webkit-input-placeholder {
		color: ' . $input_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:-ms-input-placeholder {
		color: ' . $input_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email::-ms-input-placeholder {
		color: ' . $input_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email::-moz-placeholder {
		color: ' . $input_placeholder_color . ';
		opacity: 1;
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:focus {
		background-color: ' . $input_focus_bg_color . ';
		border: ' . $input_border_width . 'px ' . $input_border_style . ' ' . $input_border_color . ';
		color: ' . $input_focus_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:focus::-webkit-input-placeholder {
		color: ' . $input_focus_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:focus:-ms-input-placeholder {
		color: ' . $input_focus_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:focus::-ms-input-placeholder {
		color: ' . $input_focus_placeholder_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--email:focus::-moz-placeholder {
		color: ' . $input_focus_placeholder_color . ';
		opacity: 1;
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--button {
		background-color: ' . $btn_bg_color . ';
		color: ' . $btn_text_color . ';
		border: ' . $btn_border_width . 'px ' . $btn_border_style . ' ' . $btn_border_color . ';
	}
	#mk-subscribe-' . $id . ' .mk-subscribe--button:hover {
		background-color: ' . $btn_hover_bg_color . ';
		color: ' . $btn_hover_text_color . ';
		border: ' . $btn_border_width . 'px ' . $btn_border_style . ' ' . $btn_hover_border_color . ';
	}
', $id
);


