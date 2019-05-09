<?php
global $mk_options;

$mk_footer_class = $show_footer = $disable_mobile = $footer_status = '';

$post_id = global_get_post_id();
if ( $post_id ) {
	$show_footer = get_post_meta( $post_id, '_template', true );
	$cases = array( 'no-footer', 'no-header-footer', 'no-header-title-footer', 'no-footer-title' );
	$footer_status = in_array( $show_footer, $cases );
}

if ( $mk_options['disable_footer'] == 'false' || ( $footer_status ) ) {
	$mk_footer_class .= ' mk-footer-disable';
}

if ( $mk_options['footer_type'] == '2' ) {
	$mk_footer_class .= ' mk-footer-unfold';
}


$boxed_footer = (isset( $mk_options['boxed_footer'] ) && ! empty( $mk_options['boxed_footer'] )) ? $mk_options['boxed_footer'] : 'true';
$footer_grid_status = ($boxed_footer == 'true') ? ' mk-grid' : ' fullwidth-footer';
$disable_mobile = ($mk_options['footer_disable_mobile'] == 'true' ) ? $mk_footer_class .= ' disable-on-mobile' : ' ';

?>

<section id="mk-footer-unfold-spacer"></section>

<section id="mk-footer" class="<?php echo $mk_footer_class; ?>" <?php echo get_schema_markup( 'footer' ); ?>>
	<?php if ( $mk_options['disable_footer'] == 'true' && ! $footer_status ) : ?>
	<div class="footer-wrapper<?php echo $footer_grid_status; ?>">
		<div class="mk-padding-wrapper">
			<?php mk_get_view( 'footer', 'widgets' ); ?>
			<div class="clearboth"></div>
		</div>
	</div>
	<?php endif; ?>
	<?php
	if ( $mk_options['disable_sub_footer'] == 'true' && ! $footer_status ) {
		mk_get_view(
			'footer', 'sub-footer', false, [
				'footer_grid_status' => $footer_grid_status,
			]
		);
	}
?>
</section>
</div>
<?php
	global $is_header_shortcode_added;

	/**
	 * After new changed, it will return null if there is no header shortcode
	 * added. Need to check and save it as array if it's null to avoid error.
	 *
	 * @since 6.0.3
	 * @see /components/shortcodes/mk_header/config.php
	 */
	if ( ! is_array( $is_header_shortcode_added ) ) {
		$is_header_shortcode_added = array();
	}

if ( $mk_options['seondary_header_for_all'] === 'true' || get_header_style() === '3' || in_array( '3', $is_header_shortcode_added, true ) ) {
	mk_get_header_view(
		'holders', 'secondary-menu', [
			'header_shortcode_style' => $is_header_shortcode_added,
		]
	);
}
?>
</div>

<div class="bottom-corner-btns js-bottom-corner-btns">
<?php
if ( 'false' !== $mk_options['go_to_top'] ) {
	mk_get_view( 'footer', 'navigate-top' );
}

if ( 'false' !== $mk_options['disable_quick_contact'] ) {
	mk_get_view( 'footer', 'quick-contact' );
}

		do_action( 'add_to_cart_responsive' );
?>
</div>


<?php
if ( $mk_options['header_search_location'] === 'fullscreen_search' ) {
	mk_get_header_view( 'global', 'full-screen-search' );
}
?>

<?php if ( ! empty( $mk_options['body_border'] ) && $mk_options['body_border'] === 'true' ) { ?>
	<div class="border-body border-body--top"></div>
	<div class="border-body border-body--left border-body--side"></div>
	<div class="border-body border-body--right border-body--side"></div>
	<div class="border-body border-body--bottom"></div>
<?php } ?>

	<?php wp_footer(); ?>

	<?php do_action( 'mk_theme_before_body_closing' ); ?>
</body>
</html>
