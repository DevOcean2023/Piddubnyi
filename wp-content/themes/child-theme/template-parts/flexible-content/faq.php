<section class="faq">
	<div class="inner">
		<?php
		$faq_posts = get_posts(
			array(
				'numberposts' => - 1,
				'post_type'   => 'faq',
			),
		);
		$i         = 1;
		?>
		<?php while ( dov_loop( $faq_posts ) ) : ?>
			<div class="accordion" id="accordion-<?php echo esc_attr( $i ); ?>">
				<h2 class="accordion__header">
					<button class="accordion__trigger" id="accordion-btn-<?php echo esc_attr( $i ); ?>" type="button"
							aria-expanded="false"
							aria-controls="accordion-panel-<?php echo esc_attr( $i ); ?>">
						<span class="accordion__title"><?php the_title(); ?></span>
					</button>
				</h2>
				<div class="accordion__panel" id="accordion-panel-<?php echo esc_attr( $i ); ?>" role="region"
					 aria-labelledby="accordion-btn-<?php echo esc_attr( $i ); ?>"
					 hidden="">
					<?php the_content(); ?>
				</div>
			</div>
			<?php ++$i; ?>
		<?php endwhile; ?>
	</div>
</section>
