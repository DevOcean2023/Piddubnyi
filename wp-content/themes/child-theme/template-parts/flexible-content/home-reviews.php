<section class="home-reviews">
	<div class="inner">
		<?php dov_the( 'dov_title_testimonials', 'home-reviews__title' ); ?>
		<?php while ( dov_loop( 'dov_testimonials_relationship', '<div class="slider-reviews" data-slider="slider-reviews">' ) ) : ?>
		<div>
				<div class="home-reviews__item">
					<div class="home-reviews__item_text">
						<?php $stars = (int) get_field( 'stars' ); ?>
						<?php if ( $stars ) : ?>
							<div class="home-reviews__item_text_stars">
								<?php for ( $i = 1; $i <= $stars; $i++ ) : ?>
									<span class="active"></span>
								<?php endfor; ?>
							</div>
						<?php endif; ?>
						<?php the_content(); ?>
						<h3><?php the_title(); ?></h3>
					</div>
				</div>
		</div>
	<?php endwhile; ?>
</section>
