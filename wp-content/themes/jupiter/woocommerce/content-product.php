<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop, $mk_options;


// Store loop count we're currently on.
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Increase loop count.
$woocommerce_loop['loop']++;

// declare variable in case if none of the conditions below meets.
$columns = '';

$grid_width = $mk_options['grid_width'];
$content_width = $mk_options['content_width'];
$height = $mk_options['woo_loop_img_height'];

if ( is_shop() || is_product_category() || is_product_tag() ) {
	$columns = mk_get_option( 'shop_archive_columns', false );
	if ( 'default' === $columns ) {
		$columns = false;
	}
} else {
	if ( ! empty( $woocommerce_loop['columns'] ) ) {
		$columns = $woocommerce_loop['columns'];
	}
}

$layout = false;
if ( global_get_post_id() ) {
	$layout = get_post_meta( global_get_post_id(), '_layout', true );
	if ( isset( $_REQUEST['layout'] ) && ! empty( $_REQUEST['layout'] ) ) {
		$layout = esc_html( $_REQUEST['layout'] );
	}
	if ( is_singular( 'product' ) ) {
		$layout = $mk_options['woocommerce_single_layout'];
		$columns = false;
	}
} else {
	$layout = get_post_meta( mk_get_shop_id(), '_layout', true );
}

if ( $columns ) {

	switch ( $columns ) {
		case 1:
			$grid = 'mk--col--12-12';
			break;
		case 2:
			$grid = 'mk--col--1-2';
			break;
		case 3:
			$grid = 'mk--col--4-12';
			break;
		case 4:
			$grid = 'mk--col--3-12';
			break;
		default:
			$grid = 'mk--col--3-12';
			break;
	}

	// Custom columns taken from Theme Options > Shop > General > Product Loop Image Size option.
	$classes = 'item mk--col ' . $grid;
	$width = absint( $grid_width / $columns );
	$column_width = absint( $grid_width / $columns );

} else {
	if ( 'full' == $layout ) {
		$classes[] = 'item mk--col mk--col--3-12';
		$width = round( $grid_width / 4 ) - 28;
		$column_width = round( $grid_width / 4 );
	} else {
		$classes[] = 'item mk--col mk--col--4-12';
		$width = round( (($content_width / 100) * $grid_width) / 3 ) - 31;
		$column_width = round( $grid_width / 3 );
	}
}

?>

<article <?php wc_product_class( $classes ); ?>>
<div class="mk-product-holder">
		<div class="product-loop-thumb">
		<?php

		if ( 'false' == $mk_options['woocommerce_catalog'] ) {
			if ( ! $product->is_in_stock() ) {
				$availability = $product->get_availability();
				echo apply_filters( 'woocommerce_stock_html', '<span class="out-of-stock">' . __( 'OUT OF STOCK', 'mk_framework' ) . '</span>', $availability['availability'], $product );
			}

			if ( $product->is_on_sale() ) :
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale', 'mk_framework' ) . '</span>', $post, $product );
			endif;
		}

		$image_size = isset( $mk_options['woo_loop_image_size'] ) ? $mk_options['woo_loop_image_size'] : 'crop';

		if ( has_post_thumbnail() ) {


			echo '<a href="' . esc_url( get_permalink() ) . '" class="product-link">';



			$featured_image_src = Mk_Image_Resize::resize_by_id_adaptive( get_post_thumbnail_id(), $image_size, $width, $height, $crop = false, $dummy = true );

			echo '<img src="' . $featured_image_src['dummy'] . '" ' . $featured_image_src['data-set'] . ' class="product-loop-image" alt="' . the_title_attribute(
				array(
					'echo' => false,
				)
			) . '" title="' . the_title_attribute(
				array(
					'echo' => false,
				)
			) . '" itemprop="image" />';

			echo '<span class="product-loading-icon added-cart"></span>';

			$product_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );

			if ( ! empty( $product_gallery ) ) {
				$gallery = explode( ',', $product_gallery );
				$hover_image_id  = $gallery[0];

				$hover_image_src = Mk_Image_Resize::resize_by_id_adaptive( $hover_image_id, $image_size, $width, $height, $crop = false, $dummy = true );

				echo '<img src="' . $hover_image_src['dummy'] . '" ' . $hover_image_src['data-set'] . ' alt="' . the_title_attribute(
					array(
						'echo' => false,
					)
				) . '" class="product-hover-image" title="' . the_title_attribute(
					array(
						'echo' => false,
					)
				) . '">';


			}
			echo '</a>';

		} else {

			echo '<img src="' . Mk_Image_Resize::generate_dummy_image( $width, $height ) . '" alt="Placeholder" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" />';

		}
?>
	
	<?php
	if ( 'false' == $mk_options['woocommerce_catalog'] ) {
		ob_start();
		do_action( 'mk_woocommerce_shop_loop_rating' );
		$wc_rating_html = ob_get_clean();
		$wc_rating_html = trim( $wc_rating_html );
		$wc_rating_state = ! empty( $wc_rating_html ) ? 'with-rating' : 'without-rating';
		?>
	<div class="product-item-footer <?php echo esc_attr( $wc_rating_state ); ?>">
			<?php if ( ! empty( $wc_rating_html ) ) : ?>
				<div class="woocommerce-product-rating"><?php echo $wc_rating_html; ?></div>
			<?php endif; ?>
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>
<?php } ?>
</div>
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="mk-shop-item-detail">
			<h3 class="product-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>
			<?php
			if ( 'false' == $mk_options['woocommerce_catalog'] ) {
					do_action( 'woocommerce_after_shop_loop_item_title' );
			}
			?>

			<?php
			if ( 'true' == $mk_options['woocommerce_loop_show_desc'] ) :
				echo '<div class="product-item-desc">' . apply_filters( 'woocommerce_short_description', $post->post_excerpt ) . '</div>';
			endif;
			?>
		</div>
</div>
</article>
