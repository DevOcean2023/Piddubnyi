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

	$fields['billing']['billing_first_name']['placeholder'] = __( 'Ім\'я*', 'woocommerce' );
	$fields['billing']['billing_last_name']['placeholder']  = __( 'Прізвище*', 'woocommerce' );
	$fields['billing']['billing_phone']['placeholder']      = __( 'Телефон*', 'woocommerce' );
	$fields['billing']['billing_email']['placeholder']      = __( 'E-mail', 'woocommerce' );

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
	if ( is_user_logged_in() ) :
		echo '<div class="edit-link-holder"><a href="/my-account/edit-account">' . wp_kses_post( 'Змінити', 'woocommerce' ) . '</a></div>';
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

// WooCommerce, refresh fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {

	global $woocommerce;

	$cls_mini_cart = ( WC()->cart->get_cart_contents_count() ) ? ' products-in-cart' : '';

	$fragments['.page-header .menu-header-second__link_mini_cart'] = '<a href="#mini-cart" data-popup-id="mini-cart"
								   class="menu-header-second__link_mini_cart ' . esc_html( $cls_mini_cart ) . '">
									<span class="menu-header-second__link_mini_cart_counter">
										' . WC()->cart->get_cart_contents_count() . '
									</span>
								</a>';

	ob_start();

	woocommerce_mini_cart();

	$mini_cart = ob_get_clean();

	$fragments['dialog#mini-cart .popup__content .mini-cart'] = $mini_cart;

	return $fragments;

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
	$fields['shipping_postcode']['maxlength']    = 5;
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
add_filter( 'woocommerce_account_orders_columns', function () use ( $updated_columns ) {
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
	if ( 'instock' === $status ) {
		$product_status = 'В наявності';
	}
	if ( 'outofstock' === $status ) {
		$product_status = 'Немає в наявності';
	}

	echo '<div class="product_sku">ID товару: ' . esc_html( $sku ) . '</div>
	<div class="product_stock-status">' . esc_html( $product_status ) . '</div>';
}

add_action( 'woocommerce_single_product_summary', 'add_product_info', 7 );

////////////////////////////////////////////////////////////////////
/// Add the "Composition" tab on the product edit page in the admin
function custom_product_data_tab_composition( $tabs ) {
	$tabs['composition'] = array(
		'label'  => __( 'Склад', 'woocommerce' ),
		'target' => 'custom_composition',
	);

	return $tabs;
}

add_filter( 'woocommerce_product_data_tabs', 'custom_product_data_tab_composition' );

// The contents of the "Application Features" tab on the product editing page in the admin
function custom_product_data_content_composition() {
	global $post;

	$custom_field = get_post_meta( $post->ID, '_custom_composition', true );
	$editor_id    = '_custom_composition';
	$settings     = array( 'name' => '_custom_composition' );

	echo '<div id="custom_composition" class="panel woocommerce_options_panel">';
	echo '<div class="options_group">';
	echo '<h2>Склад</h2>';
	wp_editor( $custom_field, $editor_id, $settings );
	echo '</div>';
	echo '</div>';
}

add_action( 'woocommerce_product_data_panels', 'custom_product_data_content_composition' );

// Saving the value of the field when saving the product
function save_custom_product_data_field_composition( $post_id ): void {
	$custom_application_features = isset( $_POST['_custom_composition'] ) ? wp_kses_post( $_POST['_custom_composition'] ) : '';
	update_post_meta( $post_id, '_custom_composition', $custom_application_features );
}

add_action( 'woocommerce_process_product_meta', 'save_custom_product_data_field_composition' );

// Adding a new "Usage Features" tab on the product page
function custom_product_tabs( $tabs ) {
	global $product;
	unset( $tabs['reviews'] );
	unset( $tabs['additional_information'] );

	$composition_content = get_post_meta( $product->get_id(), '_custom_composition', true );
	$application_content = get_post_meta( $product->get_id(), '_custom_application', true );

	if ( ! empty( $composition_content ) ) :
		$tabs['composition'] = array(
			'title'    => __( 'Склад', 'woocommerce' ),
			'priority' => 50,
			'callback' => 'custom_product_tabs_content_composition',
		);
	endif;

	if ( ! empty( $application_content ) ) :
		$tabs['application'] = array(
			'title'    => __( 'Спосіб застосування', 'woocommerce' ),
			'priority' => 60,
			'callback' => 'custom_product_tabs_content_application',
		);
	endif;

	return $tabs;
}

function custom_product_tabs_content_composition() {
	global $product;
	$custom = get_post_meta( $product->get_id(), '_custom_composition', true );

	echo '<div id="composition_features_content">';
	echo wp_kses_post( $custom );
	echo '</div>';
}

add_filter( 'woocommerce_product_tabs', 'custom_product_tabs' );
/////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////
/// Add the "Application method" tab on the product edit page in the admin
function custom_product_data_tab_application( $tabs ) {
	$tabs['application'] = array(
		'label'  => __( 'Спосіб застосування', 'woocommerce' ),
		'target' => 'custom_application',
	);

	return $tabs;
}

add_filter( 'woocommerce_product_data_tabs', 'custom_product_data_tab_application' );

// The contents of the "Application Features" tab on the product editing page in the admin
function custom_product_data_content_application() {
	global $post;

	$custom_field = get_post_meta( $post->ID, '_custom_application', true );
	$editor_id    = '_custom_application';
	$settings     = array( 'name' => '_custom_application' );

	echo '<div id="custom_application" class="panel woocommerce_options_panel">';
	echo '<div class="options_group">';
	echo '<h2>Спосіб застосування</h2>';
	wp_editor( $custom_field, $editor_id, $settings );
	echo '</div>';
	echo '</div>';
}

add_action( 'woocommerce_product_data_panels', 'custom_product_data_content_application' );

// Saving the value of the field when saving the product
function save_custom_product_data_field_application( $post_id ): void {
	$custom_application_features = isset( $_POST['_custom_application'] ) ? wp_kses_post( $_POST['_custom_application'] ) : '';
	update_post_meta( $post_id, '_custom_application', $custom_application_features );
}

add_action( 'woocommerce_process_product_meta', 'save_custom_product_data_field_application' );

// Adding a new "Usage Features" tab on the product page

function custom_product_tabs_content_application() {
	global $product;
	$custom = get_post_meta( $product->get_id(), '_custom_application', true );

	echo '<div id="application_features_content">';
	echo wp_kses_post( $custom );
	echo '</div>';
}

/////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////
/// Add the "Composition" tab on the product edit page in the admin
function custom_product_data_tab_set( $tabs ) {
	$tabs['set'] = array(
		'label'  => __( 'Набір в якому є товар', 'woocommerce' ),
		'target' => 'custom_set',
	);

	return $tabs;
}

add_filter( 'woocommerce_product_data_tabs', 'custom_product_data_tab_set' );

// The contents of the "Application Features" tab on the product editing page in the admin
function custom_product_data_content_set() {
	global $post;

	$custom_field = get_post_meta( $post->ID, '_custom_set', true );
	$editor_id    = '_custom_set';
	$settings     = array( 'name' => '_custom_set' );

	echo '<div id="custom_set" class="panel woocommerce_options_panel">';
	echo '<div class="options_group" style="padding:15px;">';
	echo '<input type="text" id="_custom_set" name="_custom_set" value="' . esc_attr( $custom_field ) . '"></input>';
	echo '</div>';
	echo '</div>';
}

add_action( 'woocommerce_product_data_panels', 'custom_product_data_content_set' );

// Saving the value of the field when saving the product
function save_custom_product_data_field_set( $post_id ): void {
	$custom_application_features = isset( $_POST['_custom_set'] ) ? wp_kses_post( $_POST['_custom_set'] ) : '';
	update_post_meta( $post_id, '_custom_set', $custom_application_features );
}

add_action( 'woocommerce_process_product_meta', 'save_custom_product_data_field_set' );

function add_content_after_addtocart_button_func() {
	global $post;

	$custom_field = get_post_meta( $post->ID, '_custom_set', true );

	if ( $custom_field ) :
		echo '<a href="' . esc_url( $custom_field ) . '" class="btn btn-set" style="margin: 0 0 20px;">' . esc_html__( 'Оглянути набір', 'theme' ) . '</a>';
	endif;
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );

////////////////////////////////////////////////////////////////////

add_action( 'woocommerce_process_product_meta', 'save_custom_product_data_field_composition' );

add_filter( 'woocommerce_output_related_products_args', 'woo_related_products_args', 20 );
function woo_related_products_args( $args ) {
	$args['posts_per_page'] = 20;

	return $args;
}

add_action( 'wp', 'remove_sidebar_product_pages' );

function remove_sidebar_product_pages() {
	if ( is_product() ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

add_filter( 'woocommerce_pagination_args', 'rocket_woo_pagination' );
function rocket_woo_pagination( $args ) {

	$args['prev_text'] = '<span class="arrow-prev"></span>';
	$args['next_text'] = '<span class="arrow-next"></span>';

	return $args;
}

add_action( 'template_redirect', 'track_product_view', 9999 );

function track_product_view() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}
	global $post;
	if ( empty( $_COOKIE['recently_viewed'] ) ) {
		$viewed_products = array();
	} else {
		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['recently_viewed'] ) ) );
	}
	$keys = array_flip( $viewed_products );
	if ( isset( $keys[ $post->ID ] ) ) {
		unset( $viewed_products[ $keys[ $post->ID ] ] );
	}
	$viewed_products[] = $post->ID;
	if ( count( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}
	wc_setcookie( 'recently_viewed', implode( '|', $viewed_products ) );
}

add_filter( 'woocommerce_product_description_heading', '__return_null' );

/**
 * WooCommerce, content-product.php template
 *
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_action( 'woocommerce_before_shop_loop_item_title', 'theme_render_product_item', 10 );
function theme_render_product_item() {
	global $product;
	echo '<a href="' . get_the_permalink( $product->get_id() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	echo '<div class="product-card-inner">';
	$post_thumbnail_id = ( $product->get_image_id() ) ? $product->get_image_id() : get_option( 'woocommerce_placeholder_image', 0 );
	$src               = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
	echo '<img src="' . $src[0] . '" alt="' . $product->get_name() . '">';
	$cls_add_to_cart = array_filter(
		array(
			'button',
			'product_type_' . $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
		)
	);
	woocommerce_template_loop_add_to_cart();
	echo '</div>';
	echo '<h2 class="woocommerce-loop-product__title">' . $product->get_name() . '</h2>';
	echo '<span class="price">' . $product->get_price_html() . '</span>
    </a>';
}


