<?php
$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
$id = Mk_Static_Files::shortcode_id();
include( $path . '/config.php' );

// Dynamic styles.
Mk_Static_Files::addCSS(
	"
	.{$custom_css_class} {
		{$blend_mode_css}
		{$bg_color}
		{$border_color}
	}
	", $id
);
?>

<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $visibility ); ?> _ height-full">
	<?php echo wpb_js_remove_wpautop( $content ); ?>
</div>
