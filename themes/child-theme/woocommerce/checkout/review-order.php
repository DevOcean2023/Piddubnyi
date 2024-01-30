<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
	<tr>
		<th><?php esc_html_e( 'Your order', 'woocommerce' ); ?></th>
		<td colspan="2" class="order-total-sum">
			<span class="sum"><?php wc_cart_totals_order_total_html(); ?></span>
		</td>
	</tr>
	</thead>
	<tbody>
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

				<td class="product-thumbnail">
					<?php
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_permalink = $_product->get_permalink();
					$product_name      = $_product->get_name();

					if ( ! $product_permalink ) {
						echo wp_kses_post( $thumbnail );
					} else {
						echo wp_kses_post( '<a href="' . $product_permalink . '">' . $thumbnail . '</a>' ); // PHPCS: XSS ok.
					}
					?>
				</td>

				<td class="product-name">
					<div class="product-name-wrap">
						<div class="product-name-value">
							<?php echo wp_kses_post( '<a href="' . $product_permalink . '">' . $product_name . '</a>' ) . '&nbsp;'; // PHPCS: XSS ok. ?>
						</div>
						<div class="product-quantity-wrap">
							<?php
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
									'product_name' => $_product->get_name(),
								),
								$_product,
								false
							);
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ) );
							?>
						</div>
					</div>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
				<td class="product-total">
					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php
		}
	}

	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
	</tbody>
	<tfoot>


	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
			<td colspan="2"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<tr class="fee">
			<th><?php echo esc_html( $fee->name ); ?></th>
			<td colspan="2"><?php wc_cart_totals_fee_html( $fee ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php echo esc_html( $tax->label ); ?></th>
					<td colspan="2"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr class="tax-total">
				<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
				<td colspan="2"><?php wc_cart_totals_taxes_total_html(); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<tr class="order-total">
		<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
		<td colspan="2" class="order-total-sum"><?php wc_cart_totals_order_total_html(); ?></td>
	</tr>
	<tr>
		<td colspan="3" class="order-comment">
			<div class="accordion">
				<div class="accordion__header">
					<button type="button" class="accordion__trigger" id="accordion__trigger-checkout"
							aria-expanded="false" aria-controls="accordion-panel-checkout">
						<span class="accordion__title">Додати коментар до замовлення</span>
					</button>
				</div>
				<div class="accordion__panel" id="accordion-panel-checkout" hidden="">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>
		</td>
	</tr>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
