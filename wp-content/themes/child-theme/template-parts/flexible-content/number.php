<section class="number">
	<div class="inner">
		<?php while ( dov_loop( 'items', '<div class="number__wrap">' ) ) : ?>
			<div class="number__item">
				<?php $class = dov_get( 'class' ); ?>
				<?php if ( 'number__title' === $class ) : ?>
					<?php dov_the( 'title', 'number__title' ); ?>
				<?php endif; ?>

				<?php $class = dov_get( 'class' ); ?>
				<?php if ( 'number__image' === $class ) : ?>
						<?php dov_the( 'image', '56x0', 'number__image' ); ?>
				<?php endif; ?>
				<?php dov_the( 'info_text', 'number__subtitle' ); ?>
			</div>
	<?php endwhile; ?>
</section>
