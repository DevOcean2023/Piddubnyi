<section class="home-products">
	<div class="inner">
		<?php dov_the( 'title', 'home-products__title' ); ?>
		<?php dov_the( 'subtitle', 'home-products__subtitle' ); ?>
		<div class="products__slider_action" data-slider="slider-products">
				<?php
				global $product;
				$args     = array(
					'post_type'      => 'product',
					'posts_per_page' => 10,
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => 'sale',
						),
					),
				);
				$products = new WP_Query( $args );
				?>
				<?php if ( $products->have_posts() ) : ?>
					<?php while ( $products->have_posts() ) : ?>
						<?php $products->the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php echo 'Товари не найдено'; ?>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<div class="home-products__button">
			<?php dov_the( 'button', 'btn' ); ?>
		</div>
	</div>
</section>
