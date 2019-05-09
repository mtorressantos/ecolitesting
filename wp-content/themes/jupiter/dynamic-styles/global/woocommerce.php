<?php
if(!function_exists('is_woocommerce')) return false;

global $mk_options;

$skin_color_60 = mk_color($mk_options['skin_color'], 0.6);

Mk_Static_Files::addGlobalStyle("

.product-loading-icon
{
	background-color:{$skin_color_60};
}

.mk-woocommerce-carousel .the-title,
.mk-woocommerce-carousel .product-title {
	font-size: {$mk_options['p_size']}px !important;
	text-transform: initial;
}

.single-product .entry-summary .price .amount{
	color: {$mk_options['skin_color']};
}
.single-product .entry-summary .star-rating span:before {
	color: {$mk_options['h1_color']} !important;	
}

.wc-tabs li.active a {
	border-color: {$mk_options['skin_color']};
}

#review_form_wrapper .comment-reply-title
{
	font-size: {$mk_options['h2_size']}px;
	color: {$mk_options['h2_color']};
	font-weight: {$mk_options['h2_weight']};
	text-transform: {$mk_options['h2_transform']};
}
");

/*
 * Show responsive shopping cart based on Main Navigation Threshold Width option.
 */

$main_navigation_max_width = $mk_options['responsive_nav_width'] . 'px';

Mk_Static_Files::addGlobalStyle("
	@media handheld, only screen and (max-width:{$main_navigation_max_width}) {
		.add-cart-responsive-state {
			display: block;
		}
	}
");


/* Fix for woocommerce products search width
-------------------------------------------------------------- */

$main_content_responsive_state = $mk_options['content_responsive'] . 'px';

Mk_Static_Files::addGlobalStyle('
	#woocommerce-product-search-field {
		box-sizing: border-box;
		width: 65%;
	}
	.woocommerce-product-search input[type="submit"] {
		width: 33%;
		padding-left: 0 !important;
		padding-right: 0 !important;
		text-align: center;
	}
	@media handheld, only screen and (max-width: ' . $main_content_responsive_state . ') {
		#woocommerce-product-search-field {
			width: 74%;
		}
		.woocommerce-product-search input[type="submit"] {
			width: 24%;
		}
	}
');


if($mk_options['woocommerce_catalog'] == 'true') {
Mk_Static_Files::addGlobalStyle('
	.single-product .entry-summary .woocommerce-product-rating,
	.single-product .entry-summary .variations_form.cart,
	.single-product .entry-summary .price,
	.single-product .product .out-of-stock, 
	.single-product .product>.onsale {
		display:none !important;
	}
	');

}




