<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

$get_addresses = apply_filters(
	'woocommerce_my_account_get_addresses',
	array(
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	),
	$customer_id
);
?>
<h2 class="title-my-account"><?php esc_html_e( 'Адреса доставки', 'woocommerce' ); ?></h2>

<div class="u-columns woocommerce-Addresses col2-set addresses">
	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php
		$address = wc_get_account_formatted_address( $name );
		?>

		<div class="u-column1 col-1 shipping-Address">
			<address>
				<?php
				$address           = apply_filters(
					'woocommerce_my_account_my_address_formatted_address',
					array(
						'country'   => get_user_meta( $customer_id, $name . '_country', true ),
						'state'     => get_user_meta( $customer_id, $name . '_state', true ),
						'city'      => get_user_meta( $customer_id, $name . '_city', true ),
						'postcode'  => get_user_meta( $customer_id, $name . '_postcode', true ),
						'address_1' => get_user_meta( $customer_id, $name . '_address_1', true ),
					),
					$customer_id,
					$name
				);
				$formatted_address = WC()->countries->get_formatted_address( $address );

				// Check if the key 'state' exists before using it
				$state_code = $address['state'] ?? '';
				$state_name = ! empty( $state_code ) ? WC()->countries->get_states( 'UA' )[ $state_code ] : '';

				$args = array(
					'customer_id' => $customer_id,
					'limit'       => - 1, // to retrieve _all_ orders by this user
				);
				?>
				<div class="container-address">
					<?php if ( empty( $address['country'] ) && empty( $address['state'] ) && empty( $address['city'] ) && empty( $address['postcode'] ) && empty( $address['address_1'] ) ) : ?>
						<p><?php esc_html_e( 'У вас ще немає збережених адрес.', 'woocommerce' ); ?></p>
					<?php else : ?>
						<div class="item">
							<p class="title"><?php echo esc_html( 'Місто' ); ?></p>
							<p class="text"><?php echo esc_html( $address['city'] ); ?></p>
						</div>
						<div class="item">
							<p class="title"><?php echo esc_html( 'Iндекс' ); ?></p>
							<p class="text"><?php echo esc_html( $address['postcode'] ); ?></p>
						</div>
						<div class="item">
							<p class="title"><?php echo esc_html( 'Область' ); ?></p>
							<p class="text"><?php echo esc_html( $state_name ); ?></p>
						</div>
						<div class="item">
							<p class="title"><?php echo esc_html( 'Адреса' ); ?></p>
							<p class="text"><?php echo esc_html( $address['address_1'] ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</address>
			<header class="woocommerce-Address-title title">
				<h3><?php echo esc_html( $address_title ); ?></h3>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>"
				   class="edit"><?php echo $address ? esc_html__( 'Додати нову адресу', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ); ?></a>
			</header>
		</div>
	<?php endforeach; ?>
</div>
