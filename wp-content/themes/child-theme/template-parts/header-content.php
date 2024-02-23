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
									<span class="screen-reader-text"><?php esc_attr_e( 'Search for:', 'theme' ); ?></span>
									<input class="search-form__input" type="search" placeholder="Шукати" value=""
										   name="s">
								</label>
								<input class="search-form__submit" type="submit" value="Search">
							</form>
							<span class="close-button"></span>
						</div>
					</div>
					<?php dov_the_logo(); ?>
					<?php dov_the_nav( 'Header Second' ); ?>
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
					<img src="assets/images/contact-bg.png" alt="" class="object-fit-cover object-fit">
					<div class="popup__item">
						<h2 class="popup__item_title-popup">Вкажіть ваші дані та ми обов’язково з вами зв’яжемось
						</h2>

						<!-- assets/css/gravity-forms.css -->

						<div class="gf_browser_chrome gform_wrapper " id="gform_wrapper_17">
							<form method="post" enctype="multipart/form-data" id="gform_17" class="" action="/">

								<div class="gform_body">
									<div class="gform_fields top_label form_sublabel_below description_below">

										<div id="field_17_98"
											 class="gfield gfield_single_line_text     field_sublabel_below field_description_below  gfield_visibility_visible">
											<label class="gfield_label" for="input_17_98">
												Ваше Ім’я*
											</label>
											<div class="ginput_container ginput_container_text">
												<input name="input_98" id="input_17_98" type="text" value=""
													   class="medium" placeholder="" aria-required="false"
													   aria-invalid="false">
											</div>
										</div>

										<div
												class="gfield gfield_phone     field_sublabel_below field_description_below  gfield_visibility_visible">
											<label class="gfield_label" for="input_17_99">
												Ваш номер телефону*
											</label>
											<div class="ginput_container ginput_container_phone">
												<input name="input_99" id="input_17_99" type="text" value=""
													   class="medium" placeholder="" aria-required="false"
													   aria-invalid="false">
											</div>
										</div>
										<fieldset id="field_17_100"
												  class="gfield  gfield_consent   field_sublabel_below field_description_below  gfield_visibility_visible">
											<legend class='gfield_label gfield_label_before_complex'>
											</legend>
											<div class='ginput_container ginput_container_consent'>
												<input name='input_17_100' id='input_17_100' type='checkbox'
													   value='1' aria-required="true" aria-invalid="false" /> <label
														class="gfield_consent_label" for='input_17_100'>Не телефонуйте
													мені, а краще напишіть у месенджер</label>
											</div>
										</fieldset>

										<div id="field_17_101"
											 class="gfield gfield_single_line_text   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
											<label class="gfield_label" for="input_17_101">
												Нік у соцмережах (Instagram, Facebook, Telegram)*<span
														class="gfield_required">*</span>
											</label>
											<div class="ginput_container ginput_container_text">
												<input name="input_101" id="input_17_101" type="text" value=""
													   class="medium" placeholder="" aria-required="true"
													   aria-invalid="false">
											</div>
										</div>

										<div id="field_17_102"
											 class="gfield gfield_single_line_text   gfield_contains_required  field_sublabel_below field_description_below  gfield_visibility_visible">
											<label class="gfield_label" for="input_17_102">
												Назва процедури, на яку бажаєте записатись<span
														class="gfield_required">*</span>
											</label>
											<div class="ginput_container ginput_container_text">
												<input name="input_102" id="input_17_102" type="text" value=""
													   class="medium" placeholder="" aria-required="true"
													   aria-invalid="false">
											</div>
										</div>

										<div class="gform_footer top_label">

											<input type='submit' class='gform_button button'
												   value='підтвердити запис'
												   onclick='if(window["gf_submitting_17"]){return false;}  window["gf_submitting_17"]=true;  '
												   onkeypress='if( event.keyCode === 13 ){ if(window["gf_submitting_17"]){return false;} window["gf_submitting_17"]=true;  jQuery("#gform_17").trigger("submit",[true]); }'>

											<input type='hidden' class='gform_hidden' name='is_submit_17' value='1'>
											<input type='hidden' class='gform_hidden' name='gform_submit'
												   value='17'>
											<input type='hidden' class='gform_hidden' name='gform_unique_id'
												   value=''>
											<input type='hidden' class='gform_hidden' name='state_17'
												   value='WyJbXSIsIjI4ZTYyMGI3NzdjMzJjM2JkN2YyNzZkNzZlMWNhNWIzIl0='>
											<input type='hidden' class='gform_hidden'
												   name='gform_target_page_number_17' id='gform_target_page_number_17'
												   value='0'>
											<input type='hidden' class='gform_hidden'
												   name='gform_source_page_number_17' id='gform_source_page_number_17'
												   value='1'>
											<input type='hidden' name='gform_field_values' value=''>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<button class="popup__close-btn" type="button">
					<svg class="popup__close-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
						 fill="currentColor" viewBox="0 0 18 18">
						<path
								d="M.93 18a.926.926 0 0 1-.654-1.581L16.422.27a.926.926 0 1 1 1.31 1.31L1.584 17.728A.929.929 0 0 1 .929 18Z" />
						<path
								d="M17.078 18a.919.919 0 0 1-.654-.272L.275 1.581a.926.926 0 0 1 1.31-1.31L17.732 16.42A.926.926 0 0 1 17.078 18Z" />
					</svg>
					<span class="popup__close-text">Close</span>
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
