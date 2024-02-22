<?php
require_once 'wc-functions.php';

add_theme_support( 'woocommerce' );

// Specify styles for .btn as in file styles.css
DOV_TinyMCE::add_editor_styles( '.btn', 'background-color:;color:#fff;' );

// Specify styles for login page
DOV_Login_Style::set( 'btn_bg', '' );
DOV_Login_Style::set( 'btn_color', '' );

// Security
DOV_Security_Headers::init();

// Add custom post types
DOV_CPT::add( 'testimonial' );

DOV_CPT::add(
	'faq',
	array(
		'menu_icon' => 'dashicons-megaphone',
		'supports'  => array( 'title', 'editor' ),
	)
);

// Add custom taxonomies
DOV_Tax::add(
	'faq_category',
	array(
		'object_type' => 'faq',
	)
);

// Add menus
DOV_Nav::add( 'Header Main' );
DOV_Nav::add( 'Header Second' );
DOV_Nav::add( 'Footer Main' );
DOV_Nav::add( 'Footer Links' );

// Add image sizes
// DOV_Images::add_size( '30x30' );
function my_account_menu_order() {
	$menuOrder = array(
		'edit-account'    => __( 'Контактні дані', 'woocommerce' ),
		'edit-address'    => __( 'Адреса доставки', 'woocommerce' ),
		'orders'          => __( 'Історія замовлень', 'woocommerce' ),
		'wishlist'        => __( 'Список бажань', 'woocommerce' ),
		'customer-logout' => __( 'Вихід', 'woocommerce' ),
	);

	return $menuOrder;
}

add_filter( 'woocommerce_account_menu_items', 'my_account_menu_order' );

// remove menu link
add_filter( 'woocommerce_account_menu_items', 'remove_my_account_dashboard' );
function remove_my_account_dashboard( $menu_links ) {

	unset( $menu_links['dashboard'] );

	return $menu_links;

}

///////////////////////
add_filter( 'body_class', function ( $classes ) {
	if ( is_page( 'wish-list' ) ) {
		$classes[] = 'wishlist-page';
	}
	if ( is_page( 'checkout' ) ) {
		$classes[] = 'checkout-section';
	}
	if ( is_page( 'cart' ) ) {
		$classes[] = 'cart';
	}
	if ( is_account_page() ) {
		if ( is_wc_endpoint_url( 'orders' ) ) {
			$classes[] = 'my-orders';
		} elseif ( is_wc_endpoint_url( 'view-order' ) ) {
			$classes[] = 'my-view-order';
		} elseif ( is_wc_endpoint_url( 'edit-address' ) ) {
			$classes[] = 'my-edit-address-class';
		} elseif ( is_wc_endpoint_url( 'wishlist' ) ) {
			$classes[] = 'my-account-wishlist';
		}
	}

	return $classes;
} );
