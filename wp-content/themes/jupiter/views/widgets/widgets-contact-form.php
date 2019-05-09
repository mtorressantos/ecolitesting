<?php

class Artbees_Widget_Contact_Form extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'widget_contact_form',
			'description' => 'Displays a email contact form.',
		);
		WP_Widget::__construct( 'contact_form', THEME_SLUG . ' - ' . 'Contact Form', $widget_ops );
	}



	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Contact Us' : $instance['title'], $instance, $this->id_base );

		Mk_Send_Mail::update_contact_form_email( 2342, $args['id'], $instance['email'] );

		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : false;
		$captcha = ! empty( $instance['captcha'] ) ? $instance['captcha'] : false;
		$gdpr_consent = ! empty( $instance['gdpr_consent'] ) ? $instance['gdpr_consent'] : false;
		$gdpr_consent_text = $instance['gdpr_consent_text'];

		$id = mt_rand( 99,999 );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

?>

	<form class="mk-contact-form" method="post" novalidate="novalidate">
			<input type="text" placeholder="<?php esc_attr_e( 'Name', 'mk_framework' ); ?>" required="required" name="name" class="text-input" value="" tabindex="<?php echo esc_attr( $id++ ); ?>" />
			<?php if ( true == $phone ) { ?>
			<input type="text" placeholder="<?php esc_attr_e( 'Phone Number', 'mk_framework' ); ?>" name="phone" class="text-input" value="" tabindex="<?php echo esc_attr( $id++ ); ?>" />
			<?php } ?>
			<input type="email" data-type="email" required="required" placeholder="<?php esc_attr_e( 'Email', 'mk_framework' ); ?>" name="email" class="text-input" value="" tabindex="<?php echo esc_attr( $id++ ); ?>"  />
			<textarea placeholder="<?php esc_attr_e( 'Type your message...', 'mk_framework' ); ?>" required="required" name="content" class="textarea" tabindex="<?php echo esc_attr( $id++ ); ?>"></textarea>
			<?php if ( true == $captcha && Mk_Theme_Captcha::is_plugin_active() ) : ?>
			<input placeholder="<?php esc_attr_e( 'Enter Captcha', 'mk_framework' ); ?>" type="text" data-type="captcha" name="captcha" class="captcha-form text-input full" required="required" autocomplete="off" tabindex="<?php echo esc_attr( $id++ ); ?>" />
				<a href="#" class="captcha-change-image"><?php esc_html_e( 'Not readable? Change text.', 'mk_framework' ); ?></a>
				<span class="captcha-image-holder">
					<img src="<?php esc_url( Mk_Theme_Captcha::create_captcha_image() ); ?>" class="captcha-image" alt="captcha txt"/>
				</span> <br/>
			<?php endif; ?>
			<?php
			if ( true == $gdpr_consent ) :
				$gdpr_tabindex = $id++;
				?>
				<div class="mk-contact-widget-gdpr-consent">
				<div>
				<input type="checkbox" name="contact_form_gdpr_check" id="gdpr_check_<?php echo esc_attr( $gdpr_tabindex ); ?>" class="mk-checkbox" required="required" value="" tabindex="<?php echo esc_attr( $gdpr_tabindex ); ?>" /><label for="gdpr_check_<?php echo esc_attr( $gdpr_tabindex ); ?>"><?php echo wp_kses_post( $gdpr_consent_text ); ?></label>
					</div>
				</div>
				<?php endif; ?>

			<div class="mk-form-row-widget">
				   <button tabindex="<?php echo esc_attr( $id++ ); ?>" class="mk-progress-button mk-button contact-form-button mk-skin-button mk-button--dimension-flat text-color-light mk-button--size-small" data-style="move-up">
					<span class="mk-progress-button-content"><?php esc_html_e( 'Send message', 'mk_framework' ); ?></span>
					<span class="mk-progress">
						<span class="mk-progress-inner"></span>
					</span>
					<span class="state-success"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true,'mk-moon-checkmark' ); ?></span>
					<span class="state-error"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true,'mk-moon-close' ); ?></span>
				</button>
			</div>
			<?php wp_nonce_field( 'mk-contact-form-security', 'security' ); ?>
			<?php echo Mk_Send_Mail::contact_form_hidden_values( $args['id'], 2342 ); ?>
			<div class="contact-form-message clearfix"></div>  
	</form>
<?php
		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['email'] = filter_var( $new_instance['email'], FILTER_SANITIZE_EMAIL );
		$instance['phone'] = ! empty( $new_instance['phone'] ) ? true : false;
		$instance['captcha'] = ! empty( $new_instance['captcha'] ) ? true : false;
		$instance['gdpr_consent'] = ! empty( $new_instance['gdpr_consent'] ) ? true : false;
		$instance['gdpr_consent_text'] = $new_instance['gdpr_consent_text'];
		$instance['check_email'] = ($instance['email'] != $new_instance['email']) ? true : false;
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$email = isset( $instance['email'] ) ? $instance['email'] : get_bloginfo( 'admin_email' );
		$phone = isset( $instance['phone'] ) ? (bool) $instance['phone'] : false;
		$captcha = isset( $instance['captcha'] ) ? (bool) $instance['captcha'] : true;
		$gdpr_consent = isset( $instance['gdpr_consent'] ) ? $instance['gdpr_consent'] : true;
		$gdpr_consent_text = isset( $instance['gdpr_consent_text'] ) ? $instance['gdpr_consent_text'] : sprintf( __( 'I consent to %s collecting my details through this form.', 'mk_framework' ), get_bloginfo( 'name' ) );
		$check_email = isset( $instance['check_email'] ) ? $instance['check_email'] : false;

		$captcha_plugin_status = '';
		if ( ! Mk_Theme_Captcha::is_plugin_active() ) {
			$captcha_plugin_status = '<span style="color:red">Captcha plugin is not active! please visit Appearance > Install Plugins to install it.</span>';
		}
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'mk_framework' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<?php
		if ( $check_email ) {
			echo '<span style="color:red">Check your email, we have stripped special chars.</span>';
		}
		?>
		<p><label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php esc_html_e( 'Email:', 'mk_framework' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo $email; ?>" /></p>
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'captcha' ); ?>" name="<?php echo $this->get_field_name( 'captcha' ); ?>"<?php checked( $captcha ); ?> />
		<label for="<?php echo $this->get_field_id( 'captcha' ); ?>"><?php esc_html_e( 'Show Captcha?', 'mk_framework' ); ?></label><?php echo $captcha_plugin_status; ?>
		</p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>"<?php checked( $phone ); ?> />
		<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'Show Phone Number Field?', 'mk_framework' ); ?></label></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'gdpr_consent' ); ?>" name="<?php echo $this->get_field_name( 'gdpr_consent' ); ?>"<?php checked( $gdpr_consent ); ?> />
		<label for="<?php echo $this->get_field_id( 'gdpr_consent' ); ?>"><?php esc_html_e( 'GDPR Consent Check', 'mk_framework' ); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'gdpr_consent_text' ); ?>"><?php esc_html_e( 'GDPR Consent Checkbox Text:', 'mk_framework' ); ?></label>
		<textarea style="width:98%" rows="6" id="<?php echo $this->get_field_id( 'gdpr_consent_text' ); ?>" name="<?php echo $this->get_field_name( 'gdpr_consent_text' ); ?>"><?php echo $gdpr_consent_text; ?></textarea></p>

<?php

	}

}

register_widget( 'Artbees_Widget_Contact_Form' );
