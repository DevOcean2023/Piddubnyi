<section class="home-services">
	<div class="inner">
		<h2 class="home-services__title">Наші послуги</h2>
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
					<div class="home-services__item">
						<div class="home-services__img">
							<?php dov_the( 'image_white', 'desktop', '60x0' ); ?>
							<?php dov_the( 'image_green', 'mobile', '40x0' ); ?>
						</div>
						<a class="home-services__link" href="#"><?php the_title(); ?></a>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>
