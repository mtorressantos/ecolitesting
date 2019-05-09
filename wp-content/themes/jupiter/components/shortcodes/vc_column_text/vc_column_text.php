<?php
$path = pathinfo( __FILE__ ) ['dirname'];

include( $path . '/config.php' );

$id = Mk_Static_Files::shortcode_id();

$class[] = 'mk-text-block';
$class[] = get_viewport_animation_class( $animation );
$class[] = $visibility;
$class[] = $el_class;

Mk_Static_Files::addCSS(
	"
	#text-block-{$id} {
	     margin-bottom:{$margin_bottom}px;
	     text-align:{$align};
	}
", $id
);

if ( ! empty( $css ) ) { ?>
	<div class="<?php echo vc_shortcode_custom_css_class( $css, ' ' ); ?>">
<?php } ?>

<div id="text-block-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">

	<?php
	mk_get_view(
		'global', 'shortcode-heading', false, [
			'title' => $title,
			'pattern' => $pattern,
			'align' => $align,
		]
	);
	?>

	<?php echo wpb_js_remove_wpautop( $content, true ); ?>

	<div class="clearboth"></div>
</div>

<?php if ( ! empty( $css ) ) { ?>
	</div>
<?php } ?>
