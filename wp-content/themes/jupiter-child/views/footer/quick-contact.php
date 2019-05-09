<?php

/**
 * template part for footer quick contact. views/footer
 *
 * @author      Artbees
 * @package     jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if ( is_singular( array( 'post', 'portfolio' ) ) && $mk_options['quick_contact_on_single'] == 'false' ) {
	return false;
}

$captcha = isset( $mk_options['captcha_quick_contact'] ) ? $mk_options['captcha_quick_contact'] : 'true';

$id = mt_rand( 999, 9999 );

Mk_Send_Mail::update_contact_form_email( 2342, 15, $mk_options['quick_contact_email'] );

?>
	<div class="mk-quick-contact-wrapper js-bottom-corner-btn js-bottom-corner-btn--contact">
			
		<a href="#" class="mk-quick-contact-link"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-envelope', 20 ); ?></a>
		<div id="mk-quick-contact">
			<div class="mk-quick-contact-title"><?php echo esc_html( $mk_options['quick_contact_title'] ); ?></div>
			<p><?php echo esc_html( $mk_options['quick_contact_desc'] ); ?></p>
			<?php echo do_shortcode("[contact-form-7 id='5594' title='Untitled' ]"); ?>
			
			
			<div class="bottom-arrow"></div>
		</div>
	</div>
