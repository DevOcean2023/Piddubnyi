<?php


//remove woocommerce styles
use JetBrains\PhpStorm\NoReturn;

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

//woocommerce checkout fields
add_filter( 'woocommerce_checkout_fields', 'remove_checkout_fields' );
function remove_checkout_fields( $fields ) {

	unset( $fields['billing']['billing_company'] );
	unset( $fields['billing']['billing_address_1'] );
	unset( $fields['billing']['billing_address_2'] );
	unset( $fields['billing']['billing_city'] );
	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['billing_state'] );
	$fields['order']['order_comments']['required'] = false;

	$fields['billing']['billing_first_name']['placeholder'] = __( 'First name*', 'woocommerce' );
	$fields['billing']['billing_last_name']['placeholder']  = __( 'Last name*', 'woocommerce' );
	$fields['billing']['billing_phone']['placeholder']      = __( 'Phone*', 'woocommerce' );
	$fields['billing']['billing_email']['placeholder']      = __( 'E mail', 'woocommerce' );

	return $fields;
}

add_action( 'woocommerce_after_checkout_billing_form', 'checkout_shipping_change_position', 100 );
function checkout_shipping_change_position() {
	echo '<div class="shipping-wrapper">';

	if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :

		do_action( 'woocommerce_review_order_before_shipping' );

		wc_cart_totals_shipping_html();

		do_action( 'woocommerce_review_order_after_shipping' );

	endif;

	echo '</div>';
}

add_action( 'woocommerce_before_checkout_billing_form', 'billing_header_modify', 100 );
function billing_header_modify() {
	if ( is_user_logged_in() ):
		echo '<div class="edit-link-holder"><a href="/my-account/edit-account">' . wp_kses_post( 'Change info', 'woocommerce' ) . '</a></div>';
	endif;
}

//disable shipping in cart
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );
function disable_shipping_calc_on_cart( $show_shipping ) {
	if ( is_cart() ) {
		return false;
	}

	return $show_shipping;
}

// Update mini cart
function theme_update_mini_cart() {
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		wp_send_json_error( __( 'The session token has expired. Reload the page and try again.', 'woocommerce' ) );
	}

	global $woocommerce;

	$woocommerce->cart->set_quantity( $_POST['key'], floatval( $_POST['qty'] ) );

	$fragment = get_refreshed_fragments_in_variable();
	wp_send_json( $fragment );
	wp_die();

}

add_action( 'wp_ajax_nopriv_theme_update_mini_cart', 'theme_update_mini_cart' );
add_action( 'wp_ajax_theme_update_mini_cart', 'theme_update_mini_cart' );

// Get refresh fragment in variable
function get_refreshed_fragments_in_variable() {
	return apply_filters( 'woocommerce_add_to_cart_fragments', array() );
}

// WooCommerce, refresh fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {

	global $woocommerce;

	$cart_count = ( $woocommerce->cart->cart_contents_count ) ?: '';

	$fragments['.menu-header-second__link_mini_cart_counter'] = '<span class="menu-header-second__link_mini_cart_counter">' . esc_html( $cart_count ) . '</span>';

	ob_start();

	woocommerce_mini_cart();

	$mini_cart = ob_get_clean();

	$fragments['dialog#mini-cart .popup__content .mini-cart'] = $mini_cart;

	return $fragments;
}

// Remove shipping form fields
add_filter( 'woocommerce_default_address_fields', 'remove_shipping_form_fields' );

function remove_shipping_form_fields( $fields ) {
	unset( $fields['first_name'] );
	unset( $fields['last_name'] );
	unset( $fields['address_2'] );
	unset( $fields['company'] );

	return $fields;
}

// Customize shipping placeholders with priority
add_filter( 'woocommerce_shipping_fields', 'customize_shipping_placeholders', 10, 1 );

function customize_shipping_placeholders( $fields ) {
	// Set the order of the fields
	$fields_order = array(
		'shipping_country',
		'shipping_state',
		'shipping_city',
		'shipping_postcode',
		'shipping_address_1',
	);
	// Set the placeholders
	$fields['shipping_country']['placeholder']   = __( 'Країна*', 'woocommerce' );
	$fields['shipping_state']['placeholder']     = __( 'Введіть вашу область*', 'woocommerce' );
	$fields['shipping_city']['placeholder']      = __( 'Введіть ваше місто*', 'woocommerce' );
	$fields['shipping_postcode']['placeholder']  = __( 'Поштовий індекс*', 'woocommerce' );
	$fields['shipping_address_1']['placeholder'] = __( 'Адреса доставки*', 'woocommerce' );

	return $fields;
}

//////add account phone
function save_account_details_phone( $user_id ) {
	if ( isset( $_POST['account_phone'] ) ) {
		update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['account_phone'] ) );
	}
}

add_action( 'woocommerce_save_account_details', 'save_account_details_phone' );

/////updated orders
$updated_columns = array(
	'order-number'  => esc_html__( '№', 'woocommerce' ),
	'order-date'    => esc_html__( 'Дата', 'woocommerce' ),
	'order-status'  => esc_html__( 'Статус', 'woocommerce' ),
	'order-total'   => esc_html__( 'Сума', 'woocommerce' ),
	'order-actions' => '&nbsp;',
);
add_filter( 'woocommerce_account_orders_columns', function ( $columns ) use ( $updated_columns ) {
	return $updated_columns;
} );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Change a currency symbol
 */
add_filter( 'woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2 );

function change_existing_currency_symbol( $currency_symbol, $currency ) {
	switch ( $currency ) {
		case 'UAH':
			$currency_symbol = 'грн';
			break;
	}

	return $currency_symbol;
}

function get_image_alt( $id ) {
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

	return $alt;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

function add_product_info() {
	global $product;
	$sku    = $product->get_sku();
	$status = $product->get_stock_status();

	echo '<div class="product_sku">ID товару: ' . esc_html( $sku ) . '</div> <div class="product_stock-status">' . esc_html( $status ) . '</div>';
}

add_action( 'woocommerce_single_product_summary', 'add_product_info', 7 );
