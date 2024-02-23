<header class="<?php echo esc_attr( dov_get_the_header_classes() ); ?>">
	<div class="page-header__wrapper">
		<?php get_template_part( 'template-parts/header-content' ); ?>
	</div>
	<a href="#mini-cart" data-popup-id="mini-cart" class="menu-header-second__link_mini_cart">
		<?php
		global $woocommerce;
		$cart_count = ( $woocommerce->cart->cart_contents_count ) ? $woocommerce->cart->cart_contents_count : '';
		?>
		<span class="menu-header-second__link_mini_cart_counter">
			<?php echo esc_html( $cart_count ); ?>
		</span>
	</a>
</header>
