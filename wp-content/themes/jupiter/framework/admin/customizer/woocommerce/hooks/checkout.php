<?php
/**
 * WooCommerce hooks actions and filters.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

// Add 'Your order' to right column in checkout page.
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review' );
add_action( 'woocommerce_checkout_shipping', 'woocommerce_order_review', 10 );

// Show 'Ship to a different address?' under billing.
remove_action( 'woocommerce_checkout_shipping', array( WC_Checkout::instance(), 'checkout_form_shipping' ) );
add_action( 'woocommerce_checkout_billing', array( WC_Checkout::instance(), 'checkout_form_shipping' ) );

// Add aadditional fields after billing fields.
add_action(
	'woocommerce_after_checkout_billing_form', function( $checkout ) {
	?>
	<div class="woocommerce-additional-fields">
		<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

			<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

				<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

			<?php endif; ?>

			<div class="woocommerce-additional-fields__field-wrapper">
				<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
	</div>
	<?php
	}
);

// Add title and subtitle for billing form.
add_action(
	'woocommerce_check_cart_items', function() {

		if ( ! is_checkout() ) {
			return;
		}

		printf(
			'<h2 class="mk-wc-title">%s<small class="mk-wc-subtitle">%s</small></h2>',
			esc_html__( 'Delivery & Payment Details', 'mk_framework' ),
			esc_html__( 'Please enter your details.', 'mk_framework' )
		);

	}
);

// Add title and subtitle for payment form.
add_action(
	'woocommerce_review_order_before_payment', function() {

		printf(
			'<h2 class="mk-wc-title">%s<small class="mk-wc-subtitle">%s</small></h2>',
			esc_html__( 'Choose your payment method', 'mk_framework' ),
			esc_html__( 'Please enter your Payment Details.', 'mk_framework' )
		);

	}
);

// Add 'Continue shopping' before the 'place order' button.
add_action(
	'woocommerce_review_order_before_submit', function() {
	?>
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button mk-wc-backword">
		<?php esc_html_e( 'Continue Shopping', 'mk_framework' ); ?>
	</a>
	<?php
	}
);
