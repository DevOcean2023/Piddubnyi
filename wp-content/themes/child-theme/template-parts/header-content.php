<header class="page-header">
	<div class="page-header__wrapper">
		<div class="page-header__content">
			<div class="page-header__top">
				<div class="inner">
					<?php get_template_part( 'template-parts/social-links', null, array( 'class' => 'social-links_toggle' ) ); ?>
					<?php while ( dov_loop( 'dov_header_phone' ) ) : ?>
						<?php dov_the( 'link', 'page-header__top-phone' ); ?>
					<?php endwhile; ?>
					<?php while ( dov_loop( 'dov_header_time', '<div class="page-header__time">' ) ) : ?>
						<?php dov_the( 'time', '<p class="page-header__time-item">' ); ?>
					<?php endwhile; ?>
				</div>
			</div>
			<div class="page-header__middle">
				<div class="inner">
					<div class="wrap__search">
						<button class="button__search"></button>
						<div class="wrap-search">
							<form class="search-form__form" role="search" method="get" action="/">
								<label class="search-form__label">
									<span
											class="screen-reader-text"><?php esc_attr_e( 'Search for:', 'theme' ); ?></span>
									<input class="search-form__input" type="search" placeholder="Шукати" value=""
										   name="s">
								</label>
								<input class="search-form__submit" type="submit" value="Search">
							</form>
							<span class="close-button"></span>
						</div>
					</div>
					<?php dov_the_logo(); ?>
					<nav class="menu-header-second" aria-label="Header Second">
						<ul class="menu-header-second__items" data-role="menubar" data-accessibility-menu>
							<li class="menu-header-second__item account">
								<a class="menu-header-second__link"
								   href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>"
								   data-role="menuitem"></a></li>
							<li class="menu-header-second__item cart menu-cart-link">
								<?php
								global $woocommerce;
								$cls_mini_cart = ( WC()->cart->get_cart_contents_count() ) ? ' products-in-cart' : '';
								?>
								<a href="#popup-mini-cart"
								   class="menu-header-second__link_mini_cart <?php echo esc_html( $cls_mini_cart ); ?>">
									<span class="menu-header-second__link_mini_cart_counter">
										<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
									</span>
								</a>
							</li>
							<li class="menu-header-second__item wish-list">
								<a href="/wish-list"></a>
							</li>
							<li class="menu-header-second__item delivery">
								<a class="menu-header-second__link" href="#"
								   data-role="menuitem">Доставка та оплата</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page-header__bottom">
				<div class="inner">
					<div class="nav-menu">
						<?php get_template_part( 'template-parts/desktop-main-menu', wp_is_mobile(), 'menu-header-main' ); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="button-mobile-menu">
			<button class="open-mobile-menu-button" aria-controls="mobile-menu" aria-expanded="false">
				<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-1'></span>
				<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-2'></span>
				<span class='open-mobile-menu-button__item open-mobile-menu-button__item_style-3'></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'theme' ); ?></span>
			</button>
		</div>
	</div>
</header>
<dialog id="popup-menu">
	<div class="popup">
		<div class="popup__content">
			<div class="popup__item">
				<?php dov_the( 'dov_title_form', 'popup__item_title-popup' ); ?>

				<!-- assets/css/gravity-forms.css -->
				<?php dov_the( 'dov_forms_popup' ); ?>
			</div>
		</div>
		<button class="popup__close-btn" type="button" autofocus>
			<span class="popup__close-text"><?php esc_attr_e( 'Close', 'theme' ); ?></span>
		</button>
	</div>
</dialog>
