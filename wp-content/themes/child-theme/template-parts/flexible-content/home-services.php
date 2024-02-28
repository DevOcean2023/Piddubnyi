<section class="home-services">
	<div class="inner">
		<?php dov_the( 'dov_title_home_services', 'home-services__title' ); ?>
		<div class="home-services__wrapper">
			<?php
			$args         = array(
				'post_type'      => 'services',
				'posts_per_page' => -1,
			);
			$custom_posts = new WP_Query( $args );
			if ( $custom_posts->have_posts() ) :
				while ( $custom_posts->have_posts() ) :
					$custom_posts->the_post();
					?>
					<a class="home-services__permalink" href="#" data-service-id="<?php echo get_the_ID(); ?>">
						<div class="home-services__item">
							<div class="home-services__img">
								<?php dov_the( 'image_white', 'desktop', '60x0' ); ?>
								<?php dov_the( 'image_green', 'mobile', '40x0' ); ?>
							</div>
							<p class="home-services__link"><?php the_title(); ?></p>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>
