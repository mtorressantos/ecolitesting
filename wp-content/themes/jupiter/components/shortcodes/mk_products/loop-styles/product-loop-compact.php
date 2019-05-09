<?php global $mk_options; ?>
<article <?php echo post_class( 'item mk--col ' . $view_params['product_col'] . get_viewport_animation_class( $view_params['animation'] ) ); ?>  >
	<div class="mk-product-holder">
		<div class="product-loop-thumb">
			<?php
			if ( 'false' == $mk_options['woocommerce_catalog'] ) {
						echo $view_params['out_of_stock_badge'];
						echo $view_params['sale_of_stock_badge'];
			}
			?>
			<a href="<?php echo esc_url( $view_params['product_link'] ); ?>" title="<?php echo esc_attr( $view_params['thumb_title'] ); ?>" class="product-link">
				<img src="<?php echo $view_params['thumb_image']['dummy']; ?>" <?php echo $view_params['thumb_image']['data-set']; ?> class="product-loop-image" alt="<?php echo $view_params['thumb_title']; ?>" title="<?php echo $view_params['thumb_title']; ?>" itemprop="image">
				<span class="product-loading-icon added-cart"></span>
				<?php if ( ! empty( $view_params['thumb_hover_image'] ) ) { ?>
					<img src="<?php echo esc_attr( $view_params['thumb_hover_image']['dummy'] ); ?>" <?php echo $view_params['thumb_hover_image']['data-set']; ?> alt="<?php echo esc_attr( $view_params['thumb_title'] ); ?>" class="product-hover-image" title="<?php echo esc_attr( $view_params['thumb_title'] ); ?>" >
				<?php } ?>	
			</a>
			<?php
				ob_start();
				do_action( 'mk_woocommerce_shop_loop_rating' );
				$wc_rating_html = ob_get_clean();
				$wc_rating_state = ! empty( $wc_rating_html ) ? 'with-rating' : 'without-rating';
			?>
			<div class="product-item-footer <?php echo esc_attr( $wc_rating_state ); ?>">
			<?php
			if ( 'false' == $mk_options['woocommerce_catalog'] ) {

				if ( ! empty( $wc_rating_html ) ) :
					?>
					<div class="woocommerce-product-rating"><?php echo $wc_rating_html; ?></div>
				<?php endif; ?>

				<?php
					global $product;

				switch ( $product->get_type() ) {
					case 'variable':
						$icon_class = 'mk-icon-plus';
						break;
					case 'grouped':
						$icon_class = 'mk-moon-search-3';
						break;
					case 'external':
						$icon_class = 'mk-moon-search-3';
						break;
					default:
						$icon_class = 'mk-moon-cart-plus';
						break;
				}

				if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
					$icon_class = 'mk-moon-search-3';
				}

					$button_class = implode(
						' ', array(
							'product_loop_button',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
						)
					);


					echo apply_filters(
						'woocommerce_loop_add_to_cart_link',
						sprintf(
							'<a rel="nofollow" href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" class="%s">%s%s</a>',
							esc_url( $product->add_to_cart_url() ),
							esc_attr( $product->get_id() ),
							esc_attr( $product->get_sku() ),
							esc_attr( $button_class ),
							Mk_SVG_Icons::get_svg_icon_by_class_name( false,$icon_class,16 ),
							esc_html( $product->add_to_cart_text() )
						),
						$product
					);
			}
				?>

			</div>
		</div>
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		<div class="mk-shop-item-detail">
			<h3 class="product-title">
				<a href="<?php echo esc_url( $view_params['product_link'] ); ?>" title="<?php echo esc_attr( $view_params['thumb_title'] ); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			<?php
			if ( 'false' == $mk_options['woocommerce_catalog'] ) {
				do_action( 'woocommerce_after_shop_loop_item_title' );
			}
			?>
			<div class="product-item-desc">
				<?php echo $view_params['item_desc']; ?>
			</div>
		</div>
	</div>
</article>
