<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
		<?php $i = 1; ?>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<div class="accordion" id="accordion-<?php echo esc_attr( $key ); ?>">
				<h2 class="accordion__header">
					<button class="accordion__trigger" id="accordion-btn-<?php echo esc_attr( $key ); ?>" type="button"
							aria-expanded="false"
							aria-controls="accordion-panel-<?php echo esc_attr( $key ); ?>">
						<span class="accordion__title">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
						</span>
						<span class="accordion__counter">0<?php echo esc_html( $i ); ?></span>
					</button>
				</h2>
				<div class="accordion__panel" id="accordion-panel-<?php echo esc_attr( $key ); ?>" role="region"
					 aria-labelledby="accordion-btn-<?php echo esc_attr( $key ); ?>" hidden="">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
			</div>
			<?php ++$i; ?>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
