<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="mini-cart">
	<h4><?php echo wp_kses_post( 'Кошик', 'woocommerce' ); ?></h4>
	<?php if ( ! WC()->cart->is_empty() ) : ?>

		<ul class="mini-cart__list">
			<?php
			do_action( 'woocommerce_before_mini_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<li class="mini-cart__item">
						<div class="mini-cart__item-image-holder">
							<?php
							$post_thumbnail_id = ( $_product->get_image_id() ) ? $_product->get_image_id() : get_option( 'woocommerce_placeholder_image', 0 );
							$src               = wp_get_attachment_image_src( $post_thumbnail_id, 'medium_large' );
							?>
							<a href="<?php echo ( empty( $product_permalink ) ) ? esc_html( '#no-follow' ) : esc_html( $product_permalink ); ?>">
								<figure class="mini-cart__item-image-inner">
									<img class="mini-cart__item-image" src="<?php echo esc_html( $src[0] ); ?>"
										 alt="<?php echo esc_html( $_product->get_name() ); ?>">
								</figure>
							</a>
						</div>
						<!-- .img-holder -->
						<div class="mini-cart__item-text">
							<div class="mini-cart__item-info">
								<div class="mini-cart__item-info-inner">
									<h4>
										<a href="<?php echo ( empty( $product_permalink ) ) ? esc_html( '#no-follow' ) : esc_html( $product_permalink ); ?>"><?php echo esc_html( $_product->get_name() ); ?></a>
									</h4>
									<?php $subtitle = get_field( 'subtitle', $product_id ); ?>
									<?php if ( $subtitle ) : ?>
										<p><?php echo esc_html( $subtitle ); ?></p>
									<?php endif ?>
									<?php echo wp_kses_post( $product_price ); ?>
								</div>
								<a href="#" class="mini-cart__item-remove"
								   data-cartkey="<?php echo wp_kses_post( $cart_item_key ); ?>"
								></a>
							</div>
							<div class="mini-cart__item-footer">
								<?php
								if ( ! $_product->is_sold_individually() ) {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'cart_item_key' => $cart_item_key,
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ) );
								?>
							</div>
							<!-- .product-info -->
						</div>
						<!-- .text-holder -->
					</li>

					<?php
				}
			}

			do_action( 'woocommerce_mini_cart_contents' );
			?>
		</ul>


		<div class="mini-cart__footer">
			<header>
				<h4><?php echo wp_kses_post( 'Підсумки кошика', 'woocommerce' ); ?></h4>
				<span class="total"><?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?></span>
			</header>
			<a href="<?php echo wp_kses_post( wc_get_checkout_url() ); ?>"
			   class="btn"><?php echo wp_kses_post( 'Оформити замовлення', 'woocommerce' ); ?></a>
		</div>


		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>


		<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

	<?php else : ?>

		<div class="empty-cart-text">
			<p><?php echo wp_kses_post( 'Кошик пустий', 'woocommerce' ); ?></p>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>
<!-- .mini-cart -->
