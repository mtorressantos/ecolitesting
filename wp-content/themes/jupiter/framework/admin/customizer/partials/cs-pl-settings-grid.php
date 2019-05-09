<?php
/**
 * Views file for selective_refresh for settings: sh_pl_set_columns
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Actions woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );
?>
<header class="woocommerce-products-header">
<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
<?php endif; ?>
<?php
/**
 * Actions woocommerce_archive_description hook.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
do_action( 'woocommerce_archive_description' );
?>
</header>
<?php
if ( have_posts() ) :

	/**
	* Actionswoocommerce_before_shop_loop hook.
	*
	* @hooked wc_print_notices - 10
	* @hooked woocommerce_result_count - 20
	* @hooked woocommerce_catalog_ordering - 30
	*/
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	woocommerce_product_subcategories();

	while ( have_posts() ) :
		the_post();

		/**
		* Actions woocommerce_shop_loop hook.
		*
		* @hooked WC_Structured_Data::generate_product_data() - 10
		*/
		do_action( 'woocommerce_shop_loop' );

		wc_get_template_part( 'content', 'product' );

	endwhile; // end of the loop.

	woocommerce_product_loop_end();

	/**
	* Actions woocommerce_after_shop_loop hook.
	*
	* @hooked woocommerce_pagination - 10
	*/
	do_action( 'woocommerce_after_shop_loop' );

elseif ( ! woocommerce_product_subcategories(
	array(
		'before' => woocommerce_product_loop_start( false ),
		'after' => woocommerce_product_loop_end( false ),
	)
) ) :

	/**
	* Actions woocommerce_no_products_found hook.
	*
	* @hooked wc_no_products_found - 10
	*/
	do_action( 'woocommerce_no_products_found' );

endif;

/**
* Actions woocommerce_after_main_content hook.
*
* @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
*/
do_action( 'woocommerce_after_main_content' );

/**
* Actions woocommerce_sidebar hook.
*
* @hooked woocommerce_get_sidebar - 10
*/
do_action( 'woocommerce_sidebar' );
