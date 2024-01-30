<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
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
if (!defined('ABSPATH')) {
	exit;
}

if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
	return;
}
?>
<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order)); ?>">
	<td class="product-thumbnail">
		<?php
		$product_id = $item->get_product_id();

		$product = wc_get_product($product_id);

		if ($product && $product->get_image_id()) {
			$image_url = wp_get_attachment_image_url($product->get_image_id(), 'thumbnail');

			echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($item->get_name()) . '" />';
		}
		?>
	</td>

	<td class="woocommerce-table__product-name product-name">
		<?php
		$is_visible = $product && $product->is_visible();
		$product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);

		echo wp_kses_post(apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a href="%s">%s</a>', $product_permalink, $item->get_name()) : $item->get_name(), $item, $is_visible));

		$qty = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item($item_id);

		if ($refunded_qty) {
			$qty_display = '<del>' . esc_html($qty) . '</del> <ins>' . esc_html($qty - ($refunded_qty * -1)) . '</ins>';
		} else {
			$qty_display = esc_html($qty);
		}

		do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

		wc_display_item_meta($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
		?>
	</td>

	<td class="product-quantity">
		<?php
		echo 'x' . esc_html($qty_display);
		?>
	</td>

	<td class="product-price">
		<?php
		$item_price = wc_get_price_to_display($product);

		echo wc_price($item_price);
		?>
	</td>

	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</td>
</tr>

<?php if ($show_purchase_note && $purchase_note) : ?>

	<tr class="woocommerce-table__product-purchase-note product-purchase-note">

		<td colspan="5"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>

	</tr>

<?php endif; ?>
