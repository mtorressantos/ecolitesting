<?php
$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );


$id = Mk_Static_Files::shortcode_id();

$cat = ! empty( $categories ) ? $categories : $cat;
$query = mk_wp_query(
	array(
		'post_type' => 'portfolio',
		'count' => $count,
		'offset' => $offset,
		'categories' => $cat,
		'author' => $author,
		'posts' => $posts,
		'orderby' => $orderby,
		'order' => $order,
	)
);

$loop = $query['wp_query'];

?>

<div id="portfolio-carousel-wrapper-<?php echo esc_attr( $id ); ?>" class="portfolio-carousel style-<?php echo esc_attr( $style ); ?> <?php echo esc_attr( $el_class ) . ' ' . esc_attr( $visibility ); ?>">

	<?php if ( ! empty( $view_all ) && $view_all != '*' ) { ?>

				<h3 class="mk-fancy-title pattern-style"><span><?php echo $title; ?></span>
		<a href="<?php echo esc_url( get_permalink( $view_all ) ); ?>" class="view-all page-bg-color"><?php echo $view_all_text; ?></a></h3>
		<div class="clearfix"></div>
		<?php
		$direction_vav = 'true';
}

	$slider_atts[] = 'data-id="' . esc_attr( $id ) . '"';
	$slider_atts[] = 'data-style="' . esc_attr( $style ) . '"';
	$slider_atts[] = 'data-direction-nav="' . esc_attr( $direction_vav ) . '"';
	$slider_atts[] = 'data-show-items="' . esc_attr( $show_items ) . '"';
	$slider_atts[] = 'data-animationSpeed="' . esc_attr( $animation_speed ) . '"';
	$slider_atts[] = 'data-slideshowSpeed="' . esc_attr( $slideshow_speed ) . '"';
	$slider_atts[] = 'data-pauseOnHover="' . esc_attr( $pause_on_hover ) . '"';
	?>

	<div id="portfolio-carousel-<?php echo esc_attr( $id ); ?>" class="mk-flexslider" <?php echo implode( $slider_atts, ' ' ); ?>>
		<ul class="mk-flex-slides">

		<?php

		while ( $loop->have_posts() ) :
				$loop->the_post();

				$post_type = get_post_meta( get_the_ID(), '_single_post_type', true );
				$post_type = ! empty( $post_type ) ? $post_type : 'image';

				$atts = array(
					'image_size' => $image_size,
					'id' => $id,
					'hover_scenarios' => $hover_scenarios,
					'meta_type' => $meta_type,
					'post_type' => $post_type,

				);

				echo mk_get_shortcode_view( 'mk_portfolio_carousel', 'loop-styles/' . $style, true, $atts );

		endwhile;
			wp_reset_query();
		?>

		</ul>
	</div>
<div class="clearboth"></div>
</div>

<?php
if ( empty( $title ) ) {
	Mk_Static_Files::addCSS(
		'
        @media handheld, only screen and (max-width: 767px) {
            #portfolio-carousel-wrapper-' . $id . ' .mk-fancy-title.pattern-style span {
                padding: 0!important;
            }
        }
    ', $id
	);
}
?>
