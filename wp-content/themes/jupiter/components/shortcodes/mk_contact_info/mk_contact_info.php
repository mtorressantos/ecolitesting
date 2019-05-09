<?php
	$phpinfo = pathinfo( __FILE__ );
	$path = $phpinfo['dirname'];
	include( $path . '/config.php' );

?>   

<div class="widget_contact_info mk-contactinfo-shortcode a_margin-bottom-20 <?php echo esc_attr( $visibility ); ?>  <?php echo esc_attr( $el_class ); ?>" <?php echo get_schema_markup( 'person' ); ?>>

	<?php
	mk_get_view(
		'global', 'shortcode-heading', false, [
			'title' => $title,
		]
	);
?>
	<ul>
		<?php if ( ! empty( $person ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-user-7', 16 ); ?></i><span itemprop="name"><?php echo wp_kses_post( $person ); ?></span></li>
		<?php } ?>

		<?php if ( ! empty( $company ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-office', 16 ); ?></i><span itemprop="jobTitle"><?php echo wp_kses_post( $company ); ?></span></li>
		<?php } ?>

		<?php if ( ! empty( $address ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-home', 16 ); ?></i><span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><?php echo wp_kses_post( $address ); ?></span></li>
		<?php } ?>

		<?php if ( ! empty( $phone ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-phone', 16 ); ?></i><span><a itemprop="telephone" href="tel:<?php echo esc_url( $phone ); ?>"><?php echo wp_kses_post( $phone ); ?></a></span></li>
		<?php } ?>

		<?php if ( ! empty( $fax ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-print', 16 ); ?></i><span><?php echo wp_kses_post( $fax ); ?></li></span>
		<?php } ?>

		<?php
		if ( ! empty( $email ) ) {
			$email = str_replace( '@', '&#64;', $email );
		?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-envelope', 16 ); ?></i><span><a itemprop="email" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo wp_kses_post( $email ); ?></a></span></li>
		<?php } ?>

		<?php if ( ! empty( $website ) ) { ?>
			<?php
			if ( is_html( $website ) ) :
			?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true,'mk-icon-globe', 16 ); ?></i><?php echo wp_kses_post( $website ); ?></li> <?php else : ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-icon-globe', 16 ); ?></i><span><a href="<?php echo esc_url( $website ); ?>" itemprop="url"><?php echo wp_kses_post( $website ); ?></a></span></li><?php endif; ?>
		<?php } ?>

		<?php if ( ! empty( $skype ) ) { ?>
			<li><i><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-moon-skype', 16 ); ?></i><span><a href="skype:<?php echo esc_url( $skype ); ?>?call"><?php echo wp_kses_post( $skype ); ?></a></span></li>
		<?php } ?>
	</ul>
</div>
