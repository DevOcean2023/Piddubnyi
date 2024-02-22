<section class="brands">
	<div class="inner">
		<?php dov_the( 'title', 'brands__title' ); ?>
		<?php while ( dov_loop( 'items', '<div class="brands__items">' ) ) : ?>
			<div class="brands__item">
				<?php while ( dov_wrap( 'link' ) ) : ?>
					<?php dov_the( 'image', '177x0' ); ?>
					<?php dov_the( 'title', 'brands__item-title' ); ?>
				<?php endwhile; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
