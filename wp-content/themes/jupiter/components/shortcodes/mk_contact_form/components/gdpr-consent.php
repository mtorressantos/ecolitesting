<div class="mk-form-row mk-gdpr-consent-check">
	<div class="s_form-all">
	<input type="checkbox" name="contact_form_gdpr_check" id="gdpr_check_<?php echo esc_attr( $view_params['tab_index'] ); ?>" class="mk-checkbox" required="required" value="" tabindex="<?php echo esc_attr( $view_params['tab_index'] ); ?>" />  <label for="gdpr_check_<?php echo esc_attr( $view_params['tab_index'] ); ?>"><?php echo wp_kses_post( $view_params['gdpr_consent_text'] ); ?></label>
	</div>
</div>
 
