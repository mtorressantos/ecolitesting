<form class="mk-contact-form" method="post" novalidate="novalidate">
			    <div class="quick__half">
				    <input type="text" placeholder="<?php esc_attr_e( 'Name*', 'mk_framework' ); ?>" required="required" id="name" name="name" class="text-input" value="" tabindex="<?php echo esc_attr( $id++ ); ?>" />
				</div>
				<div>
				    <input type="email" data-type="email" required="required" placeholder="<?php esc_attr_e( 'Email*', 'mk_framework' ); ?>" id="email" name="email" class="text-input" value="" tabindex="<?php echo esc_attr( $id++ ); ?>"  />
				</div>
				<textarea placeholder="<?php esc_attr_e( 'Message*', 'mk_framework' ); ?>" required="required" id="content" name="content" class="textarea" tabindex="<?php echo esc_attr( $id++ ); ?>"></textarea>
				<?php
				if ( 'true' == $captcha && Mk_Theme_Captcha::is_plugin_active() ) :
				?>
				<input placeholder="<?php esc_attr_e( 'Enter Captcha', 'mk_framework' ); ?>" type="text" data-type="captcha" name="captcha" class="captcha-form text-input full" required="required" autocomplete="off" />
				<a href="#" class="captcha-change-image"><?php esc_html_e( 'Not readable? Change text.', 'mk_framework' ); ?></a>
				<span class="captcha-image-holder">
					<img src="<?php esc_url( Mk_Theme_Captcha::create_captcha_image() ); ?>" class="captcha-image" alt="captcha txt"/>
				</span>
				<br/>
				<?php endif; ?>

				<?php
				if ( 'true' == $mk_options['quick_contact_gdpr_consent'] ) :
					$gdpr_tabindex = $id++;
					?>
				<div class="mk-quick-contact-gdpr-consent">
					<div>
					<input type="checkbox" name="contact_form_gdpr_check" id="gdpr_check_<?php echo esc_attr( $gdpr_tabindex ); ?>" class="mk-checkbox" required="required" value="" tabindex="<?php echo esc_attr( $gdpr_tabindex ); ?>" /><label for="gdpr_check_<?php echo esc_attr( $gdpr_tabindex ); ?>"><?php echo wp_kses_post( $mk_options['quick_contact_gdpr_consent_text'] ); ?></label>
					</div>
				</div>
				<?php endif; ?>

				<div class="btn-cont">
					<button tabindex="<?php echo esc_attr( $id++ ); ?>" class="mk-progress-button mk-contact-button accent-bg-color button" data-style="move-up">
						<span class="mk-progress-button-content"><?php esc_html_e( 'Send', 'mk_framework' ); ?></span>
						<span class="mk-progress">
							<span class="mk-progress-inner"></span>
						</span>
						<span class="state-success"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-checkmark' ); ?></span>
						<span class="state-error"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-close' ); ?></span>
					</button>
				</div>
				<?php wp_nonce_field( 'mk-contact-form-security', 'security' ); ?>
				<?php echo Mk_Send_Mail::contact_form_hidden_values( 15, 2342 ); ?>
				<div class="contact-form-message clearfix"></div>  
			</form>