<section class="home-products">
	<div class="inner">
		<?php dov_the( 'title', 'home-products__title' ); ?>
		<?php dov_the( 'subtitle', 'home-products__subtitle' ); ?>
		<div class="products__slider_action" data-slider="slider-products">
			<?php
			global $product;
			$args     = array(
				'limit'    => - 1,
				'status'   => 'publish',
				'return'   => 'ids',
				'category' => array( 'top' ),
			);
			$products = wc_get_products( $args );
			?>
			<?php foreach ( $products as $_prod ) : ?>

				<?php
				$post_object = get_post( $_prod );

				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>
		</div>
		<div class="home-products__button">
			<?php dov_the( 'button', 'btn' ); ?>
		</div>
	</div>
</section>
