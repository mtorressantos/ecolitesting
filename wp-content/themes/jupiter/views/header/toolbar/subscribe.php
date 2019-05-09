<?php
/**
 * template part for MailChimp Subscribe. views/header/toolbar
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */


global $mk_options;

$mailchimp_list_id = ! empty( $mk_options['mailchimp_list_id'] ) ? $mk_options['mailchimp_list_id'] : '';
$mailchimp_optin   = ! empty( $mk_options['mailchimp_optin'] ) ? $mk_options['mailchimp_optin'] : false;
$mailchimp_api_key = ! empty( $mk_options['mailchimp_api_key'] ) ? true : false;

?>
<div class="mk-header-signup">
	
	<a href="#" id="mk-header-subscribe-button" class="mk-subscribe-link mk-toggle-trigger"><?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-moon-envelop', 16) ?><?php _e('Subscribe', 'mk_framework');?></a>

	<div class="mk-header-subscribe mk-box-to-trigger">
		<form action="mk_ajax_subscribe" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<label for="mce-EMAIL"><?php _e('Subscribe to newsletter', 'mk_framework');?></label>
			<input type="email" value="" name="mk-subscribe--email" class="mk-subscribe--email email text-input" id="mce-EMAIL" placeholder="<?php _e('Email Address', 'mk_framework');?>" required>
			<input type="hidden" name="mk-subscribe--list-id" class="mk-subscribe--list-id" value="<?php echo $mailchimp_list_id; ?>">
			<input type="hidden" name="mk-subscribe--optin" class="mk-subscribe--optin" value="<?php echo $mailchimp_optin; ?>">
			<input type="submit" value="<?php _e('Subscribe', 'mk_framework');?>" name="subscribe" id="mc-embedded-subscribe" class="accent-bg-color button">
		</form>
		<div class="mk-subscribe--message">
			<?php 
				if ( ! $mailchimp_api_key && ! defined( 'MK_DEMO_SITE' ) ) { 
					printf( __( 'Please add MailChimp API Key in <a href="%s" target="_blank">Theme Options > General Settings > API Integrations</a>', 'mk_framework' ), admin_url('admin.php?page=theme_options#api_integrations') );
				} 
			?>
		</div>
	</div>

</div>