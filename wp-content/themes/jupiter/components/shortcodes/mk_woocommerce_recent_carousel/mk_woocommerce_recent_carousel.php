<?php
$path = pathinfo(__FILE__) ['dirname'];
include ($path . '/config.php');

// Add 'mk-sc-active' class if SC is active.
$sc_class = '';
if ( function_exists( 'mk_is_shop_customizer_active' ) ) {
	if ( mk_is_shop_customizer_active() ) {
		$sc_class = 'mk-sc-active';
	}
}

?>
<div class="mk-woocommerce-carousel <?php echo $style; ?>-style <?php echo $el_class . ' ' . $visibility . ' ' . $sc_class; ?>">


<?php 
	$atts = array(
		'per_page' => $per_page,
		'orderby' => $orderby,
		'category' => $category,
		'posts' => $posts,
		'author' => $author,
		'order' => $order,
		'featured' => $featured,
		'title' => $title,
		'image_size' => $image_size,
		'per_view'             => $per_view,
		'arrow_color'          => $arrow_color,
		'arrow_bg_color'       => $arrow_bg_color,
		'arrow_hover_color'    => $arrow_hover_color,
		'arrow_hover_bg_color' => $arrow_hover_bg_color,
	);
	echo mk_get_shortcode_view('mk_woocommerce_recent_carousel', 'loop-styles/' . $style, true, $atts);
?>

<div class="clearboth"></div>
</div>


