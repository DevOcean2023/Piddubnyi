<section class="services">
	<div class="inner">
		<div class="services__types">
			<theme-tabs>
				<?php
				$services_query = new WP_Query(
					array(
						'post_type'      => 'services',
						'posts_per_page' => -1,
					)
				);
				if ( $services_query->have_posts() ) {
					$tab_index = 1;
					foreach ( $services_query->posts as $post ) {
						setup_postdata( $post );
						$post_title     = get_the_title();
						$post_content   = get_the_content();
						$post_image_url = get_the_post_thumbnail_url();
						?>
						<theme-tabs-button data-service-id="<?php echo esc_attr( get_the_ID() ); ?>">
							<div class="services__icon-image">
								<?php dov_the( 'image_black', 'black', '24x0' ); ?>
								<?php dov_the( 'image_white_services', 'white', '24x0' ); ?>
							</div>
							<p class="services__text"><?php echo esc_html( $post_title ); ?></p>
						</theme-tabs-button>

						<theme-tabs-content data-service-id="<?php echo esc_attr( get_the_ID() ); ?>">
							<div class="tabs__tabs-content_items">
								<div class="tabs__tabs-content_items_item">
									<div class="tabs__tabs-content_items_item_text">
										<div class="tabs__tabs-content_items_item_text_editor">
											<?php echo wpautop( wp_kses_post( $post_content ) ); ?>
										</div>
										<?php dov_the( 'button', 'btn' ); ?>
									</div>
									<div class="tabs__tabs-content_items_item_image">
										<?php if ( has_post_thumbnail() ) : ?>
											<img src="<?php echo esc_url( $post_image_url ); ?>" alt="">
										<?php endif; ?>
									</div>
								</div>
							</div>
						</theme-tabs-content>
						<?php
						++$tab_index;
					}
					wp_reset_postdata();
				} else {
					echo 'Немає доступних послуг.';
				}
				?>
			</theme-tabs>
		</div>
		<div class="services__accordion">
			<?php
			$services_query = new WP_Query(
				array(
					'post_type'      => 'services',
					'posts_per_page' => -1,
				)
			);
			if ( $services_query->have_posts() ) {
				$accordion_index = 1;
				foreach ( $services_query->posts as $post ) {
					setup_postdata( $post );
					$post_title     = get_the_title();
					$post_content   = get_the_content();
					$post_image_url = get_the_post_thumbnail_url();
					$service_id     = get_the_ID();
					?>
					<div class="accordion" id='accordion-<?php echo esc_attr( $service_id ); ?>'>
						<h2 class="accordion__header" data-service-id="<?php echo esc_attr( $service_id ); ?>">
							<button class="accordion__trigger"
									id="accordion-btn-<?php echo esc_attr( $service_id ); ?>"
									type="button"
									aria-expanded="false"
									aria-controls="accordion-panel-<?php echo esc_attr( $service_id ); ?>">
					<span class="accordion__title">
						<div class="services__icon-image">
							<?php dov_the( 'image_black', 'black', '24x0' ); ?>
							<?php dov_the( 'image_white_services', 'white', '24x0' ); ?>
						</div>
						<p class="services__text"><?php echo esc_html( $post_title ); ?></p>
					</span>
							</button>
						</h2>
						<div class="accordion__panel"
							 id="accordion-panel-<?php echo esc_attr( $service_id ); ?>"
							 role="region"
							 aria-labelledby="accordion-btn-<?php echo esc_attr( $service_id ); ?>" hidden>
							<div class="tabs__tabs-content_items">
								<div class="tabs__tabs-content_items_item">
									<div class="tabs__tabs-content_items_item_text">
										<div class="tabs__tabs-content_items_item_text_editor">
											<?php echo wpautop( wp_kses_post( $post_content ) ); ?>
										</div>
										<?php dov_the( 'button', 'btn' ); ?>
									</div>
									<div class="tabs__tabs-content_items_item_image">
										<?php if ( has_post_thumbnail() ) : ?>
											<img src="<?php echo esc_url( $post_image_url ); ?>" alt="">
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					++$accordion_index;
				}
				wp_reset_postdata();
			} else {
				echo 'Немає доступних послуг.';
			}
			?>
		</div>
	</div>
</section>
