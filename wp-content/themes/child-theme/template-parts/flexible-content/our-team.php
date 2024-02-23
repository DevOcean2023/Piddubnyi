<section class="our-team">
	<?php dov_the( 'image_left', '273x0' ); ?>
	<div class="inner">
		<div class="our-team__top">
			<?php dov_the( 'title', 'our-team__title' ); ?>
			<?php dov_the( 'text', '<p class="our-team__text">' ); ?>
		</div>
		<div class="our-team__bottom">
			<?php while ( dov_loop( 'items', '<div class="our-team__slider" data-slider="our-team__slider">' ) ) : ?>
				<div class="our-team__slider-item">
					<?php dov_the( 'image', '270x0' ); ?>
					<?php dov_the( 'title', 'our-team__slider-item-title' ); ?>
					<?php dov_the( 'position', '<p class="our-team__slider-item-position">' ); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
