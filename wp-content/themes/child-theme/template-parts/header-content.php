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
								<a href="#mini-cart" data-popup-id="mini-cart"
								   class="menu-header-second__link_mini_cart <?php echo esc_html( $cls_mini_cart ); ?>">
									<span class="menu-header-second__link_mini_cart_counter">
										<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
									</span>
								</a>
							</li>
							<li class="menu-header-second__item wish-list">
								<?php echo do_shortcode( '[ti_wishlist_products_counter]' ); ?>
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
						<?php dov_the_nav( 'Header Main' ); ?>
					</div>

					<?php
					$parent_category_slug = 'face-category';
					$parent_category      = get_term_by( 'slug', $parent_category_slug, 'product_cat' );

					if ( $parent_category ) {
						$parent_category_id = $parent_category->term_id;

						$subcategories = get_categories(
							array(
								'parent'     => $parent_category_id,
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
							)
						);

						if ( $subcategories ) {
							echo "<div class='face-category-menu-wrapper'>";
							echo "<div class='inner-category'>";

							foreach ( $subcategories as $subcategory ) {
								$thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );

								echo "
                <div class='item'>
                    <div class='img'>";

								if ( $thumbnail_id ) {
									echo wp_get_attachment_image( $thumbnail_id, 'thumbnail' );
								}

								echo "</div><a href='" . get_term_link( $subcategory ) . "'>" . $subcategory->name . '</a></div>';
							}

							echo '</div></div>';
						} else {
							echo "Немає доступних підкатегорій для категорії зі slug '" . $parent_category_slug . "'";
						}
					} else {
						echo "Категорія зі slug '" . $parent_category_slug . "' не знайдена";
					}
					?>

					<?php
					$parent_category_slug = 'hair-category';
					$parent_category      = get_term_by( 'slug', $parent_category_slug, 'product_cat' );

					if ( $parent_category ) {
						$parent_category_id = $parent_category->term_id;

						$subcategories = get_categories(
							array(
								'parent'     => $parent_category_id,
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
							)
						);

						if ( $subcategories ) {
							echo "<div class='hair-category-menu-wrapper'>";
							echo "<div class='inner-category'>";

							foreach ( $subcategories as $subcategory ) {
								$thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );

								echo "
                <div class='item'>
                    <div class='img'>";

								if ( $thumbnail_id ) {
									echo wp_get_attachment_image( $thumbnail_id, 'thumbnail' );
								}

								echo "</div><a href='" . get_term_link( $subcategory ) . "'>" . $subcategory->name . '</a></div>';
							}

							echo '</div></div>';
						} else {
							echo "Немає доступних підкатегорій для категорії зі slug '" . $parent_category_slug . "'";
						}
					} else {
						echo "Категорія зі slug '" . $parent_category_slug . "' не знайдена";
					}
					?>

					<?php
					$parent_category_slug = 'body-category';
					$parent_category      = get_term_by( 'slug', $parent_category_slug, 'product_cat' );

					if ( $parent_category ) {
						$parent_category_id = $parent_category->term_id;

						$subcategories = get_categories(
							array(
								'parent'     => $parent_category_id,
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
							)
						);

						if ( $subcategories ) {
							echo "<div class='body-category-menu-wrapper'>";
							echo "<div class='inner-category'>";

							foreach ( $subcategories as $subcategory ) {
								$thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );

								echo "
                <div class='item'>
                    <div class='img'>";

								if ( $thumbnail_id ) {
									echo wp_get_attachment_image( $thumbnail_id, 'thumbnail' );
								}

								echo "</div><a href='" . get_term_link( $subcategory ) . "'>" . $subcategory->name . '</a></div>';
							}

							echo '</div></div>';
						} else {
							echo "Немає доступних підкатегорій для категорії зі slug '" . $parent_category_slug . "'";
						}
					} else {
						echo "Категорія зі slug '" . $parent_category_slug . "' не знайдена";
					}
					?>

					<div class="about-company-menu-wrapper">
						<?php while ( dov_loop( 'dov_header_about', '<div class="inner-category">' ) ) : ?>
							<div class="item">
								<div class="img">
									<?php dov_the( 'header_image_menu', '32x0' ); ?>
								</div>
								<?php dov_the( 'header_link_menu' ); ?>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		</div>

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
