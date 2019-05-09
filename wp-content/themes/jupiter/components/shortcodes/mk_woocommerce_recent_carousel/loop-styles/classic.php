<?php

$id = Mk_Static_Files::shortcode_id();

// WC Carousel classic styles.
$wc_carousel_style         = '';
$wc_carousel_style_a       = array();
$wc_carousel_style_a_hover = array();

// WC Carousel classic anchor styles.
$wc_carousel_style_a[] = "color: {$view_params['arrow_color']};";
$wc_carousel_style_a[] = "background-color: {$view_params['arrow_bg_color']};";
$wc_carousel_style    .= '.mk-woocommerce-carousel.classic-style #mk-swiper-' . $id . ' .swiper-arrows {' . implode( '', $wc_carousel_style_a ) . '}';

// WC Carousel classic hover anchor styles.
$wc_carousel_style_a_hover[] = "color: {$view_params['arrow_hover_color']};";
$wc_carousel_style_a_hover[] = "background-color: {$view_params['arrow_hover_bg_color']};";
$wc_carousel_style          .= '.mk-woocommerce-carousel.classic-style #mk-swiper-' . $id . ' .swiper-arrows:hover {' . implode( '', $wc_carousel_style_a_hover ) . '}';

Mk_Static_Files::addCSS( $wc_carousel_style, $id );

if (!empty($view_params['title'])) { ?>
    <h3 class="mk-fancy-title pattern-style"><span><?php echo $view_params['title']; ?></span>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="mk-woo-view-all page-bg-color"><?php _e('VIEW ALL', 'mk_framework'); ?></a></h3>
<?php } ?>


<div class="mk-swipe-slideshow">
	<div id="mk-swiper-<?php echo $id; ?>"
		class="mk-swiper-container  js-el"
		data-mk-component='SwipeSlideshow'
		data-swipeSlideshow-config='{
		    "effect" : "slide",
		    "slide" : ".products > .product",
			"slidesPerView" : <?php echo esc_attr( $view_params['per_view'] ); ?>,
		    "displayTime" : 6000,
		    "transitionTime" : 500,
		    "nav" : ".mk-swipe-slideshow-nav-<?php echo $id; ?>",
		    "hasNav" : true,
		    "pauseOnHover": true,
            "fluidHeight" : "toHighest" }'>

        <div class="mk-swiper-wrapper mk-slider-holder">

		<?php
			echo do_shortcode(
				'[' . ( 'false' === $view_params['featured'] ? 'recent_products' : 'featured_products' ) . '
					per_page="' . $view_params['per_page'] . '"
					orderby="' . $view_params['orderby'] . '"
					order="' . $view_params['order'] . '"
					category="' . $view_params['category'] . '"
					ids="' . $view_params['posts'] . '"
				]'
			);
		?>

		</div>

		<div class="swiper-navigation mk-swipe-slideshow-nav-<?php echo esc_attr( $id ); ?>">
			<a class="mk-swiper-prev swiper-arrows" data-direction="prev"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-jupiter-icon-arrow-left' ); ?></a>
			<a class="mk-swiper-next swiper-arrows" data-direction="next"><?php Mk_SVG_Icons::get_svg_icon_by_class_name( true, 'mk-jupiter-icon-arrow-right' ); ?></a>
		</div>

	</div>

</div>
