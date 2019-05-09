<?php
/**
 * Template part for custom headers built by Header Builder.
 *
 * @since 5.9.0 Introduced.
 * @since 5.9.4 Add parameters on HB_Grid declaration.
 * @since 5.9.5 Replace render_markup output with do_action hb_grid_markup hook.
 *
 * @package Jupiter
 */

global $mk_options;

$is_shortcode = isset( $view_params['is_shortcode'] ) ? $view_params['is_shortcode'] : false; ?>

<header class="hb-custom-header" <?php echo wp_kses_post( get_schema_markup( 'header' ) ); ?>>
	<div class="hb-devices">
		<?php
			/**
			 * Render all the markup of elements.
			 *
			 * @since 4.9.5
			 *
			 * @see  HB_Grid->render_markup() Render Header Builder HTML markup.
			 */
			do_action( 'hb_grid_markup' );

			/**
			 * Render additional HB markup outside hb-device class.
			 *
			 * @since 4.9.5
			 */
			do_action( 'hb_grid_extra' );
		?>
	</div>
	<?php
		mk_get_view( 'layout', 'title', false, [
			'is_shortcode' => $is_shortcode,
		] );
	?>
</header>
