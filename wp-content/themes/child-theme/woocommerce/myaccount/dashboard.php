<?php
/**
 * My Account Dashboard
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Redirect to the Orders page.
wp_safe_redirect( wc_get_account_endpoint_url( 'orders' ) );
exit;
