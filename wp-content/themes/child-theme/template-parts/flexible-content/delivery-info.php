<section class="delivery-info">
	<div class="inner">
		<?php while ( dov_loop( 'items' ) ) : ?>
			<div class="delivery-info__item">
				<?php dov_the( 'title', 'delivery-info__title' ); ?>
				<?php while ( dov_loop( 'items-info' ) ) : ?>
					<div class="delivery-info__block">
						<?php dov_the( 'text' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
