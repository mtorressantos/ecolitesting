<?php

$id = Mk_Static_Files::shortcode_id();

$query = array(
	'post_type' => 'product',
	'meta_query' => array(
		array(
			'key' => '_visibility',
			'value' => array(
				'catalog',
				'visible',
			),
			'compare' => 'IN',
		),
	),
);

if ( mk_is_woocommerce_version( '3.0' ) ) {
	$query['meta_query'] = [];
	$query['tax_query'] = array(
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'exclude-from-catalog',
			'operator' => 'NOT IN',
		),
	);
}

if ( isset( $view_params['per_page'] ) && ! empty( $view_params['per_page'] ) ) {
	$query['posts_per_page'] = $view_params['per_page'];
}

if ( isset( $view_params['author'] ) && ! empty( $view_params['author'] ) ) {
	$query['author'] = $view_params['author'];
}

if ( isset( $view_params['posts'] ) && ! empty( $view_params['posts'] ) ) {
	$query['post__in'] = explode( ',', $view_params['posts'] );
}

if ( ! empty( $view_params['category'] ) ) {
	$query['tax_query'] = array(
		array(
			'taxonomy'      => 'product_cat',
			'terms'         => array_map( 'sanitize_title', explode( ',', $view_params['category'] ) ),
			'field'         => 'slug',
		),
	);
}


if ( 'true' == $view_params['featured'] ) {
	$query['tax_query'][] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => 'featured',
		'operator' => 'IN',
	);
}

$query['orderby'] = $view_params['orderby'];
$query['order'] = $view_params['order'];

?>

<div class="mk-swipe-slideshow">
	<div id="mk-swiper-<?php echo esc_attr( $id ); ?>"
		data-mk-component='SwipeSlideshow'
		data-swipeSlideshow-config='{
			"effect" : "slide",
			"slide" : ".mk-slider-holder > .swiper-slide",
			"slidesPerView" : "<?php echo esc_attr( $view_params['per_view'] ); ?>",
			"displayTime" : 5000,
			"transitionTime" : 500,
			"nav" : ".mk-swipe-slideshow-nav-<?php echo esc_attr( $id ); ?>",
			"hasNav" : true,
			"fluidHeight" : "toHighest" }'
		class="mk-swiper-container  js-el">

		<div class="mk-swiper-wrapper mk-slider-holder">
			<?php

			$r = new WP_Query( $query );
			if ( $r->have_posts() ) {
				while ( $r->have_posts() ) :
					$r->the_post();

					$featured_image_src = Mk_Image_Resize::resize_by_id( get_post_thumbnail_id(), $view_params['image_size'], false, false, $crop = false, $dummy = true );
					$src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), $view_params['image_size'], true );
					$image_dimensions = Mk_Image_Resize::get_native_image_size_dimensions( $view_params['image_size'], $src_array );
				?>
					<div class="swiper-slide">
						<div class="item-holder">

								<a class="mk-lightbox" data-fancybox-group="<?php echo esc_attr( $id ); ?>" href="<?php echo esc_url( get_permalink() ); ?>">
									<img alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" width="<?php echo esc_attr( $image_dimensions['width'] ); ?>" height="<?php echo esc_attr( $image_dimensions['height'] ); ?>" src="<?php echo esc_attr( $featured_image_src ); ?>" itemprop="image" />

									<i class="mk-jupiter-icon-plus-circle"><span>&nbsp;</span></i>
										<span class="image-hover-overlay"></span>
								</a>

								<h3 class="the-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>

								<?php
								wc_get_template( 'loop/price.php' );

								echo do_shortcode(
									'[mk_button
                                                            dimension="outline"
                                                            corner_style="pointed"
                                                            size="small"
                                                            outline_skin="custom"
                                                            outline_active_color="#000000"
                                                            outline_hover_color="#ffffff"
                                                            bg_color="#f97352"
                                                            btn_hover_bg="#252525"
                                                            text_color="dark"
                                                            icon="mk-moon-cart-plus"
                                                            icon_anim="side"
                                                            url="' . esc_url( get_permalink( get_the_ID() ) ) . '"
                                                            target="_self"
                                                            align="center"
                                                            fullwidth="false"
                                                            margin_top="0"
                                                            margin_bottom="0"]' . __( 'BUY NOW', 'mk_framework' ) . '[/mk_button]'
								);
								?>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			}
			?>
		</div>
		<div class="swiper-navigation mk-swipe-slideshow-nav-<?php echo esc_attr( $id ); ?>">
			<a class="mk-swiper-prev swiper-arrows" data-direction="prev"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-jupiter-icon-arrow-left' ); ?></a>
			<a class="mk-swiper-next swiper-arrows" data-direction="next"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-jupiter-icon-arrow-right' ); ?></a>
		</div>
	</div>
</div>
